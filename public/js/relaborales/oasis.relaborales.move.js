/*
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  17-11-2014
 */
function cargarGrillaMovilidad(idRelaboral,idGerenciaAdministrativa) {
    var source =
    {
        datatype: "json",
        datafields: [
            {name: 'nro_row', type: 'integer'},
            {name: 'id_relaboral', type: 'integer'},
            {name: 'id_relaboralmovilidad', type: 'integer'},
            {name: 'id_gerencia_administrativa', type: 'integer'},
            {name: 'gerencia_administrativa', type: 'string'},
            {name: 'id_departamento_administrativo', type: 'integer'},
            {name: 'departamento_administrativo', type: 'string'},
            {name: 'id_organigrama', type: 'integer'},
            {name: 'unidad_administrativa', type: 'string'},
            {name: 'organigrama_sigla', type: 'string'},
            {name: 'organigrama_orden', type: 'string'},
            {name: 'id_area', type: 'integer'},
            {name: 'area', type: 'string'},
            {name: 'id_ubicacion', type: 'integer'},
            {name: 'ubicacion', type: 'string'},
            {name: 'numero', type: 'integer'},
            {name: 'cargo', type: 'string'},
            {name: 'evento_id', type: 'integer'},
            {name: 'evento', type: 'string'},
            {name: 'motivo', type: 'string'},
            {name: 'id_pais', type: 'integer'},
            {name: 'pais', type: 'string'},
            {name: 'id_departamento', type: 'integer'},
            {name: 'departamento', type: 'string'},
            {name: 'lugar', type: 'string'},
            {name: 'fecha_ini', type: 'date'},
            {name: 'hora_ini', type: 'time'},
            {name: 'fecha_fin', type: 'date'},
            {name: 'hora_fin', type: 'time'},
            {name: 'id_memorandum', type: 'integer'},
            {name: 'id_tipomemorandum', type: 'integer'},
            {name: 'tipo_memorandum', type: 'string'},
            {name: 'memorandum_correlativo', type: 'string'},
            {name: 'memorandum_gestion', type: 'integer'},
            {
                name: 'memorandum',
                type: 'string'
            }, /*Valor agrupado de memorandum_correltivo, memorandum_gestion y fecha_mem*/
            {name: 'fecha_mem', type: 'date'},
            {name: 'observacion', type: 'string'},
            {name: 'estado', type: 'integer'},
            {name: 'estado_descripcion', type: 'string'}

        ],
        url: '/relaborales/listhistorialmovilidad?id=' + idRelaboral,
        id: 'id_relaboralmovilidad',
        cache: false
    };
    var dataAdapter = new $.jqx.dataAdapter(source);
    cargarRegistrosDeMovilidadDePersonal();
    function cargarRegistrosDeMovilidadDePersonal() {
        var theme = prepareSimulator("grid");
        $("#jqxgridmovilidad").jqxGrid(
            {
                width: '100%',
                height: '100%',
                source: dataAdapter,
                sortable: true,
                altRows: true,
                groupable: true,
                columnsresize: true,
                pageable: true,
                pagerMode: 'advanced',
                showfilterrow: true,
                filterable: true,
                showtoolbar: true,
                autorowheight: true,
                rendertoolbar: function (toolbar) {
                    var me = this;
                    var container = $("<div></div>");
                    toolbar.append(container);
                    container.append("<button id='addrowbuttonmove' class='btn btn-sm btn-primary' type='button'><i class='fa fa-plus-square fa-2x text-primary' title='Nuevo Registro.'/></i></button>");
                    container.append("<button id='updaterowbuttonmove'  class='btn btn-sm btn-primary' type='button' ><i class='fa fa-pencil-square fa-2x text-info' title='Modificar registro.'/></button>");
                    container.append("<button title='Dar de baja registro de Movilidad' id='downrowbuttonmove' class='btn btn-sm btn-primary' type='button'><i class='fa fa-minus-square fa-2x text-warning' title='Dar de baja al registro.'/></i></button>");
                    container.append("<button title='Eliminar registro de Movilidad' id='deleterowbuttonmove' class='btn btn-sm btn-primary' type='button'><i class='gi gi-bin fa-2x text-danger' title='Eliminar registro.'/></i></button>");
                    container.append("<button title='Actualizar Grilla' id='refreshmoverowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-refresh fa-2x text-default' title='Refrescar Grilla.'/></i></button>");
                    container.append("<button title='Desagrupar Grilla.' id='clearmoverowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-chain-broken fa-2x text-default' title='Desagrupar Grilla.'/></i></button>");
                    container.append("<button title='Desfiltrar Grilla.' id='clearfiltersmoverowbutton' class='btn btn-sm btn-primary' type='button'><i class='gi gi-sorting fa-2x text-default' title='Desfiltrar Grilla.'/></i></button>");

                    $("#addrowbuttonmove").jqxButton();
                    $("#updaterowbuttonmove").jqxButton();
                    $("#downrowbuttonmove").jqxButton();
                    $("#deleterowbuttonmove").jqxButton();
                    $("#refreshmoverowbutton").jqxButton();
                    $("#clearmoverowbutton").jqxButton();
                    $("#clearfiltersmoverowbutton").jqxButton();

                    /**
                     * Actualizar grilla
                     */
                    $("#refreshcontrolexceptrowbutton").off();
                    $("#refreshcontrolexceptrowbutton").on('click', function () {
                        $("#jqxgridmovilidad").jqxGrid("updatebounddata");
                    });
                    /**
                     * Desagrupar
                     */
                    $("#clearcontrolexceptgroupsrowbutton").off();
                    $("#clearcontrolexceptgroupsrowbutton").on('click', function () {
                        $("#jqxgridmovilidad").jqxGrid('cleargroups');
                    });
                    /**
                     * Desfiltrar
                     */
                    $("#clearfilterscontrolexceptrowbutton").off();
                    $("#clearfilterscontrolexceptrowbutton").on('click', function () {
                        $("#jqxgridmovilidad").jqxGrid('clearfilters');
                    });

                    // Registrar nueva movilidad de personal.
                    $("#addrowbuttonmove").on('click', function () {
                        /**
                         * Inicialmente es necesario eliminar los eventos sobre este elemento para que no se repitan
                         */
                        $("#lstTipoMemorandum").off();
                        $("#hdnIdRelaboralPorMovilidad").val(idRelaboral);
                        $("#hdnIdRelaboralMovilidadModificar").val(0);
                        $("#hdnIdRelaboralMovilidadBaja").val(0);
                        $("#hdnFechaMemMovilidadBaja").val("");
                        $("#hdnFechaIniMovilidadBaja").val("");
                        $("#hdnIdMemorandumMovilidadModificar").val(0);
                        $("#chkAi").attr("checked", false);
                        $("#txtCorrelativoMemorandum").val("");
                        $("#txtCargoMovilidad").val("");
                        $("#lstUbicaciones").val("");
                        $("#txtMotivoMovilidad").val("");
                        $("#txtLugarMovilidad").val("");
                        $("#txtObservacionMovilidad").val("");
                        $("#txtFechaMem").val("");
                        $("#txtFechaIniMovilidad").val("");
                        $("#txtFechaFinMovilidad").val("");
                        $("#hdnIdOrganigramaPorSeleccionCargoSuperior").val(0);

                        /**
                         * Mostramos todos en caso de que hayan sido ocultados
                         */
                        $("#divTiposMemorandums").show();
                        $("#divCorrelativosMemorandums").show();
                        $("#divFechasMemorandums").show();
                        $("#divCargosMovilidad").show();
                        $("#divMotivosMovilidad").show();
                        $("#divPaisesMovilidad").show();
                        $("#divCiudadesMovilidad").show();
                        $("#divLugaresMovilidad").show();
                        $("#divGerenciasAdministrativasMovilidad").show();
                        $("#divDepartamentosAdministrativosMovilidad").show();
                        $("#divAreasAdministrativasMovilidad").show();
                        $("#divUbicacionesMovilidad").show();
                        $("#divFechasIniMovilidad").show();
                        $("#divHorasIniMovilidad").show();

                        limpiarMensajesErrorPorValidacionMovilidad();
                        $("#divTitleRegistroMovilidad").html("");
                        $("#divTitleRegistroMovilidad").append("Nuevo Registro de Movilidad de Personal");
                        $("#spanNotaDos").show();
                        cargarTiposMemorandumsParaMovilidad(0);
                        cargarGestionesMemorandumsParaMovilidad(0);
                        cargarUnidadesOrganizacionalesParaMovilidad(idGerenciaAdministrativa,0, 0, 0);
                        cargarUbicacionesParaMovilidad(0);
                        cargarCargosParaMovilidad('');
                        //$("#lstTipoMemorandum").focus();
                        $("#lstTipoMemorandum").change(function () {

                            /*var itemTipoMemorandum = $("#lstTipoMemorandum").jqxComboBox('getSelectedItem');*/
                            var itemTipoMemorandum = $("#lstTipoMemorandum").val();
                            if (itemTipoMemorandum != 0) {
                                var id_agraupado = $("#lstTipoMemorandum").val();
                                var arr = id_agraupado.split("-");
                                var idTipoMemorandum = arr[0];
                                /*Identificador del tipo de memorándum*/
                                var ff = arr[1];
                                /*Requerir fecha de finalización*/
                                var hf = arr[2];
                                /*Requerir hora de finalización*/
                                var cc = arr[3];
                                /*Requerir cargo*/
                                var oo = arr[4];
                                /*Requerir unidad organizacional*/
                                var uu = arr[5];
                                /*Requerir ubicación*/
                                var mm = arr[6];
                                /*Requerir motivo*/
                                var pp = arr[7];
                                /*Requerir pais*/
                                var dd = arr[8];
                                /*Requerir departamento o ciudad*/
                                var ll = arr[9];
                                /*Requerir lugar del evento*/
                                /*
                                 * Se evalua en función del tipo de memorándum seleccionado los datos requeridos.
                                 */
                                if (ff >= 1) {
                                    $("#divFechasFinMovilidad").show();
                                    $("#divHorasFinMovilidad").show();
                                    if (ff == 1) {
                                        $("#asteriscoFechaFin").html("");
                                        $("#asteriscoFechaFin").append("*");
                                    }
                                } else {
                                    $("#divFechasFinMovilidad").hide();
                                    $("#divHorasFinMovilidad").hide();
                                    $("#asteriscoFechaFin").html("");
                                }
                                if (mm >= 1) {
                                    $("#divMotivosMovilidad").show();
                                } else $("#divMotivosMovilidad").hide();

                                if (pp >= 1) {
                                    $("#divPaisesMovilidad").show();
                                    cargarPaisesCiudadesParaMovilidad(0, 0);
                                } else $("#divPaisesMovilidad").hide();

                                if (dd >= 1) {
                                    $("#divCiudadesMovilidad").show();
                                } else $("#divCiudadesMovilidad").hide();

                                if (ll >= 1) {
                                    $("#divLugaresMovilidad").show();
                                } else $("#divLugaresMovilidad").hide();

                                if (cc >= 1) {
                                    $("#divCargosMovilidad").show();
                                    /**
                                     * Si es requerido el cargo y además el tipo de documento refiere a ASIGNACIÓN DE FUNCIONES
                                     * se pone visible la opción de añadir "a.i." al final del nombre del cargo.
                                     */
                                    if (idTipoMemorandum == 2) {
                                        $("#divChkAi").show();
                                        obtieneCargoInmediatoSuperior(idRelaboral);
                                    } else {
                                        $("#txtCargoMovilidad").val("");
                                        $("#divChkAi").hide();
                                    }
                                } else {
                                    $("#divCargosMovilidad").hide();
                                }
                                if (oo >= 1) {
                                    $("#divGerenciasAdministrativasMovilidad").show();
                                    $("#divDepartamentosAdministrativosMovilidad").show();
                                    $("#divAreasAdministrativasMovilidad").show();
                                } else {
                                    $("#divGerenciasAdministrativasMovilidad").hide();
                                    $("#divDepartamentosAdministrativosMovilidad").hide();
                                    $("#divAreasAdministrativasMovilidad").hide();
                                }
                                if (uu >= 1) {
                                    $("#divUbicacionesMovilidad").show();
                                } else {
                                    $("#divUbicacionesMovilidad").hide();
                                }
                            } else {
                                $("#divCargosMovilidad").hide();
                                $("#divChkAi").hide();

                                $("#divMotivosMovilidad").hide();
                                $("#divPaisesMovilidad").hide();
                                $("#divCiudadesMovilidad").hide();
                                $("#divLugaresMovilidad").hide();

                                $("#divGerenciasAdministrativasMovilidad").hide();
                                $("#divDepartamentosAdministrativosMovilidad").hide();
                                $("#divAreasAdministrativasMovilidad").hide();
                                $("#divUbicacionesMovilidad").hide();
                                $("#divFechasFinMovilidad").hide();
                                $("#divHorasFinMovilidad").hide();

                            }
                            $("#txtCorrelativoMemorandum").focus();
                        });
                        $("#lstUbicaciones").change(function () {
                            $("#txtCargoMemorandum").focus();
                        });
                        $("#txtFechaMem").jqxDateTimeInput({
                            enableBrowserBoundsDetection: false,
                            disabled: false,
                            height: 24,
                            formatString: 'dd-MM-yyyy'
                        });
                        var fechaActual = fechaHoy();
                        $('#txtFechaMem').jqxDateTimeInput('setMaxDate', fechaActual);
                        /*
                         Se establece como fecha mínima debido a que al inicio de operaciones de la empresa
                         */
                        $('#txtFechaMem').jqxDateTimeInput('setMinDate', new Date(2014, 3, 1));

                        $("#txtFechaIniMovilidad").jqxDateTimeInput({
                            disabled: false,
                            enableBrowserBoundsDetection: false,
                            height: 24,
                            formatString: 'dd-MM-yyyy'
                        });
                        $("#txtFechaFinMovilidad").jqxDateTimeInput({
                            enableBrowserBoundsDetection: false,
                            height: 24,
                            formatString: 'dd-MM-yyyy'
                        });
                        /**
                         * Campos ocultos por defecto
                         */
                        $("#divCargosMovilidad").hide();
                        $("#divChkAi").hide();

                        $("#divMotivosMovilidad").hide();
                        $("#divPaisesMovilidad").hide();
                        $("#divCiudadesMovilidad").hide();
                        $("#divLugaresMovilidad").hide();

                        $("#divGerenciasAdministrativasMovilidad").hide();
                        $("#divDepartamentosAdministrativosMovilidad").hide();
                        $("#divAreasAdministrativasMovilidad").hide();
                        $("#divUbicacionesMovilidad").hide();
                        $("#divFechasFinMovilidad").hide();
                        $("#divHorasFinMovilidad").hide();

                        $("#txtHoraIniMovilidad").val("");
                        $("#txtHoraFinMovilidad").val("");
                        $("#txtObservacionMovilidad").jqxInput({
                            width: 300,
                            height: 35,
                            placeHolder: "Introduzca sus observaciones."
                        });
                        $("#popupWindowNuevaMovilidad").jqxWindow('open');
                    });

                    /**
                     * Modificar registro de movilidad de personal.
                     */
                    $("#updaterowbuttonmove").on('click', function () {
                        /**
                         * Inicialmente es necesario eliminar los eventos sobre este elemento para que no se repitan
                         */
                        $("#lstTipoMemorandum").off();
                        $("#hdnIdRelaboralPorMovilidad").val(idRelaboral);
                        $("#hdnIdRelaboralMovilidadBaja").val(0);
                        $("#hdnFechaMemMovilidadBaja").val("");
                        $("#hdnFechaIniMovilidadBaja").val("");
                        limpiarMensajesErrorPorValidacionMovilidad();
                        var selectedrowindex = $("#jqxgridmovilidad").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var dataRecord = $('#jqxgridmovilidad').jqxGrid('getrowdata', selectedrowindex);
                            if (dataRecord != undefined) {
                                var idRelaboralMovilidad = dataRecord.id_relaboralmovilidad;
                                $("#hdnIdRelaboralMovilidadModificar").val(idRelaboralMovilidad);
                                $("#hdnIdMemorandumMovilidadModificar").val(dataRecord.id_memorandum);
                                $("#divTitleRegistroMovilidad").html("");
                                $("#divTitleRegistroMovilidad").append("Modificaci&oacute;n Registro de Movilidad de Personal");
                                $("#spanNotaDos").show();
                                /*
                                 * Mostramos todos en caso de que hayan sido ocultados
                                 */
                                $("#divTiposMemorandums").show();
                                $("#divCorrelativosMemorandums").show();
                                $("#divFechasMemorandums").show();
                                $("#divCargosMovilidad").show();
                                $("#divMotivosMovilidad").show();
                                $("#divPaisesMovilidad").show();
                                $("#divCiudadesMovilidad").show();
                                $("#divLugaresMovilidad").show();
                                $("#divGerenciasAdministrativasMovilidad").show();
                                $("#divDepartamentosAdministrativosMovilidad").show();
                                $("#divAreasAdministrativasMovilidad").show();
                                $("#divUbicacionesMovilidad").show();
                                $("#divFechasIniMovilidad").show();
                                $("#divHorasIniMovilidad").show();

                                /**
                                 * Para el caso cuando la persona tenga un registro de relación laboral en estado EN PROCESO o ACTIVO.
                                 */
                                if (dataRecord.estado >= 1) {

                                    cargarGestionesMemorandumsParaMovilidad(dataRecord.memorandum_gestion);
                                    $("#txtCorrelativoMemorandum").val(dataRecord.memorandum_correlativo);
                                    $("#txtCorrelativoMemorandum").focus();
                                    $("#txtFechaMem").jqxDateTimeInput({
                                        value: dataRecord.fecha_mem,
                                        disabled: false,
                                        enableBrowserBoundsDetection: false,
                                        height: 24,
                                        formatString: 'dd-MM-yyyy'
                                    });
                                    var fechaActual = fechaHoy();
                                    $('#txtFechaMem').jqxDateTimeInput('setMaxDate', fechaActual);
                                    /*
                                     Se establece como fecha mínima debido a que al inicio de operaciones de la empresa
                                     */
                                    $('#txtFechaMem').jqxDateTimeInput('setMinDate', new Date(2014, 3, 1));
                                    $("#txtMotivoMovilidad").val(dataRecord.motivo);
                                    cargarCargosParaMovilidad('');
                                    if(dataRecord.id_tipomemorandum==2){
                                        $("#divChkAi").show();
                                    }
                                    $("#txtCargoMovilidad").val(dataRecord.cargo);
                                    var asignacionCargo = dataRecord.cargo;
                                    if (asignacionCargo != null && asignacionCargo != '') {
                                        var n = asignacionCargo.search("a.i.");
                                        if (n > 0) {
                                            /**
                                             * Si el cargo menciona la palabra a.i.
                                             */
                                            $("#chkAi").prop("checked", true);
                                        }
                                    }
                                    cargarUnidadesOrganizacionalesParaMovilidad(idGerenciaAdministrativa,dataRecord.id_gerencia_administrativa, dataRecord.id_departamento_administrativo, dataRecord.id_area);
                                    cargarUbicacionesParaMovilidad(dataRecord.id_ubicacion);
                                    cargarPaisesCiudadesParaMovilidad(dataRecord.id_pais, dataRecord.id_departamento);

                                    $("#txtFechaIniMovilidad").jqxDateTimeInput({
                                        value: dataRecord.fecha_ini,
                                        disabled: false,
                                        enableBrowserBoundsDetection: false,
                                        height: 24,
                                        formatString: 'dd-MM-yyyy'
                                    });
                                    if (dataRecord.hora_ini != '') {
                                        $("#txtHoraIniMovilidad").val(dataRecord.hora_ini);
                                    } else {
                                        $("#txtHoraIniMovilidad").val("")
                                    }
                                    if (dataRecord.hora_fin != '') {
                                        $("#txtHoraFinMovilidad").val(dataRecord.hora_fin);
                                    } else {
                                        $("#txtHoraFinMovilidad").val("");
                                    }
                                    if (dataRecord.fecha_fin != null && dataRecord.fecha_fin != '') {
                                        $("#txtFechaFinMovilidad").jqxDateTimeInput({
                                            value: dataRecord.fecha_fin,
                                            enableBrowserBoundsDetection: false,
                                            height: 24,
                                            formatString: 'dd-MM-yyyy'
                                        });
                                    } else {
                                        $("#txtFechaFinMovilidad").jqxDateTimeInput({
                                            enableBrowserBoundsDetection: false,
                                            height: 24,
                                            formatString: 'dd-MM-yyyy'
                                        });
                                        $("#txtFechaFinMovilidad").val("");
                                    }
                                    $("#txtObservacionMovilidad").jqxInput({
                                        value: dataRecord.observacion,
                                        width: 300,
                                        height: 35,
                                        placeHolder: "Introduzca sus observaciones"
                                    });

                                    cargarTiposMemorandumsParaMovilidad(dataRecord.id_tipomemorandum);
                                    /*
                                     * En base al tipo de memorándum preseleccionado se muestran a continuación los valores necesarios
                                     */
                                    var idTipoMemorandum = $("#lstTipoMemorandum").val();
                                    if (idTipoMemorandum != '0') {
                                        var id_agrupado = $("#lstTipoMemorandum").val();
                                        var arr = id_agrupado.split("-");
                                        var idTipoMemorandum = arr[0];
                                        /*Identificador del tipo de memorándum*/
                                        var ff = arr[1];
                                        /*Requerir fecha de finalización*/
                                        var hf = arr[2];
                                        /*Requerir hora de finalización*/
                                        var cc = arr[3];
                                        /*Requerir cargo*/
                                        var oo = arr[4];
                                        /*Requerir unidad organizacional*/
                                        var uu = arr[5];
                                        /*Requerir ubicación*/
                                        var mm = arr[6];
                                        /*Requerir motivo*/
                                        var pp = arr[7];
                                        /*Requerir pais*/
                                        var dd = arr[8];
                                        /*Requerir departamento o ciudad*/
                                        var ll = arr[9];
                                        /*Requerir lugar del evento*/
                                        /*
                                         * Se evalua en función del tipo de memorándum seleccionado los datos requeridos.
                                         */
                                        if (ff >= 1) {
                                            $("#divFechasFinMovilidad").show();
                                            $("#divHorasFinMovilidad").show();
                                            if (ff == 1) {
                                                $("#asteriscoFechaFin").html("");
                                                $("#asteriscoFechaFin").append("*");
                                            } else {
                                                $("#asteriscoFechaFin").html("");
                                            }
                                        } else {
                                            $("#divFechasFinMovilidad").hide();
                                            $("#divHorasFinMovilidad").hide();
                                        }
                                        if (mm >= 1) {
                                            $("#divMotivosMovilidad").show();
                                        } else $("#divMotivosMovilidad").hide();

                                        if (pp >= 1) {
                                            $("#divPaisesMovilidad").show();
                                        } else $("#divPaisesMovilidad").hide();

                                        if (dd >= 1) {
                                            $("#divCiudadesMovilidad").show();
                                        } else $("#divCiudadesMovilidad").hide();

                                        if (ll >= 1) {
                                            $("#divLugaresMovilidad").show();
                                            $("#txtLugarMovilidad").val(dataRecord.lugar);
                                        } else $("#divLugaresMovilidad").hide();

                                        if (cc >= 1) {
                                            $("#divCargosMovilidad").show();
                                            /**
                                             * Si es requerido el cargo y además el tipo de documento refiere a ASIGNACIÓN DE FUNCIONES
                                             * se pone visible la opción de añadir "a.i." al final del nombre del cargo.
                                             */
                                            if (idTipoMemorandum == 2) {
                                                $("#divChkAi").show();
                                            } else {
                                                $("#txtCargoMovilidad").val("");
                                                $("#divChkAi").hide();
                                            }
                                        } else {
                                            $("#divCargosMovilidad").hide();
                                        }
                                        if (oo >= 1) {
                                            $("#divGerenciasAdministrativasMovilidad").show();
                                            $("#divDepartamentosAdministrativosMovilidad").show();
                                            $("#divAreasAdministrativasMovilidad").show();
                                        } else {
                                            $("#divGerenciasAdministrativasMovilidad").hide();
                                            $("#divDepartamentosAdministrativosMovilidad").hide();
                                            $("#divAreasAdministrativasMovilidad").hide();
                                        }
                                        if (uu >= 1) {
                                            $("#divUbicacionesMovilidad").show();
                                        } else {
                                            $("#divUbicacionesMovilidad").hide();
                                        }
                                    }

                                    $("#lstTipoMemorandum").focus();
                                    $("#lstTipoMemorandum").change(function () {
                                        $("#txtCorrelativoMemorandum").focus();
                                        var idTipoMemorandum = $("#lstTipoMemorandum").val();
                                        if (idTipoMemorandum != '0') {
                                            var id_agrupado = $("#lstTipoMemorandum").val();
                                            var arr = id_agrupado.split("-");
                                            var idTipoMemorandum = arr[0];
                                            /*Identificador del tipo de memorándum*/
                                            var ff = arr[1];
                                            /*Requerir fecha de finalización*/
                                            var hf = arr[2];
                                            /*Requerir hora de finalización*/
                                            var cc = arr[3];
                                            /*Requerir cargo*/
                                            var oo = arr[4];
                                            /*Requerir unidad organizacional*/
                                            var uu = arr[5];
                                            /*Requerir ubicación*/
                                            var mm = arr[6];
                                            /*Requerir motivo*/
                                            var pp = arr[7];
                                            /*Requerir pais*/
                                            var dd = arr[8];
                                            /*Requerir departamento o ciudad*/
                                            var ll = arr[9];
                                            /*Requerir lugar del evento*/
                                            /*
                                             * Se evalua en función del tipo de memorándum seleccionado los datos requeridos.
                                             */
                                            if (ff >= 1) {
                                                $("#divFechasFinMovilidad").show();
                                                $("#divHorasFinMovilidad").show();
                                                if (ff == 1) {
                                                    $("#asteriscoFechaFin").html("");
                                                    $("#asteriscoFechaFin").append("*");
                                                } else {
                                                    $("#asteriscoFechaFin").html("");
                                                }
                                            } else {
                                                $("#divFechasFinMovilidad").hide();
                                                $("#divHorasFinMovilidad").hide();
                                            }
                                            if (mm >= 1) {
                                                $("#divMotivosMovilidad").show();
                                            } else $("#divMotivosMovilidad").hide();

                                            if (pp >= 1) {
                                                $("#divPaisesMovilidad").show();
                                            } else $("#divPaisesMovilidad").hide();

                                            if (dd >= 1) {
                                                $("#divCiudadesMovilidad").show();
                                            } else $("#divCiudadesMovilidad").hide();

                                            if (ll >= 1) {
                                                $("#divLugaresMovilidad").show();
                                            } else $("#divLugaresMovilidad").hide();

                                            if (cc >= 1) {
                                                $("#divCargosMovilidad").show();
                                                /**
                                                 * Si es requerido el cargo y además el tipo de documento refiere a ASIGNACIÓN DE FUNCIONES
                                                 * se pone visible la opción de añadir "a.i." al final del nombre del cargo.
                                                 */
                                                if (idTipoMemorandum == 2) {
                                                    $("#divChkAi").show();
                                                    obtieneCargoInmediatoSuperior(idRelaboral);
                                                } else {
                                                    $("#txtCargoMovilidad").val("");
                                                    $("#divChkAi").hide();
                                                }
                                            } else {
                                                $("#divCargosMovilidad").hide();
                                            }
                                            if (oo >= 1) {
                                                $("#divGerenciasAdministrativasMovilidad").show();
                                                $("#divDepartamentosAdministrativosMovilidad").show();
                                                $("#divAreasAdministrativasMovilidad").show();
                                            } else {
                                                $("#divGerenciasAdministrativasMovilidad").hide();
                                                $("#divDepartamentosAdministrativosMovilidad").hide();
                                                $("#divAreasAdministrativasMovilidad").hide();
                                            }
                                            if (uu >= 1) {
                                                $("#divUbicacionesMovilidad").show();
                                            } else {
                                                $("#divUbicacionesMovilidad").hide();
                                            }
                                        }
                                    });
                                    $("#lstUbicaciones").change(function () {
                                        $("#txtCargoMemorandum").focus();
                                    });
                                    $("#popupWindowNuevaMovilidad").jqxWindow('open');
                                } else {
                                    var msj = "Debe seleccionar un registro con estado ACTIVO para posibilitar la modificaci&oacute;n del registro de movilidad.";
                                    $("#divMsjePorError").html("");
                                    $("#divMsjePorError").append(msj);
                                    $("#divMsjeNotificacionError").jqxNotification("open");
                                }
                            }
                        } else {
                            var msj = "Debe seleccionar un registro necesariamente.";
                            $("#divMsjePorError").html("");
                            $("#divMsjePorError").append(msj);
                            $("#divMsjeNotificacionError").jqxNotification("open");
                        }
                    });
                    /* Dar de baja un registro de movilidad de personal.*/
                    $("#downrowbuttonmove").off();
                    $("#downrowbuttonmove").on('click', function () {
                        $("#hdnIdRelaboralPorMovilidad").val(idRelaboral);
                        $("#hdnIdMemorandumMovilidadModificar").val(0);
                        limpiarMensajesErrorPorValidacionMovilidad();
                        var selectedrowindex = $("#jqxgridmovilidad").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var dataRecord = $('#jqxgridmovilidad').jqxGrid('getrowdata', selectedrowindex);
                            if (dataRecord != undefined) {
                                var idRelaboralMovilidad = dataRecord.id_relaboralmovilidad;
                                $("#divTitleRegistroMovilidad").html("");
                                $("#divTitleRegistroMovilidad").append("Baja Registro de Movilidad de Personal");
                                $("#spanNotaDos").hide();
                                /**
                                 * Para el caso cuando la persona tenga un registro de relación laboral en estado EN PROCESO o ACTIVO.
                                 */
                                if (dataRecord.estado >= 1) {

                                    $("#hdnIdRelaboralMovilidadBaja").val(idRelaboralMovilidad);
                                    $("#txtFechaMem").jqxDateTimeInput({
                                        disabled: true,
                                        value: dataRecord.fecha_mem,
                                        enableBrowserBoundsDetection: false,
                                        height: 24,
                                        formatString: 'dd-MM-yyyy'
                                    });
                                    $("#txtFechaIniMovilidad").jqxDateTimeInput({
                                        disabled: true,
                                        value: dataRecord.fecha_ini,
                                        enableBrowserBoundsDetection: false,
                                        height: 24,
                                        formatString: 'dd-MM-yyyy'
                                    });
                                    if (dataRecord.hora_ini != '') {
                                        $("#txtHoraIniMovilidad").val(dataRecord.hora_ini);
                                    } else {
                                        $("#txtHoraIniMovilidad").val("");
                                    }
                                    $("#txtHoraIniMovilidad").prop("disabled",true);
                                    $("#divTiposMemorandums").hide();
                                    $("#divCorrelativosMemorandums").hide();
                                    $("#divFechasMemorandums").hide();
                                    $("#divCargosMovilidad").hide();
                                    $("#divMotivosMovilidad").hide();
                                    $("#divPaisesMovilidad").hide();
                                    $("#divCiudadesMovilidad").hide();
                                    $("#divLugaresMovilidad").hide();
                                    $("#divGerenciasAdministrativasMovilidad").hide();
                                    $("#divDepartamentosAdministrativosMovilidad").hide();
                                    $("#divAreasAdministrativasMovilidad").hide();
                                    $("#divUbicacionesMovilidad").hide();

                                    ;/*Sólo con fines de información, no modificable*/
                                    $("#divFechasMemorandums").show()
                                    $("#divFechasIniMovilidad").show();
                                    $("#divHorasIniMovilidad").show();
                                    $("#divFechasFinMovilidad").show();
                                    $("#divHorasFinMovilidad").show();

                                    $("#asteriscoFechaFin").html("");
                                    $("#asteriscoFechaFin").append("*");
                                    if (dataRecord.fecha_fin != null && dataRecord.fecha_fin != '') {
                                        $("#txtFechaFinMovilidad").jqxDateTimeInput({
                                            value: dataRecord.fecha_fin,
                                            enableBrowserBoundsDetection: false,
                                            height: 24,
                                            formatString: 'dd-MM-yyyy'
                                        });
                                    } else {
                                        $("#txtFechaFinMovilidad").jqxDateTimeInput({
                                            enableBrowserBoundsDetection: false,
                                            height: 24,
                                            formatString: 'dd-MM-yyyy'
                                        });
                                        $("#txtFechaFinMovilidad").val("");
                                    }
                                    if (dataRecord.hora_fin != '') {
                                        $("#txtHoraFinMovilidad").val(dataRecord.hora_fin);
                                    } else {
                                        $("#txtHoraFinMovilidad").val("");
                                    }
                                    $("#txtObservacionMovilidad").jqxInput({
                                        value: dataRecord.observacion,
                                        width: 300,
                                        height: 35,
                                        placeHolder: "Introduzca sus observaciones"
                                    });
                                    $("#popupWindowNuevaMovilidad").jqxWindow('open');
                                    $("#txtFechaFinMovilidad").focus();
                                } else {
                                    var msj= "Debe seleccionar un registro con estado ACTIVO para posibilitar la modificaci&oacute;n del registro de movilidad";
                                    $("#divMsjePorError").html("");
                                    $("#divMsjePorError").append(msj);
                                    $("#divMsjeNotificacionError").jqxNotification("open");

                                }
                            }
                        } else {
                            $("#divMsjePorError").html("");
                            $("#divMsjePorError").append("Debe seleccionar un registro necesariamente.");
                            $("#divMsjeNotificacionError").jqxNotification("open");
                        }
                    });
                    /* Eliminar registro de movilidad de personal */
                    $("#deleterowbuttonmove").off();
                    $("#deleterowbuttonmove").on('click', function () {
                        limpiarMensajesErrorPorValidacionMovilidad();
                        var selectedrowindex = $("#jqxgridmovilidad").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var dataRecord = $('#jqxgridmovilidad').jqxGrid('getrowdata', selectedrowindex);
                            if (dataRecord != undefined) {
                                var idRelaboralMovilidad = dataRecord.id_relaboralmovilidad;
                                var estado = dataRecord.estado;
                                if(idRelaboralMovilidad>0&&estado>0){
                                    if(confirm("Esta seguro de que desea eliminar este registro? El registro desaparecera del historial de Movilidad de Personal.")){
                                        var ok = eliminarRegistroMovilidad(idRelaboralMovilidad);
                                    }
                                }
                            }
                        } else {
                            $("#divMsjePorError").html("");
                            $("#divMsjePorError").append("Debe seleccionar un registro necesariamente.");
                            $("#divMsjeNotificacionError").jqxNotification("open");
                        }
                    });
                },
                columns: [
                    {
                        text: 'Nro.',
                        datafield: 'numero',
                        sortable: false,
                        filterable: false,
                        editable: false,
                        groupable: false,
                        draggable: false,
                        resizable: false,
                        width: 40,
                        cellsalign: 'center',
                        align: 'center'
                    },
                    {
                        text: 'Memorandum',
                        columntype: 'dropdownlist',
                        datafield: 'memorandum',
                        width: 100,
                        align: 'center'
                    },
                    {
                        text: 'Tipo',
                        filtertype: 'checkedlist',
                        datafield: 'tipo_memorandum',
                        width: 130,
                        align: 'center'
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
                        text: 'Gerencia',
                        filtertype: 'checkedlist',
                        datafield: 'gerencia_administrativa',
                        width: 130,
                        align: 'center'
                    },
                    {
                        text: 'Dependencia',
                        filtertype: 'checkedlist',
                        datafield: 'departamento_administrativo',
                        width: 130,
                        align: 'center'
                    },
                    {text: 'Area', filtertype: 'checkedlist', datafield: 'area', width: 130, align: 'center'},
                    {
                        text: 'Ubicacion',
                        filtertype: 'checkedlist',
                        datafield: 'ubicacion',
                        width: 100,
                        cellsalign: 'center',
                        align: 'center'
                    },
                    {
                        text: 'Cargo',
                        columntype: 'textbox',
                        datafield: 'cargo',
                        width: 130,
                        cellsalign: 'center',
                        align: 'center'
                    },
                    {
                        text: 'Motivo',
                        columntype: 'textbox',
                        datafield: 'motivo',
                        width: 130,
                        cellsalign: 'center',
                        align: 'center'
                    },
                    {
                        text: 'Lugar',
                        columntype: 'textbox',
                        datafield: 'lugar',
                        width: 130,
                        cellsalign: 'center',
                        align: 'center'
                    },
                    {
                        text: 'Fecha Inicio',
                        datafield: 'fecha_ini',
                        filtertype: 'range',
                        width: 90,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center'
                    },
                    {
                        text: 'Hora Inicio',
                        filtertype: 'checkedlist',
                        datafield: 'hora_ini',
                        width: 90,
                        cellsalign: 'center',
                        cellsformat: 't',
                        align: 'center'
                    },
                    {
                        text: 'Fecha Fin',
                        datafield: 'fecha_fin',
                        filtertype: 'range',
                        width: 90,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center'
                    },
                    {
                        text: 'Hora Fin',
                        filtertype: 'checkedlist',
                        datafield: 'hora_fin',
                        width: 90,
                        cellsalign: 'center',
                        cellsformat: 't',
                        align: 'center'
                    },
                    {
                        text: 'Observaciones',
                        columntype: 'textbox',
                        datafield: 'observacion',
                        width: 130,
                        cellsalign: 'center',
                        align: 'center'
                    }
                ]
            });
    }

    /**
     * Eventos
     */
    $("#jqxgridmovilidad").on('cellendedit', function (event) {
        var args = event.args;
        $("#cellendeditevent").text("Event Type: cellendedit, Column: " + args.datafield + ", Row: " + (1 + args.rowindex) + ", Value: " + args.value);
    });
}
/**
 * Función para cargar el combo de tipos de memorándums.
 * @param idTipoMemorandumPrederminado Identificador del tipo de memorándum predeterminado.
 */

