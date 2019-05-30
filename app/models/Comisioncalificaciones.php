<?php

class Comisioncalificaciones extends \Phalcon\Mvc\Model
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
    public $seguimiento_id;

    /**
     *
     * @var integer
     */
    public $usuario_id;

    /**
     *
     * @var integer
     */
    public $baja_logica;

    /**
     *
     * @var string
     */
    public $nombre;

    /**
     *
     * @var string
     */
    public $cargo;

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
            'seguimiento_id' => 'seguimiento_id', 
            'usuario_id' => 'usuario_id', 
            'baja_logica' => 'baja_logica', 
            'nombre' => 'nombre', 
            'cargo' => 'cargo'
        );
    }

}
