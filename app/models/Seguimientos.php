<?php

class Seguimientos extends \Phalcon\Mvc\Model
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
    public $pac_id;

    /**
     *
     * @var integer
     */
    public $proceso_contratacion_id;

    /**
     *
     * @var string
     */
    public $codigo_proceso;

    /**
     *
     * @var string
     */
    public $codigo_cargo;

    /**
     *
     * @var string
     */
    public $nro_solicitud;

    /**
     *
     * @var string
     */
    public $fecha_sol;

    /**
     *
     * @var string
     */
    public $cert_presupuestaria;

    /**
     *
     * @var string
     */
    public $fecha_cert_pre;

    /**
     *
     * @var integer
     */
    public $vacante;

    /**
     *
     * @var double
     */
    public $haber_basico;

    /**
     *
     * @var string
     */
    public $fecha_apr_mae;

    /**
     *
     * @var string
     */
    public $tipo_contratacion;

    /**
     *
     * @var string
     */
    public $fecha_apr;

    /**
     *
     * @var integer
     */
    public $seguimiento_estado_id;

    /**
     *
     * @var integer
     */
    public $user_reg_id;

    /**
     *
     * @var string
     */
    public $fecha_reg_id;

    /**
     *
     * @var integer
     */
    public $baja_logica;

    /**
     *
     * @var string
     */
    public $cargo;

    /**
     *
     * @var double
     */
    public $sueldo;

    /**
     *
     * @var double
     */
    public $usuario_sol;
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
            'pac_id' => 'pac_id', 
            'proceso_contratacion_id' => 'proceso_contratacion_id', 
            'codigo_proceso' => 'codigo_proceso', 
            'codigo_cargo' => 'codigo_cargo', 
            'nro_solicitud' => 'nro_solicitud', 
            'fecha_sol' => 'fecha_sol', 
            'cert_presupuestaria' => 'cert_presupuestaria', 
            'fecha_cert_pre' => 'fecha_cert_pre', 
            'vacante' => 'vacante', 
            'haber_basico' => 'haber_basico', 
            'fecha_apr_mae' => 'fecha_apr_mae', 
            'tipo_contratacion' => 'tipo_contratacion', 
            'fecha_apr' => 'fecha_apr', 
            'seguimiento_estado_id' => 'seguimiento_estado_id', 
            'user_reg_id' => 'user_reg_id', 
            'fecha_reg' => 'fecha_reg', 
            'baja_logica' => 'baja_logica', 
            'cargo' => 'cargo', 
            'sueldo' => 'sueldo',
            'organigrama_id' => 'organigrama_id',
            'usuario_sol' => 'usuario_sol',
            'agrupador' => 'agrupador'
        );
    }

}
