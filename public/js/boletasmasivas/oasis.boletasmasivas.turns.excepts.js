/*
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  05-03-2014
 */
/**
 * Función para la carga de excepciones sobre el calendario de acuerdo a un rango de días.
 * @param dataRecord
 * @param fechaIni
 * @param fechaFin
 */
function obtenerArrExcepcionesEnCalendarioPorRango(dataRecord,tipoVista,diasSemana,fechaIni,fechaFin){
    var arrFechasCalendario = obtenerListadoFechasEnCalendario(dataRecord.id_relaboral,tipoVista,diasSemana,fechaIni,fechaFin);
    var arrExcepciones=[];
    $.each(arrFechasCalendario, function (key, val) {
        var fechaIni = val.fecha.split("-");
        var horaIni = val.hora_ini.split(":");
        var horaFin = val.hora_fin.split(":");
        var yi = fechaIni[0];
        var mi = fechaIni[1] - 1;
        var di = fechaIni[2];

        var he = horaIni[0];
        var me = horaIni[1];
        var se = horaIni[2];

        var fechaFin = val.fecha.split("-");
        var yf = fechaFin[0];
        var mf = fechaFin[1] - 1;
        var df = fechaFin[2];

        var hs = horaFin[0];
        var ms = horaFin[1];
        var ss = horaFin[2];
        var prefijo = "excepciones";
        var title = "(e) " + val.excepcion;
        if (val.horario == 1)title += " " + " [" + val.hora_ini + " a " + val.hora_fin + "]";
        arrExcepciones.push({
            id: 0,
            className: prefijo,
            title: title,
            start: new Date(yi, mi, di, he, me),
            end: new Date(yf, mf, df, hs, ms),
            allDay: true,
            color: val.color,
            textColor:"#000",
            editable: false,
            borderColor: "#000",
            horas_laborales: 0,
            dias_laborales: 0,
            hora_entrada: val.hora_ini,
            hora_salida: val.hora_fin
        });
    });
    return arrExcepciones;
}
/**
 * Función para obtener el listado de excpciones en un rango determinado de tiempo
 * @param idRelaboral
 * @param tipoVista
 * @param diasSemana
 * @param fechaIni
 * @param fechaFin
 * @returns {Array}
 */
function obtenerListadoFechasEnCalendario(idRelaboral,tipoVista,diasSemana,fechaIni,fechaFin){
    var arrExcepciones = [];
    $.ajax({
        url:'/controlexcepciones/listporrelaboralyrango',
        type:'POST',
        datatype: 'json',
        cache:false,
        async:false,
        data:{id_relaboral:idRelaboral,fecha_ini:fechaIni,fecha_fin:fechaFin},
        success: function(data) {
            var res = jQuery.parseJSON(data);
            if (res.length > 0) {
                $.each(res, function (key, val) {
                    arrExcepciones.push({
                        fecha:val.fecha,
                        dia:val.dia,
                        dia_nombre:val.dia_nombre,
                        dia_nombre_abr_ing:val.dia_nombre_abr_ing,
                        id_controlexcepcion: val.id_controlexcepcion,
                        id_relaboral: val.id_relaboral,
                        fecha_ini: val.fecha_ini,
                        hora_ini: val.hora_ini,
                        fecha_fin: val.fecha_fin,
                        hora_fin: val.hora_fin,
                        justificacion: val.justificacion,
                        controlexcepcion_observacion: val.controlexcepcion_observacion,
                        controlexcepcion_estado: val.controlexcepcion_estado,
                        controlexcepcion_estado_descripcion: val.controlexcepcion_estado_descripcion,
                        excepcion_id: val.excepcion_id,
                        excepcion: val.excepcion,
                        tipoexcepcion_id: val.tipoexcepcion_id,
                        tipo_excepcion: val.tipo_excepcion,
                        codigo: val.codigo,
                        color: val.color,
                        compensatoria: val.compensatoria,
                        compensatoria_descripcion: val.compensatoria_descripcion,
                        genero_id: val.genero_id,
                        genero: val.genero,
                        cantidad:val.cantidad,
                        unidad:val.unidad,
                        fraccionamiento:val.fraccionamiento,
                        frecuencia_descripcion:val.frecuencia_descripcion,
                        redondeo:val.redondeo,
                        redondeo_descripcion:val.redondeo_descripcion,
                        horario: val.horario,
                        horario_descripcion: val.horario_descripcion
                    });
                });
            }
        }
    });
    return arrExcepciones;
}
/**
 * Función para la carga de los datos correspondientes al horario en el modal respectivo.
 * @param idHorario
 */
