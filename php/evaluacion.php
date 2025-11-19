<?php
include("usuario_logueado.php");
$legajo = usuario_logueado::get_legajo(toba::usuario()->get_id());
$legajo = $legajo[0]['legajo'];
$sql = "SELECT count(*) as cant_doc from reloj.agentes
        where escalafon = 'DOCE'
        and legajo = $legajo ";
$cantidad = toba::db('comision')->consultar_fila($sql);

// if ($cantidad['cant_doc'] > 0){
// $url = 'https://idd.fca.uncu.edu.ar/pruebas/';
// echo '<a href="' . $url . '" target="_blank">Sistema de Informe de Labor Docente</a>';
// } else {
//     echo 'Ud no es personal DOCENTE';
// }
// 

// Abre la URL en una nueva pestaña si es docente
if ($cantidad['cant_doc'] > 0) {
    $url = 'http://localhost:7008/pruebas/1.0/';
    echo "<script>window.open('$url', '_blank');</script>";
    // Opcional: redirigir a otra página o cerrar la actual
    // echo "<script>window.location.href = 'otra_pagina.php';</script>";
    exit;
} else {
    echo 'Ud no es personal DOCENTE';
}


?>
