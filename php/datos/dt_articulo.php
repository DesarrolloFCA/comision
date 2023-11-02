<?php
class dt_articulo extends comision_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_articulo, descripcion FROM articulo ORDER BY descripcion";
		return toba::db('comision')->consultar($sql);
	}

}

?>