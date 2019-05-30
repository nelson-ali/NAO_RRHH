/*
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  05-03-2014
 */
/**
 * Función para la obtención del listado de excepciones definidas en el sistema.
 * @param opcion      -- Valor que permite determinar el formulario en el que se ejecuta.
 * @param idExcepcion -- Identificador de la excepción que debería estar seleccionada por defecto en caso de que su valor sea mayor a cero.
 */
function cargaListaDeExcepciones(opcion,idExcepcion){
    var sufijo = "New";
    if(opcion==2)sufijo = "Edit";
    var selected = "";
    $("#lstExcepcion"+sufijo).html("");
    $("#lstExcepcion"+sufijo).append("<option value=''>Seleccionar..</option>");
    $("#lstExcepcion"+sufijo).prop("disabled",true);
    $.ajax({
        url: '/excepciones/list/',
        type: "POST",
        datatype: 'json',
        async: false,
        cache: false,
        success: function (data) {  //alert(data);
            var res = jQuery.parseJSON(data);
            if(res.length>0){
                $("#lstExcepcion"+sufijo).prop("disabled",false);
                $.each( res, function( key, val ) {
                    if(idExcepcion==val.id){selected="selected";
                    }else selected="";
                    $("#lstExcepcion"+sufijo).append("<option value='"+val.id+"' "+selected+">"+val.excepcion+"</option>");
                });
            }
        }, //mostramos el error
        error: function () {
            $("#divMsjePorError").html("");
            $("#divMsjePorError").append("Se ha producido un error Inesperado");
            $("#divMsjeNotificacionError").jqxNotification("open");
        }
    });
}

/**
 * Función para la limpieza de los mensajes de error debido a la validación del formulario.
 * @opción Variable que identifica a que tipo de formulario se aplica la función.
 */
function limpiarMensajesErrorPorValidacionControlExcepcion(opcion) {
    var sufijo = "New";
    if(opcion==2)sufijo = "Edit";
    $("#divExcepcion"+sufijo).removeClass("has-error");
    $("#helpErrorExcepcion"+sufijo).html("");
    $("#divFechaIni"+sufijo).removeClass("has-error");
    $("#helpErrorFechaIni"+sufijo).html("");
    $("#divHoraIni"+sufijo).removeClass("has-error");
    $("#helpErrorHoraIni"+sufijo).html("");
    $("#divFechaFin"+sufijo).removeClass("has-error");
    $("#helpErrorFechaFin"+sufijo).html("");
    $("#divHoraFin"+sufijo).removeClass("has-error");
    $("#helpErrorHoraFin"+sufijo).html("");
    $("#divJustificacion"+sufijo).removeClass("has-error");
    $("#helpErrorJustificacion"+sufijo).html("");
}

/**
 * Función para el almacenamiento de los datos registrados en el formulario de control de excepciones.
 */
function guardaControlExcepciones(opcion) {
    var ok = false;
    var idControlExcepcion = 0;
    var sufijo = "New";
    if (opcion == 2) {
        idControlExcepcion = $("#hdnIdControlExcepcionEdit").val();
        sufijo = "Edit";
    }
    var idRelaboral = $("#hdnIdRelaboral"+sufijo).val();
    var idExcepcion = $("#lstExcepcion"+sufijo).val();
    var fechaIni = $("#txtFechaIni"+sufijo).val();
    var horaIni = $("#txtHoraIni"+sufijo).val();
    var fechaFin = $("#txtFechaFin"+sufijo).val();
    var horaFin = $("#txtHoraFin"+sufijo).val();
    var justificacion = $("#txtJustificacion"+sufijo).val();
    var observacion = $("#txtObservacion"+sufijo).val();
    if (idExcepcion != ''&&idExcepcion>0) {
        $.ajax({
            url: '/controlexcepciones/save/',
            type: "POST",
            datatype: 'json',
            async: false,
            cache: false,
            data: {
                id: idControlExcepcion,
                relaboral_id:idRelaboral,
                excepcion_id:idExcepcion,
                fecha_ini:fechaIni,
                hora_ini:horaIni,
                fecha_fin:fechaFin,
                hora_fin:horaFin,
                justificacion:justificacion,
                observacion: observacion
            },
            success: function (data) {  //alert(data);
                var res = jQuery.parseJSON(data);
                $(".msjes").hide();
                if (res.result == 1) {
                    ok = true;
                    $("#divMsjePorSuccess").html("");
                    $("#divMsjePorSuccess").append(res.msj);
                    $("#divMsjeNotificacionSuccess").jqxNotification("open");
                    $("#divGridControlExcepciones").jqxGrid("updatebounddata");
                } else if (res.result == 0) {
                    $("#divMsjePorWarning").html("");
                    $("#divMsjePorWarning").append(res.msj);
                    $("#divMsjeNotificacionWarning").jqxNotification("open");
                } else {
                    $("#divMsjePorError").html("");
                    $("#divMsjePorError").append(res.msj);
                    $("#divMsjeNotificacionError").jqxNotification("open");
                }

            },
            error: function () {
                $("#divMsjePorError").html("");
                $("#divMsjePorError").append("Se ha producido un error Inesperado");
                $("#divMsjeNotificacionError").jqxNotification("open");
            }
        });
    }
    return ok;
}
/**
 * Función para la verificación de la no existencia de cruce de horarios en cuanto a la aplicación de las excepciones para una determinada persona.
 * @param idControlExcepcion
 * @param idRelaboral
 * @param idExcepcion
 * @param fechaIni
 * @param horaIni
 * @param fechaFin
 * @param horaFin
 * @param justificacion
 */
function verificaCruceDeHorarios(idControlExcepcion,idRelaboral,idExcepcion,fechaIni,horaIni,fechaFin,horaFin,justificacion){
    var ok = false;
    $.ajax({
        url: '/controlexcepciones/verificacruce/',
        type: "POST",
        datatype: 'json',
        async: false,
        cache: false,
        data: {
            id:idControlExcepcion,
            relaboral_id:idRelaboral,
            excepcion_id:idExcepcion,
            excepcion_id:idExcepcion,
            fecha_ini:fechaIni,
            hora_ini:horaIni,
            fecha_fin:fechaFin,
            hora_fin:horaFin,
            justificacion:justificacion
        },
        success: function (data) {  //alert(data);
            var res = jQuery.parseJSON(data);
            $(".msjes").hide();
            if (res.result == 0) {
                ok = true;
            } else if (res.result == 1) {
                $("#divMsjePorWarning").html("");
                $("#divMsjePorWarning").append(res.msj);
                $("#divMsjeNotificacionWarning").jqxNotification("open");
            } else {
                $("#divMsjePorError").html("");
                $("#divMsjePorError").append(res.msj);
                $("#divMsjeNotificacionError").jqxNotification("open");
            }

        },
        error: function () {
            $("#divMsjePorError").html("");
            $("#divMsjePorError").append("Se ha producido un error Inesperado");
            $("#divMsjeNotificacionError").jqxNotification("open");
        }
    });
    return ok;
}