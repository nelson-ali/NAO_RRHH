/*
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  05-11-2014
 */
/**
 * Función para la obtención del listado de gestiones donde la persona, referenciada mediante su identificador enviado como parámetro, haya tenido relación laboral con la empresa.
 * @param idPersona Identificador de la persona de la cual se desea obtener la información.
 * @returns {Array} Array conteniendo el listado de gestiones.
 */
function obtenerGestionesParaHistorial(idPersona) {
    var gestiones = [];
    $('#HistorialSplitter').jqxSplitter('expand');
    $.ajax({
        url: '/relaborales/listgestionesporpersona',
        type: 'POST',
        datatype: 'json',
        async: false,
        cache: false,
        data: {id: idPersona},
        success: function (data) {
            var res = jQuery.parseJSON(data);
            if (res.length > 0) {
                $.each(res, function (key, val) {
                    gestiones.push(val.gestion);
                });
            }
        }
    });
    return gestiones;
}
/**
 * Función para cargar el listado de años en los cuales la persona (Identificada por el parámetro idPersona) ha trabajado o trabaja en la empresa.
 * @param idPersona Identificador de la persona de la cual se desea obtener la información respectiva.
 */
function cargarGestionesHistorialRelaboral(idPersona) {
    /**
     * Es necesario eliminar todos los eventos almacenados sobre este elemento para que no se vuelvan a repetir.
     */
    $("#listboxGestiones").off();
    var gestiones = obtenerGestionesParaHistorial(idPersona);
    /*
     * La primera vez, por defecto la ventana donde estan las gestiones se expande
     */
    $('#HistorialSplitter').jqxSplitter('expand');
    // Creando el jqxListBox
    $("#listboxGestiones").jqxListBox({width: 200, source: gestiones, checkboxes: true, height: '100%'});
    // Se checkean todos los items por defecto, pues se mostrará el total del historial por defecto.
    if (gestiones.length > 0) {
        $.each(gestiones, function (key, val) {
            $("#hdnSwPrimeraVistaHistorial").val(0);
            $("#listboxGestiones").jqxListBox('checkIndex', key);
            $("#hdnSwPrimeraVistaHistorial").val(0);
        });
    }
    /*
     * En caso de que la persona tenga registros de relación laboral en una sóla gestión, el listado de gestiones se oculta
     */
    if (gestiones.length == 1) {
        $('#HistorialSplitter').jqxSplitter('collapse');
    }
    var idPersonaHistorial = $("#hdnIdPersonaHistorial").val();
    $("#listboxGestiones").on('checkChange', function (event) {
        //if($("#hdnSwPrimeraVistaHistorial").val()==1){
                var cantidadGestiones = 0;
                var args = event.args;
                var items = $("#listboxGestiones").jqxListBox('getCheckedItems');
                var checkedItems = "";
                $.each(items, function (index) {
                    if (index < items.length - 1) {
                        checkedItems += this.label + ", ";
                    }
                    else checkedItems += this.label;
                    cantidadGestiones++;
                });
                /**
                 * Si la cantidad de gestiones seleccionadas es igual a la cantidad total de gestiones disponibles
                 */
                if (cantidadGestiones == gestiones.length) {
                    cargarHistorialRelacionLaboral(idPersonaHistorial, 0, 1);
                }
                else {
                    $('#HistorialTimeLine').html("");
                    if (cantidadGestiones > 0) {
                        var gestionesSeleccionadas = checkedItems.split(",");
                        $.each(gestionesSeleccionadas, function (index, value) {
                            cargarHistorialRelacionLaboral(idPersonaHistorial, value, 0);
                        });
                    }
                }
            //}
            $("#hdnSwPrimeraVistaHistorial").val(1);
    });
}
/**
 * Función para cargar el historial de relación laboral para una persona en particular considerando un gestión específica (Si el valor para el parámetro gestion es mayor a cero)
 * @param idPersona Identificador de la persona de la cual se requiere obtener el historial.
 * @param gestion Gestión específica de la cual se desea obtener la información. En caso de que su valor sea cero el historial mostrará el total del historial.
 * @param sw Valor que indica si se debe limpiar el historial que se tiene o hacer una adición al historial.
 */
