<?php

namespace NextLevelHub\Services;

use NextLevelHub\Models\Pedido;
use NextLevelHub\Models\Usuario;
use RuntimeException;

class FacturaPdfService
{
    private const PAGE_WIDTH = 595;
    private const PAGE_HEIGHT = 842;
    private const FONT_SIZE = 10;
    private const ROWS_PER_PAGE = 23;

    public function generate(Pedido $pedido, array $items, ?Usuario $usuario = null): array
    {
        $pedidoId = $pedido->getId();
        if ($pedidoId === null || $pedidoId <= 0) {
            throw new RuntimeException('No se puede generar la factura sin numero de pedido.');
        }

        $directory = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'facturas';
        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException('No se pudo crear el directorio de facturas.');
        }

        $fileName = sprintf('factura-pedido-%d.pdf', $pedidoId);
        $path = $directory . DIRECTORY_SEPARATOR . $fileName;

        $pdf = $this->buildPdf($pedido, $items, $usuario);
        if (file_put_contents($path, $pdf) === false) {
            throw new RuntimeException('No se pudo guardar la factura PDF.');
        }

        return [
            'path' => $path,
            'url' => BASE_URL . 'facturas/' . $fileName,
            'fileName' => $fileName,
        ];
    }

    private function buildPdf(Pedido $pedido, array $items, ?Usuario $usuario): string
    {
        $pagesItems = array_chunk($items, self::ROWS_PER_PAGE);
        if (empty($pagesItems)) {
            $pagesItems = [[]];
        }

        $pageContents = [];
        $totalPages = count($pagesItems);
        foreach ($pagesItems as $index => $pageItems) {
            $pageContents[] = $this->buildPageContent($pedido, $pageItems, $usuario, $index + 1, $totalPages);
        }

        $objectCount = 3 + (count($pageContents) * 2);
        $fontObject = $objectCount;
        $objects = [];
        $kids = [];

        foreach ($pageContents as $index => $content) {
            $pageObject = 3 + ($index * 2);
            $contentObject = $pageObject + 1;
            $kids[] = $pageObject . ' 0 R';

            $objects[$pageObject] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 " . self::PAGE_WIDTH . ' ' . self::PAGE_HEIGHT . "] /Resources << /Font << /F1 {$fontObject} 0 R >> >> /Contents {$contentObject} 0 R >>";
            $objects[$contentObject] = "<< /Length " . strlen($content) . " >>\nstream\n{$content}endstream";
        }

        $objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[2] = '<< /Type /Pages /Kids [' . implode(' ', $kids) . '] /Count ' . count($kids) . ' >>';
        $objects[$fontObject] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';

        ksort($objects);

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        for ($i = 1; $i <= $objectCount; $i++) {
            $offsets[$i] = strlen($pdf);
            $pdf .= "{$i} 0 obj\n{$objects[$i]}\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 " . ($objectCount + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= $objectCount; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $pdf .= "trailer\n<< /Size " . ($objectCount + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xrefOffset}\n%%EOF";

        return $pdf;
    }

    private function buildPageContent(Pedido $pedido, array $items, ?Usuario $usuario, int $page, int $totalPages): string
    {
        $content = '';
        $this->text($content, 50, 795, 'NextLevelHub', 20);
        $this->text($content, 390, 797, 'FACTURA', 22);
        $this->text($content, 390, 772, 'Factura: F-' . str_pad((string)$pedido->getId(), 6, '0', STR_PAD_LEFT), 10);
        $this->text($content, 390, 756, 'Fecha: ' . $pedido->getFechaPedido(), 10);

        $this->line($content, 50, 738, 545, 738);

        $cliente = trim(($usuario?->getNombre() ?? '') . ' ' . ($usuario?->getApellidos() ?? ''));
        $this->text($content, 50, 712, 'Cliente', 13);
        $this->text($content, 50, 694, $cliente !== '' ? $cliente : 'Cliente #' . $pedido->getUsuarioId(), self::FONT_SIZE);
        if ($usuario && $usuario->getEmail() !== '') {
            $this->text($content, 50, 678, $usuario->getEmail(), self::FONT_SIZE);
        }
        $this->text($content, 50, 662, 'Direccion: ' . $pedido->getDireccion(), self::FONT_SIZE);
        $this->text($content, 50, 646, $pedido->getLocalidad() . ', ' . $pedido->getProvincia(), self::FONT_SIZE);

        $this->text($content, 50, 607, 'Producto', 10);
        $this->text($content, 300, 607, 'Precio', 10);
        $this->text($content, 380, 607, 'Cantidad', 10);
        $this->text($content, 470, 607, 'Subtotal', 10);
        $this->line($content, 50, 598, 545, 598);

        $y = 578;
        foreach ($items as $item) {
            $nombre = $this->truncate($item['producto']->getNombre(), 42);
            $this->text($content, 50, $y, $nombre, self::FONT_SIZE);
            $this->text($content, 300, $y, $this->money((float)$item['precio']), self::FONT_SIZE);
            $this->text($content, 395, $y, (string)$item['cantidad'], self::FONT_SIZE);
            $this->text($content, 470, $y, $this->money((float)$item['subtotal']), self::FONT_SIZE);
            $y -= 18;
        }

        if ($page === $totalPages) {
            $this->line($content, 350, 150, 545, 150);
            $this->text($content, 370, 130, 'Subtotal:', 10);
            $this->text($content, 470, 130, $this->money($pedido->getSubtotal()), 10);
            $this->text($content, 370, 112, 'IVA 21%:', 10);
            $this->text($content, 470, 112, $this->money($pedido->getImpuestos()), 10);
            $this->text($content, 370, 90, 'Total:', 13);
            $this->text($content, 470, 90, $this->money($pedido->getCosteTotal()), 13);
        } else {
            $this->text($content, 50, 130, 'Continua en la siguiente pagina...', 10);
        }

        $this->text($content, 50, 40, "Pagina {$page} de {$totalPages}", 9);

        return $content;
    }

    private function text(string &$content, int $x, int $y, string $text, int $size): void
    {
        $content .= "BT /F1 {$size} Tf {$x} {$y} Td (" . $this->pdfText($text) . ") Tj ET\n";
    }

    private function line(string &$content, int $x1, int $y1, int $x2, int $y2): void
    {
        $content .= "{$x1} {$y1} m {$x2} {$y2} l S\n";
    }

    private function money(float $amount): string
    {
        return number_format($amount, 2, ',', '.') . ' EUR';
    }

    private function truncate(string $text, int $length): string
    {
        $text = trim($text);
        return strlen($text) > $length ? substr($text, 0, $length - 3) . '...' : $text;
    }

    private function pdfText(string $text): string
    {
        $encoded = iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
        if ($encoded === false) {
            $encoded = preg_replace('/[^\x20-\x7E]/', '', $text) ?? '';
        }

        $encoded = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $encoded);
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $encoded) ?? '';
    }
}
