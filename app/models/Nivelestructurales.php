<?php

class Nivelestructurales extends \Phalcon\Mvc\Model
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
    public $orden;

    /**
     *
     * @var string
     */
    public $nivel_estructural;

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
            'orden' => 'orden', 
            'nivel_estructural' => 'nivel_estructural', 
            'observacion' => 'observacion',
            'estado' => 'estado',
            'visible' => 'visible',
            'baja_logica' => 'baja_logica'
        );
    }

}
