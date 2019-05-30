/**
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  27-10-2014
 */
/**
 * Función para definir el contenido de la grilla de cargos acéfalos en el formularión de edición de acuerdo a los datos enviados como parámetros.
 */
function definirGrillaParaSeleccionarCargoAcefaloParaEditar(numCertificacion, codCargo, filtrables) {
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
                {name: 'gestion', type: 'string'}
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
    cargarRegistrosDeCargosParaEditar();

    function cargarRegistrosDeCargosParaEditar() {
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
                    /*container.append("<button id='selectrowbutton'  class='btn btn-sm btn-primary' type='button' ><i class='fa fa-check-square fa-2x text-info' title='Seleccionar Cargo'> Seleccionar Cargo</i></button>");*/
                    container.append("<button title='Refrescar Grilla' id='refreshcgbutton' class='btn btn-sm btn-default' type='button'><i class='fa fa-repeat text-default' title='Refrescar grilla.'/></i></button>");
                    container.append("<button title='Desfiltrar.' id='clearcgfiltersrowbutton' class='btn btn-sm btn-default' type='button'><i class='gi gi-sorting text-default' title='Desfiltrar.'/></i></button>");
                    $("#refreshcgbutton").jqxButton();
                    $("#clearcgfiltersrowbutton").jqxButton();

                    /*$("#selectrowbutton").jqxButton();
                    $("#selectrowbutton").off();
                    $("#selectrowbutton").on('click', function () {
                        var selectedrowindex = $("#divGrillaParaSeleccionarCargo").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var dataRecord = $('#divGrillaParaSeleccionarCargo').jqxGrid('getrowdata', selectedrowindex);
                            if (dataRecord != undefined) {
                                agregarCargoSeleccionadoEnGrillaParaEditar(2, dataRecord);
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
                    /*{
                     text: '#', sortable: false, filterable: false, editable: false,align:'center',cellsalign:'center',
                     groupable: false, draggable: false, resizable: false,
                     datafield: '', columntype: 'number', width: 50
                     },*/
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
                    /*{ text: 'Opción', datafield: 'seleccionable', align:'center',cellsalign:'center',width: 100,sortable:false, showfilterrow:false, filterable:false, columntype: 'button', cellsrenderer: function () {
                     return "Seleccionar";
                     }, buttonclick: function (row) {
                     editrow = row;
                     var offset = $("#divGrillaParaSeleccionarCargo").offset();
                     var dataRecord = $("#divGrillaParaSeleccionarCargo").jqxGrid('getrowdata', editrow);
                     agregarCargoSeleccionadoEnGrillaParaEditar(dataRecord.id_cargo,dataRecord.codigo,dataRecord.id_finpartida,dataRecord.finpartida,dataRecord.id_resolucion_ministerial,dataRecord.resolucion_ministerial,dataRecord.id_condicion,dataRecord.condicion,dataRecord.id_organigrama,dataRecord.gerencia_administrativa,dataRecord.departamento_administrativo,0,dataRecord.nivelsalarial,dataRecord.cargo,dataRecord.sueldo);
                     }
                     },*/
                    {
                        text: '&Iacute;tem/C&oacute;digo',
                        filtertype: 'input',
                        datafield: 'codigo',
                        cellsalign: 'center',
                        align: 'center',
                        width: 100
                    },
                    /*{ text: 'Fuente', filtertype: 'checkedlist', datafield: 'finpartida', width: 200},*/
                    {
                        text: 'Gesti&oacute;n',
                        columntype: 'textbox',
                        filtertype: 'checkedlist',
                        datafield: 'gestion',
                        align: 'center',
                        width: 100
                    },
                    {
                        text: 'Cargo',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'cargo',
                        align: 'center',
                        width: 100
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
                        columntype: 'textbox',
                        filtertype: 'input',
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
                if (dataRecord != undefined) {
                    agregarCargoSeleccionadoEnGrillaParaEditar(2, dataRecord);
                    $("#divEdit").css("height","100%");
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
function cargarUbicacionesParaEditar(idUbicacionPredeterminada) {
    if (idUbicacionPredeterminada == '') idUbicacionPredeterminada = 0;
    $('#lstUbicacionesEditar').html("");
    var selected = '';
    $.ajax({
        url: '/relaborales/listubicaciones',
        type: 'POST',
        datatype: 'json',
        cache: false,
        async: false,
        success: function (data) {
            var res = jQuery.parseJSON(data);
            $.each(res, function (key, val) {
                if (idUbicacionPredeterminada == val.id) {
                    selected = 'selected';
                } else {
                    selected = '';
                }
                $('#lstUbicacionesEditar').append("<option value=" + val.id + " " + selected + ">" + val.ubicacion + "</option>");
            });
        }
    });
}

/**
 * Función para cargar los departamentos en el combo especificado.
 * @param idDepartamentoPrefijado Identificador del departamento prefijado por defecto.
 */
function cargarDepartamentosParaEditar(idDepartamentoPrefijado) {
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

    $("#departamentoEditar").jqxComboBox({
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
 * @param idAreaPredeterminada Identificador del área que ya tenía registro.
 */
function cargarAreasAdministrativasParaEditar(idPadre, idAreaPredeterminada) {
    $('#divAreasEditar').hide();
    $('#lstAreasEditar').html("");
    var ok = false;
    if (idPadre > 0) {
        $.ajax({
            url: '/relaborales/listareas',
            type: 'POST',
            datatype: 'json',
            async: false,
            data: {id_padre: idPadre},
            success: function (data) {
                var res = jQuery.parseJSON(data);
                if (res.length > 0) {
                    $('#divAreasEditar').show();
                    $('#lstAreasEditar').append("<option value='0'>Seleccionar..</option>");
                    $.each(res, function (key, val) {
                        ok = true;
                        if (idAreaPredeterminada == val.id_area) selected = "selected"; else selected = "";
                        $('#lstAreasEditar').append("<option value=" + val.id_area + " " + selected + ">" + val.unidad_administrativa + "</option>");
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
function cargarProcesosParaEditar(idCondicion, idProcesoPrefijado) {
    var lstProcesos = $.ajax({
        url: '/relaborales/listprocesos',
        type: 'POST',
        datatype: 'json',
        data: {
            id_condicion: idCondicion
        },
        success: function (data) {
            var res = jQuery.parseJSON(data);
            $('#lstProcesosEditar').html("");
            $.each(res, function (key, valo) {
                if (idProcesoPrefijado == valo.id) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                $('#lstProcesosEditar').append("<option value=" + valo.id + " " + $selected + ">" + valo.codigo_proceso + "</option>");
            });
        }
    });
}

function cargaCategoriasParaEditar(idCategoriaPredeterminada) {
    var categoria = [
        {value: 1, label: "ADMINISTRATIVO"},
        {value: 2, label: "TECNICO"},
        {value: 3, label: "JURIDICO"},
    ];

    $("#categoriaEditar").jqxComboBox({
        enableBrowserBoundsDetection: true,
        autoDropDownHeight: true,
        promptText: "Seleccione una categoria",
        source: categoria,
        height: 22,
        width: '100%'
    });
}

/**
 * Función para agregar un cargo registrado a la grilla correspondiente para determinar donde trabajará la persona.
 * @param id_cargo Identificador del cargo.
 * @param codigo Código del cargo seleccionado.
 * @param finpartida Financiamiento por partida.
 * @param condicion Condición de contrato / relación laboral.
 * @param gerencia_administrativa Gerencia Administrativa a la cual corresponde el cargo.
 * @param departamento_administrativo Departamento administrativo al cual corresponde el cargo.
 * @param nivelsalarial Nivel salarial correspondiente para el cargo.
 * @param cargo Nombre del cargo.
 * @param haber Haber mensual para el cargo.
 */
function agregarCargoSeleccionadoEnGrillaParaEditar(opcion, dataRecordCargo) {
    var id_cargo = dataRecordCargo.id_cargo;
    var codigo = "";
    if (opcion == 1) {
        codigo = dataRecordCargo.cargo_codigo;
    } else codigo = dataRecordCargo.codigo;
    var id_finpartida = dataRecordCargo.id_finpartida;
    var finpartida = dataRecordCargo.finpartida;
    var id_resolucion_ministerial = dataRecordCargo.id_resolucion_ministerial;
    var resolucion_ministerial = dataRecordCargo.cargo_resolucion_ministerial;
    var id_condicion = dataRecordCargo.id_condicion;
    var condicion = dataRecordCargo.condicion;
    var id_organigrama = dataRecordCargo.id_organigrama;
    var gerencia_administrativa = dataRecordCargo.gerencia_administrativa;
    var departamento_administrativo = dataRecordCargo.departamento_administrativo;
    var id_area = dataRecordCargo.id_area;
    var nivelsalarial = dataRecordCargo.nivelsalarial;
    var cargo = dataRecordCargo.cargo;
    var sueldo = dataRecordCargo.sueldo;
    var nivelsalarial_resolucion_id = dataRecordCargo.nivelsalarial_resolucion_id;
    var nivelsalarial_resolucion = dataRecordCargo.nivelsalarial_resolucion;
    var gestion = "";
    if (opcion == 1) {
        gestion = dataRecordCargo.cargo_gestion;
    } else gestion = dataRecordCargo.gestion;
    var correlativo = dataRecordCargo.cargo_correlativo;
    $("#tr_cargo_seleccionado_editar").html("");
    var btnDescartar = "<td class='text-center'><a class='btn btn-danger btnDescartarCargoSeleccionadoEditar' title='Descartar cargo seleccionado.' data-toggle='tooltip' data-original-title='Descartar' id='btn_editar_" + id_cargo + "' alt='Descartar cargo para el contrato'>";
    btnDescartar += "<i class='fa fa-times'></i></a></td>";
    //var grilla = "<td>"+codigo+"</td><td>"+finpartida+"</td><td>"+condicion+"</td><td>"+gerencia_administrativa+"</td><td>"+departamento_administrativo+"</td><td>"+nivelsalarial+"</td><td>"+cargo+"</td><td>"+haber+"</td>";
    var grilla = "<td class='text-center'>" + codigo + "</td><td class='text-center'>" + gestion + "</td><td class='text-center'>" + resolucion_ministerial + "</td><td class='text-center'>" + nivelsalarial_resolucion + "</td><td class='text-center'>" + condicion + "</td><td>" + gerencia_administrativa + "</td><td>" + departamento_administrativo + "</td><td>" + nivelsalarial + "</td><td>" + cargo + "</td><td class='text-center'>" + sueldo + "</td>";
    $("#tr_cargo_seleccionado_editar").append(btnDescartar + grilla);
    $("#hdnIdCargoSeleccionadoEditar").val(id_cargo);
    $("#hdnIdOrganigramaSeleccionadoEditar").val(id_organigrama);
    $("#hdnIdCondicionEditableSeleccionada").val(id_condicion);
    /*$("#popupWindowCargo").jqxWindow('close');*/
    $('#popupGrillaCargo').modal('hide');
    $("#divProcesosEditar").show();
    var okArea = cargarAreasAdministrativasParaEditar(id_organigrama, id_area);
    id_condicion = parseInt(id_condicion);
    if (id_condicion == 2 || id_condicion == 3 || id_condicion == 7) {
        $("#divNumContratosEditar").show();
        $("#divFechasFinEditar").show();
        $("#FechaFinEditar").jqxDateTimeInput({
            enableBrowserBoundsDetection: true,
            height: 24,
            formatString: 'dd-MM-yyyy'
        });
        $("#txtNumContratoEditar").focus();
    } else {
        $("#lstUbicacionesEditar").focus();
    }
    $(".btnDescartarCargoSeleccionadoEditar").click(function () {
        $("#tr_cargo_seleccionado_editar").html("");
        $("#hdnIdCargoSeleccionadoEditar").val(0);
        $("#hdnIdOrganigramaSeleccionadoEditar").val(0);
        $("#hdnIdCondicionNuevaSeleccionadaEditar").val(0);
        $("#hdnIdRelaboral").val(0);
        //$("#divItems").hide();
        $("#divProcesosEditar").hide();
        $("#divNumContratosEditar").hide();
        $("#divFechasFinEditar").hide();
        $(".msjs-alert").hide();
        $(".div-edit-relab").removeClass('has-error');
        $("#helpErrorUbicacionesEditar").html("");
        $("#helpErrorProcesosEditar").html("");
        $("#helpErrorCategoriasEditar").html("");
        $("#divUbicacionesEditar").removeClass("has-error");
        $("#divProcesosEditar").removeClass("has-error");
        $("#divCategoriasEditar").removeClass("has-error");
        //deshabilitarCamposParaEditarRegistroDeRelacionLaboral(id_organigrama,id_finpartida);
    });
}

/**
 * Función para deshabilitar los campos correspondientes en el formulario de registro de una nueva relación laboral.
 */
function deshabilitarCamposParaEditarRegistroDeRelacionLaboral() {
    $("#tr_cargo_seleccionado_editar").html("");
    $("#hdnIdPersonaSeleccionadaEditar").val(0);
    $("#NombreParaEditarRegistro").html("");
}

/**
 * Función para validar los datos del formulario de nuevo registro de relación laboral.
 * @returns {boolean} True: La validación fue correcta; False: La validación anuncia que hay errores en el formulario.
 */
function validaFormularioPorEditarRegistro() {
    var ok = true;
    var msje = "";
    $(".msjs-alert").hide();

    limpiarMensajesErrorPorValidacionEditarRegistro();

    var id_condicion = $("#hdnIdCondicionEditableSeleccionada").val();
    id_condicion = parseInt(id_condicion);
    var ubicacion = $("#lstUbicacionesEditar").val();
    var proceso = $("#lstProcesosEditar").val();
    var categoria = $("#lstCategoriasEditar").val();
    var fechaIni = $("#FechaIniEditar").jqxDateTimeInput('getText');
    var fechaIncor = $("#FechaIncorEditar").jqxDateTimeInput('getText');
    var fechaFin = null;
    /**
     * Sólo para el caso de condición consultor será necesario registrar la fecha de finalización
     */
    if (id_condicion == 2 || id_condicion == 3 || id_condicion == 7) {
        fechaFin = $("#FechaFinEditar").jqxDateTimeInput('getText');
    }
    var idCargo = $("#hdnIdCargoSeleccionadoEditar").val();
    var idRelaboral = $("#hdnIdRelaboralEditar").val();
    var msjeError = "";
    if (idRelaboral == 0 || idRelaboral == null) {
        $("#divMsjeError").show();
        $("#divMsjeError").addClass('alert alert-danger alert-dismissable');
        msjeError += "Se requiere seleccionar un registro de relaci&oacute;n laboral inicialmente.";
        ok = false;
    }
    if (idCargo == 0 || idCargo == null) {
        $("#divMsjeError").show();
        $("#divMsjeError").addClass('alert alert-danger alert-dismissable');
        if (msjeError != "") msjeError += "<br>";
        msjeError += "Debe seleccionar el cargo necesariamente.";
        ok = false;
    }
    if (msjeError != "") $("#aMsjeError").html(msjeError);

    id_condicion = parseInt(id_condicion);
    var enfoque = null;
    if (fechaIni == null || fechaIni == "") {
        ok = false;
        msje = "Debe introducir la fecha de inicio.";
        $("#divFechasIniEditar").addClass("has-error");
        $("#helpErrorFechasIniEditar").html(msje);
        if (enfoque == null) enfoque = $("#FechaIni");
    }
    if (fechaIncor == null || fechaIncor == "") {
        ok = false;
        msje = "Debe introducir la fecha de incorporaci&oacute;n.";
        $("#divFechasIncorEditar").addClass("has-error");
        $("#helpErrorFechasIncorEditar").html(msje);
        if (enfoque == null) enfoque = $("#FechaIncorEditar");
    }
    var sep = '-';
    if (procesaTextoAFecha(fechaIncor, sep) < procesaTextoAFecha(fechaIni, sep)) {
        ok = false;
        msje = "La fecha de incorporaci&oacute;n debe ser igual o superior a la fecha de inicio.";
        $("#divFechasIniEditar").addClass("has-error");
        $("#divFechasIncorEditar").addClass("has-error");
        $("#helpErrorFechasIniEditar").html(msje);
        $("#helpErrorFechasIncorEditar").html(msje);
        if (enfoque == null) enfoque = $("#FechaIniEditar");
    }
    if (id_condicion == 2 || id_condicion == 3 || id_condicion == 7) {
        if (fechaFin == "" || fechaFin == null) {
            ok = false;
            msje = "Debe introducir la fecha de finalizaci&oacute;n del contrato.";
            $("#divFechasFinEditar").show();
            $("#divFechasFinEditar").addClass("has-error");
            $("#helpErrorFechasFinEditar").html(msje);
            if (enfoque == null) enfoque = $("#FechaFinEditar");
        }
        if (procesaTextoAFecha(fechaFin, sep) < procesaTextoAFecha(fechaIni, sep)) {
            ok = false;
            msje = "La fecha de inicio no puede ser superior a la fecha de finalizaci&oacute;n.";
            $("#divFechasIniEditar").show();
            $("#divFechasIniEditar").addClass("has-error");
            $("#helpErrorFechasIniEditar").html(msje);
            $("#divFechasFinEditar").show();
            $("#divFechasFinEditar").addClass("has-error");
            $("#helpErrorFechasFinEditar").html(msje);
            if (enfoque == null) enfoque = $("#FechaFinEditar");
        }
        if (procesaTextoAFecha(fechaFin, sep) < procesaTextoAFecha(fechaIncor, sep)) {
            ok = false;
            msje = "La fecha de incorporaci&oacute;n no puede ser superior a la fecha de finalizaci&oacute;n.";
            $("#divFechasIncorEditar").show();
            $("#divFechasIncorEditar").addClass("has-error");
            $("#helpErrorFechasIncorEditar").html(msje);
            $("#divFechasFinEditar").show();
            $("#divFechasFinEditar").addClass("has-error");
            $("#helpErrorFechasFinEditar").html(msje);
            if (enfoque == null) enfoque = $("#FechaFinEditar");
        }
    }
    /**
     * Se procede al control del número de contrato para personal eventual y consultor de línea.
     */
    if (id_condicion == 2 || id_condicion == 3 || id_condicion == 7) {
        if ($("#txtNumContratoEditar").val() == null || $("#txtNumContratoEditar").val() == "") {
            ok = false;
            msje = "Debe introducir en n&uacute;mero de contrato necesariamente.";
            $("#divNumContratosEditar").addClass("has-error");
            $("#helpErrorNumContratosEditar").html(msje);
            if (enfoque == null) enfoque = $("#txtNumContratoEditar");
        }
        if (fechaFin == null || fechaFin == "") {
            ok = false;
            msje = "Debe introducir la fecha de finalizaci&oacute;n del contrato.";
            $("#divFechasFinEditar").addClass("has-error");
            $("#helpErrorFechasFinEditar").html(msje);
            if (enfoque == null) enfoque = $("#FechaFinEditar");
        }
    }
    if (ubicacion == "" || ubicacion == null) {
        ok = false;
        msje = "Debe seleccionar la ubicaci&oacute;n de trabajo necesariamente.";
        $("#divUbicacionesEditar").addClass("has-error");
        $("#helpErrorUbicacionesEditar").html(msje);
        if (enfoque == null) enfoque = $("#lstUbicacionesEditar");
    }
    if (proceso == 0 || proceso == "" || proceso == null) {
        ok = false;
        msje = "Debe seleccionar el proceso correspondiente necesariamente.";
        $("#divProcesosEditar").addClass("has-error");
        $("#helpErrorProcesosEditar").html(msje);
        if (enfoque == null) enfoque = $("#lstProcesosEditar");
    }
    if (enfoque != null) {
        enfoque.focus();
    }
    return ok;
}

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
function guardarRegistroEditado() {
    var ok = true;
    var id_relaboral = $("#hdnIdRelaboralEditar").val();
    var item = 0;
    var idArea = 0;
    /*
     Si se ha definido la opción de registro de áreas
     */
    if ($("#lstAreasEditar").val() != null) {
        idArea = $("#lstAreasEditar").val();
    }
    var idRegional = 1;
    var idPersona = $("#hdnIdPersonaSeleccionadaEditar").val();
    var idCargo = $("#hdnIdCargoSeleccionadoEditar").val();
    var idUbicacion = $('#lstUbicacionesEditar').val();
    var idProceso = $('#lstProcesosEditar').val();
    //var idCategoria = $('#lstCategorias').val();
    var idCondicion = $("#hdnIdCondicionEditableSeleccionada").val();
    var numContrato = '';
    //Si la condición de la relación laboral es consultoría se requiere que se llene el campo del número de contrato.
    var fechaFin = null;
    if (idCondicion == 2 || idCondicion == 3 || idCondicion == 7) {
        numContrato = $("#txtNumContratoEditar").val();
        var fechaFin = $('#FechaFinEditar').jqxDateTimeInput('getText');
    }
    var fechaIni = $('#FechaIniEditar').jqxDateTimeInput('getText');
    var fechaIncor = $('#FechaIncorEditar').jqxDateTimeInput('getText');
    var observacion = $("#txtObservacionEditar").val();
    if (id_relaboral > 0 && idPersona > 0 && idCargo > 0) {
        var ok = $.ajax({
            url: '/relaborales/save/',
            type: 'POST',
            datatype: 'json',
            async: false,
            data: {
                id: id_relaboral,
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
                $(".msjes").hide();
                if (res.result == 1) {
                    $("#divMsjePorSuccess").html("");
                    $("#divMsjePorSuccess").append(res.msj);
                    $("#divMsjeNotificacionSuccess").jqxNotification("open");
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
                }

            }, //mostramos el error
            error: function () {
                alert('Se ha producido un error Inesperado');
            }
        });
    } else {
        ok = false;
    }
    return ok;
}
