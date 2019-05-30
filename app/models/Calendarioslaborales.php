<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  07-01-2015
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Calendarioslaborales extends \Phalcon\Mvc\Model
{
    private $_db;
    public function initialize() {
        $this->_db = new Calendarioslaborales();
        //   parent::initialize();
    }
    /**
     * Función para la modificación del estado de los registros de turnos de un calendario al estado EN ELABORACIÓN,
     * de acuerdo a los parámetros enviados.
     * @param $fechaIni
     * @param $fechaFin
     * @param $finDeSemana
     * @return Resultset
     */
    public function retornaEstadoElaboracion($idUsuarioModificador,$idPerfilLaboral,$fechaIni,$fechaFin){
        $sql = "SELECT f_retorna_estado_elaboracion AS resultado,CASE WHEN f_retorna_estado_elaboracion=1 THEN 'Exito: La modificaci&oacuate;n se realiz&oacute; de forma satisfactoria.'";
        $sql .= " ELSE CAST('Error: No se pudo realizar la modificaci&oacute;n' AS CHARACTER VARYING) END AS msje FROM f_retorna_estado_elaboracion(".$idUsuarioModificador.",".$idPerfilLaboral.",'".$fechaIni."','".$fechaFin."')";
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la modificación del estado de los registros de turnos de un calendario al estado EN ELABORACIÓN.
     * Lo particular de este método se debe a que es aplicable a los tipos de horarios CONTINUO, donde sólo se puede establecer
     * que horarios en especifico cambiar y no dar rangos.
     * @param $idUsuarioModificador
     * @param $idPerfilLaboral
     * @param $fechaIni
     * @param $fechaFin
     * @return Resultset
     */
    public function retornaEstadoElaboracionPorIdsCalendarios($idUsuarioModificador,$idPerfilLaboral,$idCalendarios){
        $sql = "SELECT f_retorna_estado_elaboracion_por_ids_calendarios AS resultado,CASE WHEN f_retorna_estado_elaboracion_por_ids_calendarios=1 THEN 'Exito: La modificaci&oacuate;n se realiz&oacute; de forma satisfactoria.'";
        $sql .= " ELSE CAST('Error: No se pudo realizar la modificaci&oacute;n' AS CHARACTER VARYING) END AS msj FROM f_retorna_estado_elaboracion_por_ids_calendarios(".$idUsuarioModificador.",".$idPerfilLaboral.",'".$idCalendarios."')";
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para conocer si una hora se encuentra dentro de un rango de horas.
     * @param $hora
     * @param $hora_ini
     * @param $hora_fin
     * @return Resultset
     */
    public function verificaHoraEnRango($hora,$hora_ini,$hora_fin){
        if($hora!=''&&$hora_ini!=''&&$hora_fin!=''){
            $sql = "SELECT f_verifica_hora_en_rango AS resultado,CASE WHEN f_verifica_hora_en_rango=1 THEN 'La hora ".$hora." se encuentra dentro del rango entre ".$hora_ini." A ".$hora_fin.".'";
            $sql .= " ELSE 'La hora ".$hora." NO se encuentra dentro del rango entre ".$hora_ini." A ".$hora_fin.".' END AS msj FROM f_verifica_hora_en_rango('".$hora."','".$hora_ini."','".$hora_fin."')";
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para conocer si una determinada fecha y hora se encuentra en el rango de dos fechas y horas.
     * @param $fechaHora
     * @param $fechaHoraIni
     * @param $fechaHoraFin
     * @return int
     */
    public function verificaFechaHoraEnRango($fechaHora, $fechaHoraIni, $fechaHoraFin){
        if($fechaHora!=''&&$fechaHoraIni!=''&&$fechaHoraFin!=''){
            $sql = "SELECT (CASE CAST('$fechaHora' AS TIMESTAMP WITHOUT TIME ZONE) BETWEEN CAST('$fechaHoraIni' AS TIMESTAMP WITHOUT TIME ZONE) AND CAST('$fechaHoraFin' AS TIMESTAMP WITHOUT TIME ZONE) WHEN TRUE THEN 1 ELSE 0 END) AS o_resultado";
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if(count($arr)>0){
                return $arr[0]->o_resultado;
            }
        }
        return -1;
    }
    /**
     * Función para obtener el par fecha de inicio y finalización de un calendario en una gestión y mes determinados.
     * Esta función es útil para aquellos casos en que existe marcaciones cruzadas de un mes a otro.
     * @param $gestion
     * @param $mes
     * @return Resultset
     */
    public function getFechaIniFinCalendar($gestion,$mes){
        if($gestion>0&&$mes>0) {
            $sql = "SELECT * FROM f_obtener_fecha_ini_fin_calendario($gestion,$mes) ";
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para la obtención del último identificador del registro de calendarios laborales en el día previo.
     * Esta función es útil para aquellos casos en que existe marcaciones cruzadas de un mes a otro.
     * @param $idRelaboral
     * @param $fecha
     * @return integer|null
     */
    public function getUltimoIdCalendarioLaboralEntradaDiaPrevio($idRelaboral,$fecha){
        if($idRelaboral>0&&$fecha!=''){
            $sql = "SELECT id_calendariolaboral FROM f_calendario_laboral_registrado_por_relaboral(0,$idRelaboral)";
            $sql .= "WHERE (CAST((DATE '".$fecha."' - INTERVAL '1 DAY') AS DATE)) BETWEEN calendario_fecha_ini and calendario_fecha_fin ";
            $sql .= "ORDER BY hora_entrada DESC LIMIT 1";
            $res = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $res[0]->id_calendariolaboral;
        }else return null;
    }

    /**
     * Función para la obtención de la última hora de entrada del registro de calendarios laborales en el día previo.
     * Esta función es útil para aquellos casos en que existe marcaciones cruzadas de un mes a otro.
     * @param $idRelaboral
     * @param $fecha
     * @return integer|null
     */
    public function getUltimaHoraSalidaPendienteDiaPrevio($idRelaboral,$fecha){
        if($idRelaboral>0&&$fecha!=''){
            $sql = "SELECT hora_salida FROM f_calendario_laboral_registrado_por_relaboral(0,$idRelaboral)";
            $sql .= "WHERE (CAST((DATE '".$fecha."' - INTERVAL '1 DAY') AS DATE)) BETWEEN calendario_fecha_ini and calendario_fecha_fin ";
            $sql .= "ORDER BY hora_entrada DESC LIMIT 1";
            $res = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $res[0]->hora_salida;
        } return null;
    }

    /**
     * Función para la obtención de la hora de salida en función del identificador de calendario.
     * @param $idCalendarioLaboral
     * @return null
     */
    public function getHoraSalidaPorIdCalendario($idCalendarioLaboral){
        if($idCalendarioLaboral>0){
            $sql = "SELECT hl.hora_salida FROM calendarioslaborales cl
                    INNER JOIN horarioslaborales hl ON cl.horariolaboral_id = hl.id
                    WHERE cl.id=".$idCalendarioLaboral;
            $res = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $res[0]->hora_salida;
        } return null;
    }
    /**
     * Función para la obtención del identificador del registro de horario laboral correspondiente al día previo.
     * Esta función es útil para aquellos casos en que existe marcaciones cruzadas de un mes a otro.
     * @param $idRelaboral
     * @param $fecha
     * @return null
     */
    public function getUltimoIdHorarioLaboralPendienteDiaPrevio($idRelaboral,$fecha){
        if($idRelaboral>0&&$fecha!=''){
            $sql = "SELECT id_horariolaboral FROM f_calendario_laboral_registrado_por_relaboral(0,$idRelaboral)";
            $sql .= "WHERE (CAST((DATE '".$fecha."' - INTERVAL '1 DAY') AS DATE)) BETWEEN calendario_fecha_ini and calendario_fecha_fin ";
            $sql .= "ORDER BY hora_entrada DESC LIMIT 1";
            $res = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $res[0]->id_horariolaboral;
        } return null;
    }

    /**
     * Función para obtener la fecha del día previo a una fecha determinada.
     * @param $fecha
     * @return null
     */
    public function getFechaDiaPrevio($fecha){
        if($fecha!=''){
            $sql = "SELECT CAST(CAST('$fecha' AS DATE) - INTERVAL '1 DAY' AS DATE) AS o_fecha";
            $res = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $res[0]->o_fecha;
        } return null;
    }
}
