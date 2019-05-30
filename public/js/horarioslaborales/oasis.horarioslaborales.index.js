/*
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  19-12-2014
 */
$().ready(function () {
    /**
     * Inicialmente se habilita solo la pestaña del listado
     */
    $('#jqxTabsHorarios').jqxTabs('theme', 'oasis');
    $('#jqxTabsHorarios').jqxTabs('enableAt', 0);
    $('#jqxTabsHorarios').jqxTabs('disableAt', 1);
    $('#jqxTabsHorarios').jqxTabs('disableAt', 2);
    $('#jqxTabsHorarios').jqxTabs('disableAt', 3);

    definirGrillaParaListaHorarios();
    /**
     * Control del evento de solicitud de guardar el registro del horario.
     */
    $("#btnGuardarHorarioNuevo").click(function () {
        var ok = validaFormularioHorarioLaboral()
        if (ok) {
            var okk = guardaHorarioLaboral();
            if (okk) {
                $('#jqxTabsHorarios').jqxTabs('enableAt', 0);
                $('#jqxTabsHorarios').jqxTabs('disableAt', 1);
                $('#jqxTabsHorarios').jqxTabs('disableAt', 2);
                $('#jqxTabsHorarios').jqxTabs('disableAt', 3);
                $("#msjs-alert").hide();
            }
        }
    });
    $("#btnGuardarHorarioEditar").click(function () {
        var ok = validaFormularioHorarioLaboral();
        if (ok) {
            var okk = guardaHorarioLaboral();
            if (okk) {
                $('#jqxTabsHorarios').jqxTabs('enableAt', 0);
                $('#jqxTabsHorarios').jqxTabs('disableAt', 1);
                $('#jqxTabsHorarios').jqxTabs('disableAt', 2);
                $('#jqxTabsHorarios').jqxTabs('disableAt', 3);
                $("#msjs-alert").hide();
            }
        }
    });
    $("#btnGuardarHorarioBaja").click(function () {
        var ok = validaFormularioPorBajaRegistro();
        if (ok) {
            guardarRegistroBajaPerfilLaboral();
        }
    });

    $("#btnCancelarHorarioNuevo").click(function () {
        $('#jqxTabsHorarios').jqxTabs('enableAt', 0);
        $('#jqxTabsHorarios').jqxTabs('disableAt', 1);
        $('#jqxTabsHorarios').jqxTabs('disableAt', 2);
        $('#jqxTabsHorarios').jqxTabs('disableAt', 3);
        $("#msjs-alert").hide();

    });
    $("#btnCancelarHorarioEditar").click(function () {
        $('#jqxTabsHorarios').jqxTabs('enableAt', 0);
        $('#jqxTabsHorarios').jqxTabs('disableAt', 1);
        $('#jqxTabsHorarios').jqxTabs('disableAt', 2);
        $('#jqxTabsHorarios').jqxTabs('disableAt', 3);
        $("#msjs-alert").hide();
    });
    $("#btnExportarExcel").click(function () {
        var items = $("#jqxlistbox").jqxListBox('getCheckedItems');
        var numColumnas = 0;
        $.each(items, function (index, value) {
            numColumnas++;
        });
        if (numColumnas > 0) exportarReporte(1);
        else {
            alert("Debe seleccionar al menos una columna para la obtención del reporte solicitado.");
            $("#jqxlistbox").focus();
        }
    });
    $("#btnExportarPDF").click(function () {
        var items = $("#jqxlistbox").jqxListBox('getCheckedItems');
        var numColumnas = 0;
        $.each(items, function (index, value) {
            numColumnas++;
        });
        if (numColumnas > 0) exportarReporte(2);
        else {
            alert("Debe seleccionar al menos una columna para la obtención del reporte solicitado.");
            $("#jqxlistbox").focus();
        }
    });
    $("#chkAllCols").click(function () {
        if (this.checked == true) {
            $("#jqxlistbox").jqxListBox('checkAll');
        } else {
            $("#jqxlistbox").jqxListBox('uncheckAll');
        }
    });

    $("#liList").click(function () {
        $("#btnCancelarHorarioNuevo").click();
        $("#btnCancelarHorarioEditar").click();
    });
    $('#txtHoraEntHorario').off();
    var inputEntradaNew = $('#txtHoraEntHorario').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });
    $("#aHoraEntrada").off();
    $("#aHoraEntrada").on("click", function (e) {
        e.stopPropagation();
        inputEntradaNew.clockpicker('show');
    });
    $("#txtHoraEntHorario").on("click", function () {
        inputEntradaNew.clockpicker('show');
    });

    $('#txtHoraSalHorario').off();
    var inputSalidaNew = $('#txtHoraSalHorario').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now',
        afterDone: function () {
            if ($("#txtHoraSalHorario").val() == '00:00' || $("#txtHoraSalHorario").val() == '00:00:00')
                $("#txtHoraSalHorario").val("23:59");
        }
    });
    $("#txtHoraSalHorario").on("click", function () {
        inputSalidaNew.clockpicker('show');
    });
    /**
     * Funciónes de control agregadas debido a que el registro de la hora '00:00' y similares
     * para la hora de salida provoca errores al momento del registro.
     */
    $("#txtHoraSalHorario").on("click", function () {
        if ($("#txtHoraSalHorario").val() == '00:00' || $("#txtHoraSalHorario").val() == '24:00' || $("#txtHoraSalHorario").val() == '00:00:00' || $("#txtHoraSalHorario").val() == '24:00:00') {
            $("#txtHoraSalHorario").val("23:59");
        }
    });
    $("#txtHoraSalHorario").on("blur", function () {
        if ($("#txtHoraSalHorario").val() == '00:00' || $("#txtHoraSalHorario").val() == '24:00' || $("#txtHoraSalHorario").val() == '00:00:00' || $("#txtHoraSalHorario").val() == '24:00:00') {
            $("#txtHoraSalHorario").val("23:59");
        }
    });
    $("#txtHoraSalHorarioEditar").on("click", function () {
        if ($("#txtHoraSalHorarioEditar").val() == '00:00' || $("#txtHoraSalHorario").val() == '24:00' || $("#txtHoraSalHorario").val() == '00:00:00' || $("#txtHoraSalHorario").val() == '24:00:00') {
            $("#txtHoraSalHorarioEditar").val("23:59");
        }
    });
    $("#txtHoraSalHorarioEditar").on("blur", function () {
        if ($("#txtHoraSalHorarioEditar").val() == '00:00' || $("#txtHoraSalHorario").val() == '24:00' || $("#txtHoraSalHorario").val() == '00:00:00' || $("#txtHoraSalHorario").val() == '24:00:00') {
            $("#txtHoraSalHorarioEditar").val("23:59");
        }
    });

    $("#aHoraSalidaEditar").off();
    $("#aHoraSalidaEditar").on("click", function (e) {
        e.stopPropagation();
        inputSalidaNew.clockpicker('show');
    });
    var inputEntradaEditar = $('#txtHoraEntHorarioEditar').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });
    $("#aHoraEntradaEditar").off();
    $("#aHoraEntradaEditar").on("click", function (e) {
        e.stopPropagation();
        inputEntradaEditar.clockpicker('show');
    });
    var inputSalidaEditar = $('#txtHoraSalHorarioEditar').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });
    $("#aHoraSalidaEditar").off();
    $("#aHoraSalidaEditar").on("click", function (e) {
        e.stopPropagation();
        inputSalidaEditar.clockpicker('show');
    });
    $(".hora-entrada-salida").on("change", function () {
        determinaNombreHorario(1);
        aplicarCalculosParaRangoMarcaciones(1);
    });
    $("#txtHoraFinalizacionRangoEnt").off();
    $("#txtHoraFinalizacionRangoEnt").on("change", function () {
        var horaInicioSalida = sumarMinutosSegundosAHora($("#txtHoraFinalizacionRangoEnt").val(), 0, 1);
        $("#txtHoraInicioRangoSal").val(horaInicioSalida);
    });
    $(".hora-entrada-salida-editar").on("change", function () {
        determinaNombreHorario(2);
        aplicarCalculosParaRangoMarcaciones(2);
    });
    $("#txtHoraFinalizacionRangoEntEditar").off();
    $("#txtHoraFinalizacionRangoEntEditar").on("change", function () {
        var horaInicioSalida = sumarMinutosSegundosAHora($("#txtHoraFinalizacionRangoEntEditar").val(), 0, 1);
        $("#txtHoraInicioRangoSalEditar").val(horaInicioSalida);
    });
    /*
     *   Función para la inserción obligatoria de datos numéricos en los campos de clase.
     */
    $('.numeral').keyup(function (event) {
        if ($(this).val() != '') {
            $(this).val($(this).val().replace(/[^0-9]/g, ""));
        }
    });

    /*
     *   Función para la inserción obligatoria de letras distintas a números en los campos de clase.
     */
    $('.literal').keyup(function (event) {
        if ($(this).val() != '') {
            $(this).val($(this).val().replace(/[^A-Z,a-z,ñ,Ñ, ]/g, ""));
        }
    });
    $("#divMsjeNotificacionError").jqxNotification({
        width: '100%', position: "bottom-right", opacity: 0.9,
        autoOpen: false, animationOpenDelay: 800, autoClose: true, autoCloseDelay: 7000, template: "error"
    });

    $("#divMsjeNotificacionWarning").jqxNotification({
        width: '100%', position: "bottom-right", opacity: 0.9,
        autoOpen: false, animationOpenDelay: 800, autoClose: true, autoCloseDelay: 7000, template: "warning"
    });
    $("#divMsjeNotificacionSuccess").jqxNotification({
        width: '100%', position: "bottom-right", opacity: 0.9,
        autoOpen: false, animationOpenDelay: 800, autoClose: true, autoCloseDelay: 7000, template: "success"
    });

    $(document).keypress(OperaEvento);
    $(document).keyup(OperaEvento);
});
/**
 * Función para definir la grilla principal (listado) para la gestión de relaciones laborales.
 */
