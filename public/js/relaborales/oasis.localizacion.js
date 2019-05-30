var localizationobj = {};
localizationobj.pagergotopagestring = "Ir A:";
localizationobj.pagershowrowsstring = "Mostrar fila:";
localizationobj.pagerrangestring = " de ";
localizationobj.pagernextbuttonstring = "anterior";
localizationobj.pagerpreviousbuttonstring = "siguiente";
localizationobj.sortascendingstring = "Ordenar Ascendentemente";
localizationobj.sortdescendingstring = "Ordenar Descendentemente";
localizationobj.sortremovestring = "Retire Ordenar";
localizationobj.groupbystring = "Agrupar por";
localizationobj.groupremovestring= "Remover Agrupadores",
localizationobj.firstDay = 1;
localizationobj.percentsymbol = "%";
localizationobj.currencysymbol = "Bs.";
localizationobj.currencysymbolposition = "antes";
localizationobj.decimalseparator = ".";
localizationobj.thousandsseparator = ",";
var filterstringcomparisonoperators = [
    'vacio', 'no vacio', 'contiene', 'contiene(match case)',
    'no contiene', 'no contiene(match case)', 'empieza con', 'empieza con(match case)',
    'termina con', 'termina con(match case)', 'igual', 'igual(match case)', 'null', 'no null'];
localizationobj.filterstringcomparisonoperators= filterstringcomparisonoperators;
var days = {
    // full day names
    names: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
    // abbreviated day names
    namesAbbr: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
    // shortest day names
    namesShort: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"]
};
localizationobj.days = days;
var months = {
    // full month names (13 months for lunar calendards -- 13th month should be "" if not lunar)
    names: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre", ""],
    // abbreviated month names
    namesAbbr: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic", ""]
};
localizationobj.months = months;