<?php

class Personascontactos extends \Phalcon\Mvc\Model

{
    /**
     * 
     *  @var integer
     */
    public $id;
    
    /**
     * 
     *  @var integer
     */
    public $persona_id;
    
    /**
     * 
     *  @var string
     */
    public $direccion_dom;
    
    /**
     * 
     *  @var string
     */
    public $telefono_fijo;
    
    /**
     * 
     *  @var string
     */
    public $telefono_inst;
    
    /**
     * 
     *  @var string
     */
    public $telefono_fax;
    
    /**
     * 
     *  @var string
     */
    public $interno_inst;
    
    /**
     * 
     *  @var string
     */
    public $celular_per;
    
    /**
     * 
     *  @var string
     */
    public $celular_inst;
    
    /**
     * 
     *  @var string
     */
    public $num_credencial;
    
    /**
     * 
     *  @var string
     */
    public $ac_no;
    
    /**
     * 
     *  @var string
     */
    public $e_mail_per;
    
    /**
     * 
     *  @var string
     */
    public $e_mail_inst;
    
    /**
     * 
     *  @var string
     */
    public $observacion;
    
    /**
     * 
     *  @var integer
     */
    public $estado;
    
    
    /**
     * 
     *  @var integer
     */
    public $baja_logica;
    
    /**
     * 
     * @var string
     */
    public  $telefono_emerg;
    
    /**
     * 
     * @var string
     */
    public  $persona_emerg;
    
    /**
     * 
     * @var string
     */
    public  $relacion_emerg;
        
    /**
     * Initialize method for model.
     */
    public function initialize(){
        $this->setSchema("");
    }
    
    /**
     * Independent Column Mapping.
     */
    public function columnMap(){
        return array(
            'id' => 'id',
            'persona_id' => 'persona_id',
            'direccion_dom' => 'direccion_dom',
            'telefono_fijo' => 'telefono_fijo',
            'telefono_inst' => 'telefono_inst',
            'telefono_fax' => 'telefono_fax',
            'interno_inst' => 'interno_inst',
            'celular_per' => 'celular_per',
            'celular_inst' => 'celular_per',
            'num_credencial' => 'num_credencial',
            'ac_no' => 'ac_no',
            'e_mail_per' => 'e_mail_per',
            'e_mail_inst' => 'e_mail_inst',
            'observacion' => 'observacion',
            'estado' => 'estado',
            'baja_logica' => 'baja_logica',
            'telefono_emerg' => 'telefono_emerg',
            'persona_emerg' => 'persona_emerg',
            'relacion_emerg' => 'relacion_emerg'
        );
    }
}