function cargarTiposMemorandumsParaMovilidad(idTipoMemorandumPrederminado) {
    var agrupador = 1;
    var sw = 0;
    $.ajax({
        url: '/relaborales/listtiposmemorandumsmovilidad',
        type: 'POST',
        datatype: 'json',
        async: false,
        cache: false,
        success: function (data) {
            var res = jQuery.parseJSON(data);
            $('#lstTipoMemorandum').html("");
            $('#lstTipoMemorandum').append("<option value='0'>Seleccionar...</option>");
            if (res.length > 0) {
                $.each(res, function (key, val) {
                    if (idTipoMemorandumPrederminado == val.id) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }
                    $('#lstTipoMemorandum').append("<option value=" + val.id_agrupado + " " + $selected + ">" + val.tipo_memorandum + "</option>");
                    sw = 1;
                });
                if (sw == 0)$('#lstTipoMemorandum').prop("disabled", "disabled");
            } else $('#lstTipoMemorandum').prop("disabled", "disabled");
        }
    });
}

/*
 function cargarTiposMemorandumsParaMovilidad(idTipoMemorandumPrederminado){
 var tiposMemorandumsSource =
 {
 dataType: "json",
 dataFields: [
 { name: 'tipo_memorandum'},
 { name: 'id_agrupado'}
 ],
 url: '/relaborales/listtiposmemorandumsmovilidad'
 };
 var tiposMemorandumsAdapter = new $.jqx.dataAdapter(tiposMemorandumsSource);
 $("#lstTipoMemorandum").jqxComboBox(
 {
 source: tiposMemorandumsAdapter,
 width: 300,
 height: 25,
 promptText: "Seleccione un tipo de memorandum...",
 displayMember: 'tipo_memorandum',
 valueMember: 'id_agrupado'
 });

 $("#lstTipoMemorandum").jqxComboBox('selectItem','DESIGNACION DE FUNCIONES');
 }*/
