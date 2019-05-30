<?php

class Pcalificaciones extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $proceso_contratacion_id;

    /**
     *
     * @var integer
     */
    public $seguimiento_id;

    /**
     *
     * @var integer
     */
    public $formacion_academica_id;

    /**
     *
     * @var integer
     */
    public $documento_id;

    /**
     *
     * @var string
     */
    public $numero_titulo;

    /**
     *
     * @var string
     */
    public $fecha_emision;

    /**
     *
     * @var integer
     */
    public $exp_general_meses;

    /**
     *
     * @var integer
     */
    public $exp_profesional_meses;

    /**
     *
     * @var integer
     */
    public $exp_relacionado_meses;

    /**
     *
     * @var integer
     */
    public $gestion_formacion;

    /**
     *
     * @var integer
     */
    public $cargo_perfil_id;

    /**
     *
     * @var integer
     */
    public $cumple;

    /**
     *
     * @var integer
     */
    public $exp_general_requerido;

    /**
     *
     * @var integer
     */
    public $exp_profesional_requerido;

    /**
     *
     * @var integer
     */
    public $exp_relacionado_requerido;

    /**
     *
     * @var string
     */
    public $observacion;

    /**
     *
     * @var integer
     */
    public $documento_id_requerido;

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
            'proceso_contratacion_id' => 'proceso_contratacion_id', 
            'seguimiento_id' => 'seguimiento_id', 
            'formacion_academica_id' => 'formacion_academica_id', 
            'documento_id' => 'documento_id', 
            'numero_titulo' => 'numero_titulo', 
            'fecha_emision' => 'fecha_emision', 
            'exp_general_meses' => 'exp_general_meses', 
            'exp_profesional_meses' => 'exp_profesional_meses', 
            'exp_relacionado_meses' => 'exp_relacionado_meses', 
            'gestion_formacion' => 'gestion_formacion', 
            'cargo_perfil_id' => 'cargo_perfil_id', 
            'cumple' => 'cumple', 
            'exp_general_requerido' => 'exp_general_requerido', 
            'exp_profesional_requerido' => 'exp_profesional_requerido', 
            'exp_relacionado_requerido' => 'exp_relacionado_requerido', 
            'observacion' => 'observacion', 
            'documento_id_requerido' => 'documento_id_requerido'
        );
    }

}
