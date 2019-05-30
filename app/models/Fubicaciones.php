<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  28-01-2015
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Fubicaciones  extends \Phalcon\Mvc\Model {
    public $id;
    public $padre_id;
    public $id_ubicacion;
    public $ubicacion;
    public $id_estacion;
    public $estacion;
    public $color;
    public $id_cupoturno;
    public $perfilaboral_id;
    public $fecha_ini;
    public $fecha_fin;
    public $cupo;
    public $cant_nodos_hijos;

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
            'padre_id' => 'padre_id',
            'ubicacion' => 'ubicacion',
            'id_ubicacion' => 'id_ubicacion',
            'id_estacion' => 'id_estacion',
            'estacion' => 'estacion',
            'color' => 'color',
            'id_cupoturno' => 'id_cupoturno',
            'perfillaboral_id' => 'perfillaboral_id',
            'fecha_ini' => 'fecha_ini',
            'fecha_fin' => 'fecha_fin',
            'cupo' => 'cupo',
            'cant_nodos_hijos' => 'cant_nodos_hijos'
        );
    }
    private $_db;

    /**
     * Función para la obtención del listado de ubacaciones en orden jerárquico.
     * @param $idUbicacion
     * @return Resultset
     */
    public function obtenerGrupoUbicaciones($idUbicacion){
        $sql = "SELECT * from f_ubicaciones()";
        if($idUbicacion>0)$sql.=" WHERE id_ubicacion=".$idUbicacion;
        $this->_db = new Fubicaciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para obtener los cupos de acuerdo a las ubicaciones requeridas, considerando el perfil laboral,
     * y el rango de fechas.
     * @param $idUbicacion
     * @param $idPerfil
     * @param $fechaIni
     * @param $fechaFin
     * @return Resultset
     */
    public function obtenerCuposPorGrupoUbicaciones($idUbicacion,$idPerfilLaboral,$fechaIni,$fechaFin){
        $sql = "SELECT fu.*,ct.id as id_cupoturno,ct.perfillaboral_id,ct.fecha_ini,ct.fecha_fin,ct.cupo";
        $sql .= " FROM f_ubicaciones() fu LEFT JOIN cuposturnos ct ON fu.id = ct.ubicacion_id";
        $sql .= " AND ct.estado>=1 AND ct.baja_logica=1";
        if($idUbicacion>0)$sql.=" AND fu.id_ubicacion = ".$idUbicacion;
        if($idPerfilLaboral>0)$sql.=" AND ct.perfillaboral_id = ".$idPerfilLaboral;
        if($fechaIni!='')$sql.=" AND ct.fecha_ini = CAST('".$fechaIni."' AS DATE)";
        if($fechaFin!='')$sql.=" AND ct.fecha_fin = CAST('".$fechaFin."' AS DATE)";
        //echo "<p>--->".$sql;
        $this->_db = new Fubicaciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del listado de ubicaciones principales de acuerdo a un identificador de perfil.
     * @param $idPerfilLaboral
     * @return Resultset
     */
    public function obtenerUbicacionesPrincipalesPorPerfil($idPerfilLaboral){
        $sql = "SELECT DISTINCT fu.padre_id,0 as id,fu.id_ubicacion,fu.ubicacion,0 as id_estacion,null as estacion,fu.color,";
        $sql .= "(SELECT COUNT(*) FROM ubicaciones a WHERE fu.id_ubicacion = a.padre_id AND a.estado=1 AND a.baja_logica=1) AS cant_nodos_hijos FROM relaboralesperfiles rp";
        $sql .= " INNER JOIN f_ubicaciones() fu ON fu.id = rp.ubicacion_id";
        $sql .= " WHERE rp.perfillaboral_id=".$idPerfilLaboral;
        $sql .= " AND rp.estado>=1 AND rp.baja_logica=1";
        $this->_db = new Fubicaciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del listado de estaciones registradas para una determinada ubicación principal y perfil laboral.
     * @param $idPerfilLaboral
     * @param $idUbicacion
     * @return Resultset
     */
    public function obtenerEstacionesPorUbicacionPorPerfil($idPerfilLaboral,$idUbicacion){
        $sql = "SELECT DISTINCT fu.* FROM relaboralesperfiles rp";
        $sql .= " INNER JOIN f_ubicaciones() fu ON fu.id = rp.ubicacion_id";
        $sql .= " WHERE rp.perfillaboral_id=".$idPerfilLaboral." AND  fu.id_ubicacion=".$idUbicacion;
        $sql .= " AND rp.estado>=1 AND rp.baja_logica=1";
        $this->_db = new Fubicaciones();
        //echo "<p>--->".$sql;
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
} 