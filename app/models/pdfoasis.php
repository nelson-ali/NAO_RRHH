<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  01-12-2014
*/
require_once('../app/libs/qrlib/qrlib.php');

class pdfoasis extends fpdf
{
    public $title_rpt = '';
    public $header_title_estado_rpt = 'ESTADO PLURINACIONAL DE BOLIVIA';
    public $header_title_empresa_rpt = 'Empresa Estatal de Transporte por Cable "Mi Teleferico"';
    public $style_header_table = '';
    public $style_footer_table = '';
    public $nombre_solicitante = '';
    var $debug;              //Valor de seguimiento 1: Hacer debug; 0: No hacer debug
    var $widths;             //Array de anchuras
    var $totalWidths;             //Array de anchuras
    var $aligns;             //Array de alineaciones
    var $totalAligns;             //Array de alineaciones
    var $pageWidth;          //Ancho de la hoja (Sea si esta vertical u horizontal)
    var $totalPageWidth;          //Ancho de la hoja (Sea si esta vertical u horizontal)
    var $tableWidth;         //Ancho de la tabla
    var $totalTableWidth;         //Ancho de la tabla
    var $FechaHoraReporte;     //Fecha y hora del reporte
    var $FechaHoraCreacion;     //Fecha y hora de creación
    var $IdUsrPrint;         //Identificador del usuario impresor
    var $ContadorFilas;         //Contador de filas
    var $PrimerNumero;         //Primer número de la lista en la página
    var $UltimoNumero;         //Último número de la lista en la página
    var $TotalNumeros;         //Total de números de la lista
    var $ShowLeftFooter;     //Opción para mostrar el pie de página izquierdo.
    var $ShowNumeralLeftFooter;    //Opción para mostrar el pie de página izquierdo (Numeral).
    var $Condicion;            //Opción para conocer que tipo de condición tiene un determinado formulario
    var $angle = 0;             //Angulo
    var $generalConfigForAllColumns;    //Array multidimencional con todas las configuraciones necesarias para el despliegue de valores
    var $widthsSelecteds;       //Anchuras seleccionadas
    var $totalWidthsSelecteds;  //Anchuras seleccionadas para la grilla de totales
    var $colTitleSelecteds;     //Titulos de las columnas seleccionadas
    var $totalColTitleSelecteds;  //Titulos de las columnas seleccionadas para la grilla de totales
    var $alignSelecteds;        //Alineaciones de la columnas seleccionadas
    var $totalAlignSelecteds;   //Alineaciones de la columnas seleccionadas para la grilla de totales
    var $alignTitleSelecteds;    //Alineaciones de las cabeceras seleccionadas

