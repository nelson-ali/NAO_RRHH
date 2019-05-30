<?php

class Pacs extends \Phalcon\Mvc\Model
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
    public $cargo_id;

    /**
     *
     * @var integer
     */
    public $gestion;

    /**
     *
     * @var string
     */
    public $fecha_ini;

    /**
     *
     * @var string
     */
    public $fecha_fin;

    /**
     *
     * @var integer
     */
    public $unidad_sol_id;

    /**
     *
     * @var integer
     */
    public $usuario_sol_id;

    /**
     *
     * @var string
     */
    public $fecha_apr;

    /**
     *
     * @var integer
     */
    public $usuario_apr_id;

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
            'cargo_id' => 'cargo_id', 
            'gestion' => 'gestion', 
            'fecha_ini' => 'fecha_ini', 
            'fecha_fin' => 'fecha_fin', 
            'unidad_sol_id' => 'unidad_sol_id', 
            'usuario_sol_id' => 'usuario_sol_id', 
            'fecha_apr' => 'fecha_apr', 
            'usuario_apr_id' => 'usuario_apr_id', 
            'estado' => 'estado', 
            'baja_logica' => 'baja_logica'
        );
    }

}