function cargarModalHorario(idHorario){
    if(idHorario>0){
        $.ajax({
            url: '/horarioslaborales/getone',
            type: 'POST',
            datatype: 'json',
            async: false,
            cache: false,
            data: {id: idHorario},
            success: function (data) {
                var res = jQuery.parseJSON(data);
                if (res.length > 0) {
                    $.each(res, function (key, val) {
                        $("#txtNombreHorario").val(val.nombre);
                        $("#txtNombreAlternativoHorario").val(val.nombre_alternativo);
                        $("#txtColorHorario").val(val.color);
                        $("#txtColorHorario").css({'background-color':val.color,'color':val.color});
                        $("#txtHoraEntHorario").val(val.hora_entrada);
                        $("#txtHoraSalHorario").val(val.hora_salida);
                        $("#txtHoraInicioRangoEnt").val(val.hora_inicio_rango_ent);
                        $("#txtHoraFinalizacionRangoEnt").val(val.hora_final_rango_ent);
                        $("#txtHoraInicioRangoSal").val(val.hora_inicio_rango_sal);
                        $("#txtHoraFinalizacionRangoSal").val(val.hora_final_rango_sal);
                        if (val.agrupador === 1) {
                            $("#chkPermitirCruce").prop("checked", true);
                        } else {
                            $("#chkPermitirCruce").prop("checked", false);
                        }
                        $("#txtObservacion").val(val.observacion);
                        var hEnt = val.hora_entrada;
                        var hSal = val.hora_salida;
                        if(hEnt=="")hEnt = "00:00:00";
                        if(hSal=="")hSal = "00:00:00";
                        $("#txtHorasLaborales").val(val.horas_laborales);
                    });
                }
            }
        });
        return true;
    }else return false;
}
/**
 * Función para la obtención del registro correspondiente a un horario en específico.
 * @param idHorario
 * @returns {Array}
 */
function obtenerDatosHorario(idHorario){
    var arrHorario = [];
    var prefijo = "r_";
    if(idHorario>0){
        $.ajax({
            url: '/horarioslaborales/getone',
            type: 'POST',
            datatype: 'json',
            async: false,
            cache: false,
            data: {id: idHorario},
            success: function (data) {
                var res = jQuery.parseJSON(data);
                if (res.length > 0) {
                    $.each(res, function (key, val) {
                        arrHorario.push( {
                            nombre:val.nombre,
                            nombre_alternativo:val.nombre_alternativo,
                            color:val.color,
                            hora_entrada:val.hora_entrada,
                            hora_salida:val.hora_salida,
                            hora_inicio_rango_ent:val.hora_inicio_rango_ent,
                            hora_final_rango_ent:val.hora_final_rango_ent,
                            hora_inicio_rango_sal:val.hora_inicio_rango_sal,
                            observacion:val.observacion,
                            horas_laborales:val.horas_laborales,
                            dias_laborales:val.dias_laborales
                        });
                    });
                }
            }
        });

    }
    return arrHorario;
}
/**
 * Función para la obtención de los feriados de acuerdo a un rango de fechas.
 * @param dia
 * @param mes
 * @param gestion
 * @param fechaIni
 * @param fechaFin
 * @returns {Array}
 */
function obtenerFeriadosRangoFechas(dia,mes,gestion,fechaIni,fechaFin){
    var arrFeriados = [];
    var prefijo = "r_";
    if(gestion>0&&fechaIni!=""&&fechaFin!=""){
        $.ajax({
            url: '/feriados/listrange',
            type: 'POST',
            datatype: 'json',
            async: false,
            cache: false,
            data: {dia:dia,mes:mes,gestion:gestion,fecha_ini:fechaIni,fecha_fin:fechaFin},
            success: function (data) {
                var res = jQuery.parseJSON(data);
                if (res.length > 0) {
                    $.each(res, function (key, val) {
                        arrFeriados.push( {
                            id:val.id,
                            feriado:val.feriado,
                            descripcion:val.descripcion,
                            cantidad_dias:val.cantidad_dias,
                            repetitivo:val.repetitivo,
                            dia:val.dia,
                            mes:val.mes,
                            gestion:val.gestion,
                            fecha_ini:val.fecha_ini,
                            fecha_fin:val.fecha_fin,
                            observacion:val.observacion
                        });
                    });
                }
            }
        });

    }
    return arrFeriados;
}
