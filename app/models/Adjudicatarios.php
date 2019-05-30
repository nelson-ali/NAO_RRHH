<?php

class Adjudicatarios extends \Phalcon\Mvc\Model
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
    public $nombre;

    /**
     *
     * @var integer
     */
    public $seguimiento_id;

    /**
     *
     * @var string
     */
    public $ci;

    /**
     *
     * @var string
     */
    public $expedida;

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
            'nombre' => 'nombre', 
            'seguimiento_id' => 'seguimiento_id', 
            'ci' => 'ci', 
            'expedida' => 'expedida', 
            'baja_logica' => 'baja_logica'
        );
    }

}
