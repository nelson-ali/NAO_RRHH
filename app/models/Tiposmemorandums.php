<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  18-11-2014
*/


use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Tiposmemorandums extends \Phalcon\Mvc\Model {
    public $id;
    public $tipo_memorandum;
    public $cabecera;
    public $abrebiacion;
    public $fecha_fin;
    public $hora_fin;
    public $cargo;
    public $organigama;
    public $ubicacion;
    public $motivo;
    public $pais;
    public $ciudad;
    public $lugar;
    public $movilidad;
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
            'hora_fin'=>'hora_fin',
            'cargo'=>'cargo',
            'organigrama'=>'organigrama',
            'ubicacion'=>'ubicacion',
            'motivo'=>'motivo',
            'pais'=>'pais',
            'ciudad'=>'ciudad',
            'lugar'=>'lugar',
            'movilidad'=>'movilidad',
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
        $sql = "SELECT * from tiposmemorandums";
        $this->_db = new Tiposmemorandums();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
}