/**
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  2.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  18-08-2016
 */
/**
 * Función para el envío de correo electrónico de aviso de nueva incorporación o baja.
 * @param idRelaboral
 * @param operacion
 * @returns {boolean}
 */
function enviarMensajePorOperacion(idRelaboral, operacion) {
    var ok = false;
    var mensajeAdicional = CKEDITOR.instances.txtEditorMensaje.getData();
    $.ajax({
        url: '/relaborales/sendmessage/',
        type: "POST",
        datatype: 'json',
        data: {
            id: idRelaboral, msj: mensajeAdicional, operacion: operacion
        },
        async: false,
        cache: false,
        beforeSend: function () {
            $("#divCarga").show();

        },
        complete: function () {
            $("#divCarga").hide();
        },
        success: function (data) {
            var res = jQuery.parseJSON(data);
            if (res.result == 1) {
                ok = true;
                $("#divMsjePorSuccess").html("");
                $("#divMsjePorSuccess").append(res.msj);
                $("#divMsjeNotificacionSuccess").jqxNotification("open");
            } else if (res.result == 0) {
                ok = false;
                $("#divMsjePorWarning").html("");
                $("#divMsjePorWarning").append(res.msj);
                $("#divMsjeNotificacionWarning").jqxNotification("open");
            } else {
                ok = false;
                $("#divMsjePorError").html("");
                $("#divMsjePorError").append(res.msj);
                $("#divMsjeNotificacionError").jqxNotification("open");
            }
        }
    });
    return ok;
}
/**
 * Función para definir la lista de destinatarios.
 * @param idRelaboral
 * @returns {boolean}
 */
function defineListaDestinatarios(idRelaboral) {
    var ok = false;
    $("#ulListaDestinatarios").html("");
    $.ajax({
        url: '/relaborales/getdestinatarios/',
        type: "POST",
        datatype: 'json',
        async: false,
        cache: false,
        data: {id: idRelaboral},
        success: function (data) {
            var res = jQuery.parseJSON(data);
            $.each(res, function (key, val) {
                ok = true;
                $('#ulListaDestinatarios').append("<li>&bull; " + val.nombres + "</li>");
            });
        }
    });
    return ok;
}