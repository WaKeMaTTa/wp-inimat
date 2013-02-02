<?php
/**
 * Clasificador de Criaturas:
 * @description	Anadir, modificar y ver las criaturas.
 * @author		Mohamed Ziata (WaKeMaTTa)
 * @version		1.0 
 */
class clasificadorCriaturas {
	
	/**
     * Formulario para añadir una criatura o Formulario para modificar una criatura o Ver las criaturas
     * 
     * @param string $funcion		anadir, modificar o ver
     * @param int $id_user			ID user: get_current_user_id()
	 * @param int $id_criatura		ID criatura para crear el formulario para modificar la criatura
	 * @return mixed				(depende de la opcion selecionada)
     */
    public function __construct ($funcion, $id_user = NULL, $rol_admin = FALSE, $action = NULL, $mostrar = FALSE, $id_criatura = NULL) {
		// las opciones de construcion
		$funcAnadir = FALSE;
		$funcModificar = FALSE;
		$funcVer = FALSE;
		
		// verificar que funcion se a selecionado
		switch ($funcion) {
		case 'anadir':
			$funcAnadir = TRUE;
			break;
		case 'modificar':
			$funcModificar = TRUE;
			break;
		case 'ver':
			$funcVer = TRUE;
			break;
		default:
			echo '<span class="error">ERROR: no existe la opcion '.$funcion.' para la clase '.__CLASS__.'</span>';
			break;
		}
		
		// opcion anadir
		if ($funcAnadir == TRUE) {
			if ($mostrar == TRUE) {
				if ($action != NULL) {
					self::form_criaturas(	$action, TRUE, $rol_admin,
											$nombre='', 
											$altura='', $ancho='', $peso='', 
											$tipo='', $genero='', 
											$habitat='', 
											$habilidad1='', $habilidad2='', $habilidad3='', $habilidad4='', $habilidad5='', 
											$descripcion='', $comentario='',
											$licencia='',
											$imgBoceto=NULL, $imgModelado=NULL, $imgTexturizado=NULL, $zip=NULL);
				}
			}
		}
		
		// opcion modificar
		if ($funcModificar == TRUE) {
			echo 'modificar';
		}
		
		// opcion ver
		if ($funcVer == TRUE) {
			echo 'ver';
		}	
    }
    
    public function __destruct() {
    }
	
	/*
	* verificar si el nombre de la criatura si ya esta en usuo
	*
	* @param string $nombre		nombre de la criatura
	*
	* @return bolean			0 = no existe el nombre
	*							1 = ya existe ese nombre
	*/
	function verificar_nombre_criatura_existe($nombre) {
		global $wpdb;
		$query = $wpdb -> prepare( "SELECT `nombre` FROM `".$wpdb -> prefix."inimat_criaturas` WHERE `nombre` = %s", $nombre );
		$resultado = $wpdb -> query($query);
		return $resultado;
		
	}
	
	/*
	* verificar si el medida indicada es mas grande o mas pequeña que la perdeterminada
	*
	* @param int $medida		medida (ejemplos: altura, ancho, peso,... )
	* @param int $min			min de la medida
	* @param int $max			max de la medida
	*
	* @return bolean 			TRUE	la medida es mas grnade o mas pequeña que el valor
	*							FALSE	la medida esta dentro el min y el max
	*/		
	function verificar_medida_criatura($medida, $max = 100, $min = 0.01) {
		// ¿la medida es mas grnade o mas pequeña que el valor?
		if ($medida < $min || $medida > $max) {
			$resultado = TRUE;
		} else { $resultado = FALSE; }
		return $resultado;
	}