function cargarHistorialRelacionLaboral(idPersona, gestion, sw) {
    var gestiones = [];
    if (sw == 1)$('#HistorialTimeLine').html("");
    var historial = "";
    $.ajax({
        url: '/relaborales/listhistorial',
        type: 'POST',
        datatype: 'json',
        async: false,
        cache: false,
        data: {id: idPersona, gestion: gestion},
        success: function (data) {
            var res = jQuery.parseJSON(data);
            if (res.length > 0) {

                $.each(res, function (key, val) {
                    if (val.estado == 1) {
                        historial += "<li class='active'>";
                        historial += "<div class='timeline-icon'>";
                    } else if(val.estado == 2) {
                        historial += "<li>";
                        historial += "<div class='timeline-icon'>";
                    }else{
                        historial += "<li class='active'>";
                        historial += "<div class='timeline-icon themed-background-fire themed-border-fire'>";
                    }

                    historial += "<i class='fa fa-file-text' title='Registro de Relaci&oacute;n Laboral'></i></div>";
                    historial += "<div class='timeline-time'><a href='#' id='divContent_" + val.id_relaboral + "'>" + val.fecha_ini + "</a><strong></strong></div>";
                    historial += "<div class='timeline-content'>";
                    historial += "<p class='push-bit'><strong id='strCargo_" + val.id_relaboral + "'>" + val.cargo + "</strong></p>";
                    historial += "<dl class='dl-horizontal'>";
                    historial += "<dt id='dtProceso_" + val.id_relaboral + "'>Proceso:</dt><dd id='ddProceso_" + val.id_relaboral + "'>" + val.proceso_codigo + "</dd>";
                    //historial += "<dt id='dtFinPartida_" + val.id_relaboral + "'>Financiamiento:</dt><dd id='ddFinPartida_" + val.id_relaboral + "'>" + val.condicion + " (Partida " + val.partida + ")</dd>";
                    historial += "<dt id='dtFinPartida_" + val.id_relaboral + "'>Fuente:</dt><dd id='ddFinPartida_" + val.id_relaboral + "'>" + val.fin_partida + "</dd>";
                    historial += "<dt id='dtGerencia_" + val.id_relaboral + "'>Gerencia:</dt><dd id='ddGerencia_" + val.id_relaboral + "'>" + val.gerencia_administrativa + "</dd>";
                    if (val.departamento_administrativo != "")historial += "<dt id='dtDepartamento_" + val.id_relaboral + "'>Dependencia:</dt><dd id='ddDepartamento_" + val.id_relaboral + "'>" + val.departamento_administrativo + "</dd>";
                    if(val.id_area>0)historial += "<dt id='dtArea_" + val.id_relaboral + "'>&Aacute;rea:</dt><dd id='ddArea_" + val.id_relaboral + "'>" + val.area + "</dd>";
                    historial += "<dt id='dtUbicacion_" + val.id_relaboral + "'>Ubicaci&oacute;n:</dt><dd id='ddUbicacion_" + val.id_relaboral + "'>" + val.ubicacion + "</dd>";
                    switch (val.tiene_item){
                        case 1:
                            historial += "<dt id='dtItem_" + val.id_relaboral + "'>&Iacute;tem:</dt><dd id='ddItem_" + val.id_relaboral + "'>" + val.cargo_codigo + "</dd>";
                            break;
                        case 0:
                            var numContrato = '&nbsp;';
                            if(val.num_contrato!=null)numContrato = val.num_contrato;
                            historial += "<dt id='dtNumContrato_" + val.id_relaboral + "'>Nro. de Contrato:</dt><dd id='ddNumContrato_" + val.id_relaboral + "'>" + numContrato + "</dd>";
                            break;
                    }
                    historial += "<dt id='dtNivelSalarial_" + val.id_relaboral + "'>Nivel Salarial:</dt><dd id='ddNivelSalarial_" + val.id_relaboral + "'>" + val.nivelsalarial + "</dd>";
                    /*historial +="<dt id='dtCargo_"+val.id_relaboral+"'>Cargo:</dt><dd id='ddCargo_'>ssssssss</dd>";*/
                    historial += "<dt id='dtHaber_" + val.id_relaboral + "'>Haber:</dt><dd id='ddHaber_" + val.id_relaboral + "'>" + val.sueldo + "</dd>";
                    if(val.fecha_ing!=null&&val.fecha_ing!=""){
                        historial += "<dt id='dtFechaIng_" + val.id_relaboral + "'>Fecha Ingreso:</dt><dd id='ddFechaIng_" + val.id_relaboral + "'>" + val.fecha_ing + "</dd>";
                    }else{
                        historial += "<dt id='dtFechaIng_" + val.id_relaboral + "'>Fecha Ingreso:</dt><dd id='ddFechaIng_" + val.id_relaboral + "'>&nbsp;</dd>";
                    }
                    if(val.fecha_ini!=null&&val.fecha_ini!=""){
                        historial += "<dt id='dtFechaIni_" + val.id_relaboral + "'>Fecha Inicio:</dt><dd id='ddFechaIni_" + val.id_relaboral + "'>" + val.fecha_ini + "</dd>";
                    }else{
                        historial += "<dt id='dtFechaIni_" + val.id_relaboral + "'>Fecha Inicio:</dt><dd id='ddFechaIni_" + val.id_relaboral + "'>&nbsp;</dd>";
                    }
                    if(val.fecha_incor!=null&&val.fecha_incor!=""){
                        historial += "<dt id='dtFechaIncor_" + val.id_relaboral + "'>Fecha Incor:</dt><dd id='ddFechaIncor_" + val.id_relaboral + "'>" + val.fecha_incor + "</dd>";
                    }else{
                        historial += "<dt id='dtFechaIncor_" + val.id_relaboral + "'>Fecha Incor:</dt><dd id='ddFechaIncor_" + val.id_relaboral + "'>&nbsp;</dd>";
                    }
                    switch (val.tiene_item){
                         case 1:break;
                         case 0:
                         historial += "<dt id='dtFechaFin_" + val.id_relaboral + "'>Fecha Fin:</dt><dd id='ddFechaFin_" + val.id_relaboral + "'>" + val.fecha_fin + "</dd>";
                         break;
                   }
                    if(val.estado == 0){
                        historial += "<dt id='dtFechaBaja_" + val.id_relaboral + "'>Fecha Baja:</dt><dd id='ddFechaBaja_" + val.id_relaboral + "'>" + val.fecha_baja + "</dd>";
                        historial += "<dt id='dtMotivoBaja_" + val.id_relaboral + "'>Motivo Baja:</dt><dd id='ddMotivoBaja_" + val.id_relaboral + "'>" + val.motivo_baja + "</dd>";
                    }
                    historial += "<dt id='dtContratoEstado_" + val.id_relaboral + "'>Estado:</dt>";
                    historial += "<dd id='ddContratoEstado_" + val.id_relaboral + "'>";
                    historial += "<strong>" + val.estado_descripcion + "</strong></dd>";
                    historial += "<dt id='dtObservacion_" + val.id_relaboral + "'>Observaciones:</dt><dd id='ddObservacion_" + val.id_relaboral + "'>" + val.observacion + "</dd>";
                    historial += "</dl>";
                    historial += cargarHistorialRelacionLaboralMovilidad(val.id_relaboral);
                });
                $('#HistorialTimeLine').append(historial);
            }
        }
    });
}
/**
 * Función para cargar el historial de movilidad funcionaria por registro de relación laboral.
 * @param idRelaboral Identificador del registro de relación laboral.
 */
