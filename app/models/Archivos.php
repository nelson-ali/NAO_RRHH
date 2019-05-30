<?php

class Archivos extends \Phalcon\Mvc\Model
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
    public $tipo_documento;

    /**
     *
     * @var integer
     */
    public $persona_id;

    /**
     *
     * @var string
     */
    public $tipo_archivo;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $nombre_archivo;

    /**
     *
     * @var string
     */
    public $carpeta;

    /**
     *
     * @var string
     */
    public $fecha;

    /**
     *
     * @var integer
     */
    public $baja_logica;

    /**
     *
     * @var integer
     */
    public $tamanio;

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
            'tipo_documento' => 'tipo_documento', 
            'persona_id' => 'persona_id', 
            'tipo_archivo' => 'tipo_archivo', 
            'user_id' => 'user_id', 
            'nombre_archivo' => 'nombre_archivo', 
            'carpeta' => 'carpeta', 
            'fecha' => 'fecha', 
            'baja_logica' => 'baja_logica', 
            'tamanio' => 'tamanio'
        );
    }

}
