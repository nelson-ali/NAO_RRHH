<?php

class Pformaciones extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $detalle;

    /**
     *
     * @var integer
     */
    public $documento_id;

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
    public $grado;

    /**
     *
     * @var string
     */
    public $numero_titulo;

    /**
     *
     * @var string
     */
    public $nombre_rector;

    /**
     *
     * @var string
     */
    public $fecha_emision;

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
            'detalle' => 'detalle', 
            'documento_id' => 'documento_id', 
            'gestion' => 'gestion', 
            'institucion' => 'institucion', 
            'grado' => 'grado', 
            'numero_titulo' => 'numero_titulo', 
            'nombre_rector' => 'nombre_rector', 
            'fecha_emision' => 'fecha_emision', 
            'baja_logica' => 'baja_logica'
        );
    }

}
