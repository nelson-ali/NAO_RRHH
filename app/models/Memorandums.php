<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  20-11-2014
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Memorandums extends \Phalcon\Mvc\Model {
    public $id;
    public $relaboral_id;
    public $emisor_id;
    public $cargoemisor_id;
    public $autorintelectual_id;
    public $transcriptor_id;
    public $destinocopia_id;
    public $referencia;
    public $finpartida_id;
    public $fecha_mem;
    public $correlativo;
    public $gestion;
    public $da_id;
    public $regional_id;
    public $tipomemorandum_id;
    public $contenido;
    public $observacion;
    public $estado;
    public $baja_logica;
    public $agrupador;
    public $user_reg_id;
    public $fecha_reg;
    public $user_mod_id;
    public $fecha_mod;

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
            'id'=>'id',
            'relaboral_id'=>'relaboral_id',
            'emisor_id'=>'emisor_id',
            'cargoemisor_id'=>'cargoemisor_id',
            'autorintelectual_id'=>'autorintelectual_id',
            'transcriptor_id'=>'transcriptor_id',
            'destinocopia_id'=>'destinocopia_id',
            'referencia'=>'referencia',
            'finpartida_id'=>'finpartida_id',
            'fecha_mem'=>'fecha_mem',
            'correlativo'=>'correlativo',
            'gestion'=>'gestion',
            'da_id'=>'da_id',
            'regional_id'=>'regional_id',
            'tipomemorandum_id'=>'tipomemorandum_id',
            'contenido'=>'contenido',
            'observacion'=>'observacion',
            'estado'=>'estado',
            'baja_logica'=>'baja_logica',
            'agrupador'=>'agrupador',
            'user_reg_id'=>'user_reg_id',
            'fecha_reg'=>'fecha_reg',
            'user_mod_id'=>'user_mod_id',
            'fecha_mod'=>'fecha_mod'
        );
    }
} 