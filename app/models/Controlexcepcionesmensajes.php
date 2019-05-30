<?php
/**
 *   RRHH - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  13-06-2018
 */
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Controlexcepcionesmensajes extends \Phalcon\Mvc\Model
{
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->_db = new Controlexcepcionesmensajes();
    }

    private $_db;

    /**
     * Función para la obtención del listado de boletas remitidas por un determinado usuario y/o destinadas a un determinado usuario, considerando un rango de fechas y cantidad de registros solicitados.
     * @param int $idUsuarioDestinatario
     * @param int $idUsuarioSolicitante
     * @param int $estado
     * @param string $fechaIni
     * @param string $fechaFin
     * @param string $where
     * @param string $group
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    public function getPaged($idUsuarioDestinatario = 0, $idUsuarioSolicitante = 0, $estado, $fechaIni = "", $fechaFin = "", $where = "", $group = "", $offset = 0, $limit = 0)
    {
        $sql = "SELECT COUNT(*) OVER() as num_row, us.username as solicitante,ud.username as destinatario, ps.ci as destinatario_ci, ps.expd as destinatario_expd, ";
        $sql .= "(CASE WHEN cem.controlexcepcion_estado IN (3,4) THEN cem.link_aceptacion ELSE NULL END) AS link_aceptacion, ";
        $sql .= "(CASE WHEN cem.controlexcepcion_estado IN (3,4) THEN cem.link_rechazo ELSE NULL END) AS link_rechazo, cem.fecha_env, cem.cuerpo_mensaje, fc.* ";
        $sql .= "FROM controlexcepcionesmensajes cem ";
        $sql .= "INNER JOIN f_controlexcepciones_por_id(cem.controlexcepcion_id) fc on true ";
        $sql .= "INNER JOIN usuarios us on us.username||'@viasbolivia.gob.bo' LIKE cem.user_sol_mail ";
        $sql .= "INNER JOIN usuarios ud on ud.username||'@viasbolivia.gob.bo' LIKE cem.user_dest_mail ";
        $sql .= "INNER JOIN personas ps on ps.id = us.persona_id ";
        $sql .= "WHERE ud.id > 0 ";
        if ($idUsuarioDestinatario > 0) {
            $sql .= "AND ud.id = " . $idUsuarioDestinatario . " ";
        }
        if ($idUsuarioSolicitante > 0) {
            $sql .= "AND us.id = " . $idUsuarioSolicitante . " ";
        }
        if($estado == 100){
            $sql .= "AND cem.controlexcepcion_estado NOT IN (-3, -4) ";
        }
        if ($fechaIni != "" && $fechaFin != "") {
            $sql .= "AND ( ";
            $sql .= "cem.fecha_env BETWEEN CAST('$fechaIni' AS DATE) AND CAST('$fechaFin' AS DATE) ";
            $sql .= "OR ";
            $sql .= "cem.fecha_reg BETWEEN CAST('$fechaIni' AS DATE) AND CAST('$fechaFin' AS DATE) ";
            $sql .= "OR ";
            $sql .= "fc.fecha_ini BETWEEN CAST('$fechaIni' AS DATE) AND CAST('$fechaFin' AS DATE) ";
            $sql .= "OR ";
            $sql .= "fc.fecha_fin BETWEEN CAST('$fechaIni' AS DATE) AND CAST('$fechaFin' AS DATE) ";
            $sql .= "OR ";
            $sql .= "CAST('$fechaIni' AS DATE) BETWEEN fc.fecha_ini AND fc.fecha_fin ";
            $sql .= "OR ";
            $sql .= "CAST('$fechaFin' AS DATE) BETWEEN fc.fecha_ini AND fc.fecha_fin ";
            $sql .= ")";
        }
        if ($where != "") {
            $sql .= $where . " ";
        }
        $sql .= "ORDER BY fc.fecha_ini desc ";
        if ($limit > 0) {
            $sql .= "offset " . $offset . " LIMIT " . $limit . " ";
        }
        //echo $sql;
        $this->_db = new Controlexcepcionesmensajes();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
}