<?php
//Se importa la barra de navegacion y la imagen del tribunal supremo de elecciones
include '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'barra_navegacion_index.php';
include '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'imagen_tse_opciones.php';

//Se hace un require_once de la conexion a la base de datos
require_once("../includes/registrarPersonas.php");

//Se verifica si el formulario fue enviado
if ($_POST) {
    //Se verifica de que los campos no vayan vacios
    if($_POST["cedula"] != '' && $_POST["nombre"] != '' && $_POST["apellido1"] != '' && $_POST["apellido2"] != '' && $_POST["provincia"] != '' && $_POST["contrasena1"] != ''){
        //Si fue enviado entonces se genera un instancia a la clase 'registrarPersonas()'
        $registrarPersona = new registrarPersonas();
        //Se llama a la funcion 'registrarPersona()' y se le pasan como parametros la cedula, el nombre, el primer apellido, el segundo apellido y la contraseña
        $registrarPersona->registrarPersona($_POST["cedula"], $_POST["nombre"], $_POST["apellido1"], $_POST["apellido2"], $_POST["provincia"], $_POST["contrasena1"]);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Registrar personas</title>
        <link rel="stylesheet" type="text/css" href="../css/styles_registro_personas.css" />
        <script type="text/javascript" src="../js/scripts.js"></script>
        <link rel="shortcut icon" href="../images/favicon.ico" />
    </head>
    <!--Cuando carga el formulario de llama a la funcion para que muestre el reloj en la pantalla-->
    <body onload="muestraReloj()">
        <div id="wrapper">
            <div class="wrapRegistroPersonas">
                <div id="fechaHoraActualRegistroPersonas">
                    <span id="fechaHoraJavaScript"></span>
                    <?php
                    //Se generan variables para obtener la fecha y hora actual del sistema
                    date_default_timezone_set("America/Costa_Rica");
                    'Fecha y hora actual: ' . $fechaHoraActual = date("d-m-Y h:i A", strtotime(date("d-m-Y H:i:s")));
                    $fechaMinima = date("Y-m-d", strtotime(date("d-m-Y H:i:s"))) . 'T00:01';
                    ?>
                </div>
                <form id="registro_de_personas" name="registro_de_personas" method="post" action="registroPersonas.php" onsubmit="return validarRegistroPersonas()" autocomplete="off">
                    <h1>Registrar personas</h1>
                    <table>
                        <tr>
                            <td>
                                C&eacute;dula:
                            </td>
                            <td>
                                <input id="cedula" name="cedula" type="text" size="30" maxlength="11" placeholder="Digite aqu&iacute; su c&eacute;dula"/>
                            </td>			
                        </tr>
                        <tr>
                            <td>
                                <br />
                                Nombre:
                            </td>
                            <td>
                                <br />
                                <input id="nombre" name="nombre" type="text" size="30" maxlength="50" placeholder="Digite aqu&iacute; su nombre"/>
                            </td>			
                        </tr>
                        <tr>
                            <td>
                                <br />
                                Primer apellido:
                            </td>
                            <td>
                                <br />
                                <input id="apellido1" name="apellido1" type="text" size="30" maxlength=50" placeholder="Digite aqu&iacute; su primer apellido"/>
                            </td>			
                        </tr>
                        <tr>
                            <td>
                                <br />
                                Segundo apellido:
                            </td>
                            <td>
                                <br />
                                <input id="apellido2" name="apellido2" type="text" size="30" maxlength="50" placeholder="Digite aqu&iacute; su segundo apellido"/>
                            </td>			
                        </tr>
                        <tr>
                            <td>
                                <br />
                                Provincia:
                            </td>
                            <td>
                                <br />
                                <select id="provincia" name="provincia">
                                    <option value="Seleccione">Seleccione una provincia</option>
                                    <option value="San José">San Jos&eacute;</option>
                                    <option value="Alajuela">Alajuela</option>
                                    <option value="Cartago">Cartago</option>
                                    <option value="Heredia">Heredia</option>
                                    <option value="Limón">Lim&oacute;n</option>
                                    <option value="Guanacaste">Guanacaste</option>
                                    <option value="Puntarenas">Puntarenas</option>
                                </select>
                            </td>			
                        </tr>
                        <tr>
                            <td>
                                <br />
                                Contrase&ntilde;a:
                            </td>
                            <td>
                                <br />
                                <input id="contrasena1" name="contrasena1" type="password" maxlength="40" placeholder="Digite aqu&iacute; su contrase&ntilde;a"/>
                            </td>			
                        </tr>
                        <tr>
                            <td>
                                <br />
                                Confirmar contrase&ntilde;a:
                            </td>
                            <td>
                                <br />
                                <input id="contrasena2" name="contrasena2" type="password" maxlength="40" placeholder="Digite aqu&iacute; nuevamente su contrase&ntilde;a"/>
                            </td>			
                        </tr>
                        <tr>
                            <td>
                                <br />
                            </td>
                            <td>
                                <br />
                                <a href="../index.php"><button id="volver" name="volver" type="button"><img style="vertical-align: text-bottom" src="../images/atras.png" alt="Volver" width="22" height="22"/><span style="vertical-align: baseline"> &nbsp;Volver</span></button></a>
                                <button id="enviar" name="enviar" type="submit"><img style="vertical-align: text-bottom" src="../images/check.png" alt="Enviar" width="22" height="22"/><span style="vertical-align: baseline"> Registrar</span></button>
                                <br />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div class="footer">
        </div>
        <br />
        <br />
    </body>
</html>