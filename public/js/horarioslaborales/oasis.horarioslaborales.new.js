/*
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  19-12-2014
 */
/**
 * Formulario para la validación de lo datos enviados para el registro de horarios laborales.
 * @author JLM
 * @returns {boolean}
 */
function validaFormularioHorarioLaboral() {
    var ok = true;
    var msje = "";
    $(".msjs-alert").hide();
    var idHorarioLaboral = $("#hdnIdHorarioLaboralEditar").val();
    var sufijoEditar="";
    if(idHorarioLaboral>0){
        sufijoEditar="Editar";
    }
    limpiarMensajesErrorPorValidacionHorario(sufijoEditar);
    var enfoque = null;

    var nombre = $("#txtNombreHorario"+sufijoEditar).val();
    var nombreAlternativo = $("#txtNombreAlternativoHorario"+sufijoEditar).val();
    var color = $("#txtColorHorario"+sufijoEditar).val();
    var horaEntHorario = $("#txtHoraEntHorario"+sufijoEditar).val();
    var horaSalHorario = $("#txtHoraSalHorario"+sufijoEditar).val();
    var horaInicioRangoEntrada = $("#txtHoraInicioRangoEnt"+sufijoEditar).val();
    var horaFinalizacionRangoEntrada = $("#txtHoraFinalizacionRangoEnt"+sufijoEditar).val();
    var horaInicioRangoSalida = $("#txtHoraInicioRangoSal"+sufijoEditar).val();
    var horaFinalizacionRangoSalida = $("#txtHoraFinalizacionRangoSal"+sufijoEditar).val();
    var fechaIni = $("#txtFechaIni"+sufijoEditar).val();
    var fechaFin = $("#txtFechaFin"+sufijoEditar).val();

    var divNombreHorario = $("#divNombreHorario"+sufijoEditar);
    var helpErrorNombreHorario = $("#helpErrorNombreHorario"+sufijoEditar);
    var txtNombreHorario = $("#txtNombreHorario"+sufijoEditar);

    var divColorHorario=$("#divColorHorario"+sufijoEditar);
    var helpErrorColorHorario=$("#helpErrorColorHorario"+sufijoEditar);
    var txtColorHorario = $("#txtColorHorario"+sufijoEditar);

    var divHoraEntHorario=$("#divHoraEntHorario"+sufijoEditar);
    var helpErrorHoraEntHorario=$("#helpErrorHoraEntHorario"+sufijoEditar);
    var txtHoraEntHorario = $("#txtHoraEntHorarioEditar"+sufijoEditar);

    var divHoraSalHorario=$("#divHoraSalHorario"+sufijoEditar);
    var helpErrorHoraSalHorario= $("#helpErrorHoraSalHorario"+sufijoEditar);
    var txtHoraSalHorario=$("#txtHoraSalHorario"+sufijoEditar);

    var divHoraInicioRangoEnt = $("#divHoraInicioRangoEnt"+sufijoEditar);
    var helpErrorHoraInicioRangoEnt = $("#helpErrorHoraInicioRangoEnt"+sufijoEditar);
    var txtHoraInicioRangoEnt = $("#txtHoraInicioRangoEnt"+sufijoEditar);

    var divHoraFinalizacionRangoEnt = $("#divHoraFinalizacionRangoEnt"+sufijoEditar);
    var helpErrorHoraFinalizacionRangoEnt = $("#helpErrorHoraFinalizacionRangoEnt"+sufijoEditar);
    var txtHoraFinalizacionRangoEnt = $("#txtHoraFinalizacionRangoEnt"+sufijoEditar);

    var divHoraInicioRangoSal = $("#divHoraInicioRangoSal"+sufijoEditar);
    var helpErrorHoraInicioRangoSal = $("#helpErrorHoraInicioRangoSal"+sufijoEditar);
    var txtHoraInicioRangoSal = $("#txtHoraInicioRangoSal"+sufijoEditar);

    var divHoraFinalizacionRangoSal = $("#divHoraFinalizacionRangoSal"+sufijoEditar);
    var helpErrorHoraFinalizacionRangoSal = $("#helpErrorHoraFinalizacionRangoSal"+sufijoEditar);
    var txtHoraFinalizacionRangoSal = $("#txtHoraFinalizacionRangoSal"+sufijoEditar);

    var divFechaIni = $("#divFechaIni"+sufijoEditar);
    var helpErrorFechaIni = $("#helpErrorFechaIni"+sufijoEditar);
    var txtFechaIni = $("#txtFechaIni"+sufijoEditar);

    var divFechaFin = $("#divFechaFin"+sufijoEditar);
    var helpErrorFechaFin = $("#helpErrorFechaFin"+sufijoEditar);
    var txtFechaFin = $("#txtFechaFin"+sufijoEditar);

    if (nombre == '') {
        ok = false;
        var msje = "Debe introducir la Hora de Entrada y Hora de Salida para posibilitar la generación del nombre para el horario.";
        divNombreHorario.addClass("has-error");
        helpErrorNombreHorario.html(msje);
        //if (enfoque == null)enfoque = txtNombreHorario;
    }
    if(color==''){
        ok = false;
        var msje = "Debe seleccionar un color para el horario necesariamente.";
        divColorHorario.addClass("has-error");
        helpErrorColorHorario.html(msje);
        if (enfoque == null)enfoque = txtColorHorario;
    }
    if(color=='#FFFFFF'){
        ok = false;
        var msje = "Seleccion&oacute; el color blanco para el horario, debe seleccionar un color diferente necesariamente.";
        divColorHorario.addClass("has-error");
        helpErrorColorHorario.html(msje);
        if (enfoque == null)enfoque = txtColorHorario;
    }
    if(horaEntHorario==''){
        ok = false;
        var msje = "Debe seleccionar una hora de entrada necesariamente.";
        divHoraEntHorario.addClass("has-error");
        helpErrorHoraEntHorario.html(msje);
        if (enfoque == null)enfoque = txtHoraEntHorario;
    }
    if(horaSalHorario==''){
        ok = false;
        var msje = "Debe seleccionar una hora de salida necesariamente.";
        divHoraSalHorario.addClass("has-error");
        helpErrorHoraSalHorario.html(msje);
        if (enfoque == null)enfoque = txtHoraSalHorario;
    }
    if(horaInicioRangoEntrada==''){
        ok = false;
        var msje = "Debe introducir la hora de inicio del rango de marcaci&oacute;n para la entrada.";
        divHoraInicioRangoEnt.addClass("has-error");
        helpErrorHoraInicioRangoEnt.html(msje);
        if (enfoque == null)enfoque = txtHoraInicioRangoEnt;
    }
    if(horaFinalizacionRangoEntrada==''){
        ok = false;
        var msje = "Debe introducir la hora de finalizaci&oacute;n del rango de marcaci&oacute;n para la entrada.";
        divHoraFinalizacionRangoEnt.addClass("has-error");
        helpErrorHoraFinalizacionRangoEnt.html(msje);
        if (enfoque == null)enfoque = txtHoraFinalizacionRangoEnt;
    }
    if(horaInicioRangoSalida==''){
        ok = false;
        var msje = "Debe introducir la hora de inicio del rango de marcaci&oacute;n para la salida.";
        divHoraInicioRangoSal.addClass("has-error");
        helpErrorHoraInicioRangoSal.html(msje);
        if (enfoque == null)enfoque = txtHoraInicioRangoSal;
    }
    if(horaFinalizacionRangoSalida==''){
        ok = false;
        var msje = "Debe introducir la hora de finalizaci&oacute;n del rango de marcaci&oacute;n para la salida.";
        divHoraFinalizacionRangoSal.addClass("has-error");
        helpErrorHoraFinalizacionRangoSal.html(msje);
        if (enfoque == null)enfoque = txtHoraFinalizacionRangoSal;
    }
    if (fechaIni == '') {
        ok = false;
        var msje = "Debe seleccionar la fecha de inicio de disponibilidad del horario necesariamente.";
        divFechaIni.addClass("has-error");
        helpErrorFechaIni.html(msje);
        if (enfoque == null)enfoque = txtFechaIni;
    }
    if (fechaFin == '') {
        ok = false;
        var msje = "Debe seleccionar la fecha de finalizaci&oacute;n de disponibilidad del horario necesariamente.";
        divFechaFin.addClass("has-error");
        helpErrorFechaFin.html(msje);
        if (enfoque == null)enfoque = txtFechaFin;
    }
    var sep = "-";
    if (procesaTextoAFecha(fechaFin, sep) < procesaTextoAFecha(fechaIni, sep)) {
        ok = false;
        msje = "La fecha de inicio no puede ser superior a la fecha de finalizaci&oacute;n.";
        divFechaIni.addClass("has-error");
        helpErrorFechaIni.html(msje);
        divFechaFin.addClass("has-error");
        helpErrorFechaFin.html(msje);
        if (enfoque == null)enfoque = txtFechaIni;
    }
    if (enfoque != null) {
        enfoque.focus();
    }
    return ok;
}
/**
 * Función para la limpieza de los mensajes de error debido a la validación del formulario para registro de horario laboral.
 * @sufijoEditar Variable que define la limpieza de variables para el caso de nuevo y edición.
 */
