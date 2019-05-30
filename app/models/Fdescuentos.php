<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  16-10-2015
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Fdescuentos extends \Phalcon\Mvc\Model{
    public $id;
    public $relaboral_id;
    public $gestion;
    public $mes;
    public $mes_descripcion;
    public $faltas;
    public $atrasos;
    public $faltas_atrasos;
    public $lsgh;
    public $abandono;
    public $omision;
    public $retencion;
    public $otros;
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
            'mes_descripcion'=>'mes_descripcion',
            'faltas'=>'faltas',
            'atrasos'=>'atrasos',
            'faltas_atrasos'=>'faltas_atrasos',
            'lsgh'=>'lsgh',
            'abandono'=>'abandono',
            'omision'=>'omision',
            'retencion'=>'retencion',
            'otros'=>'otros',
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

    /**
     * Función para la obtención del listado de registros de descuentos salariales por persona.
     * @param $idPersona
     * @param $gestion
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function getAllByPerson($idPersona,$gestion,$where='',$group='')
    {
        $sql = "SELECT * FROM f_descuentos_salariales_por_persona(".$idPersona.",".$gestion.")";
        if($where!='')$sql .= $where;
        if($group!='')$sql .= $group;
        $this->_db = new Fdescuentos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
} 