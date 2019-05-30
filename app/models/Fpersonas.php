<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  29-10-2014
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Fpersonas extends \Phalcon\Mvc\Model {

    public $id_persona;
    public $postulante_id;
    public $p_nombre;
    public $s_nombre;
    public $t_nombre;
    public $p_apellido;
    public $s_apellido;
    public $c_apellido;
    public $tipo_documento;
    public $ci;
    public $expd;
    public $fecha_caducidad;
    public $fecha_nac;
    public $edad;
    public $lugar_nac;
    public $genero;
    public $e_civil;
    public $codigo;
    public $nacionalidad;
    public $nit;
    public $num_func_sigma;
    public $grupo_sanguineo;
    public $num_lib_ser_militar;
    public $num_reg_profesional;
    public $observacion;
    public $estado;
    public $baja_logica;
    public $agrupador;
    public $user_reg_id;
    public $fecha_reg;
    public $user_mod_id;
    public $fecha_mod;
    public $direccion_dom;
    public $telefono_fijo;
    public $telefono_inst;
    public $telefono_fax;
    public $interno_inst;
    public $celular_per;
    public $celular_inst;
    public $num_credencial;
    public $ac_no;
    public $e_mail_per;
    public $e_mail_inst;
    public $contacto_observacion;
    public $contacto_estado;

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
            'id_persona' => 'id_persona',
            'postulante_id'=>'postulante_id',
            'p_nombre'=>'p_nombre',
            's_nombre'=>'s_nombre',
            't_nombre'=>'t_nombre',
            'p_apellido'=>'p_apellido',
            's_apellido'=>'s_apellido',
            'c_apellido'=>'c_apellido',
            'tipo_documento'=>'tipo_documento',
            'ci'=>'ci',
            'expd'=>'expd',
            'fecha_caducidad'=>'fecha_caducidad',
            'fecha_nac'=>'fecha_nac',
            'edad'=>'edad',
            'lugar_nac'=>'lugar_nac',
            'genero'=>'genero',
            'e_civil'=>'e_civil',
            'codigo'=>'codigo',
            'nacionalidad'=>'nacionalidad',
            'nit'=>'nit',
            'num_func_sigma'=>'num_func_sigma',
            'grupo_sanguineo'=>'grupo_sanguineo',
            'num_lib_ser_militar'=>'num_lib_ser_militar',
            'num_reg_profesional'=>'num_reg_profesional',
            'observacion'=>'observacion',
            'estado'=>'estado',
            'baja_logica'=>'baja_logica',
            'agrupador'=>'agrupador',
            'user_reg_id'=>'user_reg_id',
            'fecha_reg'=>'fecha_reg',
            'user_mod_id'=>'user_mod_id',
            'fecha_mod'=>'fecha_mod',
            'direccion_dom'=>'direccion_dom',
            'telefono_fijo'=>'telefono_fijo',
            'telefono_inst'=>'telefono_inst',
            'telefono_fax'=>'telefono_fax',
            'interno_inst'=>'interno_inst',
            'celular_per'=>'celular_per',
            'celular_inst'=>'celular_inst',
            'num_credencial'=>'num_credencial',
            'ac_no'=>'ac_no',
            'e_mail_per'=>'e_mail_per',
            'e_mail_inst'=>'e_mail_inst',
            'contacto_observacion'=>'contacto_observacion',
            'contacto_estado'=>'contacto_estado'
        );
    }
    private $_db;
    /**
     * Función para la obtención del registro de una persona adicionando sus datos de contacto.
     * @return Resultset
     */
    public function getOne($id)
    {
        $sql = "SELECT * from f_personas_por_id($id)";
        $this->_db = new Fpersonas();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del pseudónimo correspondiente a la persona.
     * @param $idPersona
     * @return mixed
     */
    public function getPseudonimo($idPersona)
    {   if($idPersona>0) {
        $sql = "SELECT o_pseudonimo FROM f_obtener_pseudonimo($idPersona) ";
        $this->_db = new Fpersonas();
        $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        return $arr[0]->o_pseudonimo;
    }
    }
} 