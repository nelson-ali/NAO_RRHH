<?php

class presentaciondoc extends \Phalcon\Mvc\Model
{
    
    /**
     * 
     * @var integer
     */
    public $id;
    
    /**
     * 
     *  @var integer
     */
    public $institucion_id;
    
    /**
     * 
     *  @var integer
     */
    public $gestion_emi;
    
    /**
     * 
     *  @var integer
     */
    public $trim_emi;
    
    /**
     * 
     *  @var integer
     */
    public $mes_emi;
    
    /**
     * 
     *  @var integer
     */
    public $dia_emi;
    
    /**
     * 
     *  @var integer
     */
    public $tipodocumento_id;
    
    /**
     * 
     * @var integer
     */
    public $rellaboral_id;
    
    /**
     * 
     *  @var string
     */
    public $fecha_pres;
    
    /**
     * 
     *  @var string
     */
    public $campo_aux_v1;
    
    /**
     * 
     *  @var string
     */
    public $campo_aux_v2;
    
    /**
     * 
     *  @var string
     */
    public $campo_aux_v3;
    
    /**
     * 
     *  @var integer
     */
    public $campo_aux_n1;
    
    /**
     * 
     *  @var integer
     */
    public $campo_aux_n2;
    
    /**
     * 
     *  @var integer
     */
    public $campo_aux_n3;
    
    /**
     * 
     * @var string
     */
    public $campo_aux_d1;
    
    /**
     * 
     * @var string
     */
    public $campo_aux_d2;
    
    /**
     * 
     * @var string
     */
    public $campo_aux_d3;
    
    /**
     * 
     *  @var string
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
     *  @var string
     */
    public $nombre;
    
    /**
     * 
     * @var integer
     */
    public $tamanio;
    
    /**
     * 
     * @var string
     */
    public $tipo;
    
    /**
     * Initialize method for model.
     */
    
    public function initialize(){
        $this->setSchema("");
    }
    
    /**
     * Independent Column MApping.
     */
    
    public function columnMap(){
        return array(
            'id' => 'id',
            'institucion_id' => 'institucion_id',
            'gestion_emi' => 'gestion_emi',
            'trim_emi' => 'trim_emi',
            'mes_emi' => 'mes_emi',
            'dia_emi' => 'dia_emi',
            'tipodocumento_id' => 'tipodocumento_id',
            'rellaboral_id' => 'rellaboral_id',
            'fecha_pres' => 'fecha_pres',
            'campo_aux_v1' => 'campo_aux_v1',
            'campo_aux_v2' => 'campo_aux_v2',
            'campo_aux_v3' => 'campo_aux_v3',
            'campo_aux_n1' => 'campo_aux_n1',
            'campo_aux_n2' => 'campo_aux_n2',
            'campo_aux_n3' => 'campo_aux_n3',
            'campo_aux_d1' => 'campo_aux_d1',
            'campo_aux_d2' => 'campo_aux_d2',
            'campo_aux_d3' => 'campo_aux_d3',
            'observacion' => 'observacion',
            'estado' => 'estado',
            'visible' => 'visible',
            'baja_logica' => 'baja_logica',
            'user_reg_id' => 'user_reg_id',
            'fecha_reg' => 'fecha_reg',
            'user_mod_id' => 'user_mod_id',
            'fecha_mod' => 'fecha_mod',
            'nombre' => 'nombre',
            'tamanio' => 'tamanio',
            'tipo' => 'tipo'
        );
    }
}