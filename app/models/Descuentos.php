<?php
/**
 * Created by PhpStorm.
 * User: GGE-JLOZA
 * Date: 26/05/2015
 * Time: 09:11 AM
 */
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Descuentos extends \Phalcon\Mvc\Model{
    public $id;
    public $relaboral_id;
    public $gestion;
    public $mes;
    public $faltas;
    public $atrasos;
    public $faltas_atrasos;
    public $lsgh;
    public $abandono;
    public $omision;
    public $retencion;
    public $otros;
    public $total_descuentos_sancionados;
    public $total_descuentos;
    public $observacion;
    public $motivo_anu;
    public $estado;
    public $baja_logica;
    public $agrupador;
    public $user_reg_id;
    public $fecha_reg;
    public $user_mod_id;
    public $fecha_mod;
    public $user_anu_id;
    public $fecha_anu;

    public function initialize() {
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
            'gestion'=>'gestion',
            'mes'=>'mes',
            'faltas'=>'faltas',
            'atrasos'=>'atrasos',
            'faltas_atrasos'=>'faltas_atrasos',
            'lsgh'=>'lsgh',
            'abandono'=>'abandono',
            'omision'=>'omision',
            'retencion'=>'retencion',
            'otros'=>'otros',
            'total_descuentos_sancionados'=>'total_descuentos_sancionados',
            'total_descuentos'=>'total_descuentos',
            'observacion'=>'observacion',
            'motivo_anu'=>'motivo_anu',
            'estado'=>'estado',
            'baja_logica'=>'baja_logica',
            'agrupador'=>'agrupador',
            'user_reg_id'=>'user_reg_id',
            'fecha_reg'=>'fecha_reg',
            'user_mod_id'=>'user_mod_id',
            'fecha_mod'=>'fecha_mod',
            'user_anu_id'=>'user_anu_id',
            'fecha_anu'=>'fecha_anu'
        );
    }
} 