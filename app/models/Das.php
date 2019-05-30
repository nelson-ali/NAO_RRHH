<?php

class Das extends \Phalcon\Mvc\Model
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
    public $direccion_administrativa;

    /**
     *
     * @var string
     */
    public $codigo;

    /**
     *
     * @var string
     */
    public $observacion;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     *
     * @var string
     */
    public $visible;

    /**
     *
     * @var string
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
            'direccion_administrativa' => 'direccion_administrativa', 
            'codigo' => 'codigo', 
            'observacion' => 'observacion',
            'estado' => 'estado',
            'visible' => 'visible',
            'baja_logica' => 'baja_logica'
        );
    }

}
