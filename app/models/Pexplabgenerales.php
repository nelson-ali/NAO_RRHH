<?php

class Pexplabgenerales extends \Phalcon\Mvc\Model
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
     * @var double
     */
    public $gestion_desde;

    /**
     *
     * @var double
     */
    public $mes_desde;

    /**
     *
     * @var double
     */
    public $gestion_hasta;

    /**
     *
     * @var double
     */
    public $mes_hasta;

    /**
     *
     * @var string
     */
    public $cargo;

    /**
     *
     * @var string
     */
    public $empresa;

    /**
     *
     * @var string
     */
    public $motivo_retiro;

    /**
     *
     * @var string
     */
    public $inmediato_superior;

    /**
     *
     * @var string
     */
    public $nombre_superior;

    /**
     *
     * @var string
     */
    public $doc_respaldo;

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
            'gestion_desde' => 'gestion_desde', 
            'mes_desde' => 'mes_desde', 
            'gestion_hasta' => 'gestion_hasta', 
            'mes_hasta' => 'mes_hasta', 
            'cargo' => 'cargo', 
            'empresa' => 'empresa', 
            'motivo_retiro' => 'motivo_retiro', 
            'inmediato_superior' => 'inmediato_superior', 
            'nombre_superior' => 'nombre_superior', 
            'doc_respaldo' => 'doc_respaldo', 
            'baja_logica' => 'baja_logica'
        );
    }

}
