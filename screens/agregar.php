<?php
//Se importa la barra de navegacion y la imagen del tribunal supremo de elecciones
include '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'barra_navegacion_opciones.php';
include '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'imagen_tse_opciones.php';

//Se hace un require_once de la conexion a la base de datos
require('../includes/conexion.php');

//Funcion creada para reemplazar caracteres especiales de un string y retorna al string sin caracteres especiales
function limpiarCaracteresEspeciales($string) {
    $string = str_replace(' ', '-', $string);
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    return preg_replace('/-+/', '-', $string);
}

//Se declaran variables necesarias para el manejo de la logica, las cuales se necesitaran mas adelante
$cont = 0;
$tipoSeleccionado = '';
$fechaInicio = '';
$fechaFin = '';
$num = 0;
$errores = 0;
$errorEnParticular = '';

//Se verifica si existe 'fechaInicio' y 'fechaFin' cuando se da clic sobre 'Continuar'
if (isset($_POST['fechaInicio']) && isset($_POST['fechaFin'])) {
    $tipoSeleccionado = $_POST['opcionesTipo'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
}

//Se verifica si el formulario fue enviado
if (isset($_POST['enviar'])) {
    //Se verifica si la fechaInicio y la fechaFinal no estan vacias
    if ($fechaInicio != "" && $fechaFin != "") {
        //Se toman el numero de opcines que escogio  el usuario
        $num = $_POST['opciones'];

        //Se hace el insert de la votacion para luego poder tomar el id y poder hacer el insert de la o las opciones
        $sql = "INSERT INTO Votaciones(`FechaInicio`, `FechaFin`, `Tipo`) VALUES " .
               "('$fechaInicio', '$fechaFin', '$tipoSeleccionado');";
        mysql_query($sql, ConectarMySQL::conexion());

        //Se hace la consulta para tomar la ultima votacion registrada
        $sql = "SELECT MAX(PK_IdVotacion) as PK_IdVotacion FROM Votaciones";
        $req = mysql_query($sql, ConectarMySQL::conexion());
        while ($result = mysql_fetch_assoc($req)) {
            $id_votacion = $result["PK_IdVotacion"];
        }

        //Se hace el insert de las opciones para la ultima votacion
        $sql = "INSERT INTO Votaciones_Opciones(`FK_IdVotacion`, `Bandera`, `Partido`, `Candidato`) VALUES ";
        for ($i = 1; $i <= $num; $i++) {
            $banderaOpcionActual = 'banderaOpc' . $i;
            if ($tipoSeleccionado == 'P') {
                $tituloBandera = 'Presidencial';
            } else if ($tipoSeleccionado == 'M') {
                $tituloBandera = 'Municipal';
            } else {
                $tituloBandera = 'Referendum';
            }
            
            /* Se declaran una variable '$extensionArchivo' que almacenara el tipo de archivo de la imagen haciendo uso de la funcion 'substr()', 
             * obteniendo los ultimos 3 caracteres del nombre del archivo
             */
            $extensionArchivo = substr($_FILES[$banderaOpcionActual]['type'], -3);
            //Se declaran una variable '$banderaRutaExitosa'para almacenar la ruta de la bandera en caso de ser exitosa la subida
            $banderaRutaExitosa = "";
            /* Se declaran una variable '$partidoBanderaSinCaracteres' la cual almacera el nombre de la bandera del partido actual de ciclo para colocarselo a la bandera, 
             * además se hace uso de la funcion 'limpiarCaracteresEspeciales()' para reemplazar los caracteres especiales
             */
            $partidoBanderaSinCaracteres = limpiarCaracteresEspeciales(trim($_POST['partido' . $i]));
            //Se declaran una variable la cual almacera el nombre del partido actual de ciclo
            $partido = $_POST['partido' . $i];
            //Se declaran una variable la cual almacera el nombre del candidato actual de ciclo
            $candidato = $_POST['candidato' . $i];
            /* Se declaran una variable '$candidatoBanderaSinCaracteres' la cual almacera el nombre del candidato actual de ciclo para colocarselo a la bandera, 
             * además se hace uso de la funcion 'limpiarCaracteresEspeciales()' para reemplazar los caracteres especiales
             */
            $candidatoBanderaSinCaracteres = limpiarCaracteresEspeciales($_POST['candidato' . $i]);

            //Se verifica si el archivo que el usuario esta queriendo subir ha dado un error
            if ($_FILES[$banderaOpcionActual]["error"] > 0) {
                //Si ha ocurrido un error entonces se setea la bandera '$errores' a '1'
                $errores = 1;
                //Se le asigna a la variable '$errorEnParticular' el numero de error que sucedio para luego mostrarselo al cliente
                $errorEnParticular = 1;
            } else {
                //Si no es asi entonces se declara una variable que contendrá un arreglo de la lista de tipos de archivos permitidos
                $permitidos = array("image/jpg", "image/peg", "image/gif", "image/png"); // --> "image/peg" = "image/jpeg"
                //Se declara una variable que almacenará el limite de kilobytes permitido, como tamaño de la imagen
                $limite_tamaño_kb = 1000;

                /* Se verifica si el archivo que esta queriendo subir el usuario cumple con los tipos de archivo permitido y si el tamaño del archivo cumple
                 * con el limite permitido de kilobytes
                 */
                if (in_array($_FILES[$banderaOpcionActual]['type'], $permitidos) && $_FILES[$banderaOpcionActual]['size'] <= $limite_tamaño_kb * 1024) {
                    /* Si es asi entonces se declara una variable '$nuevoNombreImagen' que almacenara el nombre de archivo que llevará el archivo cuando se almancerá
                     * en el servidor web de la aplicación
                     */
                    $nuevoNombreImagen = 'bandera_' . $tituloBandera . '_' . $partidoBanderaSinCaracteres . '_' . $candidatoBanderaSinCaracteres . '.' . $extensionArchivo;
                    /* Se declara una variable '$banderaRuta' que almacenara la ruta donde se guardará la imagen, esta se conforma por el path donde se guardan las imagenes
                     * '../images/banderas/' y se concatena el nombre que llevara la imagen '$nuevoNombreImagen'
                     */
                    $banderaRuta = "../images/banderas/" . $nuevoNombreImagen;
                    //Se verifica si el nombre de archivo que se va a subir al servidor no existe
                    if (!file_exists($banderaRuta)) {
                        /* Si el archivo no existe en el servidor entonces se declara una variable '$resultado' que contendrá true o false si el archivo se pudo mover al servidor,
                         * y se mueve al servidor por medio de la funcion '@move_uploaded_file()' la cual recibe como parametro el nombre temporal que le da PHP a la imagen que desea
                         * subir el usuario y la ruta donde va a ser movida
                         */
                        $resultado = @move_uploaded_file($_FILES[$banderaOpcionActual]["tmp_name"], $banderaRuta);
                        //Se verifica si la imagen se subio correctamente al servidor
                        if ($resultado) {
                            //Si es asi entonces a la variable '$banderaRutaExitosa' se le asigna la ruta donde se subio el archivo
                            $banderaRutaExitosa = $banderaRuta;
                        } else {
                            //Si ha ocurrido un error entonces se setea la bandera '$errores' a '1'
                            $errores = 1;
                            //Se le asigna a la variable '$errorEnParticular' el numero de error que sucedio para luego mostrarselo al cliente
                            $errorEnParticular = 2;
                        }
                    } else {
                        $banderaRutaExitosa = $banderaRuta;
                    }
                } else {
                    //Si ha ocurrido un error entonces se setea la bandera '$errores' a '1'
                    $errores = 1;
                    //Se le asigna a la variable '$errorEnParticular' el numero de error que sucedio para luego mostrarselo al cliente
                    $errorEnParticular = 3;
                }
            }

            //Se hace el insert de la opcion a la votacion
            if ($id_votacion != "" && $partido != "" && $candidato != "" && $banderaRutaExitosa != "") {
                $sql .= "('$id_votacion', '$banderaRutaExitosa', '$partido', '$candidato')";
                $cont++;
            }
            //Se verifica si la iteracion actual es igual a la cantidad de opciones seleccionadas por el usuario
            if ($i == $num) {
                //Si es asi entonces se concatena a la variable $sql un ';' para finalizar el insert
                $sql .= ";";
            } else {
                //Si no es asi entonces se concatena a la variable $sql un ', ' para seguir agregando lineas de inserts
                $sql .= ", ";
            }
        }
        //Si por dado caso el usuario no selecciono opciones entonces se borra la ultima votacion insertada porque no va a contener opciones
        if ($cont < 2) {
            $sql = "DELETE FROM Votaciones WHERE PK_IdVotacion = " . $id_votacion;
            //Si ha ocurrido un error entonces se setea la bandera '$errores' a '1'
            $errores = 1;
            //Se le asigna a la variable '$errorEnParticular' el numero de error que sucedio para luego mostrarselo al cliente
            $errorEnParticular = 4;
        } else {
            //Se redirecciona al usuario al formulario de inicio de sesion
            header('location: ../index.php');
        }
        //Se ejecutan las consultas
        mysql_query($sql, ConectarMySQL::conexion());
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sistema de Votaciones</title>
        <link rel="stylesheet" href="../css/styles_screens.css">
        <script type="text/javascript" src="../js/scripts.js"></script>
        <link rel="shortcut icon" href="../images/favicon.ico" />
    </head>
    <!--Cuando carga el formulario de llama a la funcion para que muestre el reloj en la pantalla-->
    <body onload="muestraReloj()">
        <div class="wrapAgregar">
        <div id="fechaHoraActualAgregar">
            <span id="fechaHoraJavaScript"></span>
            <?php
            //Se generan variables para obtener la fecha y hora actual del sistema
            date_default_timezone_set("America/Costa_Rica");
            'Fecha y hora actual: ' . $fechaHoraActual = date("d-m-Y h:i A", strtotime(date("d-m-Y H:i:s")));
            $fechaMinima = date("Y-m-d", strtotime(date("d-m-Y H:i:s"))) . 'T00:01';
            ?>
        </div>
            <h1>Agregar nueva votaci&oacute;n</h1>
            <form id="nuevaVotacion" name="nuevaVotacion" action="" method="post" enctype="multipart/form-data" autocomplete="off" onsubmit="return validarIngresoFechas()">
                <div class="fl titulo">
                    <table>
                        <tr>
                            <th>
                                <label for="titulo">Tipo:</label>
                            </th>
                            <th>
                                <label for="fechaInicio">Fecha inicio:</label>
                            </th>
                            <th>
                                <label for="fechaFin">Fecha fin:</label>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <select id="opcionesTipo" name="opcionesTipo">
                                    <!--Se muestran las opciones al usuario para escoger al usuario, las cuales son: Presidencial, Municipal y Referéndum-->
                                    <?php if (!isset($_POST['opc'])) {
                                        echo '<option value="P">Presidencial</option>';
                                        echo '<option value="M">Municipal</option>';
                                        echo '<option value="R">Refer&eacute;ndum</option>';
                                    } else {
                                        //Despues de que el usuario hace clic sobre 'Continuar' entonces se vuelve a seleccionar la opcion que habia marcado
                                        if ($tipoSeleccionado == 'P') {
                                            echo '<option value="P" selected>Presidencial</option>';
                                            echo '<option value="M">Municipal</option>';
                                            echo '<option value="R">Refer&eacute;ndum</option>';
                                        } else if($tipoSeleccionado == 'M') {
                                            echo '<option value="P">Presidencial</option>';
                                            echo '<option value="M" selected>Municipal</option>';
                                            echo '<option value="R">Refer&eacute;ndum</option>';
                                        } else {
                                            echo '<option value="P" selected>Presidencial</option>';
                                            echo '<option value="M">Municipal</option>';
                                            echo '<option value="R" selected>Refer&eacute;ndum</option>';
                                        }
                                    } ?>
                                </select>
                            </td>
                            <td>
                                <input name="fechaInicio" id="fechaInicio" type="datetime-local" min="<?php echo $fechaMinima; ?>" value="<?php echo $fechaInicio; ?>" placeholder="dd/mm/aaaa --:-- a.m." style="height: 32px; width: 220px;"/>
                            </td>
                            <td>
                                <input name="fechaFin" id="fechaFin" type="datetime-local" min="<?php echo $fechaMinima; ?>" value="<?php echo $fechaFin; ?>" placeholder="dd/mm/aaaa --:-- p.m." style="height: 32px; width: 220px;"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php
                //Se verifica si el usuario selecciono la cantidad de opciones para la votacion y dio clic en 'Continuar'
                if (isset($_POST['opc'])) {
                    $num = $_POST['opciones'];
                    ?>
                    <div class="cf">
                        <table>
                            <?php
                            //Se genera un ciclo para la cantidad de opciones que seleccionó el usuario
                            for ($i = 1; $i <= $num; $i++) {
                                ?>
                                <tr>
                                    <!--Se verifica que la votacion seleccionada sea diferente a 'Referéndum entonces se le muestran las siguientes opciones-->
                                    <?php if ($tipoSeleccionado != 'R') { ?>
                                        <th>
                                            <label for="partido<?php echo $i; ?>">Partido n&uacute;mero <?php echo $i; ?>: </label>
                                        </th>
                                        <th>
                                            <label for="candidato<?php echo $i; ?>">Candidato n&uacute;mero <?php echo $i; ?>: </label>
                                        </th>
                                        <th>
                                            <label for="banderaOpc<?php echo $i; ?>">Bandera alusiva al partido n&uacute;mero <?php echo $i; ?>: </label>
                                        </th>
                                    <?php } else { ?>
                                        <!--En caso de que si lo sea entonces se le muestan las siguientes opciones-->
                                        <th>
                                            <label for="partido<?php echo $i; ?>">Pregunta n&uacute;mero <?php echo $i; ?>: </label>
                                        </th>
                                        <th>
                                            <label for="candidato<?php echo $i; ?>">Respuesta n&uacute;mero <?php echo $i; ?>: </label>
                                        </th>
                                        <th>
                                            <label for="banderaOpc<?php echo $i; ?>">Bandera alusiva a la pregunta n&uacute;mero <?php echo $i; ?>: </label>
                                        </th>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <td>
                                        <!--Se verifica si la opcion seleccionada es 'Referéndum' para asi cambiar el 'Nombre partido' por 'Pregunta 1'
                                            y cambiar 'Nombre Candidato' por 'Respuesta 1'
                                        -->
                                        <?php if ($tipoSeleccionado != 'R') { ?>
                                            <input name="partido<?php echo $i; ?>" id="partido<?php echo $i; ?>" type="text" size="20" style="text-align: center" maxlength="300" placeholder="Nombre del partido"/>
                                        <?php } else { ?>
                                            <input name="partido<?php echo $i; ?>" id="partido<?php echo $i; ?>" type="text" size="20" style="text-align: center" maxlength="300" placeholder="Digite aqu&iacute; su pregunta"/>
                                        <?php } 
                                            echo $saltoLinea1 = ($i != $num)? '<br />&nbsp;' : '';
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($tipoSeleccionado != 'R') { ?>
                                            <input name="candidato<?php echo $i; ?>" id="candidato<?php echo $i; ?>" type="text" size="20" style="text-align: center" maxlength="100" placeholder="Nombre del candidato"/>
                                        <?php } else { ?>
                                            <input name="candidato<?php echo $i; ?>" id="candidato<?php echo $i; ?>" type="text" size="20" style="text-align: center" maxlength="100" placeholder="Digite aqu&iacute; su opci&oacute;n"/>
                                        <?php } 
                                            echo $saltoLinea2 = ($i != $num)? '<br />&nbsp;' : '';
                                        ?>
                                    </td>
                                    <td>
                                        <!--Se le muestra al usuario la opcion para subir la bandera alusiva al Partido o a la Pregunta
                                        -->
                                        <input name="banderaOpc<?php echo $i; ?>" id="banderaOpc<?php echo $i; ?>" type="file" size="20">
                                        <?php
                                            echo $saltoLinea3 = ($i != $num)? '<br />&nbsp;' : '';
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="cf">
                        <button id="cancelar" name="cancelar" type="reset" onclick="location.reload()"><img style="vertical-align: text-bottom" src="../images/recargar.png" alt="Cancelar" width="22" height="22"/><span style="vertical-align: baseline"> Deshacer</span></button>
                        <button id="agregarVotacion" name="enviar" type="submit"><span style="vertical-align: baseline">Agregar votaci&oacute;n&nbsp;&nbsp;</span><img style="vertical-align: text-bottom" src="../images/agregar.png" alt="Agregar votaci&oacute;n" width="22" height="22"/></button>
                        <input name="opciones" type="hidden" value="<?php echo $num; ?>">
                        <input name="cont" type="hidden" value="<?php echo $cont; ?>">
                    </div>
                <?php if($errores == 1) {
                    echo '<br />';
                    echo '<br />';
                    echo '<div id="tablaErrores_2">';
                        echo '<p>';
                                switch ($errorEnParticular) {
                                    case 1:
                                        echo 'Error: Ha ocurrido un error.';
                                        break;
                                    case 2:
                                        echo 'Error: Ocurrio un error al mover el archivo.';
                                        break;
                                    case 3:
                                        echo 'Error: Archivo no permitido o excede el tamano de ' . $limite_tamaño_kb . ' Kilobytes permitidos.';
                                        break;
                                    case 4:
                                        echo 'Error: Tiene que llevar por lo menos 2 opciones, el nombre del partido o la pregunta, el candidato o la respuesta y la bandera del partido. ' . 
                                             'Además verifica que el tipo de archivo sea permitido [jpg, jpeg, gif, png] o que no exceda el tamano de ' . $limite_tamaño_kb . ' Kilobytes establecido.';
                                        break;
                                }
                        echo '</p>';
                    echo '</div>';
                } ?>
                <?php } else { ?>
                    <div class="fl titulo">
                        <label><b>Nº de opciones:</b></label>
                        <select id="opciones" name="opciones">
                            <!--Se genera un ciclo para la cantidad de opciones que puede seleccionar el usuario, se escogio 20 dado que se estima que es
                                una cantidad considerable pero se puede cambiar facilmente por un numero mas grande de acuerdo a las necesidades
                            -->
                            <?php for ($i = 2; $i <= 20; $i++) {
                                if ($i == 2) {
                                    echo "<option value='" .  $i . "' selected>" .  $i . "</option>";
                                } else {
                                    echo "<option value='" .  $i . "'>" .  $i . "</option>";
                                }
                             } ?>
                        </select>
                    </div>
                    <div class="cf">
                        <button id="continuar" class="continuar" name="opc" type="submit"><img style="vertical-align: text-bottom" src="../images/check.png" alt="Votar por esta opcion" width="22" height="22"/><span style="vertical-align: baseline"> &nbsp;Continuar</span></button>
                        <br />&nbsp
                    </div>
                <?php if($errores == 1) {
                    echo '<div id="tablaErrores_1">';
                        echo '<p>';
                                switch ($errorEnParticular) {
                                    case 1:
                                        echo 'Error: Ha ocurrido un error.';
                                        break;
                                    case 2:
                                        echo 'Error: Ocurrio un error al mover el archivo.';
                                        break;
                                    case 3:
                                        echo 'Error: Archivo no permitido o excede el tamano de ' . $limite_tamaño_kb . ' Kilobytes permitidos.';
                                        break;
                                    case 4:
                                        echo 'Error: Tiene que llevar por lo menos 2 opciones, el nombre del partido o la pregunta, el candidato o la respuesta y la bandera del partido. ' . 
                                             'Además verifica que el tipo de archivo sea permitido [jpg, jpeg, gif, png] o que no exceda el tamano de ' . $limite_tamaño_kb . ' Kilobytes establecido.';
                                        break;
                                }
                        echo '</p>';
                    echo '</div>';
                } ?>
                <?php } ?>
                <?php if($num != 0 && $errores != 1) { ?>
                    <br />&nbsp
                    <br />&nbsp
                <?php } ?>
                <a href="../index.php"><button id="volverIndex" name="volverIndex" type="button"><img style="vertical-align: text-bottom" src="../images/atras.png" alt="Volver" width="22" height="22"/><span style="vertical-align: baseline"> &nbsp;Volver</span></button></a>
                <br />&nbsp
                <br />&nbsp
            </form>
        </div>
    </body>
</html>