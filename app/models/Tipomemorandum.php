<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  18-11-2014
*/


use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Tipomemorandum extends \Phalcon\Mvc\Model {
    public $id;
    public $tipo_memorandum;
    public $cabecera;
    public $abrebiacion;
    public $fecha_fin;
    public $observacion;
    public $estado;
    public $agrupador;

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
            'id'=>'id',
            'tipo_memorandum'=>'tipo_memorandum',
            'cabecera'=>'cabecera',
            'abreviacion'=>'abreviacion',
            'fecha_fin'=>'fecha_fin',
            'observacion'=>'observacion',
            'estado'=>'estado',
            'agrupador'=>'agrupador'
        );
    }
    private $_db;
    /**
     * Función para la obtención de la totalidad de los registros de relaciones laborales.
     * @return Resultset
     */
    public function getAll()
    {
        $sql = "SELECT * from tipomemorandum";
        $this->_db = new Tipomemorandum();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
}