function definirGrillaParaListaHorarios() {
    var fecha = new Date();
    var gestionActual = fecha.getFullYear();
    var source =
    {
        datatype: "json",
        datafields: [
            {name: 'nro_row', type: 'integer'},
            {name: 'id_horariolaboral', type: 'integer'},
            {name: 'nombre', type: 'string'},
            {name: 'nombre_alternativo', type: 'string'},
            {name: 'hora_entrada', type: 'string'},
            {name: 'hora_salida', type: 'string'},
            {name: 'horas_laborales', type: 'numeric'},
            {name: 'dias_laborales', type: 'numeric'},
            {name: 'rango_entrada', type: 'integer'},
            {name: 'rango_salida', type: 'integer'},
            {name: 'hora_inicio_rango_ent', type: 'time'},
            {name: 'hora_final_rango_ent', type: 'time'},
            {name: 'hora_inicio_rango_sal', type: 'time'},
            {name: 'hora_final_rango_sal', type: 'time'},
            {name: 'color', type: 'string'},
            {name: 'fecha_ini', type: 'date'},
            {name: 'fecha_fin', type: 'date'},
            {name: 'observacion', type: 'string'},
            {name: 'estado', type: 'string'},
            {name: 'estado_descripcion', type: 'string'},
            {name: 'agrupador', type: 'integer'},
            {name: 'agrupador_descripcion', type: 'string'}
        ],
        url: '/horarioslaborales/list',
        cache: false
    };
    var dataAdapter = new $.jqx.dataAdapter(source);
    cargarRegistrosDeRelacionesLaborales();
    function cargarRegistrosDeRelacionesLaborales() {
        $("#jqxgridhorarios").jqxGrid(
            {
                width: '100%',
                height: 660,
                source: dataAdapter,
                sortable: true,
                altRows: true,
                groupable: true,
                columnsresize: true,
                pageable: true,
                pagesize: 20,
                pagerMode: 'advanced',
                showfilterrow: true,
                filterable: true,
                showtoolbar: true,
                autorowheight: true,
                enablebrowserselection: true,
                ready:function(){
                    applyFilter('31-12-'+gestionActual,'31-12-'+gestionActual);
                },
                rendertoolbar: function (toolbar) {
                    var me = this;
                    var container = $("<div></div>");
                    toolbar.append(container);
                    container.append("<button id='addrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-plus-square fa-2x text-primary' title='Nuevo Registro.'/></i></button>");
                    container.append("<button id='approverowbutton'  class='btn btn-sm btn-primary' type='button' ><i class='fa fa-check-square fa-2x text-info' title='Aprobar registro'></i></button>");
                    container.append("<button id='updaterowbutton'  class='btn btn-sm btn-primary' type='button' ><i class='fa fa-pencil-square fa-2x text-success' title='Modificar registro.'/></button>");
                    container.append("<button id='deleterowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-minus-square fa-2x text-danger' title='Dar de baja al registro.'/></i></button>");
                    container.append("<button title='Refrescar Grilla' id='refreshbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-repeat fa-2x text-default' title='Refrescar grilla.'/></i></button>");
                    container.append("<button title='Desagrupar.' id='cleargroupsrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-chain-broken fa-2x text-default' title='Desagrupar.'/></i></button>");
                    container.append("<button title='Desfiltrar.' id='clearfiltersrowbutton' class='btn btn-sm btn-primary' type='button'><i class='gi gi-sorting fa-2x text-default' title='Desfiltrar.'/></i></button>");
                    /*container.append("<button id='viewrowbutton' class='btn btn-sm btn-primary' type='button'><i class='hi hi-time fa-2x text-info' title='Vista Historial.'/></i></button>");*/

                    $("#addrowbutton").jqxButton();
                    $("#approverowbutton").jqxButton();
                    $("#updaterowbutton").jqxButton();
                    $("#deleterowbutton").jqxButton();
                    $("#refreshbutton").jqxButton();
                    $("#cleargroupsrowbutton").jqxButton();
                    $("#clearfiltersrowbutton").jqxButton();

                    /* Registrar nueva relación laboral.*/
                    $("#addrowbutton").off();
                    $("#addrowbutton").on('click', function () {
                        inicializarCamposParaNuevoRegistro();
                        limpiarMensajesErrorPorValidacionHorario("");
                        $("#txtNombreAlternativoHorario").focus();
                        //inicializarPaleta($("#txtColorHorario"));
                        $("#txtColorHorario").colorpicker()
                            .on('change.color', function (evt, color) {
                                $(this).css("background", color);
                                $(this).css("color", color);
                                $(".evo-pointer").hide();
                            });
                        $(".evo-pointer").hide();
                        $('#jqxTabsHorarios').jqxTabs('enableAt', 1);
                        $('#jqxTabsHorarios').jqxTabs('disableAt', 2);
                        $('#jqxTabsHorarios').jqxTabs('disableAt', 3);
                        $('#jqxTabsHorarios').jqxTabs({selectedItem: 1});
                    });
                    /*Aprobar registro.*/
                    $("#approverowbutton").off();
                    $("#approverowbutton").on('click', function () {
                        var selectedrowindex = $("#jqxgridhorarios").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var dataRecord = $('#jqxgridhorarios').jqxGrid('getrowdata', selectedrowindex);
                            if (dataRecord != undefined) {
                                /*
                                 * La aprobación de un registro es admisible si el estado del registro es EN PROCESO.
                                 */
                                if (dataRecord.estado == 2) {
                                    if (confirm("¿Esta seguro de aprobar este horario?")) {
                                        aprobarRegistroHorarioLaboral(dataRecord.id_horariolaboral);
                                    }
                                } else {
                                    var msje = "Debe seleccionar un registro con estado EN PROCESO para posibilitar la aprobaci&oacute;n del registro";
                                    $("#divMsjePorError").html("");
                                    $("#divMsjePorError").append(msje);
                                    $("#divMsjeNotificacionError").jqxNotification("open");
                                }
                            }
                        } else {
                            var msje = "Debe seleccionar un registro necesariamente.";
                            $("#divMsjePorError").html("");
                            $("#divMsjePorError").append(msje);
                            $("#divMsjeNotificacionError").jqxNotification("open");
                        }
                    });
                    /* Modificar registro.*/
                    $("#updaterowbutton").off();
                    $("#updaterowbutton").on('click', function () {
                        var selectedrowindex = $("#jqxgridhorarios").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var dataRecord = $('#jqxgridhorarios').jqxGrid('getrowdata', selectedrowindex);
                            if (dataRecord != undefined) {
                                $("#hdnIdHorarioLaboralEditar").val(dataRecord.id_horariolaboral);
                                /**
                                 * La modificación sólo es admisible si el registro de horario laboral tiene estado EN PROCESO
                                 */
                                if (dataRecord.estado >= 1) {
                                    limpiarMensajesErrorPorValidacionHorario("Editar");
                                    $("#txtNombreHorario").focus();
                                    $("#txtNombreHorarioEditar").val(dataRecord.nombre);
                                    $("#txtNombreAlternativoHorarioEditar").val(dataRecord.nombre_alternativo);
                                    $("#txtColorHorarioEditar").val(dataRecord.color);
                                    $("#txtColorHorarioEditar").css({
                                        'background-color': dataRecord.color,
                                        'color': dataRecord.color
                                    });
                                    $("#txtHoraEntHorarioEditar").val(dataRecord.hora_entrada);
                                    $("#txtHoraSalHorarioEditar").val(dataRecord.hora_salida);
                                    $("#txtHoraInicioRangoEntEditar").val(dataRecord.hora_inicio_rango_ent);
                                    $("#txtHoraFinalizacionRangoEntEditar").val(dataRecord.hora_final_rango_ent);
                                    $("#txtHoraInicioRangoSalEditar").val(dataRecord.hora_inicio_rango_sal);
                                    $("#txtHoraFinalizacionRangoSalEditar").val(dataRecord.hora_final_rango_sal);
                                    $("#txtFechaIniEditar").datepicker("update", dataRecord.fecha_ini);
                                    $("#txtFechaFinEditar").datepicker("update", dataRecord.fecha_fin);
                                    if(dataRecord.agrupador===1){
                                        $("#chkPermitirCruceEditar").bootstrapSwitch("state", true);
                                    }else{
                                        $("#chkPermitirCruceEditar").bootstrapSwitch("state", false);
                                    }
                                    $("#txtObservacionEditar").val(dataRecord.observacion);
                                    //inicializarPaleta($("#txtColorHorarioEditar"));
                                    $("#txtColorHorarioEditar").colorpicker()
                                        .on('change.color', function (evt, color) {
                                            $(this).css("background", color);
                                            $(this).css("color", color);
                                            $(".evo-pointer").hide();
                                        });
                                    $(".evo-pointer").hide();
                                    var horaEntrada = dataRecord.hora_entrada;
                                    var horaSalida = dataRecord.hora_salida;
                                    if (horaEntrada == "")horaEntrada = "00:00:00";
                                    if (horaSalida == "")horaSalida = "00:00:00";
                                    var cantidadHorasLaborales = calcularCantidadHorasLaborales(horaEntrada, horaSalida);
                                    $("#txtHorasLaboralesEditar").val(cantidadHorasLaborales);
                                    $('#jqxTabsHorarios').jqxTabs('enableAt', 0);
                                    $('#jqxTabsHorarios').jqxTabs('disableAt', 1);
                                    $('#jqxTabsHorarios').jqxTabs('enableAt', 2);
                                    $('#jqxTabsHorarios').jqxTabs('disableAt', 3);
                                    /**
                                     * Trasladamos el item seleccionado al que corresponde, el de modificación
                                     */
                                    $('#jqxTabsHorarios').jqxTabs({selectedItem: 2});
                                    $("#txtNombreAlternativoHorario").focus();
                                } else {
                                    var msje = "Debe seleccionar un registro en estado EN PROCESO o ACTIVO necesariamente.";
                                    $("#divMsjePorError").html("");
                                    $("#divMsjePorError").append(msje);
                                    $("#divMsjeNotificacionError").jqxNotification("open");
                                }
                            }
                        } else {
                            var msje = "Debe seleccionar un registro necesariamente.";
                            $("#divMsjePorError").html("");
                            $("#divMsjePorError").append(msje);
                            $("#divMsjeNotificacionError").jqxNotification("open");
                        }
                    });
                    /* Dar de baja un registro.*/
                    $("#deleterowbutton").off();
                    $("#deleterowbutton").on('click', function () {
                        var selectedrowindex = $("#jqxgridhorarios").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var dataRecord = $('#jqxgridhorarios').jqxGrid('getrowdata', selectedrowindex);
                            if (dataRecord != undefined) {
                                var id_horariolaboral = dataRecord.id_horariolaboral;
                                /*
                                 *  Para dar de baja un registro, este debe estar inicialmente en estado ACTIVO
                                 */
                                if (dataRecord.estado >= 1) {
                                    if (confirm("Esta seguro de dar de baja registro de horario laboral?"))
                                        darDeBajaHorarioLaboral(id_horariolaboral);
                                } else {
                                    var msje = "Para dar de baja un registro, este debe estar en estado ACTIVO o EN PROCESO inicialmente.";
                                    $("#divMsjePorError").html("");
                                    $("#divMsjePorError").append(msje);
                                    $("#divMsjeNotificacionError").jqxNotification("open");
                                }
                            }
                        } else {
                            var msje = "Debe seleccionar un registro necesariamente.";
                            $("#divMsjePorError").html("");
                            $("#divMsjePorError").append(msje);
                            $("#divMsjeNotificacionError").jqxNotification("open");
                        }
                    });
                    $("#refreshbutton").off();
                    $("#refreshbutton").on("click", function () {
                        $("#jqxgridhorarios").jqxGrid("updatebounddata");
                    });
                    $("#cleargroupsrowbutton").off();
                    $("#cleargroupsrowbutton").on("click", function () {
                        $("#jqxgridhorarios").jqxGrid('cleargroups');
                    });
                    $("#clearfiltersrowbutton").off();
                    $("#clearfiltersrowbutton").on("click", function () {
                        $("#jqxgridhorarios").jqxGrid('clearfilters');
                    });
                },
                columns: [
                    {
                        text: 'Nro.',
                        filterable: false,
                        columntype: 'number',
                        width: 50,
                        cellsalign: 'center',
                        align: 'center',
                        cellsrenderer: rownumberrenderer
                    },
                    {
                        text: 'Estado',
                        filtertype: 'checkedlist',
                        datafield: 'estado_descripcion',
                        width: 100,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: false,
                        cellclassname: cellclass
                    },
                    {
                        text: 'Color',
                        datafield: 'color',
                        width: 100,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: false,
                        cellsrenderer: cellsrenderer

                    },
                    {
                        text: 'Nombre',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'nombre',
                        width: 100,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Nombre Alternativo',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'nombre_alternativo',
                        width: 200,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Hora Entrada',
                        filtertype: 'checkedlist',
                        datafield: 'hora_entrada',
                        width: 100,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Hora Salida',
                        filtertype: 'checkedlist',
                        datafield: 'hora_salida',
                        width: 100,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Horas Laborales',
                        filtertype: 'checkedlist',
                        datafield: 'horas_laborales',
                        width: 100,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: true
                    },
                    {
                        text: 'D&iacute;as Laborales',
                        filtertype: 'checkedlist',
                        datafield: 'dias_laborales',
                        width: 100,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: true
                    },
                    {
                        text: 'Hora Inicio Rango Entrada',
                        filtertype: 'checkedlist',
                        datafield: 'hora_inicio_rango_ent',
                        width: 100,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: true
                    },
                    {
                        text: 'Hora Final Rango Entrada',
                        filtertype: 'checkedlist',
                        datafield: 'hora_final_rango_ent',
                        width: 100,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: true
                    },
                    {
                        text: 'Hora Inicio Rango Salida',
                        filtertype: 'checkedlist',
                        datafield: 'hora_inicio_rango_sal',
                        width: 100,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: true
                    },
                    {
                        text: 'Hora Final Rango Salida',
                        filtertype: 'checkedlist',
                        datafield: 'hora_final_rango_sal',
                        width: 100,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: true
                    },
                    {
                        text: 'Fecha Inicio',
                        datafield: 'fecha_ini',
                        filtertype: 'range',
                        width: 80,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Fecha Fin',
                        datafield: 'fecha_fin',
                        filtertype: 'range',
                        width: 80,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Permitir Cruce',
                        filtertype: 'checkedlist',
                        datafield: 'agrupador_descripcion',
                        width: 100,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: false
                    },
                    {text: 'Observaci&oacute;n', datafield: 'observacion', width: 200, hidden: false},
                ]
            });
        var listSource = [
            {label: 'Estado', value: 'estado_descripcion', checked: true},
            {label: 'Color', value: 'color', checked: true},
            {label: 'Nombre', value: 'nombre', checked: true},
            {label: 'Nombre Alternativo', value: 'nombre_alternativo', checked: true},
            {label: 'Hora Entrada', value: 'hora_entrada', checked: true},
            {label: 'Hora Salida', value: 'hora_salida', checked: true},
            {label: 'Horas Laborales', value: 'horas_laborales', checked: false},
            {label: 'D&iacute;as Laborales', value: 'dias_laborales', checked: false},
            {label: 'Hora Inicio Rango Entrada', value: 'hora_inicio_rango_ent', checked: false},
            {label: 'Hora Final Rango Entrada', value: 'hora_final_rango_ent', checked: false},
            {label: 'Hora Inicio Rango Salida', value: 'hora_inicio_rango_sal', checked: false},
            {label: 'Hora Final Rango Salida', value: 'hora_final_rango_sal', checked: false},
            {label: 'Fecha Ini', value: 'fecha_ini', checked: true},
            {label: 'Fecha Fin', value: 'fecha_fin', checked: true},
            {label: 'Permitir Cruce', value: 'agrupador_descripcion', checked: true},
            {label: 'Observacion', value: 'observacion', checked: true},
        ];
        $("#jqxlistbox").jqxListBox({source: listSource, width: "100%", height: 580, checkboxes: true});
        $("#jqxlistbox").on('checkChange', function (event) {
            $("#jqxgridhorarios").jqxGrid('beginupdate');
            if (event.args.checked) {
                $("#jqxgridhorarios").jqxGrid('showcolumn', event.args.value);
            }
            else {
                $("#jqxgridhorarios").jqxGrid('hidecolumn', event.args.value);
            }
            $("#jqxgridhorarios").jqxGrid('endupdate');
        });
    }

}

