$(document).ready(function () {

    $('#codigo_nivel').change('select', function (event) {
        $.ajax({
            url: '/cargos/getSueldo/',
            type: 'POST',
            datatype: 'json',
            data: {id: $('#codigo_nivel').val()},
            success: function (data) {
                var obj = jQuery.parseJSON(data);
                $("#sueldotxt").text(obj.sueldo);
                $("#nivel").val(obj.nivel);
            },
            error: function () {
                alert('Se ha producido un error Inesperado');
            }
        });
    });
    /**
     * Se inicializa la gestión por defecto del sistema
     * @type {*|{get, set, inputmaskpatch}|jQuery}
     */
    var objColumnasOcultas = {
        "hdnResolucionOrganigrama": {"title": "ResolucionOrganigrama", "selectable": false, "hidden": false},
        "hdnResolucionEscala": {"title": "ResolucionEscala", "selectable": true, "hidden": true},
        "hdnUnidadOrganizacional": {"title": "UnidadOrganizacional", "selectable": false, "hidden": false},
        "hdnDenominacion": {"title": "Denominacion", "selectable": false, "hidden": false},
        "hdnOrdenador": {"title": "Ordenador", "selectable": true, "hidden": true},
        "hdnCargo": {"title": "Cargo", "selectable": false, "hidden": false},
        "hdnSalario": {"title": "Salario", "selectable": false, "hidden": false},
        "hdnItem": {"title": "Item", "selectable": false, "hidden": false},
        "hdnEstado": {"title": "Estado", "selectable": true, "hidden": true},
        "hdnCondicion": {"title": "Condicion", "selectable": false, "hidden": false},
        "hdnPartida": {"title": "Partida", "selectable": false, "hidden": false},
        "hdnCodFuente": {"title": "Cod. Fuente", "selectable": true, "hidden": true},
        "hdnCodOrganismo": {"title": "Cod Organismo", "selectable": true, "hidden": true},
        "hdnGestion": {"title": "Gestión", "selectable": false, "hidden": false},
    };
    defineColumnasOcultas("divGrillaCargos", "divComboColumnasVisibles", "chkAllColumnasVisibles", objColumnasOcultas);
    cargarGrillaCargos(objColumnasOcultas);
    cargarGrillaPac();

    $("#resolucion_ministerial_id").change(function () {
        select_organigrama();
        $("#depende_id").html("");
        //select_fuentefinanciamiento();
    });

    $("#organigrama_id").change(function () {
        select_dependencia($("#organigrama_id").val(), 0, $("#gestion_fp").val());
    });
    $("#id_resolucion_escala").on("change", function () {
        select_escala($(this).val());
    });
    $("#fin_partida_id").change(function () {
        var v = $.ajax({
            url: '/cargos/getGestion/',
            type: 'POST',
            datatype: 'json',
            data: {fin_partida_id: $(this).val()},
            success: function (data) {
                var obj = jQuery.parseJSON(data);
                $("#gestion_fp").val(obj.gestion);
                select_dependencia($("#organigrama_id").val(), 0, obj.gestion);
            },
            error: function () {
                alert('Se ha producido un error Inesperado');
            }
        });
    });
    select_organigrama();
    select_finpartidas(0);
    $('#testForm').validate({
        rules: {
            organigrama_id: {
                required: true,
            },
            codigo_nivel: {
                required: true,
            },
            // codigo: {
            //  required: true,
            // },
            cargo: {
                required: true
            },
            fin_partida_id: {
                required: false,
            },

        },
        highlight: function (element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
        },
        success: function (element) {
            element.addClass('valid').closest('.control-group').removeClass('error').addClass('success');
        },
        submitHandler: function (form) {
            var asistente = $('input:radio[name=asistente]:checked').val();
            var jefe = $('input:radio[name=jefe]:checked').val();
            var v = $.ajax({
                url: '/cargos/save/',
                type: 'POST',
                datatype: 'json',
                data: {
                    id: $("#id").val(),
                    resolucion_ministerial_id: $('#resolucion_ministerial_id').val(),
                    organigrama_id: $('#organigrama_id').val(),
                    fin_partida_id: $('#fin_partida_id').val(),
                    depende_id: $('#depende_id').val(),
                    cargo: $("#cargo").val(),
                    nivel: $("#nivel").val(),
                    codigo_nivel: $("#codigo_nivel").val(),
                    codigo: $('#codigo').val(),
                    ordenador: $('#ordenador').val(),
                    formacion_requerida: $("#formacion_requerida").val(),
                    asistente: asistente,
                    jefe: jefe,
                    gestion_fp: $("#gestion_fp").val()
                },
                success: function (data) {
                    $("#divGrillaCargos").jqxGrid('updatebounddata', 'cells');
                    $("#divMsjeExito").show();
                    $("#divMsjeExito").addClass('alert alert-sucess alert-dismissable');
                    $("#aMsjeExito").html(data);
                },
                error: function () {
                    alert('Se ha producido un error Inesperado');
                }
            });
            $('#myModal').modal('hide');
            return false; // ajax used, block the normal submit
        }
    });
    //$("[name='organigrama_id']").css("position", "absolute").css("z-index","-9999").chosen().show();


    $('#testForm_pac').validate({
        rules: {
            gestion_pac: {
                required: true
            }
        },
        highlight: function (element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
        },
        success: function (element) {
            //element.text('OK!').addClass('valid').closest('.control-group').removeClass('error').addClass('success');
            element.addClass('valid').closest('.control-group').removeClass('error').addClass('success');
        },
        submitHandler: function (form) {
            var fecha_ini = $('#fecha_ini_pac').val();
            var fecha_fin = $('#fecha_fin_pac').val();
            $.ajax({
                url: '/cargos/save_pac/',
                type: 'POST',
                datatype: 'json',
                data: {
                    cargo_id_pac: $("#cargo_id_pac").val(),
                    gestion: $('#gestion_pac').val(),
                    fecha_ini: fecha_ini,
                    fecha_fin: fecha_fin
                },
                success: function (data) {
                    $("#jqxgrid2").jqxGrid('updatebounddata', 'cells');
                    $("#divMsjeExito").show();
                    $("#divMsjeExito").addClass('alert alert-sucess alert-dismissable');
                    $("#aMsjeExito").html(data);

                },
                error: function () {
                    alert('Se ha producido un error Inesperado');
                }
            });
            $('#myModal_pac').modal('hide');
            return false; // ajax used, block the normal submit
        }
    });
    $("#rep_pdf_pac").click(function () {
        exportarPacReporte(2);
    });
});

