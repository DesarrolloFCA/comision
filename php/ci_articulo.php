<?php
class ci_articulo extends comision_ci
{
	protected $s__datos;
	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	public function evt__formulario__alta($datos)
	{

		$datos['anio'] = date('Y');

		$bandera_nodo = true;
		$legajo = $datos['legajo'];
		$id_catedra = $datos['catedra'];
		$anio = $datos['anio'];
		$id_motivo = $datos['id_motivo'];
		//ei_arbol($anio);
		if ($datos['dias'] > 0) {

			if ($id_motivo == 35 or $id_motivo == 57 or $id_motivo == 55) {
				$sql = "SELECT count(*) cantidad from reloj.inasistencias
			WHERE legajo = $legajo
			AND id_catedra=$id_catedra
			AND anio =$anio
			AND id_motivo =35 ";
				$hay_vacaciones = toba::db('comision')->consultar($sql);
				$ya_tomo = $hay_vacaciones[0]['cantidad'];

				$sql = "SELECT nombre_catedra, id_departamento FROM reloj.catedras
				WHERE id_catedra = $id_catedra";
				$depto = toba::db('comision')->consultar($sql);
				if ($depto[0]['id_departamento'] < 10 or ($depto[0]['id_departamento'] == 12)) {
					$sql = "SELECT t_l.legajo, t_l.apellido, t_l.nombre, t_l.fec_nacim, t_l.dni, t_l.fec_ingreso, t_l.estado_civil,
						t_l.caracter, t_l.categoria, t_l.agrupamiento, t_l.escalafon,
						t_l.fec_nacim as fecha_nacimiento, t_l.cuil,
						t_d.pais, t_d.provincia, t_d.codigo_postal, t_d.localidad, t_d.codc_cara_manzana,
						t_d.zona_paraje_barrio, t_d.calle, t_d.numero, t_d.piso, t_d.dpto_oficina, t_d.telefono, t_l.tipo_sexo,
						t_d.telefono_celular
						FROM uncu.legajo  as t_l LEFT JOIN uncu.domicilio as t_d
						ON t_l.legajo = t_d.legajo
						WHERE t_l.legajo = $legajo
						AND cod_depcia = '04'
						AND escalafon<> 'NODO'";
				} else {
					$sql = "SELECT t_l.legajo, t_l.apellido, t_l.nombre, t_l.fec_nacim, t_l.dni, t_l.fec_ingreso, t_l.estado_civil,
						t_l.caracter, t_l.categoria, t_l.agrupamiento, t_l.escalafon,
						t_l.fec_nacim as fecha_nacimiento, t_l.cuil,
						t_d.pais, t_d.provincia, t_d.codigo_postal, t_d.localidad, t_d.codc_cara_manzana,
						t_d.zona_paraje_barrio, t_d.calle, t_d.numero, t_d.piso, t_d.dpto_oficina, t_d.telefono, t_l.tipo_sexo,
						t_d.telefono_celular
						FROM uncu.legajo  as t_l LEFT JOIN uncu.domicilio as t_d
						ON t_l.legajo = t_d.legajo
						WHERE t_l.legajo = $legajo
						AND cod_depcia = '04'
						AND escalafon in ('NODO','AUTO') ";
				}

			} else {

				$ya_tomo = 0;
				$sql = "SELECT nombre_catedra, id_departamento FROM reloj.catedras
				WHERE id_catedra = $id_catedra";
				$depto = toba::db('comision')->consultar($sql);
				if ($depto[0]['id_departamento'] < 10 or ($depto[0]['id_departamento'] == 12)) {
					$sql = "SELECT t_l.legajo, t_l.apellido, t_l.nombre, t_l.fec_nacim, t_l.dni, t_l.fec_ingreso, t_l.estado_civil,
						t_l.caracter, t_l.categoria, t_l.agrupamiento, t_l.escalafon,
						t_l.fec_nacim as fecha_nacimiento, t_l.cuil,
						t_d.pais, t_d.provincia, t_d.codigo_postal, t_d.localidad, t_d.codc_cara_manzana,
						t_d.zona_paraje_barrio, t_d.calle, t_d.numero, t_d.piso, t_d.dpto_oficina, t_d.telefono, t_l.tipo_sexo,
						t_d.telefono_celular
						FROM uncu.legajo  as t_l LEFT JOIN uncu.domicilio as t_d
						ON t_l.legajo = t_d.legajo
						WHERE t_l.legajo = $legajo
						AND cod_depcia = '04'
						AND escalafon<> 'NODO'";
				} else {
					$sql = "SELECT t_l.legajo, t_l.apellido, t_l.nombre, t_l.fec_nacim, t_l.dni, t_l.fec_ingreso, t_l.estado_civil,
						t_l.caracter, t_l.categoria, t_l.agrupamiento, t_l.escalafon,
						t_l.fec_nacim as fecha_nacimiento, t_l.cuil,
						t_d.pais, t_d.provincia, t_d.codigo_postal, t_d.localidad, t_d.codc_cara_manzana,
						t_d.zona_paraje_barrio, t_d.calle, t_d.numero, t_d.piso, t_d.dpto_oficina, t_d.telefono, t_l.tipo_sexo,
						t_d.telefono_celular
						FROM uncu.legajo  as t_l LEFT JOIN uncu.domicilio as t_d
						ON t_l.legajo = t_d.legajo
						WHERE t_l.legajo = $legajo
						AND cod_depcia = '04'
						AND escalafon in ('NODO','AUTO') ";
				}

			}

			$agente = toba::db('mapuche')->consultar($sql);

			$cant = count($agente);
			//ei_arbol($agente);
			$sql = "SELECT MIN(fec_ingreso) fecha from uncu.legajo
		where legajo = $legajo";
			$fec_ingreso = toba::db('mapuche')->consultar($sql);
			$res = $fec_ingreso[0]['fecha'];
			list($y, $m, $d) = explode("-", $res); //2011-03-31
			$fecha = $d . "-" . $m . "-" . $y;
			$dias = explode('-', $fecha, 3);
			$dias = mktime(0, 0, 0, $dias[1], $dias[0], $dias[2]);
			$antiguedad = ((time() - $dias) / 31556926);
			//(int)((time()-$dias)/31556926 );
			//ei_arbol($antiguedad);
			if ($antiguedad == 0) {
				$prop_vaca = (int) ((time() - $dias) / 1729147);
			}
			$sql = "SELECT fecha_ingreso from reloj.antiguedad
			WHERE legajo = $legajo";
			$fec_ingreso = toba::db('comision')->consultar($sql);
			// ei_arbol($sql);
			if (isset($fec_ingreso[0]['fecha_ingreso'])) {
				$fec = $fec_ingreso[0]['fecha_ingreso'];
				list($y, $m, $d) = explode("-", $fec); //2011-03-31
				$fecha = $d . "-" . $m . "-" . $y;
				$dias = explode('-', $fecha, 3);
				$dias = mktime(0, 0, 0, $dias[1], $dias[0], $dias[2]);
				$antiguedad = ((time() - $dias) / 31556926);
				// (int)((time()-$dias)/31556926 );
			}
			// ei_arbol($antiguedad);

			$dias = $datos['dias'];
			$anio = $datos['anio'];
			if ($id_motivo == 57) {
				$sql = "SELECT sum(dias) dias_restantes FROM reloj.vacaciones_restantes
				WHERE legajo = $legajo
				AND anio <= $anio";
			} else if ($id_motivo == 35) {
				$sql = "SELECT sum(dias) dias_restantes FROM reloj.vacaciones_restantes
			WHERE legajo = $legajo
			AND anio < $anio";
			}
			$temp = toba::db('comision')->consultar($sql);
			/*$sql = "SELECT dias_adelanto FROM reloj.vacaciones_adelantadas
			where legajo = $legajo and anio = $anio; ";
			$ade = toba::db('comision')->consultar($sql);
			$adelanto = $ade[0]['dias_adelanto'];*/
			// ei_arbol($temp);
			if (isset($temp)) {
				$dias_restantes = $temp['dias_restantes'];
			} else {
				$dias_restantes = 0;
			}
			$fecha_inicio_licencia = $datos['fecha_inicio_licencia'];
			$fechaentera1 = strtotime($fecha_inicio_licencia);
			$fecha_inicio = date_create(date("Y-m-d", $fechaentera1));
			$hoy = date_create(date("Y-m-d"));
			$diferencia = date_diff($fecha_inicio, $hoy);
			$y = date("Y", $fechaentera1);
			$m = date("m", $fechaentera1);

			$agrupamiento = $agente[0]['escalafon'];
			$agrego = 0;
			$sql = "SELECT sum(dias) dias_restantes FROM reloj.vacaciones_restantes
		WHERE legajo = $legajo
		AND anio = $anio";
			$vac_pen = toba::db('comision')->consultar($sql);
			$insertadas = count($vac_pen);
			//  ei_arbol($insertadas);

			for ($i = 0; $i < $cant; $i++) {

				if ($agente[$i]['escalafon'] == 'NODO') {

					if ($id_motivo == 30) { //Razones Particulares

						if (date("Y") == $anio) {
							if ($dias <= 2) {
								$agente[$i]['articulo'] = null;
								$agente[$i]['id_decreto'] = 4;

								$sql = "SELECT -SUM(dias) dias_restantes
									FROM reloj.parte
									WHERE legajo = $legajo    AND id_motivo = 30    AND  DATE_PART('month', fecha_inicio_licencia) = $m
									and DATE_PART('year', fecha_inicio_licencia) = $anio";
								$parte = toba::db('comision')->consultar($sql);
								//ei_arbol($parte);
								$sql = "SELECT fecha_inicio, fecha_fin
								FROM reloj.inasistencias
								Where legajo = $legajo AND id_motivo=30 AND extract (month from fecha_inicio)=$m And extract(year from fecha_inicio) = $anio";

								$pendiente = toba::db('comision')->consultar($sql);
								//ei_arbol($pendiente);
								$lim = count($pendiente);
								$dias_tomados = 0;
								for ($i = 0; $i < $lim; $i++) {
									$fecha_inicio = $pendiente[$i]['fecha_inicio'];
									$fechaentera1 = strtotime($fecha_inicio);
									$fecha_inicio = date_create(date("Y-m-d", $fechaentera1));
									$fecha_fin = $pendiente[$i]['fecha_fin'];
									$fechaentera1 = strtotime($fecha_fin);
									$fecha_fin = date_create(date("Y-m-d", $fechaentera1));
									$diferencia = date_diff($fecha_inicio, $fecha_fin);
									$dias_tomados = $dias_tomados + $diferencia;
									//ei_arbol($dias_tomados);
								}

								if ($parte[0]['dias_restantes'] == null) {
									$parte[0]['dias_restantes'] = 0;
								}

								$temp[0]['dias_restantes'] = $parte[0]['dias_restantes'] + $dias_tomados + $dias;
								//ei_arbol($temp);
								if (!is_null($temp) && ($temp[0]['dias_restantes'] > 0 && $temp[0]['dias_restantes'] <= 2)) {
									$sql = "SELECT -SUM(dias) +6 dias_restantes
									FROM reloj.parte
									WHERE legajo = $legajo
									AND id_motivo = 30
									AND  DATE_PART('year', fecha_inicio_licencia) = $y";
									$temp = toba::db('comision')->consultar($sql);
									$bandera = false;

									//ei_arbol($temp);
									if (is_null($temp[0]['dias_restantes']) || ($temp[0]['dias_restantes'] >= 0 && $temp[0]['dias_restantes'] <= 2)) {
										$agente[$i]['articulo'] = 40;
										$bandera = true;
										//ei_arbol($agente);

									}
									if (!is_null($temp[0]['dias_restantes']) && !($temp[0]['dias_restantes'] > 0 && $temp[0]['dias_restantes'] < 6)) {
										ei_arbol($temp);
										toba::notificacion()->agregar('1 - Ud ha excedido la cantidad anual de razones particulares este a�o cuenta con ' . $temp[0]['dias_restantes'] . ' d�as', "info");
										$bandera = false;
									}
								} else if ($temp[0]['dias_restantes'] <= 0 || $temp[0]['dias_restantes'] > 2) {

									//ei_arbol($agente);
									$temp[0]['dias_restantes'] = $parte[0]['dias_restantes'] + $dias_tomados - 2;
									if ($temp[0]['dias_restantes'] >= 0) {
										$temp[0]['dias_restantes'] = 0;
									} else {
										$temp[0]['dias_restantes'] = abs($temp[0]['dias_restantes']);
									}

									toba::notificacion()->agregar('2 - Ud ha excedido la cantidad mensual de razones particulares este mes cuenta con ' . $temp[0]['dias_restantes'] . ' d�as', "info");

									$bandera_nodo = false;

								}

							} else {
								toba::notificacion()->agregar('3 - Ud ha excedido la cantidad de dias recuerde que las razones particulares son entre 1 y 2 d�as', "info");
								$bandera_nodo = false;
							}
							//ei_arbol($agente);
						} else {
							toba::notificacion()->agregar('Introduzca el corriente a�o. Gracias ', "info");

							$bandera_nodo = false;

						}

					} elseif ($id_motivo == 35) {

						//ei_arbol($agente);
						$agente[$i]['articulo'] = 0;
						$agente[$i]['id_decreto'] = 4;
						//ei_arbol ($agente);
						/*$sql= "SELECT count(*) tiene from reloj.vacaciones_restantes
						WHERE legajo = $legajo AND anio= $anio;";
						$resto = toba::db('comision')->consultar($sql);*/
						if ($resto[0]['tiene'] > 0) {
							$hay_cargadas = 1;
						} else {
							$hay_cargadas = 0;
						}
						if ($antiguedad > 20) {
							$dias_totales = 40 + $dias_restantes - $adelanto;
							//     ei_arbol ($dias);
							if ($dias < 30) {
								toba::notificacion()->agregar('Los días de vacaciones no pueden ser menores de 30 d�as', "info");
								break;
							} else {

								if ($agrego == 0 and $insertadas > 0 and $hay_cargadas == 0) {
									$dias_restantes = $dias_totales - $dias;
									//ei_arbol ($dias_restantes);

									$sql = "INSERT INTO reloj.vacaciones_restantes(
										legajo, cod_depcia, agrupamiento, anio, dias)
										VALUES ($legajo,4, '$agrupamiento', $anio, $dias_restantes);
										DELETE FROM reloj.vacaciones_restantes
										where legajo=$legajo
										AND anio < $anio;";
									toba::db('comision')->ejecutar($sql);
									$agrego = 1;
								}

							}

						} elseif ($antiguedad > 15 && $antiguedad <= 20) {
							$dias_totales = 35 + $dias_restantes - $adelanto;

							if ($dias < 30) {
								toba::notificacion()->agregar('Los días de vacaciones no pueden ser menores de 30 d�as', "info");
								$bandera_nodo = false;
								break;

							} else {
								if ($agrego == 0 and $insertadas > 0 and $hay_cargadas == 0) {
									$dias_restantes = $dias_totales - $dias;
									if ($dias_restantes > 0) {
										$sql = "INSERT INTO reloj.vacaciones_restantes(
										legajo, cod_depcia, agrupamiento, anio, dias)
										VALUES ($legajo,4, '$agrupamiento', $anio, $dias_restantes);
										DELETE FROM reloj.vacaciones_restantes
										where legajo=$legajo
										AND anio < $anio;";
										toba::db('comision')->ejecutar($sql);
										$agrego = 1;
									}
								}

							}

						} elseif ($antiguedad > 10 && $antiguedad <= 15) {
							$dias_totales = 30 + $dias_restantes - $adelanto;
							if ($dias < 30) {
								toba::notificacion()->agregar('Los días de vacaciones no pueden ser menores de 30 d�as', "info");
								$bandera_nodo = false;
								break;
							} else {
								$dias_restantes = 0;
								if ($agrego == 0 and $insertadas > 0 and $hay_cargadas == 0) {
									$dias_restantes = $dias_totales - $dias;
									if ($dias_restantes > 0) {
										$sql = "INSERT INTO reloj.vacaciones_restantes(
											legajo, cod_depcia, agrupamiento, anio, dias)
											VALUES ($legajo,4, '$agrupamiento', $anio, $dias_restantes);
											DELETE FROM reloj.vacaciones_restantes
											where legajo=$legajo
											AND anio < $anio;";
										toba::db('comision')->ejecutar($sql);
										$agrego = 1;
									}
								}
							}
						} elseif ($antiguedad > 5 && $antiguedad <= 10) {
							$dias_totales = 25 + $dias_restantes - $adelanto;
							$dias_restantes = 0;
						} elseif ($antiguedad > 0.5 && $antiguedad <= 5) {
							$dias_totales = 20 + $dias_restantes - $adelanto;
							$dias_restantes = 0;
						} else {
							$dias_totales = $prop_vaca;
							$dias_restantes = 0;
						}

						if ($dias > $dias_totales) {
							toba::notificacion()->agregar('Los días de vacaciones tienen que ser menores o iguales a ' . $dias_totales . ' d�as.', "info");

							$bandera_nodo = false;

						} else {
							$agente[$i]['articulo'] = 55;
							$bandera = true;
						}
						/// Vacaciones Pendientes no docente

					} else if ($id_motivo == 57) {
						$agente[$i]['articulo'] = null;
						$agente[$i]['id_decreto'] = 4;
						$datos['anio'] = $anio - 1;
						//ei_arbol ($agente);
						$sql = "SELECT sum(dias)  dias_rest from reloj.vacaciones_restantes
									WHERE legajo = $legajo AND anio <= $anio;";

						$resto = toba::db('comision')->consultar($sql);
						$dias_pendientes = $resto[0]['dias_rest'];
						//ei_arbol($resto);
						if ($dias <= $dias_pendientes) {
							/*toba::notificacion()->agregar('Usted cuenta con  '.$dias_pendientes .' días pendientes del año'.$anio. ' coloque una cantidad de dias validos.', "info");*/
							$agente[$i]['articulo'] = 55;
							$bandera = true;

						} else {
							toba::notificacion()->agregar('Usted cuenta con  ' . $dias_pendientes . ' d�as, coloque una cantidad de d�as validos.', "info");
							$bandera = false;
						}
								// Adelanto de Vacaciones
					}         /* else if ($id_motivo == 55){
							$agente[$i]['id_decreto'] = 4;
							if ($anio != date("Y")) {
							toba::notificacion()->agregar('Para pedir Adelanto de vacaciones recuerde colocar el a�o en curso', "info");
							$bandera = false;

							} else {
							*$sql = "SELECT dias_adelanto FROM reloj.vacaciones_adelantadas
							where legajo = $legajo and anio = $anio; ";
							$ade = toba::db('comision')->consultar($sql);
							$adelanto = $ade[0]['dias_adelanto'];
							$bandera = true;

							if ($antiguedad > 20){
							$dias_vacaciones = 40 - $adelanto ;

							} elseif ($antiguedad > 15 && $antiguedad <=20) {
							$dias_vacaciones = 35 - $adelanto;

							} elseif($antiguedad > 10 && $antiguedad <=15) {
							$dias_vacaciones = 30 - $adelanto;

							}elseif ($antiguedad > 5 && $antiguedad <=10) {
							$dias_vacaciones = 25 - $adelanto;

							}elseif ($antiguedad > 0.5 && $antiguedad <=5){
							$dias_vacaciones = 20 - $adelanto;

							} else {
							$bandera = false;
							toba::notificacion()->agregar('Ud. no cuenta con la antig�edad suficiente para solicitar Adelanto de Vacaciones', "info");

							}
							if ($bandera){
							if ($dias <= $dias_vacaciones){
							$agente[$i]['articulo'] = 55;
							$dias_adelantados = $adelanto + $dias;

							} else {
							toba::notificacion()->agregar('Ud.  cuenta con '. $dias_vacaciones. ' por favor corrija los dias para solicitar Adelanto de Vacaciones', "info");
							$bandera = false;

							}

							}

							}
							}*/

				} else {

					if ($id_motivo == 30) {
						//ei_arbol($i);
						if (date("Y") == $anio) {
							if ($dias <= 2) {
								for ($j = 0; $j < $cant; $j++) {
									$agente[$j]['articulo'] = 0;
									$agente[$j]['id_decreto'] = 8;
								}

								$sql = "SELECT -SUM(dias) +2 dias_restantes
									FROM reloj.parte
									WHERE legajo = $legajo
									AND id_motivo = 30
									AND  DATE_PART('month', fecha_inicio_licencia) = $m";
								$parte = toba::db('comision')->consultar($sql);
								/*$sql = "SELECT fecha_inicio, fecha_fin*/
								$sql = "SELECT  fecha_fin - fecha_inicio + 1 dias_rp
								FROM reloj.inasistencias
								Where legajo = $legajo AND id_motivo=30 AND extract (month from fecha_inicio)=$m And extract(year from fecha_inicio) = $anio";

								$pendiente = toba::db('comision')->consultar($sql);
								$lim = count($pendiente);
								$dias_tomados = 0;
								for ($i = 0; $i < $lim; $i++) {
									$dias_tomados = $dias_tomados + $pendiente[$i]['dias_rp'];

								}

								$temp[0]['dias_restantes'] = $parte[0]['dias_restantes'] + $dias_tomados + $dias;
								//ei_arbol($temp);
								if (!is_null($temp) && ($temp[0]['dias_restantes'] >= 0 && $temp[0]['dias_restantes'] <= 2)) {
									$sql = "SELECT -SUM(dias) +6 dias_restantes
									FROM reloj.parte
									WHERE legajo = $legajo
									AND id_motivo = 30
									AND  DATE_PART('year', fecha_inicio_licencia) = $y";
									$temp = toba::db('comision')->consultar($sql);
									//ei_arbol($temp);
									if (is_null($temp[0]['dias_restantes']) || ($temp[0]['dias_restantes'] >= 0 && $temp[0]['dias_restantes'] <= 2)) {
										$agente[$i]['articulo'] = 57;
										$bandera = true;
										//ei_arbol($agente);

									}
									if (!is_null($temp[0]['dias_restantes']) && !($temp[0]['dias_restantes'] > 0 && $temp[0]['dias_restantes'] < 6)) {
										toba::notificacion()->agregar('4 - Ud ha excedido la cantidad anual de razones particulares este a�o cuenta con ' . $temp[0]['dias_restantes'] . ' d�as', "info");
									}
								} else if ($temp[0]['dias_restantes'] <= 0 || $temp[0]['dias_restantes'] > 2) {

									//ei_arbol($agente);
									$temp[0]['dias_restantes'] = $parte[0]['dias_restantes'] + $dias_tomados - 2;
									if ($temp[0]['dias_restantes'] >= 0) {
										$temp[0]['dias_restantes'] = 0;
									} else {
										$temp[0]['dias_restantes'] = abs($temp[0]['dias_restantes']);
									}

									toba::notificacion()->agregar('5 - Ud ha excedido la cantidad mensual de razones particulares este mes cuenta con ' . $temp[0]['dias_restantes'] . ' días', "info");
								}

							} else {
								toba::notificacion()->agregar('6 - Ud ha excedido la cantidad de d�as recuerde que las razones particulares son entre 1 y 2 d�as', "info");
							}
						} else {
							toba::notificacion()->agregar('Introduzca el corriente a�o. Gracias ', "info");

							$bandera_nodo = false;

						}

					} elseif ($id_motivo == 35) {

						$agente[$i]['articulo'] = 56;
						$agente[$i]['id_decreto'] = 2;
						$bandera = true;

						if ($antiguedad > 15) {
							$dias_totales = 45 + $dias_restantes;
							if ($dias < 30) {
								toba::notificacion()->agregar('Los días de vacaciones no pueden ser menores de 30 d�as', "info");
								break;
							} else {
								if ($agrego == 0 and $insertadas > 0 and $hay_cargadas == 0) {
									$dias_restantes = $dias_totales - $dias;
									if ($dias_restantes > 0) {
										$sql = "INSERT INTO reloj.vacaciones_restantes(
										legajo, cod_depcia, agrupamiento, anio, dias)
										VALUES ($legajo,4, '$agrupamiento', $anio, $dias_restantes);
										DELETE FROM reloj.vacaciones_restantes
										where legajo=$legajo
										AND anio < $anio;";
										toba::db('comision')->ejecutar($sql);
										$agrego = 1;
									}
								}
							}
						} else {
							$dias_totales = 30 + $dia_restantes;
							if ($dias < 30) {
								toba::notificacion()->agregar('Los días de vacaciones no pueden ser menores de 30 d�as', "info");
								break;
							} else {
								if ($agrego == 0 and $insertadas > 0 and $hay_cargadas == 0) {
									$agrego = 1;
								}
								$dias_restantes = $dias_totales - $dias;
								if ($dias_restantes > 0) {
									$sql = "INSERT INTO reloj.vacaciones_restantes( legajo, cod_depcia, agrupamiento, anio, dias)
										VALUES ($legajo,4, '$agrupamiento', $anio, $dias_restantes);
										DELETE FROM reloj.vacaciones_restantes
										where legajo=$legajo
										AND anio < $anio;";
									toba::db('comision')->ejecutar($sql);
								}
							}

							if ($dias > $dias_totales) {
								toba::notificacion()->agregar('Los días de vacaciones tienen que ser menores o iguales a ' . $dias_totales . ' d�as', "info");
							}

						}
						// VAcaciones pendientes docentes
					} else if ($id_motivo == 57) {
						$agente[$i]['articulo'] = null;
						$agente[$i]['id_decreto'] = 2;
						$datos['anio'] = $anio - 1;

						//ei_arbol ($agente);
						$sql = "SELECT sum(dias)  dias_rest from reloj.vacaciones_restantes
									WHERE legajo = $legajo AND anio <= $anio;";

						$resto = toba::db('comision')->consultar($sql);
						$dias_pendientes = $resto[0]['dias_rest'];
						//ei_arbol($resto);
						if ($dias <= $dias_pendientes) {

							$agente[$i]['articulo'] = 56;
							$bandera = true;
							$datos['dias_restantes'] = $dias_pendientes;
						} else {
							toba::notificacion()->agregar('Usted cuenta con  ' . $dias_pendientes . ' d�as, coloque una cantidad de d�as validos.', "info");
							$bandera = false;
						}
						//adelanto de Vacaciones
					} /*else if ($id_motivo == 55){
				$agente[$i]['id_decreto'] = 2;
				if ($anio != date("Y")) {
				toba::notificacion()->agregar('Para pedir Adelanto de vacaciones recuerde colocar el a�o en curso', "info");
				$bandera = false;

				} else {
				$sql = "SELECT dias_adelanto FROM reloj.vacaciones_adelantadas
				where legajo = $legajo and anio = $anio; ";
				$ade = toba::db('comision')->consultar($sql);
				$adelanto = $ade[0]['dias_adelanto'];
				$bandera = true;

				if ($antiguedad > 20){
				$dias_vacaciones = 40 - $adelanto ;

				} elseif ($antiguedad > 15 && $antiguedad <=20) {
				$dias_vacaciones = 35 - $adelanto;

				} elseif($antiguedad > 10 && $antiguedad <=15) {
				$dias_vacaciones = 30 - $adelanto;

				}elseif ($antiguedad > 5 && $antiguedad <=10) {
				$dias_vacaciones = 25 - $adelanto;

				}elseif ($antiguedad > 0.5 && $antiguedad <=5){
				$dias_vacaciones = 20 - $adelanto;

				} else {
				$bandera = false;
				toba::notificacion()->agregar('Ud. no cuenta con la antig�edad suficiente para solicitar Adelanto de Vacaciones', "info");

				}
				if ($bandera){
				if ($dias <= $dias_vacaciones){
				$agente[$i]['articulo'] = 56;
				$dias_adelantados = $adelanto + $dias;

				} else {
				toba::notificacion()->agregar('Ud.  cuenta con '. $dias_vacaciones. ' por favor corrija los d�as para solicitar Adelanto de Vacaciones', "info");
				$bandera = false;

				}

				}

				}

				}*/
				}

				$edad = $this->dep('mapuche')->get_edad($legajo, null);
				$datos['dias_restantes'] = $dias_restantes;
				//ei_arbol($agente);
				if ($bandera) {
					$fecha_inicio_licencia = $datos['fecha_inicio_licencia'];
					$fechaentera1 = strtotime($fecha_inicio_licencia);
					$fecha = date_create(date("Y-m-d", $fechaentera1));
					//$fecha=date('d/m/Y',strtotime($datos['fecha_inicio_licencia'] ) );

					$fecha_inicio = $fecha->format("Y-m-d");
					$dias = $dias - 1;
					$dias_to = $dias . ' days';
					$hasta = date_add($fecha, date_interval_create_from_date_string($dias_to));
					$hasta = $hasta->format("Y-m-d");

					for ($i = 0; $i < $cant; $i++) {
						$fecha_alta = date("Y-m-d H:i:s");
						$datos['fecha_alta'] = $fecha_alta;
						$usuario_alta = $datos['legajo'];
						$estado = 'A';
						$datos['fecha_inicio'] = $fecha_inicio;
						$datos['hasta'] = $hasta;
						$dias = $datos['dias'];
						$cod_depcia = '04';
						$observaciones = $datos['observaciones'];
						$id_motivo = $datos['id_motivo'];
						$id_decreto = $agente[$i]['id_decreto'];
						$datos['id_decreto'] = $id_decreto;
						$articulo = $agente[$i]['articulo'];
						$datos['articulo'] = $articulo;
						$catedra = $datos['catedra'];
						$anio = $datos['anio'];
						$superior = $datos['superior'];
						$autoridad = $datos['autoridad'];

					}

					if ($datos['fecha_inicio_licencia'] < '2022-12-26') {
						toba::notificacion()->agregar('Ingrese una fecha mayor o igual al 26/12/2022', "info");
					} else {

						if ($ya_tomo == 0) {
							if ($bandera_nodo) {
								if ($id_motivo != 30) {
									$sql = "INSERT INTO reloj.inasistencias( legajo, id_catedra, fecha_inicio, fecha_fin, anio, observaciones, leg_sup, auto_sup, leg_aut, auto_aut, fecha_alta, usuario_alta, estado, id_motivo, id_decreto, id_articulo)    VALUES ( $usuario_alta, $catedra, '$fecha_inicio', '$hasta',$anio, '$observaciones', $superior, true, $autoridad, true, '$fecha_alta',$usuario_alta ,'A', $id_motivo, $id_decreto, $articulo);";
								} else {
									$sql = "INSERT INTO reloj.inasistencias(    legajo, id_catedra, fecha_inicio, fecha_fin, anio, observaciones, leg_sup, auto_sup, leg_aut, auto_aut, fecha_alta, usuario_alta, estado, id_motivo, id_decreto)    VALUES ($usuario_alta, $catedra, '$fecha_inicio', '$hasta',$anio, '$observaciones', $superior, true, $autoridad, true, '$fecha_alta',$usuario_alta ,'A', $id_motivo, $id_decreto);";
								}

/*

$sql = "INSERT INTO reloj.parte(
legajo, edad, fecha_alta, usuario_alta, estado, fecha_inicio_licencia, dias, cod_depcia, domicilio, localidad, agrupamiento, fecha_nacimiento,
apellido, nombre, estado_civil, observaciones, id_decreto, id_motivo, id_articulo, tipo_sexo)
VALUES ($legajo, $edad, '$fecha_alta', $usuario_alta, '$estado', '$fecha_inicio', $dias, '$cod_depcia', '$domicilio', '$localidad', '$agrupamiento', '$fecha_nacimiento',
'$apellido', '$nombre',    '$estado_civil', '$observaciones', $id_decreto, $id_motivo,$articulo,'$tipo_sexo');";*/

								toba::db('comision')->ejecutar($sql);

								/////
								//actualizacion o borrado de vacaciones restantes
								//////
								if ($id_motivo == 57) {

									toba::notificacion()->agregar('Parte de inasistencia agregado correctamente.', 'info');
									if ($dias == $dias_pendientes) {
										$sql1 = "DELETE FROM reloj.vacaciones_restantes
				where legajo = $legajo
				and anio =$anio ";
									} else {
										$dias_pendientes = $dias_pendientes - $dias;
										if ($dias_pendientes > 0) {
											$datos['dias_restantes'] = $dias_pendientes;
											$sql1 = "UPDATE reloj.vacaciones_restantes
					set dias = $dias_pendientes
					where legajo = $legajo
					AND anio=$anio ";
										}
										//toba::notificacion()->agregar('Parte de inasistencia agregado correctamente.', 'info');

									}
									toba::db('comision')->ejecutar($sql1);

									//    toba::notificacion()->agregar('Ud. ya completo el fomulario para '. $depto[0]['nombre_catedra'] , "info");

								} else if ($id_motivo == 55) {
									/// actualizacion e Insersion de adelantos de vacaciones
									if (isset($adelanto)) {
										$sql1 = "UPDATE reloj.vacaciones_adelantadas
				SET dias_adelanto = $dias_adelantados
				WHERE legajo =$legajo and anio = $anio;";
									} else {
										$sql1 = "INSERT INTO reloj.vacaciones_adelantadas (legajo,anio,dias_adelanto)
				VALUES ($legajo, $anio,$dias_adelantados);";
									}
									toba::db('comision')->ejecutar($sql1);
									toba::notificacion()->agregar('Parte de inasistencia agregado correctamente.', 'info');
								}

								//$this->dep('datos')->tabla('parte')->set($datos);

								if (isset($catedra) and $catedra != 0) {

									$sql = "SELECT nombre_catedra from reloj.catedras
						WHERE id_catedra = $catedra;";
									$cat = toba::db('comision')->consultar($sql);
									$datos['catedra'] = $cat[0]['nombre_catedra'];

								}

								if (isset($legajo)) {
									//$correo_agente = $this->dep('mapuche')->get_legajos_email($datos['legajo']);
									$correo_agente = $this->dep('datos')->tabla('agentes_mail')->get_correo($datos['legajo']);
									$datos['agente_ayn'] = $correo_agente[0]['descripcion'];

								}

								if (isset($datos['superior']) and $datos['superior'] != 0) {
									//$correo_sup = $this->dep('mapuche')->get_legajos_email($datos['superior']);
									$correo_sup = $this->dep('datos')->tabla('agentes_mail')->get_correo($datos['superior']);

									$datos['superior_ayn'] = $correo_sup[0]['descripcion'];
								}

								if (isset($datos['autoridad'])) {
									//$correo_aut = $this->dep('mapuche')->get_legajos_email($datos['autoridad']);
									$correo_aut = $this->dep('datos')->tabla('agentes_mail')->get_correo($datos['autoridad']);
									$datos['autoridad_ayn'] = $correo_aut[0]['descripcion'];

								}
								$agente = $this->dep('mapuche')->get_legajo_todos($legajo);
								$datos['descripcion'] = $agente[0]['descripcion'];

								$this->s__datos = $datos;

								if (isset($legajo)) {
									$sql = "SELECT email from reloj.agentes_mail
					where legajo=$legajo";
									$correo = toba::db('comision')->consultar($sql);
									$this->enviar_correos($correo[0]['email']);
								}

								if (isset($datos['superior']) and $datos['superior'] != 0) {
									$superior = $datos['superior'];

									$sql = "SELECT email from reloj.agentes_mail
					where legajo=$superior";
									$correo = toba::db('comision')->consultar($sql);
									//$this->enviar_correos_sup($correo[0]['email'],$datos['superior_ayn']);

								}

								/*if(isset($datos['autoridad'])) {
							$superior= $datos['autoridad'];
							//ei_arbol ($superior);

							$sql= "SELECT email from reloj.agentes_mail
							where legajo=$superior";
							$correo = toba::db('comision')->consultar($sql);
							$this->enviar_correos_sup($correo[0]['email'],$datos['autoridad_ayn']);

							}*/
							}
						} else {
							toba::notificacion()->agregar('Ud. ya completo el fomulario para ' . $depto[0]['nombre_catedra'], "info");

						}
					}

				}
			}
		} else {
			toba::notificacion()->agregar("Coloque un d�a mayor que 0", "info");

		}
	}

	public function evt__guardar()
	{

		//verificamos que no exista otro parte abiertos(estado) con el mismo legajo, motivo y dependencia.
		$datos = $this->dep('datos')->tabla('parte')->get();
		//ei_arbol ($datos);
		/*$filtro['legajo']     = $datos['legajo'];
		$filtro['id_motivo']  = $datos['id_motivo'];
		$filtro['cod_depcia'] = 04;*/

		$this->dep('datos')->sincronizar();
		toba::notificacion()->agregar('Parte de inasistencia agregado correctamente.', 'info');

	}
	public function enviar_correos($correo)
	{
		require_once '3ros/phpmailer/class.phpmailer.php';

		$datos = $this->s__datos;
//ei_arbol($datos);
		if ($datos['id_motivo'] == 30) {
			$datos['dias'] = $datos['dias'] - 1;

		}
		$fecha = date('d/m/Y', strtotime($datos['fecha_inicio_licencia']));

		$hasta = date('d/m/Y', strtotime($datos['fecha_inicio_licencia'] . "+ " . $datos['dias'] . " days"));

		if ($datos['dias_restantes'] <= 0) {
			$datos['dias_restantes'] = 0;
		}

		$mail = new phpmailer();
		$mail->IsSMTP();

//Esto es para activar el modo depuración. En entorno de pruebas lo mejor es 2, en producción siempre 0
		// 0 = off (producción)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;
//Ahora definimos gmail como servidor que aloja nuestro SMTP
		$mail->Host = 'smtp.gmail.com';
//El puerto será el 587 ya que usamos encriptación TLS
		$mail->Port = 587;
//Definmos la seguridad como TLS
		$mail->SMTPSecure = 'tls';
//Tenemos que usar gmail autenticados, así que esto a TRUE
		$mail->SMTPAuth = true;
//Definimos la cuenta que vamos a usar. Dirección completa de la misma

		$mail->Username = "formularios_asistencia@fca.uncu.edu.ar";
//Introducimos nuestra contraseña de gmail
		$mail->Password = "anzmxlazswghxqgb";
//Definimos el remitente (dirección y, opcionalmente, nombre)
		$mail->SetFrom('formularios_asistencia@fca.uncu.edu.ar', 'Formulario Personal');
//Esta línea es por si queréis enviar copia a alguien (dirección y, opcionalmente, nombre)

//$mail->AddReplyTo('caifca@fca.uncu.edu.ar','El de la réplica');
		//Y, ahora sí, definimos el destinatario (dirección y, opcionalmente, nombre)
		//$mail -> AddAddress('ebermejillo@fca.uncu.edu.ar', 'Tester');
		$mail->AddAddress($correo, 'El Destinatario'); //Descomentar linea cuando pase a produccion
		//Definimos el tema del email

//Para enviar un correo formateado en HTML lo cargamos con la siguiente función. Si no, puedes meterle directamente una cadena de texto.
		//$mail->MsgHTML(file_get_contents('correomaquetado.html'), dirname(ruta_al_archivo));
		//Y por si nos bloquean el contenido HTML (algunos correos lo hacen por seguridad) una versión alternativa en texto plano (también será válida para lectores de pantalla)
		$mail->IsHTML(true); //el mail contiene html

		if ($datos['id_motivo'] == 30) {
			//$motivo = 'Razones Particulares con gose de haberes';
			$mail->Subject = 'Formulario de Solicitud Razones Particulares';
			$body = '<table>
						El/la agente  <b>' . $datos['descripcion'] . '</b> perteneciente a la catedra/oficina/ direcci�n <b>' . $datos['catedra'] . '</b>.<br/>
						Solicita Justificaci�n de Inasistencia por Razones Particulares a partir del d�a ' . $fecha . ' hasta ' . $hasta . '.
							Teniendo en cuenta las siguientes Observaciones: ' . $datos['observaciones'] . '

			</table>';

		} else if ($datos['id_motivo'] == 35) {
			$mail->Subject = 'Formulario de Licencia Anual';
			//$motivo = 'Vacaciones'.$datos['anio'];
			$body = '<table>
						El/la agente  <b>' . $datos['descripcion'] . '</b> perteneciente a  <b>' . $datos['catedra'] . '</b>.<br/>
						Solicita la licencia anual correspondiente al  ' . $datos['anio'] . ' a partir del d�a ' . $fecha . 'hasta ' . $hasta . '. <br/>
						Teniendo en cuenta las siguientes Observaciones: ' . $datos['observaciones'] . '

			</table>';
			/*} else if ($datos['id_motivo'] == 55)
		{
		$mail->Subject = 'Formulario de Adelanto de Licencia Anual';
		$body = '<table>

		El/la agente <b>'.$datos['agente_ayn'].'</b> perteneciente a <b>'.$datos['catedra'].'</b> <br/>
		Solicita adelanto de licencia anual correspondiente al' .$datos['anio']. ' a partir del d�a'.$fecha. ' hasta '.$hasta. '<br/>
		Teniendo en cuenta las siguientes Observaciones: ' .$datos['observaciones']. 'Estos d�as de adelanto que ud ha solicitado,
		serán restados del total de vacaciones correspondientes al año en curso
		<table/>';*/

		} else if ($datos['id_motivo'] == 57) {
			$mail->Subject = 'Formulario de D�&as Pendientes de la Licencia Anual';
			$body = '<table>

				El/la agente <b>' . $datos['descripcion'] . '</b> perteneciente a <b>' . $datos['catedra'] . '</b> <br/>
				Solicita los d�as pendientes de la licencia anual correspondiente al ' . $datos['anio'] . ' a partir del d�a ' . $fecha . ' hasta ' . $hasta . '<br/>
				Teniendo en cuenta las siguientes Observaciones: ' . $datos['observaciones'] . '<br/>
				Ud. cuenta con ' . $datos['dias_restantes'] . ' d�as de vacaciones pendientes.
			<table/>';

		}

		; //date("d/m/y",$fecha)

		$mail->Body = $body;
//Enviamos el correo
		if (!$mail->Send()) {
			echo "Error: " . $mail->ErrorInfo;
		} else {
			toba::notificacion()->agregar('Su formulario ha sido completado y enviado a su correo. Ya puede cerrar la ventana.', "info");
			echo "Enviado!";

		}

	}
	public function enviar_correos_sup($correo, $destino)
	{
		require_once '3ros/phpmailer/class.phpmailer.php';

		$datos = $this->s__datos;
		$fecha = date('d/m/Y', strtotime($datos['fecha_inicio_licencia']));

		$hasta = date('d/m/Y', strtotime($datos['fecha_inicio_licencia'] . "+ " . $datos['dias'] . " days"));

		ei_arbol($datos);
		$mail = new phpmailer();
		$mail->IsSMTP();
//Esto es para activar el modo depuración. En entorno de pruebas lo mejor es 2, en producción siempre 0
		// 0 = off (producción)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;
//Ahora definimos gmail como servidor que aloja nuestro SMTP
		$mail->Host = 'smtp.gmail.com';
//El puerto será el 587 ya que usamos encriptación TLS
		$mail->Port = 587;
//Definmos la seguridad como TLS
		$mail->SMTPSecure = 'tls';
//Tenemos que usar gmail autenticados, así que esto a TRUE
		$mail->SMTPAuth = true;
//Definimos la cuenta que vamos a usar. Dirección completa de la misma

		$mail->Username = "formularios_asistencia@fca.uncu.edu.ar";
//Introducimos nuestra contraseña de gmail
		$mail->Password = "anzmxlazswghxqgb";
//Definimos el remitente (dirección y, opcionalmente, nombre)
		$mail->SetFrom('formularios_asistencia@fca.uncu.edu.ar', 'Formulario Personal');
//Esta línea es por si queréis enviar copia a alguien (dirección y, opcionalmente, nombre)
		//$mail->AddReplyTo('caifca@fca.uncu.edu.ar','El de la réplica');
		//Y, ahora sí, definimos el destinatario (dirección y, opcionalmente, nombre)
		$mail->AddAddress($correo, $destino);
//Definimos el tema del email
		//$mail->Subject = 'Formulario de Vacaciones del Agente ' .$datos['agente_ayn'];
		//Para enviar un correo formateado en HTML lo cargamos con la siguiente función. Si no, puedes meterle directamente una cadena de texto.
		//$mail->MsgHTML(file_get_contents('correomaquetado.html'), dirname(ruta_al_archivo));
		//Y por si nos bloquean el contenido HTML (algunos correos lo hacen por seguridad) una versión alternativa en texto plano (también será válida para lectores de pantalla)
		$mail->IsHTML(true); //el mail contiene html

		if ($datos['id_motivo'] == 30) {
			//$motivo = 'Razones Particulares con gose de haberes';
			$mail->Subject = 'Formulario de Solicitud Razones Particulares del Agente ' . $datos['agente_ayn'];
			$body = '<table>
						El/la agente  <b>' . $datos['descripcion'] . '</b> perteneciente a la <b>' . $datos['catedra'] . '</b> solicita <b> Razones Particulares </b> a partir del d�a ' . $fecha . ' hasta ' . $hasta . '.<br/>
							Observaciones: ' . $datos['observaciones'] . ' - <br/>
							En caso de no estar de acuerdo con la autorizacion comuniquese por correo con la autoridad correspondiente.


			</table>';

		} else if ($datos['id_motivo'] == 35) {

			//$motivo = 'Vacaciones'.$datos['anio'];
			$mail->Subject = 'Formulario de Solicitud Licencia Anual del Agente ' . $datos['agente_ayn'];
			$body = '<table>

						El/la agente  <b>' . $datos['descripcion'] . '</b> perteneciente a  la <b>' . $datos['catedra'] . '</b>.<br/>
						Solicita <b> Licencia Anual</b> correspondiente al  ' . $datos['anio'] . ' a partir del d�a ' . $fecha . ' hasta ' . $hasta . '.<br/>
						Teniendo en cuenta las siguientes Observaciones: ' . $datos['observaciones'] . ' <br/>
						En caso de no estar de acuerdo con la autorizacion comuniquese por correo con la autoridad correspondiente.


			</table>';
		} else if ($datos['id_motivo'] == 57) {
			$mail->Subject = 'Formulario de D�&as Pendientes de la Licencia Anual del Agente' . $datos['agente_ayn'];
			$body = '<table>

				El/la agente <b>' . $datos['descripcion'] . '</b> perteneciente a <b>' . $datos['catedra'] . '</b> <br/>
				Solicita <b>los d�as pendientes de la licencia anual</b> correspondiente al ' . $datos['anio'] . ' a partir del d�a ' . $fecha . ' hasta ' . $hasta . '<br/>
				Teniendo en cuenta las siguientes Observaciones: ' . $datos['observaciones'] . '<br/>
				Ud. cuenta con ' . $datos['dias_restantes'] . ' d�as de vacaciones pendientes.
			<table/>';

		}

		$mail->Body = $body;
//Enviamos el correo
		if (!$mail->Send()) {
			echo "Error: " . $mail->ErrorInfo;
		} else {
			echo "Enviado!";
		}
	}
}