/**
 * Función para cargar el combo de gestiones para memorándums.
 * @param gestionPredeterminada Gestión predeterminada para
 */
function cargarGestionesMemorandumsParaMovilidad(gestionPredeterminada) {
    $.ajax({
        url: '/relaborales/listgestionesmemorandums',
        type: 'POST',
        datatype: 'json',
        async: false,
        cache: false,
        success: function (data) {
            var res = jQuery.parseJSON(data);
            $('#lstGestionMemorandum').html("");
            $('#lstGestionMemorandum').append("<option value='0'>Seleccionar...</option>");
            if (res.length > 0) {
                $.each(res, function (key, val) {
                    if (gestionPredeterminada == val.gestion) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }
                    $('#lstGestionMemorandum').append("<option value=" + val.gestion + " " + $selected + ">" + val.gestion + "</option>");
                });
            } else $('#lstGestionMemorandum').prop("disabled", "disabled");
        }
    });
}
/**
 * Función para la carga de los combos relacionados a las unidad administrativa a la cual corresponde la asignación de funciones.
 */
function cargarUnidadesOrganizacionalesParaMovilidad(idGerenciaAdministrativaContrato,idGerencia, idDepartamento, idArea) {
    var gerenciasSource =
    {
        dataType: "json",
        async: false,
        dataFields: [
            {name: 'unidad_administrativa'},
            {name: 'id'}
        ],
        url: '/relaborales/listgerencias?id_gerencia='+idGerenciaAdministrativaContrato
    };
    var gerenciasAdapter = new $.jqx.dataAdapter(gerenciasSource);

    var departamentosSource =
    {
        dataType: "json",
        async: false,
        dataFields: [
            {name: 'padre_id'},
            {name: 'id'},
            {name: 'unidad_administrativa'},
        ],
        url: '/relaborales/listdepartamentosadministrativos/'
    };
    var departamentosAdapter = new $.jqx.dataAdapter(departamentosSource);
    var areasSource =
    {
        dataType: "json",
        async: false,
        dataFields: [
            {name: 'padre_id'},
            {name: 'id'},
            {name: 'unidad_administrativa'},
        ],
        url: '/relaborales/listareasadministrativas/'
    };
    var areasAdapter = new $.jqx.dataAdapter(areasSource);


    $("#lstGerenciasAdministrativasMovilidad").jqxComboBox(
        {
            source: gerenciasAdapter,
            width: 300,
            height: 25,
            promptText: "Seleccione una gerencia.",
            displayMember: "unidad_administrativa",
            valueMember: 'id'
        });
    $("#lstDepartamentosAdministrativosMovilidad").jqxComboBox(
        {

            width: 300,
            height: 25,
            disabled: true,
            promptText: "Seleccione Departamento Administrativo.",
            displayMember: 'unidad_administrativa',
            valueMember: 'id'
        });
    $("#lstAreasAdministrativasMovilidad").jqxComboBox(
        {
            width: 300,
            height: 25,
            disabled: true,
            promptText: "Seleccione un Area Administrativa.",
            displayMember: 'unidad_administrativa',
            valueMember: 'id'
        });
    if (idGerencia > 0) {
        $("#lstGerenciasAdministrativasMovilidad").jqxComboBox('selectItem', idGerencia);
        /**
         * Estableciendo el identificador del departamento predeterminado
         */
        $("#lstDepartamentosAdministrativosMovilidad").jqxComboBox({disabled: false, selectedIndex: -1});
        departamentosSource.data = {padre_id: idGerencia};
        departamentosAdapter = new $.jqx.dataAdapter(departamentosSource);
        $("#lstDepartamentosAdministrativosMovilidad").jqxComboBox({source: departamentosAdapter});

        if (idDepartamento > 0) {
            $("#lstDepartamentosAdministrativosMovilidad").jqxComboBox('selectItem', idDepartamento);
            /**
             * Estableciendo el identificador del área predeterminada
             */
            $("#lstAreasAdministrativasMovilidad").jqxComboBox({disabled: false, selectedIndex: -1});
            areasSource.data = {padre_id: idDepartamento};
            areasAdapter = new $.jqx.dataAdapter(areasSource);
            $("#lstAreasAdministrativasMovilidad").jqxComboBox({source: areasAdapter});
            if (idArea > 0) {
                $("#lstAreasAdministrativasMovilidad").jqxComboBox('selectItem', idArea);
            }
        }
    }
    /**
     * Controlando los eventos sucedidos por cambios
     */
    $("#lstGerenciasAdministrativasMovilidad").bind('select', function (event) {
        if (event.args) {
            $("#lstDepartamentosAdministrativosMovilidad").jqxComboBox({disabled: false, selectedIndex: -1});
            var value = event.args.item.value;
            departamentosSource.data = {padre_id: value};
            departamentosAdapter = new $.jqx.dataAdapter(departamentosSource);
            $("#lstDepartamentosAdministrativosMovilidad").jqxComboBox({source: departamentosAdapter});
            /**
             * En caso de que una gerencia tenga un área dependiente
             */
            $("#lstAreasAdministrativasMovilidad").jqxComboBox({disabled: false, selectedIndex: -1});
            var value = event.args.item.value;
            areasSource.data = {padre_id: value};
            areasAdapter = new $.jqx.dataAdapter(areasSource);
            $("#lstAreasAdministrativasMovilidad").jqxComboBox({source: areasAdapter});
        }
    });
    $("#lstDepartamentosAdministrativosMovilidad").bind('select', function (event) {
        if (event.args) {
            $("#lstAreasAdministrativasMovilidad").jqxComboBox({disabled: false, selectedIndex: -1});
            var value = event.args.item.value;
            areasSource.data = {padre_id: value};
            areasAdapter = new $.jqx.dataAdapter(areasSource);
            $("#lstAreasAdministrativasMovilidad").jqxComboBox({source: areasAdapter});
        }
    });
}
/**
 * Función para la especificación de los combos dependientes de país y ciudad.
 * @param idPais Identificador del país.
 * @param idDepartamento Identificador del departamento.
 */
