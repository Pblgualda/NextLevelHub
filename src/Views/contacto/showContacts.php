<?php
$numero_elementos_pagina=2;
$pagination = new Zebra_Pagination();

$pagination->records(count($contactos));

$pagination->records_per_page($numero_elementos_pagina);

$contactos = array_slice(
    $contactos,
    (($pagination->get_page() -1)* $numero_elementos_pagina),
    $numero_elementos_pagina
);

?>

<h2>Contactos</h2>

<a href="<?=BASE_URL?>Contacto/nuevoContacto/">NuevoContacto</a>

    <table class="contacts">
        <thead>
        <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Apellidos</th>
            <th scope="col">Telefono</th>
            <th scope="col">Correo</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($contactos as $contacto): ?>
                <tr>
                    <td><?=$contacto->getNombre()?></td>
                    <td><?=$contacto->getApellido()?></td>
                    <td><?=$contacto->getTelefono()?></td>
                    <td><?=$contacto->getCorreo()?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php

$pagination->render();?>
