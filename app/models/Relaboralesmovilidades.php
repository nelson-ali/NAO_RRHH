<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  20-11-2014
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Relaboralesmovilidades extends \Phalcon\Mvc\Model {
   /* public $id;
    public $relaboral_id;
    public $da_id;
    public $regional_id;
    public $organigrama_id;
    public $area_id;
    public $ubicacion_id;
    public $cargo;
    public $modalidadmemorandum_id;
    public $memorandum_id;
    public $numero;
    public $fecha_ini;
    public $hora_ini;
    public $fecha_fin;
    public $hora_fin;
    public $observacion;
    public $estado;
    public $baja_logica;
    public $agrupador;
    public $user_reg_id;
    public $fecha_reg;
    public $user_mod_id;
    public $fecha_mod;*/

    public function initialize()
    {
        $this->setSchema("");
    }

    /**
     * Independent Column Mapping.
     */
//    public function columnMap()
//    {
//        return array(
//            'id'=>'id',
//            'relaboral_id'=>'relaboral_id',
//            'da_id'=>'da_id',
//            'regional_id'=>'regional_id',
//            'organigrama_id'=>'organigrama_id',
//            'area_id'=>'area_id',
//            'ubicacion_id'=>'ubicacion_id',
//            'cargo'=>'cargo',
//            'modalidadmemorandum_id'=>'modalidadmemorandum_id',
//            'memorandum_id'=>'memorandum_id',
//            'numero'=>'numero',
//            'fecha_ini'=>'fecha_ini',
//            'hora_ini'=>'hora_ini',
//            'fecha_fin'=>'fecha_fin',
//            'hora_fin'=>'hora_fin',
//            'observacion'=>'observacion',
//            'estado'=>'estado',
//            'baja_logica'=>'baja_logica',
//            'agrupador'=>'agrupador',
//            'user_reg_id'=>'user_reg_id',
//            'fecha_reg'=>'fecha_reg',
//            'user_mod_id'=>'user_mod_id',
//            'fecha_mod'=>'fecha_mod'
//        );
//    }
//    private $_db;

}