function cargarPaisesCiudadesParaMovilidad(idPais, idDepartamento) {
    var paisesSource =
    {
        dataType: "json",
        method:'post',
        async: false,
        dataFields: [
            {name: 'pais'},
            {name: 'id'}
        ],
        url: '/relaborales/listpaises/'
    };
    var paisesAdapter = new $.jqx.dataAdapter(paisesSource);
    $("#lstPaisesMovilidad").jqxComboBox(
        {
            source: paisesAdapter,
            width: 300,
            height: 25,
            promptText: "Seleccione un país.",
            displayMember: 'pais',
            valueMember: 'id'
        });
    var departamentosSource =
    {
        dataType: "json",
        method:'post',
        async: false,
        dataFields: [
            {name: 'pais_id'},
            {name: 'id'},
            {name: 'departamento'},
        ],
        url: '/relaborales/listdepartamentos/'
    };
    var departamentosAdapter = new $.jqx.dataAdapter(departamentosSource);

    $("#lstCiudadesMovilidad").jqxComboBox(
        {

            width: 300,
            height: 25,
            disabled: true,
            promptText: "Seleccione la ciudad.",
            displayMember: 'departamento',
            valueMember: 'id'
        });
    if (idPais > 0) {
        $("#lstPaisesMovilidad").jqxComboBox('selectItem', idPais);
        /**
         * Controlando si se ha seleccionado una ciudad
         */
        $("#lstCiudadesMovilidad").jqxComboBox({disabled: false, selectedIndex: -1});
        departamentosSource.data = {pais_id: idPais};
        departamentosAdapter = new $.jqx.dataAdapter(departamentosSource);
        $("#lstCiudadesMovilidad").jqxComboBox({source: departamentosAdapter});

        if (idDepartamento > 0) {
            $("#lstCiudadesMovilidad").jqxComboBox('selectItem', idDepartamento);
        }
    }

    /**
     * Controlando los cambios de selector
     */
    $("#lstPaisesMovilidad").bind('select', function (event) {
        if (event.args) {
            $("#lstCiudadesMovilidad").jqxComboBox({disabled: false, selectedIndex: -1});
            var value = event.args.item.value;
            departamentosSource.data = {pais_id: value};
            departamentosAdapter = new $.jqx.dataAdapter(departamentosSource);
            $("#lstCiudadesMovilidad").jqxComboBox({source: departamentosAdapter});
        }
    });
}
/**
 * Función para cargar el combo de ubicaciones por movilidad.
 */