	/*
	* mostrar el formulario para añadir o modificar una criatura
	*
	* @param string $action			pagina donde se mostarador el resultado
	* @param bolean $reset			TRUE	se mostra el formulario sin verificar	
	*								FALSE	se mostra el formulario verificado
	* @param string $nombre			nombre de la criatura
	* @param int $altura			altura de la criatura
	* @param int $ancho				ancho de la criatura
	* @param int $peso				peso de la criatura
	* @param string $tipo			tipo de la criatura
	* @param string $genero			genero de la criatura
	* @param string $habitat		habitat de la criatura
	* @param string $habilidad1		habilidad1 de la criatura
	* @param string $habilidad2		habilidad2 de la criatura
	* @param string $habilidad3		habilidad3 de la criatura
	* @param string $habilidad4		habilidad4 de la criatura
	* @param string $habilidad5		habilidad5 de la criatura
	* @param string $descripcion	descripcion de la criatura
	* @param bolean $licencia		
	*
	* @return mixed
	*/
	function form_criaturas($action, $reset, $rol_admin, $nombre='', $altura='', $ancho='', $peso='', $tipo='', $genero='', $habitat='', $habilidad1='', $habilidad2='', $habilidad3='', $habilidad4='', $habilidad5='', $descripcion='', $licencia='',$imgBoceto=NULL, $imgModelado=NULL, $imgTexturizado=NULL, $zip=NULL, $path=NULL) {
		global $current_user;
		
		// variables de estilo rapido
		$red = '';
		$green = '';
		
		// si $reset=true, solo mostrar el form sin ser verificado
		if ($reset == FALSE) {
			
			// variables de estilo rapido
			$red = ' style="border: 1px red solid;" ';
			$green = ' style="border: 1px green solid;" ';
			
			// verificacion del nombre de la criatura si existe
			if ($nombre != '') {
				$existe = self::verificar_nombre_criatura_existe($nombre);
				if ($existe > 0) {
					$error_global = TRUE;
					$error_nombre = 1;
					$mensaje_error_nombre = 'El nombre de la criatura ya existe!';
				}
			} else {
				$error_global = TRUE;
				$error_nombre = 1;
				$mensaje_error_nombre = 'Rellena el nombre de la criatura!';
			}
			
			// verificacion de la altura
			if ($altura != '') {
				$medida = self::verificar_medida_criatura($altura, 20);
				if ($medida == TRUE) {
					$error_global = TRUE;
					$error_altura = 1;
					$mensaje_error_altura = '('.$altura.') no esta dentro los parametros!';
				}
			} else {
				$error_global = TRUE;
				$error_altura = 1;
				$mensaje_error_altura = 'Rellena la altura de la criatura!';
			}
			
			// verificacion del ancho
			if ($ancho != '') {
				$medida = self::verificar_medida_criatura($ancho, 20);
				if ($medida == TRUE) {
					$error_global = TRUE;
					$error_ancho = 1;
					$mensaje_error_ancho = '('.$ancho.') no esta dentro los parametros!';
				}
			} else {
				$error_global = TRUE;
				$error_ancho = 1;
				$mensaje_error_ancho = 'Rellena el ancho de la criatura!';
			}
			
			// verificacion del peso
			if ($peso != '') {
				$medida = self::verificar_medida_criatura($peso, 20000);
				if ($medida == TRUE) {
					$error_global = TRUE;
					$error_peso = 1;
					$mensaje_error_peso = '('.$peso.') no esta dentro los parametros!';
				}
			} else {
				$error_global = TRUE;
				$error_peso = 1;
				$mensaje_error_peso = 'Rellena el peso de la criatura!';
			}
			
			// verificar si se a selecionado un tipo para la criatura
			if ($tipo == '') {
				$error_global = TRUE;
				$error_tipo = 1;
				$mensaje_error_tipo = 'No has selecionado ninguna opcion!';
			} else if ($tipo == 'material' || $tipo == 'astral' || $tipo == 'guardian' || $tipo == 'saums') {
			} else {
				$error_global = TRUE;
				$error_tipo = 1;
				$mensaje_error_tipo = 'El tipo selecionado ('.$tipo.') no esta dentro de nuestra base de datos!';	
			}
			
			// verificar si se a selecionado un genero para la criatura
			if ($genero == '') {
				$error_global = TRUE;
				$error_genero = 1;
				$mensaje_error_genero = 'No has selecionado ninguna opcion!';
			} else if ($genero == 'acuatico' || $genero == 'volador' || $genero == 'terrestre' || $genero == 'vegetal') {
			} else {
				$error_global = TRUE;
				$error_genero = 1;
				$mensaje_error_genero = 'El genero selecionado ('.$genero.') no esta dentro de nuestra base de datos!';
			}
			
			// verificar si se a rellenado el habitat de la criatura
			if ($habitat == '') {
				$error_global = TRUE;
				$error_habitat = 1;
			}
			
			// verificar si se a rellenado un minimo de 1 habilidad para la criatura
			$n_habilidad = 0;
			if ($habilidad1 != '') { $n_habilidad = $n_habilidad + 1;  } 
			if ($habilidad2 != '') { $n_habilidad = $n_habilidad + 1;  }
			if ($habilidad3 != '') { $n_habilidad = $n_habilidad + 1;  }
			if ($habilidad4 != '') { $n_habilidad = $n_habilidad + 1;  }
			if ($habilidad5 != '') { $n_habilidad = $n_habilidad + 1;  }
			
			if ($n_habilidad == 0) {
				$error_global = TRUE;
				$error_habilidad = 1;
				$mensaje_error_habilidad = 'Rellena un minimo de 1 habilidad!';
			}
			
			// verificar si se a rellenado la descripcion de la criatura
			if ($descripcion == '') {
				$error_global = TRUE;
				$error_descripcion = 1;
			}

		}
		
		if ($reset == FALSE && isset($error_global) == FALSE) {echo '<span>Bien la criatura a sido agregada</span>';}

		?>
		<form action="admin.php?page=<?php echo $action; ?>" method="post" name="formulario" target="_self" enctype="multipart/form-data">
		<table>
		<tr>
			<td><label>Autor</label></td>
			<td><input name="autor" type="text" value="<?php echo $current_user -> display_name; ?>" size="20" disabled="disabled" /></td>
		</tr>
		<tr>
			<td><label>Nombre de la criatura</label></td>
			<td><input name="nombre" placeholder="Nombre de la criatura" type="text" size="20" maxlength="50"
				<?php echo 'value="'.$nombre.'"'; 
				if (isset($error_nombre)) { echo $red; } else { echo $green; } ?> /> 
				<?php if (isset($error_nombre)) { echo '<input class="error" value="'.$mensaje_error_nombre.'" disabled="disabled" />'; } ?></td>
		</tr>
		<tr>
			<td><label>Altura (m)</label></td>
			<td><input name="altura" placeholder="0.00" type="text" size="6" maxlength="6" class="formato_numerico"
				<?php echo 'value="'.$altura.'"'; 
				if (isset($error_altura)) { echo $red; } else { echo $green; } ?> /> 
				<?php if (isset($error_altura)) { echo '<input class="error" value="'.$mensaje_error_altura.'" disabled="disabled"/>'; } ?></td>
		</tr>
		<tr>
			<td><label>Ancho (m)</label></td>
			<td><input name="ancho" placeholder="0.00" type="text" size="6" maxlength="6" class="formato_numerico"
				<?php echo 'value="'.$ancho.'"'; 
				if (isset($error_ancho)) { echo $red; } else { echo $green; } ?> /> 
				<?php if (isset($error_ancho)) { echo '<input class="error" value="'.$mensaje_error_ancho.'" disabled="disabled"/>'; } ?></td>
		</tr>
		<tr>
			<td><label>Peso (kg)</label></td>
			<td><input name="peso" placeholder="0.00" type="text" size="6" maxlength="6" class="formato_numerico"
				<?php echo 'value="'.$peso.'"'; 
				if (isset($error_peso)) { echo $red; } else { echo $green; } ?> /> 
				<?php if (isset($error_peso)) { echo '<input class="error" value="'.$mensaje_error_peso.'" disabled="disabled"/>'; }?></td>
		</tr>
		<?php if (isset($error_tipo)) { 
			echo '<tr><td colspan="2"><input class="error" style="width: 510px;" value="'.$mensaje_error_tipo.'" disabled="disabled"/></td></tr>'; 
		} ?>
		
		<tr>
			<td><label>Tipo</label></td>
			<td><select name="tipo" <?php if (isset($error_tipo)) { echo $red; } else { echo $green; } ?> >
				<option value="" <?php if ($tipo == '') { echo 'selected'; } ?> > « elige » </option>
				<option value="material" <?php if ($tipo == 'material') { echo 'selected'; } ?> >Material</option>
				<option value="astral" <?php if ($tipo == 'astral') { echo 'selected'; } ?> >Astral</option>
				<option value="guardian" <?php if ($tipo == 'guardian') { echo 'selected'; } ?> >Guardian</option>
				<option value="saums" <?php if ($tipo == 'saums') { echo 'selected'; } ?> >Saums</option>
			</select>
			</td>
		</tr>
		<?php if (isset($error_genero)) { 
			echo '<tr><td colspan="2"><input class="error" style="width: 510px;" value="'.$mensaje_error_genero.'" disabled="disabled"/></td></tr>'; 
		} ?>
		<tr>
			<td><label>Genero</label></td>
			<td><select name="genero" <?php if (isset($error_genero)) { echo $red; } else { echo $green; } ?> >
				<option value="" <?php if ($genero == '') { echo 'selected'; } ?> > « elige » </option>
				<option value="acuatico" <?php if ($genero == 'acuatico') { echo 'selected'; } ?> >Acuático</option>
				<option value="volador" <?php if ($genero == 'volador') { echo 'selected'; } ?> >Volador</option>
				<option value="terrestre" <?php if ($genero == 'terrestre') { echo 'selected'; } ?> >Terrestre</option>
				<option value="vegetal" <?php if ($genero == 'vegetal') { echo 'selected'; } ?> >Vegetal</option>
			</select></td>
		</tr>
		<tr>
			<td><label>Hábitat</label></td>
			<td><input name="habitat" placeholder="El hábitat de la criatura" type="text" size="62" maxlength="255" 
			<?php 
			echo 'value="'.$habitat.'"';
			if (isset($error_habitat)) { echo $red; } else { echo $green; } ?> /></td>
		</tr>
		<tr>
			<td><label>Habilidades</label></td>
			<td>
				<input name="habilidad1" placeholder="Habilidad 1" type="text" size="20" maxlength="30" 
				<?php 
				echo 'value="'.$habilidad1.'"';
				if (isset($error_habilidad)) { echo $red; } ?> /><br />
				<input name="habilidad2" placeholder="Habilidad 2" type="text" size="20" maxlength="30" <?php echo 'value="'.$habilidad2.'"'; ?> /><br />
				<input name="habilidad3" placeholder="Habilidad 3" type="text" size="20" maxlength="30" <?php echo 'value="'.$habilidad3.'"'; ?> /><?php if (isset($error_habilidad)) { echo '<input class="error" value="'.$mensaje_error_habilidad.'" disabled="disabled"/>'; }?><br />
				<input name="habilidad4" placeholder="Habilidad 4" type="text" size="20" maxlength="30" <?php echo 'value="'.$habilidad4.'"'; ?> /><br />
				<input name="habilidad5" placeholder="Habilidad 5" type="text" size="20" maxlength="30" <?php echo 'value="'.$habilidad5.'"'; ?> />
			</td>
		</tr>
		<tr>
			<td><label>Descripción</label></td>
			<td><textarea name="descripcion" placeholder="Descripción fisicamente y psicamente" cols="64" rows="5" 
			<?php if (isset($error_descripcion)) { echo $red; } else { echo $green; } ?> ><?php echo $descripcion; ?></textarea></td>
		</tr>
		<tr>
			<td><label>Boceto (imagen)</label></td>
			<td><input name="imgBoceto" type="file" size="2" /></td>
		</tr>
		<tr>
			<td><label>Modelado (imagen)</label></td>
			<td><input name="imgModelado" type="file" size="2" /></td>
		</tr>
		<tr>
			<td><label>Texturizado (imagen)</label></td>
			<td><input name="imgTexturizado" type="file" size="2" /></td>
		</tr>
		<tr>
			<td><label>Zip (del .blend)</label></td>
			<td><input name="zip" type="file" size="2" /></td>
		</tr>
		<tr>
			<td><label>Tu comentario</label></td>
			<td><textarea name="comentario" placeholder="Añade tu comentario aqui para indicar cualquier cosa." cols="64" rows="5"><?php echo $descripcion; ?></textarea></td>
		</tr>
		<tr>
			<td><label>Licencia</label></td>
			<td><?php 
			if ($reset == TRUE) {
				echo '<input name="licencia" type="checkbox" value="acepto_licencia">';
				echo '<span>¿Aceptas que la criatura quedara bajo la licencia <a href="http://creativecommons.org/licenses/by-sa/3.0/es/" target="_blank">CC by-sa 3.0</a>?</span>';
			} else {
				if ($licencia == FALSE) {
					$error_global = TRUE;
					echo '<input name="licencia" type="checkbox" value="acepto_licencia">';
					echo '<span style="color:red;">¿Aceptas que la criatura quedara bajo la licencia <a href="http://creativecommons.org/licenses/by-sa/3.0/es/" target="_blank">CC by-sa 3.0</a></span>';
				} else {
					echo '<input name="licencia" type="checkbox" value="acepto_licencia" checked="checked">';
					echo '<span style="color:green;">Gracias por aceptar la licencia.</span>';
				}
			}
			?></td> 			
		</tr>
		<tr>
			<td colspan="2" align="center"><input class="button button-primary" name="enviar" type="submit" value="¡enviar!" style="margin:15px;"/></td>
			
		</tr>
		</table>
	</form>
	<?php
	}
	
	function saveFileCraitura($path, $type, $size, $nombre_criatura) {
		//
	}
	
}

?>