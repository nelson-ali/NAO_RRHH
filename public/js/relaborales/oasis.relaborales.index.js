/**
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  21-10-2014
 */
$().ready(function () {
    /**
     * Inicialmente se habilita solo la pestaña del listado
     */
    $('#jqxTabs').jqxTabs('theme', 'oasis');
    $('#jqxTabs').jqxTabs('enableAt', 1);
    $('#jqxTabs').jqxTabs('disableAt', 1);
    $('#jqxTabs').jqxTabs('disableAt', 2);
    $('#jqxTabs').jqxTabs('disableAt', 3);
    $('#jqxTabs').jqxTabs('disableAt', 4);
    $('#jqxTabs').jqxTabs('disableAt', 5);
    /**
     * Definición de la ventana donde se ve el historial de registros de relación laboral
     */
    $('#HistorialSplitter').jqxSplitter({
        theme: 'oasis',
        width: '100%',
        height: 480,
        panels: [{size: '8%'}, {size: '92%'}]
    });
    var fecha = new Date();
    var anio = fecha.getFullYear();
    cargarGestionesRelaborales(anio);
    /**
     * Se inicializa la gestión por defecto del sistema
     * @type {*|{get, set, inputmaskpatch}|jQuery}
     */
    var objColumnasOcultas = {
        "hdnNombres": {"title": "Nombres y Apellidos", "selectable": true, "hidden": true},
        "hdnCi": {"title": "CI", "selectable": false, "hidden": false},
        "hdnExpd": {"title": "Expd", "selectable": false, "hidden": false},
        "hdnGenero": {"title": "Genero", "selectable": true, "hidden": true},
        "hdnEdad": {"title": "Edad", "selectable": true, "hidden": true},
        "hdnFechaNac": {"title": "Fecha Nac", "selectable": true, "hidden": true},
        "hdnFechaCumple": {"title": "Fecha Cumple", "selectable": true, "hidden": true},
        "hdnGrupoSanguineo": {"title": "Tipo Sangre", "selectable": true, "hidden": true},
        "hdnEstadoDescripcion": {"title": "Estado", "selectable": false, "hidden": false},
        "hdnActivo": {"title": "Activo", "selectable": true, "hidden": true},
        "hdnUbicacion": {"title": "Ubicacion", "selectable": true, "hidden": true},
        "hdnCondicion": {"title": "Condicion", "selectable": true, "hidden": true},
        "hdnGerencia": {"title": "Gerencia", "selectable": false, "hidden": false},
        "hdnDepartamento": {"title": "Dependencia", "selectable": true, "hidden": true},
        "hdnArea": {"title": "Area", "selectable": true, "hidden": true},
        "hdnProcesoContratacion": {"title": "Proceso", "selectable": true, "hidden": true},
        "hdnFuente": {"title": "Fuente", "selectable": true, "hidden": true},
        "hdnNivelSalarial": {"title": "Nivel", "selectable": true, "hidden": true},
        "hdnCargo": {"title": "Cargo", "selectable": true, "hidden": true},
        "hdnHaber": {"title": "Haber", "selectable": true, "hidden": true},
        "hdnFechaIng": {"title": "Fecha Ing", "selectable": true, "hidden": true},
        "hdnFechaIni": {"title": "Fecha Ini", "selectable": true, "hidden": true},
        "hdnFechaIncor": {"title": "Fecha Incor", "selectable": true, "hidden": true},
        "hdnFechaFin": {"title": "Fecha Fin", "selectable": true, "hidden": true},
        "hdnFechaBaja": {"title": "Fecha Baja", "selectable": true, "hidden": true},
        "hdnMotivoBaja": {"title": "Motivo Baja", "selectable": true, "hidden": true},
        "hdnInternoInst": {"title": "Nro. Interno", "selectable": true, "hidden": true},
        "hdnCelularPer": {"title": "Celular Per.", "selectable": true, "hidden": true},
        "hdnCelularInst": {"title": "Celular Inst.", "selectable": true, "hidden": true},
        "hdnEmailPer": {"title": "E-mail Per.", "selectable": true, "hidden": true},
        "hdnEmailInst": {"title": "E-mail Inst.", "selectable": true, "hidden": true},
        "hdnCasFechaEmi": {"title": "Fecha Emi CAS", "selectable": true, "hidden": true},
        "hdnCasFechaPres": {"title": "Fecha Pres CAS", "selectable": true, "hidden": true},
        "hdnCasFechaFinCal": {"title": "Fecha Fin Cal CAS", "selectable": true, "hidden": true},
        "hdnCasNumero": {"title": "Nro. CAS", "selectable": true, "hidden": true},
        "hdnCasCodigoVerificacion": {"title": "Cod. Verif. CAS", "selectable": true, "hidden": true},
        "hdnCasAnios": {"title": "Años CAS", "selectable": true, "hidden": true},
        "hdnCasMeses": {"title": "Meses CAS", "selectable": true, "hidden": true},
        "hdnCasDias": {"title": "Dias CAS", "selectable": true, "hidden": true},
        "hdnMtAnios": {"title": "Años MT->CAS", "selectable": true, "hidden": true},
        "hdnMtMeses": {"title": "Meses MT->CAS", "selectable": true, "hidden": true},
        "hdnMtDias": {"title": "Dias MT->CAS", "selectable": true, "hidden": true},
        "hdnAntiguedadTotalAnios": {"title": "Total Años", "selectable": true, "hidden": true},
        "hdnantiguedadTotalMeses": {"title": "Total Meses", "selectable": true, "hidden": true},
        "hdnAntiguedadTotalDias": {"title": "Total Dias", "selectable": true, "hidden": true},
        "hdnMtFinMesAnios": {"title": "Años (Fin Mes)", "selectable": true, "hidden": true},
        "hdnMtFinMesMeses": {"title": "Meses  (Fin Mes)", "selectable": true, "hidden": true},
        "hdnMtFinMesDias": {"title": "Dias (Fin Mes)", "selectable": true, "hidden": true},
        "hdnObservacion": {"title": "Observacion", "selectable": true, "hidden": true}
    };
    defineColumnasOcultas("jqxgrid", "divComboColumnasVisibles", "chkAllColumnasVisibles", objColumnasOcultas);
    definirGrillaParaListaRelaborales($("#lstGestion").val(), objColumnasOcultas);
    habilitarCamposParaNuevoRegistroDeRelacionLaboral();
    $("#btnGuardarEditar").click(function () {
        var ok = validaFormularioPorEditarRegistro();
        if (ok) {
            guardarRegistroEditado();
        }
    });
    $("#lstGestion").on("change", function () {
        definirGrillaParaListaRelaborales($(this).val(), objColumnasOcultas);
    });
    /**
     * Control sobre la solicitud de guardar registro de movilidad de personal por nuevo, edición y baja.
     */
    $("#btnGuardarMovilidad").click(function () {
        var idRelaboralMovilidadBaja = $("#hdnIdRelaboralMovilidadBaja").val();
        if (idRelaboralMovilidadBaja == 0) {
            /**
             * Si se solicita nuevo registro o modificación.
             * @type {boolean}
             */
            var ok = validaFormularioPorRegistroMovilidad();
            if (ok) {
                var okk = guardarRegistroMovilidad();
                if (okk) {
                    $("#popupWindowNuevaMovilidad").jqxWindow('close');
                }
            }
        } else {
            /**
             * Si se ha solicitado realizar una baja.
             */
            var ok = validaFormularioPorBajaRegistroMovilidad();
            if (ok) {
                var okk = bajaRegistroMovilidad();
                if (okk) {
                    $("#popupWindowNuevaMovilidad").jqxWindow('close');
                }
            }
        }
    });
    $("#btnCancelarNuevo").click(function () {
        $('#jqxTabs').jqxTabs('enableAt', 0);
        $('#jqxTabs').jqxTabs('disableAt', 1);
        $('#jqxTabs').jqxTabs('disableAt', 2);
        $('#jqxTabs').jqxTabs('disableAt', 3);
        $('#jqxTabs').jqxTabs('disableAt', 4);
        $('#jqxTabs').jqxTabs('disableAt', 5);
        $("#msjs-alert").hide();
        deshabilitarCamposParaNuevoRegistroDeRelacionLaboral();
    });
    $("#btnCancelarEditar").click(function () {
        $('#jqxTabs').jqxTabs('enableAt', 0);
        $('#jqxTabs').jqxTabs('disableAt', 1);
        $('#jqxTabs').jqxTabs('disableAt', 2);
        $('#jqxTabs').jqxTabs('disableAt', 3);
        $('#jqxTabs').jqxTabs('disableAt', 4);
        $('#jqxTabs').jqxTabs('disableAt', 5);
        $("#msjs-alert").hide();
        deshabilitarCamposParaEditarRegistroDeRelacionLaboral();
    });
    $("#btnCancelarBaja").click(function () {
        $('#jqxTabs').jqxTabs('enableAt', 0);
        $('#jqxTabs').jqxTabs('disableAt', 1);
        $('#jqxTabs').jqxTabs('disableAt', 2);
        $('#jqxTabs').jqxTabs('disableAt', 3);
        $('#jqxTabs').jqxTabs('disableAt', 4);
        $('#jqxTabs').jqxTabs('disableAt', 5);
        $("#msjs-alert").hide();
        deshabilitarCamposParaBajaRegistroDeRelacionLaboral();
    });
    $("#btnCancelarVista").click(function () {
        $('#jqxTabs').jqxTabs('enableAt', 0);
        $('#jqxTabs').jqxTabs('disableAt', 1);
        $('#jqxTabs').jqxTabs('disableAt', 2);
        $('#jqxTabs').jqxTabs('disableAt', 3);
        $('#jqxTabs').jqxTabs('disableAt', 4);
        $('#jqxTabs').jqxTabs('disableAt', 5);
        $("#msjs-alert").hide();
    });
    $("#btnVolverDesdeMovilidad").click(function () {
        $('#jqxTabs').jqxTabs('enableAt', 0);
        $('#jqxTabs').jqxTabs('disableAt', 1);
        $('#jqxTabs').jqxTabs('disableAt', 2);
        $('#jqxTabs').jqxTabs('disableAt', 3);
        $('#jqxTabs').jqxTabs('disableAt', 4);
        $('#jqxTabs').jqxTabs('disableAt', 5);
        $("#msjs-alert").hide();
    });
    $("#btnVolverDesdeBaja").click(function () {
        $('#jqxTabs').jqxTabs('enableAt', 0);
        $('#jqxTabs').jqxTabs('disableAt', 1);
        $('#jqxTabs').jqxTabs('disableAt', 2);
        $('#jqxTabs').jqxTabs('disableAt', 3);
        $('#jqxTabs').jqxTabs('disableAt', 4);
        $('#jqxTabs').jqxTabs('disableAt', 5);
        $("#msjs-alert").hide();
    });
    $("#btnCancelarMovilidad").click(function () {
        /**
         * Inicialmente es necesario eliminar los eventos sobre este elemento para que no se repitan
         */
        $("#lstTipoMemorandum").off();
    });

    $('#popupGrillaCargo').on('show', function () {
        $(this).find('.modal-body').css({
            width: 'auto', //probably not needed
            height: 'auto', //probably not needed
            'max-height': '100%'
        });
    });
    $("#chkAllCols").click(function () {
        if (this.checked == true) {
            $("#jqxlistbox").jqxListBox('checkAll');
        } else {
            $("#jqxlistbox").jqxListBox('uncheckAll');
        }
    });
    $("#btnImprimirHistorial").on("click", function () {
        var opciones = {mode: "popup", popClose: false};
        $("#HistorialSplitter").printArea(opciones);
    });
    /**
     * Control sobre el cambio en el listado de motivos de baja
     */
    $("#lstMotivosBajas").change(function () {
        var res = this.value.split("_");
        $("#hdnFechaRenBaja").val(res[0]);
        $("#hdnFechaAceptaRenBaja").val(res[1]);
        $("#hdnFechaAgraServBaja").val(res[2]);
        if (res[0] > 0) defineFechasBajas(res[1], res[2], res[3]);
        else $("#divFechasBaja").hide();
    });
    /**
     * Control sobre el uso o no de a.i. en el cargo para movilidad de personal.
     */
    $("#chkAi").on("click", function () {
        var cargo = $("#txtCargoMovilidad").val();
        var sw = 0;
        if (jQuery.type(cargo) == "object") {
            cargo = String(cargo.label);
        }
        cargo = cargo + '';
        if (cargo != null && cargo != '') {
            if (this.checked == true) {
                var n = cargo.indexOf("a.i.");
                if (n < 0) {
                    cargo = cargo + " a.i.";
                    $('#txtCargoMovilidad').val(cargo);
                    //$('#txtCargoMovilidad').jqxInput('val', {label: cargo, value: cargo});
                }
            } else {
                var n = cargo.indexOf("a.i.");
                if (n > 0) {
                    cargo = cargo.replace("a.i.", "").trim();
                    $('#txtCargoMovilidad').val(cargo);
                    //$('#txtCargoMovilidad').jqxInput('val', {label: cargo, value: cargo});
                }
            }
        }
    });

    $("#liList").click(function () {
        $("#btnCancelarNuevo").click();
        $("#btnCancelarEditar").click();
        $("#btnCancelarBaja").click();
    });
    $("#popupWindowNuevaMovilidad").jqxWindow({
        position: {x: 300, y: 200},
        height: 700,
        width: '100%',
        resizable: true,
        isModal: true,
        autoOpen: false,
        cancelButton: $("#btnCancelarMovilidad"),
        modalOpacity: 0.01
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
    $("#txtMotivoMovilidad").jqxInput({
        width: 300,
        height: 35,
        placeHolder: "Introduzca el motivo de la comisión."
    });
    $("#txtLugarMovilidad").jqxInput({
        width: 300,
        height: 35,
        placeHolder: "Introduzca el lugar donde se realizará el evento."
    });
    $(document).keypress(OperaEvento);
    $(document).keyup(OperaEvento);
});

/**
 * Función para obtener el listado de gestiones para el registro de relaciones laborales.
 * @returns {string}
 */
function cargarGestionesRelaborales(gest) {
    var selection = "";
    $("#lstGestion").html("");
    $("#lstGestion").append("<option value='0'>TODAS</option>");
    $.ajax({
        url: '/relaborales/getgestionesrelaborales/',
        type: "POST",
        datatype: 'json',
        async: false,
        cache: false,
        success: function (data) {
            var res = jQuery.parseJSON(data);
            $.each(res, function (index, value) {
                if (gest == value) {
                    selection = "selected";
                } else {
                    selection = "";
                }
                $("#lstGestion").append("<option value='" + value + "' " + selection + ">" + value + "</option>");
            });
        }
        ,
        error: function () {
            alert('Se ha producido un error Inesperado');
        }
    })
    ;
}

/**
 * Función para definir la grilla principal (listado) para la gestión de relaciones laborales.
 * @param gestionConsulta
 * @param objColumnasOcultas
 */
function definirGrillaParaListaRelaborales(gestionConsulta, objColumnasOcultas) {
    var source =
        {
            datatype: "json",
            datafields: [
                {name: 'nro_row', type: 'integer'},
                {name: 'fecha_nac', type: 'string'},
                {name: 'edad', type: 'integer'},
                {name: 'lugar_nac', type: 'integer'},
                {name: 'genero', type: 'integer'},
                {name: 'fecha_cumple', type: 'date'},
                {name: 'fecha_nac', type: 'date'},
                {name: 'e_civil', type: 'integer'},
                {name: 'grupo_sanguineo', type: 'string'},
                {name: 'id_relaboral', type: 'integer'},
                {name: 'id_persona', type: 'integer'},
                {name: 'tiene_contrato_vigente', type: 'integer'},
                {name: 'tiene_contrato_vigente_descripcion', type: 'string'},
                {name: 'id_fin_partida', type: 'integer'},
                {name: 'finpartida', type: 'string'},
                {name: 'id_condicion', type: 'integer'},
                {name: 'condicion', type: 'string'},
                {name: 'tiene_item', type: 'integer'},
                {name: 'id_cargo', type: 'integer'},
                {name: 'cargo_codigo', type: 'string'},
                {name: 'cargo_resolucion_ministerial_id', type: 'integer'},
                {name: 'cargo_resolucion_ministerial', type: 'string'},
                {name: 'estado', type: 'integer'},
                {name: 'estado_descripcion', type: 'string'},
                {name: 'nombres', type: 'string'},
                {name: 'ci', type: 'string'},
                {name: 'expd', type: 'string'},
                {name: 'num_complemento', type: 'string'},
                {name: 'id_organigrama', type: 'integer'},
                {name: 'id_gerencia_administrativa', type: 'integer'},
                {name: 'gerencia_administrativa', type: 'string'},
                {name: 'departamento_administrativo', type: 'string'},
                {name: 'id_area', type: 'integer'},
                {name: 'area', type: 'string'},
                {name: 'id_ubicacion', type: 'integer'},
                {name: 'ubicacion', type: 'string'},
                {name: 'num_contrato', type: 'string'},
                {name: 'fin_partida', type: 'string'},
                {name: 'id_procesocontratacion', type: 'integer'},
                {name: 'proceso_codigo', type: 'string'},
                {name: 'nivelsalarial', type: 'string'},
                {name: 'nivelsalarial_resolucion', type: 'string'},
                {name: 'cargo', type: 'string'},
                {name: 'cargo_gestion', type: 'integer'},
                {name: 'cargo_correlativo', type: 'string'},
                {name: 'sueldo', type: 'numeric'},
                {name: 'fecha_ini', type: 'date'},
                {name: 'fecha_incor', type: 'date'},
                {name: 'fecha_fin', type: 'date'},
                {name: 'fecha_baja', type: 'date'},
                {name: 'motivo_baja', type: 'string'},
                {name: 'relaboral_previo_id', type: 'integer'},
                {name: 'observacion', type: 'string'},
                {name: 'fecha_ing', type: 'date'},
                {name: 'id_presentaciondoc', type: 'integer'},
                {name: 'interno_inst', type: 'string'},
                {name: 'celular_per', type: 'string'},
                {name: 'celular_inst', type: 'string'},
                {name: 'e_mail_per', type: 'string'},
                {name: 'e_mail_inst', type: 'string'},
                {name: 'cas_fecha_emi', type: 'date', format: 'dd-MM-yyyy'},
                {name: 'cas_fecha_pres', type: 'date', format: 'dd-MM-yyyy'},
                {name: 'cas_fecha_fin_cal', type: 'date', format: 'dd-MM-yyyy'},
                {name: 'cas_numero', type: 'string'},
                {name: 'cas_codigo_verificacion', type: 'string'},
                {name: 'cas_anios', type: 'number'},
                {name: 'cas_meses', type: 'number'},
                {name: 'cas_dias', type: 'number'},
                {name: 'mt_anios', type: 'number'},
                {name: 'mt_meses', type: 'number'},
                {name: 'mt_dias', type: 'number'},
                {name: 'tot_anios', type: 'number'},
                {name: 'tot_meses', type: 'number'},
                {name: 'tot_dias', type: 'number'},
                {name: 'mt_fin_mes_anios', type: 'number'},
                {name: 'mt_fin_mes_meses', type: 'number'},
                {name: 'mt_fin_mes_dias', type: 'number'},
            ],
            url: '/relaborales/listpaged?opcion=1&gestion=' + gestionConsulta,
            cache: false,
            root: 'Rows',
            beforeprocessing: function (data) {
                source.totalrecords = data[0].TotalRows;
            },
            filter: function () {
                // Actualiza la grilla y reenvia los datos actuales al servidor
                $("#jqxgrid").jqxGrid('updatebounddata', 'filter');
            },
            sort: function () {
                // Actualiza la grilla y reenvia los datos actuales al servidor
                $("#jqxgrid").jqxGrid('updatebounddata', 'sort');
            }
        };
    var dataAdapter = new $.jqx.dataAdapter(source);
    cargarRegistrosDeRelacionesLaborales();

    function cargarRegistrosDeRelacionesLaborales() {
        var filtrables = obtenerFiltrables(gestionConsulta);
        var expds = filtrables.expds;
        var sueldos = filtrables.sueldos;
        var gerencias = filtrables.gerencias;
        var condiciones = filtrables.condiciones;
        var departamentos = filtrables.departamentos;
        var ubicaciones = filtrables.ubicaciones;
        var edades = filtrables.edades;
        var antiguedadAnios = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10","11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "33", "34", "35", "36", "37", "38", "39", "40", "41", "42", "43", "44", "45", "46", "47", "48", "49", "50"];
        var antiguedadMeses = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11"];
        var antiguedadDias = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "15", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30"];
        $("#jqxgrid").jqxGrid(
            {
                width: '100%',
                height: 600,
                source: dataAdapter,
                sortable: true,
                altRows: true,
                groupable: true,
                columnsresize: true,
                pageable: true,
                pagerMode: 'advanced',
                pagesize: 10,
                virtualmode: true,
                rendergridrows: function (params) {
                    return params.data;
                },
                showfilterrow: true,
                filterable: true,
                showtoolbar: true,
                autorowheight: true,
                enablebrowserselection: true,
                rendertoolbar: function (toolbar) {
                    var me = this;
                    var container = $("<div></div>");
                    toolbar.append(container);
                    container.append("<button title='Nuevo registro.' id='addnewrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-plus-square fa-2x text-primary' title='Nuevo Registro'/></i></button>");
                    /*container.append("<button id='approverowbutton'  class='btn btn-sm btn-primary' type='button' ><i class='fa fa-check-square fa-2x text-info' title='Aprobar registro'></i></button>");*/
                    container.append("<button title='Modificar registro.' id='updaterowbutton'  class='btn btn-sm btn-primary' type='button' ><i class='fa fa-pencil-square fa-2x text-info' title='Modificar registro'/></button>");
                    container.append("<button title='Dar de baja al registro.' id='deleterowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-minus-square fa-2x text-danger' title='Dar de baja al registro'/></i></button>");
                    container.append("<button title='Movilidad de Personal' id='moverowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-tag fa-2x text-warning' title='Movilidad de Personal'/></i></button>");
                    container.append("<button title='Vista Historial' id='viewrowbutton' class='btn btn-sm btn-primary' type='button'><i class='gi gi-nameplate_alt fa-2x text-purple' title='Vista Historial'/></i></button>");
                    container.append("<button title='Exportar a Excel' id='exportexcelrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fi fi-xls fa-2x text-success' title='Exportar a Excel'/></i></button>");
                    container.append("<button title='Exportar a PDF' id='exportpdfrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fi fi-pdf fa-2x text-danger' title='Exportar a PDF'/></i></button>");
                    container.append("<button title='Refrescar Grilla' id='refreshbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-repeat fa-2x text-default' title='Refrescar grilla'/></i></button>");
                    container.append("<button title='Desagrupar' id='cleargroupsrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-chain-broken fa-2x text-default' title='Desagrupar'/></i></button>");
                    container.append("<button title='Desfiltrar' id='clearfiltersrowbutton' class='btn btn-sm btn-primary' type='button'><i class='gi gi-sorting fa-2x text-default' title='Desfiltrar'/></i></button>");


                    $("#addnewrowbutton").jqxButton();
                    /*$("#approverowbutton").jqxButton();*/
                    $("#updaterowbutton").jqxButton();
                    $("#deleterowbutton").jqxButton();
                    $("#moverowbutton").jqxButton();
                    $("#viewrowbutton").jqxButton();
                    $("#refreshbutton").jqxButton();
                    $("#cleargroupsrowbutton").jqxButton();
                    $("#clearfiltersrowbutton").jqxButton();
                    $("#exportexcelrowbutton").jqxButton();
                    $("#exportpdfrowbutton").jqxButton();
                    /**
                     * Refrescar la grilla
                     */
                    $("#refreshbutton").off();
                    $("#refreshbutton").on('click', function () {
                        $("#jqxgrid").jqxGrid("updatebounddata");
                    });
                    /**
                     * Desagrupar
                     */
                    $("#cleargroupsrowbutton").off();
                    $("#cleargroupsrowbutton").on('click', function () {
                        $("#jqxgrid").jqxGrid('cleargroups');
                    });
                    /**
                     * Desfiltrar
                     */
                    $("#clearfiltersrowbutton").off();
                    $("#clearfiltersrowbutton").on('click', function () {
                        $("#jqxgrid").jqxGrid('clearfilters');
                    });
                    /**
                     * Exportar a formato Excel.
                     */
                    $("#exportexcelrowbutton").off();
                    $("#exportexcelrowbutton").on('click', function () {
                        exportarReporte(1);
                    });
                    /**
                     * Exportar a formato PDF.
                     */
                    $("#exportpdfrowbutton").off();
                    $("#exportpdfrowbutton").on('click', function () {
                        exportarReporte(2);
                    });

                    /* Registrar nueva relación laboral.*/
                    $("#addnewrowbutton").off();
                    $("#addnewrowbutton").on('click', function () {
                        $("#formNuevo")[0].reset();
                        var selectedrowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var dataRecord = $('#jqxgrid').jqxGrid('getrowdata', selectedrowindex);
                            if (dataRecord != undefined) {
                                /**
                                 * Para el caso cuando la persona no tenga ninguna relación laboral vigente con la entidad se da la opción de registro de nueva relación laboral.
                                 */
                                if (dataRecord.id_persona > 0) {
                                    if (dataRecord.tiene_contrato_vigente == 0 || dataRecord.tiene_contrato_vigente == -1) {
                                        limpiarFormularioNuevoRegistro(1);
                                        limpiarMensajesErrorPorValidacionNuevoRegistro();
                                        $("#hdnIdRelaboralEditar").val(dataRecord.id_relaboral);
                                        $("#hdnIdPersonaSeleccionada").val(dataRecord.id_persona);
                                        $("#NombreParaNuevoRegistro").html(dataRecord.nombres);
                                        $("#CorreoPersonal").html("");
                                        $("#hdnIdCondicionNuevaSeleccionada").val(0)
                                        $("#divAreas").hide();
                                        $("#divItems").hide();
                                        $("#divFechasFin").hide();
                                        $("#divNumContratos").hide();
                                        $(".msjs-alert").hide();
                                        $("#divProcesos").hide();
                                        $("#txtNumContrato").val("");
                                        $("#lstUbicaciones").val("");
                                        var rutaImagen = obtenerRutaFoto(dataRecord.ci, dataRecord.num_complemento);
                                        $("#imgFotoPerfilNuevo").attr("src", rutaImagen);
                                        $("#tr_cargo_seleccionado").html("<td style='height: 120px;' colspan='11'>&nbsp;</td>");
                                        /*$("#tr_cargo_seleccionado").append("<td style='height: 120px;' colspan='11'>&nbsp;</td>");*/
                                        $("#btnGuardarNuevo").off();
                                        $("#btnGuardarNuevo").on("click", function () {
                                            var ok = validaFormularioPorNuevoRegistro();
                                            if (ok) {
                                                var idRelaboral = guardarNuevoRegistro();
                                                $('#jqxTabs').jqxTabs('enableAt', 0);
                                                $('#jqxTabs').jqxTabs('disableAt', 1);
                                                /*deshabilitarCamposParaNuevoRegistroDeRelacionLaboral();*/
                                                if (idRelaboral != null && idRelaboral > 0) {
                                                    var d = new Date();
                                                    var gestionActual = parseInt(d.getFullYear().toString());
                                                    var fechaIncor = $('#FechaIncor').jqxDateTimeInput('getText');
                                                    var arrFechaIncor = fechaIncor.split("-");
                                                    var gestionContrato = parseInt(arrFechaIncor[2]);
                                                    if (gestionActual == gestionContrato) {
                                                        var okl = defineListaDestinatarios(idRelaboral);
                                                        if (okl) {
                                                            if (CKEDITOR.instances.txtEditorMensaje) CKEDITOR.instances.txtEditorMensaje.destroy();
                                                            $("#txtEditorMensaje").val("");
                                                            CKEDITOR.replace('txtEditorMensaje',
                                                                {
                                                                    customConfig: 'config.min.js',
                                                                    on: {
                                                                        'instanceReady': function (evt) {
                                                                            CKEDITOR.instances.txtEditorMensaje.focus();
                                                                        }
                                                                    }
                                                                }
                                                            );
                                                            CKEDITOR.add;
                                                            $("#popupMensajeNuevaIncorporacion").off();
                                                            $("#popupMensajeNuevaIncorporacion").modal("show");
                                                            $("#btnEnviarMensaje").off();
                                                            $("#btnEnviarMensaje").on("click", function () {
                                                                var oks = enviarMensajePorOperacion(idRelaboral, 1);
                                                                if (oks) {
                                                                    $("#popupMensajeNuevaIncorporacion").modal("hide");
                                                                }
                                                            });
                                                        }
                                                    }
                                                } else {

                                                }
                                            }
                                        });
                                        $('#jqxTabs').jqxTabs('enableAt', 1);
                                        $('#jqxTabs').jqxTabs('disableAt', 2);
                                        $('#jqxTabs').jqxTabs('disableAt', 3);
                                        $('#jqxTabs').jqxTabs('disableAt', 4);
                                        $('#jqxTabs').jqxTabs('disableAt', 5);
                                        /**
                                         * Trasladamos el item seleccionado al que corresponde, el de nuevo registro.
                                         */
                                        $('#jqxTabs').jqxTabs({selectedItem: 1});
                                        $('#btnBuscarCargo').click();
                                    } else {
                                        var msje = "La persona seleccionada tiene actualmente un registro en estado " + dataRecord.estado_descripcion + " de relaci&oacute;n laboral por lo que no se le puede asignar otro.";
                                        $("#divMsjePorError").html("");
                                        $("#divMsjePorError").append(msje);
                                        $("#divMsjeNotificacionError").jqxNotification("open");
                                    }
                                } else {
                                    alert("no se encuentra id persona");
                                }
                            }
                        } else {
                            var msje = "Debe seleccionar un registro necesariamente.";
                            $("#divMsjePorError").html("");
                            $("#divMsjePorError").append(msje);
                            $("#divMsjeNotificacionError").jqxNotification("open");
                        }
                    });
                    /*Aprobar registro.*/
                    /*$("#approverowbutton").on('click', function () {
                     var selectedrowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
                     if (selectedrowindex >= 0) {
                     var dataRecord = $('#jqxgrid').jqxGrid('getrowdata', selectedrowindex);
                     if (dataRecord != undefined) {
                     */
                    /*
                     * Para el caso cuando la persona tenga un registro de relación laboral en estado EN PROCESO.
                     */
                    /*
                     if (dataRecord.estado == 2) {
                     if(confirm("¿Esta seguro de aprobar este registro?")){
                     aprobarRegistroRelabolar(dataRecord.id_relaboral);
                     }
                     }else {
                     var msje = "Debe seleccionar un registro con estado EN PROCESO para posibilitar la aprobaci&oacute;n del registro";
                     $("#divMsjePorError").html("");
                     $("#divMsjePorError").append(msje);
                     $("#divMsjeNotificacionError").jqxNotification("open");
                     }
                     }
                     }else{
                     var msje = "Debe seleccionar un registro necesariamente.";
                     $("#divMsjePorError").html("");
                     $("#divMsjePorError").append(msje);
                     $("#divMsjeNotificacionError").jqxNotification("open");
                     }
                     });*/
                    /* Modificar registro.*/
                    $("#updaterowbutton").off();
                    $("#updaterowbutton").on('click', function () {
                        var selectedrowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var dataRecord = $('#jqxgrid').jqxGrid('getrowdata', selectedrowindex);
                            if (dataRecord != undefined) {
                                var id_relaboral = dataRecord.id_relaboral;
                                /**
                                 * Para el caso cuando la persona tenga un registro de relación laboral en estado EN PROCESO o ACTIVO.
                                 */
                                if (dataRecord.estado != null && dataRecord.estado >= 0) {//Modificado eventualmente
                                    $('#jqxTabs').jqxTabs('enableAt', 0);
                                    $('#jqxTabs').jqxTabs('disableAt', 1);
                                    $('#jqxTabs').jqxTabs('enableAt', 2);
                                    $('#jqxTabs').jqxTabs('disableAt', 3);
                                    $('#jqxTabs').jqxTabs('disableAt', 4);
                                    $('#jqxTabs').jqxTabs('disableAt', 5);
                                    /**
                                     * Trasladamos el item seleccionado al que corresponde, el de modificación
                                     */
                                    $('#jqxTabs').jqxTabs({selectedItem: 2});
                                    limpiarFormularioNuevoRegistro(2);
                                    $("#hdnIdRelaboralEditar").val(id_relaboral);
                                    $("#hdnIdPersonaSeleccionadaEditar").val(dataRecord.id_persona);
                                    $("#NombreParaEditarRegistro").html(dataRecord.nombres);
                                    $("#hdnIdCondicionEditableSeleccionada").val(dataRecord.id_condicion);
                                    $("#hdnIdUbicacionEditar").val(dataRecord.id_ubicacion);
                                    $("#hdnIdProcesoEditar").val(dataRecord.id_procesocontratacion);
                                    $("#FechaIniEditar").jqxDateTimeInput({
                                        value: dataRecord.fecha_ini,
                                        enableBrowserBoundsDetection: false,
                                        height: 24,
                                        formatString: 'dd-MM-yyyy'
                                    });
                                    $("#FechaIncorEditar").jqxDateTimeInput({
                                        value: dataRecord.fecha_incor,
                                        enableBrowserBoundsDetection: false,
                                        height: 24,
                                        formatString: 'dd-MM-yyyy'
                                    });
                                    switch (dataRecord.tiene_item) {
                                        case 1:
                                            $("#divFechasFinEditar").hide();
                                            break;
                                        case 0:
                                            $("#FechaFinEditar").jqxDateTimeInput({
                                                value: dataRecord.fecha_fin,
                                                enableBrowserBoundsDetection: false,
                                                height: 24,
                                                formatString: 'dd-MM-yyyy'
                                            });
                                            break;
                                    }
                                    $("#hdnFechaFinEditar").val(dataRecord.fecha_fin);
                                    $("#txtNumContratoEditar").val(dataRecord.num_contrato);
                                    $("#divItemsEditar").hide();
                                    $("#divNumContratosEditar").hide();
                                    $(".msjs-alert").hide();
                                    $("#helpErrorUbicacionesEditar").html("");
                                    $("#helpErrorProcesosEditar").html("");
                                    $("#helpErrorCategoriasEditar").html("");
                                    $("#helpErrorFechasIniEditar").html("");
                                    $("#helpErrorFechasIncorEditar").html("");
                                    $("#helpErrorFechasFinEditar").html("");
                                    $("#divUbicacionesEditar").removeClass("has-error");
                                    $("#divProcesosEditar").removeClass("has-error");
                                    $("#divCategoriasEditar").removeClass("has-error");
                                    $("#divAreas").hide();
                                    $("#divFechasIniEditar").removeClass("has-error");
                                    $("#divFechasIncorEditar").removeClass("has-error");
                                    $("#divFechasFinEditar").removeClass("has-error");
                                    $("#tr_cargo_seleccionado_editar").html("");
                                    if (dataRecord.observacion != null) $("#txtObservacionEditar").text(dataRecord.observacion);
                                    else $("#txtObservacionEditar").text('');
                                    var rutaImagen = obtenerRutaFoto(dataRecord.ci, dataRecord.num_complemento);
                                    $("#imgFotoPerfilEditar").attr("src", rutaImagen);
                                    cargarProcesosParaEditar(dataRecord.id_condicion, dataRecord.id_procesocontratacion);
                                    var idUbicacionPrederminada = 0;
                                    if (dataRecord.id_ubicacion != null) idUbicacionPrederminada = dataRecord.id_ubicacion;
                                    cargarUbicacionesParaEditar(idUbicacionPrederminada);
                                    //.id_cargo, dataRecord.cargo_codigo, dataRecord.id_finpartida, dataRecord.finpartida, dataRecord.cargo_resolucion_ministerial_id,dataRecord.cargo_resolucion_ministerial,dataRecord.id_condicion, dataRecord.condicion, dataRecord.id_organigrama, dataRecord.gerencia_administrativa, dataRecord.departamento_administrativo, dataRecord.id_area, dataRecord.nivelsalarial, dataRecord.cargo, dataRecord.sueldo,dataRecord.nivelsalarial_resolucion_id,dataRecord.nivelsalarial_resolucion
                                    agregarCargoSeleccionadoEnGrillaParaEditar(1, dataRecord);
                                } else {
                                    var msje = "Debe seleccionar un registro con estado EN PROCESO o ACTIVO para posibilitar la modificaci&oacute;n del registro";
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
                        var selectedrowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var dataRecord = $('#jqxgrid').jqxGrid('getrowdata', selectedrowindex);
                            if (dataRecord != undefined) {
                                var id_relaboral = dataRecord.id_relaboral;
                                /*
                                 *  Para dar de baja un registro, este debe estar inicialmente en estado ACTIVO
                                 */
                                if (dataRecord.estado == 1) {
                                    $('#jqxTabs').jqxTabs('enableAt', 0);
                                    $('#jqxTabs').jqxTabs('disableAt', 1);
                                    $('#jqxTabs').jqxTabs('disableAt', 2);
                                    $('#jqxTabs').jqxTabs('enableAt', 3);
                                    $('#jqxTabs').jqxTabs('disableAt', 4);
                                    $('#jqxTabs').jqxTabs('disableAt', 5);
                                    /**
                                     * Trasladamos el item seleccionado al que corresponde, el de bajas.
                                     */
                                    $('#jqxTabs').jqxTabs({selectedItem: 3});

                                    //alert(dataRecord.fecha_incor.toString());
                                    $("#hdnIdRelaboralBaja").val(id_relaboral);
                                    $("#NombreParaBajaRegistro").html(dataRecord.nombres);
                                    $("#hdnIdCondicionSeleccionadaBaja").val(dataRecord.id_condicion);
                                    $("#txtFechaIniBaja").jqxDateTimeInput({
                                        disabled: true,
                                        value: dataRecord.fecha_ini,
                                        enableBrowserBoundsDetection: true,
                                        height: 24,
                                        formatString: 'dd-MM-yyyy'
                                    });
                                    $("#txtFechaIncorBaja").jqxDateTimeInput({
                                        disabled: true,
                                        value: dataRecord.fecha_incor,
                                        enableBrowserBoundsDetection: true,
                                        height: 24,
                                        formatString: 'dd-MM-yyyy'
                                    });
                                    $("#txtFechaFinBaja").jqxDateTimeInput({
                                        disabled: true,
                                        value: dataRecord.fecha_fin,
                                        enableBrowserBoundsDetection: true,
                                        height: 24,
                                        formatString: 'dd-MM-yyyy'
                                    });
                                    $("#txtFechaRenBaja").jqxDateTimeInput({
                                        value: dataRecord.fecha_fin,
                                        enableBrowserBoundsDetection: true,
                                        height: 24,
                                        formatString: 'dd-MM-yyyy'
                                    });
                                    $("#txtFechaAceptaRenBaja").jqxDateTimeInput({
                                        value: dataRecord.fecha_fin,
                                        enableBrowserBoundsDetection: true,
                                        height: 24,
                                        formatString: 'dd-MM-yyyy'
                                    });
                                    $("#txtFechaAgraServBaja").jqxDateTimeInput({
                                        value: dataRecord.fecha_fin,
                                        enableBrowserBoundsDetection: true,
                                        height: 24,
                                        formatString: 'dd-MM-yyyy'
                                    });
                                    $("#txtFechaBaja").jqxDateTimeInput({
                                        value: dataRecord.fecha_fin,
                                        enableBrowserBoundsDetection: true,
                                        height: 24,
                                        formatString: 'dd-MM-yyyy'
                                    });
                                    $(".msjs-alert").hide();
                                    $("#divFechasRenBaja").hide();
                                    $("#divFechasAceptaRenBaja").hide();
                                    $("#divFechasAgraServBaja").hide();
                                    $("#txtObservacionBaja").val(dataRecord.observacion);
                                    $("#divMsjeError").hide();
                                    $("#tr_cargo_seleccionado_baja").html("");
                                    $("#lstMotivosBajas").focus();
                                    $("#hdnFechaRenBaja").val(0);
                                    $("#hdnFechaAceptaRenBaja").val(0);
                                    $("#hdnFechaAgraServBaja").val(0);
                                    //dataRecord.id_cargo, dataRecord.cargo_codigo, dataRecord.cargo_resolucion_ministerial_id, dataRecord.cargo_resolucion_ministerial,dataRecord.id_finpartida, dataRecord.finpartida, dataRecord.id_condicion, dataRecord.condicion, dataRecord.id_organigrama, dataRecord.gerencia_administrativa, dataRecord.departamento_administrativo, dataRecord.nivelsalarial, dataRecord.cargo, dataRecord.sueldo,dataRecord.nivelsalarial_resolucion_id,dataRecord.nivelsalarial_resolucion
                                    agregarCargoSeleccionadoEnGrillaParaBaja(dataRecord);
                                    cargarMotivosBajas(0, dataRecord.id_condicion);
                                    //habilitarCamposParaBajaRegistroDeRelacionLaboral(dataRecord.id_organigrama,dataRecord.id_fin_partida,dataRecord.id_condicion);
                                    var rutaImagen = obtenerRutaFoto(dataRecord.ci, dataRecord.num_complemento);
                                    $("#imgFotoPerfilBaja").attr("src", rutaImagen);

                                    $("#btnGuardarBaja").off();
                                    $("#btnGuardarBaja").on("click", function () {
                                        var ok = validaFormularioPorBajaRegistro();
                                        if (ok) {
                                            guardarRegistroBaja(dataRecord);
                                        }
                                    });
                                } else {
                                    var msje = "Para dar de baja un registro, este debe estar en estado ACTIVO inicialmente.";
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
                    /* Movilidad de Personal.*/
                    $("#moverowbutton").off();
                    $("#moverowbutton").on('click', function () {
                        var selectedrowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var dataRecord = $('#jqxgrid').jqxGrid('getrowdata', selectedrowindex);
                            if (dataRecord != undefined) {
                                var id_relaboral = dataRecord.id_relaboral;
                                /*
                                 *  La vista del historial se habilita para personas que tengan al menos un registro de relación sin importar su estado, ACTIVO, EN PROCESO o PASIVO.
                                 *  De esta vista se excluyen a personas que no tengan ningún registro de relación laboral.
                                 */
                                $(".msjs-alert").hide();
                                $("#hdnIdPersonaHistorialMovimiento").val(dataRecord.id_persona);
                                $("#NombreParaMoverRegistro").html(dataRecord.nombres);
                                if (dataRecord.tiene_contrato_vigente >= 1) {
                                    $('#jqxTabs').jqxTabs('enableAt', 0);
                                    $('#jqxTabs').jqxTabs('disableAt', 1);
                                    $('#jqxTabs').jqxTabs('disableAt', 2);
                                    $('#jqxTabs').jqxTabs('disableAt', 3);
                                    $('#jqxTabs').jqxTabs('enableAt', 4);
                                    $('#jqxTabs').jqxTabs('disableAt', 5);
                                    /**
                                     * Trasladamos el item seleccionado al que corresponde, el de vistas.
                                     */
                                    $('#jqxTabs').jqxTabs({selectedItem: 4});

                                    cargarGrillaMovilidad(dataRecord.id_relaboral, dataRecord.id_gerencia_administrativa);
                                    var rutaImagen = obtenerRutaFoto(dataRecord.ci, dataRecord.num_complemento);
                                    $("#imgFotoPerfilMover").attr("src", rutaImagen);

                                } else {
                                    var msje = "Para acceder a la asignación de Movilidad Funcionaria, el estado de registro de Relación Laboral debe tener un estado ACTIVO.";
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
                    /* Ver registro.*/
                    $("#viewrowbutton").off();
                    $("#viewrowbutton").on('click', function () {

                        var selectedrowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var dataRecord = $('#jqxgrid').jqxGrid('getrowdata', selectedrowindex);
                            if (dataRecord != undefined) {
                                var id_relaboral = dataRecord.id_relaboral;
                                /*
                                 *  La vista del historial se habilita para personas que tengan al menos un registro de relación sin importar su estado, ACTIVO, EN PROCESO o PASIVO.
                                 *  De esta vista se excluyen a personas que no tengan ningún registro de relación laboral.
                                 */
                                $(".msjs-alert").hide();
                                $("#hdnIdPersonaHistorial").val(dataRecord.id_persona);
                                if (dataRecord.tiene_contrato_vigente >= 0) {
                                    $('#jqxTabs').jqxTabs('enableAt', 0);
                                    $('#jqxTabs').jqxTabs('disableAt', 1);
                                    $('#jqxTabs').jqxTabs('disableAt', 2);
                                    $('#jqxTabs').jqxTabs('disableAt', 3);
                                    $('#jqxTabs').jqxTabs('disableAt', 4);
                                    $('#jqxTabs').jqxTabs('enableAt', 5);
                                    /**
                                     * Trasladamos el item seleccionado al que corresponde, el de vistas.
                                     */
                                    $('#jqxTabs').jqxTabs({selectedItem: 5});
                                    // Create jqxTabs.
                                    $('#tabFichaPersonal').jqxTabs({
                                        theme: 'oasis',
                                        width: '100%',
                                        height: '100%',
                                        position: 'top'
                                    });
                                    $('#tabFichaPersonal').jqxTabs({selectedItem: 0});
                                    $("#ddNombres").html(dataRecord.nombres);
                                    var rutaImagen = obtenerRutaFoto(dataRecord.ci, dataRecord.num_complemento);
                                    $("#imgFotoPerfilContactoPer").attr("src", rutaImagen);
                                    $("#imgFotoPerfilContactoInst").attr("src", rutaImagen);
                                    $("#imgFotoPerfil").attr("src", rutaImagen);
                                    cargarPersonasContactos(dataRecord.id_persona);
                                    $("#hdnIdRelaboralVista").val(id_relaboral);
                                    $("#hdnSwPrimeraVistaHistorial").val(0);
                                    cargarGestionesHistorialRelaboral(dataRecord.id_persona);
                                    /**
                                     * Para la primera cargada el valor para el parámetro gestión es 0 debido a que mostrará el historial completo.
                                     * Para el valor del parámetro sw el valor es 1 porque se desea que se limpie lo que haya y se cargue algo nuevo
                                     */
                                    cargarHistorialRelacionLaboral(dataRecord.id_persona, 0, 1);
                                    $("#divContent_" + dataRecord.id_relaboral).focus().select();
                                } else {
                                    var msje = "Para acceder a la vista del registro, la persona debe haber tenido al menos un registro de relaci&oacute,n laboral que implica un estado ACTIVO o PASIVO.";
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
                    /**
                     * Se añaden las acciones para poder envíar los datos filtrables como parámetros.
                     */
                    $("#btnBuscarCargo").off();
                    $("#btnBuscarCargo").on("click", function () {
                        /*$("#divGrillaParaSeleccionarCargo").jqxGrid("clear");*/
                        $("#divContenedorGrillaParaSeleccionarCargo").html("<div id='divGrillaParaSeleccionarCargo'></div>");
                        $('#popupGrillaCargo').modal('show');
                        definirGrillaParaSeleccionarCargoAcefalo(0, '', filtrables);
                    });
                    $("#btnBuscarCargoEditar").off();
                    $("#btnBuscarCargoEditar").on("click", function () {
                        /*$("#divGrillaParaSeleccionarCargo").jqxGrid("clear");*/
                        $("#divContenedorGrillaParaSeleccionarCargo").html("<div id='divGrillaParaSeleccionarCargo'></div>");
                        $('#popupGrillaCargo').modal('show');
                        definirGrillaParaSeleccionarCargoAcefaloParaEditar(0, '', filtrables);
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
                        pinned: true,
                        width: 50,
                        cellsalign: 'center',
                        align: 'center',
                        cellsrenderer: rownumberrenderer
                    },
                    {
                        text: 'Nombres y Apellidos',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'nombres',
                        pinned: true,
                        width: 215,
                        align: 'center',
                        hidden: objColumnasOcultas.hdnNombres.hidden
                    },
                    {
                        text: 'CI',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'ci',
                        pinned: true,
                        width: 90,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnCi.hidden
                    },
                    {
                        text: 'Expd',
                        filtertype: 'checkedlist',
                        datafield: 'expd',
                        filteritems: expds,
                        pinned: true,
                        width: 40,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnExpd.hidden
                    },
                    {
                        text: 'Genero',
                        filtertype: 'checkedlist',
                        datafield: 'genero',
                        filteritems: ["F", "M"],
                        pinned: false,
                        width: 40,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnGenero.hidden
                    },
                    {
                        text: 'Edad',
                        filtertype: 'checkedlist',
                        datafield: 'edad',
                        filteritems: edades,
                        width: 40,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnEdad.hidden
                    },
                    {
                        text: 'Fecha Nac',
                        datafield: 'fecha_nac',
                        filtertype: 'range',
                        width: 100,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnFechaNac.hidden
                    },
                    {
                        text: 'Fecha Cumple',
                        datafield: 'fecha_cumple',
                        filtertype: 'range',
                        width: 100,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnFechaCumple.hidden
                    },
                    {
                        text: 'Tipo Sangre',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'grupo_sanguineo',
                        width: 70,
                        align: 'center',
                        cellsalign: 'center',
                        hidden: objColumnasOcultas.hdnGrupoSanguineo.hidden
                    },
                    {
                        text: 'Estado',
                        filtertype: 'checkedlist',
                        datafield: 'estado_descripcion',
                        filteritems: ['ACTIVO', 'PASIVO', ''],
                        width: 100,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnEstadoDescripcion.hidden,
                        cellclassname: cellclass
                    },
                    {
                        text: 'Activo',
                        filtertype: 'checkedlist',
                        datafield: 'tiene_contrato_vigente_descripcion',
                        filteritems: ['SI', 'NO', ''],
                        width: 50,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnActivo.hidden,
                        cellclassname: cellclass
                    },
                    {
                        text: 'Ubicaci&oacute;n',
                        filtertype: 'checkedlist',
                        filteritems: ubicaciones,
                        datafield: 'ubicacion',
                        width: 150,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnUbicacion.hidden
                    },
                    {
                        text: 'Condici&oacute;n',
                        columntype: 'textbox',
                        filtertype: 'checkedlist',
                        datafield: 'condicion',
                        filteritems: condiciones,
                        width: 150,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnCondicion.hidden
                    },
                    {
                        text: 'Gerencia',
                        filtertype: 'checkedlist',
                        filteritems: gerencias,
                        datafield: 'gerencia_administrativa',
                        width: 220,
                        align: 'center',
                        hidden: objColumnasOcultas.hdnGerencia.hidden
                    },
                    {
                        text: 'Dependencia',
                        filtertype: 'checkedlist',
                        datafield: 'departamento_administrativo',
                        filteritems: departamentos,
                        width: 220,
                        align: 'center',
                        hidden: objColumnasOcultas.hdnDepartamento.hidden
                    },
                    {
                        text: '&Aacute;rea',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'area',
                        width: 220,
                        align: 'center',
                        hidden: objColumnasOcultas.hdnArea.hidden
                    },
                    {
                        text: 'Proceso',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'proceso_codigo',
                        width: 180,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnProcesoContratacion.hidden
                    },
                    {
                        text: 'Fuente',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'fin_partida',
                        width: 220,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnFuente.hidden
                    },
                    {
                        text: 'Nivel Salarial',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'nivelsalarial',
                        width: 220,
                        align: 'center',
                        hidden: objColumnasOcultas.hdnNivelSalarial.hidden
                    },
                    {
                        text: 'Cargo',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'cargo',
                        width: 215,
                        align: 'center',
                        hidden: objColumnasOcultas.hdnCargo.hidden
                    },
                    {
                        text: 'Haber',
                        filtertype: 'checkedlist',
                        filteritems: sueldos,
                        datafield: 'sueldo',
                        width: 100,
                        cellsalign: 'right',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnHaber.hidden
                    },
                    {
                        text: 'Fecha Ingreso',
                        datafield: 'fecha_ing',
                        filtertype: 'range',
                        width: 100,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnFechaIng.hidden
                    },
                    {
                        text: 'Fecha Inicio',
                        datafield: 'fecha_ini',
                        filtertype: 'range',
                        width: 100,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnFechaIni.hidden
                    },
                    {
                        text: 'Fecha Incor.',
                        datafield: 'fecha_incor',
                        filtertype: 'range',
                        width: 100,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnFechaIncor.hidden
                    },
                    {
                        text: 'Fecha Fin',
                        datafield: 'fecha_fin',
                        filtertype: 'range',
                        width: 100,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnFechaFin.hidden
                    },
                    {
                        text: 'Fecha Baja',
                        datafield: 'fecha_baja',
                        filtertype: 'range',
                        width: 100,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnFechaBaja.hidden
                    },
                    {
                        text: 'Motivo Baja',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'motivo_baja',
                        align: 'center',
                        cellsalign: 'justify',
                        width: 100,
                        hidden: objColumnasOcultas.hdnMotivoBaja.hidden
                    },
                    {
                        text: 'Nro. Interno',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'interno_inst',
                        align: 'center',
                        width: 100,
                        hidden: objColumnasOcultas.hdnInternoInst.hidden
                    },
                    {
                        text: 'Celular Per.',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'celular_per',
                        align: 'center',
                        width: 100,
                        hidden: objColumnasOcultas.hdnCelularPer.hidden
                    },
                    {
                        text: 'Celular Inst.',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'celular_inst',
                        align: 'center',
                        cellsalign: 'justify',
                        width: 100,
                        hidden: objColumnasOcultas.hdnCelularInst.hidden
                    },
                    {
                        text: 'E-mail Per.',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'e_mail_per',
                        align: 'center',
                        cellsalign: 'justify',
                        width: 100,
                        hidden: objColumnasOcultas.hdnEmailPer.hidden
                    },
                    {
                        text: 'E-mail Inst.',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'e_mail_inst',
                        align: 'center',
                        cellsalign: 'justify',
                        width: 100,
                        hidden: objColumnasOcultas.hdnEmailInst.hidden
                    },
                    {
                        text: 'Fecha Emi CAS',
                        datafield: 'cas_fecha_emi',
                        filtertype: 'range',
                        columngroup: 'AntiguedadCas',
                        width: 100,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnCasFechaEmi.hidden,
                        cellclassname: cellantiguedadcasclass
                    },
                    {
                        text: 'Fecha Pres CAS',
                        datafield: 'cas_fecha_pres',
                        filtertype: 'range',
                        columngroup: 'AntiguedadCas',
                        width: 100,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnCasFechaPres.hidden,
                        cellclassname: cellantiguedadcasclass
                    },
                    {
                        text: 'Fecha Fin Cal',
                        datafield: 'cas_fecha_fin_cal',
                        filtertype: 'range',
                        columngroup: 'AntiguedadCas',
                        width: 100,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: objColumnasOcultas.hdnCasFechaFinCal.hidden,
                        cellclassname: cellantiguedadcasclass
                    },
                    {
                        text: 'Num. CAS',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'cas_numero',
                        columngroup: 'AntiguedadCas',
                        width: 80,
                        align: 'center',
                        hidden: objColumnasOcultas.hdnCasNumero.hidden,
                        cellclassname: cellantiguedadcasclass
                    },
                    {
                        text: 'Cod. Verif. CAS',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'cas_codigo_verificacion',
                        columngroup: 'AntiguedadCas',
                        width: 100,
                        align: 'center',
                        hidden: objColumnasOcultas.hdnCasCodigoVerificacion.hidden,
                        cellclassname: cellantiguedadcasclass
                    },
                    {
                        text: 'Años CAS',
                        filtertype: 'checkedlist',
                        filteritems: antiguedadAnios,
                        datafield: 'cas_anios',
                        columngroup: 'AntiguedadCas',
                        width: 50,
                        align: 'center',
                        cellsalign: 'center',
                        hidden: objColumnasOcultas.hdnCasAnios.hidden,
                        cellclassname: cellantiguedadcasclass
                    },
                    {
                        text: 'Meses CAS',
                        filtertype: 'checkedlist',
                        filteritems: antiguedadMeses,
                        datafield: 'cas_meses',
                        columngroup: 'AntiguedadCas',
                        width: 50,
                        align: 'center',
                        cellsalign: 'center',
                        hidden: objColumnasOcultas.hdnCasMeses.hidden,
                        cellclassname: cellantiguedadcasclass
                    },
                    {
                        text: 'Dias CAS',
                        filtertype: 'checkedlist',
                        filteritems: antiguedadDias,
                        datafield: 'cas_dias',
                        columngroup: 'AntiguedadCas',
                        width: 50,
                        align: 'center',
                        cellsalign: 'center',
                        hidden: objColumnasOcultas.hdnCasDias.hidden,
                        cellclassname: cellantiguedadcasclass
                    },
                    {
                        text: 'Años MT->CAS',
                        filtertype: 'checkedlist',
                        filteritems: antiguedadAnios,
                        datafield: 'mt_anios',
                        columngroup: 'AntiguedadLaboral',
                        width: 50,
                        align: 'center',
                        cellsalign: 'center',
                        hidden: objColumnasOcultas.hdnMtAnios.hidden,
                        cellclassname: cellantiguedadmtclass
                    },
                    {
                        text: 'Meses MT->CAS',
                        filtertype: 'checkedlist',
                        filteritems: antiguedadMeses,
                        datafield: 'mt_meses',
                        columngroup: 'AntiguedadLaboral',
                        width: 50,
                        align: 'center',
                        cellsalign: 'center',
                        hidden: objColumnasOcultas.hdnMtMeses.hidden,
                        cellclassname: cellantiguedadmtclass
                    },
                    {
                        text: 'Dias MT->CAS',
                        filtertype: 'checkedlist',
                        filteritems: antiguedadDias,
                        datafield: 'mt_dias',
                        columngroup: 'AntiguedadLaboral',
                        width: 50,
                        align: 'center',
                        cellsalign: 'center',
                        hidden: objColumnasOcultas.hdnMtDias.hidden,
                        cellclassname: cellantiguedadmtclass
                    },
                    {
                        text: 'Total Años',
                        filtertype: 'checkedlist',
                        filteritems: antiguedadAnios,
                        datafield: 'tot_anios',
                        columngroup: 'AntiguedadLaboralTotal',
                        width: 50,
                        align: 'center',
                        cellsalign: 'center',
                        hidden: objColumnasOcultas.hdnAntiguedadTotalAnios.hidden,
                        cellclassname: cellantiguedadtotalclass
                    },
                    {
                        text: 'Total Meses',
                        filtertype: 'checkedlist',
                        filteritems: antiguedadMeses,
                        datafield: 'tot_meses',
                        columngroup: 'AntiguedadLaboralTotal',
                        width: 50,
                        align: 'center',
                        cellsalign: 'center',
                        hidden: objColumnasOcultas.hdnantiguedadTotalMeses.hidden,
                        cellclassname: cellantiguedadtotalclass
                    },
                    {
                        text: 'Total Dias',
                        filtertype: 'checkedlist',
                        filteritems: antiguedadDias,
                        datafield: 'tot_dias',
                        columngroup: 'AntiguedadLaboralTotal',
                        width: 50,
                        align: 'center',
                        cellsalign: 'center',
                        hidden: objColumnasOcultas.hdnAntiguedadTotalDias.hidden,
                        cellclassname: cellantiguedadtotalclass
                    },
                    {
                        text: 'Años (Fin Mes)',
                        filtertype: 'checkedlist',
                        filteritems: antiguedadAnios,
                        datafield: 'mt_fin_mes_anios',
                        columngroup: 'AntiguedadLaboralFinMes',
                        width: 50,
                        align: 'center',
                        cellsalign: 'center',
                        hidden: objColumnasOcultas.hdnMtFinMesAnios.hidden,
                        cellclassname: cellantiguedadclass
                    },
                    {
                        text: 'Meses (Fin Mes)',
                        filtertype: 'checkedlist',
                        filteritems: antiguedadMeses,
                        datafield: 'mt_fin_mes_meses',
                        columngroup: 'AntiguedadLaboralFinMes',
                        width: 50,
                        align: 'center',
                        cellsalign: 'center',
                        hidden: objColumnasOcultas.hdnMtFinMesMeses.hidden,
                        cellclassname: cellantiguedadclass
                    },
                    {
                        text: 'Dias (Fin Mes)',
                        filtertype: 'checkedlist',
                        filteritems: antiguedadDias,
                        datafield: 'mt_fin_mes_dias',
                        columngroup: 'AntiguedadLaboralFinMes',
                        width: 50,
                        align: 'center',
                        cellsalign: 'center',
                        hidden: objColumnasOcultas.hdnMtFinMesDias.hidden,
                        cellclassname: cellantiguedadclass
                    },
                    {
                        text: 'Observacion',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'observacion',
                        width: 100,
                        hidden: objColumnasOcultas.hdnObservacion.hidden
                    },
                ],
                columngroups:
                    [
                        {text: 'Antiguedad CAS', align: 'center', name: 'AntiguedadCas'},
                        {text: 'Antiguedad MT->CAS', align: 'center', name: 'AntiguedadLaboral'},
                        {text: 'Total Antiguedad Actual', align: 'center', name: 'AntiguedadLaboralTotal'},
                        {text: 'Antiguedad Hasta Fin de Mes', align: 'center', name: 'AntiguedadLaboralFinMes'},
                    ]
            });
    }
}

var rownumberrenderer = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
    var nro = row + 1;
    return "<div align='center'>" + nro + "</div>";
}

/*
 * Función para controlar la ejecución del evento esc con el teclado.
 */
function OperaEvento(evento) {
    if ((evento.type == "keyup" || evento.type == "keydown") && evento.which == "27") {
        $('#jqxTabs').jqxTabs('enableAt', 0);
        $('#jqxTabs').jqxTabs('disableAt', 1);
        $('#jqxTabs').jqxTabs('disableAt', 2);
        $('#jqxTabs').jqxTabs('disableAt', 3);
        $('#jqxTabs').jqxTabs('disableAt', 4);
        $('#jqxTabs').jqxTabs('disableAt', 5);
        /**
         * Saltamos a la pantalla principal en caso de presionarse ESC
         */
        $('#jqxTabs').jqxTabs({selectedItem: 0});

        $("#popupWindowCargo").jqxWindow('close');
        $("#popupWindowNuevaMovilidad").jqxWindow('close');
        $("#lstTipoMemorandum").off();
        $('#jqxgrid').jqxGrid('focus');
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
 * Función para la obtención del listado de filtrables.
 * @param gestion
 */
function obtenerFiltrables(gestion) {
    var resultado = null;
    $.ajax({
        url: '/relaborales/getfilters/',
        type: "POST",
        datatype: 'json',
        async: false,
        cache: false,
        data: {gestion: gestion},
        success: function (data) {
            resultado = jQuery.parseJSON(data);
        }, //mostramos el error
        error: function () {
            alert('Se ha producido un error Inesperado');
        }
    });
    return resultado;
}

/**
 *
 * Función para la obtención de la ruta en la cual reside la fotografía correspondiente de la persona.
 * @param numDocumento Número de documento, comunmente el número de CI.
 * @param numComplemento Número de complemento.
 * @returns {string} Ruta de ubicación de la fotografía a mostrarse.
 */
function obtenerRutaFoto(numDocumento, numComplemento) {
    var resultado = "/images/perfil-profesional.jpg";
    if (numDocumento != "") {
        $.ajax({
            url: '/relaborales/obtenerrutafoto/',
            type: "POST",
            datatype: 'json',
            async: false,
            cache: false,
            data: {ci: numDocumento, num_complemento: numComplemento},
            success: function (data) {
                var res = jQuery.parseJSON(data);
                if (res.result == 1) {
                    resultado = res.ruta;
                }
            }, //mostramos el error
            error: function () {
                alert('Se ha producido un error Inesperado');
            }
        });
    }
    return resultado;
}

/**
 * Función para obtener la fecha de este día
 * @param separador
 * @returns {*}
 * @author JLM
 */
function fechaHoy(separador, format) {
    if (separador == '') separador = "-";
    var fullDate = new Date()
    var dia = fullDate.getDate().toString();
    var mes = (fullDate.getMonth() + 1).toString();
    var twoDigitDay = (dia.length === 1 ) ? '0' + dia : dia;
    var twoDigitMonth = (mes.length === 1 ) ? '0' + mes : mes;
    if (format == "dd-mm-yyyy")
        var currentDate = twoDigitDay + separador + twoDigitMonth + separador + fullDate.getFullYear();
    else if (format == "mm-dd-yyyy") {
        var currentDate = twoDigitMonth + separador + twoDigitDay + separador + fullDate.getFullYear();
    } else {
        var currentDate = fullDate;
    }
    return currentDate;
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

var cellantiguedadcasclass = function (row, columnfield, value, data) {
        return 'celeste';
};
var cellantiguedadmtclass = function (row, columnfield, value, data) {
    return 'azul';
};

var cellantiguedadtotalclass = function (row, columnfield, value, data) {
    return 'verde';
};
var cellantiguedadclass = function (row, columnfield, value, data) {
    if((data.antiguedad_fin_mes_anios == 2 && data.antiguedad_anios == 1)
        || (data.antiguedad_fin_mes_anios == 5 && data.antiguedad_anios == 4)
        || (data.antiguedad_fin_mes_anios == 8 && data.antiguedad_anios == 7)
        || (data.antiguedad_fin_mes_anios == 11 && data.antiguedad_anios == 10)
        || (data.antiguedad_fin_mes_anios == 15 && data.antiguedad_anios == 14)
        || (data.antiguedad_fin_mes_anios == 20 && data.antiguedad_anios == 19)
        || (data.antiguedad_fin_mes_anios == 25 && data.antiguedad_anios == 24)
    ){
        return 'rojo';
    }
    else return 'plomo'
};

function evalua(gestion) {
    if (gestion > 0) return true;
    else return false;
};

/**
 * Función para la habilitación de los campos correspondientes en el formulario de registro de una nueva relación laboral.
 * @param idOrganigrama Identificador del organigrama.
 * @param idFinPartida Identificador del financiamiento por partida.
 */
function habilitarCamposParaNuevoRegistroDeRelacionLaboral() {
    defineFechas();
}

/**
 * Función para la definición de las fechas para el registro de la relación laboral.
 */
function defineFechas() {
    $("#FechaIni").jqxDateTimeInput({enableBrowserBoundsDetection: true, height: 24, formatString: 'dd-MM-yyyy'});
    $("#FechaIncor").jqxDateTimeInput({enableBrowserBoundsDetection: true, height: 24, formatString: 'dd-MM-yyyy'});
}