    function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1)
            $x = $this->x;
        if ($y == -1)
            $y = $this->y;
        if ($this->angle != 0)
            $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5f %.5f %.5f %.5f %.2f %.2f cm 1 0 0 1 %.2f %.2f cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    function RotatedText($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    function RotatedImage($file, $x, $y, $w, $h, $angle)
    {
        //Image rotated around its upper-left corner
        $this->Rotate($angle, $x, $y);
        $this->Image($file, $x, $y, $w, $h);
        $this->Rotate(0);
    }

    /**
     * Función para establecer la cabecera del reporte.
     */
    function Header()
    {
        $this->Image('../public/images/men_00.jpg', 90, 5, 45, 0);
        $x_segunda_imagen = $this->pageWidth - 203;
        $this->Image('../public/images/men_01.png', $x_segunda_imagen, 10, 30, 0);
        $this->SetFont('Arial', 'B', 10);
        $west = $this->GetStringWidth($this->header_title_estado_rpt) + 6;
        $wemp = $this->GetStringWidth($this->header_title_empresa_rpt) + 6;
        $this->SetX(($this->pageWidth - $west) / 2);
        $this->SetDrawColor(247, 249, 251);
        $this->SetFillColor(247, 249, 251);
        $this->SetTextColor(79, 129, 189);
        $this->SetFont('Arial', '', 13);
        //$this->Cell($west + 15, 5, $this->header_title_estado_rpt, 1, 1, 'C');
        $this->SetX(($this->pageWidth - $wemp) / 2);
        $this->SetFont('Arial', 'B', 11);
        //$this->Cell($wemp + 15, 5, $this->header_title_empresa_rpt, 1, 1, 'C');
        /**
         * Espacio para definir la línea en el encabezado
         */
        $this->SetFillColor(79, 129, 189);
        $this->SetLineWidth(1);
        $this->SetX(($this->pageWidth - $wemp) / 2);
        //$this->Cell($wemp + 15, 2, "", 1, 1, 'C', 1);

        if ($this->title_rpt != "") {
            $w = $this->GetStringWidth($this->title_rpt) + 6;
            $this->SetX(($this->pageWidth - $w) / 2);
            $this->Cell($w + 3, 35, $this->title_rpt, 0, 1, 'C');
        }

        $this->Ln();

        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        /**
         * Añadido
         */
        $this->SetY(30);
        if (($this->pageWidth - $this->tableWidth) > 0) $this->SetX(($this->pageWidth - $this->tableWidth) / 2);
        $this->SetWidths($this->widthsSelecteds);
        $this->DefineColorHeaderTable();
        $this->SetAligns($this->alignTitleSelecteds);
        $this->RowTitle($this->colTitleSelecteds);
        if (($this->pageWidth - $this->tableWidth) > 0) $this->SetX(($this->pageWidth - $this->tableWidth) / 2);
    }

    /**
     * Cabecera de totales.
     */
    function HeaderTotal()
    {
        $this->Image('../public/images/escudo.jpg', 10, 6, 20, 0);
        $x_segunda_imagen = $this->pageWidth - 25;
        $this->Image('../public/images/logoVB.jpg', $x_segunda_imagen, 6, 15, 0);
        $this->SetFont('Arial', 'B', 10);
        $west = $this->GetStringWidth($this->header_title_estado_rpt) + 6;
        $wemp = $this->GetStringWidth($this->header_title_empresa_rpt) + 6;
        $this->SetX(($this->pageWidth - $west) / 2);
        $this->SetDrawColor(247, 249, 251);
        $this->SetFillColor(247, 249, 251);
        $this->SetTextColor(79, 129, 189);
        $this->SetFont('Arial', '', 13);
        $this->Cell($west + 15, 5, $this->header_title_estado_rpt, 1, 1, 'C');
        $this->SetX(($this->pageWidth - $wemp) / 2);
        $this->SetFont('Arial', '', 9);
        $this->Cell($wemp + 15, 5, $this->header_title_empresa_rpt, 1, 1, 'C');
        /**
         * Espacio para definir la línea en el encabezado
         */
        $this->SetFillColor(79, 129, 189);
        $this->SetLineWidth(1);
        $this->SetX(($this->pageWidth - $wemp) / 2);
        $this->Cell($wemp + 15, 2, "", 1, 1, 'C', 1);

        if ($this->title_total_rpt != "") {
            $w = $this->GetStringWidth($this->title_total_rpt) + 6;
            $this->SetX(($this->pageWidth - $w) / 2);
            $this->Cell($w + 15, 5, $this->title_total_rpt, 1, 1, 'C');
        }

        $this->Ln();

        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        /**
         * Añadido
         */
        $this->SetY(30);
    }

    /**
     * Función para definir el color de las celdas en la cabecera de la tabla.
     */
    function DefineColorHeaderTable()
    {
        $this->SetFont('Arial', 'B', 8);
        $this->SetDrawColor(0);
        $this->SetFillColor(52, 152, 219);//Fondo celeste
        $this->SetTextColor(255, 255, 255);//Letras blancas
        $this->SetLineWidth(.3);
    }

    /**
     * Función para definir el color de las celdas en el cuerpo de la tabla.
     */
    function DefineColorBodyTable()
    {
        $this->SetFont('Arial', '', 7);
        $this->SetDrawColor(0, 0, 0);
        $this->SetFillColor(255, 255, 255);//Fondo blanco
        $this->SetTextColor(0, 0, 0);//Letras negras
    }

    /**
     * Función para establecer el cuerpo del reporte en la versión 2.0 del formulario.
     * @param $tempDir
     * @param $objR
     * @param $objCe
     * @param $objCorr
     * @param $objExcRip
     */
    function Body($tempDir, $objR, $objCe, $objCorr, $objExcRip)
    {
        $this->SetTextColor(0, 0, 0);

        $this->SetY(29);
        $this->SetX(180);

        $this->SetTextColor(0, 0, 0);
        $this->SetY(30);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(38, 5, "Nombre del Trabajador(a):", '', 0, 'L', false);
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, utf8_decode($objR->nombres), '', 0, 'L', false);

        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "C.I.:", '', 0, 'R', false);
        $this->SetFont('Arial', '', 8);
        $this->Cell(38, 5, $objR->ci . " " . $objR->expd, '', 0, 'L', false);

        $this->SetY(35);
        $this->SetFont('Arial', 'B', 8);
        //$this->Cell(38, 5, "- ", '', 0, 'L', false);
        $this->SetFont('Arial', '', 8);
        $gerenciaAdministrativa = str_replace("GERENCIA DE ", "", $objR->gerencia_administrativa);
        //$this->Cell(38, 5, utf8_decode($gerenciaAdministrativa), '', 0, 'L', false);

        $this->SetFont('Arial', 'B', 8);
        //$this->Cell(25, 5, "- ", '', 0, 'L', false);
        $this->SetFont('Arial', '', 8);
        $departamentoAdministrativo = str_replace("DEPARTAMENTO DE ", "", $objR->departamento_administrativo);
        $this->Cell(38, 5, utf8_decode($objR->cargo) ." - ". utf8_decode($departamentoAdministrativo !== '' ? $departamentoAdministrativo : ""), 0, 'L', false);

        $this->SetY(42);

        $this->SetFont('Arial', 'B', 8);
        $lblFecha = "";
        $fecha = "";
        if ($objCe->fecha_ini == $objCe->fecha_fin) {
            $lblFecha = "Fecha:";
            $fecha = $objCe->fecha_ini != "" ? date("d-m-Y", strtotime($objCe->fecha_ini)) : " ";
        } else {
            $lblFecha = "Fechas:";
            $fecha = date("d-m-Y", strtotime($objCe->fecha_ini)) . " AL " . date("d-m-Y", strtotime($objCe->fecha_fin));
        }
        $this->Cell(38, 5, $lblFecha, '', 0, 'R', false);
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, $fecha, '', 0, 'L', false);

        if ($objCe->horario == 1) {
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(11, 5, "Salida:", '', 0, 'R', false);
            $this->SetFont('Arial', '', 8);
            $this->Cell(16, 5, $objCe->hora_ini != "" ? date("H:i", strtotime($objCe->hora_ini)) : "", 'LTRB', 0, 'C', false);

            $this->SetFont('Arial', 'B', 8);
            $this->Cell(50, 5, "Retorno:", '', 0, 'R', false);
            $this->SetFont('Arial', '', 8);
            $this->Cell(16, 5, $objCe->hora_fin != "" ? date("H:i", strtotime($objCe->hora_fin)) : "", 'LRTB', 0, 'C', false);
        }
        /**
         * Segunda parte: Ckecs
         */

        $this->SetY(48);
        $this->SetFillColor(0, 0, 0); // Set background colour to black
        $this->SetFont('Arial', 'B', 8);
        $this->SetDrawColor(0, 0, 0); // Set the border colour to Aqua
        $this->SetFillColor(191, 191, 191);
        $this->MultiCell(196, 4, "TIPO DE PERMISO", 1, 'C', true);

        $this->SetY(57);
        $permisoParticularOk = "";
        $licenciaOk = "";
        $comisionOk = "";
        $consultaMedicaOk = "";
        $otrasExcepcionesOk = "";
        $justificacionRip = "";
        $otrasExcepciones = "";
        switch ($objCe->tipoexcepcion_id) {
            case 1:
                $permisoParticularOk = "X";
                break;
            case 2:
                $comisionOk = "X";
                break;
            case 3:
                $licenciaOk = "X";
                break;
            case 4:
                $consultaMedicaOk = "X";
                break;
            case 5:
                $otrasExcepcionesOk = "X";
                break;
        }
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 5, "Comision:", '', 0, 'R', false);
        $this->Cell(20, 5, $comisionOk, 'LRTB', 0, 'C', false);

        $this->SetY(56);
        $this->SetX(51);
        $this->SetFont('Arial', 'B', 8);
        //$this->Cell(45, 5, "Permiso Particular o Licencia:", 0, 0, 'R', false);
        $this->MultiCell(28, 3, utf8_decode("Permiso Particular o Licencia:"), 0, 'R', false);
        $this->SetY(57);
        $this->SetX(79);
        $this->Cell(20, 5, $permisoParticularOk . $licenciaOk, 'LTRB', 0, 'C', false);

        $this->SetY(57);
        $this->SetX(106);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, utf8_decode("Consulta Médica:"), '', 0, 'R', false);
        $this->Cell(20, 5, $consultaMedicaOk, 'LRTB', 0, 'C', false);

        $this->SetY(57);
        $this->SetX(155);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(31, 5, "Otras Excepciones:", '', 0, 'R', false);
        $this->Cell(20, 5, $otrasExcepcionesOk, 'LRTB', 0, 'C', false);

        /**
         * Tercera parte: Comisión
         */

        $this->SetY(67);
        $this->SetFillColor(0, 0, 0);
        $this->SetFont('Arial', 'B', 8);
        $this->SetDrawColor(31, 152, 152);
        $this->SetDrawColor(0, 0, 0); // Set the border colour to Aqua
        $this->SetFillColor(191, 191, 191);
        $this->MultiCell(196, 4, utf8_decode("COMISIÓN"), 1, 'C', true);

        $this->SetY(77);
        $lugarComision = ".......................................................................................................";
        $justificacionComision = "............................................................................................................";
        if ($objCe->lugar == 1) {
            $lugarComision = $objCe->destino;
            $justificacionComision = $objCe->justificacion;
        }
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(10, 4, "Lugar:", '', 0, 'R', false);
        $this->SetFont('Arial', '', 8);
        $this->MultiCell(83, 4, utf8_decode($lugarComision), 0, 'J', false);

        $this->SetY(77);
        $this->SetX(90);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 4, "Motivo:", '', 0, 'R', false);
        $this->SetFont('Arial', '', 8);
        $this->MultiCell(87, 4, utf8_decode($justificacionComision), 0, 'J', false);

        /**
         * Cuarta parte: Llenado de Permisos Particulares o Licencias, Consultas Médicas y Otras Excepciones
         */

        $this->SetY(87);
        $this->SetDrawColor(0, 0, 0); // Set the border colour to Aqua
        $this->SetFont('Arial', 'B', 8);

        $this->MultiCell(60, 4, utf8_decode("Permiso Particular o Licencia"), 1, 'C', true);
        $this->SetY(87);
        $this->SetX(78);
        //$this->MultiCell(60, 4, utf8_decode("Consulta Médica"), 1, 'C', true);

        $this->SetY(87);
        $this->SetX(146);
        $this->MultiCell(60, 4, utf8_decode("Otras Excepciones"), 1, 'C', true);


        $this->SetY(92);
        $this->SetX(10);
        $this->Cell(60, 4, utf8_decode("Artículo, Inciso RIP"), 0, 0, 'C', false);

        $this->SetY(96);
        $this->SetX(10);
        $this->SetFont('Arial', '', 8);
        /**
         * Sólo se desplegará las justificaciones del RIP si se trata de un persmiso o licencia.
         */
        if ($permisoParticularOk != "" || $licenciaOk != "") {
            if (count($objExcRip) > 0) {
                foreach ($objExcRip as $item) {
                    if ($item->articulo != null && $item->articulo != "") {
                        $justificacionRip .= "Art.: " . $item->articulo;
                    }
                    if ($item->articulo != null && $item->inciso != "") {
                        if ($item->articulo != null && $item->articulo != "") {
                            $justificacionRip .= ", ";
                        }
                        $justificacionRip .= "Inc.: " . $item->inciso;
                    }
                    $justificacionRip .= ";";
                }
                $justificacionRip .= ";";
                $justificacionRip = str_replace(";;", ";", $justificacionRip);
            }
        }
        $this->Cell(60, 10, $justificacionRip, 1, 0, 'C', false);

        /**
         * Quinta parte: Establecimiento del Aprobador / Verificar, imagen del código QR y leyenda final de recomendaciones
         */
        $valorY = 111;
        $this->SetY($valorY);
        $verificador = "";
        $cargoVerificador = "";
        $aprobador = "";
        $cargoAprobador = "";
        if ($objCe->controlexcepcion_user_ver_id != null && $objCe->controlexcepcion_estado >= 4) {
            $verificador = $objCe->controlexcepcion_user_verificador;
            $usuarioVerificador = Usuarios::findFirstById($objCe->controlexcepcion_user_ver_id);
            if (is_object($usuarioVerificador)) {
                $objVer = new Frelaborales();
                //$relaboral = Relaborales::findFirst(array("persona_id=" . $usuarioVerificador->persona_id . " AND estado>=1"));
                $idRelaboralVerificador = $objVer->getIdRelaboralInDate($usuarioVerificador->persona_id, $objCe->controlexcepcion_fecha_ver);
                if ($idRelaboralVerificador > 0) {
                    $objRV = $objVer->getOneRelaboralConsiderandoUltimaMovilidad($idRelaboralVerificador);
                    $cargoVerificador = $objRV->cargo;
                }
            }
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(25, 5, "Verificado por:", '', 0, 'L', false);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(140, 3, utf8_decode($verificador . " - " . $cargoVerificador), 0, 'J', false);
            $valorY += 5;
            $this->SetY($valorY);
        }
        $valorY += 9;
        if ($objCe->controlexcepcion_estado == 6 || (($objCe->controlexcepcion_estado == 3 || $objCe->controlexcepcion_estado == 4) && $plazo <= 0 && $plazoMaximoDeAprobacionBoletasFisicas <= $plazo)) {
            $valorY += 5;
        }
        /**
         * Se usa el valor >=6 debido a que se ha solicitado la agregación de un séptimo nivel donde
         * se establece la recepción del formulario en el Departamento de Recursos Humanos.
         */
        if ($objCe->controlexcepcion_user_apr_id != null && $objCe->controlexcepcion_estado >= 6) {

            $aprobador = $objCe->controlexcepcion_user_aprobador;
            $usuarioAprobador = Usuarios::findFirstById($objCe->controlexcepcion_user_apr_id);
            if (is_object($usuarioAprobador)) {
                $objApr = new Frelaborales();
                /**
                 * Considerando que se requiere el cargo que ocupa o ocupaba en la fecha de aprobación el aprobador
                 */
                $idRelaboralAprobador = $objApr->getIdRelaboralInDate($usuarioAprobador->persona_id, $objCe->controlexcepcion_fecha_apr);
                if ($idRelaboralAprobador > 0) {
                    $objRA = $objApr->getOneRelaboralConsiderandoUltimaMovilidad($idRelaboralAprobador);
                    $cargoAprobador = $objRA->cargo;
                }
            }
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(20, 3, "Aprobado por :", 0, 0, 'L', false);
            $this->SetFont('Arial', '', 7);
            $this->Cell(133, 3, utf8_decode($aprobador . " - " . $cargoAprobador), 0, 'L', false);
        }
        /**
         * Se añade un enlace al
         */
        $idControlExcepcionCodificado = rtrim(strtr(base64_encode($objCe->id_controlexcepcion), '+/', '-_'), '=');
        $param = Parametros::findFirst(array("parametro LIKE 'RUTA_APLICACION' AND estado=1 AND baja_logica=1"));
        $ruta = 'http://rrhh.local/controlexcepcionesvistobueno/detail/';
        if (is_object($param)) {
            $ruta = 'http://' . $param->nivel . '/controlexcepcionesvistobueno/detail/';
        }
        $codeContents = $ruta . $idControlExcepcionCodificado;
        $imgname = $objCe->id_controlexcepcion . '.png';
        $this->Image($tempDir . $imgname, 175, 105, 35, 35, '', $codeContents);

        $this->SetY($valorY);
        $this->SetFont('Arial', '', 6);
        $this->SetDrawColor(0, 0, 0); // Set the border colour to Aqua
        $this->SetFillColor(191, 191, 191);
        $leyenda = "Nota: El presente formulario (no debe contener borrones o información falsa) deberá ser presentado: un día antes, el mismo día o un día después  al permiso o ";
        $leyenda .= "licencia, posteriormente no será tomada en cuenta, a la hora de hacer planillas. El encargado de Control de Personal, será  el responsable de verificar que se ";
        $leyenda .= "cumplió con las horas y fechas señaladas. Para solicitud de permisos mayores a un día, deberán emitirse memorándums de designación en comisión ";
        $leyenda .= "y/o solicitud de licencias sin goce de haberes de acuerdo a lo establecido en el RIP.";
        $leyenda = utf8_decode($leyenda);
        $this->MultiCell(166, 3, $leyenda, 1, 'J', true);


        $this->SetFont('Arial', 'B', 8);
        $this->SetY(109);
        $this->SetX(146);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(16, 4, utf8_decode("Código:"), 'TLB', 0, 'L', false);
        $this->SetFont('Arial', '', 7);
        $gestion = substr($objCorr->gestion, -2);
        $cant = strlen($objCorr->numero);
        $prefijo = "";
        switch ($cant) {
            case 1:
                $prefijo = "0000";
                break;
            case 2:
                $prefijo = "000";
                break;
            case 3:
                $prefijo = "00";
                break;
            case 4:
                $prefijo = "0";
                break;
        }
        $this->Cell(14, 4, $prefijo . $objCorr->numero . "-" . $gestion, 'TRB', 0, 'L', false);
        /**
         * Sólo se muestra la marca de agua del estado en los casos en que no este aprobada la boleta
         */
        $objCex = new Fcontrolexcepciones();
        $plazo = $objCex->verificaPlazoValidezSolicitud($objCe->id_relaboral, $objCe->fecha_ini, 1, 1);
        $objPrm = new Parametros();
        $plazoMaximoDeAprobacionBoletasFisicas = $objPrm->getPlazoMaximoAprobacionBoletasFisicas();
        $plazoMaximoDeAprobacionBoletasFisicas = ($plazoMaximoDeAprobacionBoletasFisicas * -1);
        if ($objCe->controlexcepcion_estado == 6 || (($objCe->controlexcepcion_estado == 3 || $objCe->controlexcepcion_estado == 4) && $plazo <= 0 && $plazoMaximoDeAprobacionBoletasFisicas <= $plazo)) {
            $this->SetY(113);
            $this->SetX(146);
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(30, 5, utf8_decode($objCe->controlexcepcion_estado_descripcion), 1, 'C', false);
            if (($objCe->controlexcepcion_estado == 3||$objCe->controlexcepcion_estado == 4) && $plazo <= 0) {
                $this->SetY(111);
                $this->SetX(10);
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('Arial', 'B', 7);
                $this->MultiCell(130, 13, utf8_decode("Firma y Sello Inmediato Superior (Aprobador): "), 1, 'L', false);
            }
        } else {
            $this->SetFont('Arial', 'B', 50);
            $this->SetTextColor(0, 255, 0);
            $lenght = strlen($objCe->controlexcepcion_estado_descripcion);
            $xx = 70;
            $yy = 110;
            $grados = 45;
            if ($lenght > 10) {
                $xx = 10;
                $yy = 140;
                $grados = 30;
            }
            $this->RotatedText($xx, $yy, utf8_decode($objCe->controlexcepcion_estado_descripcion), $grados);
        }
        $this->SetTextColor(0, 0, 0);

        /**
         * Séptima parte: Despliegue del espacio para la firma y sello del médico.
         */
        $this->SetY(102);
        $this->SetX(78);

        $this->SetFont('Arial', '', 8);
        //$this->Cell(60, 5, ".............................................................................", 0, 0, 'C', false);

        $this->SetY(106);
        $this->SetX(78);
        $this->SetFont('Arial', 'B', 8);
        //$this->Cell(60, 5, utf8_decode("Firma y Sello del Médico"), 0, 0, 'C', false);


        $this->SetY(96);
        $this->SetX(146);
        $this->SetFont('Arial', 'B', 8);
        if ($otrasExcepcionesOk != "") {
            $otrasExcepciones = $objCe->codigo;
        }
        $this->Cell(60, 10, utf8_decode($otrasExcepciones), 1, 0, 'C', false);
    }

    /**
     * Función para el despliegue del formulario en el primer formato (Formato antiguo).
     * @param $tempDir
     * @param $objR
     * @param $objCe
     * @param $objCorr
     */
    function BodyOld($tempDir, $objR, $objCe, $objCorr)
    {
        /**
         * Sólo se muestra la marca de agua del estado en los casos en que no este aprobada la boleta
         */
        if ($objCe->controlexcepcion_estado !== 6) {
            $this->SetFont('Arial', 'B', 50);
            $this->SetTextColor(0, 255, 0);
            $lenght = strlen($objCe->controlexcepcion_estado_descripcion);
            $xx = 70;
            $yy = 110;
            $grados = 45;
            if ($lenght > 10) {
                $xx = 10;
                $yy = 140;
                $grados = 30;
            }
            $this->RotatedText($xx, $yy, utf8_decode($objCe->controlexcepcion_estado_descripcion), $grados);
        } else {
            $this->SetY(29);
            $this->SetX(180);
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(26, 3, utf8_decode($objCe->controlexcepcion_estado_descripcion), 1, 'C', false);
        }

        $this->SetTextColor(0, 0, 0);

        $this->SetFont('Arial', 'B', 8);
        $this->SetY(25);
        $this->SetX(180);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(12, 4, utf8_decode("Código:"), 'TLB', 0, 'L', false);
        $this->SetFont('Arial', '', 7);
        $gestion = substr($objCorr->gestion, -2);
        $cant = strlen($objCorr->numero);
        $prefijo = "";
        switch ($cant) {
            case 1:
                $prefijo = "0000";
                break;
            case 2:
                $prefijo = "000";
                break;
            case 3:
                $prefijo = "00";
                break;
            case 4:
                $prefijo = "0";
                break;
        }
        $this->Cell(14, 4, $prefijo . $objCorr->numero . "-" . $gestion, 'TRB', 0, 'L', false);
        $this->SetY(29);
        $this->SetX(180);

        $this->SetTextColor(0, 0, 0);
        $this->SetY(30);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(38, 5, "Nombre del Trabajador(a):", '', 0, 'L', false);
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, utf8_decode($objR->nombres), '', 0, 'L', false);

        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "C. I.:", '', 0, 'L', false);
        $this->SetFont('Arial', '', 8);
        $this->Cell(43, 5, $objR->ci . " " . $objR->expd, '', 0, 'L', false);

        $this->SetY(35);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(38, 5, "Gerencia:", '', 0, 'L', false);
        $this->SetFont('Arial', '', 8);
        $gerenciaAdministrativa = str_replace("GERENCIA DE ", "", $objR->gerencia_administrativa);
        $this->Cell(65, 5, utf8_decode($gerenciaAdministrativa), '', 0, 'L', false);

        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "Departamento:", '', 0, 'L', false);
        $this->SetFont('Arial', '', 8);
        $departamentoAdministrativo = str_replace("DEPARTAMENTO DE ", "", $objR->departamento_administrativo);
        $this->MultiCell(65, 3, utf8_decode($departamentoAdministrativo !== '' ? $departamentoAdministrativo : ""), 0, 'J', false);

        $this->SetY(42);

        $this->SetFont('Arial', 'B', 8);
        $lblFecha = "";
        $fecha = "";
        if ($objCe->fecha_ini == $objCe->fecha_fin) {
            $lblFecha = "Fecha:";
            $fecha = $objCe->fecha_ini != "" ? date("d-m-Y", strtotime($objCe->fecha_ini)) : "";
        } else {
            $lblFecha = "Fechas:";
            $fecha = $objCe->fecha_ini != "" ? date("d-m-Y", strtotime($objCe->fecha_ini)) : "" . " AL " . $objCe->fecha_fin != "" ? date("d-m-Y", strtotime($objCe->fecha_fin)) : "";
        }
        $this->Cell(38, 5, $lblFecha, '', 0, 'L', false);
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, $fecha, '', 0, 'L', false);

        if ($objCe->horario == 1) {
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(25, 5, "Salida:", '', 0, 'R', false);
            $this->SetFont('Arial', '', 8);
            $this->Cell(15, 5, $objCe->hora_ini != "" ? date("H:i", strtotime($objCe->hora_ini)) : "", 'LTRB', 0, 'C', false);

            $this->SetFont('Arial', 'B', 8);
            $this->Cell(25, 5, "Retorno:", '', 0, 'R', false);
            $this->SetFont('Arial', '', 8);
            $this->Cell(15, 5, $objCe->hora_fin != "" ? date("H:i", strtotime($objCe->hora_fin)) : "", 'LRTB', 0, 'C', false);
        }
        /**
         * Segunda parte
         */

        $this->SetY(48);
        $this->SetFillColor(0, 0, 0); // Set background colour to black
        $this->SetFont('Arial', 'B', 8);
        $this->SetDrawColor(0, 0, 0); // Set the border colour to Aqua
        $this->SetFillColor(191, 191, 191);
        $this->MultiCell(196, 4, "TIPO DE PERMISO", 1, 'C', true);

        $this->SetY(57);
        $permisoParticularOk = "";
        $comisionOk = "";
        $ConsultaMedicaOk = "";
        switch ($objCe->tipoexcepcion_id) {
            case 1:
                $permisoParticularOk = "X";
                break;
            case 2:
                $comisionOk = "X";
                break;
            case 4:
                $ConsultaMedicaOk = "X";
                break;
        }
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "Comision:", '', 0, 'L', false);
        $this->Cell(30, 5, $comisionOk, 'LRTB', 0, 'C', false);

        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "Permiso particular:", '', 0, 'R', false);
        $this->Cell(30, 5, $permisoParticularOk, 'LTRB', 0, 'C', false);

        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "Consulta medica:", '', 0, 'R', false);
        $this->Cell(30, 5, $ConsultaMedicaOk, 'LRTB', 0, 'C', false);

        /**
         * Tercera parte
         */

        $this->SetY(67);
        $this->SetFillColor(0, 0, 0);
        $this->SetFont('Arial', 'B', 8);
        $this->SetDrawColor(31, 152, 152);
        //$this->Cell(196,5,utf8_decode("COMISIÓN"),'LRTB',0,'C',false);
        $this->SetDrawColor(0, 0, 0); // Set the border colour to Aqua
        $this->SetFillColor(191, 191, 191);
        $this->MultiCell(196, 4, utf8_decode("COMISIÓN"), 1, 'C', true);

        $this->SetY(77);
        $lugarComision = ".....................................................................................";
        $justificacionComision = ".....................................................................................";
        if ($objCe->tipoexcepcion_id == 2) {
            $lugarComision = $objCe->controlexcepcion_observacion;
            $justificacionComision = $objCe->justificacion;
        }
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "Lugar:", '', 0, 'R', false);
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, utf8_decode($lugarComision), '', 0, 'L', false);

        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "Motivo:", '', 0, 'R', false);
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, $justificacionComision, '', 0, 'L', false);

        /**
         * Cuarta parte
         */

        $this->SetY(87);
        $this->SetDrawColor(0, 0, 0); // Set the border colour to Aqua
        $this->SetFont('Arial', 'B', 8);

        $this->MultiCell(98, 4, utf8_decode("PERMISO PARTICULAR"), 1, 'C', true);
        $this->SetY(87);
        $this->SetX(110);
        $this->MultiCell(96, 8, utf8_decode("CONSULTA MÉDICA"), 1, 'C', true);


        $this->SetY(91);
        $this->SetX(110);
        $this->SetFont('Arial', '', 6);
        $this->Cell(98, 5, utf8_decode("Nota: El espacio a continuación debe ser sellado por la Caja Petrolera de Salud"), '', 0, 'C', false);

        /**
         * Quinta parte
         */
        $this->SetY(97);
        $permisoParticularMensualDosDosHorasOk = "";
        $paragrafosRipRespaldatoriosPermisos = "";
        $permisoParticularCumpleOk = "";
        if ($objCe->tipoexcepcion_id == 1) {
            if ($objCe->excepcion === "CUMPLEAÑOS") {
                $permisoParticularCumpleOk = "X";
            } else {
                if ($objCe->cantidad == 2) {
                    switch ($objCe->unidad) {
                        case "HORA":
                            $permisoParticularMensualDosDosHorasOk = "X";
                            $paragrafosRipRespaldatoriosPermisos = utf8_decode("(RIP, Par. I: " . $objCe->frecuencia_descripcion . ")");
                            break;
                        case "DIA":
                            $permisoParticularMensualDosDosHorasOk = "X";
                            $paragrafosRipRespaldatoriosPermisos = utf8_decode("(RIP, Par. II: " . $objCe->frecuencia_descripcion . ")");
                            break;
                        default:
                            break;
                    }
                }

            }
        }
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(53, 5, "Permiso Personal (Art. 35 del RIP)", '', 0, 'L', false);
        $this->Cell(10, 5, $permisoParticularMensualDosDosHorasOk, 'LRTB', 0, 'C', false);
        $this->SetFont('Arial', '', 5);
        $this->Cell(10, 5, $paragrafosRipRespaldatoriosPermisos, '', 0, 'L', false);
        /**
         * Sexta parte
         */
        $this->SetY(103);

        $this->SetFont('Arial', 'B', 8);
        $this->Cell(53, 5, utf8_decode("Permiso por Cumpleaños"), '', 0, 'L', false);
        $this->Cell(10, 5, $permisoParticularCumpleOk, 'LRTB', 0, 'C', false);

        $this->SetFont('Arial', 'B', 8);
        $this->Cell(55, 5, utf8_decode("Firma y Sello del Médico:"), '', 0, 'R', false);
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 5, "....................................................", '', 0, 'L', false);
        /**
         * Séptima parte
         */
        $valorY = 117;
        $this->SetY($valorY);
        $verificador = "";
        $cargoVerificador = "";
        $aprobador = "";
        $cargoAprobador = "";
        if ($objCe->controlexcepcion_user_ver_id != null && $objCe->controlexcepcion_estado >= 4) {
            $verificador = $objCe->controlexcepcion_user_verificador;
            $usuarioVerificador = usuarios::findFirstById($objCe->controlexcepcion_user_ver_id);
            if (is_object($usuarioVerificador)) {
                $relaboral = Relaborales::findFirst(array("persona_id=" . $usuarioVerificador->persona_id . " AND estado>=1"));
                if (is_object($relaboral)) {
                    $objRelaboralVerificador = new Frelaborales();
                    $objRV = $objRelaboralVerificador->getOneRelaboralConsiderandoUltimaMovilidad($relaboral->id);
                    $cargoVerificador = $objRV->cargo;
                }
            }
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(25, 5, "Verificado por:", '', 0, 'L', false);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(140, 3, utf8_decode($verificador . " - " . $cargoVerificador), 0, 'J', false);
            $valorY += 5;
            $this->SetY($valorY);
        }
        $valorY += 7;
        /**
         * Se usa el valor >=6 debido a que se ha solicitado la agregación de un séptimo nivel donde
         * se establece la recepción del formulario en el Departamento de Recursos Humanos.
         */
        if ($objCe->controlexcepcion_user_apr_id != null && $objCe->controlexcepcion_estado >= 6) {
            /**
             * Octava parte
             */

            $aprobador = $objCe->controlexcepcion_user_aprobador;
            $usuarioAprobador = usuarios::findFirstById($objCe->controlexcepcion_user_apr_id);
            if (is_object($usuarioAprobador)) {
                $relaboral = Relaborales::findFirst(array("persona_id=" . $usuarioAprobador->persona_id . " AND estado>=1"));
                if (is_object($relaboral)) {
                    $objRelaboralAprobador = new Frelaborales();
                    $objRA = $objRelaboralAprobador->getOneRelaboralConsiderandoUltimaMovilidad($relaboral->id);
                    $cargoAprobador = $objRA->cargo;
                }
            }
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(25, 5, "Aprobado por :", '', 0, 'L', false);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(133, 3, utf8_decode($aprobador . " - " . $cargoAprobador), 0, 'J', false);
        }
        $this->SetY($valorY);
        $this->SetFont('Arial', '', 6);
        $this->SetDrawColor(0, 0, 0); // Set the border colour to Aqua
        $this->SetFillColor(191, 191, 191);
        $leyenda = "Nota: El presente formulario (no debe contener borrones o información falsa) deberá ser presentado: un día antes, el mismo día o un día después  al permiso o ";
        $leyenda .= "licencia, posteriormente no será tomada en cuenta, a la hora de hacer planillas. El encargado de Control de Personal, será  el responsable de verificar que se ";
        $leyenda .= "cumplió con las horas y fechas señaladas. Para solicitud de permisos iguales o mayores a 24 horas, deberán emitirse memorándums de designación en comisión ";
        $leyenda .= "y/o solicitud de licencias sin goce de haberes de acuerdo a lo establecido en el RIP.";
        $leyenda = utf8_decode($leyenda);
        $this->MultiCell(157, 3, $leyenda, 1, 'J', true);
        $imgname = $objCe->id_controlexcepcion . '.png';
        $this->Image($tempDir . $imgname, 168, 102, 40);
    }

    /**
     * Función para la creación de la imagen con el código QR de acuerdo a los datos del registro de Control de Excepciones.
     * @param $tempDir
     * @param $objR
     * @param $objCe
     */
    function crearCodigoConDatosQR($tempDir, $objR, $objCe, $objCorr)
    {
        $nombreTrabajador = utf8_decode($objR->nombres);
        $ciTrabajador = $objR->ci . " " . $objR->expd;
        $gerenciaSolicitante = utf8_decode(str_replace("GERENCIA DE ", "", $objR->gerencia_administrativa));
        $departamentoSolicitante = utf8_decode(str_replace("DEPARTAMENTO DE ", "", $objR->departamento_administrativo !== "" ? $objR->departamento_administrativo : ""));
        $horaSalida = $objCe->hora_ini;
        $horaRetorno = $objCe->hora_fin;
        $fechaVerificacion = "";
        $fechaAprobacion = "";
        $gestion = substr($objCorr->gestion, -2);
        $cant = strlen($objCorr->numero);
        $prefijo = "";
        switch ($cant) {
            case 1:
                $prefijo = "0000";
                break;
            case 2:
                $prefijo = "000";
                break;
            case 3:
                $prefijo = "00";
                break;
            case 4:
                $prefijo = "0";
                break;
        }


        $codeContents = "Código: " . $prefijo . $objCorr->numero . "-" . $gestion . "\n";
        $codeContents .= "Nombre: " . $nombreTrabajador . "\n";
        $codeContents .= "C. I.: " . $ciTrabajador . "\n";
        /*$codeContents.="Gerencia: ".$gerenciaSolicitante."\n";
        $codeContents.="Departamento: ".$departamentoSolicitante."\n";*/


        if ($objCe->fecha_ini == $objCe->fecha_fin) {
            $lblFecha = "Fecha: ";
            $fecha = $objCe->fecha_ini != "" ? date("d-m-Y", strtotime($objCe->fecha_ini)) : "";
        } else {
            $lblFecha = "Fechas: ";
            $fecha = $objCe->fecha_ini != "" ? date("d-m-Y", strtotime($objCe->fecha_ini)) : "" . " AL " . $objCe->fecha_fin != "" ? date("d-m-Y", strtotime($objCe->fecha_fin)) : "";
        }
        $codeContents .= $lblFecha . $fecha . "\n";
        if ($objCe->horario == 1) {
            $horaSalida = $horaSalida != "" ? date("H:i", strtotime($horaSalida)) : "";
            $horaRetorno = $horaSalida != "" ? date("H:i", strtotime($horaRetorno)) : "";
            $codeContents .= "Salida: " . $horaSalida . "\n";
            $codeContents .= "Retorno: " . $horaRetorno . "\n";
        }
        $codeContents .= "Tipo de Permiso: " . utf8_decode($objCe->tipo_excepcion) . "\n";
        switch ($objCe->tipoexcepcion_id) {
            case 1:
                if ($objCe->excepcion === "CUMPLEAÑOS") {

                    $tipoPermisoParticular = utf8_decode("Permiso por Cumpleaños");
                } else {
                    $tipoPermisoParticular = utf8_decode("Permiso Personal (Art. 35 del RIP)");
                }
                $codeContents .= "(" . utf8_decode($tipoPermisoParticular) . ")\n";
                break;
            case 2:
                $codeContents .= "Lugar: " . utf8_decode($objCe->observacion) . "\n";
                $codeContents .= "Motivo: " . utf8_decode($objCe->justificacion) . "\n";
                break;
            case 3:

                break;
        }
        if ($objCe->controlexcepcion_estado > 0) {
            $verificador = "";
            $cargoVerificador = "";
            $aprobador = "";
            $cargoAprobador = "";
            if ($objCe->controlexcepcion_user_ver_id != null) {
                $verificador = $objCe->controlexcepcion_user_verificador;
                $usuarioVerificador = usuarios::findFirstById($objCe->controlexcepcion_user_ver_id);
                if (is_object($usuarioVerificador)) {
                    $relaboral = Relaborales::findFirst(array("persona_id=" . $usuarioVerificador->persona_id . " AND estado>=1"));
                    if (is_object($relaboral)) {
                        $objRelaboralVerificador = new Frelaborales();
                        $objRV = $objRelaboralVerificador->getOneRelaboralConsiderandoUltimaMovilidad($relaboral->id);
                        $cargoVerificador = $objRV->cargo;
                        $fechaVerificacion = $objCe->controlexcepcion_fecha_ver != "" ? date("d-m-Y H:i:s", strtotime($objCe->controlexcepcion_fecha_ver)) : "";
                    }
                }
                $codeContents .= "Verificador: " . utf8_decode($verificador) . "\n";
                $codeContents .= "Cargo Verificador: " . utf8_decode($cargoVerificador) . "\n";
                $codeContents .= "Fecha Verificación: " . utf8_decode($fechaVerificacion) . "\n";
            }
            $aprobador = $objCe->controlexcepcion_user_aprobador;
            $usuarioAprobador = usuarios::findFirstById($objCe->controlexcepcion_user_apr_id);
            if (is_object($usuarioAprobador)) {
                $relaboral = Relaborales::findFirst(array("persona_id=" . $usuarioAprobador->persona_id . " AND estado>=1"));
                if (is_object($relaboral)) {
                    $objRelaboralAprobador = new Frelaborales();
                    $objRA = $objRelaboralAprobador->getOneRelaboralConsiderandoUltimaMovilidad($relaboral->id);
                    $cargoAprobador = $objRA->cargo;
                    $fechaAprobacion = $objCe->controlexcepcion_fecha_apr != "" ? date("d-m-Y H:i:s", strtotime($objCe->controlexcepcion_fecha_apr)) : "";
                }
                $codeContents .= "Aprobador: " . utf8_decode($aprobador) . "\n";
                $codeContents .= "Cargo Aprobador: " . utf8_decode($cargoAprobador) . "\n";
                $codeContents .= "Fecha Aprobación: " . utf8_decode($fechaAprobacion) . "\n";
            }
        }
        $codeContents .= utf8_decode("Excepción: " . $objCe->excepcion) . "\n";
        $codeContents .= utf8_decode("Estado: " . $objCe->controlexcepcion_estado_descripcion) . "\n";
        /**
         * Inicialmente se elimina la imagen pre-existente debido a que puede contener datos desactualizados
         */
        if (file_exists($tempDir . $objCe->id_controlexcepcion . '.png'))
            unlink($tempDir . $objCe->id_controlexcepcion . '.png');
        QRcode::png($codeContents, $tempDir . $objCe->id_controlexcepcion . '.png', QR_ECLEVEL_L, 3);
    }

    /**
     * Función para la creación del código QR que sólo almacena la URL para que el sistema pueda mostrarle los datos de la boleta.
     * @param $tempDir
     * @param $objR
     * @param $objCe
     * @param $objCorr
     */
    function crearCodigoConUrlQR($tempDir, $objR, $objCe, $objCorr)
    {
        if ($objCe->id_controlexcepcion > 0) {
            $idControlExcepcionCodificado = rtrim(strtr(base64_encode($objCe->id_controlexcepcion), '+/', '-_'), '=');
            $param = Parametros::findFirst(array("parametro LIKE 'RUTA_APLICACION' AND estado=1 AND baja_logica=1"));
            $ruta = 'http://rrhh.local/controlexcepcionesvistobueno/detail/';
            if (is_object($param)) {
                $ruta = 'http://' . $param->nivel . '/controlexcepcionesvistobueno/detail/';
            }
            $codeContents = $ruta . $idControlExcepcionCodificado;
            try {
                if (file_exists($tempDir . $objCe->id_controlexcepcion . '.png')) {
                    unlink($tempDir . $objCe->id_controlexcepcion . '.png');
                }
                QRcode::png($codeContents, $tempDir . $objCe->id_controlexcepcion . '.png', QR_ECLEVEL_L, 3);
            } catch (Exception $e) {
                return false;
            }

        }
    }

    /*
     *  Función para definir el pie del reporte
     */
    function Footer()
    {
        //Posición: a 1,5 cm del final
        $this->SetY(-15);
        $this->SetTextColor(0);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, ' Pagina ' . $this->PageNo() . ' de {nb}' . $this->AliasNbPages(), 0, 0, 'C');
        $this->SetY(-15);
        $this->SetX(-50);
        $this->SetTextColor(0);
        $this->DatesFooter();
    }

    /**
     * Función para desplegar en el pie del documento la fecha de despliegue.
     */
    function DatesFooter()
    {
        $this->SetY(-15);
        $this->SetX(-50);
        //Arial italic 8
        $this->SetTextColor(0);
        $this->SetFont('Arial', 'I', 8);
        //Numero de página
        if ($this->FechaHoraReporte != "")
            $this->Cell(0, 10, "Reporte al " . $this->FechaHoraReporte, 0, 0, 'C');
        if ($this->FechaHoraCreacion != "") {
            $this->SetY(-15);
            $this->SetX(-50);
            $this->Cell(0, 1, "Creado el " . $this->FechaHoraCreacion, 0, 0, 'C');
        }
        //$this->Cell(0,10,"Reporte al ".date("d-m-Y h:i:s"),0,0,'C');
    }

    function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns = $a;
    }

    function fill($f)
    {
        //juego de arreglos de relleno
        $this->fill = $f;
    }

    /**
     * Función para el despliegue del registro dentro del cuerpo de la grilla principal.
     * @param $data
     * @return mixed
     */
    function Row($data)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        //Se verifica que se ha añadido una nueva página, por lo cual se ha establecido la cabecera con un
        //conjunto de alineaciones de cabecerá que hay que reestablecer para el cuerpo de la grilla.
        $ok = $this->CheckPageBreak($h);
        if ($ok) {
            $this->aligns = $this->alignSelecteds;
        }
        $aligns = $this->aligns;
        //Trazando la celda de la fila
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($aligns[$i]) ? $aligns[$i] : 'L';
            //Guardando la posición actual
            $x = $this->GetX();
            $y = $this->GetY();
            //Trazando el borde
            $this->Rect($x, $y, $w, $h);
            //Imprimiendo el texto
            $this->MultiCell($w, 5, utf8_decode($data[$i]), 0, $a);
            //Ubicando la posición de la linea derecha de la celda
            $this->SetXY($x + $w, $y);
        }
        //Saltando a la siguiente línea
        $this->Ln($h);
        return $w;
    }

    /**
     * Función para el despliegue del registro dentro del cuerpo de la grilla de totales
     * @param $data
     * @return mixed
     */
    function RowTotal($data)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->totalWidths[$i], $data[$i]));
        $h = 5 * $nb;
        $this->DefineColorBodyTable();
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        if (($this->pageWidth - $this->totalTableWidth) > 0) $this->SetX(($this->pageWidth - $this->totalTableWidth) / 2);
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->totalWidths[$i];
            $a = isset($this->totalAligns[$i]) ? $this->totalAligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, 5, utf8_decode($data[$i]), 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
        return $w;
    }

    function Agrupador($data)
    {
        if (count($data) > 0) {
            $ancho = 0;
            foreach ($this->widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            $nb = 0;
            for ($i = 0; $i < count($data); $i++)
                $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
            $h = 5 * $nb;
            //Issue a page break first if needed
            $this->CheckPageBreak($h);
            for ($i = 0; $i < count($data); $i++) {
                if (($this->pageWidth - $this->tableWidth) > 0) $this->SetX(($this->pageWidth - $this->tableWidth) / 2);
                $this->Cell($ancho, 5, $data[$i], 1, 1, 'L', true);
            }
        }

        //Go to the next line
        //$this->Ln($h);
    }

    /**
     * Función para definir la fila de títulos de la tabla
     * @param array $data
     * @version 17-02-2012
     */
    function RowTitle($data, $sw = 0)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        if (($this->pageWidth - $this->tableWidth) > 0) $this->SetX(($this->pageWidth - $this->tableWidth) / 2);
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, 5, utf8_decode($data[$i]), 0, $a, true);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    /**
     * Función para mostrar la primera fila cabecera de la grilla de totales.
     * @param $data
     * @param int $sw
     */
    function RowTotalTitle($data, $sw = 0)
    {
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->totalWidths[$i], $data[$i]));
        $h = 5 * $nb;
        $this->DefineColorHeaderTable();
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        if (($this->pageWidth - $this->totalTableWidth) > 0) $this->SetX(($this->pageWidth - $this->totalTableWidth) / 2);
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->totalWidths[$i];
            //$a=isset($this->totalAlignSelecteds[$i]) ? $this->totalAlignSelecteds[$i] : 'L';
            $a = 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, 5, utf8_decode($data[$i]), 0, $a, true);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            return true;
        }
        return false;
    }

    function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw =& $this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    #region nuevas funciones
    function cabeceraHorizontal($cabecera)
    {
        $this->SetXY(10, 40);
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(2, 157, 116);//Fondo verde de celda
        $this->SetTextColor(240, 255, 240); //Letra color blanco
        foreach ($cabecera as $fila) {
            //Atención!! el parámetro true rellena la celda con el color elegido
            $this->Cell(24, 7, utf8_decode($fila), 1, 0, 'L', true);
        }
    }

    function datosHorizontal($datos)
    {
        $this->SetXY(10, 47);
        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(229, 229, 229); //Gris tenue de cada fila
        $this->SetTextColor(3, 3, 3); //Color del texto: Negro
        $bandera = false; //Para alternar el relleno
        foreach ($datos as $fila) {
            //El parámetro badera dentro de Cell: true o false
            //true: Llena  la celda con el fondo elegido
            //false: No rellena la celda
            $this->Cell(24, 7, utf8_decode($fila['nombre']), 1, 0, 'L', $bandera);
            $this->Cell(24, 7, utf8_decode($fila['apellido']), 1, 0, 'L', $bandera);
            $this->Cell(24, 7, utf8_decode($fila['matricula']), 1, 0, 'L', $bandera);
            $this->Ln();//Salto de línea para generar otra fila
            $bandera = !$bandera;//Alterna el valor de la bandera
        }
    }

    function tablaHorizontal($cabeceraHorizontal, $datosHorizontal)
    {
        $this->cabeceraHorizontal($cabeceraHorizontal);
        $this->datosHorizontal($datosHorizontal);
    }

    /**
     * Función para definir las columnas a mostrarse.
     * @param $widthAlignAll
     * @param $columns
     * @return array
     */
    function DefineCols($widthAlignAll, $columns, $exclude = array())
    {
        $arrRes = Array();
        $arrRes[] = "nro_row";
        foreach ($columns as $key => $val) {
            if (isset($widthAlignAll[$key])) {
                if (!isset($val['hidden']) || $val['hidden'] != true) {
                    if (!in_array($key, $exclude) || count($exclude) == 0)
                        $arrRes[] = $key;
                }
            }
        }
        return $arrRes;
    }

    /**
     * Función para obtener las columnas establecidas para el cálculo de totales y seleccionadas para aparecer.
     * @param $generalConfigForAllColumns
     * @param $columns
     * @param array $exclude
     * @return array
     */
    function DefineSelectedTotalColsWithExclude($widthAlignAll, $columns, $exclude = array())
    {
        $arrRes = Array();
        foreach ($columns as $key => $val) {
            if (isset($widthAlignAll[$key])) {
                if ((!isset($val['hidden']) || $val['hidden'] != true) && $widthAlignAll[$key]["totales"] === true) {
                    if (!in_array($key, $exclude) || count($exclude) == 0)
                        $arrRes[] = $key;
                }
            }
        }
        return $arrRes;
    }

    /*
     * Función para la definición de los contenidos de la cabecera.
     * @param $widthAlignAll
     * @param $columns
     * @return array
     */
    function DefineTitleCols($widthAlignAll, $columns, $exclude = array())
    {
        $arrRes = Array();
        $arrRes[] = "Nro.";
        foreach ($columns as $key => $val) {
            if (isset($widthAlignAll[$key])) {
                if (!isset($val['hidden']) || $val['hidden'] != true) {
                    if (!in_array($key, $exclude) || count($exclude) == 0) {
                        $arrRes[] = $widthAlignAll[$key]['title'];
                    }
                }
            }
        }
        return $arrRes;
    }

    /**
     * Función para definir las columnas a considerarse dentro de la grilla de totales
     * @param $widthAlignAll
     * @param $columns
     * @param array $exclude
     * @return array
     */
    function DefineTotalTitleCols($widthAlignAll, $totalCols)
    {
        $arrRes = Array();
        foreach ($totalCols as $key => $val) {
            if (array_key_exists($val, $widthAlignAll)) {
                $arrRes[] = $widthAlignAll[$val]['title'];
            }
        }
        return $arrRes;
    }

    /**
     * Función para la obtención de la fila que contiene los totales
     * @param $colSelecteds
     * @param $colTotalSelecteds
     * @param $arrTodosTotales
     * @return array
     */
    public function generaFilaTotales($colSelecteds, $colTotalSelecteds, $arrTodosTotales)
    {
        $arrTotales = array();
        $sw = 0;
        $clave = -1;
        if (count($colSelecteds) > 0 && count($colTotalSelecteds) > 0) {
            foreach ($colSelecteds as $val) {
                if (in_array($val, $colTotalSelecteds)) {
                    //echo "$val<------>".$colTotalSelecteds[$val]."&&".in_array($val,$colTotalSelecteds);
                    $arrTotales [] = $arrTodosTotales[$val];
                    $sw = 1;
                } else {
                    $arrTotales [] = '';
                }
                if ($sw == 0) {
                    $clave++;
                }
            }
            if ($sw == 1) {
                if ($clave >= 0) {
                    $arrTotales[$clave] = "Totales:";
                }
            } else {
                $arrTotales = array();
            }
        }
        return $arrTotales;
    }

    /**
     * Función para conocer el listado de columnas para el resumen de totales
     * @param $generalConfigForAllColumns
     * @return array
     */
    function DefineSelectedTotalCols($generalConfigForAllColumns)
    {
        $arrRes = Array();
        $arrRes[] = "nro_row";
        foreach ($generalConfigForAllColumns as $key => $val) {
            if ($val['totales'] === true) {
                $arrRes[] = $key;
            }
        }
        return $arrRes;
    }

    /**
     * Función para definir el listado de columnas para mostrarse, considerando que se estén usando agrupadores
     * @param $widthAlignAll
     * @param $dondeCambio
     * @param $queCambio
     * @return array
     */
    function DefineTitleColsByGroups($widthAlignAll, $dondeCambio, $queCambio)
    {
        $arrRes = Array();
        foreach ($dondeCambio as $val) {
            if (isset($widthAlignAll[$val]['title'])) {
                $arrRes[] = $queCambio[$val];
            }
        }

        return $arrRes;
    }

    /**
     * Función para la definición de las alignaciones de las cabeceras de la tabla.
     * @param $numCols
     * @return array
     */
    function DefineTitleAligns($numCols)
    {
        $arrRes = Array();
        for ($i = 0; $i <= $numCols; $i++) {
            $arrRes[] = "C";
        }
        return $arrRes;
    }

    /**
     * Función para definir el contenido de la fila a mostrarse.
     * @param int $numRow
     * @param $rowRelaboral
     * @param $colSelecteds
     * @return array
     */
    function DefineRows($numRow = 0, $rowRelaboral, $colSelecteds)
    {
        $arrRes = Array();
        $arrRes[] = $numRow;
        foreach ($colSelecteds as $val) {
            if (array_key_exists($val, $rowRelaboral)) {
                $arrRes[] = $rowRelaboral[$val];
            }
        }
        return $arrRes;
    }

    /*
     * Función para la generación del array con las alineaciones de columna en el cuerpo de la tabla.
     * @param $generalWiths Array de los anchos y alineaciones de columnas disponibles.
     * @param $columns Array de las columnas con las propiedades de oculto (hidden:1) y visible (hidden:null).
     * @return array Array con el listado de alineaciones a desplegarse.
     */
    function DefineAligns($widthAlignAll, $columns, $exclude = array())
    {
        $arrRes = Array();
        $arrRes[] = 'C';
        foreach ($columns as $key => $val) {
            if (isset($widthAlignAll[$key])) {
                if (!isset($val['hidden']) || $val['hidden'] != true) {
                    if (!in_array($key, $exclude) || count($exclude) == 0)
                        $arrRes[] = $widthAlignAll[$key]['align'];
                }
            }
        }
        return $arrRes;
    }

    /**
     * Función para la obtención del listado de alineaciones de los campos considerados dentro de la grilla de totales.
     * @param $widthAlignAll
     * @param $totalCols
     * @return array
     */
    function DefineAlignsForTotals($widthAlignAll, $totalCols)
    {
        $arrRes = Array();
        foreach ($totalCols as $key => $val) {
            if (array_key_exists($val, $widthAlignAll)) {
                $arrRes[] = $widthAlignAll[$val]['align'];
            }
        }
        return $arrRes;
    }

    /**
     * Función para establecer los valores por defecto (Vacios) para los grupos seleccionados.
     * @param $groups
     * @return array
     */
    function DefineDefaultValuesForGroups($groups)
    {
        $arrRes = Array();
        if ($groups != "") {
            $gr = explode(",", $groups);
            if (count($gr) > 0) {
                foreach ($gr as $val) {
                    $arrRes[$val] = array("valor" => "");
                }
            }
        }
        return $arrRes;
    }

    /**
     * Función para la obtención del listado de columnas a mostrarse descontando los que se han solicitado en agrupador.
     * @param $colTitleSelecteds
     * @param $agrupadores
     * @return array
     */
    function defineListadoSinColumnasEnAgrupador($colTitleSelecteds, $agrupadores)
    {
        $arrRes = Array();
        if (count($colTitleSelecteds) > 0 && count($agrupadores) > 0) {
            foreach ($colTitleSelecteds as $val) {
                if (!in_array($val, $agrupadores)) {
                    $arrRes[] = $val;
                }
            }
        } else $arrRes = $colTitleSelecteds;

        return $arrRes;
    }
    #endregion nuevas funciones
    /*
     * Función para obtener la cantidad de veces que se considera una misma columna en el filtrado.
     * @param $columna
     * @param $array
     * @return int
     */
    function obtieneCantidadVecesConsideracionPorColumnaEnFiltros($columna, $array)
    {
        $cont = 0;
        if (count($array) >= 1) {
            foreach ($array as $key => $val) {
                if (in_array($columna, $val)) {
                    $cont++;
                }
            }
        }
        return $cont;
    }

    /**
     * Función para la obtención de los valores considerados en el filtro enviado.
     * @param $columna Nombre de la columna
     * @param $array Array con los registro de busquedas.
     * @return array Array con los valores considerados en el filtrado enviado.
     */
    function obtieneValoresConsideradosPorColumnaEnFiltros($columna, $array)
    {
        $arr_col = array();
        $cont = 0;
        if (count($array) >= 1) {
            foreach ($array as $key => $val) {
                if (in_array($columna, $val)) {
                    $arr_col[] = $val["valor"];
                }
            }
        }
        return $arr_col;
    }

    /**
     * Función para la agregación de la grilla correspondiente a los totales del reporte.
     * @param array $arrListado
     * @param int $totalAtrasos
     * @param int $totalFaltas
     * @param int $totalAbandono
     * @param int $totalOmision
     * @param int $totalLsgh
     * @param int $totalCompensacion
     */
    function agregarPaginaTotales($arrListado = array(), $totalAtrasos = 0, $totalAtrasados, $totalFaltas = 0, $totalAbandono = 0, $totalOmision = 0, $totalLsgh = 0, $totalAgrupador = 0, $totalDescanso, $totalCompensacion = 0)
    {
        $this->AddTotalPage();
        $this->RowTotalTitle($this->totalColTitleSelecteds);
        $cc = 0;
        foreach ($arrListado as $clave2 => $valor2) {
            foreach ($valor2 as $clave1 => $valor1) {
                foreach ($valor1 as $clave => $valor) {
                    $cc++;
                    $arrMostrable = array($cc, $valor["nombres"], $valor["ci"], $valor["expd"], $valor["gerencia_administrativa"], $valor["departamento_administrativo"], $valor["area"], $valor["cargo"], str_replace(".00", "", $valor["sueldo"]), $valor["gestion"], $valor["mes_nombre"], $valor["atrasos"], $valor["atrasados"], $valor["faltas"], $valor["abandono"], $valor["omision"], $valor["lsgh"], $valor["agrupador"], $valor["descanso"]);
                    $this->RowTotal($arrMostrable);
                }
            }
        }
        if ($cc > 0) {
            $this->DefineColorHeaderTable();
            $arrMostrable = array("", "", "", "", "", "", "", "", "", "", "Totales:", $totalAtrasos, $totalAtrasados, $totalFaltas, $totalAbandono, $totalOmision, $totalLsgh, $totalAgrupador, $totalDescanso);
            $this->RowTotal($arrMostrable);
        }
    }

    /**
     * Función para agregar una tabla de totales al final del reporte.
     * @param $rrCabeceras
     * @param $arrTotales
     */
    function agregarPaginaTotalesTransposed($rrCabeceras, $arrTotales)
    {
        $this->Ln();
        $this->DefineColorHeaderTable();
        foreach ($rrCabeceras as $col)
            $this->Cell(40, 7, $col, 1, 0, 'C', 1);
        $this->Ln();
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        foreach ($arrTotales as $tot)
            $this->Cell(40, 5, $tot, 1, 0, 'R');
        $this->Ln();

    }
}