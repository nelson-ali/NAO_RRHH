<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  24-10-2014
*/

class Relaboralesubicaciones  extends \Phalcon\Mvc\Model {
    public $id;
    public $relaboral_id;
    public $ubicacion_id;
    public $fecha_ini;
    public $fecha_fin;
    public $observacion;
    public $estado;
    public $baja_logica;
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
            'id' => 'id',
            'relaboral_id' => 'relaboral_id',
            'ubicacion_id' => 'ubicacion_id',
            'fecha_ini' => 'fecha_ini',
            'fecha_fin' => 'fecha_fin',
            'observacion' => 'observacion',
            'estado' => 'estado',
            'baja_logica' => 'baja_logica',
            'agrupador'=>'agrupador'
        );
    }
} 