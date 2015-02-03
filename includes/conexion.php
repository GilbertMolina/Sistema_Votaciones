<?php

//Se crea la clase 'ConectarMySQL'
class ConectarMySQL {

    //Se crea la funcion 'conexion()' la cual se encarga de conectarse a la base de datos MySQL
    public static function conexion() {
        //Variable que recibe el nombre del host donde se encuentra la base de datos
        $mysql_host = "localhost";
        //Variable que recibe el nombre de la base de datos
        $mysql_database = "elecciones";
        //Variable que recibe el nombre del usuario para conectarse a la base de datos
        $mysql_user = "root";
        //Variable que recibe la contraseña del usuario para conectarse a la base de datos
        $mysql_password = "12345";

        /* Se crea una variable la cual almacenara el objeto de conexion a la base de datos, por medio de la
         * funcion 'mysql_connect()', esta funcion recibe como parametros el nombre del host de la base de datos,
         * el nombre del usuario para conectarse al base de datos, y la contraseña de este ultimo
         */
        $conexion = mysql_connect($mysql_host, $mysql_user, $mysql_password);
        //Se setean los charsets a 'utf8' para evitar que se muestren caracteres extraños en las consultas 
        mysql_query("SET NAMES 'utf8'");
        mysql_query("SET CHARACTER SET 'utf8'");

        /* Se crea una variable la cual recibira el objecto de seleccion de la bae de datos, por medio de la funcion
         * 'mysql_select_db()', esta funcion recibe como parametros el nombre de la base de datos y objeto conexion
         */
        $seleccionarBase = mysql_select_db($mysql_database, $conexion);

        //Si no se lográ establecer la conexion se muestra un error de que verifica que el usuario y la contraseña sean los correctos
        if (!$conexion) {
            die('<h1>Error al conectar a la base de datos.</h1><h2>Por favor, verifique que la contraseña sea la correcta.</h2>');
            mysql_close($conexion);
        }

        //Si no se lográ seleccionar la base de datos se muestra un error de que verifique la base de datos sea la correcta
        if (!$seleccionarBase) {
            die('<h1>Error al seleccionar la base de datos.</h1><h2>Por favor, verifique que la base de datos sea la correcta y que este bien escrito.</h2>');
            mysql_close($conexion);
        }

        //Se retorna el objeto conexion
        return $conexion;
    }
    
}
?>
