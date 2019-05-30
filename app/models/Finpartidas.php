<?php

class Finpartidas extends \Phalcon\Mvc\Model
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
    public $denominacion;

    /**
     *
     * @var integer
     */
    public $financiamiento_id;

    /**
     *
     * @var integer
     */
    public $condicion_id;

    /**
     *
     * @var integer
     */
    public $partida;

    /**
     *
     * @var string
     */
    public $codigo;

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
    public $visible;

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
     *
     * @var integer
     */
    public $user_reg_id;

    /**
     *
     * @var string
     */
    public $fecha_reg;

    /**
     *
     * @var integer
     */
    public $user_mod_id;

    /**
     *
     * @var string
     */
    public $fecha_mod;

    /**
     *
     * @var integer
     */
    public $contador;

    /**
     *
     * @var integer
     */
    public $poa_id;

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
            'denominacion' => 'denominacion', 
            'financiamiento_id' => 'financiamiento_id', 
            'condicion_id' => 'condicion_id', 
            'partida' => 'partida', 
            'codigo' => 'codigo', 
            'observacion' => 'observacion', 
            'estado' => 'estado', 
            'visible' => 'visible', 
            'baja_logica' => 'baja_logica', 
            'agrupador' => 'agrupador', 
            'user_reg_id' => 'user_reg_id', 
            'fecha_reg' => 'fecha_reg', 
            'user_mod_id' => 'user_mod_id', 
            'fecha_mod' => 'fecha_mod',
            'contador' => 'contador',
            'poa_id' => 'poa_id'
        );
    }

}
