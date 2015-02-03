<?php
//Se reanuda la sesion actual basada en un identificador de sesion pasado el cual es el nombre del usuario completo
session_start();
?>
<div class="menu">
    <ul>
        <?php
        //Se verifica si existe la variable '$_SESSION[]' con el identificador 'NombreCompleto'
        if (isset($_SESSION['NombreCompleto'])) {
            //Se asigna a la variable '$nombre' lo que contiene la variable '$_SESSION[]' con el identificador 'NombreCompleto'
            $nombre = $_SESSION['NombreCompleto'];
            echo "<li><a href='' target='_self' >" . $nombre . "</a>
                      <ul>
                          <!--Se incluye en un link de la barra de navegacion el cierre de la sesion, para que el usuario pueda tener la opcion de cerrar sesion-->
                          <li>
                            <a href='../includes/cerrar_sesion.php' target='_self'><img style='vertical-align: text-bottom' src='../images/salir.png' alt='Volver' width='22' height='22'/><span style='vertical-align: baseline'> &nbsp;Cerrar sesi&oacute;n</span></a>
                          </li>
                      </ul>
                  </li>";
        }
        ?>
    </ul>
</div>
