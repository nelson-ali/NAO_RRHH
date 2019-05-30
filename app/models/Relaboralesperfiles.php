<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  03-02-2015
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Relaboralesperfiles extends \Phalcon\Mvc\Model {

    private $_db;

    public function initialize() {
        $this->_db = new Relaboralesperfiles();
        //   parent::initialize();
    }
    /**
     * Función para la obtención del listado asignaciones de perfiles.
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function getAll($where='',$group='')
    {
        $sql = "SELECT * FROM relaboralesperfiles ";
        if($where!='')$sql .= $where;
        if($group!='')$sql .= $group;
        $this->_db = new Relaboralesperfiles();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la verificación de la existencia de sobre posición de asignación de perfiles laborales.
     * @param $idRelaboralPerfil
     * @param $idRelaboral
     * @param $idUbicacion
     * @param $fechaIni
     * @param $fechaFin
     * @return Resultset
     */
    public function verificaSobrePosicionPerfiles($idRelaboralPerfil,$idRelaboral,$idPerfilLaboral,$idUbicacion,$fechaIni,$fechaFin){
        if($idRelaboral>0&&$idUbicacion>0&&$fechaIni!=''&&$fechaFin!=''){
            $sql = "SELECT * FROM f_tiene_sobreposicion_perfil(".$idRelaboralPerfil.",".$idRelaboral.",".$idPerfilLaboral.",".$idUbicacion.",'".$fechaIni."','".$fechaFin."')";
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }
    /**
     * Función para la verificación de que las fechas enviadas como parámetros no tienen conflicto con las fechas del registro de relación laboral.
     * @param $idRelaboral
     * @param $fechaIni
     * @param $fechaFin
     * @return Resultset
     */
    public function verificaDentroRangoFechasRelaborales($idRelaboral,$fechaIni,$fechaFin){
        if($fechaIni!=''&&$fechaFin!=''){
            $sql = "SELECT * FROM f_verifica_dentro_rango_fechas(".$idRelaboral.",'".$fechaIni."','".$fechaFin."')";
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }
    /**
     * Función para la obtención del listado de relaciones laborales registradas en un determinado perfil laboral.
     * @param $idPerfilLaboral
     * @return Resultset
     */
    public function getListRelaboralesByPerfil($idPerfilLaboral){
        if($idPerfilLaboral>0){
            $sql = "select * from f_relaborales_asignados_y_no_asignados_a_perfil(".$idPerfilLaboral.", 0, null, null)";
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para la obtención del listado de relaciones laborales registradas en un determinado perfil laboral, considerando una gestión o todas las gestiones.
     * @param $idPerfilLaboral
     * @param $gestion
     * @param $offset
     * @param $limit
     * @return Resultset
     */
    public function getListRelaboralesByPerfilInGestion($idPerfilLaboral,$gestion,$offset,$limit,$where=''){
        if($idPerfilLaboral>0){
            $sql = "select * from f_relaborales_asignados_y_no_asignados_a_perfil_por_gestion(".$idPerfilLaboral.", 0, null, null,$gestion,$offset,$limit,'$where')";
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para obtener la cantidad total de registros de relación laboral registradas para un determinado perfil laboral, considerando una gestión o todas las gestiones.
     * @param $idPerfilLaboral
     * @param $gestion
     * @param $offset
     * @param $limit
     * @param string $where
     * @return int
     */
    public function getCountRelaboralesByPerfilInGestion($idPerfilLaboral,$gestion,$where=''){
        if($idPerfilLaboral>0&&$gestion>=0){
            $sql = "select count(*) AS o_cantidad from f_relaborales_asignados_y_no_asignados_a_perfil_por_gestion(".$idPerfilLaboral.", 0, null, null,$gestion,null,null,'$where')";
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if(count($arr)>0)return $arr[0]->o_cantidad;
        } return 0;
    }
}