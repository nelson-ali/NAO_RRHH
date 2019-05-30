<?php
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class personas_tadeo extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $p_nombre;

    /**
     *
     * @var string
     */
    public $s_nombre;

    /**
     *
     * @var string
     */
    public $t_nombre;

    /**
     *
     * @var string
     */
    public $p_apellido;

    /**
     *
     * @var string
     */
    public $s_apellido;

    /**
     *
     * @var string
     */
    public $c_apellido;

    /**
     *
     * @var string
     */
    public $ci;
    
    /**
     *
     * @var string
     */
    public $expd;
    
    /**
     *
     * @var string
     */
    public $fecha_caducidad;
    
    /**
     *
     * @var string
     */
    public $fecha_nac;
    
    /**
     *
     * @var string
     */
    public $lugar_nac;
    
    /**
     *
     * @var string
     */
    public $genero;
    
    /**
     *
     * @var string
     */
    public $e_civil;
    
    /**
     *
     * @var string
     */
    public $codigo;
    
    /**
     *
     * @var string
     */
    public $nacionalidad;
    
    /**
     *
     * @var string
     */
    public $nit;
    
    /**
     *
     * @var integer
     */
    public $num_func_sigma;
    
    /**
     *
     * @var string
     */
    public $grupo_sanguineo;
    
    /**
     *
     * @var string
     */
    public $num_lib_ser_militar;
    
    /**
     *
     * @var string
     */
    public $num_reg_profesional;
    
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
     * @var string
     */
    
    public $tipo_doc;
    
    /**
     *
     * @var string
     */
    
    public $foto;
    
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
            'p_nombre' => 'p_nombre', 
            's_nombre' => 's_nombre', 
            't_nombre' => 't_nombre', 
            'p_apellido' => 'p_apellido', 
            's_apellido' => 's_apellido',
            'c_apellido' => 'c_apellido',
            'ci' => 'ci', 
            'expd' => 'expd', 
            'fecha_caducidad' => 'fecha_caducidad', 
            'fecha_nac' => 'fecha_nac', 
            'lugar_nac' => 'lugar_nac', 
            'genero' => 'genero', 
            'e_civil' => 'e_civil', 
            'codigo' => 'codigo',
            'nacionalidad' => 'nacionalidad',
            'nit' => 'nit',
            'num_func_sigma' => 'num_func_sigma',
            'grupo_sanguineo' => 'grupo_sanguineo',
            'num_lib_ser_militar' => 'num_lib_ser_militar',
            'num_reg_profesional' => 'num_reg_profesional',
            'observacion' => 'observacion',
            'estado' => 'estado',
            'baja_logica' => 'baja_logica',
            'agrupador' => 'agrupador',
            'user_reg_id' => 'user_reg_id',
            'fecha_reg' => 'fecha_reg',
            'user_mod_id' => 'user_mod_id',
            'fecha_mod' => 'fecha_mod',
            'tipo_doc' => 'tipo_doc',
            'foto' => 'foto'
        );
    }
    
    private $_db;
    /**
     * Lista el personal que cuenta con una relación laboral activa con la empresa.
     */
    public function listaPerRelLab(){
        $sql_query = "SELECT p.id, p.p_nombre, p.s_nombre, p.p_apellido, p.s_apellido, 
                        p.ci, p.fecha_nac, p.lugar_nac, p.genero, p.expd 
                      FROM relaborales rl, personas p 
                      WHERE p.id = rl.persona_id AND rl.baja_logica = 1";
        $this->_db = new Personas();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql_query));
    }
}