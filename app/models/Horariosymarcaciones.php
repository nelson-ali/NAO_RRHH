<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  09-03-2015
*/

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Horariosymarcaciones extends \Phalcon\Mvc\Model
{

    private $_db;

    public function initialize()
    {
        $this->_db = new Horariosymarcaciones();
    }

    /**
     * Función para el establecimiento del estado PLANILLADO para los registros de horarios y marcaciones de acuerdo a un gestión y mes determinados.
     * Es necesario mencionar que se considera un rango de fechas establecido en la tabla de parámetros para la realización de esta tarea.
     * @param $idRelaboral
     * @param $gestion
     * @param $mes
     * @return bool
     */
    public function planillarHorariosYMarcacionesPorSalarios($idRelaboral, $gestion, $mes)
    {
        if ($idRelaboral > 0 && $gestion > 0 && $mes > 0) {
            $db = $this->getDI()->get('db');
            $res = $db->execute("SELECT f_horariosymarcaciones_planillar AS resultado FROM f_horariosymarcaciones_planillar($idRelaboral,$gestion,$mes)");
            return $res;
        }
        return false;
    }

    /**
     * Función para el establecimiento del estado PLANILLADO para los registros de horarios y marcaciones de acuerdo a un gestión y mes determinados.
     * Es necesario mencionar que se considera un rango de fechas establecido en la tabla de parámetros para la realización de esta tarea.
     * @param $idRelaboral
     * @param $gestion
     * @param $mes
     * @return bool
     */
    public function planillarHorariosYMarcacionesPorRefrigerios($idRelaboral, $gestion, $mes)
    {
        if ($idRelaboral > 0 && $gestion > 0 && $mes > 0) {
            $db = $this->getDI()->get('db');
            $res = $db->execute("SELECT f_horariosymarcaciones_planillar_por_refrigerios AS resultado FROM f_horariosymarcaciones_planillar_por_refrigerios($idRelaboral,$gestion,$mes)");
            return $res;
        }
        return false;
    }
    /**
     * Funcion para la obtencion de la marcacion valida en base al calculo de marcaciones registradas y los datos implicados debido al identificador del horario laboral correspondiente.
     * @param $idRelaboral
     * @param $idMaquina
     * @param $fecha
     * @param $idHorarioLaboral
     * @param int $entradaSalida
     * @return Resultset
     */
    /*public function obtenerHorarioValido($idRelaboral,$fecha,$entradaSalida=0){
        if($idRelaboral>0&&$fecha!=null&&$fecha!=''){
            $sql = "SELECT * FROM f_obtener_marcacion_valida_por_id_horariolaboral($idRelaboral,$idMaquina,'$fecha',$idHorarioLaboral,$entradaSalida) ";
            $this->_db = new Marcaciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }*/
    /**
     * Función para dar de baja lógicamente a registros de horarios y marcaciones.
     * @param int $idUsuario
     * @param int $idRelaboral
     * @param int $gestion
     * @param int $mes
     * @param string $clasemarcacion
     * @param int $operacion
     * @return bool
     */
    public function bajaRegistro($idUsuario = 0, $idRelaboral = 0, $gestion = 0, $mes = 0, $clasemarcacion = "", $operacion = 0)
    {
        if ($idUsuario > 0 && $idRelaboral > 0 && $gestion > 0 && $mes > 0 && $clasemarcacion != "" && $operacion > 0) {
            $db = $this->getDI()->get('db');
            $sqldel = "INSERT INTO horariosymarcaciones_del ";
            $sqldel .= "(";
            $sqldel .= "SELECT hm.*," . $idUsuario . ",current_timestamp,CAST(NULL AS DATE), CAST(NULL AS DATE), CAST(" . $operacion . " AS INTEGER) FROM horariosymarcaciones hm WHERE hm.relaboral_id=" . $idRelaboral;
            $sqldel .= " AND hm.gestion = " . $gestion . " AND hm.mes=" . $mes . " ";
            if ($clasemarcacion == "H" || $clasemarcacion == "M") {
                $sqldel .= "AND hm.clasemarcacion LIKE '" . $clasemarcacion . "'";
            }
            $sqldel .= ")";
            $db->execute($sqldel);
            $sql = "DELETE FROM horariosymarcaciones WHERE gestion = " . $gestion . " and mes = " . $mes . " and relaboral_id = " . $idRelaboral . " ";
            if ($clasemarcacion == "H" || $clasemarcacion == "M") {
                $sql .= "AND clasemarcacion LIKE '" . $clasemarcacion . "'";
            }
            $res = $db->execute($sql);
            return $res;
        }
        return false;
    }

    /**
     * Función para vaciar los datos de un determinado horarioymarcacion relacionado a un registro de relación laboral, gestión, mes y clasemarcacion.
     * @param int $idUsuario
     * @param int $idRelaboral
     * @param int $gestion
     * @param int $mes
     * @param string $clasemarcacion
     * @param int $operacion
     * @return bool
     */
    public function vaciarRegistro($idUsuario = 0, $idRelaboral = 0, $gestion = 0, $mes = 0, $clasemarcacion = "")
    {
        if ($idUsuario > 0 && $idRelaboral > 0 && $gestion > 0 && $mes > 0 && $clasemarcacion != "") {
            $db = $this->getDI()->get('db');
            $sql = "UPDATE horariosymarcaciones SET ";
            $sql .= "d1=NULL, calendariolaboral1_id=NULL, estado1=null,";
            $sql .= "d2=NULL, calendariolaboral2_id=NULL, estado2=null,";
            $sql .= "d3=NULL, calendariolaboral3_id=NULL, estado3=null,";
            $sql .= "d4=NULL, calendariolaboral4_id=NULL, estado4=null,";
            $sql .= "d5=NULL, calendariolaboral5_id=NULL, estado5=null,";
            $sql .= "d6=NULL, calendariolaboral6_id=NULL, estado6=null,";
            $sql .= "d7=NULL, calendariolaboral7_id=NULL, estado7=null,";
            $sql .= "d8=NULL, calendariolaboral8_id=NULL, estado8=null,";
            $sql .= "d9=NULL, calendariolaboral9_id=NULL, estado9=null,";
            $sql .= "d10=NULL,calendariolaboral10_id=NULL, estado10=null,";
            $sql .= "d11=NULL,calendariolaboral11_id=NULL, estado11=null,";
            $sql .= "d12=NULL,calendariolaboral12_id=NULL, estado12=null,";
            $sql .= "d13=NULL,calendariolaboral13_id=NULL, estado13=null,";
            $sql .= "d14=NULL,calendariolaboral14_id=NULL, estado14=null,";
            $sql .= "d15=NULL,calendariolaboral15_id=NULL, estado15=null,";
            $sql .= "d16=NULL,calendariolaboral16_id=NULL, estado16=null,";
            $sql .= "d17=NULL,calendariolaboral17_id=NULL, estado17=null,";
            $sql .= "d18=NULL,calendariolaboral18_id=NULL, estado18=null,";
            $sql .= "d19=NULL,calendariolaboral19_id=NULL, estado19=null,";
            $sql .= "d20=NULL,calendariolaboral20_id=NULL, estado20=null,";
            $sql .= "d21=NULL,calendariolaboral21_id=NULL, estado21=null,";
            $sql .= "d22=NULL,calendariolaboral22_id=NULL, estado22=null,";
            $sql .= "d23=NULL,calendariolaboral23_id=NULL, estado23=null,";
            $sql .= "d24=NULL,calendariolaboral24_id=NULL, estado24=null,";
            $sql .= "d25=NULL,calendariolaboral25_id=NULL, estado25=null,";
            $sql .= "d26=NULL,calendariolaboral26_id=NULL, estado26=null,";
            $sql .= "d27=NULL,calendariolaboral27_id=NULL, estado27=null,";
            $sql .= "d28=NULL,calendariolaboral28_id=NULL, estado28=null,";
            $sql .= "d29=NULL,calendariolaboral29_id=NULL, estado29=null,";
            $sql .= "d30=NULL,calendariolaboral30_id=NULL, estado30=null,";
            $sql .= "d31=NULL,calendariolaboral31_id=NULL, estado31=null ";
            $sql .= "WHERE gestion = " . $gestion . " and mes = " . $mes . " and relaboral_id = " . $idRelaboral . " ";
            if ($clasemarcacion == "H" || $clasemarcacion == "M") {
                $sql .= "AND clasemarcacion LIKE '" . $clasemarcacion . "'";
            }
            $res = $db->execute($sql);
            return $res;
        }
        return false;
    }
}