function cargarUbicacionesParaMovilidad(idUbicacion) {
    var ubicacionesSource =
    {
        dataType: "json",
        async: false,
        dataFields: [
            {name: 'ubicacion'},
            {name: 'id'}
        ],
        url: '/relaborales/listubicaciones/'
    };
    var ubicacionesAdapter = new $.jqx.dataAdapter(ubicacionesSource);
    $("#lstUbicacionesMovilidad").jqxComboBox(
        {
            source: ubicacionesAdapter,
            width: 300,
            height: 25,
            promptText: "Seleccione una ubicacion.",
            displayMember: 'ubicacion',
            valueMember: 'id'
        });
    if (idUbicacion > 0) {
        $("#lstUbicacionesMovilidad").jqxComboBox('selectItem', idUbicacion);
    }
}
/**
 * Función para obtener el listado de cargos que se disponen como autocompletables en el campo Cargo.
 * @param cargo
 */
function cargarCargosParaMovilidad(cargo) {
 /* $("#txtCargoMovilidad").jqxInput({
        width: 300,
        height: 35,
        placeHolder: "Introduzca el nombre del cargo."
    });
    var source =
    {   method: 'post',
        datatype: "json",
        async:false,
        datafields: [
            {name: 'cargo'},
        ],
        url: '/relaborales/listnombrecargos/'
    };
    var dataAdapter = new $.jqx.dataAdapter(source);
    $("#txtCargoMovilidad").jqxInput({
        width: 300,
        height: 35,
        source: dataAdapter,
        placeHolder: "Introduzca el nombre del cargo",
        displayMember: "cargo",
        valueMember: "cargo"
    });*/
}
/**
 * Función para validar los datos del formulario de nuevo registro de relación laboral.
 * @returns {boolean} True: La validación fue correcta; False: La validación anuncia que hay errores en el formulario.
 */
