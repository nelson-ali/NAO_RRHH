<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  14-10-2014
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Perfileslaborales  extends \Phalcon\Mvc\Model {

    private $_db;

    public function initialize() {
        $this->_db = new Perfileslaborales();
        //   parent::initialize();
    }

    /**
     * Función para la obtención del primer día disponible de registro en calendario laboral. La fecha se devuelve disgregada en tres
     * columnas, una para el día, otro para el mes y finalmente para la gestión.
     * @param $idPerfil
     * @return Resultset
     */
    public function getPrimerDiaSiguienteMesParaCalendario($idPerfil){
        $sql = "SELECT * FROM f_primer_dia_siguiente_mes_para_calendario(".$idPerfil.")";
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención a través de un procedimiento almacenado del listado de fechas dentro de un rango establecido por los parámetros.
     * @param $fechaIni
     * @param $fechaFin
     * @param $finDeSemana
     * @return Resultset
     */
    public function getRangoDeFechas($fechaIni,$fechaFin,$finDeSemana){
        $sql = "SELECT * FROM f_rango_fechas(".$fechaIni.",".$fechaFin.",".$finDeSemana.")";
        if($finDeSemana>=0)$sql .= " WHERE o_fin_de_semana=".$finDeSemana;
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del listado de gestiones disponibles para la generación de turnos laborales.
     * @param $fecha
     * @param $idPerfilLaboral
     * @return Resultset
     */
    public function getGestionesByPerfilLaboral($fecha=NULL,$idPerfilLaboral){
        if($fecha!=''&&$fecha!=NULL)
        $sql = "SELECT f_listado_gestiones_generacion_calendarios AS o_gestiones FROM f_listado_gestiones_generacion_calendarios('".$fecha."',".$idPerfilLaboral.")";
        else $sql = "SELECT f_listado_gestiones_generacion_calendarios AS o_gestiones FROM f_listado_gestiones_generacion_calendarios(NULL,".$idPerfilLaboral.")";
        //echo "<p>---->".$sql;
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del listado de meses disponibles un una determinada gestión para un perfil laboral.
     * @param null $fecha
     * @param $idPerfilLaboral
     * @param $mes
     * @return Resultset
     */
    public function getMesesByPerfilLaboralAndGestion($idPerfilLaboral,$gestion){
        if($gestion>0){
            $sql = "SELECT * FROM f_listado_meses_generacion_calendarios($idPerfilLaboral,$gestion)";
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }
    /**
     * Verifica que una fecha esté dentro del rango de fechas establecidas
     * @param $start_date fecha de inicio
     * @param $end_date fecha final
     * @param $evaluame fecha a comparar
     * @return true si esta en el rango, false si no lo está
     */
    function checkInRange($start_date, $end_date, $evaluame) {
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $user_ts = strtotime($evaluame);
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }
}
