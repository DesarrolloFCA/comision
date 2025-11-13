<?php
include("usuario_logueado.php");
$legajo = usuario_logueado::get_legajo(toba::usuario()->get_id());
$legajo = $legajo[0]['legajo'];
$sql = "SELECT count(*) as cant_doc from reloj.agentes
        where escalafon = 'DOCE'
        and legajo = $legajo ";
$cantidad = toba::db('comision')->consultar_fila($sql);

if ($cantidad['cant_doc'] > 0){
$url = 'http://localhost:7008/ctrl_asis/1.0';
echo '<a href="' . $url . '" target="_blank">Ir al sistema de asistencia</a>';
} else {
    echo 'Ud no es personal DOCENTE';
}
?>