function limpiarMensajesErrorPorValidacionHorario(sufijoEditar) {
    $("#divNombreHorario"+sufijoEditar).removeClass("has-error");
    $("#helpErrorNombreHorario"+sufijoEditar).html("");
    $("#divColorHorario"+sufijoEditar).removeClass("has-error");
    $("#helpErrorColorHorario"+sufijoEditar).html("");
    $("#divHoraEntHorario"+sufijoEditar).removeClass("has-error");
    $("#helpErrorHoraEntHorario"+sufijoEditar).html("");
    $("#divHoraSalHorario"+sufijoEditar).removeClass("has-error");
    $("#helpErrorHoraSalHorario"+sufijoEditar).html("");
    $("#divHoraInicioRangoEnt"+sufijoEditar).removeClass("has-error");
    $("#helpErrorHoraInicioRangoEnt"+sufijoEditar).html("");
    $("#divHoraFinalizacionRangoEnt"+sufijoEditar).removeClass("has-error");
    $("#helpErrorHoraFinalizacionRangoEnt"+sufijoEditar).html("");
    $("#divHoraInicioRangoSal"+sufijoEditar).removeClass("has-error");
    $("#helpErrorHoraInicioRangoSal"+sufijoEditar).html("");
    $("#divHoraFinalizacionRangoSal"+sufijoEditar).removeClass("has-error");
    $("#helpErrorHoraFinalizacionRangoSal"+sufijoEditar).html("");
    $("#divFechaIni"+sufijoEditar).removeClass("has-error");
    $("#helpErrorFechaIni"+sufijoEditar).html("");
    $("#divFechaFin"+sufijoEditar).removeClass("has-error");
    $("#helpErrorFechaFin"+sufijoEditar).html("");
    $("#divControlCruce"+sufijoEditar).removeClass("has-error");
    $("#helpErrorControlCruce"+sufijoEditar).html("");
}
/**
 * Función para guardar el registro del horario.
 * @param idHorario Identificador del horario
 * @returns {boolean}
 */
