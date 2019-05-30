/*
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  22-10-2014
 */
/**
 * Función para definir el contenido de la grilla de cargos acéfalos de acuerdo a los datos enviados como parámetros.
 */
function definirGrillaParaSeleccionarCargoAcefalo(numCertificacion, codCargo, filtrables) {
    var gestion = $("#lstGestion").val();
    var sourceCargo =
        {
            datatype: "json",
            datafields: [
                {name: 'seleccionable', type: 'string'},
                {name: 'codigo', type: 'string'},
                /*{ name: 'finpartida', type: 'string' },*/
                {name: 'id_condicion', type: 'string'},
                {name: 'condicion', type: 'string'},
                {name: 'id_organigrama', type: 'integer'},
                {name: 'id_cargo', type: 'string'},
                {name: 'gerencia_administrativa', type: 'string'},
                {name: 'departamento_administrativo', type: 'string'},
                {name: 'nivelsalarial', type: 'string'},
                {name: 'cargo', type: 'string'},
                {name: 'sueldo', type: 'number'},
                {name: 'asistente', type: 'integer'},
                {name: 'jefe', type: 'integer'},
                {name: 'id_resolucion_ministerial', type: 'integer'},
                {name: 'resolucion_ministerial', type: 'string'},
                {name: 'nivelsalarial_resolucion_id', type: 'integer'},
                {name: 'nivelsalarial_resolucion', type: 'string'},
                {name: 'gestion', type: 'integer'},
                {name: 'correlativo', type: 'string'}
            ],
            url: '/relaborales/listcargosbygestion?gestion=' + gestion,
            cache: false,
            root: 'Rows',
            beforeprocessing: function (data) {
                sourceCargo.totalrecords = data[0].TotalRows;
            },
            filter: function () {
                // Actualiza la grilla y reenvia los datos actuales al servidor
                $("#divGrillaParaSeleccionarCargo").jqxGrid('updatebounddata', 'filter');
            },
            sort: function () {
                // Actualiza la grilla y reenvia los datos actuales al servidor
                $("#divGrillaParaSeleccionarCargo").jqxGrid('updatebounddata', 'sort');
            }
        };
    var dataAdapterCargo = new $.jqx.dataAdapter(sourceCargo);
    cargarRegistrosDeCargos();
    function cargarRegistrosDeCargos() {
        var fecha = new Date();
        var anio = fecha.getFullYear();
        var gestionCargo = [];
        if (gestion == 0) {
            var c = 0;
            for (var i = 2014; i <= anio; i++) {
                gestionCargo[c] = i + "";
                c++;
            }
        }
        $("#divGrillaParaSeleccionarCargo").jqxGrid(
            {
                width: '100%',
                height: '100%',
                source: dataAdapterCargo,
                sortable: true,
                altRows: true,
                groupable: false,
                columnsresize: true,
                pageable: true,
                pagerMode: 'advanced',
                showfilterrow: true,
                filterable: true,
                showtoolbar: true,
                autorowheight: true,
                enablebrowserselection: true,
                virtualmode: true,
                rendergridrows: function (params) {
                    return params.data;
                },
                rendertoolbar: function (toolbar) {
                    var me = this;
                    var container = $("<div></div>");
                    toolbar.append(container);
                    //container.append("<button title='Seleccionar cargo.' id='selectcgrowbutton'  class='btn btn-sm btn-primary' type='button' ><i class='fa fa-check-square fa-2x text-info' title='Seleccionar Cargo'> Seleccionar Cargo</i></button>");
                    container.append("<button title='Refrescar Grilla' id='refreshcgbutton' class='btn btn-sm btn-default' type='button'><i class='fa fa-repeat text-default' title='Refrescar grilla.'/></i></button>");
                    container.append("<button title='Desfiltrar.' id='clearcgfiltersrowbutton' class='btn btn-sm btn-default' type='button'><i class='gi gi-sorting text-default' title='Desfiltrar.'/></i></button>");

                    //$("#selectcgrowbutton").jqxButton();
                    $("#refreshcgbutton").jqxButton();
                    $("#clearcgfiltersrowbutton").jqxButton();


                    /*$("#selectcgrowbutton").off();
                    $("#selectcgrowbutton").on('click', function () {
                        var selectedrowindex = $("#divGrillaParaSeleccionarCargo").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var dataRecord = $('#divGrillaParaSeleccionarCargo').jqxGrid('getrowdata', selectedrowindex);
                            if (dataRecord != undefined) {
                                agregarCargoSeleccionadoEnGrilla(dataRecord);
                            } else {
                                var msje = "Debe seleccionar un cargo necesariamente.";
                                $("#divMsjePorError").html("");
                                $("#divMsjePorError").append(msje);
                                $("#divMsjeNotificacionError").jqxNotification("open");
                            }
                        } else {
                            var msje = "Debe seleccionar un cargo necesariamente.";
                            $("#divMsjePorError").html("");
                            $("#divMsjePorError").append(msje);
                            $("#divMsjeNotificacionError").jqxNotification("open");
                        }
                    });*/
                    /**
                     * Refrescar la grilla
                     */
                    $("#refreshcgbutton").off();
                    $("#refreshcgbutton").on('click', function () {
                        $("#divGrillaParaSeleccionarCargo").jqxGrid("updatebounddata");
                    });
                    /**
                     * Desfiltrar
                     */
                    $("#clearcgfiltersrowbutton").off();
                    $("#clearcgfiltersrowbutton").on('click', function () {
                        $("#divGrillaParaSeleccionarCargo").jqxGrid('clearfilters');
                    });

                },
                columns: [
                    {
                        text: 'Nro.',
                        sortable: false,
                        filterable: false,
                        editable: false,
                        groupable: false,
                        draggable: false,
                        resizable: false,
                        columntype: 'number',
                        width: 50,
                        cellsalign: 'center',
                        align: 'center',
                        cellsrenderer: rownumberrenderer
                    },
                    {
                        text: '&Iacute;tem/C&oacute;digo',
                        datafield: 'codigo',
                        columntype: 'textbox',
                        filtertype: 'input',
                        align: 'center',
                        cellsalign: 'center',
                        width: 80
                    },
                    /*{ text: 'Fuente', filtertype: 'checkedlist', datafield: 'finpartida', width: 200},*/
                    {
                        text: 'Gestion',
                        columntype: 'textbox',
                        filtertype: 'checkedlist',
                        filteritems: gestionCargo,
                        datafield: 'gestion',
                        align: 'center',
                        cellsalign: 'center',
                        width: 50
                    },
                    {
                        text: 'Cargo',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'cargo',
                        align: 'center',
                        width: 200
                    },
                    {
                        text: 'Nivel Salarial',
                        filtertype: 'checkedlist',
                        datafield: 'nivelsalarial',
                        align: 'center',
                        width: 200
                    },
                    /*{
                        text: 'Haber',
                        filtertype: 'checkedlist',
                        datafield: 'sueldo',
                        align: 'center',
                        cellsalign: 'center',
                        width: 70
                    },*/
                    {
                        text: 'Haber',
                        datafield: 'sueldo',
                        filtertype: 'number',
                        cellsformat: 'd',
                        pinned: false,
                        width: 70,
                        cellsalign: 'right',
                        align: 'center'
                    },
                    {
                        text: 'Gerencia',
                        filtertype: 'checkedlist',
                        datafield: 'gerencia_administrativa',
                        filteritems: filtrables.gerencias,
                        width: 220,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Departamento',
                        filtertype: 'checkedlist',
                        datafield: 'departamento_administrativo',
                        filteritems: filtrables.departamentos,
                        width: 220,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Condici&oacute;n',
                        columntype: 'textbox',
                        filtertype: 'checkedlist',
                        datafield: 'condicion',
                        filteritems: filtrables.condiciones,
                        width: 150,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Resoluci&oacute;n Organigrama',
                        filtertype: 'checkedlist',
                        datafield: 'resolucion_ministerial',
                        align: 'center',
                        width: 200
                    },
                    {
                        text: 'Resoluci&oacute;n Escala',
                        filtertype: 'checkedlist',
                        datafield: 'nivelsalarial_resolucion',
                        align: 'center',
                        width: 200
                    },
                ]
            });
        $('#divGrillaParaSeleccionarCargo').off();
        $('#divGrillaParaSeleccionarCargo').on('rowdoubleclick', function (event) {
            var args = event.args;
            var selectedrowindex = args.rowindex;
            if (selectedrowindex >= 0) {
                var dataRecord = $('#divGrillaParaSeleccionarCargo').jqxGrid('getrowdata', selectedrowindex);
                if (dataRecord != undefined&&dataRecord.id_cargo!=undefined) {
                    agregarCargoSeleccionadoEnGrilla(dataRecord);
                    $("#divNew").css("height","100%");
                } else {
                    var msje = "Debe seleccionar un cargo necesariamente.";
                    $("#divMsjePorError").html("");
                    $("#divMsjePorError").append(msje);
                    $("#divMsjeNotificacionError").jqxNotification("open");
                }
            } else {
                var msje = "Debe seleccionar un cargo necesariamente.";
                $("#divMsjePorError").html("");
                $("#divMsjePorError").append(msje);
                $("#divMsjeNotificacionError").jqxNotification("open");
            }
        });
    }
}

