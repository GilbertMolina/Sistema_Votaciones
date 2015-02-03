//Se le avisa al usuario que tiene 60 segundos para efectuar su voto y se asigna el valor retornado a variable 'aceptar'
var aceptar = alert("Usted posee 60 segundos para realizar su voto, si no ha realizado su voto durante ese tiempo, el sistema autom\xe1ticamente cerrar\xe1 su sesi\xf3n.");
//Se verifica si lo que tiene la variable 'aceptar' es igual a 'undefined'
if (aceptar == undefined) {
    //Si es asi entonces se declara un timer por medio de la funcion 'setTimeout()', que ejecutara la funcion 'redireccionar()' dentro de 61 segundos
    setTimeout("redireccionar()", 60000);
}
//Se crea la funcion 'redireccionar()' que redireccionara al usuario al archivo 'cerrar_sesion.php' que se encarga de cerrar la sesion activa por el usuario
function redireccionar() {
    //Se le avisa al usuario que han pasado 60 segundos y que no ha efectuado su voto, por lo que el sistema ha cerrado la sesion automaticamente
    alert("Lo sentimos han pasado 60 segundos y no ha efectuado su voto, el sistema ha cerrado s\u00fa sesi\xf3n autom\xe1ticamente.");
    //Se declara una variable 'tiempoRestante' y se le asigna el valor del objeto 'timerVoto' html obtenido por javascript
    var tiempoRestante = document.getElementById("timerVoto").innerHTML;
    //Se verifica si 'tiempoRestante' es igual a 0
    if (tiempoRestante == 0) {
        //Si es asi entonces se redirecciona al archivo 'cerrar_sesion.php' que se encarga de cerrar la sesion
        window.location = "../includes/cerrar_sesion.php";
    }
} 