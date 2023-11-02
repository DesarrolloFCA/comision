<?php
class dt_departamentos extends comision_datos_tabla
{
		function get_descripciones()
		{
			$sql = "SELECT id_departamento, departamento FROM departamentos ORDER BY departamento";
			return toba::db('comision')->consultar($sql);
		}






}
?>