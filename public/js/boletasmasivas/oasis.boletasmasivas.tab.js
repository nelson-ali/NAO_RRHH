/*
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  02-03-2015
 */
$(document).ready(function () {
    $.jqx.theme = "bootstrap";
    var theme = prepareSimulator("tabs");
    $('#divTabControlMarcaciones').jqxTabs({ theme:theme,height: '100%', width: '100%',  keyboardNavigation: false });
});

