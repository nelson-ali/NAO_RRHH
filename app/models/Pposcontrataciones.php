<?php

class Pposcontrataciones extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $postulante_id;

    /**
     *
     * @var integer
     */
    public $proceso_contratacion_id;

    /**
     *
     * @var string
     */
    public $fecha_cierre;

    /**
     *
     * @var integer
     */
    public $estado;

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
            'postulante_id' => 'postulante_id', 
            'proceso_contratacion_id' => 'proceso_contratacion_id', 
            'fecha_cierre' => 'fecha_cierre', 
            'estado' => 'estado', 
            'baja_logica' => 'baja_logica'
        );
    }

}
