<?php

class Preferenciaspersonales extends \Phalcon\Mvc\Model
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
    public $nombres_y_apps;

    /**
     *
     * @var string
     */
    public $parentesco;

    /**
     *
     * @var string
     */
    public $telefono;

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
            'nombres_y_apps' => 'nombres_y_apps', 
            'parentesco' => 'parentesco', 
            'telefono' => 'telefono', 
            'baja_logica' => 'baja_logica'
        );
    }

}
