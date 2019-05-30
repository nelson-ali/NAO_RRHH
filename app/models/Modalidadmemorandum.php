<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  21-11-2014
*/


use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Modalidadmemorandum extends \Phalcon\Mvc\Model {
    public $id;
    public $modalidadmovilidad_id;
    public $tipomemorandum_id;
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
            'modalidadmovilidad_id'=>'modalidadmovilidad_id',
            'tipomemorandum_id'=>'tipomemorandum_id',
            'observacion'=>'observacion',
            'estado'=>'estado',
            'agrupador'=>'agrupador',
        );
    }
} 