var rownumberrenderer = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
    var nro = row + 1;
    return "<div align='center'>" + nro + "</div>";
}

/**
 * Función para la carga del combo de ubicaciones de trabajo (Oficinas o Paradas de Línea).
 * @param idUbicacionPredeterminada Identificador de la ubicación de la oficina o Parada de Línea en la cual trabajará el empleado.
 */
function cargarUbicaciones(idUbicacionPredeterminada) {
    var ubicacion = [
        {value: 1, label: "OFICINA CENTRAL"},
        {value: 2, label: "LÍNEA ROJA"},
        {value: 3, label: "LÍNEA AMARILLA"},
        {value: 4, label: "LÍNEA VERDE"},
    ];
    $("#ubicacion").jqxComboBox({
        selectedIndex: 0,
        autoComplete: true,
        enableBrowserBoundsDetection: true,
        autoDropDownHeight: true,
        promptText: "Seleccione una ubicacion",
        source: ubicacion,
        height: 22,
        width: '100%'
    });
}

/**
 * Función para cargar los departamentos en el combo especificado.
 * @param idDepartamentoPrefijado Identificador del departamento prefijado por defecto.
 */
function cargarDepartamentos(idDepartamentoPrefijado) {
    var departamento = [
        {value: 0, label: "La Paz"},
        {value: 1, label: "Cochabamba"},
        {value: 2, label: "Sucre"},
        {value: 3, label: "Oruro"},
        {value: 4, label: "Potosí"},
        {value: 5, label: "Santa Cruz"},
        {value: 6, label: "Tarija"},
        {value: 7, label: "Trinidad"},
        {value: 8, label: "Cobija"}
    ];

    $("#departamento").jqxComboBox({
        enableBrowserBoundsDetection: true,
        autoDropDownHeight: true,
        promptText: "Seleccione un departamento o ciudad",
        source: departamento,
        height: 22,
        width: '100%'
    });
}

