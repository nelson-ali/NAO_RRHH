<?php

class Normativasmod extends \Phalcon\Mvc\Model
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
    public $normativa;

    /**
     *
     * @var string
     */
    public $modalidad;

    /**
     *
     * @var string
     */
    public $denominacion;

    /**
     *
     * @var integer
     */
    public $permanente;

    /**
     *
     * @var integer
     */
    public $eventual;

    /**
     *
     * @var integer
     */
    public $consultor;

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
            'normativa' => 'normativa', 
            'modalidad' => 'modalidad', 
            'denominacion' => 'denominacion', 
            'permanente' => 'permanente', 
            'eventual' => 'eventual', 
            'consultor' => 'consultor', 
            'observacion' => 'observacion', 
            'estado' => 'estado', 
            'visible' => 'visible', 
            'baja_logica' => 'baja_logica', 
            'agrupador' => 'agrupador', 
            'user_reg_id' => 'user_reg_id', 
            'fecha_reg' => 'fecha_reg', 
            'user_mod_id' => 'user_mod_id', 
            'fecha_mod' => 'fecha_mod'
        );
    }

}