function cargarHistorialRelacionLaboralMovilidad(idRelaboral) {
    var historial = "";
    $.ajax({
        url: '/relaborales/listhistorialmovilidad',
        type: 'GET',
        datatype: 'json',
        async: false,
        cache: false,
        data: {id: idRelaboral},
        success: function (data) {
            var res = jQuery.parseJSON(data);
            if (res.length > 0) {

                $.each(res, function (key, val) {
                    if (val.estado == 1) {
                        historial += "<li class='active'>";
                        historial += "<div class='timeline-icon'>";
                    }else{
                        historial += "<li class='active'>";
                        historial += "<div class='timeline-icon themed-background-fire themed-border-fire'>";
                    }
                   if(val.id_tipomemorandum==4){
                       historial += "<i class='gi gi-airplane' title='Memor&aacute;ndum'></i></div>";
                   }else if(val.id_tipomemorandum==5){
                       historial += "<i class='hi hi-tree_conifer' title='Memor&aacute;ndum'></i></div>";
                   }else{
                       historial += "<i class='fa fa-tag' title='Memor&aacute;ndum'></i></div>";
                   }
                    historial += "<div class='timeline-time'><a href='#' id='divContentMovilidad_" + val.id_relaboralmovilidad + "'>" + val.fecha_ini + "</a><strong></strong></div>";
                    historial += "<div class='timeline-content'>";
                    historial += "<p class='push-bit'>"+val.tipo_memorandum+": <strong id='strCargoMotivoMovilidad_" + val.id_relaboralmovilidad + "'>";
                    if(val.cargo!=null){
                        historial += val.cargo;
                    }
                    else if(val.motivo!=''){
                        historial += val.motivo;
                    }
                    historial += "</strong></p>";
                    historial += "<dl class='dl-horizontal'>";
                    if(val.gerencia_administrativa!=null&&val.gerencia_administrativa!='')historial += "<dt id='dtGerenciaMovilidad_" + val.id_relaboralmovilidad + "'>Gerencia:</dt><dd id='ddGerenciaMovilidad_" + val.id_relaboralmovilidad + "'>" + val.gerencia_administrativa + "</dd>";
                    if (val.departamento_administrativo!=null&&val.departamento_administrativo != "")historial += "<dt id='dtDepartamentoMovilidad_" + val.id_relaboralmovilidad + "'>Dependencia:</dt><dd id='ddDepartamentoMovilidad_" + val.id_relaboralmovilidad + "'>" + val.departamento_administrativo + "</dd>";
                    if(val.id_area>0)historial += "<dt id='dtAreaMovilidad_" + val.id_relaboralmovilidad + "'>&Aacute;rea:</dt><dd id='ddAreaMovilidad_" + val.id_relaboralmovilidad + "'>" + val.area + "</dd>";
                    if(val.ubicacion!=null&&val.ubicacion!='')historial += "<dt id='dtUbicacionMovilidad_" + val.id_relaboralmovilidad + "'>Ubicaci&oacute;n:</dt><dd id='ddUbicacionMovilidad_" + val.id_relaboralmovilidad + "'>" + val.ubicacion + "</dd>";
                    historial += "<dt id='dtFechaIniMovilidad_" + val.id_relaboralmovilidad + "'>Fecha Inicio:</dt><dd id='ddFechaIniMovilidad_" + val.id_relaboralmovilidad + "'>" + val.fecha_ini + "</dd>";
                    if(val.hora_ini!=null&&val.hora_ini!='')historial += "<dt id='dtHoraIniMovilidad_" + val.id_relaboralmovilidad + "'>Hora Ini:</dt><dd id='ddHoraIniMovilidad_" + val.id_relaboralmovilidad + "'>" + val.hora_ini + "</dd>";
                    if(val.fecha_fin!='')historial += "<dt id='dtFechaFinMovilidad_" + val.id_relaboralmovilidad + "'>Fecha Fin:</dt><dd id='ddFechaFinMovilidad_" + val.id_relaboralmovilidad + "'>" + val.fecha_fin + "</dd>";
                    if(val.hora_fin!=null&&val.hora_fin!='')historial += "<dt id='dtHoraFinMovilidad_" + val.id_relaboralmovilidad + "'>Hora Fin:</dt><dd id='ddHoraFinMovilidad_" + val.id_relaboralmovilidad + "'>" + val.hora_fin + "</dd>";
                    historial += "<dt id='dtMemorandumMovilidad_" + val.id_relaboralmovilidad + "'>Memor&aacute;ndum:</dt><dd id='ddMemorandumMovilidad_" + val.id_relaboralmovilidad +"'>"+ val.memorandum_correlativo +"/" + val.memorandum_gestion + " de " + val.fecha_mem+"</dd>";
                    if(val.id_tipomemorandum==4||val.id_tipomemorandum==5){
                        if(val.pais!=null&&val.pais!='')historial += "<dt id='dtPaisMovilidad_" + val.id_relaboralmovilidad + "'>Pa&iacute;s:</dt><dd id='ddPaisMovilidad_" + val.id_relaboralmovilidad +"'>"+ val.pais +"</dd>";
                        if(val.departamento!=null&&val.departamento!='')historial += "<dt id='dtCiudadMovilidad_" + val.id_relaboralmovilidad + "'>Ciudad:</dt><dd id='ddPaisMovilidad_" + val.id_relaboralmovilidad +"'>"+ val.departamento +"</dd>";
                        if(val.lugar!=null&&val.lugar!='')historial += "<dt id='dtLugarMovilidad_" + val.id_relaboralmovilidad + "'>Lugar:</dt><dd id='ddLugarMovilidad_" + val.id_relaboralmovilidad +"'>"+ val.lugar +"</dd>";
                    }
                    historial += "<dt id='dtObservacionMovilidad_" + val.id_relaboralmovilidad + "'>Observaciones:</dt><dd id='ddObservacionMovilidad_" + val.id_relaboralmovilidad + "'>" + val.observacion + "</dd>";
                    historial += "</dl>";
                });

            }
        }
    });
    return historial;
}