/**
 * Función para aplicar el filtro de fecha finalización al último día del año del sistema.
 * @param from
 * @param to
 */
var applyFilter = function (from, to) {
    $("#jqxgridhorarios").jqxGrid("clearfilters");
    var filtertype = "datefilter";
    var filtergroup = new $.jqx.filter();
    var filter_or_operator = 0;
    var filtervalueFrom = from;
    var filterconditionFrom = "GREATER_THAN_OR_EQUAL";
    var filterFrom = filtergroup.createfilter(filtertype, filtervalueFrom, filterconditionFrom);
    filtergroup.addfilter(filter_or_operator, filterFrom);
    var filtervalueTo = to;
    var filterconditionTo = "LESS_THAN_OR_EQUAL";
    var filterTo = filtergroup.createfilter(filtertype, filtervalueTo, filterconditionTo);
    filtergroup.addfilter(filter_or_operator, filterTo);
    $("#jqxgridhorarios").jqxGrid("addfilter", "fecha_fin", filtergroup);
    $("#jqxgridhorarios").jqxGrid("applyfilters");
};
var rownumberrenderer = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
    var nro = row + 1;
    return "<div align='center'>" + nro + "</div>";
}
/*
 * Función para controlar la ejecución del evento esc con el teclado.
 */
function OperaEvento(evento) {
    if ((evento.type == "keyup" || evento.type == "keydown") && evento.which == "27") {
        $('#jqxTabsHorarios').jqxTabs('enableAt', 0);
        $('#jqxTabsHorarios').jqxTabs('disableAt', 1);
        $('#jqxTabsHorarios').jqxTabs('disableAt', 2);
        $('#jqxTabsHorarios').jqxTabs('disableAt', 3);
        /**
         * Saltamos a la pantalla principal en caso de presionarse ESC
         */
        $('#jqxTabsHorarios').jqxTabs({selectedItem: 0});
    }
}
/**
 * Función para convertir un texto con el formato dd-MM-yyyy al formato MM/dd/yyyy
 * @param date Cadena con la fecha
 * @param sep Separador
 * @returns {number}
 */