function validaFormularioPorRegistroMovilidad() {
    var ok = true;
    var msje = "";
    var idRelaboral = $("#hdnIdRelaboralPorMovilidad").val();
    var idRelaboralMovilidad = $("#hdnIdRelaboralMovilidadModificar").val();
    var idOrganigrama = 0;
    var idUbicacion = 0;
    var idArea = 0;
    var swOrganigrama = 0;
    var swUbicacion = 0;
    var swCargo = 0;
    var swFechaFin = 0;
    $(".msjs-alert").hide();

    limpiarMensajesErrorPorValidacionMovilidad();

    /*var itemTipoMemorandum = $("#lstTipoMemorandum").jqxComboBox('getSelectedItem');*/
    var idTipoMemorandum = $("#lstTipoMemorandum").val();
    var correlativoMemoradundum = $("#txtCorrelativoMemorandum").val();
    var gestionMemorandum = $("#lstGestionMemorandum").val();
    var fechaMem = $('#txtFechaMem').jqxDateTimeInput('getText');
    var idGerencia = $("#lstGerenciasAdministrativasMovilidad").val();
    var idDepartamentoAdministrativo = $("#lstDepartamentosAdministrativosMovilidad").val();
    var idArea = $("#lstAreasAdministrativasMovilidad").val();
    var idUbicacion = $("#lstUbicacionesMovilidad").val();
    var cargo = $("#txtCargoMovilidad").val();
    if(jQuery.type(cargo)=="object"){
        cargo = String(cargo.label);
    }
    var motivo = $("#txtMotivoMovilidad").val();
    var idPais = $("#lstPaisesMovilidad").val();
    var idDepartamento = $("#lstDepartamentosMovilidad").val();
    var lugar = $("#txtLugarMovilidad").val();
    var fechaIni = $('#txtFechaIniMovilidad').jqxDateTimeInput('getText');
    var horaIni = $('#txtHoraIniMovilidad').val();
    var fechaFin = $('#txtFechaFinMovilidad').jqxDateTimeInput('getText');
    var horaFin = $('#txtHoraFinMovilidad').val();

    idPais = parseInt(idPais);
    idDepartamento = parseInt(idDepartamento);
    if (isNaN(idPais))idPais = 0;
    if (isNaN(idDepartamento))idDepartamento = 0;
    idGerencia = parseInt(idGerencia);
    idDepartamentoAdministrativo = parseInt(idDepartamentoAdministrativo);
    idArea = parseInt(idArea);
    idUbicacion = parseInt(idUbicacion);
    if (idGerencia != null && idGerencia != undefined) {
        swOrganigrama = 1;
        if (!isNaN(idGerencia)) {
            idOrganigrama = idGerencia;
        }
        if (!isNaN(idDepartamentoAdministrativo)) {
            idOrganigrama = idDepartamentoAdministrativo;
        }
    }
    var enfoque = null;
    if (idRelaboral <= 0) {
        ok = false;
        var msje = "Existe un error en la especificación del registro de relación laboral. Comuniquese con el Administrador del Sistema.";
        $("#divMsjePorError").html("");
        $("#divMsjePorError").append(msje);
        $("#divMsjeNotificacionError").jqxNotification("open");
    }
    if (idTipoMemorandum == 0) {
        ok = false;
        var msje = "Debe introducir el tipo de Memor&aacute;ndum necesariamente.";
        $("#divTiposMemorandums").addClass("has-error");
        $("#helpErrorTiposMemorandums").html(msje);
        if (enfoque == null)enfoque = $("#lstTipoMemorandum");
    }
    else {
        var id_agraupado = $("#lstTipoMemorandum").val();
        var arr = id_agraupado.split("-");
        var ff = arr[1];
        /*Requerir fecha de finalización*/
        var hf = arr[2];
        /*Requerir hora de finalización*/
        var cc = arr[3];
        /*Requerir cargo*/
        var oo = arr[4];
        /*Requerir unidad organizacional*/
        var uu = arr[5];
        /*Requerir ubicación*/
        var mm = arr[6];
        /*Requerir motivo*/
        var pp = arr[7];
        /*Requerir pais*/
        var dd = arr[8];
        /*Requerir departamento o ciudad*/
        var ll = arr[9];
        /*Requerir lugar del evento*/
        /*
         * Se evalua en función del tipo de memorándum seleccionado los datos requeridos.
         */
        if (ff == 1)swFechaFin = 1;
        /*
         Se evalua si el tipo de memorándum establece el requerimiento de la fecha de finalización.
         */
        if (ff == 1 && fechaFin == '') {
            ok = false;
            var msje = "Debe introducir la fecha de finalizaci&oacute;n de aplicaci&oacute;n de la movilidad de personal necesariamente.";
            $("#divFechasFinMovilidad").show();
            $("#divFechasFinMovilidad").addClass("has-error");
            $("#helpErrorFechasFinMovilidad").html(msje);
            if (enfoque == null)enfoque = $("#txtFechaFinMovilidad");
        }
        if (horaIni != '' && fechaIni == '') {
            ok = false;
            var msje = "Si desea registrar una hora de inicio debe especificar la fecha de inicio necesariamente.";
            $("#divHorasIniMovilidad").show();
            $("#divHorasIniMovilidad").addClass("has-error");
            $("#helpErrorHorasIniMovilidad").html(msje);
            if (enfoque == null)enfoque = $("#txtHoraIniMovilidad");
        }
        if (horaFin != '' && fechaFin == '') {
            ok = false;
            var msje = "Si desea registrar una hora de finalizaci&oacute;n debe especificar la fecha de finalizaci&oacute;n necesariamente.";
            $("#divHorasFinMovilidad").show();
            $("#divHorasFinMovilidad").addClass("has-error");
            $("#helpErrorHorasFinMovilidad").html(msje);
            if (enfoque == null)enfoque = $("#txtHoraFinMovilidad");
        }
        if (cc == 1 && cargo == '') {
            ok = false;
            var msje = "Debe introducir la asignaci&oacute;n del cargo necesariamente.";
            $("#divCargosMovilidad").addClass("has-error");
            $("#helpErrorCargosMovilidad").html(msje);
            if (enfoque == null)enfoque = $("#txtCargoMovilidad");
        }
        if (oo == 1 && idOrganigrama == 0) {
            ok = false;
            var msje = "Debe seleccionar una Gerencia y/o Departamento para el registro requerido.";
            $("#divGerenciasAdministrativasMovilidad").addClass("has-error");
            $("#helpErrorGerenciasAdministrativasMovilidad").html(msje);
            if (enfoque == null)enfoque = $("#lstGerenciasAdministrativasMovilidad");
        }
        if (mm == 1 && motivo == '') {
            ok = false;
            var msje = "Debe registrar un motivo para la designaci&oacute;n.";
            $("#divMotivosMovilidad").addClass("has-error");
            $("#helpErrorMotivosMovilidad").html(msje);
            if (enfoque == null)enfoque = $("#txtMotivoMovilidad");
        }
        /*
         Se oculta temporalmente
         if(pp==1&&idPais==0){
         var msje = "Debe seleccionar un pa&iacute;s necesariamente.";
         $("#divPaisesMovilidad").addClass("has-error");
         $("#helpErrorPaisesMovilidad").html(msje);
         if(enfoque==null)enfoque =$("#lstPaisesMovilidad");
         }
         if(dd==1&&idDepartamento==0){
         var msje = "Debe registrar una ciudad necesariamente.";
         $("#divCiudadesMovilidad").addClass("has-error");
         $("#helpErrorCiudadesMovilidad").html(msje);
         if(enfoque==null)enfoque =$("#lstCiudadMovilidad");
         }*/
        if (ll == 1 && lugar == '') {
            var msje = "Debe registrar un lugar para la designaci&oacute;n.";
            $("#divLugarMovilidad").addClass("has-error");
            $("#helpErrorLugarMovilidad").html(msje);
            if (enfoque == null)enfoque = $("#txtLugarMovilidad");
        }
    }
    if (correlativoMemoradundum == '' || gestionMemorandum == 0) {
        ok = false;
        var msje = "";
        $("#divCorrelativosMemorandums").addClass("has-error");
        if (correlativoMemoradundum == '') {
            msje = "Debe introducir el correlativo del Memor&aacute;ndum necesariamente.";
            if (enfoque == null)enfoque = $("#txtCorrelativoMemorandum");
        }
        if (gestionMemorandum == 0) {
            if (msje != "")msje += "<br>";
            msje += "Debe seleccionar la gesti&oacute;n del memor&aacute;ndum necesariamente.";
            if (enfoque == null)enfoque = $("#lstGestionMemorandum");
        }
        $("#helpErrorCorrelativosMemorandums").html(msje);
    }
    if (fechaMem == '') {
        ok = false;
        var msje = "Debe introducir la fecha de emisi&oacute;n del memor&aacute;ndum necesariamente.";
        $("#divFechasMemorandums").addClass("has-error");
        $("#helpErrorFechasMemorandums").html(msje);
        if (enfoque == null)enfoque = $("#txtFechaMem");
    }
    if (fechaIni == '') {
        ok = false;
        var msje = "Debe introducir la fecha de inicio de aplicaci&oacute;n de la movilidad de personal necesariamente.";
        $("#divFechasIniMovilidad").addClass("has-error");
        $("#helpErrorFechasIniMovilidad").html(msje);
        if (enfoque == null)enfoque = $("#txtFechaIniMovilidad");
    }
    var sep = '-';
    if (procesaTextoAFecha(fechaIni, sep) < procesaTextoAFecha(fechaMem, sep)) {
        ok = false;
        msje = "La fecha de inicio debe ser igual o superior a la fecha del memor&aacute;ndum.";
        $("#divFechasIniMovilidad").addClass("has-error");
        $("#divFechasMemorandums").addClass("has-error");
        $("#helpErrorFechasIniMovilidad").html(msje);
        $("#helpErrorFechasMemorandums").html(msje);
        if (enfoque == null)enfoque = $("#txtFechaIniMovilidad");
    }
    if (swFechaFin == 1) {
        if (procesaTextoAFecha(fechaFin, sep) < procesaTextoAFecha(fechaIni, sep)) {
            ok = false;
            msje = "La fecha de finalizaci&oacute;n debe ser igual o superior a la fecha de inicio.";
            $("#divFechasIniMovilidad").addClass("has-error");
            $("#divFechasFinMovilidad").addClass("has-error");
            $("#helpErrorFechasIniMovilidad").html(msje);
            $("#helpErrorFechasFinMovilidad").html(msje);
            if (enfoque == null)enfoque = $("#txtFechaIniMovilidad");
        }
    }
    if (enfoque != null) {
        enfoque.focus();
    }
    return ok;
}
/**
 * Función para la validación del formulario de baja.
 * @returns {boolean}
 */