/**
 * Función para cargar el combo de áreas en caso de existir para el organigrama correspondiente al cargo.
 * @param idPadre Identificador del organigrama padre del cual se desea conocer las áreas disponibles.
 */
function cargarAreasAdministrativas(idPadre, idAreaPredeterminada) {
    $('#divAreas').hide();
    $('#lstAreas').html("");
    var ok = false;
    var selected = "";
    if (idPadre > 0) {
        $.ajax({
            url: '/relaborales/listareas',
            type: 'POST',
            datatype: 'json',
            async: false,
            data: {
                id_padre: idPadre
            },
            success: function (data) {
                var res = jQuery.parseJSON(data);
                if (res.length > 0) {
                    $('#divAreas').show();
                    $('#lstAreas').append("<option value='0'>Seleccionar..</option>");
                    $.each(res, function (key, val) {
                        ok = true;
                        if (idAreaPredeterminada == val.id_area) selected = "selected"; else selected = "";
                        $('#lstAreas').append("<option value=" + val.id_area + " " + selected + ">" + val.unidad_administrativa + "</option>");
                    });
                }
            }
        });
    }
    return ok;
}

/**
 * Función para cargar el combo de procesos de acuerdo al financiamiento seleccionado de acuerdo al cargo.
 * @param idFinPartida Identificador del registro de financiamiento por partida.
 * @param idProcesoPrefijado Identificador del proceso prefijado por defecto.
 */
