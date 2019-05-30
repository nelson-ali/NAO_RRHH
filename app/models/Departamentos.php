<?php

class Departamentos extends \Phalcon\Mvc\Model
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
    public $pais_id;

    /**
     *
     * @var string
     */
    public $departamento;

    /**
     *
     * @var string
     */
    public $observacion;

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
     *
     * @var integer
     */
    public $agrupador;

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
            'pais_id' => 'pais_id', 
            'departamento' => 'departamento', 
            'observacion' => 'observacion', 
            'estado' => 'estado', 
            'baja_logica' => 'baja_logica', 
            'agrupador' => 'agrupador'
        );
    }

}