function procesaTextoAFecha(date, sep) {
    var parts = date.split(sep);
    var date = new Date(parts[1] + "/" + parts[0] + "/" + parts[2]);
    return date.getTime();
}
/**
 * Función para calcular la cantidad de horas laborales transcurridas entre la hora de entrada y la hora de salida, el resultado se expresa en términos numéricos.
 * @param horaEntrada
 * @param horaSalida
 * @returns {number}
 */
function calcularCantidadHorasLaborales(horaEntrada, horaSalida) {
    if (horaEntrada != "" && horaSalida != "") {
        var horaEnt = numeroHoras(horaEntrada);
        var horaSal = numeroHoras(horaSalida);
        var calculo = 0;
        if (parseFloat(horaSal) >= parseFloat(horaEnt)) {
            calculo = parseFloat(horaSal) - parseFloat(horaEnt);
        }
        else {
            var aux = 24 - parseFloat(horaEnt);
            calculo = aux + parseFloat(horaSal);
        }
        return calculo.toFixed(2);
    } else return 0;
}
/**
 * Función para calcular el número de horas que representa la hora.
 * @param hora
 * @returns {*}
 */
function numeroHoras(hora) {
    if (hora != "") {
        var arrHora = hora.split(":");
        var hEnt = parseFloat(arrHora[0]);
        var mEnt = parseFloat(arrHora[1]);
        var sEnt = parseFloat(arrHora[2]);
        var sEnMin = 0;
        var mEnHor = 0;
        if (sEnt > 0) {
            sEnMin = sEnt / 60;
        }
        mEnt = mEnt + sEnMin;
        if (mEnt > 0) {
            mEnHor = mEnt / 60;
        }
        hEnt = hEnt + mEnHor;
        return hEnt;
    }
    else return 0;
}
/**
 * Función anónima para la aplicación de clases a celdas en particular dentro de las grillas.
 * @param row
 * @param columnfield
 * @param value
 * @returns {string}
 * @author JLM
 */
