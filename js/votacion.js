//Se crea la funcion que se encarga de validar la firma ingresada por el usuario
function validarFirma() {
    var firmaVotante = document.forms["votacion"]["firmaVotante"].value;
    var voto = document.getElementsByName("valor");
    var seleccionado = false;

    //Se valida que el usuario haya marcado al menos una de las opciones para el voto, si es asi la variable 'seleccionado' se setea a true
    for (var i = 0; i < voto.length; i++) {
        if (voto[i].checked) {
            seleccionado = true;
            break;
        }
    }

    //Se valida si la variable 'seleccionado' es false, si es asi entonces se le avisa al usuario y retorna false
    if (!seleccionado) {
        alert("Debe seleccionar una de las opciones disponibles para efectuar el voto.");
        return false;
    }

    //Se valida la firma del votante, si la firma del votante no cumple se le avisa al usuario y retorna false
    if (firmaVotante == 0 || firmaVotante == "") {
        alert("Por favor ingrese su firma para realizar el voto.");
        document.votacion.firmaVotante.select();
        return false;
    }

    //Se valida si la variable 'seleccionado' es true y si la firma del votante no esta vacia o que no sea un 0
    if (seleccionado && (firmaVotante !== 0 && firmaVotante !== "")) {
        //Si es asi, entonces se le pregunta al usuario si es realidad desea votar por esa opcion
        if (!(window.confirm("Atenci\xf3n:\n\n¿Esta seguro que desea realizar este voto?"))) {
            //Si el usuario en el mensaje marca 'Cancelar' entonces se retorna false
            return false;
        } else {
            //Se lo contrario entonces se le avisa al usuario que su voto ha sido contabilizado y se le avisa que será redireccionado a la pantalla de resultados
            alert("Su voto a sido efectuado y contabilizado, a continuaci\u00f3n ser\xe1 redireccionado a la pantalla de resultados.\n\nMuchas gracias.");
        }
    }

    //Si los datos anteriores son correctos se retorna true
    return true;
}

//Se crea la funcion que se encarga de mostrar la fecha actualizado segundo a segundo al usuario
function muestraReloj() {
    //Se declaran las variables necesarias
    var puntos = ":";
    var fecha = new Date();
    var dia = fecha.getDate();
    var mes = fecha.getMonth() + 1;
    var anno = fecha.getFullYear();
    var hora = fecha.getHours();
    var horaAux = fecha.getHours();
    var minuto = fecha.getMinutes();
    var segundos = fecha.getSeconds();
    var meridiano = " a.m.";

    //Se pone un 0 a la izquierda en caso de que el mes sea menor a 10
    if (mes < 10) {
        mes = '0' + mes;
    }
    //Para cambiar el reloj de hora militar a normal entonces se valida si la hora es mayor a 12, entonces se le resta 12 a la hora y se cambia el merdiano a 'p.m.'
    if (hora > 12) {
        hora -= 12;
        meridiano = " p.m.";
    }
    //Se pone un 0 a la izquierda en caso de que la hora sea menor a 10
    if (hora < 10) {
        hora = "0" + hora;
    }
    /* Cuando son las 12 media noche, la hora por venir en militar se cambia a '00', entonces se arregla verificando si la hora es igual a '00', si es asi
     * entonces se asigna '12' a la hora
     */
    if (hora == 00) {
        hora = '12';
    }
    /* Si son las 12 media noche entonces el meridiano se asigna a 'a.m.', esto se arregla haciendo uso de un variable auxiliar para la hora, entonces se verifica
     * si la hora auxiliar actual es '00' entonces el meridiano se asigna a 'a.m.', de lo contrario si la hora auxiliar actual es '12' entonces el meridiano se asigna a 'p.m.'
     */
    if (hora == 12) {
        if (horaAux == 00) {
            meridiano = " a.m.";
        } else {
            meridiano = " p.m.";
        }
    }
    //Se pone un 0 a la izquierda en caso de que el minuto sea menor a 10
    if (minuto < 10) {
        minuto = "0" + minuto;
    }
    //Se pone un 0 a la izquierda en caso de que los segundos sean menores a 10
    if (segundos < 10) {
        segundos = '0' + segundos;
    }
    //Se declara una variable que da formato a la fecha actual
    var fechaConformada = dia + '-' + mes + '-' + anno;
    //Se declara una variable que da formato a la hora actual
    var horaConformada = hora + puntos + minuto + puntos + segundos + meridiano;
    //Se declara una variable que se conforma de la fecha y hora actual
    var horaFechaActualFinal = 'Fecha y hora actual: ' + fechaConformada + ' ' + horaConformada;
    //Se le asigna al objeto html obtenido por javascript la variable 'horaFechaActualFinal'
    document.getElementById("fechaHoraJavaScript").innerHTML = horaFechaActualFinal;

    //Se declara una variable 'tiempoRestante' y se le asigna el valor del objeto html 'timerVoto' obtenido por javascript
    var tiempoRestante = document.getElementById("timerVoto").innerHTML;
    //Se verifica si 'tiempoRestante' es mayor que 0
    if (tiempoRestante > 0) {
        //Se le resta un elemento al objeto html obtenido por javascript de lo que tiene actualmente
        document.getElementById("timerVoto").innerHTML -= 1;
    }

}

//Se ejecuta la funcion 'muestraReloj()' cada segundo
window.onload = function() {
    setInterval(muestraReloj, 1000);
}