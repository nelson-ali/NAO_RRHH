<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  21-11-2014
*/


use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Modalidadmovilidad extends \Phalcon\Mvc\Model {
    public $id;
    public $movilidadpersonal_id;
    public $modalidad_movilidad;
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
            'movilidadpersonal_id'=>'movilidadpersonal_id',
            'modalidad_movilidad'=>'modalidad_movilidad',
            'observacion'=>'observacion',
            'estado'=>'estado',
            'agrupador'=>'agrupador'
        );
    }
}