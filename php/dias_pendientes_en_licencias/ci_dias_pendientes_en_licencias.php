<?php
class ci_dias_pendientes_en_licencias extends comision_ci
{
	protected $s__datos_filtro;


	//---- Filtro -----------------------------------------------------------------------

	function conf__filtro(toba_ei_formulario $filtro)
	{
		if (isset($this->s__datos_filtro)) {
			$filtro->set_datos($this->s__datos_filtro);
		}
	}

	function evt__filtro__filtrar($datos)
	{
		$this->s__datos_filtro = $datos;
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__datos_filtro);
	}

	//---- Cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		
		if (isset($this->s__datos_filtro)) {

				$legajo = $this->s__datos_filtro['legajo'];
				$anio = date(Y);
				$sql= "SELECT a.legajo, descripcion motivo, apellido ||', '|| nombre as ayp, dias dias_tomados,fecha_inicio_licencia  FROM reloj.parte a
						INNER JOIN reloj.motivo c on a.id_motivo = c.id_motivo
						WHERE EXTRACT(YEAR FROM fecha_inicio_licencia) = $anio
						AND legajo = $legajo ";
				$dias = toba::db('comision')->consultar($sql);
				$cuadro->set_datos($dias);
		}
		/*if (isset($this->s__datos_filtro)) {
			$cuadro->set_datos($this->dep('datos')->tabla('vacaciones_restantes')->get_listado($this->s__datos_filtro));
		} else {
			$cuadro->set_datos($this->dep('datos')->tabla('vacaciones_restantes')->get_listado());
		}*/
	}

	function evt__cuadro__seleccion($datos)
	{
		$this->dep('datos')->cargar($datos);
	}

	//---- Formulario -------------------------------------------------------------------

	/*function conf__formulario(toba_ei_formulario $form)
	{
		if ($this->dep('datos')->esta_cargada()) {
			$form->set_datos($this->dep('datos')->tabla('vacaciones_restantes')->get());
		}
	}

	function evt__formulario__alta($datos)
	{
		$this->dep('datos')->tabla('vacaciones_restantes')->set($datos);
		$this->dep('datos')->sincronizar();
		$this->resetear();
	}

	function evt__formulario__modificacion($datos)
	{
		$this->dep('datos')->tabla('vacaciones_restantes')->set($datos);
		$this->dep('datos')->sincronizar();
		$this->resetear();
	}

	function evt__formulario__baja()
	{
		$this->dep('datos')->eliminar_todo();
		$this->resetear();
	}

	function evt__formulario__cancelar()
	{
		$this->resetear();
	}

	function resetear()
	{
		$this->dep('datos')->resetear();
	}*/

}

?>