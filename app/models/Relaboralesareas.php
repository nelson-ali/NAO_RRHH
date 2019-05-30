<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  07-11-2014
*/

class Relaboralesareas  extends \Phalcon\Mvc\Model {
    public $id;
    public $relaboral_id;
    public $organigrama_id;
    public $observacion;
    public $estado;
    public $baja_logica;
    public $agrupador;
    public $user_reg_id;
    public $fecha_reg;
    public $user_mod_id;
    public $fecha_mod;
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
            'relaboral_id' => 'relaboral_id',
            'organigrama_id' => 'organigrama_id',
            'observacion' => 'observacion',
            'estado' => 'estado',
            'baja_logica' => 'baja_logica',
            'agrupador'=>'agrupador',
            'user_reg_id'=>'user_reg_id',
            'fecha_reg'=>'fecha_reg',
            'user_mod_id'=>'user_mod_id',
            'fecha_mod'=>'fecha_mod'
        );
    }
    public function verificarCorrectaCorrespondeciaArea(){
        return true;
    }
}