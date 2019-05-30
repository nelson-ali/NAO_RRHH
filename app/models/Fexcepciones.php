<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  26-02-2014
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Fexcepciones extends \Phalcon\Mvc\Model
{
    public $id;
    public $excepcion;
    public $tipoexcepcion_id;
    public $tipo_excepcion;
    public $codigo;
    public $color;
    public $descuento;
    public $descuento_descripcion;
    public $compensatoria;
    public $compensatoria_descripcion;
    public $genero_id;
    public $genero;
    public $cantidad;
    public $unidad;
    public $fraccionamiento;
    public $frecuencia_descripcion;
    public $redondeo;
    public $redondeo_descripcion;
    public $horario;
    public $horario_descripcion;
    public $refrigerio;
    public $refrigerio_descripcion;
    public $observacion;
    public $estado;
    public $estado_descripcion;
    public $baja_logica;
    public $agrupador;
    public $user_reg_id;
    public $fecha_reg;
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
            'id' => 'id',
            'excepcion' => 'excepcion',
            'tipoexcepcion_id' => 'tipoexcepcion_id',
            'tipo_excepcion' => 'tipo_excepcion',
            'codigo' => 'codigo',
            'color' => 'color',
            'descuento' => 'descuento',
            'descuento_descripcion' => 'descuento_descripcion',
            'compensatoria' => 'compensatoria',
            'compensatoria_descripcion' => 'compensatoria_descripcion',
            'genero_id' => 'genero_id',
            'genero' => 'genero',
            'cantidad' => 'cantidad',
            'unidad' => 'unidad',
            'fraccionamiento' => 'fraccionamiento',
            'frecuencia_descripcion' => 'frecuencia_descripcion',
            'redondeo' => 'redondeo',
            'redondeo_descripcion' => 'redondeo_descripcion',
            'horario' => 'horario',
            'horario_descripcion' => 'horario_descripcion',
            'refrigerio' => 'refrigerio',
            'refrigerio_descripcion' => 'refrigerio_descripcion',
            'observacion' => 'observacion',
            'estado' => 'estado',
            'estado_descripcion' => 'estado_descripcion',
            'baja_logica' => 'baja_logica',
            'agrupador' => 'agrupador',
            'user_reg_id' => 'user_reg_id',
            'fecha_reg' => 'fecha_reg',
            'user_mod_id' => 'user_mod_id',
            'fecha_mod' => 'fecha_mod'
        );
    }

    private $_db;

    /**
     * Función para la obtención del listado de registros de excepciones.
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function getAll($where = '', $group = '')
    {
        $sql = "SELECT * FROM f_excepciones()";
        if ($where != '') $sql .= $where;
        if ($group != '') $sql .= $group;
        $this->_db = new Fexcepciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
    /**
     * Función para la obtención del listado de registros de excepciones considerando el filtro de excepciones.
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function getAllByRelaboral($idRelaboral,$boleta=null,$genero=null,$where = '', $group = '')
    {
        $sql = "SELECT * FROM f_excepciones_filtros(";
        $sql .= $idRelaboral.",";
        if($boleta!=null)$sql .= $boleta.",";else"null,";
        if($genero!=null)$sql .= "'".$genero."'";else"null";
        $sql .= ") ";
        if ($where != '') $sql .= $where;
        if ($group != '') $sql .= $group;
        $this->_db = new Fexcepciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
    /**
     * Función para la obtención del registro de excepción
     * @param $idExcepcion
     * @return Resultset
     */
    public function getOne($idExcepcion)
    {
        if ($idExcepcion > 0) {
            $sql = "SELECT * FROM f_excepciones_por_id(" . $idExcepcion . ")";
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $arr[0];
        }
    }

    /**
     * Función para la obtención de la cantidad de veces se ha usado un tipo específico de excepción en un rango definido de fechas para una persona en particular
     * considerando también un registro de relación laboral determinado.
     * @param $idRelaboral
     * @param $idExcepcion
     * @param $fechaIniRango
     * @param $fechaFinRango
     * @return int
     */
    public function calculaCantidadVecesEnRangoFechas($idRelaboral, $idExcepcion, $idControlExcepcion = 0, $fechaIniRango, $fechaFinRango)
    {
        $cantidad = 0;
        if ($idRelaboral > 0 && $idExcepcion > 0 && $fechaIniRango != '' && $fechaFinRango != '') {
            $sql = "SELECT COUNT(DISTINCT id_controlexcepcion) AS cantidad_veces FROM f_controlexcepciones_relaboral_rango($idRelaboral,'" . $fechaIniRango . "','" . $fechaFinRango . "') ";
            $sql .= "WHERE excepcion_id=" . $idExcepcion . " AND id_controlexcepcion!=" . $idControlExcepcion;
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            $cantidad = $arr[0]->cantidad_veces;
        }
        return $cantidad;
    }

    /**
     * Función para la obtención de la cantidad de veces se ha usado un tipo específico de excepción en un rango definido de fechas para una persona en particular.
     * @param $idPersona
     * @param $idExcepcion
     * @param int $idControlExcepcion
     * @param $fechaIniRango
     * @param $fechaFinRango
     * @return int
     */
    public function calculaCantidadVecesEnRangoFechasPorPersona($idPersona, $idExcepcion, $idControlExcepcion = 0, $fechaIniRango, $fechaFinRango)
    {
        $cantidad = 0;
        if ($idPersona > 0 && $idExcepcion > 0 && $fechaIniRango != '' && $fechaFinRango != '') {
            $sql = "SELECT COUNT(DISTINCT id_controlexcepcion) AS cantidad_veces FROM f_controlexcepciones_persona_rango($idPersona,'" . $fechaIniRango . "','" . $fechaFinRango . "') ";
            $sql .= "WHERE excepcion_id=" . $idExcepcion . " AND id_controlexcepcion!=" . $idControlExcepcion;
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            $cantidad = $arr[0]->cantidad_veces;
        }
        return $cantidad;
    }

    /**
     * Función para la obtención de la cantidad de días usados con un tipo específico de excepción en un rango definido de fechas
     * considerando un registro de relación laboral específico.
     * @param $idRelaboral
     * @param $idExcepcion
     * @param $fechaIniRango
     * @param $fechaFinRango
     * @return int
     */
    public function calculaCantidadDiasEnRangoFechas($idRelaboral, $idExcepcion, $idControlExcepcion = 0, $fechaIniRango, $fechaFinRango)
    {
        $cantidad = 0;
        if ($idRelaboral > 0 && $idExcepcion > 0 && $fechaIniRango != '' && $fechaFinRango != '') {
            $sql = "SELECT COUNT(id_relaboral) AS cantidad_dias FROM f_controlexcepciones_relaboral_rango($idRelaboral,'" . $fechaIniRango . "','" . $fechaFinRango . "') ";
            $sql .= "WHERE excepcion_id=" . $idExcepcion . " AND id_controlexcepcion!=" . $idControlExcepcion;
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            $cantidad = $arr[0]->cantidad_dias;
        }
        return $cantidad;
    }

    /**
     * Función para verificar la existencia de un registro de excepción en el día previo y posterior.
     * @param $idPersona
     * @param $idExcepcion
     * @param int $idControlExcepcion
     * @param $fecha
     * @return int
     */
    public function verificaExistenciaExcepcionEnDiaPrevioYPosterior($idPersona, $idExcepcion, $idControlExcepcion = 0, $fecha)
    {
        $cantidad = 0;
        if ($idPersona > 0 && $idExcepcion > 0 && $fecha != '') {
            $sql = "SELECT COUNT(id_relaboral) AS cantidad_dias FROM f_controlexcepciones_persona_rango($idPersona,CAST(CAST('" . $fecha . "' AS DATE) - interval '1 DAYS' as DATE),CAST(CAST('" . $fecha . "' AS DATE) + interval '1 DAYS' as DATE)) ";
            $sql .= "WHERE excepcion_id=" . $idExcepcion . " AND id_controlexcepcion!=" . $idControlExcepcion;
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            $cantidad = $arr[0]->cantidad_dias;
        }
        return $cantidad;
    }
    /**
     * Función para verificar la existencia de un registro de excepción de un tipo en una determinada fecha.
     * @param $idRelaboral
     * @param $idExcepcion
     * @param int $idControlExcepcion
     * @param $fecha
     * @return int
     */
    public function verificaExistenciaExcepcionEnDiaPrevio($idRelaboral, $idExcepcion, $idControlExcepcion = 0, $fecha)
    {
        $cantidad = 0;
        if ($idRelaboral > 0 && $idExcepcion > 0 && $fecha != '') {
            $sql = "SELECT COUNT(id_relaboral) AS cantidad_dias FROM f_controlexcepciones_relaboral_rango($idRelaboral,CAST(CAST('" . $fecha . "' AS DATE) + interval '1 DAYS' as DATE),CAST(CAST('" . $fecha . "' AS DATE) + interval '1 DAYS' as DATE)) ";
            $sql .= "WHERE excepcion_id=" . $idExcepcion . " AND id_controlexcepcion!=" . $idControlExcepcion;
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            $cantidad = $arr[0]->cantidad_dias;
        }
        return $cantidad;
    }

    public function verificaExistenciaExcepcionEnDiaPosterior($idRelaboral, $idExcepcion, $idControlExcepcion = 0, $fecha)
    {
        $cantidad = 0;
        if ($idRelaboral > 0 && $idExcepcion > 0 && $fecha != '') {
            $sql = "SELECT COUNT(id_relaboral) AS cantidad_dias FROM f_controlexcepciones_relaboral_rango($idRelaboral,CAST(CAST('" . $fecha . "' AS DATE) - interval '1 DAYS' as DATE),CAST(CAST('" . $fecha . "' AS DATE) - interval '1 DAYS' as DATE)) ";
            $sql .= "WHERE excepcion_id=" . $idExcepcion . " AND id_controlexcepcion!=" . $idControlExcepcion;
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            $cantidad = $arr[0]->cantidad_dias;
        }
        return $cantidad;
    }

    /**
     * Función para la obtención de la cantidad de días usados con un tipo específico de excepción en un rango definido de fechas por una persona.
     * considerando una persona en particular y todos sus registros de relación laborales válidos.
     * @param $idPersona
     * @param $idExcepcion
     * @param int $idControlExcepcion
     * @param $fechaIniRango
     * @param $fechaFinRango
     * @return int
     */
    public function calculaCantidadDiasEnRangoFechasPorPersona($idPersona, $idExcepcion, $idControlExcepcion = 0, $fechaIniRango, $fechaFinRango)
    {
        $cantidad = 0;
        if ($idPersona > 0 && $idExcepcion > 0 && $fechaIniRango != '' && $fechaFinRango != '') {
            $sql = "SELECT COUNT(id_relaboral) AS cantidad_dias FROM f_controlexcepciones_persona_rango($idPersona,'" . $fechaIniRango . "','" . $fechaFinRango . "') ";
            $sql .= "WHERE excepcion_id=" . $idExcepcion . " AND id_controlexcepcion!=" . $idControlExcepcion;
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            $cantidad = $arr[0]->cantidad_dias;
        }
        return $cantidad;
    }

    /**
     * Función para conocer el listado de las semanas involucradas en los registros de excepción de un tipo determinado de excepción para una persona
     * en relación a su registro de relacíon laboral.
     * @param $idRelaboral
     * @param $idExcepcion
     * @param int $idControlExcepcion
     * @param $fechaIniRango
     * @param $fechaFinRango
     * @return int
     */
    public function calculaCantidadSemanasEnRangoFechas($idRelaboral, $idExcepcion, $idControlExcepcion = 0, $fechaIniRango, $fechaFinRango)
    {
        $cantidad = 0;
        if ($idRelaboral > 0 && $idExcepcion > 0 && $fechaIniRango != '' && $fechaFinRango != '') {
            $sql = "SELECT count(distinct fl.fecha_ini) AS cantidad_semanas FROM f_listado_fechas_dobles_rango_semana(1,'" . $fechaIniRango . "','" . $fechaFinRango . "') fl ";
            $sql .= "INNER JOIN f_controlexcepciones_relaboral_rango(" . $idRelaboral . ",fl.fecha_ini,fl.fecha_fin) fc ON fc.fecha_ini IS NOT NULL ";
            $sql .= "WHERE excepcion_id=" . $idExcepcion . " AND id_controlexcepcion!=" . $idControlExcepcion;
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            $cantidad = $arr[0]->cantidad_semanas;
        }
        return $cantidad;
    }

    /**
     * Función para conocer el listado de las semanas involucradas en los registros de excepción de un tipo determinado de excepción para un persona en particular.
     * @param $idPersona
     * @param $idExcepcion
     * @param int $idControlExcepcion
     * @param $fechaIniRango
     * @param $fechaFinRango
     * @return int
     */
    public function calculaCantidadSemanasEnRangoFechasPorPersona($idPersona, $idExcepcion, $idControlExcepcion = 0, $fechaIniRango, $fechaFinRango)
    {
        $cantidad = 0;
        if ($idPersona > 0 && $idExcepcion > 0 && $fechaIniRango != '' && $fechaFinRango != '') {
            $sql = "SELECT count(distinct fl.fecha_ini) AS cantidad_semanas FROM f_listado_fechas_dobles_rango_semana(1,'" . $fechaIniRango . "','" . $fechaFinRango . "') fl ";
            $sql .= "INNER JOIN f_controlexcepciones_persona_rango(" . $idPersona . ",fl.fecha_ini,fl.fecha_fin) fc ON fc.fecha_ini IS NOT NULL ";
            $sql .= "WHERE excepcion_id=" . $idExcepcion . " AND id_controlexcepcion!=" . $idControlExcepcion;
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            $cantidad = $arr[0]->cantidad_semanas;
        }
        return $cantidad;
    }

    /**
     * Función para conocer el listado de meses involucrados en los registros de excepción de un tipo determinado de excepción para una persona, considerando
     * un registro de relación laboral específico.
     * @param $idRelaboral
     * @param $idExcepcion
     * @param int $idControlExcepcion
     * @param $fechaIniRango
     * @param $fechaFinRango
     * @return int
     */
    public function calculaCantidadMesesEnRangoFechas($idRelaboral, $idExcepcion, $idControlExcepcion = 0, $fechaIniRango, $fechaFinRango)
    {
        $cantidad = 0;
        if ($idRelaboral > 0 && $idExcepcion > 0 && $fechaIniRango != '' && $fechaFinRango != '') {
            $sql = "SELECT count(distinct fl.fecha_ini) AS cantidad_semanas FROM f_listado_fechas_dobles_rango('" . $fechaIniRango . "','" . $fechaFinRango . "') fl ";
            $sql .= "INNER JOIN f_controlexcepciones_relaboral_rango(" . $idRelaboral . ",fl.fecha_ini,fl.fecha_fin) fc ON fc.fecha_ini IS NOT NULL ";
            $sql .= "WHERE excepcion_id=" . $idExcepcion . " AND id_controlexcepcion!=" . $idControlExcepcion;
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            $cantidad = $arr[0]->cantidad_semanas;
        }
        return $cantidad;
    }

    /**
     * Función para conocer el listado de meses involucrados en los registros de excepción de un tipo determinado de excepción para una persona en particular.
     * @param $idPersona
     * @param $idExcepcion
     * @param int $idControlExcepcion
     * @param $fechaIniRango
     * @param $fechaFinRango
     * @return int
     */
    public function calculaCantidadMesesEnRangoFechasPorPersona($idPersona, $idExcepcion, $idControlExcepcion = 0, $fechaIniRango, $fechaFinRango)
    {
        $cantidad = 0;
        if ($idPersona > 0 && $idExcepcion > 0 && $fechaIniRango != '' && $fechaFinRango != '') {
            $sql = "SELECT count(distinct fl.fecha_ini) AS cantidad_semanas FROM f_listado_fechas_dobles_rango('" . $fechaIniRango . "','" . $fechaFinRango . "') fl ";
            $sql .= "INNER JOIN f_controlexcepciones_persona_rango(" . $idPersona . ",fl.fecha_ini,fl.fecha_fin) fc ON fc.fecha_ini IS NOT NULL ";
            $sql .= "WHERE excepcion_id=" . $idExcepcion . " AND id_controlexcepcion!=" . $idControlExcepcion;
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            $cantidad = $arr[0]->cantidad_semanas;
        }
        return $cantidad;
    }

    /**
     * Función para la obtención de la cantidad de horas usadas en un rango de fechas de acuerdo a un tipo específico de control de excepción para un registro de relación laboral específico.
     * @param $idRelaboral
     * @param $idExcepcion
     * @param int $idControlExcepcion
     * @param $fechaIniRango
     * @param $fechaFinRango
     * @return int
     */
    public function calculaCantidadHorasEnRangoFechas($idRelaboral, $idExcepcion, $idControlExcepcion = 0, $fechaIniRango, $fechaFinRango)
    {
        $cantidad = 0;
        if ($idRelaboral > 0 && $idExcepcion > 0 && $fechaIniRango != '' && $fechaFinRango != '') {
            $sql = "SELECT (CASE WHEN sum(f_cantidad_horas_entre_dos_horas(hora_ini,hora_fin))>0 THEN sum(f_cantidad_horas_entre_dos_horas(hora_ini,hora_fin)) ELSE 0 END) AS cantidad_horas ";
            $sql .= "FROM f_controlexcepciones_relaboral_rango(" . $idRelaboral . ",'" . $fechaIniRango . "','" . $fechaFinRango . "') ";
            $sql .= "WHERE excepcion_id=" . $idExcepcion . " AND id_controlexcepcion!=" . $idControlExcepcion;
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            $cantidad = $arr[0]->cantidad_horas;
        }
        return $cantidad;
    }

    /**
     * Función para la obtención de la cantidad de horas usadas en un rango de fechas de acuerdo a un tipo específico de control de excepción para una persona en específico.
     * @param $idPersona
     * @param $idExcepcion
     * @param int $idControlExcepcion
     * @param $fechaIniRango
     * @param $fechaFinRango
     * @return int
     */
    public function calculaCantidadHorasEnRangoFechasPorPersona($idPersona, $idExcepcion, $idControlExcepcion = 0, $fechaIniRango, $fechaFinRango)
    {
        $cantidad = 0;
        //if ($idPersona > 0 && $idExcepcion > 0 && $fechaIniRango != '' && $fechaFinRango != '') {
            $sql = "SELECT (CASE WHEN sum(f_cantidad_horas_entre_dos_horas(hora_ini,hora_fin))>0 THEN sum(f_cantidad_horas_entre_dos_horas(hora_ini,hora_fin)) ELSE 0 END) AS cantidad_horas ";
            $sql .= "FROM f_controlexcepciones_persona_rango(" . $idPersona . ",'" . $fechaIniRango . "','" . $fechaFinRango . "') ";
            $sql .= "WHERE excepcion_id=" . $idExcepcion . " AND id_controlexcepcion!=" . $idControlExcepcion;
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            $cantidad = $arr[0]->cantidad_horas;
        //}
        return $cantidad;
    }

    /**
     * Función para la obtención del rango de fechas en una semana, en las cuales se encuentra una fecha específica.
     * @param $opcion
     * @param $fecha
     */
    public function obtieneRangoFechasDeLaSemana($opcion, $fecha)
    {
        if ($fecha != '') {
            $arr = explode("-", $fecha);
            $gestion = $arr[2];
            $gestionAnterior = $gestion - 1;
            /**
             * A objeto de considerar todas las semanas que implican la gestión
             */
            $fechaIni = "25-12-" . $gestionAnterior;
            $fechaFin = "31-12-" . $gestion;
            $sql = "SELECT * FROM f_listado_fechas_dobles_rango_semana(" . $opcion . ",'" . $fechaIni . "','" . $fechaFin . "')";
            $sql .= "WHERE '" . $fecha . "' BETWEEN fecha_ini AND fecha_fin";
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $arr[0];
        }
        return null;
    }

    /**
     * Función para obtener el listado de rangos de fechas semanales, de acuerdo al rango de fechas enviadas como parámetros.
     * Si el parámetro $opcion =0 : Considerar Lunes a Viernes, $opcion=1:Considerar de Lunes a Domingo.
     * @param $opcion
     * @param $fechaIni
     * @param $fechaFin
     * @return null
     */
    public function obtieneRangoSemanasPorRangoFechas($opcion, $fechaIni, $fechaFin)
    {
        if ($fechaIni != '' && $fechaFin != '') {
            $sql = "SELECT * FROM f_listado_fechas_dobles_rango_semana(" . $opcion . ",'" . $fechaIni . "','" . $fechaFin . "')";
            $this->_db = new Fexcepciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
        return null;
    }

    /**
     * Función para la obtención de la fecha del último
     * @param $gestion
     * @param $mes
     * @return null
     */
    public function obtieneUltimoDiaMes($gestion, $mes)
    {
        if ($gestion > 0 && $mes > 0) {
            $sql = "SELECT f_ultimo_dia_mes FROM f_ultimo_dia_mes(" . $mes . "," . $gestion . ")";
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $arr[0]->f_ultimo_dia_mes;
        }
        return null;
    }

    /**
     * Función para conocer la cantidad de meses involucrados en un rango de fechas.
     * @param $fechaIni
     * @param $fechaFin
     * @return int
     */
    public function cantidadMesesInvolucradosEnRango($fechaIni, $fechaFin)
    {
        if ($fechaIni != '' && $fechaFin != '') {
            $sql = "SELECT COUNT(*) AS cantidad FROM f_listado_fechas_dobles_rango('" . $fechaIni . "','" . $fechaFin . "')";
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $arr[0]->cantidad;
        } else return 0;
    }

    /**
     * Función para obtener el listado de fechas que referencian a cada mes involucrado en un rago de fechas.
     * @param $fechaIni
     * @param $fechaFin
     * @return null|Resultset
     */
    public function listadoMesesInvolucradosEnRango($fechaIni, $fechaFin)
    {
        if ($fechaIni != '' && $fechaFin != '') {
            $sql = "SELECT * FROM f_listado_fechas_dobles_rango('" . $fechaIni . "','" . $fechaFin . "'))";
            $this->_db = new Fexcepciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        } else return null;
    }

    /**
     * Función para obtener el listado de fechas que referencian a cada mes completo involucrado en un rago de fechas.
     * @param $fechaIni
     * @param $fechaFin
     * @return null|Resultset
     */
    public function listadoMesesCompletosInvolucradosEnRango($fechaIni, $fechaFin)
    {
        if ($fechaIni != '' && $fechaFin != '') {
            $arrA = explode("-", $fechaIni);
            if (count($arrA) > 0) {
                $gestionA = $arrA[2];
                $mesA = $arrA[1];
            }
            $arrB = explode("-", $fechaFin);
            if (count($arrA) > 0) {
                $gestionB = $arrB[2];
                $mesB = $arrB[1];
            }
            $sql = "SELECT * FROM f_listado_fechas_dobles_rango(CAST('01-" . $mesA . "-" . $gestionA . "' AS DATE),f_ultimo_dia_mes(" . $mesB . "," . $gestionB . "))";
            $this->_db = new Fexcepciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        } else return null;
    }

    /**
     * Función para calcular en que semestre se encuentra una fecha determinada
     * @param $fechaIni
     * @return int
     */
    public function semestrePerteneciente($fechaIni)
    {
        $resultado = 0;
        if ($fechaIni != '') {
            $arr = explode("-", $fechaIni);
            if (count($arr) > 0) {
                $mes = $arr[1];
                if ($mes < 7) $resultado = 1;
                else $resultado = 2;
            }
        }
        return $resultado;
    }

    /**
     * Función para la obtención del listado de fechas día por día de una fecha inicial a una fecha final.
     * @param $fechaIni
     * @param $fechaFin
     */
    public function listadoFechasPorRango($fechaIni, $fechaFin)
    {
        if ($fechaIni != '' && $fechaFin != '') {
            $sql = "SELECT * FROM f_listado_fechas_rango('" . $fechaIni . "','" . $fechaFin . "')";
            $this->_db = new Fexcepciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para obtener la cantidad de horas entre dos horas.
     * @param $horaIni
     * @param $horaFin
     * @return int
     */
    public function cantidadHorasEntreDosHoras($horaIni, $horaFin)
    {
        if ($horaIni != '' && $horaFin != '') {
            $sql = "SELECT o_resultado FROM f_cantidad_horas_entre_dos_horas('" . $horaIni . "','" . $horaFin . "')";
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $arr[0]->o_resultado;
        }
        return 0;
    }

    /**
     * Función para obtener la cantidad de horas entre dos fechas.
     * @param $fechaHoraIni
     * @param null $fechaHoraFin
     * @return int
     */
    public function cantidadHorasEntreDosFechas($fechaHoraIni, $fechaHoraFin = null)
    {
        if ($fechaHoraIni != '') {
            $sql = "SELECT o_resultado FROM f_horas_transcurridas_entre_dos_fechas(cast('" . $fechaHoraIni . "' as timestamp),";
            if ($fechaHoraFin == null || $fechaHoraFin == '')
                $sql .= "CAST(CURRENT_TIMESTAMP AS TIMESTAMP))";
            else $sql .= "CAST('" . $fechaHoraFin . "' AS TIMESTAMP))";
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $arr[0]->o_resultado;
        }
        return 0;
    }

    /**
     * Función para obtener la cantidad de minutos entre dos fechas.
     * @param $fechaHoraIni
     * @param null $fechaHoraFin
     * @return int
     */
    public function cantidadMinutosEntreDosFechas($fechaHoraIni, $fechaHoraFin = null)
    {
        if ($fechaHoraIni != '') {
            $sql = "SELECT o_resultado FROM f_minutos_transcurridos_entre_dos_fechas(cast('" . $fechaHoraIni . "' as timestamp),";
            if ($fechaHoraFin == null || $fechaHoraFin == '')
                $sql .= "CAST(CURRENT_TIMESTAMP AS TIMESTAMP))";
            else $sql .= "CAST('" . $fechaHoraFin . "' AS TIMESTAMP))";
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $arr[0]->o_resultado;
        }
        return 0;
    }

    /**
     * Función para la obtención de la cantidad de días entre dos fechas.
     * En caso de considerarse nulo el valor de la fecha final se realiza la comparación con la fecha actual del servidor de la Base de Datos.
     * @param $fechaIni
     * @param null $fechaFin
     * @return int
     */
    public function cantidadDiasEntreDosFechas($fechaIni, $fechaFin = null)
    {
        if ($fechaIni != '') {
            $sql = "SELECT f_cantidad_dias_entre_dos_fechas AS o_resultado FROM f_cantidad_dias_entre_dos_fechas('" . $fechaIni . "',";
            if ($fechaFin == null || $fechaFin == '')
                $sql .= "CURRENT_DATE";
            else $sql .= "'" . $fechaFin . "')";
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $arr[0]->o_resultado;
        }
        return 0;
    }

    /**
     * Función para conocer las horas laborales en un determinado día para una persona de acuerdo a su registro de relación laboral.
     * @param $idRelaboral
     * @param $gestion
     * @param $mes
     * @param $dia
     * @return int
     */
    public function cantidadHorasLaboralesPorRelaboral($idRelaboral, $gestion, $mes, $dia)
    {
        if ($idRelaboral > 0 && $gestion > 0 && $mes > 0 && $dia > 0) {
            $sql = "SELECT f_cantidad_horas_laborales_dia_relaboral AS o_resultado FROM f_cantidad_horas_laborales_dia_relaboral($idRelaboral,$gestion,$mes,$dia)";
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $arr[0]->o_resultado;
        }
        return 0;
    }

    /**
     * Función para conocer las horas laborales en un determinado día para una persona en particular.
     * @param $idPersona
     * @param $gestion
     * @param $mes
     * @param $dia
     * @return int
     */
    public function cantidadHorasLaboralesPorPersona($idPersona, $gestion, $mes, $dia)
    {
        if ($idPersona > 0 && $gestion > 0 && $mes > 0 && $dia > 0) {
            $sql = "SELECT f_cantidad_horas_laborales_dia_relaboral_por_persona AS o_resultado FROM f_cantidad_horas_laborales_dia_relaboral_por_persona($idPersona,$gestion,$mes,$dia)";
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $arr[0]->o_resultado;
        }
        return 0;
    }

    /**
     * Función para la obtención del identificador de excepción agregado por 'DIA MEDIO DIA' para un tipo de excepción determinado.
     * La referencia 'DIA MEDIO DIA' se refiere a la sumatoria de aplicación de excepciones.
     * Ejemplo: En caso de que se solicite el cálculo de dias acumulados por permisos anuales de día completo, también se tomarán en cuenta los de medio día
     * pues en una excepción del mismo tipo sólo que se ha disgregado su aplicación.
     * @param $idExcepcion
     * @return int
     */
    public function obtieneExcepcionAgregadaDiaMedioDia($idExcepcion)
    {
        if ($idExcepcion > 0) {
            $sql = "SELECT f_excepcion_agregada_dia_medio_dia as id_excepcion FROM f_excepcion_agregada_dia_medio_dia($idExcepcion)";
            $this->_db = new Fexcepciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $arr[0]->id_excepcion;
        }
        return 0;
    }
} 