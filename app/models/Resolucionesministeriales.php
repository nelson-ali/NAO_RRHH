<?php

class Resolucionesministeriales extends \Phalcon\Mvc\Model
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
    public $tipo_resolucion;

    /**
     *
     * @var string
     */
    public $numero_res;

    /**
     *
     * @var string
     */
    public $fecha_emision;

    /**
     *
     * @var string
     */
    public $fecha_apr;

    /**
     *
     * @var integer
     */
    public $baja_logica;

    /**
     *
     * @var integer
     */
    public $activo;

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
            'tipo_resolucion' => 'tipo_resolucion', 
            'numero_res' => 'numero_res', 
            'fecha_emision' => 'fecha_emision', 
            'fecha_apr' => 'fecha_apr', 
            'baja_logica' => 'baja_logica',
            'activo' => 'activo'
        );
    }

}
