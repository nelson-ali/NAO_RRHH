<?php

class Pidiomas extends \Phalcon\Mvc\Model
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
    public $idioma;

    /**
     *
     * @var string
     */
    public $lectura;

    /**
     *
     * @var string
     */
    public $escritura;

    /**
     *
     * @var string
     */
    public $conversacion;

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
            'idioma' => 'idioma', 
            'lectura' => 'lectura', 
            'escritura' => 'escritura', 
            'conversacion' => 'conversacion', 
            'baja_logica' => 'baja_logica'
        );
    }

}