function validaFormularioPorBajaRegistroMovilidad() {
    var ok = true;
    var msje = "";
    var idRelaboral = $("#hdnIdRelaboralPorMovilidad").val();
    var idRelaboralMovilidad = $("#hdnIdRelaboralMovilidadBaja").val();
    $(".msjs-alert").hide();

    limpiarMensajesErrorPorValidacionMovilidad();

    var fechaMem = $("#txtFechaMem").val();
    var fechaIni = $("#txtFechaIniMovilidad").val();
    var fechaFin = $('#txtFechaFinMovilidad').jqxDateTimeInput('getText');

    var enfoque = null;
    if (idRelaboral <= 0) {
        ok = false;
        var msje = "Existe un error en la especificación del registro de relación laboral. Comuniquese con el Administrador del Sistema.";
        $("#divMsjePorError").html("");
        $("#divMsjePorError").append(msje);
        $("#divMsjeNotificacionError").jqxNotification("open");
    }
    var sep = '-';
    if (fechaFin == '') {
        ok = false;
        var msje = "Debe introducir la fecha de finalizaci&oacute;n de aplicaci&oacute;n de la movilidad de personal necesariamente.";
        $("#divFechasFinMovilidad").show();
        $("#divFechasFinMovilidad").addClass("has-error");
        $("#helpErrorFechasFinMovilidad").html(msje);
        if (enfoque == null)enfoque = $("#txtFechaFinMovilidad");
    }else {
        if (procesaTextoAFecha(fechaFin, sep) < procesaTextoAFecha(fechaMem, sep)) {
            ok = false;
            msje = "La fecha de inicio debe ser igual o superior a la fecha del memor&aacute;ndum.";
            $("#divFechasFinMovilidad").addClass("has-error");
            $("#helpErrorFechasFinMovilidad").html(msje);
            if (enfoque == null)enfoque = $("#txtFechaFinMovilidad");
        }
        if (procesaTextoAFecha(fechaFin, sep) < procesaTextoAFecha(fechaIni, sep)) {
            ok = false;
            msje = "La fecha de finalizaci&oacute;n debe ser igual o superior a la fecha de inicio.";
            $("#divFechasFinMovilidad").addClass("has-error");
            $("#helpErrorFechasFinMovilidad").html(msje);
            if (enfoque == null)enfoque = $("#txtFechaFinMovilidad");
        }
    }
    if (enfoque != null) {
        enfoque.focus();
    }
    return ok;
}
/**
 * Función para la limpieza de los mensajes de error debido a la validación del formulario para registro de movilidad de personal.
 */
function limpiarMensajesErrorPorValidacionMovilidad() {
    $("#divTiposMemorandums").removeClass("has-error");
    $("#divFechasMemorandums").removeClass("has-error");
    $("#divCorrelativosMemorandums").removeClass("has-error");
    $("#divGerenciasAdministrativasMovilidad").removeClass("has-error");
    $("#divDepartamentosAdministrativosMovilidad").removeClass("has-error");
    $("#divAreasAdministrativasMovilidad").removeClass("has-error");
    $("#divUbicacionesMovilidad").removeClass("has-error");
    $("#divLugaresMovilidad").removeClass("has-error");
    $("#divCargosMovilidad").removeClass("has-error");
    $("#divFechasIniMovilidad").removeClass("has-error");
    $("#divFechasFinMovilidad").removeClass("has-error");

    $("#helpErrorTiposMemorandums").html("");
    $("#helpErrorCorrelativosMemorandums").html("");
    $("#helpErrorFechasMemorandums").html("");
    $("#helpErrorGerenciasAdministrativasMovilidad").html("");
    $("#helpErrorDepartamentosAdministrativosMovilidad").html("");
    $("#helpErrorAreasAdministrativasMovilidad").html("");
    $("#helpErrorUbicacionesMovilidad").html("");
    $("#helpErrorLugaresMovilidad").html("");
    $("#helpErrorCargosMovilidad").html("");
    $("#helpErrorFechasIniMovilidad").html("");
    $("#helpErrorFechasFinMovilidad").html("");
}
/**
 * Función para el registro de la movilidad de personal.
 */