function cargarProcesos(idCondicion) {
    $.ajax({
        url: '/relaborales/listprocesos',
        type: 'POST',
        datatype: 'json',
        data: {
            id_condicion: idCondicion
        },
        success: function (data) {
            var res = jQuery.parseJSON(data);
            $('#lstProcesos').html("");
            $('#lstProcesos').append("<option value='0'>Seleccionar..</option>");
            $.each(res, function (key, valo) {
                $('#lstProcesos').append("<option value=" + valo.id + ">" + valo.codigo_proceso + "</option>");
            });
        }
    });
}

function cargaCategorias(idCategoriaPredeterminada) {
    var categoria = [
        {value: 1, label: "ADMINISTRATIVO"},
        {value: 2, label: "TECNICO"},
        {value: 3, label: "JURIDICO"},
    ];

    $("#categoria").jqxComboBox({
        enableBrowserBoundsDetection: true,
        autoDropDownHeight: true,
        promptText: "Seleccione una categoria",
        source: categoria,
        height: 22,
        width: '100%'
    });
}

/**
 * Función para la definición de las fechas para el registro de la relación laboral.
 */
function defineFechas() {
    $("#FechaIni").jqxDateTimeInput({enableBrowserBoundsDetection: true, height: 24, formatString: 'dd-MM-yyyy'});
    $("#FechaIncor").jqxDateTimeInput({enableBrowserBoundsDetection: true, height: 24, formatString: 'dd-MM-yyyy'});
}

/**
 * Función para agregar un cargo a la grilla correspondiente para determinar donde trabajará la persona.
 * @param dataRecordCargo
 */
