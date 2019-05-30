<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  27-10-2015
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Fideas extends \Phalcon\Mvc\Model  {
    public $id;
    public $padre_id;
    public $relaboral_id;
    public $rubro_id;
    public $tipo_negocio;
    public $tipo_negocio_descripcion;
    public $gestion;
    public $mes;
    public $mes_nombre;
    public $numero;
    public $titulo;
    public $resumen;
    public $descripcion;
    public $inversion;
    public $beneficios;
    public $puntuacion_a;
    public $puntuacion_a_descripcion;
    public $puntuacion_b;
    public $puntuacion_b_descripcion;
    public $puntuacion_c;
    public $puntuacion_c_descripcion;
    public $puntuacion_d;
    public $puntuacion_d_descripcion;
    public $puntuacion_e;
    public $puntuacion_e_descripcion;
    public $observacion;
    public $estado;
    public $estado_descripcion;
    public $baja_logica;
    public $agrupador;
    public $user_reg_id;
    public $user_reg;
    public $pseudonimo;
    public $fecha_reg;
    public $user_mod_id;
    public $user_mod;
    public $fecha_mod;
    public $user_punt_a_id;
    public $user_punt_a;
    public $fecha_punt_a;
    public $user_punt_b_id;
    public $user_punt_b;
    public $fecha_punt_b;
    public $user_punt_c_id;
    public $user_punt_c;
    public $fecha_punt_c;
    public $user_punt_d_id;
    public $user_punt_d;
    public $fecha_punt_d;
    public $user_punt_e_id;
    public $user_punt_e;
    public $fecha_punt_e;

    private $_db;

    public function initialize() {
        $this->_db = new Ideas();
    }
    public function columnMap()
    {
        return array(
            'id'=>'id',
            'padre_id'=>'padre_id',
            'relaboral_id'=>'relaboral_id',
            'rubro_id'=>'rubro_id',
            'tipo_negocio'=>'tipo_negocio',
            'tipo_negocio_descripcion'=>'tipo_negocio_descripcion',
            'gestion'=>'gestion',
            'mes'=>'mes',
            'mes_nombre'=>'mes_nombre',
            'numero'=>'numero',
            'titulo'=>'titulo',
            'resumen'=>'resumen',
            'descripcion'=>'descripcion',
            'inversion'=>'inversion',
            'beneficios'=>'beneficios',
            'puntuacion_a'=>'puntuacion_a',
            'puntuacion_a_descripcion'=>'puntuacion_a_descripcion',
            'puntuacion_b'=>'puntuacion_b',
            'puntuacion_b_descripcion'=>'puntuacion_b_descripcion',
            'puntuacion_c'=>'puntuacion_c',
            'puntuacion_c_descripcion'=>'puntuacion_c_descripcion',
            'puntuacion_d'=>'puntuacion_d',
            'puntuacion_d_descripcion'=>'puntuacion_d_descripcion',
            'puntuacion_e'=>'puntuacion_e',
            'puntuacion_e_descripcion'=>'puntuacion_e_descripcion',
            'observacion'=>'observacion',
            'estado'=>'estado',
            'estado_descripcion'=>'estado_descripcion',
            'baja_logica'=>'baja_logica',
            'agrupador'=>'agrupador',
            'user_reg_id'=>'user_reg_id',
            'user_reg'=>'user_reg',
            'pseudonimo'=>'pseudonimo',
            'fecha_reg'=>'fecha_reg',
            'user_mod_id'=>'user_mod_id',
            'user_mod'=>'user_mod',
            'fecha_mod'=>'fecha_mod',
            'user_punt_a_id'=>'user_punt_a_id',
            'user_punt_a'=>'user_punt_a',
            'fecha_punt_a'=>'fecha_punt_a',
            'user_punt_b_id'=>'user_punt_b_id',
            'user_punt_b'=>'user_punt_b',
            'fecha_punt_b'=>'fecha_punt_b',
            'user_punt_c_id'=>'user_punt_c_id',
            'user_punt_c'=>'user_punt_c',
            'fecha_punt_c'=>'fecha_punt_c',
            'user_punt_d_id'=>'user_punt_d_id',
            'user_punt_d'=>'user_punt_d',
            'fecha_punt_d'=>'fecha_punt_d',
            'user_punt_e_id'=>'user_punt_e_id',
            'user_punt_e'=>'user_punt_e',
            'fecha_punt_e'=>'fecha_punt_e'
        );
    }

    /**
     * Función para la obtención del listado de ideas de negocio registradas por una persona o varias en una determinada gestión. En caso de enviarse en el parámetro gestión el valor de cero, se retorna todos los registros.
     * @param $idPersona
     * @param $gestion
     * @param string $where
     * @param string $group
     * @return Resultset
     */

    public function getAllByGestionAndMonth($idPersona,$gestion,$mes,$estado,$where='',$group='',$offset=0,$limit=0){
        if($idPersona>=0&&$gestion>=0&&$mes>=0){
            $sql = "SELECT * FROM f_ideas_por_persona_en_gestion(".$idPersona.",".$gestion.",".$mes.",".$estado.",'$where','$group',".$offset.",".$limit.")";
            $this->_db = new Fideas();
            //echo "<p>-------------------------------------->".$sql;
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }
}