function guardarRegistroMovilidad() {
    var ok = false;
    var swCargo = 0;
    var idRelaboral = $("#hdnIdRelaboralPorMovilidad").val();
    var idRelaboralMovilidad = $("#hdnIdRelaboralMovilidadModificar").val();
    var idMemorandum = 0;
    var idOrganigrama = 0;
    var idUbicacion = 0;
    var idArea = 0;
    var swOrganigrama = 0;
    var swUbicacion = 0;
    var swCargo = 0;
    var chAi = 0;
    if ($("#chkAi").is(':checked')) {
        chAi = 1;
    }
    var idff = $("#lstTipoMemorandum").val();
    var arr = idff.split("-");
    var idTipoMemorandum = arr[0];
    var swFechaFin = arr[1];
    var swHoraFin = arr[2];
    var swCargo = arr[3];
    var swOrganigrama = arr[4];
    var swUbicacion = arr[5];

    var correlativoMemorandum = $("#txtCorrelativoMemorandum").val();
    var gestionMemorandum = $("#lstGestionMemorandum").val();
    var fechaMem = $('#txtFechaMem').jqxDateTimeInput('getText');
    var idGerencia = $("#lstGerenciasAdministrativasMovilidad").val();
    var idDepartamentoAdministrativo = $("#lstDepartamentosAdministrativosMovilidad").val();
    var idArea = $("#lstAreasAdministrativasMovilidad").val();
    var idUbicacion = $("#lstUbicacionesMovilidad").val();
    var asignacionCargo = $("#txtCargoMovilidad").val();
    if(jQuery.type(asignacionCargo)=="object"){
        asignacionCargo = String(asignacionCargo.label);
    }
    asignacionCargo = asignacionCargo+'';
    if (asignacionCargo != '' && chAi == 1) {
        /**
         * Si el cargo menciona la palabra a.i. no se hace nada
         */
        var n = asignacionCargo.indexOf("a.i.");
        if (n < 0) {
            asignacionCargo += " a.i.";
        }
    }else if(asignacionCargo != '' && chAi == 0){
        /**
         * Si el cargo menciona la palabra a.i. se le quita
         */
        var n = asignacionCargo.indexOf("a.i.");
        if (n > 0) {
            asignacionCargo.replace("a.i.", "");
        }
    }
    var motivo = $("#txtMotivoMovilidad").val();
    var idPais = $("#lstPaisesMovilidad").val();
    var idDepartamento = $("#lstCiudadesMovilidad").val();
    var lugar = $("#txtLugarMovilidad").val();
    var fechaIni = $('#txtFechaIniMovilidad').jqxDateTimeInput('getText');
    var horaIni = $('#txtHoraIniMovilidad').val();
    var fechaFin = $('#txtFechaFinMovilidad').jqxDateTimeInput('getText');
    var horaFin = $('#txtHoraFinMovilidad').val();
    var observacion = $("#txtObservacionMovilidad").val();

    idPais = parseInt(idPais);
    idDepartamento = parseInt(idDepartamento);
    if (isNaN(idPais))idPais = 0;
    if (isNaN(idDepartamento))idDepartamento = 0;
    idGerencia = parseInt(idGerencia);
    idDepartamentoAdministrativo = parseInt(idDepartamentoAdministrativo);
    idArea = parseInt(idArea);
    idUbicacion = parseInt(idUbicacion);
    if (idGerencia != null && idGerencia != undefined) {
        if (!isNaN(idGerencia)) {
            idOrganigrama = idGerencia;
        }
        if (!isNaN(idDepartamentoAdministrativo)) {
            idOrganigrama = idDepartamentoAdministrativo;
        }
    }
    if (idOrganigrama == 0) {
        /**
         * En caso de que se haya seleccionado el cargo superior y no se haya especificado Gerencia, Departamento ni área
         * se establece el valor de acuerdo al id_organigrama del cargo del jefe
         */
        if ($("#hdnIdOrganigramaPorSeleccionCargoSuperior").val() > 0)
            idOrganigrama = $("#hdnIdOrganigramaPorSeleccionCargoSuperior").val();
    }
    if (isNaN(idArea)) {
        idArea = 0;
    }
    if (!isNaN(idUbicacion)) {
        idUbicacion = idUbicacion;
        swUbicacion = 1;
    } else {
        /*
         * En caso de que se haya seleccionado el cargo superior y no se haya especificado la ubicación
         * se establece el valor de acuerdo al lugar donde esta situado del cargo del jefe
         */
        if ($("#hdnIdOrganigramaPorSeleccionCargoSuperior").val() > 0) {
            idUbicacion = -1;
        }
        else idUbicacion = 0;

    }
    if (idRelaboralMovilidad > 0) {
        idMemorandum = $("#hdnIdMemorandumMovilidadModificar").val();
    }
    if (swFechaFin == 0) {
        fechaFin = '';
    }
    if (idRelaboral > 0 && idTipoMemorandum > 0 && correlativoMemorandum != '' && gestionMemorandum > 0 && fechaMem != '' && fechaIni != '') {
        $.ajax({
            url: '/relaborales/savemovilidad/',
            type: "POST",
            datatype: 'json',
            async: false,
            cache: false,
            data: {
                id: idRelaboralMovilidad,
                id_relaboral: idRelaboral,
                id_da: 0,
                id_regional: 0,
                id_organigrama: idOrganigrama,
                id_area: idArea,
                id_ubicacion: idUbicacion,
                cargo: asignacionCargo,
                id_evento: 0,
                motivo: motivo,
                id_pais: idPais,
                id_departamento: idDepartamento,
                lugar: lugar,
                id_memorandum: idMemorandum,
                id_tipomemorandum: idTipoMemorandum,
                correlativo: correlativoMemorandum,
                gestion: gestionMemorandum,
                fecha_mem: fechaMem,
                contenido: '',
                fecha_ini: fechaIni,
                hora_ini: horaIni,
                fecha_fin: fechaFin,
                hora_fin: horaFin,
                observacion: observacion
            },
            success: function (data) {  //alert(data);
                var res = jQuery.parseJSON(data);
                /**
                 * Si se ha realizado correctamente el registro de la relación laboral y la movilidad
                 */
                $(".msjes").hide();
                if (res.result == 1) {
                    ok = true;
                    $("#jqxgridmovilidad").jqxGrid("updatebounddata");
                    $("#divMsjePorSuccess").html("");
                    $("#divMsjePorSuccess").append(res.msj);
                    $("#divMsjeNotificacionSuccess").jqxNotification("open");
                    /*Es necesario actualizar la grilla principal debido a que este debe mostrar los datos de acuerdo a la última movilidad de personal*/
                    $("#jqxgrid").jqxGrid('beginupdate');
                } else if (res.result == 0) {
                    /**
                     * En caso de presentarse un error subsanable
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
                $("#divMsjePorError").html("");
                $("#divMsjePorError").append("Se ha producido un error Inesperado");
                $("#divMsjeNotificacionError").jqxNotification("open");
            }
        });
    }
    return ok;
}
/**
 * Función para el registro de la baja de movilidad de personal.
 */
function bajaRegistroMovilidad() {
    var ok = false;
    var swCargo = 0;
    var idRelaboral = $("#hdnIdRelaboralPorMovilidad").val();
    var idRelaboralMovilidad = $("#hdnIdRelaboralMovilidadBaja").val();
    var fechaFin = $('#txtFechaFinMovilidad').jqxDateTimeInput('getText');
    var horaFin = $('#txtHoraFinMovilidad').val();
    var observacion = $("#txtObservacionMovilidad").val();

    if (idRelaboral > 0 && idRelaboralMovilidad > 0 && fechaFin != '') {
        $.ajax({
            url: '/relaborales/downmovilidad/',
            type: "POST",
            datatype: 'json',
            async: false,
            cache: false,
            data: {
                id: idRelaboralMovilidad,
                id_relaboral: idRelaboral,
                fecha_fin: fechaFin,
                hora_fin: horaFin,
                observacion: observacion
            },
            success: function (data) {  //alert(data);
                var res = jQuery.parseJSON(data);
                /**
                 * Si se ha realizado correctamente el registro de la relación laboral y la movilidad
                 */
                $(".msjes").hide();
                if (res.result == 1) {
                    ok = true;
                    $("#jqxgridmovilidad").jqxGrid("updatebounddata");
                    $("#divMsjePorSuccess").html("");
                    $("#divMsjePorSuccess").append(res.msj);
                    $("#divMsjeNotificacionSuccess").jqxNotification("open");
                } else if (res.result == 0) {
                    /**
                     * En caso de presentarse un error subsanable
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
    }
    return ok;
}
/**
 * Función para obtener registro correspondiente al cargo del inmediato superior considerando el identificador de la relación laboral.
 * @param idRelaboral
 */
function obtieneCargoInmediatoSuperior(idRelaboral) {
    if (idRelaboral > 0) {
        var resultado = $.ajax({
            url: '/relaborales/getcargosuperiorrelaboral',
            type: 'POST',
            datatype: 'json',
            async: false,
            cache: false,
            data: {id: idRelaboral},
            success: function (data) {
            }
        }).responseText;
        var res = jQuery.parseJSON(resultado);

        if (res.cargo != null && res.cargo != '') {
            if (confirm("Desea usar el cargo '" + res.cargo + "' del inmediato superior \nen la asignacion de funciones?")) {
                $("#txtCargoMovilidad").val(res.cargo+" a.i.");
                $("#chkAi").prop("checked", "checked");
                /*
                 Si se ha seleccionado el cargo del nivel superior se debería asignar de igual modo el organigrama del jefe para la asignación de funciones.
                 */
                $("#hdnIdOrganigramaPorSeleccionCargoSuperior").val(res.organigrama_id);
            } else {
                $("#txtCargoMovilidad").val("");
                $("#chkAi").attr("checked", false);
            }
        }
    }
}
/**
 * Función para la eliminación del registro de movilidad de personal.
 * @param idRelaboralMovilidad
 */
function eliminarRegistroMovilidad(idRelaboralMovilidad){
    var resultado = 0;
    if(idRelaboralMovilidad>0){
        resultado = $.ajax({
            url: '/relaborales/delmovilidad',
            type: 'POST',
            datatype: 'json',
            async: false,
            cache: false,
            data: {id: idRelaboralMovilidad},
            success: function (data) {  //alert(data);
            var res = jQuery.parseJSON(data);
            /**
             * Si se ha realizado correctamente el registro de la relación laboral y la movilidad
             */
            $(".msjes").hide();
            if (res.result == 1) {
                $("#divMsjePorSuccess").html("");
                $("#divMsjePorSuccess").append(res.msj);
                $("#divMsjeNotificacionSuccess").jqxNotification("open");
                /*Es necesario actualizar la grilla principal debido a que este debe mostrar los datos de acuerdo a la última movilidad de personal*/
                $("#jqxgridmovilidad").jqxGrid("updatebounddata");
                return true;
            } else if (res.result == 0) {
                /**
                 * En caso de presentarse un error subsanable
                 */
                $("#divMsjePorWarning").html("");
                $("#divMsjePorWarning").append(res.msj);
                $("#divMsjeNotificacionWarning").jqxNotification("open");
                return false;
            } else {
                /**
                 * En caso de haberse presentado un error crítico al momento de registrarse la relación laboral
                 */
                $("#divMsjePorError").html("");
                $("#divMsjePorError").append(res.msj);
                $("#divMsjeNotificacionError").jqxNotification("open");
                return false;
            }

        }, //mostramos el error
        error: function () {
            $("#divMsjePorError").html("");
            $("#divMsjePorError").append("Se ha producido un error Inesperado");
            $("#divMsjeNotificacionError").jqxNotification("open");
            return false;
        }
        }).responseText;
    }
    return resultado;
}