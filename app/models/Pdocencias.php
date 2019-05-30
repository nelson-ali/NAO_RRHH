<?php

class Pdocencias extends \Phalcon\Mvc\Model
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
    public $institucion;

    /**
     *
     * @var string
     */
    public $materia;

    /**
     *
     * @var string
     */
    public $duracion;

    /**
     *
     * @var string
     */
    public $gestion;

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
            'institucion' => 'institucion', 
            'materia' => 'materia', 
            'duracion' => 'duracion', 
            'gestion' => 'gestion', 
            'baja_logica' => 'baja_logica'
        );
    }

}
