<?php

class Partidas extends \Phalcon\Mvc\Model
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
    public $codigo;
    /**
     *
     * @var string
     */
    public $partida;

    /**
     *
     * @var string
     */
    public $descripcion;

    /**
     *
     * @var string
     */
    public $activo;

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
            'codigo' => 'codigo', 
            'partida' => 'partida', 
            'descripcion' => 'descripcion', 
            'activo' => 'activo'
        );
    }

}
