<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  24-10-2014
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Ubicaciones  extends \Phalcon\Mvc\Model {
    public $id;
    public $padre_id;
    public $ubicacion;
    public $color;
    public $observacion;
    public $estado;
    public $baja_logica;
    public $agrupador;
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
            'color' => 'color',
            'observacion' => 'observacion',
            'estado' => 'estado',
            'baja_logica' => 'baja_logica',
            'agrupador'=>'agrupador',
            'cant_nodos_hijos'=>'cant_nodos_hijos'
        );
    }
    private $_db;

    /**
     * Función para la obtención del listado de ubicaciones considerando la cantidad de nodos hijos que tenga. (Líneas por ubicación)
     * @return Resultset
     */
    public function getAllWithSon(){
        $sql = "SELECT u.*,(SELECT COUNT(*) FROM ubicaciones a WHERE u.id = a.padre_id AND estado=1 AND baja_logica=1) AS cant_nodos_hijos";
        $sql .= " FROM ubicaciones u WHERE (u.agrupador=0 OR u.agrupador=1) AND u.estado=1 AND u.baja_logica=1";
        $this->_db = new Ubicaciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
} 