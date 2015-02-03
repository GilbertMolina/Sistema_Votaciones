<?php
//Se importa la barra de navegacion y la imagen del tribunal supremo de elecciones
include 'includes' . DIRECTORY_SEPARATOR . 'barra_navegacion_index.php';
include 'includes' . DIRECTORY_SEPARATOR . 'imagen_tse_index.php';

//Se hace un require_once de la conexion a la base de datos y del archivo validar_usuario.php
require_once("includes/conexion.php");
require_once("includes/validar_usuario.php");

//Variable que manera si el formulario ha sido enviado
$formularioEnviado = '';

//Se verifica si el objeto 'formularioEnviado' existe
if (isset($_POST['formularioEnviado'])) {
    //Se verifica si el objeto 'formularioEnviado' tiene un valor diferente a vacio
    if ($_POST['formularioEnviado'] != "") {
        //Si tiene un valor diferente a vacio se asigna a la variable $formularioEnviado el valor de 'cargado'
        $formularioEnviado = "cargado";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sistema de Votaciones</title>
        <link rel="stylesheet" type="text/css" href="css/styles_index.css" />
        <link rel="shortcut icon" href="images/favicon.ico" />
    </head>
    <body>
        <?php
        //Se hace la instancia de la clase 'ValidarIngreso()'
        $ingresoDeUsuario = new ValidarIngreso();
        /* Se invoca a la funcion 'votacionesActivas()' que valida si hay votaciones activas, si no las hay
         * se redirecciona a la pagina de resultados finales
         */
        $ingresoDeUsuario->votacionesActivas();
        ?>
        <div id="wrapper">
            <div class="wrap">
                <h1>Ingresar al Sistema Votaciones</h1>
                <form id="votaciones" name="votaciones" action="index.php" method="post" autocomplete="off">
                    <table id="tablaIndex">
                        <tr>
                            <th>
                                C&eacute;dula:
                            </th>
                            <td>
                                <input id="cedula" type="text" name="cedula" size="40" maxlength="11" placeholder="Digite aqu&iacute; su c&eacute;dula"/>
                            </td>			
                        </tr>
                        <tr>
                            <th>
                                <br />
                                Contrase&ntilde;a:
                            </th>
                            <td>
                                <br />
                                <input id="contrasena" type="password" name="contrasena" size="10" maxlength="40" placeholder="Digite aqu&iacute; su contrase&ntilde;a"/>
                            </td>			
                        </tr>
                        <tr>
                            <td colspan="2">
                                <br />
                                <button id="cancelar" name="cancelar" type="reset"><img style="vertical-align: text-bottom" src="images/limpiar.png" alt="Limpiar campos" width="22" height="22"/><span style="vertical-align: baseline"> Limpiar</span></button>
                                <button id="ingresar" name="ingresar" type="submit"><span style="vertical-align: baseline">Ingresar&nbsp;&nbsp;</span><img style="vertical-align: text-bottom" src="images/ingresar.png" alt="Iniciar sesi&oacute;n" width="22" height="22"/></button>
                                <input name="formularioEnviado" id="formularioEnviado" type="hidden" value="enviado" />
                                <?php if ($formularioEnviado == "cargado") { ?>
                                    <br />&nbsp;
                                <?php } ?>
                            </td>
                        </tr>
                        <div id="erroresParent">
                        <?php
                        //Se valida si el formulario a sido enviado por medio de la variable $formularioEnviado
                        if ($formularioEnviado == "cargado") {
                            /* Si el formulario ha sido enviado se invoca a la funcion 'validar()', la cual sirve para validar
                             * el inicio de sesion del usuario, y en caso de que hayan errores, estos se muestran en esta seccion
                             */
                            echo '<tr id="erroresChild">';
                            echo '<td colspan = "2" id = "tablaErrores" >';
                            $ingresoDeUsuario->validar();
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                        </div>
                    </table>
                    <table>
                        <tr>
                            <td>
                                <br />
                                <br />
                                <a href="screens/agregar.php"><button id="agregarNuevaVotacion" name="agregarNuevaVotacion" type="button"><img style="vertical-align: text-bottom" src="images/agregar.png" alt="Agregar votaci&oacute;n" width="22" height="22"/><span style="vertical-align: baseline">&nbsp;Agregar votaci&oacute;n</span></button></a>
                            </td>
                            <td>
                                <br />
                                <br />
                                <a href="screens/registroPersonas.php"><button id="registrarPersona" name="registrarPersona" type="button"><span style="vertical-align: baseline">Registrarse&nbsp;&nbsp;</span><img style="vertical-align: text-bottom" src="images/registrar.png" alt="Registrarse" width="22" height="22"/></button></a>
                            </td>
                        </tr>
                    </table>
                </form>
                <div id="lineaBotones"></div>
            </div>
        </div>
        <div class="footer">
            <img id="banderaCostaRica" src="images/banderaCostaRica.png">
            <p>
                <br />
                Sistema de Votaciones Costa Rica
            </p>
        </div>
    </body>
</html>
