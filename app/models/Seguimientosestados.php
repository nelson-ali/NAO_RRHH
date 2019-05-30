<?php

class Seguimientosestados extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     *
     * @var string
     */
    public $descripcion;

    /**
     *
     * @var integer
     */
    public $baja_logica;

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
            'estado' => 'estado', 
            'descripcion' => 'descripcion', 
            'baja_logica' => 'baja_logica'
        );
    }

}
