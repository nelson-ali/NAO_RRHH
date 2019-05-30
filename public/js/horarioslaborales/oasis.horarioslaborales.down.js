/*
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  22-12-2014
 */
/**
 * Función para registrar la baja del registro de horario laboral.
 */
function darDeBajaHorarioLaboral(idHorarioLaboral){
    var ok=true;
    if(idHorarioLaboral>0){
        var ok=$.ajax({
            url:'/horarioslaborales/down/',
            type:'POST',
            datatype: 'json',
            async:false,
            data:{id:idHorarioLaboral},
            success: function(data) {  //alert(data);
                var res = jQuery.parseJSON(data);
                //alert(data);
                /**
                 * Si se ha realizado correctamente el registro de baja del horario laboral.
                 */
                $(".msjes").hide();
                if(res.result==1){
                    $("#divMsjePorSuccess").html("");
                    $("#divMsjePorSuccess").append(res.msj);
                    $("#divMsjeNotificacionSuccess").jqxNotification("open");
                    $("#jqxgridhorarios").jqxGrid("updatebounddata");
                } else if(res.result==0){
                    /**
                     * En caso de haberse presentado un error al momento de registrar la baja por inconsistencia de datos.
                     */
                    $("#divMsjePorWarning").html("");
                    $("#divMsjePorWarning").append(res.msj);
                    $("#divMsjeNotificacionWarning").jqxNotification("open");
                }else{
                    /**
                     * En caso de haberse presentado un error crítico al momento de registrarse la baja (Error de conexión)
                     */
                    $("#divMsjePorError").html("");
                    $("#divMsjePorError").append(res.msj);
                    $("#divMsjeNotificacionError").jqxNotification("open");
                }

            }, //mostramos el error
            error: function() { alert('Se ha producido un error Inesperado'); }
        });
    }else {
        ok = false;
    }
    return ok;
}