function guardaHorarioLaboral(){
    var ok = false;
    var idHorarioLaboral = $("#hdnIdHorarioLaboralEditar").val();
    var sufijoEditar = "";
    if(idHorarioLaboral>0)
    {
        sufijoEditar="Editar";
    }
    var nombre = $("#txtNombreHorario"+sufijoEditar).val();
    var nombreAlternativo = $("#txtNombreAlternativoHorario"+sufijoEditar).val();
    var color = $("#txtColorHorario"+sufijoEditar).val();
    var horaEntHorario = $("#txtHoraEntHorario"+sufijoEditar).val();
    var horaSalHorario = $("#txtHoraSalHorario"+sufijoEditar).val();
    var rangoEntrada = 1;
    var rangoSalida = 1;
    var horaInicioRangoEntrada = $("#txtHoraInicioRangoEnt"+sufijoEditar).val();
    var horaFinalizacionRangoEntrada = $("#txtHoraFinalizacionRangoEnt"+sufijoEditar).val();
    var horaInicioRangoSalida = $("#txtHoraInicioRangoSal"+sufijoEditar).val();
    var horaFinalizacionRangoSalida = $("#txtHoraFinalizacionRangoSal"+sufijoEditar).val();
    var fechaIni = $("#txtFechaIni"+sufijoEditar).val();
    var fechaFin = $("#txtFechaFin"+sufijoEditar).val();
    var controlCruce = 0;
    if ($("#chkPermitirCruce" + sufijoEditar).bootstrapSwitch("state")) {
        controlCruce = 1;
    }
    var observacion = $("#txtObservacion"+sufijoEditar).val();
    if (nombre != '' && color != '') {
        $.ajax({
            url: '/horarioslaborales/save/',
            type: "POST",
            datatype: 'json',
            async: false,
            cache: false,
            data: {
                id: idHorarioLaboral,
                nombre: nombre,
                nombre_alternativo: nombreAlternativo,
                color: color,
                hora_entrada: horaEntHorario,
                hora_salida: horaSalHorario,
                rango_entrada:rangoEntrada,
                rango_salida:rangoSalida,
                hora_inicio_rango_ent: horaInicioRangoEntrada,
                hora_final_rango_ent: horaFinalizacionRangoEntrada,
                hora_inicio_rango_sal: horaInicioRangoSalida,
                hora_final_rango_sal: horaFinalizacionRangoSalida,
                fecha_ini:fechaIni,
                fecha_fin:fechaFin,
                agrupador:controlCruce,
                observacion: observacion
            },
            success: function (data) {  //alert(data);
                var res = jQuery.parseJSON(data);
                /**
                 * Si se ha realizado correctamente el registro de la relación laboral y la movilidad
                 */
                if (res.result == 1) {
                    ok = true;
                    $("#divMsjePorSuccess").html("");
                    $("#divMsjePorSuccess").append(res.msj);
                    $("#divMsjeNotificacionSuccess").jqxNotification("open");
                    $("#jqxgridhorarios").jqxGrid("updatebounddata","filter");
                } else if (res.result == 0) {
                    /**
                     * En caso de presentarse un error subsanable
                     */
                    $("#divMsjePorWarning").html("");
                    $("#divMsjePorWarning").append(res.msj);
                    $("#divMsjeNotificacionWarning").jqxNotification("open");
                } else {
                    /**
                     * En caso de haberse presentado un error crítico al momento de registrarse la relación laboral
                     */
                    $("#divMsjePorError").html("");
                    $("#divMsjePorError").append(res.msj);
                    $("#divMsjeNotificacionError").jqxNotification("open");
                }

            }, //mostramos el error
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
 * Función para limpiar los campos correspondientes para el registro de un nuevo horario.
 */
function inicializarCamposParaNuevoRegistro(){
    $("#hdnIdHorarioLaboralEditar").val(0);
    $("#txtNombreHorario").val("");
    $("#txtNombreAlternativoHorario").val("");
    $("#txtColorHorario").val("#FFFFFF");
    $("#txtColorHorario").css({ 'background': 'white','color':'white' });
    $("#txtHoraEntHorario").val("");
    $("#txtHoraSalHorario").val("");
    $("#txtHoraInicioRangoEnt").val("");
    $("#txtHoraFinalizacionRangoEnt").val("");
    $("#txtHoraInicioRangoSal").val("");
    $("#txtHoraFinalizacionRangoSal").val("");
    $("#txtFechaIni").val("");
    $("#txtFechaFin").val("");
    $("#txtFechaFin").val("");
    $("#chkPermitirCruce").bootstrapSwitch();
    $("#chkPermitirCruce").bootstrapSwitch("state", false);
    $("#txtObservacion").val("");
}