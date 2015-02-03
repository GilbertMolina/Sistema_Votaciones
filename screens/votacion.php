<?php
//Se importa la barra de navegacion y la imagen del tribunal supremo de elecciones
include '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'barra_navegacion_opciones.php';
include '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'imagen_tse_opciones.php';

//Se hace un require_once de la conexion a la base de datos
require('../includes/conexion.php');

//Se verifica si no existe la variable '$_SESSION' con el identificador 'NombreCompleto'
if (!isset($_SESSION['NombreCompleto'])) {
    //Si es asi entonces se redirecciona al usuario al formulario de inicio de sesion
    header('location: ../index.php');
}

//Se crea un variable la cual guardara la cedula de la persona, esta es tomada la variable '$_SESSION' por el identificador 'PK_Cedula'
$cedulaPersona = $_SESSION["PK_Cedula"];
//Se obtiene por medio de '$_GET' el id de la votacion 'PK_IdVotacion'
$PK_IdVotacion = $_GET['PK_IdVotacion'];

//Se verifica si no existe el parametro 'PK_IdVotacion'
if (!isset($_GET['PK_IdVotacion'])) {
    //Si es asi entonces se redirecciona al usuario a la pantalla de inicio
    header('location: inicio.php');
}
/* Se genera una consulta la cual devuelve registros si el usuario ya ha votado en la votacion actual, esto para motivo de que un usuario que ya voto no se
 * le muestre nuevamente esa opcion para votar
 */
$sqlVerificar = "SELECT COUNT(*) as voto " .
                "FROM Votos " .
                "WHERE PK_Cedula = '" . $cedulaPersona . "' " .
                "AND PK_IdVotacion = " . $PK_IdVotacion;
$reqVerificar = mysql_query($sqlVerificar, ConectarMySQL::conexion());
while ($resultadoVerificar = mysql_fetch_assoc($reqVerificar)) {
    //Se obtiene el valor devuelto por la consulta, si '$votoRealizado' es mayor a 0 quiere decir que el usuario ya efectuó su voto por la votacion actual
    $votoRealizado = $resultadoVerificar["voto"];
}

