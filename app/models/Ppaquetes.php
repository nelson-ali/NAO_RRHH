<?php

class Ppaquetes extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $postulante_id;

    /**
     *
     * @var string
     */
    public $aplicacion;

    /**
     *
     * @var string
     */
    public $nivel;

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
            'postulante_id' => 'postulante_id', 
            'aplicacion' => 'aplicacion', 
            'nivel' => 'nivel', 
            'baja_logica' => 'baja_logica'
        );
    }

}
