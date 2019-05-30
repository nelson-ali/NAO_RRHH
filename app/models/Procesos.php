<?php

class Procesos extends \Phalcon\Mvc\Model
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
    public $gestion;

    /**
     *
     * @var integer
     */
    public $da_id;

    /**
     *
     * @var integer
     */
    public $regional_id;

    /**
     *
     * @var string
     */
    public $codigo;

    /**
     *
     * @var integer
     */
    public $convocatoria_id;

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
            'gestion' => 'gestion', 
            'da_id' => 'da_id', 
            'regional_id' => 'regional_id', 
            'codigo' => 'codigo', 
            'convocatoria_id' => 'convocatoria_id', 
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
