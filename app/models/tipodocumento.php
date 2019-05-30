<?php

class tipodocumento extends \Phalcon\Mvc\Model
{
    
    /**
     * 
     *  @var integer
     */
    public $id;
    
    /**
     * 
     *  @var string
     */
    public $tipo_documento;
    
    /**
     * 
     *  @var string
     */
    public $codigo;
    
    /**
     * 
     *  @var integer
     */
    public $consultor;
    
    /**
     * 
     *  @var integer
     */
    public $eventual;
    
    /**
     * 
     *  @var integer
     */
    public $permanente;
    
    /**
     * 
     *  @var integer
     */
    public $carrera;
    
    /**
     * 
     *  @var integer
     */
    public $tipopresdoc_id;
    
    /**
     * 
     *  @var integer
     */
    public $periodopresdoc_id;
    
    /**
     * 
     *  @var integer
     */
    public $tipoemisordoc_id;
    
    /**
     * 
     *  @var integer
     */
    public $tipofechaemidoc_id;
    
    /**
     * 
     *  @var integer
     */
    public $tipoperssoldoc_id;
    
    /**
     * 
     *  @var string
     */
    public $ruta_carpeta;
    
    /**
     * 
     *  @var string
     */
    public $nombre_carpeta;
    
    /**
     * 
     *  @var string
     */
    public $formato_archivo_digital;
    
    /**
     * 
     *  @var integer
     */
    public $resolucion_archivo_digital;
    
    /**
     * 
     *  @var integer
     */
    public $altura_archivo_digital;
    
    /**
     * 
     *  @var integer
     */
    public $anchura_archivo_digital;
    
    /**
     * 
     *  @var string
     */
    public $campo_aux_a;
    
    /**
     * 
     *  @var string
     */
    public $tipo_dato_campo_aux_a;
    
    /**
     * 
     *  @var string
     */
    public $campo_aux_b;
    
    /**
     * 
     *  @var string
     */
    public $tipo_dato_campo_aux_b;
    
    /**
     * 
     *  @var string
     */
    public $campo_aux_c;
    
    /**
     * 
     *  @var string
     */
    public $tipo_dato_campo_aux_c;
    
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
    public $grupoarchivos_id;
    
    /**
     *
     * @var string
     */
    public $sexo;
    
    /**
     * 
     *  @var integer
     */
    public $tipo_proceso_contratacion;


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
    public function columnMap(){
        return array(
            'id' => 'id',
            'tipo_documento' => 'tipo_documento',
            'codigo' => 'codigo',
            'consultor' => 'consultor',
            'eventual' => 'eventual',
            'permanente' => 'permanente',
            'carrera' => 'carrera',
            'tipopresdoc_id' => 'tipopresdoc_id',
            'periodopresdoc_id' => 'periodopresdoc_id',
            'tipoemisordoc_id' => 'tipoemisordoc_id',
            'tipofechaemidoc_id' => 'tipofechaemidoc_id',
            'tipoperssoldoc_id' => 'tipoperssoldoc_id',
            'ruta_carpeta' => 'ruta_carpeta',
            'nombre_carpeta' => 'nombre_carpeta',
            'formato_archivo_digital' => 'formato_archivo_digital',
            'resolucion_archivo_digital' => 'resolucion_archivo_digital',
            'altura_archivo_digital' => 'altura_archivo_digital',
            'anchura_archivo_digital' => 'anchura_archivo_digital',
            'campo_aux_a' => 'campo_aux_a',
            'tipo_dato_campo_aux_a' => 'tipo_dato_campo_aux_a',
            'campo_aux_b' => 'campo_aux_b',
            'tipo_dato_campo_aux_b' => 'tipo_dato_campo_aux_b',
            'campo_aux_c' => 'campo_aux_c',
            'tipo_dato_campo_aux_c' => 'tipo_dato_campo_aux_c',
            'observacion' => 'observacion',
            'estado' => 'estado',
            'baja_logica' => 'baja_logica',
            'agrupador' => 'agrupador',
            'user_reg_id' => 'user_reg_id',
            'fecha_reg' => 'fecha_reg',
            'user_mod_id' => 'user_mod_id',
            'fecha_mod' => 'fecha_mod',
            'grupoarchivos_id' => 'grupoarchivos_id',
            'sexo' => 'sexo',
            'tipo_proceso_contratacion' => 'tipo_proceso_contratacion'
            
        );
    }
}
