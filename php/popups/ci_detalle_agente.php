<?php

class ci_detalle_agente extends comision_ci
{
    protected $s__marcas;
    protected $s__agente;
    function conf()
	{

		
		//Primero cargo los parametros recibidos
		$parametros = toba::memoria()->get_parametros();
		$clave_get = toba::memoria()->get_parametro('fila_safe');
        $claves_originales = toba_ei_cuadro::recuperar_clave_fila('24234236000002', $clave_get);
       
        $legajo=$claves_originales['legajo'];
        $sql= "SELECT DISTINCT legajo, apellido, nombre from reloj.agentes
        where legajo = $legajo";
        $agente = toba::db('comision')->consultar_fila($sql);
        $this->s__agente =$agente;
        $sql = "SELECT Distinct  dia,fecha,hora_entrada,hora_salida,horas_trabajadas,horas_requeridad,descripcion,estado 
		        from reloj.vm_detalle_pres
		        where legajo = $legajo
		        and fecha >= CURRENT_DATE - INTERVAL '30 days'
		        order by fecha Asc";
        $datos = toba::db('comision')->consultar($sql);
        
        $this->s__marcas = $datos;
       
        
         
    }
    function conf__cuadro(comision_ei_cuadro $cuadro)
	{
       // ei_arbol($this->s__marcas);
        if(isset($this->s__marcas)){
			$cuadro->set_datos($this->s__marcas);
        }
    }
    function conf__formulario(toba_ei_formulario $form)
	{ 
        
        if (isset($this->s__agente)){
            $form->set_datos($this->s__agente);
        }
    }


}
?>