function agregarCargoSeleccionadoEnGrilla(dataRecordCargo) {
    var id_cargo = dataRecordCargo.id_cargo;
    var codigo = dataRecordCargo.codigo;
    var id_finpartida = dataRecordCargo.id_finpartida;
    var finpartida = dataRecordCargo.finpartida;
    var id_resolucion_ministerial = dataRecordCargo.id_resolucion_ministerial;
    var resolucion_ministerial = dataRecordCargo.resolucion_ministerial;
    var id_condicion = dataRecordCargo.id_condicion;
    var condicion = dataRecordCargo.condicion;
    var id_organigrama = dataRecordCargo.id_organigrama;
    var gerencia_administrativa = dataRecordCargo.gerencia_administrativa;
    var departamento_administrativo = dataRecordCargo.departamento_administrativo;
    var nivelsalarial = dataRecordCargo.nivelsalarial;
    var cargo = dataRecordCargo.cargo;
    var sueldo = dataRecordCargo.sueldo;
    var nivelsalarial_resolucion_id = dataRecordCargo.nivelsalarial_resolucion_id;
    var nivelsalarial_resolucion = dataRecordCargo.nivelsalarial_resolucion;
    var gestion = "";
    if (dataRecordCargo.gestion != '' && dataRecordCargo.gestion != null) {
        gestion = dataRecordCargo.gestion;
    }
    var correlativo = dataRecordCargo.correlativo;
    $("#tr_cargo_seleccionado").html("");
    var btnDescartar = "<td  class='text-center'><a class='btn btn-danger btnDescartarCargoSeleccionado' title='Descartar cargo seleccionado.' data-toggle='tooltip' data-original-title='Descartar' id='btn_" + id_cargo + "' alt='Descartar cargo para el contrato'>";
    btnDescartar += "<i class='fa fa-times'></i></a></td>";
    var grilla = "<td class='text-center'>" + codigo + "</td><td class='text-center'>" + gestion + "</td><td class='text-center'>" + resolucion_ministerial + "</td><td class='text-center'>" + nivelsalarial_resolucion + "</td><td class='text-center'>" + condicion + "</td><td>" + gerencia_administrativa +
        "</td><td>" + departamento_administrativo + "</td><td>" + nivelsalarial + "</td><td>" + cargo + "</td><td  class='text-right'>" + sueldo + "</td>";
    $("#tr_cargo_seleccionado").append(btnDescartar + grilla);
    $("#hdnIdCargoNuevoSeleccionado").val(id_cargo);
    $("#hdnIdOrganigramaSeleccionado").val(id_organigrama);
    $("#hdnIdCondicionNuevaSeleccionada").val(id_condicion);
    $("#divProcesos").show();
    var okArea = cargarAreasAdministrativas(id_organigrama, 0);
    cargarProcesos(id_condicion);
    /*$("#popupWindowCargo").jqxWindow('close');*/
    $('#popupGrillaCargo').modal('hide');
    id_condicion = parseInt(id_condicion);
    /**
     * Un número de contrato es requerido si es eventual o consultor o contrato a plazo fijo.
     */
    if (id_condicion == 2 || id_condicion == 3 || id_condicion == 7) {
        $("#divNumContratos").show();
        if (!okArea) $("#txtNumContrato").focus();
        else $("#lstAreas").focus();
        $("#divFechasFin").show();
        $("#FechaFin").jqxDateTimeInput({enableBrowserBoundsDetection: true, height: 24, formatString: 'dd-MM-yyyy'});
    } else {
        if (!okArea) $("#lstUbicaciones").focus();
        else $("#lstAreas").focus();
    }
    $(".btnDescartarCargoSeleccionado").off();
    $(".btnDescartarCargoSeleccionado").click(function () {
        $("#tr_cargo_seleccionado").html("");
        $("#hdnIdCargoNuevoSeleccionado").val(0);
        $("#hdnIdOrganigramaSeleccionado").val(0);
        $("#hdnIdCondicionNuevaSeleccionada").val(0);
        //$("#divItems").hide();
        $("#divNumContratos").hide();
        $("#divProcesos").hide();
        $("#divFechasFin").hide();
        $(".msjs-alert").hide();
        $(".div-new-relab").removeClass('has-error');
        $("#helpErrorUbicaciones").html("");
        $("#helpErrorProcesos").html("");
        $("#helpErrorCategorias").html("");
        $("#divUbicaciones").removeClass("has-error");
        $("#divProcesos").removeClass("has-error");
        $("#divCategorias").removeClass("has-error");
    });
}

/**
 * Función para la habilitación de los campos correspondientes en el formulario de registro de una nueva relación laboral.
 * @param idOrganigrama Identificador del organigrama.
 * @param idFinPartida Identificador del financiamiento por partida.
 */
function habilitarCamposParaNuevoRegistroDeRelacionLaboral(idOrganigrama, idFinPartida) {
    defineFechas();
}

/**
 * Función para deshabilitar los campos correspondientes en el formulario de registro de una nueva relación laboral.
 */
function deshabilitarCamposParaNuevoRegistroDeRelacionLaboral() {
    $("#tr_cargo_seleccionado").html("");
    $("#hdnIdPersonaSeleccionada").val(0);
    $("#NombreParaNuevoRegistro").html("");
}

/**
 * Función para validar los datos del formulario de nuevo registro de relación laboral.
 * @returns {boolean} True: La validación fue correcta; False: La validación anuncia que hay errores en el formulario.
 */
