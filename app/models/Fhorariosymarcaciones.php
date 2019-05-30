<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  13-03-2015
*/

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Fhorariosymarcaciones extends \Phalcon\Mvc\Model
{
    public $id_horarioymarcacion;
    public $relaboral_id;
    public $gestion;
    public $mes;
    public $mes_nombre;
    public $turno;
    public $grupo;
    public $clasemarcacion;
    public $clasemarcacion_descripcion;
    public $modalidadmarcacion_id;
    public $modalidad_marcacion;
    public $d1;
    public $calendariolaboral1_id;
    public $estado1;
    public $estado1_descripcion;
    public $d2;
    public $calendariolaboral2_id;
    public $estado2;
    public $estado2_descripcion;
    public $d3;
    public $calendariolaboral3_id;
    public $estado3;
    public $estado3_descripcion;
    public $d4;
    public $calendariolaboral4_id;
    public $estado4;
    public $estado4_descripcion;
    public $d5;
    public $calendariolaboral5_id;
    public $estado5;
    public $estado5_descripcion;
    public $d6;
    public $calendariolaboral6_id;
    public $estado6;
    public $estado6_descripcion;
    public $d7;
    public $calendariolaboral7_id;
    public $estado7;
    public $estado7_descripcion;
    public $d8;
    public $calendariolaboral8_id;
    public $estado8;
    public $estado8_descripcion;
    public $d9;
    public $calendariolaboral9_id;
    public $estado9;
    public $estado9_descripcion;
    public $d10;
    public $calendariolaboral10_id;
    public $estado10;
    public $estado10_descripcion;
    public $d11;
    public $calendariolaboral11_id;
    public $estado11;
    public $estado11_descripcion;
    public $d12;
    public $calendariolaboral12_id;
    public $estado12;
    public $estado12_descripcion;
    public $d13;
    public $calendariolaboral13_id;
    public $estado13;
    public $estado13_descripcion;
    public $d14;
    public $calendariolaboral14_id;
    public $estado14;
    public $estado14_descripcion;
    public $d15;
    public $calendariolaboral15_id;
    public $estado15;
    public $estado15_descripcion;
    public $d16;
    public $calendariolaboral16_id;
    public $estado16;
    public $estado16_descripcion;
    public $d17;
    public $calendariolaboral17_id;
    public $estado17;
    public $estado17_descripcion;
    public $d18;
    public $calendariolaboral18_id;
    public $estado18;
    public $estado18_descripcion;
    public $d19;
    public $calendariolaboral19_id;
    public $estado19;
    public $estado19_descripcion;
    public $d20;
    public $calendariolaboral20_id;
    public $estado20;
    public $estado20_descripcion;
    public $d21;
    public $calendariolaboral21_id;
    public $estado21;
    public $estado21_descripcion;
    public $d22;
    public $calendariolaboral22_id;
    public $estado22;
    public $estado22_descripcion;
    public $d23;
    public $calendariolaboral23_id;
    public $estado23;
    public $estado23_descripcion;
    public $d24;
    public $calendariolaboral24_id;
    public $estado24;
    public $estado24_descripcion;
    public $d25;
    public $calendariolaboral25_id;
    public $estado25;
    public $estado25_descripcion;
    public $d26;
    public $calendariolaboral26_id;
    public $estado26;
    public $estado26_descripcion;
    public $d27;
    public $calendariolaboral27_id;
    public $estado27;
    public $estado27_descripcion;
    public $d28;
    public $calendariolaboral28_id;
    public $estado28;
    public $estado28_descripcion;
    public $d29;
    public $calendariolaboral29_id;
    public $estado29;
    public $estado29_descripcion;
    public $d30;
    public $calendariolaboral30_id;
    public $estado30;
    public $estado30_descripcion;
    public $d31;
    public $calendariolaboral31_id;
    public $estado31;
    public $estado31_descripcion;
    public $ultimo_dia;
    public $atrasos;
    public $faltas;
    public $abandono;
    public $omision;
    public $lsgh;
    public $compensacion;
    public $observacion;
    public $estado;
    public $estado_descripcion;
    public $baja_logica;
    public $agrupador;
    public $user_reg_id;
    public $fecha_reg;
    public $user_apr_id;
    public $fecha_apr;
    public $user_mod_id;
    public $fecha_mod;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("");
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id_horarioymarcacion' => 'id_horariosymarcacion',
            'relaboral_id' => 'relaboral_id',
            'gestion' => 'gestion',
            'mes' => 'mes',
            'mes_nombre' => 'mes_nombre',
            'turno' => 'turno',
            'grupo' => 'grupo',
            'clasemarcacion' => 'clasemarcacion',
            'clasemarcacion_descripcion' => 'clasemarcacion_descripcion',
            'modalidadmarcacion_id' => 'modalidadmarcacion_id',
            'modalidad_marcacion' => 'modalidad_marcacion',
            'd1' => 'd1',
            'calendariolaboral1_id' => 'calendariolaboral1_id',
            'estado1' => 'estado1',
            'estado1_descripcion' => 'estado1_descripcion',
            'd2' => 'd2',
            'calendariolaboral2_id' => 'calendariolaboral2_id',
            'estado2' => 'estado2',
            'estado2_descripcion' => 'estado2_descripcion',
            'd3' => 'd3',
            'calendariolaboral3_id' => 'calendariolaboral3_id',
            'estado3' => 'estado3',
            'estado3_descripcion' => 'estado3_descripcion',
            'd4' => 'd4',
            'calendariolaboral4_id' => 'calendariolaboral4_id',
            'estado4' => 'estado4',
            'estado4_descripcion' => 'estado4_descripcion',
            'd5' => 'd5',
            'calendariolaboral5_id' => 'calendariolaboral5_id',
            'estado5' => 'estado5',
            'estado5_descripcion' => 'estado5_descripcion',
            'd6' => 'd6',
            'calendariolaboral6_id' => 'calendariolaboral6_id',
            'estado6' => 'estado6',
            'estado6_descripcion' => 'estado6_descripcion',
            'd7' => 'd7',
            'calendariolaboral7_id' => 'calendariolaboral7_id',
            'estado7' => 'estado7',
            'estado7_descripcion' => 'estado7_descripcion',
            'd8' => 'd8',
            'calendariolaboral8_id' => 'calendariolaboral8_id',
            'estado8' => 'estado8',
            'estado8_descripcion' => 'estado8_descripcion',
            'd9' => 'd9',
            'calendariolaboral9_id' => 'calendariolaboral9_id',
            'estado9' => 'estado9',
            'estado9_descripcion' => 'estado9_descripcion',
            'd10' => 'd10',
            'calendariolaboral10_id' => 'calendariolaboral10_id',
            'estado10' => 'estado10',
            'estado10_descripcion' => 'estado10_descripcion',
            'd11' => 'd11',
            'calendariolaboral11_id' => 'calendariolaboral11_id',
            'estado11' => 'estado11',
            'estado11_descripcion' => 'estado11_descripcion',
            'd12' => 'd12',
            'calendariolaboral12_id' => 'calendariolaboral12_id',
            'estado12' => 'estado12',
            'estado12_descripcion' => 'estado12_descripcion',
            'd13' => 'd13',
            'calendariolaboral13_id' => 'calendariolaboral13_id',
            'estado13' => 'estado13',
            'estado13_descripcion' => 'estado13_descripcion',
            'd14' => 'd14',
            'calendariolaboral14_id' => 'calendariolaboral14_id',
            'estado14' => 'estado14',
            'estado14_descripcion' => 'estado14_descripcion',
            'd15' => 'd15',
            'calendariolaboral15_id' => 'calendariolaboral15_id',
            'estado15' => 'estado15',
            'estado15_descripcion' => 'estado15_descripcion',
            'd16' => 'd16',
            'calendariolaboral16_id' => 'calendariolaboral16_id',
            'estado16' => 'estado16',
            'estado16_descripcion' => 'estado16_descripcion',
            'd17' => 'd17',
            'calendariolaboral17_id' => 'calendariolaboral17_id',
            'estado17' => 'estado17',
            'estado17_descripcion' => 'estado17_descripcion',
            'd18' => 'd18',
            'calendariolaboral18_id' => 'calendariolaboral18_id',
            'estado18' => 'estado18',
            'estado18_descripcion' => 'estado18_descripcion',
            'd19' => 'd19',
            'calendariolaboral19_id' => 'calendariolaboral19_id',
            'estado19' => 'estado19',
            'estado19_descripcion' => 'estado19_descripcion',
            'd20' => 'd20',
            'calendariolaboral20_id' => 'calendariolaboral20_id',
            'estado20' => 'estado20',
            'estado20_descripcion' => 'estado20_descripcion',
            'd21' => 'd21',
            'calendariolaboral21_id' => 'calendariolaboral21_id',
            'estado21' => 'estado21',
            'estado21_descripcion' => 'estado21_descripcion',
            'd22' => 'd22',
            'calendariolaboral22_id' => 'calendariolaboral22_id',
            'estado22' => 'estado22',
            'estado22_descripcion' => 'estado22_descripcion',
            'd23' => 'd23',
            'calendariolaboral23_id' => 'calendariolaboral23_id',
            'estado23' => 'estado23',
            'estado23_descripcion' => 'estado23_descripcion',
            'd24' => 'd24',
            'calendariolaboral24_id' => 'calendariolaboral24_id',
            'estado24' => 'estado24',
            'estado24_descripcion' => 'estado24_descripcion',
            'd25' => 'd25',
            'calendariolaboral25_id' => 'calendariolaboral25_id',
            'estado25' => 'estado25',
            'estado25_descripcion' => 'estado25_descripcion',
            'd26' => 'd26',
            'calendariolaboral26_id' => 'calendariolaboral26_id',
            'estado26' => 'estado26',
            'estado26_descripcion' => 'estado26_descripcion',
            'd27' => 'd27',
            'calendariolaboral27_id' => 'calendariolaboral27_id',
            'estado27' => 'estado27',
            'estado27_descripcion' => 'estado27_descripcion',
            'd28' => 'd28',
            'calendariolaboral28_id' => 'calendariolaboral28_id',
            'estado28' => 'estado28',
            'estado28_descripcion' => 'estado28_descripcion',
            'd29' => 'd29',
            'calendariolaboral29_id' => 'calendariolaboral29_id',
            'estado29' => 'estado29',
            'estado29_descripcion' => 'estado29_descripcion',
            'd30' => 'd30',
            'calendariolaboral30_id' => 'calendariolaboral30_id',
            'estado30' => 'estado30',
            'estado30_descripcion' => 'estado30_descripcion',
            'd31' => 'd31',
            'calendariolaboral31_id' => 'calendariolaboral31_id',
            'estado31' => 'estado31',
            'estado31_descripcion' => 'estado31_descripcion',
            'ultimo_dia' => 'ultimo_dia',
            'atrasos' => 'atrasos',
            'faltas' => 'faltas',
            'abandono' => 'abandono',
            'omision' => 'omision',
            'lsgh' => 'lsgh',
            'compensacion' => 'compensacion',
            'observacion' => 'observacion',
            'estado' => 'estado',
            'estado_descripcion' => 'estado_descripcion',
            'baja_logica' => 'baja_logica',
            'agrupador' => 'agrupador',
            'user_reg_id' => 'user_reg_id',
            'fecha_reg' => 'fecha_reg',
            'user_apr_id' => 'user_apr_id',
            'fecha_apr' => 'fecha_apr',
            'user_mod_id' => 'user_mod_id',
            'fecha_mod' => 'fecha_mod',

        );
    }

    private $_db;

    /**
     * Función para la obtención del listado total de horarios y marcaciones filtrable de acuerdo a los parámetros enviados.
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function getAll($where = '', $group = '')
    {
        $sql = "SELECT * FROM f_horariosymarcaciones_calculos_totales_global() ";
        if ($where != '') $sql .= $where;
        if ($group != '') $sql .= $group;
        $this->_db = new Fhorariosymarcaciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para obtener el listado de los registros calculados para un registro de relación laboral.
     * @param $idRelaboral
     * @param $where
     * @param $group
     * @return Resultset
     */
    public function getAllFromOneRelaboral($idRelaboral, $where = '', $group = '')
    {
        if ($idRelaboral > 0) {
            /**
             * Se instancia la fecha de baja si es que el registro de relación laboral esta PASIVO.
             */
            $sql = "SELECT * FROM f_horariosymarcaciones_calculos_rango_fechas($idRelaboral,(SELECT fecha_incor FROM relaborales WHERE id=$idRelaboral),(SELECT CASE WHEN fecha_baja IS NOT NULL AND estado=0 THEN fecha_baja ELSE fecha_fin END FROM relaborales WHERE id=$idRelaboral))";
            if ($where != '') $sql .= $where;
            if ($group != '') $sql .= $group;
            $this->_db = new Fhorariosymarcaciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función que se encarga de devolver en un solo resultado el conjunto de excepciones registradas para un registro de relación laboral determinado, considerando el
     * filtro de un tipo de excepción específico, una fecha, hora de inicio y finalización.
     * @param $idRelaboral
     * @param $idExcepcion
     * @param $gestion
     * @param $mes
     * @param $dia
     * @param $idCalendariolaboral
     * @return Resultset
     */
    public function getExcepcionesEnDia($idRelaboral, $idExcepcion, $gestion, $mes, $dia, $idCalendariolaboral, $opcion = 0)
    {
        if ($gestion > 0 && $mes > 0 && $dia > 0 && $idCalendariolaboral > 0) {
            $sql = "SELECT f_excepciones_en_dia FROM f_excepciones_en_dia($idRelaboral,$idExcepcion,$gestion,$mes,$dia,$idCalendariolaboral,$opcion) ";
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para la obtención en un sólo resultado el conjunto de feriados en un día en particular.
     * @param $gestion
     * @param $mes
     * @param $dia
     * @param int $opcion
     * @return Resultset
     */
    public function getFeriadosEnDia($gestion, $mes, $dia, $opcion = 0)
    {
        if ($gestion > 0 && $mes > 0 && $dia > 0) {
            $sql = "SELECT f_feriados_en_dia FROM f_feriados_en_dia('$dia-$mes-$gestion',$opcion) ";
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para la obtención del listado de fechas de acuerdo al rango enviado de fechas.
     * @param $fechaIni
     * @param $fechaFin
     * @return Resultset
     */
    public function getListadoFechas($fechaIni, $fechaFin)
    {
        $sql = "SELECT * FROM f_listado_fechas_rango('$fechaIni','$fechaFin')";
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del
     * @param $idRelaboral
     * @param $fechaIni
     * @param $fechaFin
     * @return Resultset
     */
    public function getReportTransposed($idRelaboral, $fechaIni, $fechaFin)
    {
        if ($idRelaboral > 0 && $fechaIni != '' && $fechaFin != '') {
            $sql = "SELECT * FROM f_horariosymarcaciones_transpuesta_rango_fechas($idRelaboral,'$fechaIni','$fechaFin')";
            $this->_db = new Fhorariosymarcaciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
        return array();
    }

    /**
     * Función para la obtención del la fila correspondiente al cálculo de totales de la tabla transpuesta.
     * @param $idRelaboral
     * @param $fechaIni
     * @param $fechaFin
     * @param string $where
     * @param string $group
     * @return array|Resultset
     */
    public function getReportTransposedTotals($idRelaboral, $fechaIni, $fechaFin, $where = '', $group = '')
    {
        if ($idRelaboral > 0 && $fechaIni != '' && $fechaFin != '') {
            $sql = "SELECT * FROM f_horariosymarcaciones_calculos_solo_totales_rango_fechas($idRelaboral,'$fechaIni','$fechaFin')";
            $this->_db = new Fhorariosymarcaciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (count($arr) > 0) {
                return $arr[0];
            }
        }
        return null;
    }

    /**
     * Función para generar el registro de marcaciones efectivas.
     * @param $idRelaborales
     * @param $gestion
     * @param $mes
     * @param $primerDia
     * @param $ultimoDia
     * @param $idUsuario
     * @return null
     */
    public function calculoEfectivas($idRelaborales, $gestion, $mes, $primerDia, $ultimoDia, $idUsuario)
    {
        if ($idRelaborales != '' && $gestion > 0 && $mes > 0 && $primerDia > 0 && $ultimoDia > 0) {
            $sql = "select f_calculo_efectivas AS o_resultado from f_calculo_efectivas('" . $idRelaborales . "',$gestion,$mes,$primerDia,$ultimoDia,$idUsuario)";
            //echo $sql;
            $this->_db = new Fhorariosymarcaciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (count($arr) > 0) {
                return $arr[0]->o_resultado;
            }
        }
        return null;
    }

    /**
     * Función para obtener el resumen de horarios y marcaciones para exportación en formato CSV.
     * @param $idRelaborales
     * @param $gestion
     * @param $mesIni
     * @param $mesFin
     * @return null|Resultset
     */
    public function obtenerResumen($idRelaborales, $gestion, $mesIni, $mesFin)    {
        if ($idRelaborales != '' && $gestion > 0 && $mesIni > 0 && $mesFin > 0) {
            $sql = "SELECT * FROM f_horariosymarcaciones_puros_rango_fechas('".$idRelaborales."',$gestion,$mesIni,$mesFin)";
            //echo $sql;
            $this->_db = new Horariosymarcaciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
        return null;
    }
    /**
     * Obtiene la cantidad de días de diferencia entre dos fechas.
     * @param $primera
     * @param $segunda
     * @param string $sep
     * @return int
     */
    public function compararDosFechas($primera, $segunda, $sep = "-")
    {
        $valoresPrimera = explode($sep, $primera);
        $valoresSegunda = explode($sep, $segunda);
        $diaPrimera = $valoresPrimera[0];
        $mesPrimera = $valoresPrimera[1];
        $anyoPrimera = $valoresPrimera[2];
        $diaSegunda = $valoresSegunda[0];
        $mesSegunda = $valoresSegunda[1];
        $anyoSegunda = $valoresSegunda[2];
        $diasPrimeraJuliano = gregoriantojd($mesPrimera, $diaPrimera, $anyoPrimera);
        $diasSegundaJuliano = gregoriantojd($mesSegunda, $diaSegunda, $anyoSegunda);
        if (!checkdate($mesPrimera, $diaPrimera, $anyoPrimera)) {
            // "La fecha ".$primera." no es válida";
            return 0;
        } elseif (!checkdate($mesSegunda, $diaSegunda, $anyoSegunda)) {
            // "La fecha ".$segunda." no es válida";
            return 0;
        } else {
            return $diasPrimeraJuliano - $diasSegundaJuliano;
        }
    }
    /**
     * Función para obtener el resumen de horarios y marcaciones para exportación en formato CSV.
     * @param $idRelaborales
     * @param $gestion
     * @param $mesIni
     * @param $mesFin
     * @return null|Resultset
     */
    public function obtenerReporteEnRango($idRelaboral, $fechaIni, $fechaFin)    {
        if ($idRelaboral > 0 && $fechaIni != "" && $fechaFin !="") {
            $sql = "SELECT * FROM f_horariosymarcaciones_calculos_rango_fechas(".$idRelaboral.",'$fechaIni','$fechaFin')";
            //echo $sql;
            $this->_db = new Fhorariosymarcaciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
        return null;
    }
}