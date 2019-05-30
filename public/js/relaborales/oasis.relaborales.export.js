/*
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  09-11-2014
 */
function exportarReporte(option) {
    columna = new Object();
    filtros = new Object();
    agrupados = new Object();
    ordenados = new Object();
    ubicacion = $('#jqxgrid').jqxGrid('getcolumn', 'ubicacion');
    condicion = $('#jqxgrid').jqxGrid('getcolumn', 'condicion');
    estado_descripcion = $('#jqxgrid').jqxGrid('getcolumn', 'estado_descripcion');
    tiene_contrato_vigente_descripcion = $('#jqxgrid').jqxGrid('getcolumn', 'tiene_contrato_vigente_descripcion');
    nombres = $('#jqxgrid').jqxGrid('getcolumn', 'nombres');
    ci = $('#jqxgrid').jqxGrid('getcolumn', 'ci');
    expd = $('#jqxgrid').jqxGrid('getcolumn', 'expd');
    edad = $('#jqxgrid').jqxGrid('getcolumn', 'edad');
    genero = $('#jqxgrid').jqxGrid('getcolumn', 'genero');
    fecha_nac = $('#jqxgrid').jqxGrid('getcolumn', 'fecha_nac');
    grupo_sanguineo = $('#jqxgrid').jqxGrid('getcolumn','grupo_sanguineo');
    fecha_cumple = $('#jqxgrid').jqxGrid('getcolumn','fecha_cumple');
    gerencia_administrativa = $('#jqxgrid').jqxGrid('getcolumn','gerencia_administrativa');
    cargo = $('#jqxgrid').jqxGrid('getcolumn','cargo');
    sueldo = $('#jqxgrid').jqxGrid('getcolumn','sueldo');
    departamento_administrativo = $('#jqxgrid').jqxGrid('getcolumn','departamento_administrativo');
    area = $('#jqxgrid').jqxGrid('getcolumn','area');
    fin_partida = $('#jqxgrid').jqxGrid('getcolumn','fin_partida');
    proceso_codigo = $('#jqxgrid').jqxGrid('getcolumn','proceso_codigo');
    nivelsalarial = $('#jqxgrid').jqxGrid('getcolumn','nivelsalarial');
    fecha_ing = $('#jqxgrid').jqxGrid('getcolumn','fecha_ing');
    fecha_ini = $('#jqxgrid').jqxGrid('getcolumn','fecha_ini');
    fecha_incor = $('#jqxgrid').jqxGrid('getcolumn','fecha_incor');
    fecha_fin = $('#jqxgrid').jqxGrid('getcolumn','fecha_fin'),
    fecha_baja = $('#jqxgrid').jqxGrid('getcolumn','fecha_baja');
    motivo_baja = $('#jqxgrid').jqxGrid('getcolumn','motivo_baja');
    interno_inst = $('#jqxgrid').jqxGrid('getcolumn','interno_inst');
    celular_per = $('#jqxgrid').jqxGrid('getcolumn','celular_per');
    celular_inst = $('#jqxgrid').jqxGrid('getcolumn','celular_inst');
    e_mail_per = $('#jqxgrid').jqxGrid('getcolumn','e_mail_per');
    e_mail_inst = $('#jqxgrid').jqxGrid('getcolumn','e_mail_inst');
    cas_fecha_emi = $('#jqxgrid').jqxGrid('getcolumn','cas_fecha_emi');
    cas_fecha_pres = $('#jqxgrid').jqxGrid('getcolumn','cas_fecha_pres');
    cas_fecha_fin_cal = $('#jqxgrid').jqxGrid('getcolumn','cas_fecha_fin_cal');
    cas_numero = $('#jqxgrid').jqxGrid('getcolumn','cas_numero');
    cas_codigo_verificacion = $('#jqxgrid').jqxGrid('getcolumn','cas_codigo_verificacion');
    cas_anios = $('#jqxgrid').jqxGrid('getcolumn','cas_anios');
    cas_meses = $('#jqxgrid').jqxGrid('getcolumn','cas_meses');
    cas_dias = $('#jqxgrid').jqxGrid('getcolumn','cas_dias');
    mt_anios = $('#jqxgrid').jqxGrid('getcolumn','mt_anios');
    mt_meses = $('#jqxgrid').jqxGrid('getcolumn','mt_meses');
    mt_dias = $('#jqxgrid').jqxGrid('getcolumn','mt_dias');
    tot_anios = $('#jqxgrid').jqxGrid('getcolumn','tot_anios');
    tot_meses = $('#jqxgrid').jqxGrid('getcolumn','tot_meses');
    tot_dias = $('#jqxgrid').jqxGrid('getcolumn','tot_dias');
    mt_fin_mes_anios = $('#jqxgrid').jqxGrid('getcolumn','mt_fin_mes_anios');
    mt_fin_mes_meses = $('#jqxgrid').jqxGrid('getcolumn','mt_fin_mes_meses');
    mt_fin_mes_dias = $('#jqxgrid').jqxGrid('getcolumn','mt_fin_mes_dias');
    observacion = $('#jqxgrid').jqxGrid('getcolumn','observacion');

    columna[ubicacion.datafield] = {text: ubicacion.text, hidden: ubicacion.hidden};
    columna[condicion.datafield] = {text: condicion.text, hidden: condicion.hidden};
    columna[estado_descripcion.datafield] = {text: estado_descripcion.text, hidden: estado_descripcion.hidden};
    columna[tiene_contrato_vigente_descripcion.datafield] = {text: tiene_contrato_vigente_descripcion.text, hidden: tiene_contrato_vigente_descripcion.hidden};
    columna[nombres.datafield] = {text: nombres.text, hidden: nombres.hidden};
    columna[ci.datafield] = {text: ci.text, hidden: ci.hidden};
    columna[expd.datafield] = {text: expd.text, hidden: expd.hidden};
    columna[genero.datafield] = {text: genero.text, hidden: genero.hidden};
    columna[edad.datafield] = {text: edad.text, hidden: edad.hidden};
    columna[fecha_nac.datafield] = {text: fecha_nac.text, hidden: fecha_nac.hidden};
    columna[fecha_cumple.datafield] = {text: fecha_cumple.text, hidden: fecha_cumple.hidden};
    columna[grupo_sanguineo.datafield] = {text: grupo_sanguineo.text, hidden: grupo_sanguineo.hidden};
    columna[gerencia_administrativa.datafield] = {text: gerencia_administrativa.text, hidden: gerencia_administrativa.hidden};
    columna[departamento_administrativo.datafield] = {text: departamento_administrativo.text, hidden: departamento_administrativo.hidden};
    columna[area.datafield] = {text: area.text, hidden: area.hidden};
    columna[proceso_codigo.datafield] = {text: proceso_codigo.text, hidden: proceso_codigo.hidden};
    columna[fin_partida.datafield] = {text: fin_partida.text, hidden: fin_partida.hidden};
    columna[cargo.datafield] = {text: cargo.text, hidden: cargo.hidden};
    columna[sueldo.datafield] = {text: sueldo.text, hidden: sueldo.hidden};
    columna[fecha_ing.datafield] = {text: fecha_ing.text, hidden: fecha_ing.hidden};
    columna[fecha_ini.datafield] = {text: fecha_ini.text, hidden: fecha_ini.hidden};
    columna[fecha_incor.datafield] = {text: fecha_incor.text, hidden: fecha_incor.hidden};
    columna[nivelsalarial.datafield] = {text: nivelsalarial.text, hidden: nivelsalarial.hidden};
    columna[fecha_fin.datafield] = {text: fecha_fin.text, hidden: fecha_fin.hidden};
    columna[fecha_baja.datafield] = {text: fecha_baja.text, hidden: fecha_baja.hidden};
    columna[motivo_baja.datafield] = {text: motivo_baja.text, hidden: motivo_baja.hidden};
    columna[interno_inst.datafield] = {text: interno_inst.text, hidden: interno_inst.hidden};
    columna[celular_per.datafield] = {text: celular_per.text, hidden: celular_per.hidden};
    columna[celular_inst.datafield] = {text: celular_inst.text, hidden: celular_inst.hidden};
    columna[e_mail_per.datafield] = {text: e_mail_per.text, hidden: e_mail_per.hidden};
    columna[e_mail_inst.datafield] = {text: e_mail_inst.text, hidden: e_mail_inst.hidden};
    columna[cas_fecha_emi.datafield] = {text: cas_fecha_emi.text, hidden: cas_fecha_emi.hidden};
    columna[cas_fecha_pres.datafield] = {text: cas_fecha_pres.text, hidden: cas_fecha_pres.hidden};
    columna[cas_fecha_fin_cal.datafield] = {text: cas_fecha_fin_cal.text, hidden: cas_fecha_fin_cal.hidden};
    columna[cas_numero.datafield] = {text: cas_numero.text, hidden: cas_numero.hidden};
    columna[cas_codigo_verificacion.datafield] = {text: cas_codigo_verificacion.text, hidden: cas_codigo_verificacion.hidden};
    columna[cas_anios.datafield] = {text: cas_anios.text, hidden: cas_anios.hidden};
    columna[cas_meses.datafield] = {text: cas_meses.text, hidden: cas_meses.hidden};
    columna[cas_dias.datafield] = {text: cas_dias.text, hidden: cas_dias.hidden};
    columna[mt_anios.datafield] = {text: mt_anios.text, hidden: mt_anios.hidden};
    columna[mt_meses.datafield] = {text: mt_meses.text, hidden: mt_meses.hidden};
    columna[mt_dias.datafield] = {text: mt_dias.text, hidden: mt_dias.hidden};
    columna[tot_anios.datafield] = {text: tot_anios.text, hidden: tot_anios.hidden};
    columna[tot_meses.datafield] = {text: tot_meses.text, hidden: tot_meses.hidden};
    columna[tot_dias.datafield] = {text: tot_dias.text, hidden: tot_dias.hidden};
    columna[mt_fin_mes_anios.datafield] = {text: mt_fin_mes_anios.text, hidden: mt_fin_mes_anios.hidden};
    columna[mt_fin_mes_meses.datafield] = {text: mt_fin_mes_meses.text, hidden: mt_fin_mes_meses.hidden};
    columna[mt_fin_mes_dias.datafield] = {text: mt_fin_mes_dias.text, hidden: mt_fin_mes_dias.hidden};
    columna[observacion.datafield] = {text: observacion.text, hidden: observacion.hidden};

    var groups = $('#jqxgrid').jqxGrid('groups');
    if(groups === null || groups === '')groups='null';
    //var sorteds = $('#jqxgrid').jqxGrid('getsortcolumn');

    var sortinformation = $('#jqxgrid').jqxGrid('getsortinformation');
    if(sortinformation.sortcolumn !== undefined){
        // The sortcolumn rep   resents the sort column's datafield. If there's no sort column, the sortcolumn is null.
        var sortcolumn = sortinformation.sortcolumn;
        // The sortdirection is an object with two fields: 'ascending' and 'descending'. Ex: { 'ascending': true, 'descending': false }
        var sortdirection = sortinformation.sortdirection;
        ordenados[sortcolumn] = {asc: sortdirection.ascending, desc: sortdirection.descending};
    }else ordenados='';

    var gestion_consulta = $("#lstGestion").val();
    var rows = $('#jqxgrid').jqxGrid('getrows');
    var filterGroups = $('#jqxgrid').jqxGrid('getfilterinformation');
    var counter = 0;
    for (var i = 0; i < filterGroups.length; i++) {
        var filterGroup = filterGroups[i];
        var filters = filterGroup.filter.getfilters();
        for (var j = 0; j < filters.length; j++) {
            if (j>0){
                counter++;
            }
            var indice = i+counter;
            filtros[indice] = {columna: filterGroup.filtercolumn, valor: filters[j].value,
                condicion: filters[j].condition, tipo: filters[j].type};
        }
    }
    var n_rows = rows.length;
    var json_filter = JSON.stringify(filtros);
    var json_columns = JSON.stringify(columna);
    var json_sorteds = JSON.stringify(ordenados);
    json_columns = btoa(utf8_encode(json_columns));
    json_filter = btoa(utf8_encode(json_filter));
    json_sorteds = btoa(utf8_encode(json_sorteds));
    var json_groups =  btoa(utf8_encode(groups));

    json_columns= json_columns.replace(/\+/g, '-').replace(/\//g, '_').replace(/\=+$/, '');
    json_filter= json_filter.replace(/\+/g, '-').replace(/\//g, '_').replace(/\=+$/, '');
    json_groups= json_groups.replace(/\+/g, '-').replace(/\//g, '_').replace(/\=+$/, '');
    json_sorteds= json_sorteds.replace(/\+/g, '-').replace(/\//g, '_').replace(/\=+$/, '');
    var ruta='';
    switch (option){
        case 1: ruta="/relaborales/exportexcel/";break;
        case 2: ruta="/relaborales/exportpdf/";break;
    }
    if(ruta!='')
        window.open(ruta+n_rows+"/"+gestion_consulta+"/"+json_columns+"/"+json_filter+"/"+json_groups+"/"+json_sorteds ,"_blank");
}