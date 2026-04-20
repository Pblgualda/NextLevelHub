<?php
$numero_elementos_pagina = 10;
$pagination = new Zebra_Pagination();
$pagination -> records(count($contactos));
$pagination -> records_per_page($numero_elementos_pagina);
$contactos = array_slice(
    $contactos,
    (($pagination->get_page() - 1) * $numero_elementos_pagina),
    $numero_elementos_pagina
);

?>

<h2>Contactos</h2>

<table class="contactos">

</table>