var cellclass = function (row, columnfield, value) {
    if (value == 'ACTIVO') {
        return 'verde';
    }
    else if (value == 'EN PROCESO') {
        return 'amarillo';
    }
    else if (value == 'PASIVO') {
        return 'rojo';
    }
    else return ''
}
/**
 * Función para la definición de la columna en función del valor almacenado en la columna.
 * @param row
 * @param column
 * @param value
 * @param defaultHtml
 * @returns {*}
 */
var cellsrenderer = function (row, column, value, defaultHtml) {
    var element = $(defaultHtml);
    element.css({'background-color': value});
    return element[0].outerHTML;
    return defaultHtml;
}
/**
 * Función para determinar el nombre del horario en base a la Hora de Entrada y Hora de Salida.
 */
function determinaNombreHorario(opcion) {
    var sufijo = "";
    if (opcion == 2)sufijo = "Editar";
    var nombre = "";
    var horaEntrada = $("#txtHoraEntHorario" + sufijo).val();
    var horaSalida = $("#txtHoraSalHorario" + sufijo).val();
    if (horaSalida == '00:00' || horaSalida == '00:00:00')horaSalida = '23:59';
    if (horaEntrada != "" && horaSalida != "") {
        var arrHoraEntrada = horaEntrada.split(":");
        horaEntrada = arrHoraEntrada[0] + ":" + arrHoraEntrada[1];
        var arrHoraSalida = horaSalida.split(":");
        horaSalida = arrHoraSalida[0] + ":" + arrHoraSalida[1];
        nombre = horaEntrada + " A " + horaSalida;
    }
    $("#txtNombreHorario" + sufijo).val(nombre);
}
/**
 * Función para sumar una determinada cantidad de minutos y/o segundos a una hora en específico.
 * @param horaInicial Hora a la cual se hara la suma de minutos.
 * @param minutosSumar Valor que determina la cantidad de minutos que se debe adicionar a la Hora Inicial.
 * @param segundosSumar Valor que determina la cantidad de segundos que se debe adicionar a la hora Inicial.
 * @param segundosAlFinal Número de segundos que se desea ver en el retorno del resultado.
 */