function validaFormularioPorNuevoRegistro() {
    var ok = true;
    var msje = "";
    $(".msjs-alert").hide();
    limpiarMensajesErrorPorValidacionNuevoRegistro();

    var id_condicion = $("#hdnIdCondicionNuevaSeleccionada").val();
    id_condicion = parseInt(id_condicion);
    var ubicacion = $("#lstUbicaciones").val();
    var proceso = $("#lstProcesos").val();
    var categoria = $("#lstCategorias").val();
    var fechaIni = $("#FechaIni").jqxDateTimeInput('getText');
    var fechaIncor = $("#FechaIncor").jqxDateTimeInput('getText');
    var fechaFin = null;
    /**
     * Sólo para el caso de condición consultor será necesario registrar la fecha de finalización
     */
    if (id_condicion == 2 || id_condicion == 3 || id_condicion == 7) {
        fechaFin = $("#FechaFin").jqxDateTimeInput('getText');
    }
    var idCargo = $("#hdnIdCargoNuevoSeleccionado").val();
    if (idCargo == 0 || idCargo == null) {
        $("#divMsjeError").show();
        $("#divMsjeError").addClass('alert alert-danger alert-dismissable');
        $("#aMsjeError").html("Debe seleccionar el cargo necesariamente.");
        ok = false;
    }
    id_condicion = parseInt(id_condicion);
    var enfoque = null;
    if (fechaIni == null || fechaIni == "") {
        ok = false;
        msje = "Debe introducir la fecha de inicio.";
        $("#divFechasIni").addClass("has-error");
        $("#helpErrorFechasIni").html(msje);
        if (enfoque == null) enfoque = $("#FechaIni");
    }
    if (fechaIncor == null || fechaIncor == "") {
        ok = false;
        msje = "Debe introducir la fecha de incorporaci&oacute;n.";
        $("#divFechasIncor").addClass("has-error");
        $("#helpErrorFechasIncor").html(msje);
        if (enfoque == null) enfoque = $("#FechaIncor");
    }
    var sep = '-';
    if (procesaTextoAFecha(fechaIncor, sep) < procesaTextoAFecha(fechaIni, sep)) {
        ok = false;
        msje = "La fecha de incorporaci&oacute;n debe ser igual o superior a la fecha de inicio.";
        $("#divFechasIni").addClass("has-error");
        $("#divFechasIncor").addClass("has-error");
        $("#helpErrorFechasIni").html(msje);
        $("#helpErrorFechasIncor").html(msje);
        if (enfoque == null) enfoque = $("#FechaIni");
    }
    if (id_condicion == 2 || id_condicion == 3 || id_condicion == 7) {
        if (fechaFin == "" || fechaFin == null) {
            ok = false;
            msje = "Debe introducir la fecha de finalizaci&oacute;n del contrato.";
            $("#divFechasFin").show();
            $("#divFechasFin").addClass("has-error");
            $("#helpErrorFechasFin").html(msje);
            if (enfoque == null) enfoque = $("#FechaFin");
        }
        var sep = '-';
        if (procesaTextoAFecha(fechaFin, sep) < procesaTextoAFecha(fechaIni, sep)) {
            ok = false;
            msje = "La fecha de inicio no puede ser superior a la fecha de finalizaci&oacute;n.";
            $("#divFechasIni").show();
            $("#divFechasIni").addClass("has-error");
            $("#helpErrorFechasIni").html(msje);
            $("#divFechasFin").show();
            $("#divFechasFin").addClass("has-error");
            $("#helpErrorFechasFin").html(msje);
            if (enfoque == null) enfoque = $("#FechaFin");
        }
        if (procesaTextoAFecha(fechaFin, sep) < procesaTextoAFecha(fechaIncor, sep)) {
            ok = false;
            msje = "La fecha de incorporaci&oacute;n no puede ser superior a la fecha de finalizaci&oacute;n.";
            $("#divFechasIncor").show();
            $("#divFechasIncor").addClass("has-error");
            $("#helpErrorFechasIncor").html(msje);
            $("#divFechasFin").show();
            $("#divFechasFin").addClass("has-error");
            $("#helpErrorFechasFin").html(msje);
            if (enfoque == null) enfoque = $("#FechaFin");
        }
    }
    /**
     * Se procede al control del número de contrato para personal eventual y consultor de línea.
     */
    if (id_condicion == 2 || id_condicion == 3 || id_condicion == 7) {
        if ($("#txtNumContrato").val() == null || $("#txtNumContrato").val() == "") {
            ok = false;
            msje = "Debe introducir en n&uacute;mero de contrato necesariamente.";
            $("#divNumContratos").addClass("has-error");
            $("#helpErrorNumContratos").html(msje);
            if (enfoque == null) enfoque = $("#txtNumContrato");
        }
        if (fechaFin == null || fechaFin == "") {
            ok = false;
            msje = "Debe introducir la fecha de finalizaci&oacute;n del contrato.";
            $("#divFechasFin").addClass("has-error");
            $("#helpErrorFechasFin").html(msje);
            if (enfoque == null) enfoque = $("#FechaFin");
        }
    }
    /*switch (id_condicion){
     case 1:
     if($("#txtItem").val()==null||$("#txtItem").val()==""){
     ok=false;
     msje = "Debe introducir en n&uacute;mero de &iacute;tem necesariamente.";
     $("#divItems").addClass("has-error");
     $("#helpErrorItems").html(msje);
     if(enfoque==null)enfoque =$("#txtItem");
     }
     break;
     case 2:
     case 3:
     if($("#txtNumContrato").val()==null||$("#txtNumContrato").val()==""){
     ok=false;
     msje = "Debe introducir en n&uacute;mero de contrato necesariamente.";
     $("#divNumContratos").addClass("has-error");
     $("#helpErrorNumContratos").html(msje);
     if(enfoque==null)enfoque =$("#txtNumContrato");
     }
     break;
     }*/
    if (ubicacion == "" || ubicacion == null) {
        ok = false;
        msje = "Debe seleccionar la ubicaci&oacute;n de trabajo necesariamente.";
        $("#divUbicaciones").addClass("has-error");
        $("#helpErrorUbicaciones").html(msje);
        if (enfoque == null) enfoque = $("#lstUbicaciones");
    }
    if (proceso == 0 || proceso == "" || proceso == null) {
        ok = false;
        msje = "Debe seleccionar el proceso correspondiente necesariamente.";
        $("#divProcesos").addClass("has-error");
        $("#helpErrorProcesos").html(msje);
        if (enfoque == null) enfoque = $("#lstProcesos");
    }
    /*if(categoria==""||categoria==null){
     ok=false;
     msje = "Debe seleccionar la categor&iacute;a necesariamente.";
     $("#divCategorias").addClass("has-error");
     $("#helpErrorCategorias").html(msje);
     if(enfoque==null)enfoque =$("#lstCategorias");
     }*/
    $('#formNuevo').jqxValidator({focus: false});
    $('#formNuevo').jqxValidator({
        hintType: 'label',
        rules: [
            {
                input: '#FechaIni',
                message: 'Debe registrar la fecha de inicio!',
                action: 'keyup, blur',
                rule: 'required'
            },
            {
                input: '#FechaIncor',
                message: 'Debe registrar la fecha de incorporación!',
                action: 'keyup, blur',
                rule: 'required'
            },
            {
                input: '#FechaFin',
                message: 'Debe registrar la fecha de finalización!',
                action: 'keyup, blur',
                rule: 'required'
            }
        ]
    });
    if (enfoque != null) {
        enfoque.focus();
    }

    //if(categoria==null||categoria==""){
    //    ok=false;
    //    msje = "Debe seleccionar la categoria necesariamente.";
    //
    //}
    //if(fechaIni==""||fechaIni==null){
    //    ok=false;
    //    msje = "Debe registrar la Fecha de Inicio necesariamente.";
    //}
    //if(fechaIncor==""||fechaIncor==null){
    //    ok=false;
    //    msje = "Debe registrar la Fecha de Incorporaci&oacute;n necesariamente.";
    //}
    //if(fechaFin == ""||fechaFin == null){
    //    ok=false;
    //    msje = "Debe registrar la Fecha de Finalizaci&oacute;n necesariamente.";
    //}
    //alert(msje);
    return ok;
}

