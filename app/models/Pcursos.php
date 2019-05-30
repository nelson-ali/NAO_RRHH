<?php

class Pcursos extends \Phalcon\Mvc\Model
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
    public $gestion;

    /**
     *
     * @var string
     */
    public $institucion;

    /**
     *
     * @var string
     */
    public $nombre_curso;

    /**
     *
     * @var double
     */
    public $duracion_hrs;

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
            'gestion' => 'gestion', 
            'institucion' => 'institucion', 
            'nombre_curso' => 'nombre_curso', 
            'duracion_hrs' => 'duracion_hrs', 
            'baja_logica' => 'baja_logica'
        );
    }

}