function sumarMinutosSegundosAHora(horaInicial, minutosSumar, segundosSumar) {
    var nuevaHora = "";
    $.ajax({
        url: '/horarioslaborales/sumarminutosahora/',
        type: 'POST',
        datatype: 'html',
        async: false,
        data: {
            hora_inicial: horaInicial,
            minutos_sumar: minutosSumar,
            segundos_sumar: segundosSumar
        },
        success: function (data) {
            if (data != '') {
                nuevaHora = data;
            }
        }
    });
    return nuevaHora;
}
/**
 * Función para efectuar los cálculos para los rangos de marcación.
 * @param opcion
 */
function aplicarCalculosParaRangoMarcaciones(opcion) {
    var sufijo = "";
    if (opcion == 2)sufijo = "Editar";
    var horaEntrada = $("#txtHoraEntHorario" + sufijo).val();
    var horaSalida = $("#txtHoraSalHorario" + sufijo).val();
    if (horaEntrada == "")horaEntrada = "00:00:00";
    if (horaSalida == "")horaSalida = "00:00:00";
    var cantidadHorasLaborales = calcularCantidadHorasLaborales(horaEntrada, horaSalida);
    $("#txtHorasLaborales" + sufijo).val(cantidadHorasLaborales);
    var horaInicioEntrada = sumarMinutosSegundosAHora($("#txtHoraEntHorario" + sufijo).val(), -60, 0);
    if (horaInicioEntrada != '') {
        $("#txtHoraInicioRangoEnt" + sufijo).val(horaInicioEntrada);
    }
    var auxCantidadHorasLaborales = parseFloat(cantidadHorasLaborales);
    if (auxCantidadHorasLaborales > 0) {
        var mitadCantidadHorasLaborales = parseFloat(auxCantidadHorasLaborales / 2);
        var mitadCantidadMinutosLaborales = parseFloat(mitadCantidadHorasLaborales * 60);
        var horaFinEntrada = sumarMinutosSegundosAHora($("#txtHoraEntHorario" + sufijo).val(), mitadCantidadMinutosLaborales, 0);
        $("#txtHoraFinalizacionRangoEnt" + sufijo).val(horaFinEntrada);
        var horaInicioSalida = sumarMinutosSegundosAHora($("#txtHoraFinalizacionRangoEnt" + sufijo).val(), 0, 1);
        $("#txtHoraInicioRangoSal" + sufijo).val(horaInicioSalida);
    }
    $("#txtHoraFinalizacionRangoSal" + sufijo).val("23:59:59");
}