/**
 * Función para la limpieza de los mensajes de error debido a la validación del formulario para nuevo registro.
 */
function limpiarMensajesErrorPorValidacionNuevoRegistro() {
    $("#helpErrorUbicaciones").html("");
    $("#helpErrorProcesos").html("");
    $("#helpErrorCategorias").html("");
    $("#helpErrorItems").html("");
    $("#helpErrorNumContratos").html("");
    $("#helpErrorFechasIni").html("");
    $("#helpErrorFechasIncor").html("");
    $("#helpErrorFechasFin").html("");
    $("#divUbicaciones").removeClass("has-error");
    $("#divProcesos").removeClass("has-error");
    $("#divCategorias").removeClass("has-error");
    $("#divItems").removeClass("has-error");
    $("#divNumContratos").removeClass("has-error");
    $("#divFechasIni").removeClass("has-error");
    $("#divFechasIncor").removeClass("has-error");
    $("#divFechasFin").removeClass("has-error");
}

/**
 * Función para limpiar el formulario de registro de relaborales.
 */
function limpiarFormularioNuevoRegistro(opcion) {
    var sufijo = "";
    if (opcion == 2) {
        sufijo = "Editar";
    }
    $("#txtNumContrato" + sufijo).val("");
    $("#lstUbicaciones" + sufijo).val("");
    $("#lstProcesos" + sufijo).val("");
    $("#txtObservacion" + sufijo).val("")
}