//Se verifica si se ha hecho clic sobre el boton 'votar'
if (isset($_POST['votar'])) {
    //Si es asi entonces se verifica que exista la opcion haya sido marcada y que el usuario haya firmado en el campo firma
    if (isset($_POST['valor']) && (isset($_POST['firmaVotante']) && $_POST['firmaVotante'] != '')) {
        //Se obtienen los valores envidos por el usuario
        $idOpcionSeleccionada = $_POST['valor'];
        $firmaPersona = $_POST['firmaVotante'];
        //Se realiza el insert del voto por la opcion seleccionada por el usuario
        $sql = "INSERT INTO Votos (PK_Cedula, PK_IdVotacion, FK_IdOpcion, Firma, Fecha) " .
               "VALUES ('$cedulaPersona', '$PK_IdVotacion', '$idOpcionSeleccionada', '$firmaPersona', '$fechaHoraActualInsert')";
        $votacion = mysql_query($sql, ConectarMySQL::conexion());
        //Se redirecciona al usuario a la pantalla de resultados mostrandole los resultados de la votacion por la que acaba de efectuar su voto
        header('location: resultado.php?PK_IdVotacion=' . $PK_IdVotacion);
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sistema de Votaciones</title>
        <link rel="stylesheet" href="../css/styles_screens.css">
        <script type="text/javascript" src="../js/votacion.js"></script>
        <link rel="shortcut icon" href="../images/favicon.ico" />
    </head>
    <!--Cuando carga el formulario de llama a la funcion para que muestre el reloj en la pantalla-->
    <body onload="muestraReloj()">
        <div class="wrap">
            <div id="fechaHoraActual">
                <span id="fechaHoraJavaScript"></span>
                <?php
                //Se generan variables para obtener la fecha y hora actual del sistema
                date_default_timezone_set("America/Costa_Rica");
                $fechaHoraActual = date("d-m-Y h:i A", strtotime(date("d-m-Y H:i:s")));
                $fechaHoraActualInsert = date("Y-m-d H:i:s", strtotime(date("d-m-Y H:i:s")));
                'Fecha y hora actual: ' . $fechaHoraActual;
                ?>
            </div>
            <form id="votacion" name="votacion" action="" method="post" onsubmit="return validarFirma()" autocomplete="off">
                <!--Se verifica si el usuario no ha realizado su voto-->
                <?php if ($votoRealizado == 0) { ?>
                    <!--Si es asi entonces se le muestra un mensaje al usuario del tiempo que tiene disponible para realizar su voto-->
                    <p id="letreroCartel">Tiempo restante para realizar su voto: <span id="timerVoto">60</span></p>
                    <br />
                 <?php } ?>
                <?php
                //Se declaran variables que se utilizaran mas adelante
                $aux = 0;
                $contador = 0;
                //Se genere un consulta la cual mostrara al usuario las opciones que tiene para la votacion actual
                $sql = "SELECT v.PK_IdVotacion, v.Tipo, CASE Tipo WHEN 'P' THEN 'Presidencial' WHEN 'M' THEN 'Municipal' WHEN 'R' THEN 'Referendum' END as 'TipoString', v.FechaInicio, v.FechaFin, vo.PK_IdOpcion, vo.Bandera, vo.Partido, vo.Candidato " .
                       "FROM Votaciones v " .
                       "INNER JOIN Votaciones_Opciones vo " .
                       "ON v.PK_IdVotacion = vo.FK_IdVotacion " .
                       "WHERE CONCAT(CURDATE(),' ',CURTIME()) BETWEEN v.FechaInicio AND v.FechaFin " .
                       "AND v.PK_IdVotacion = " . $PK_IdVotacion;
                $req = mysql_query($sql, ConectarMySQL::conexion());
                while ($result = mysql_fetch_assoc($req)) {
                    //Se verifica si es la primer iteracion para colocar un titulo del nombre de la votacion
                    if ($aux == 0) {
                        /* Si es la primer iteracion entonces se coloca el titulo de la votacion, si no es Referéndum entonces deberá de quedar 'Votación Presidencial' o 
                         * 'Votación Municipal', en caso contrario deberá quedar solamente 'Referéndum'
                         */
                        if ($result["Tipo"] != 'R') {
                            echo '<h1>Votaci&oacute;n ' . $result["TipoString"] . '</h1>';
                        } else {
                            echo '<h1>' . $result["TipoString"] . '</h1>';
                        }
                        echo '<ul class="votacion">';

                        /* Si verifica si el usuario ya ha votado por la votacion actual, si es asi entonces se le muestra un mensaje al usuario de que ya efectuó su voto por esa
                         * opcion y no lo puede volver a hacer
                         */
                        if ($votoRealizado > 0) {
                            echo '<center><span id="nombreOpcion">Usted ya efectu&oacute; su voto en esta votaci&oacute;n</span></center>';
                        }
                        //Se setea la variable '$aux' a 1, para asi colocar las opciones que tiene el usuario para poder efectuar su voto
                        $aux = 1;
                    }
                    if ($contador != 0) {
                        if ($votoRealizado == 0) {
                            echo '<br />';
                        }
                    }
                    //Si el usuario no ha efectuado su voto la votacion actual entonces se le muestran las opciones disponibles al usuario
                    if ($votoRealizado == 0) {
                        echo '<center><span id="nombreOpcion">' . $result["Partido"] . '</span></center>';
                        echo '<li>' .
                        '<img align="center" src="' . $result["Bandera"] . '" alt="Bandera" width="45" height="45"/>&nbsp;&nbsp;' .
                        '<input name="valor" type="radio" value="' . $result["PK_IdOpcion"] . '"><b><span>' . $result["Candidato"] . '</span></b>' .
                        '</li>';
                    }
                    $contador++;
                }
                echo '</ul>';
                ?>
                <!--Se verifica si el usuario no ha efectuado, si es asi entonces se le muestra la opcion de la firma del votante-->
                <?php if ($votoRealizado == 0) { ?>
                    <div id="firma">
                        <input type="text" id="firmaVotante" name="firmaVotante" maxlength="290" placeholder="Digite aqu&iacute; su firma" />
                    </div>
                <?php } ?>
                <?php
                //Se verifica de que el formulario no haya sido enviado
                if (!isset($_POST['valor'])) {
                    /* Se verifica si el usuario no ha efectuado su voto por la votacion actual para asi mostrarle un mensaje que le recuerde que puede votar 
                     * o que puede ver los resultados finales
                     */
                    if ($votoRealizado == 0) {
                        echo "<center><div class='error'>Seleccione entre votar o ver resultados</div></center><br />";
                    } else {
                        /* Si el usuario ya habia efectuado su voto entonces se le muestra un mensaje que le recuerde que puede volver al inicio o que puede ver 
                         * los resultados finales
                         */
                        echo "<center><div class='error'>Seleccione entre volver al inicio o ver resultados</div></center><br />";
                    }
                }
                /* Se verifica si el usuario no ha efectuado su voto por la votacion actual para asi mostrarle el boton de votar
                 */
                if ($votoRealizado == 0) {
                    echo '<button id="votar" class="votar" name="votar" type="submit"><img style="vertical-align: text-bottom" src="../images/check.png" alt="Votar por esta opcion" width="22" height="22"/><span style="vertical-align: baseline"> &nbsp;Votar</span></button>';
                } else {
                    //En caso contrario se le muestra en boton de volver al inicio
                    echo '<a href="inicio.php"><button id="volverIndex" name="volverIndex" type="button"><img style="vertical-align: text-bottom" src="../images/atras.png" alt="Volver" width="22" height="22"/><span style="vertical-align: baseline"> &nbsp;Volver</span></button></a>';
                }
                //Se le muestra al usuario el boton de ver resultados de la votacion actual
                echo '<a href="resultado.php?PK_IdVotacion=' . $PK_IdVotacion . '" class="resultado"><button id="verResultados" name="verResultados" type="button"><span style="vertical-align: baseline">Ver Resultados&nbsp;&nbsp;</span><img style="vertical-align: text-bottom" src="../images/siguiente.png" alt="Ver resultados" width="22" height="22"/></button></a>';
                echo "<br />";
                echo "<br />";
                echo "<br />";
                if ($votoRealizado == 0) {
                    echo '<a href="inicio.php"><button id="volverIndex" name="volverIndex" type="button"><img style="vertical-align: text-bottom" src="../images/atras.png" alt="Volver" width="22" height="22"/><span style="vertical-align: baseline"> &nbsp;Volver</span></button></a>';
                    echo '<br/><br/>';
                }
                ?>
            </form>
        </div>
    </body>
    <!--Se verifica si el usuario no ha votado por la votacion actual, si es asi entonces entonces se ejecuta el script que genera un timer al usuario para que tenga 60 segundos para votar-->
    <?php if ($votoRealizado == 0) { ?>
            <script type="text/javascript" src="../js/redireccionar.js"></script>
    <?php } ?>
</html>