/**
 * Función para la carga de la grilla de cargos.
 */
function cargarGrillaCargos(objColumnasOcultas) {
    var source =
        {
            datatype: "json",
            datafields: [
                {name: 'id', type: 'number'},
                {name: 'organigrama_resolucion_ministerial_id', type: 'number'},
                {name: 'organigrama_tipo_resolucion', type: 'string'},
                {name: 'escala_resolucion_ministerial_id', type: 'number'},
                {name: 'escala_tipo_resolucion', type: 'string'},
                {name: 'unidad_administrativa', type: 'string'},
                {name: 'organigrama_id', type: 'string'},
                {name: 'depende_id', type: 'number'},
                {name: 'codigo_nivel', type: 'string'},
                {name: 'nivelsalarial_id', type: 'string'},
                {name: 'denominacion', type: 'string'},
                {name: 'codigo', type: 'string'},
                {name: 'ordenador', type: 'number'},
                {name: 'cargo', type: 'string'},
                {name: 'sueldo'},
                {name: 'fin_partida_id'},
                {name: 'estado', type: 'string'},
                {name: 'condicion', type: 'string'},
                {name: 'partida_denominacion', type: 'string'},
                {name: 'partida', type: 'number'},
                {name: 'fuente_codigo', type: 'number'},
                {name: 'fuente', type: 'string'},
                {name: 'organismo_codigo', type: 'number'},
                {name: 'organismo', type: 'string'},
                {name: 'asistente', type: 'number'},
                {name: 'jefe', type: 'number'},
                {name: 'gestion', type: 'number'}
            ],
            url: '/cargos/list/',
            async: false,
            cache: false,
            root: 'Rows',
            beforeprocessing: function (data) {
                source.totalrecords = data[0].TotalRows;
            },
            filter: function () {
                // Actualiza la grilla y reenvia los datos actuales al servidor
                $("#divGrillaCargos").jqxGrid('updatebounddata', 'filter');
            },
            sort: function () {
                // Actualiza la grilla y reenvia los datos actuales al servidor
                $("#divGrillaCargos").jqxGrid('updatebounddata', 'sort');
            }

        };
    var dataAdapter = new $.jqx.dataAdapter(source);

    $("#divGrillaCargos").jqxGrid('applyfilters');

    var firstNameColumnFilter = function () {
        //alert(tipo_resolucion);
        var filtergroup = new $.jqx.filter();
        var filter_or_operator = 1;
        var filtervalue = $("#organigrama_tipo_resolucion").val();
        var filtercondition = 'equal';
        var filter = filtergroup.createfilter('stringfilter', filtervalue, filtercondition);
        filtergroup.addfilter(filter_or_operator, filter);
        return filtergroup;
    }();
    /**
     * Se modifica debido a que ya no se particiona por año.
     */
    var firstNameColumnFilterGestion = function () {
        //alert(tipo_resolucion);
        var filtergroup = new $.jqx.filter();
        var filter_or_operator = 1;
        var filtervalue = $("#gestion").val();
        var filtercondition = 'equal';
        var filter = filtergroup.createfilter('numericfilter', filtervalue, filtercondition);
        filtergroup.addfilter(filter_or_operator, filter);
        return filtergroup;
    }();

    var filtrables = obtenerFiltrables();
    var resoluciones = filtrables.resoluciones;
    var organigramas = filtrables.organigramas;
    var gestiones = filtrables.gestiones;
    var condiciones = filtrables.condiciones;

    $("#divGrillaCargos").jqxGrid({
        width: '100%',
        source: dataAdapter,
        sortable: true,
        altRows: true,
        columnsresize: true,
        pageable: true,
        pagerMode: 'advanced',
        showfilterrow: true,
        filterable: true,
        autorowheight: true,
        autoshowfiltericon: false,
        virtualmode: true,
        showtoolbar: true,
        rendergridrows: function (obj) {
            return obj.data;
        },
        enablebrowserselection: true,
        rendertoolbar: function (toolbar) {
            var me = this;
            var container = $("<div></div>");
            toolbar.append(container);
            container.append("<button title='Nuevo registro' id='addnewrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-plus-square fa-2x text-primary' title='Nuevo Registro'/></i></button>");
            container.append("<button title='Modificar registro' id='updaterowbutton'  class='btn btn-sm btn-primary' type='button' ><i class='fa fa-pencil-square fa-2x text-info' title='Modificar registro'/></button>");
            container.append("<button title='Dar de baja al registro' id='deleterowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-minus-square fa-2x text-danger' title='Dar de baja al registro'/></i></button>");
            container.append("<button title='Asignar PAC' id='assignpacrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-bookmark fa-2x text-warning' title='Asignar PAC-P'/></i></button>");
            container.append("<button title='Copiar cargo' id='copyrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-files-o fa-2x text-info' title='Copiar cargo'/></i></button>");
            container.append("<button title='Exportar a Excel.' id='exportexcelrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fi fi-xls fa-2x text-success' title='Exportar a Excel.'/></i></button>");
            container.append("<button title='Exportar a PDF.' id='exportpdfrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fi fi-pdf fa-2x text-danger' title='Exportar a PDF.'/></i></button>");
            container.append("<button title='Refrescar Grilla' id='refreshbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-repeat fa-2x text-default' title='Refrescar grilla'/></i></button>");
            container.append("<button title='Desagrupar' id='cleargroupsrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-chain-broken fa-2x text-default' title='Desagrupar'/></i></button>");
            container.append("<button title='Desfiltrar' id='clearfiltersrowbutton' class='btn btn-sm btn-primary' type='button'><i class='gi gi-sorting fa-2x text-default' title='Desfiltrar'/></i></button>");

            $("#addnewrowbutton").jqxButton();
            $("#updaterowbutton").jqxButton();
            $("#deleterowbutton").jqxButton();
            $("#assignpacrowbutton").jqxButton();
            $("#copyrowbutton").jqxButton();
            $("#exportexcelrowbutton").jqxButton();
            $("#exportpdfrowbutton").jqxButton();
            $("#refreshbutton").jqxButton();
            $("#cleargroupsrowbutton").jqxButton();
            $("#clearfiltersrowbutton").jqxButton();

            $("#addnewrowbutton").off();
            $("#addnewrowbutton").on('click', function () {
                $("#titulo").text("Adicionar");
                $("#id").val("");
                $("#codigo").val("");
                $("#ordenador").val("");
                $("#organigrama_id").val("");
                $("#depende_id").val("");
                $("#fin_partida_id").val("");
                $("#codigo_nivel").val("");
                $("#cargo").val("");
                $("#formacion_requerida").val("");
                $("#sueldotxt").text("");
                $('#myModal').off();
                $('#myModal').modal('show');
                $('#myModal').on('shown.bs.modal', function () {
                    $("#resolucion_ministerial_id").focus();
                    $("#id_resolucion_escala").off();
                    $("#id_resolucion_escala").on("change", function () {
                        console.log("cambio de resolución de escala por registro");
                        select_escala($(this).val(), 0);
                    });
                });
            });
            $("#updaterowbutton").off();
            $("#updaterowbutton").on("click",function(){
                var selectedrowindex = $("#divGrillaCargos").jqxGrid('getselectedrowindex');
                if (selectedrowindex >= 0) {
                    var dataRecord = $("#divGrillaCargos").jqxGrid('getrowdata', selectedrowindex);
                    if (dataRecord != undefined) {
                        $("#titulo").text("Editar");

                        $("#cargo_id_pac").val(dataRecord.id);
                        $("#estado_cargo").val(dataRecord.estado);
                        $("#formacion_requerida").text(dataRecord.formacion_requerida);

                        $("#asistente0").prop("checked", true);
                        if (dataRecord.asistente == 1) {
                            $("#asistente1").prop("checked", true);
                        }

                        $("#jefe0").prop("checked", true);
                        if (dataRecord.jefe == 1) {
                            $("#jefe1").prop("checked", true);
                        }

                        $("#id").val(dataRecord.id);
                        $("#gestion_fp").val(dataRecord.gestion);
                        select_finpartidas(dataRecord.fin_partida_id);
                        select_organigrama(dataRecord.organigrama_id);
                        select_dependencia(dataRecord.organigrama_id, dataRecord.depende_id, dataRecord.gestion);
                        select_resoluciones_escala(dataRecord.escala_resolucion_ministerial_id);
                        select_escala(dataRecord.escala_resolucion_ministerial_id, dataRecord.nivelsalarial_id);
                        $("#depende_id").val(dataRecord.depende_id);
                        $("#codigo_nivel").val(dataRecord.nivelsalarial_id);
                        $("#nivel").val(dataRecord.codigo_nivel);
                        $("#codigo").val(dataRecord.codigo);
                        $("#ordenador").val(dataRecord.ordenador);
                        $("#cargo").val(dataRecord.cargo);
                        $("#formacion_requerida").val(dataRecord.formacion_requerida);
                        $("#sueldotxt").text(dataRecord.sueldo);
                        $('#myModal').off();
                        $('#myModal').modal('show');
                        $('#myModal').on('shown.bs.modal', function () {
                            $("#fin_partida_id").focus();
                            console.log("rm org: " + dataRecord.resolucion_ministerial_id);
                            $("#resolucion_ministerial_id").val(dataRecord.resolucion_ministerial_id);
                            $("#id_resolucion_escala").off();
                            $("#id_resolucion_escala").on("change", function () {
                                console.log("cambio de resolución de escala por edicion -> " + $(this).val());
                                select_escala($(this).val(), 0);
                            });
                        });
                    }else{
                        var msje = "Error al seleccionar el cargo.";
                        $("#divMsjePorWarning").html("");
                        $("#divMsjePorWarning").append(msje);
                        $("#divMsjeNotificacionWarning").jqxNotification("open");
                    }
                } else {
                    var msje = "Debe seleccionar un registro necesariamente.";
                    $("#divMsjePorError").html("");
                    $("#divMsjePorError").append(msje);
                    $("#divMsjeNotificacionError").jqxNotification("open");
                }
            });
            $("#deleterowbutton").off();
            $("#deleterowbutton").on('click', function () {
                var selectedrowindex = $("#divGrillaCargos").jqxGrid('getselectedrowindex');
                if (selectedrowindex >= 0) {
                    var dataRecord = $("#divGrillaCargos").jqxGrid('getrowdata', selectedrowindex);
                    if (dataRecord != undefined) {
                        bootbox.confirm("<strong>¡Mensaje!</strong> Esta seguro de eliminar el registro.", function (result) {
                            if (result === true) {
                                var v = $.ajax({
                                    url: '/cargos/delete/',
                                    type: 'POST',
                                    datatype: 'json',
                                    data: {id: dataRecord.id},
                                    success: function (data) {
                                        $("#divGrillaCargos").jqxGrid('updatebounddata', 'cells');
                                        $("#divMsjeExito").show();
                                        $("#divMsjeExito").addClass('alert alert-warning alert-dismissable');
                                        $("#aMsjeExito").html(data);
                                    }, //mostramos el error
                                    error: function () {
                                        alert('Se ha producido un error Inesperado');
                                    }
                                });
                            }
                        });
                    }else{
                        var msje = "Error al seleccionar el cargo.";
                        $("#divMsjePorWarning").html("");
                        $("#divMsjePorWarning").append(msje);
                        $("#divMsjeNotificacionWarning").jqxNotification("open");
                    }
                } else {
                    var msje = "Debe seleccionar un registro necesariamente.";
                    $("#divMsjePorError").html("");
                    $("#divMsjePorError").append(msje);
                    $("#divMsjeNotificacionError").jqxNotification("open");
                }
            });
            $("#assignpacrowbutton").off();
            $("#assignpacrowbutton").on("click",function(){
                var selectedrowindex = $("#divGrillaCargos").jqxGrid('getselectedrowindex');
                if (selectedrowindex >= 0) {
                    var dataRecord = $("#divGrillaCargos").jqxGrid('getrowdata', selectedrowindex);
                    if (dataRecord != undefined) {
                        $("#cargo_id_pac").val(dataRecord.id);
                        $("#estado_cargo").val(dataRecord.estado);
                        $("#organigrama_pac").text(dataRecord.unidad_administrativa);
                        $("#item_pac").text(dataRecord.codigo);
                        $("#cargo_pac").text(dataRecord.cargo);
                        $("#sueldo_pac").text(dataRecord.sueldo + " Bs.");

                        if (dataRecord.estado == 'ADJUDICADO') {
                            bootbox.alert("<strong>¡Mensaje!</strong> El cargo ya esta ADJUDICADO.");
                        } else {
                            var v = $.ajax({
                                url: '/cargos/getEstadoSeguimiento/',
                                type: 'POST',
                                datatype: 'json',
                                data: {cargo_id: dataRecord.id},
                                success: function (data) {
                                    if (data == 1 || data == 0) {
                                        bootbox.alert("<strong>¡Mensaje!</strong> El cargo ya esta en proceso o esta en el PACP.");
                                    } else {
                                        $('#myModal_pac').modal('show');
                                    }

                                },
                                error: function () {
                                    alert('Se ha producido un error Inesperado');
                                }
                            });


                        }
                    }else{
                        var msje = "Error al seleccionar el cargo.";
                        $("#divMsjePorWarning").html("");
                        $("#divMsjePorWarning").append(msje);
                        $("#divMsjeNotificacionWarning").jqxNotification("open");
                    }
                } else {
                    var msje = "Debe seleccionar un registro necesariamente.";
                    $("#divMsjePorError").html("");
                    $("#divMsjePorError").append(msje);
                    $("#divMsjeNotificacionError").jqxNotification("open");
                }
            });
            $("#copyrowbutton").off();
            $("#copyrowbutton").on("click",function(){
                var selectedrowindex = $("#divGrillaCargos").jqxGrid('getselectedrowindex');
                if (selectedrowindex >= 0) {
                    var dataRecord = $("#divGrillaCargos").jqxGrid('getrowdata', selectedrowindex);
                    if (dataRecord != undefined) {
                        $("#titulo").text("Copiar Cargo");
                        $("#cargo_id_pac").val(dataRecord.id);
                        $("#estado_cargo").val(dataRecord.estado);
                        $("#formacion_requerida").text(dataRecord.formacion_requerida);

                        $("#asistente0").prop("checked", true);
                        if (dataRecord.asistente == 1) {
                            $("#asistente1").prop("checked", true);
                        }
                        $("#jefe0").prop("checked", true);
                        if (dataRecord.jefe == 1) {
                            $("#jefe1").prop("checked", true);
                        }
                        $("#id").val("");
                        $("#resolucion_ministerial_id").val(dataRecord.resolucion_ministerial_id);
                        $("#gestion_fp").val(dataRecord.gestion);
                        select_finpartidas(dataRecord.fin_partida_id);
                        select_organigrama(dataRecord.organigrama_id);
                        select_resoluciones_escala(dataRecord.escala_resolucion_ministerial_id);
                        select_dependencia(dataRecord.organigrama_id, dataRecord.depende_id, dataRecord.gestion);
                        select_escala(dataRecord.escala_resolucion_ministerial_id, dataRecord.nivelsalarial_id);
                        $("#depende_id").val(dataRecord.depende_id);
                        $("#codigo_nivel").val(dataRecord.nivelsalarial_id);
                        $("#nivel").val(dataRecord.codigo_nivel);
                        $("#codigo").val(dataRecord.codigo);
                        $("#ordenador").val(dataRecord.ordenador);
                        $("#cargo").val(dataRecord.cargo);
                        $("#formacion_requerida").val(dataRecord.formacion_requerida);
                        $("#sueldotxt").text(dataRecord.sueldo);
                        $('#myModal').modal('show');
                    }else{
                        var msje = "Error al seleccionar el cargo.";
                        $("#divMsjePorWarning").html("");
                        $("#divMsjePorWarning").append(msje);
                        $("#divMsjeNotificacionWarning").jqxNotification("open");
                    }
                } else {
                    var msje = "Debe seleccionar un registro necesariamente.";
                    $("#divMsjePorError").html("");
                    $("#divMsjePorError").append(msje);
                    $("#divMsjeNotificacionError").jqxNotification("open");
                }
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
            /**
             * Refrescar la grilla
             */
            $("#refreshbutton").on('click', function () {
                $("#divGrillaCargos").jqxGrid("updatebounddata");
            });
            /**
             * Desagrupar
             */
            $("#cleargroupsrowbutton").off();
            $("#cleargroupsrowbutton").on('click', function () {
                $("#divGrillaCargos").jqxGrid('cleargroups');
            });
            /**
             * Desfiltrar
             */
            $("#clearfiltersrowbutton").off();
            $("#clearfiltersrowbutton").on('click', function () {
                $("#divGrillaCargos").jqxGrid('clearfilters');
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
                text: 'Resolución (Organigrama)',
                datafield: 'organigrama_tipo_resolucion',
                filtertype: 'checkedlist',
                align: 'center',
                filteritems: resoluciones,
                filter: firstNameColumnFilter,
                hidden: objColumnasOcultas.hdnResolucionOrganigrama.hidden,
                width: '10%'
            },
            {
                text: 'Resolución (Escala)',
                datafield: 'escala_tipo_resolucion',
                filtertype: 'checkedlist',
                align: 'center',
                filteritems: resoluciones,
                hidden: objColumnasOcultas.hdnResolucionEscala.hidden,
                width: '10%'
            },
            {
                text: 'Unidad Organizacional',
                datafield: 'unidad_administrativa',
                filtertype: 'checkedlist',
                align: 'center',
                filteritems: organigramas,
                hidden: objColumnasOcultas.hdnUnidadOrganizacional.hidden,
                width: '10%'
            },
            {
                text: 'Denominacion',
                datafield: 'denominacion',
                filtertype: 'input',
                align: 'center',
                hidden: objColumnasOcultas.hdnDenominacion.hidden,
                width: '15%'
            },
            {
                text: 'Ordenador',
                datafield: 'ordenador',
                filtertype: 'number',
                align: 'center',
                cellsalign: 'center',
                hidden: objColumnasOcultas.hdnOrdenador.hidden,
                width: '4%'
            },
            {
                text: 'Cargo',
                datafield: 'cargo',
                filtertype: 'input',
                align: 'center',
                hidden: objColumnasOcultas.hdnCargo.hidden,
                width: '12%'
            },
            {
                text: 'Salario Mensual',
                datafield: 'sueldo',
                filtertype: 'input',
                width: '5%',
                cellsformat: 'c2',
                cellsalign: 'right',
                hidden: objColumnasOcultas.hdnSalario.hidden,
                align: 'center'
            },
            {
                text: 'Item',
                datafield: 'codigo',
                filtertype: 'input',
                align: 'center',
                cellsalign: 'center',
                hidden: objColumnasOcultas.hdnItem.hidden,
                width: '4%'
            },
            {
                text: 'Estado',
                datafield: 'estado',
                filtertype: 'input',
                align: 'center',
                hidden: objColumnasOcultas.hdnEstado.hidden,
                width: '7%'
            },
            {
                text: 'Condici&oacute;n',
                datafield: 'condicion',
                filtertype: 'checkedlist',
                filteritems: condiciones,
                hidden: objColumnasOcultas.hdnCondicion.hidden,
                align: 'center',
                width: '10%'
            },
            {
                text: 'Partida',
                datafield: 'partida',
                filtertype: 'input',
                align: 'center',
                cellsalign: 'center',
                hidden: objColumnasOcultas.hdnPartida.hidden,
                width: '4%'
            },
            {
                text: 'Fnt',
                datafield: 'fuente_codigo',
                filtertype: 'number',
                align: 'center',
                cellsalign: 'center',
                hidden: objColumnasOcultas.hdnCodFuente.hidden,
                width: '3%'
            },
            {
                text: 'Org',
                datafield: 'organismo_codigo',
                filtertype: 'number',
                align: 'center',
                cellsalign: 'center',
                hidden: objColumnasOcultas.hdnCodOrganismo.hidden,
                width: '3%'
            },
            {
                text: 'Gestión',
                datafield: 'gestion',
                filtertype: 'checkedlist',
                width: '3%',
                align: 'center',
                cellsalign: 'center',
                filteritems: gestiones,
                //filter: firstNameColumnFilterGestion,
                hidden: objColumnasOcultas.hdnGestion.hidden
            }
        ]
    });
    $("#add_pac").click(function () {

        var rowindex = $("#divGrillaCargos").jqxGrid('getselectedrowindex');
        if (rowindex > -1) {
            var dataRecord = $("#divGrillaCargos").jqxGrid('getrowdata', rowindex);
            $("#cargo_id_pac").val(dataRecord.id);
            $("#estado_cargo").val(dataRecord.estado);
            $("#organigrama_pac").text(dataRecord.unidad_administrativa);
            $("#item_pac").text(dataRecord.codigo);
            $("#cargo_pac").text(dataRecord.cargo);
            $("#sueldo_pac").text(dataRecord.sueldo + " Bs.");

            if (dataRecord.estado == 'ADJUDICADO') {
                bootbox.alert("<strong>¡Mensaje!</strong> El cargo ya esta ADJUDICADO.");
            } else {
                var v = $.ajax({
                    url: '/cargos/getEstadoSeguimiento/',
                    type: 'POST',
                    datatype: 'json',
                    data: {cargo_id: dataRecord.id},
                    success: function (data) {
                        if (data == 1 || data == 0) {
                            bootbox.alert("<strong>¡Mensaje!</strong> El cargo ya esta en proceso o esta en el PACP.");
                        } else {
                            $('#myModal_pac').modal('show');
                        }

                    },
                    error: function () {
                        alert('Se ha producido un error Inesperado');
                    }
                });


            }


        }
        else {
            bootbox.alert("<strong>¡Mensaje!</strong> Para asignar PACP debe seleccionar un registro.");
        }
    });
}

/**
 * Función para la carga de la grilla correspondiente al PAC.
 */
function cargarGrillaPac() {
    var source2 =
        {
            datatype: "json",
            datafields: [
                {name: 'nro', type: 'number'},
                {name: 'id', type: 'number'},
                {name: 'organigrama_tipo_resolucion', type: 'string'},
                {name: 'escala_tipo_resolucion', type: 'string'},
                {name: 'unidad_administrativa', type: 'string'},
                {name: 'codigo', type: 'string'},
                {name: 'cargo', type: 'string'},
                {name: 'estado', type: 'string'},
                {name: 'gestion', type: 'string'},
                {name: 'fecha_ini', type: 'date'},
                {name: 'fecha_fin', type: 'date'}
            ],
            url: '/cargos/listpacpaged/',
            async: false,
            cache: false,
            root: 'Rows',
            beforeprocessing: function (data) {
                source2.totalrecords = data[0].TotalRows;
            },
            filter: function () {
                $("#jqxgrid2").jqxGrid('updatebounddata', 'filter');
            },
            sort: function () {
                $("#jqxgrid2").jqxGrid('updatebounddata', 'sort');
            }

        };
    var dataAdapter2 = new $.jqx.dataAdapter(source2);
    $("#jqxgrid2").jqxGrid({
        width: '100%',
        source: dataAdapter2,
        sortable: true,
        altRows: true,
        columnsresize: true,
        pageable: true,
        pagerMode: 'advanced',
        showfilterrow: true,
        filterable: true,
        autorowheight: true,
        autoshowfiltericon: false,
        virtualmode: true,
        showtoolbar: true,
        rendergridrows: function (obj) {
            return obj.data;
        },
        enablebrowserselection: true,
        rendertoolbar: function (toolbar) {
            var me = this;
            var container = $("<div></div>");
            toolbar.append(container);
            container.append("<button title='Dar de baja al registro' id='deletepacrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-minus-square fa-2x text-danger' title='Dar de baja al registro'/></i></button>");
            container.append("<button title='Exportar a Excel.' id='exportexcelpacprowbutton' class='btn btn-sm btn-primary' type='button'><i class='fi fi-xls fa-2x text-success' title='Exportar a Excel.'/></i></button>");
            container.append("<button title='Exportar a PDF.' id='exportpdpacpfrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fi fi-pdf fa-2x text-danger' title='Exportar a PDF.'/></i></button>");
            container.append("<button title='Refrescar Grilla' id='refreshpacbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-repeat fa-2x text-default' title='Refrescar grilla.'/></i></button>");
            container.append("<button title='Desagrupar' id='cleargroupspacrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-chain-broken fa-2x text-default' title='Desagrupar.'/></i></button>");
            container.append("<button title='Desfiltrar' id='clearfilterspacrowbutton' class='btn btn-sm btn-primary' type='button'><i class='gi gi-sorting fa-2x text-default' title='Desfiltrar.'/></i></button>");

            $("#deletepacrowbutton").jqxButton();
            $("#exportpdpacpfrowbutton").jqxButton();
            $("#exportexcelpacprowbutton").jqxButton();
            $("#refreshpacbutton").jqxButton();
            $("#cleargroupspacrowbutton").jqxButton();
            $("#clearfilterspacrowbutton").jqxButton();

            $("#deletepacrowbutton").off();
            $("#deletepacrowbutton").on('click', function () {
                var selectedrowindex = $("#jqxgrid2").jqxGrid('getselectedrowindex');
                if (selectedrowindex >= 0) {
                    var dataRecord = $('#jqxgrid2').jqxGrid('getrowdata', selectedrowindex);
                    if (dataRecord != undefined) {
                        bootbox.confirm("<strong>¡Mensaje!</strong> Esta seguro de eliminar el registro.", function (result) {
                            if (result == true) {
                                var v = $.ajax({
                                    url: '/cargos/delete_pac/',
                                    type: 'POST',
                                    datatype: 'json',
                                    data: {id: dataRecord.id},
                                    success: function (data) {
                                        $("#jqxgrid2").jqxGrid('updatebounddata', 'cells');
                                        $("#divMsjeExito").show();
                                        $("#divMsjeExito").addClass('alert alert-warning alert-dismissable');
                                        $("#aMsjeExito").html(data);
                                    }, //mostramos el error
                                    error: function () {
                                        alert('Se ha producido un error Inesperado');
                                    }
                                });
                            }
                        });
                    }else{
                        var msje = "Error al seleccionar el cargo.";
                        $("#divMsjePorWarning").html("");
                        $("#divMsjePorWarning").append(msje);
                        $("#divMsjeNotificacionWarning").jqxNotification("open");
                    }
                } else {
                    var msje = "Debe seleccionar un registro necesariamente.";
                    $("#divMsjePorError").html("");
                    $("#divMsjePorError").append(msje);
                    $("#divMsjeNotificacionError").jqxNotification("open");
                }
            });
            /**
             * Exportar a formato Excel
             */
            $("#exportexcelpacprowbutton").off();
            $("#exportexcelpacprowbutton").on('click', function () {
                exportarPacReporte(1);
            });
            /**
             * Exportar a formato PDF
             */
            $("#exportpdpacpfrowbutton").off();
            $("#exportpdpacpfrowbutton").on('click', function () {
                exportarPacReporte(2);
            });
            /**
             * Refrescar la grilla
             */
            $("#refreshpacbutton").on('click', function () {
                $("#jqxgrid2").jqxGrid("updatebounddata");
            });
            /**
             * Desagrupar
             */
            $("#cleargroupspacrowbutton").off();
            $("#cleargroupspacrowbutton").on('click', function () {
                $("#jqxgrid2").jqxGrid('cleargroups');
            });
            /**
             * Desfiltrar
             */
            $("#clearfilterspacrowbutton").off();
            $("#clearfilterspacrowbutton").on('click', function () {
                $("#jqxgrid2").jqxGrid('clearfilters');
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
                text: 'Resolución (Organigrama)',
                datafield: 'organigrama_tipo_resolucion',
                filtertype: 'checkedlist',
                align: 'center',
                width: '10%'
            },
            {
                text: 'Resolución (Escala)',
                datafield: 'escala_tipo_resolucion',
                filtertype: 'checkedlist',
                align: 'center',
                width: '10%'
            },
            {
                text: 'Unidad Organizacional',
                datafield: 'unidad_administrativa',
                filtertype: 'checkedlist',
                align: 'center',
                width: '20%'
            },
            {
                text: 'Item',
                datafield: 'codigo',
                filtertype: 'input',
                align: 'center',
                cellsalign: 'center',
                width: '3%'
            },
            {text: 'Cargo', datafield: 'cargo', filtertype: 'input', width: '27%'},
            {
                text: 'Gestion',
                datafield: 'gestion',
                filtertype: 'number',
                align: 'center',
                cellsalign: 'center',
                width: '3%'
            },
            {
                text: 'Fecha Inicio',
                datafield: 'fecha_ini',
                filtertype: 'range',
                width: '5%',
                cellsalign: 'center',
                cellsformat: 'dd-MM-yyyy',
                align: 'center'
            },
            {
                text: 'Fecha Finalización',
                datafield: 'fecha_fin',
                filtertype: 'range',
                width: '5%',
                cellsalign: 'center',
                cellsformat: 'dd-MM-yyyy',
                align: 'center'
            },
            {
                text: 'Estado',
                datafield: 'estado',
                filtertype: 'input',
                align: 'center',
                cellsalign: 'center',
                width: '5%'
            }
        ]
    });


    $("#delete_pac").click(function () {
        var rowindex = $('#jqxgrid2').jqxGrid('getselectedrowindex');
        if (rowindex > -1) {
            var dataRecord = $("#jqxgrid2").jqxGrid('getrowdata', rowindex);
            bootbox.confirm("<strong>¡Mensaje!</strong> Esta seguro de eliminar el registro.", function (result) {
                if (result == true) {
                    var v = $.ajax({
                        url: '/cargos/delete_pac/',
                        type: 'POST',
                        datatype: 'json',
                        data: {id: dataRecord.id},
                        success: function (data) {
                            $("#jqxgrid2").jqxGrid('updatebounddata', 'cells');
                            $("#divMsjeExito").show();
                            $("#divMsjeExito").addClass('alert alert-warning alert-dismissable');
                            $("#aMsjeExito").html(data);
                        }, //mostramos el error
                        error: function () {
                            alert('Se ha producido un error Inesperado');
                        }
                    });
                }
            });
        }
        else {
            bootbox.alert("<strong>¡Mensaje!</strong> Seleccionar un registro para eliminar.");
        }

    });
}

/*
    * Función para obtener los filtros disponibles.
    * @returns {*}
    */
function obtenerFiltrables() {
    var resultado = null;
    $.ajax({
        url: '/cargos/getfilters/',
        type: "POST",
        datatype: 'json',
        async: false,
        cache: false,
        //data: {gestion: gestion},
        success: function (data) {
            resultado = jQuery.parseJSON(data);
        },
        error: function () {
            alert('Se ha producido un error Inesperado');
        }
    });
    return resultado;
}

function select_finpartidas(fin_partida_id) {
    console.log("fin_partida_id:" + fin_partida_id);
    $.post("/cargos/select_finpartidas/", function (data) {
        $("#fin_partida_id").html(data);
        $("#fin_partida_id").val(fin_partida_id);
    });
}

function select_fuentefinanciamiento(fin_partida_id) {
    console.log("fin_partida_id:" + fin_partida_id);
    $("#resolucion_ministerial_id option:selected").each(function () {
        elegido = $(this).val();
        $.post("/cargos/select_fuentefinanciamiento/", {elegido: elegido}, function (data) {
            $("#fin_partida_id").html(data);
            $("#fin_partida_id").val(fin_partida_id);
        });
    });
}

function select_organigrama(organigrama_id) {
    console.log("organigrama prefijado: " + organigrama_id);
    $("#resolucion_ministerial_id option:selected").each(function () {
        elegido = $(this).val();
        $.post("/cargos/select_organigrama/", {elegido: elegido}, function (data) {
            $("#organigrama_id").html(data);
            $("#organigrama_id").val(organigrama_id);
        });
    });
}

function select_resoluciones_escala(id_resolucion_escala) {
    $.post("/cargos/select_resoluciones_escala/", function (data) {
        $("#id_resolucion_escala").html(data);
        $("#id_resolucion_escala").val(id_resolucion_escala);
    });

}

function select_escala(id_resolucion_escala, id_nivelsalarial) {
    $("#id_resolucion_escala option:selected").each(function () {
        id = $(this).val();
        $.post("/cargos/select_escala/", {id_r: id_resolucion_escala}, function (data) {
            $("#codigo_nivel").html(data);
            $("#codigo_nivel").val(id_nivelsalarial);
        });
    });
}

function select_dependencia(organigrama_id, depende_id, gestion_fp) {

    //elegido=organigrama_id;
    $.post("/cargos/select_dependencia/", {elegido: organigrama_id, gestion: gestion_fp}, function (data) {
        $("#depende_id").html(data);
        $("#depende_id").val(depende_id);
    });

}

function exportarPacReporte(option) {
    columna = new Object();
    filtros = new Object();
    agrupados = new Object();
    ordenados = new Object();

    organigrama_tipo_resolucion = $('#jqxgrid2').jqxGrid('getcolumn', 'organigrama_tipo_resolucion');
    escala_tipo_resolucion = $('#jqxgrid2').jqxGrid('getcolumn', 'escala_tipo_resolucion');
    unidad_administrativa = $('#jqxgrid2').jqxGrid('getcolumn', 'unidad_administrativa');
    codigo = $('#jqxgrid2').jqxGrid('getcolumn', 'codigo');
    cargo = $('#jqxgrid2').jqxGrid('getcolumn', 'cargo');
    gestion = $('#jqxgrid2').jqxGrid('getcolumn', 'gestion');
    fecha_ini = $('#jqxgrid2').jqxGrid('getcolumn', 'fecha_ini');
    fecha_fin = $('#jqxgrid2').jqxGrid('getcolumn', 'fecha_fin');
    estado = $('#jqxgrid2').jqxGrid('getcolumn', 'estado');

    columna[organigrama_tipo_resolucion.datafield] = {
        text: organigrama_tipo_resolucion.text,
        hidden: organigrama_tipo_resolucion.hidden
    };
    columna[escala_tipo_resolucion.datafield] = {
        text: escala_tipo_resolucion.text,
        hidden: escala_tipo_resolucion.hidden
    };
    columna[unidad_administrativa.datafield] = {
        text: unidad_administrativa.text,
        hidden: unidad_administrativa.hidden
    };
    columna[codigo.datafield] = {text: codigo.text, hidden: codigo.hidden};
    columna[cargo.datafield] = {text: cargo.text, hidden: cargo.hidden};
    columna[gestion.datafield] = {text: gestion.text, hidden: gestion.hidden};
    columna[fecha_ini.datafield] = {text: fecha_ini.text, hidden: fecha_ini.hidden};
    columna[fecha_fin.datafield] = {text: fecha_fin.text, hidden: fecha_fin.hidden};
    columna[estado.datafield] = {text: estado.text, hidden: estado.hidden};

    var groups = $('#jqxgrid2').jqxGrid('groups');
    if (groups == null || groups == '') groups = 'null';
    //var sorteds = $("#divGrillaCargos").jqxGrid('getsortcolumn');
    ordenados='';
    var sortinformation = $('#jqxgrid2').jqxGrid('getsortinformation');
    if (sortinformation.sortcolumn != undefined) {
        // The sortcolumn rep   resents the sort column's datafield. If there's no sort column, the sortcolumn is null.
        var sortcolumn = sortinformation.sortcolumn;
        // The sortdirection is an object with two fields: 'ascending' and 'descending'. Ex: { 'ascending': true, 'descending': false }
        var sortdirection = sortinformation.sortdirection;
        ordenados[sortcolumn] = {asc: sortdirection.ascending, desc: sortdirection.descending};
    }


    var rows = $('#jqxgrid2').jqxGrid('getrows');
    var filterGroups = $('#jqxgrid2').jqxGrid('getfilterinformation');
    var counter = 0;
    for (var i = 0; i < filterGroups.length; i++) {
        var filterGroup = filterGroups[i];
        var filters = filterGroup.filter.getfilters();
        for (var j = 0; j < filters.length; j++) {
            if (j > 0) {
                counter++;
            }
            var indice = i + counter;
            filtros[indice] = {
                columna: filterGroup.filtercolumn, valor: filters[j].value,
                condicion: filters[j].condition, tipo: filters[j].type
            };
        }
    }
    var n_rows = rows.length;
    var json_filter = JSON.stringify(filtros);
    var json_columns = JSON.stringify(columna);
    var json_sorteds = JSON.stringify(ordenados);
    json_columns = btoa(utf8_encode(json_columns));
    json_filter = btoa(utf8_encode(json_filter));
    json_sorteds = btoa(utf8_encode(json_sorteds));
    var json_groups = btoa(utf8_encode(groups));

    json_columns = json_columns.replace(/\+/g, '-').replace(/\//g, '_').replace(/\=+$/, '');
    json_filter = json_filter.replace(/\+/g, '-').replace(/\//g, '_').replace(/\=+$/, '');
    json_groups = json_groups.replace(/\+/g, '-').replace(/\//g, '_').replace(/\=+$/, '');
    json_sorteds = json_sorteds.replace(/\+/g, '-').replace(/\//g, '_').replace(/\=+$/, '');
    var ruta = '';
    switch (option) {
        case 1:
            ruta = "/cargos/exportarPacExcel/";
            break;
        case 2:
            ruta = "/cargos/exportarPacPdf/";
            break;
    }
    /*if(option==1)ruta="/relaborales/print/";
     elseif(option==2)ruta="/relaborales/print/";*/
    if (ruta != '')
        window.open(ruta + n_rows + "/" + json_columns + "/" + json_filter + "/" + json_groups + "/" + json_sorteds, "_blank");
}

function exportarReporte(option) {
    //alert(option);
    columna = new Object();
    filtros = new Object();
    agrupados = new Object();
    ordenados = new Object();
    organigrama_tipo_resolucion = $("#divGrillaCargos").jqxGrid('getcolumn', 'organigrama_tipo_resolucion');
    escala_tipo_resolucion = $("#divGrillaCargos").jqxGrid('getcolumn', 'escala_tipo_resolucion');
    unidad_administrativa = $("#divGrillaCargos").jqxGrid('getcolumn', 'unidad_administrativa');
    denominacion = $("#divGrillaCargos").jqxGrid('getcolumn', 'denominacion');
    ordenador = $("#divGrillaCargos").jqxGrid('getcolumn', 'ordenador');
    cargo = $("#divGrillaCargos").jqxGrid('getcolumn', 'cargo');
    sueldo = $("#divGrillaCargos").jqxGrid('getcolumn', 'sueldo');
    codigo = $("#divGrillaCargos").jqxGrid('getcolumn', 'codigo');
    estado = $("#divGrillaCargos").jqxGrid('getcolumn', 'estado');
    condicion = $("#divGrillaCargos").jqxGrid('getcolumn', 'condicion');
    partida = $("#divGrillaCargos").jqxGrid('getcolumn', 'partida');
    fuente_codigo = $("#divGrillaCargos").jqxGrid('getcolumn', 'fuente_codigo');
    fuente = $("#divGrillaCargos").jqxGrid('getcolumn', 'fuente');
    organismo_codigo = $("#divGrillaCargos").jqxGrid('getcolumn', 'organismo_codigo');
    organismo = $("#divGrillaCargos").jqxGrid('getcolumn', 'organismo');
    gestion = $("#divGrillaCargos").jqxGrid('getcolumn', 'gestion');


    columna[organigrama_tipo_resolucion.datafield] = {
        text: organigrama_tipo_resolucion.text,
        hidden: organigrama_tipo_resolucion.hidden
    };
    columna[escala_tipo_resolucion.datafield] = {
        text: escala_tipo_resolucion.text,
        hidden: escala_tipo_resolucion.hidden
    };
    columna[unidad_administrativa.datafield] = {
        text: unidad_administrativa.text,
        hidden: unidad_administrativa.hidden
    };
    columna[denominacion.datafield] = {text: denominacion.text, hidden: denominacion.hidden};
    columna[ordenador.datafield] = {text: ordenador.text, hidden: ordenador.hidden};
    columna[cargo.datafield] = {text: cargo.text, hidden: cargo.hidden};
    columna[sueldo.datafield] = {text: sueldo.text, hidden: sueldo.hidden};
    columna[codigo.datafield] = {text: codigo.text, hidden: codigo.hidden};
    columna[estado.datafield] = {text: estado.text, hidden: estado.hidden};
    columna[condicion.datafield] = {text: condicion.text, hidden: condicion.hidden};
    columna[partida.datafield] = {text: partida.text, hidden: partida.hidden};
    columna[fuente_codigo.datafield] = {text: fuente_codigo.text, hidden: fuente_codigo.hidden};
    //columna[fuente.datafield] = {text: fuente.text, hidden: fuente.hidden};
    columna[organismo_codigo.datafield] = {text: organismo_codigo.text, hidden: organismo_codigo.hidden};
    //columna[organismo.datafield] = {text: organismo.text, hidden: organismo.hidden};
    columna[gestion.datafield] = {text: gestion.text, hidden: gestion.hidden};

    var groups = $("#divGrillaCargos").jqxGrid('groups');
    if (groups == null || groups == '') groups = 'null';
    //var sorteds = $("#divGrillaCargos").jqxGrid('getsortcolumn');

    var sortinformation = $("#divGrillaCargos").jqxGrid('getsortinformation');
    if (sortinformation.sortcolumn != undefined) {
        // The sortcolumn rep   resents the sort column's datafield. If there's no sort column, the sortcolumn is null.
        var sortcolumn = sortinformation.sortcolumn;
        // The sortdirection is an object with two fields: 'ascending' and 'descending'. Ex: { 'ascending': true, 'descending': false }
        var sortdirection = sortinformation.sortdirection;
        ordenados[sortcolumn] = {desc: sortdirection.ascending, asc: sortdirection.descending};
    } else ordenados = '';


    var rows = $("#divGrillaCargos").jqxGrid('getrows');
    var filterGroups = $("#divGrillaCargos").jqxGrid('getfilterinformation');
    var counter = 0;
    for (var i = 0; i < filterGroups.length; i++) {
        var filterGroup = filterGroups[i];
        var filters = filterGroup.filter.getfilters();
        for (var j = 0; j < filters.length; j++) {
            if (j > 0) {
                counter++;
            }
            var indice = i + counter;
            filtros[indice] = {
                columna: filterGroup.filtercolumn, valor: filters[j].value,
                condicion: filters[j].condition, tipo: filters[j].type
            };
        }
    }
    var n_rows = rows.length;
    var json_filter = JSON.stringify(filtros);
    var json_columns = JSON.stringify(columna);
    var json_sorteds = JSON.stringify(ordenados);
    json_columns = btoa(utf8_encode(json_columns));
    json_filter = btoa(utf8_encode(json_filter));
    json_sorteds = btoa(utf8_encode(json_sorteds));
    var json_groups = btoa(utf8_encode(groups));

    json_columns = json_columns.replace(/\+/g, '-').replace(/\//g, '_').replace(/\=+$/, '');
    json_filter = json_filter.replace(/\+/g, '-').replace(/\//g, '_').replace(/\=+$/, '');
    json_groups = json_groups.replace(/\+/g, '-').replace(/\//g, '_').replace(/\=+$/, '');
    json_sorteds = json_sorteds.replace(/\+/g, '-').replace(/\//g, '_').replace(/\=+$/, '');
    var ruta = '';
    switch (option) {
        case 1:
            ruta = "/cargos/exportarExcel/";
            break;
        case 2:
            ruta = "/cargos/exportarPdf/";
            break;
    }
    /*if(option==1)ruta="/relaborales/print/";
    elseif(option==2)ruta="/relaborales/print/";*/
    if (ruta != '')
        window.open(ruta + n_rows + "/" + json_columns + "/" + json_filter + "/" + json_groups + "/" + json_sorteds, "_blank");
}
var rownumberrenderer = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
    var nro = row + 1;
    return "<div align='center'>" + nro + "</div>";
}