/**
 * Función para el almacenamiento de un nuevo registro en la Base de Datos.
 */
function guardarNuevoRegistro() {
    var ok = 0;
    var item = 0;
    var idArea = 0;
    /*
     Si se ha definido la opción de registro de áreas
     */
    if ($("#lstAreas").val() != null) {
        idArea = $("#lstAreas").val();
    }
    var idRegional = 1;
    var idPersona = $("#hdnIdPersonaSeleccionada").val();
    var idCargo = $("#hdnIdCargoNuevoSeleccionado").val();
    var idUbicacion = $('#lstUbicaciones').val();
    var idProceso = $('#lstProcesos').val();
    //var idCategoria = $('#lstCategorias').val();
    var idCondicion = $("#hdnIdCondicionNuevaSeleccionada").val();
    var numContrato = '';
    //Si la condición de la relación laboral es consultoría se requiere que se llene el campo del número de contrato.
    var fechaFin = null;
    if (idCondicion == 2 || idCondicion == 3 || idCondicion == 7) {
        numContrato = $("#txtNumContrato").val();
        var fechaFin = $('#FechaFin').jqxDateTimeInput('getText');
    }
    var fechaIni = $('#FechaIni').jqxDateTimeInput('getText');
    var fechaIncor = $('#FechaIncor').jqxDateTimeInput('getText');
    var observacion = $("#txtObservacion").val();
    if (idPersona > 0 && idCargo > 0) {
        $.ajax({
            url: '/relaborales/save/',
            type: "POST",
            datatype: 'json',
            async: false,
            cache: false,
            data: {
                id: 0,
                id_persona: idPersona,
                id_cargo: idCargo,
                num_contrato: numContrato,
                id_area: idArea,
                id_ubicacion: idUbicacion,
                id_regional: idRegional,
                id_procesocontratacion: idProceso,
                fecha_inicio: fechaIni,
                fecha_incor: fechaIncor,
                fecha_fin: fechaFin,
                observacion: observacion
            },
            success: function (data) {  //alert(data);

                var res = jQuery.parseJSON(data);
                /**
                 * Si se ha realizado correctamente el registro de la relación laboral
                 */
                if (res.result == 1) {
                    ok = parseInt(res.id_r);
                    $("#divMsjePorSuccess").html("");
                    $("#divMsjePorSuccess").append(res.msj);
                    $("#divMsjeNotificacionSuccess").jqxNotification("open");
                    /**
                     * Se habilita nuevamente el listado actualizado con el registro realizado y
                     * se inhabilita el formulario para nuevo registro.
                     */
                    $('#jqxTabs').jqxTabs('enableAt', 0);
                    $('#jqxTabs').jqxTabs('disableAt', 1);
                    deshabilitarCamposParaNuevoRegistroDeRelacionLaboral();
                    $("#jqxgrid").jqxGrid("updatebounddata");
                } else if (res.result == 0) {
                    /**
                     * En caso de haberse presentado un error al momento de especificar la ubicación del trabajo
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
                    ok = 0;
                }

            }, //mostramos el error
            error: function () {
                alert('Se ha producido un error Inesperado');
            }
        });
    }
    return ok;
}