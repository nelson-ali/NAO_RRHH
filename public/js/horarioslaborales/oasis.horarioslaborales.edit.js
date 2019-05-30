/*
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  22-12-2014
 */

/**
 * Función para la limpieza de los mensajes de error debido a la validación del formulario para edición de registro.
 */
function limpiarMensajesErrorPorValidacionEditarRegistro() {
    $("#helpErrorUbicacionesEditar").html("");
    $("#helpErrorProcesosEditar").html("");
    $("#helpErrorCategoriasEditar").html("");
    $("#helpErrorNumContratosEditar").html("");
    $("#helpErrorItemsEditar").html("");
    $("#helpErrorFechasIniEditar").html("");
    $("#helpErrorFechasIncorEditar").html("");
    $("#helpErrorFechasFinEditar").html("");
    $("#divUbicacionesEditar").removeClass("has-error");
    $("#divProcesosEditar").removeClass("has-error");
    $("#divCategoriasEditar").removeClass("has-error");
    $("#divFechasIniEditar").removeClass("has-error");
    $("#divFechasIncorEditar").removeClass("has-error");
    $("#divFechasFinEditar").removeClass("has-error");
    $("#divNumContratosEditar").removeClass("has-error");
    $("#divItemsEditar").removeClass("has-error");
    $("#tr_cargo_seleccionado_editar").html("");
}
/**
 * Función para el almacenamiento de un nuevo registro en la Base de Datos.
 */
function guardarRegistroEditado(){
    var ok=true;
    var id_relaboral = $("#hdnIdRelaboralEditar").val();
    var item=0;
    var idArea = 0;
    /*
     Si se ha definido la opción de registro de áreas
     */
    if($("#lstAreasEditar").val()!=null){
        idArea =$("#lstAreasEditar").val();
    }
    var idRegional = 1;
    var idPersona = $("#hdnIdPersonaSeleccionadaEditar").val();
    var idCargo = $("#hdnIdCargoSeleccionadoEditar").val();
    var idUbicacion = $('#lstUbicacionesEditar').val();
    var idProceso = $('#lstProcesosEditar').val();
    //var idCategoria = $('#lstCategorias').val();
    var idCondicion = $("#hdnIdCondicionEditableSeleccionada").val();
    var numContrato=  '';
    //Si la condición de la relación laboral es consultoría se requiere que se llene el campo del número de contrato.
    var fechaFin=null;
    if(idCondicion==2||idCondicion==3){
        numContrato =  $("#txtNumContratoEditar").val();
        var fechaFin = $('#FechaFinEditar').jqxDateTimeInput('getText');
    }
    var fechaIni = $('#FechaIniEditar').jqxDateTimeInput('getText');
    var fechaIncor = $('#FechaIncorEditar').jqxDateTimeInput('getText');
    var observacion = $("#txtObservacionEditar").val();
    if(id_relaboral>0&&idPersona>0&&idCargo>0){
        var ok=$.ajax({
            url:'/relaborales/save/',
            type:'POST',
            datatype: 'json',
            async:false,
            data:{id:id_relaboral,
                id_persona:idPersona,
                id_cargo:idCargo,
                num_contrato:numContrato,
                id_area:idArea,
                id_ubicacion:idUbicacion,
                id_regional:idRegional,
                id_procesocontratacion:idProceso,
                fecha_inicio:fechaIni,
                fecha_incor:fechaIncor,
                fecha_fin:fechaFin,
                observacion:observacion
            },
            success: function(data) {  //alert(data);

                var res = jQuery.parseJSON(data);
                /**
                 * Si se ha realizado correctamente el registro de la relación laboral
                 */
                $(".msjes").hide();
                if(res.result==1){
                    $("#divMsjeExito").show();
                    $("#divMsjeExito").addClass('alert alert-success alert-dismissable');
                    $("#aMsjeExito").html(res.msj);
                    /**
                     * Se habilita nuevamente el listado actualizado con el registro realizado y
                     * se inhabilita el formulario para nuevo registro.
                     */
                    $('#jqxTabs').jqxTabs('enableAt', 0);
                    $('#jqxTabs').jqxTabs('disableAt', 1);
                    $('#jqxTabs').jqxTabs('disableAt', 2);
                    $('#jqxTabs').jqxTabs('disableAt', 3);
                    deshabilitarCamposParaEditarRegistroDeRelacionLaboral();
                    $("#jqxgrid").jqxGrid("updatebounddata");
                } else if(res.result==0){
                    /**
                     * En caso de haberse presentado un error al momento de especificar la ubicación del trabajo
                     */
                    $("#divMsjePeligro").show();
                    $("#divMsjePeligro").addClass('alert alert-warning alert-dismissable');
                    $("#aMsjePeligro").html(res.msj);
                }else{
                    /**
                     * En caso de haberse presentado un error crítico al momento de registrarse la relación laboral
                     */
                    $("#divMsjeError").show();
                    $("#divMsjeError").addClass('alert alert-danger alert-dismissable');
                    $("#aMsjeError").html(res.msj);
                }

            }, //mostramos el error
            error: function() { alert('Se ha producido un error Inesperado'); }
        });
    }else {
        ok = false;
    }
    return ok;
}
