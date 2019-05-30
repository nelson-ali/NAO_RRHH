<?php

/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  16-05-2016
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Feventosmoviles extends \Phalcon\Mvc\Model
{
    public $total_rows;
    public $id_eventomovil;
    public $evento_movil;
    public $eventomovil_descripcion;
    public $cant_marcaciones;
    public $eventomovil_observacion;
    public $eventomovil_estado;
    public $eventomovil_estado_descripcion;
    public $id_eventomarcacion;
    public $num_marcacion;
    public $tipo_marcacion;
    public $tipo_marcacion_descripcion;
    public $referencia;
    public $latitud;
    public $longitud;
    public $radio;
    public $planillable;
    public $planillable_descripcion;
    public $fecha_ini;
    public $fecha_fin;
    public $plazo;
    public $plazo_descripcion;
    public $eventomarcacion_descripcion;
    public $eventomarcacion_observacion;
    public $eventomarcacion_estado;
    public $eventomarcacion_estado_descripcion;
    public $eventomovil_user_reg_id;
    public $eventomovil_fecha_reg;
    public $eventomarcacion_user_reg_id;
    public $eventomarcacion_fecha_reg;
    public $eventomovil_user_mod_id;
    public $eventomovil_fecha_mod;
    public $eventomarcacion_user_mod_id;
    public $eventomarcacion_fecha_mod;

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
            'total_rows'=>'total_rows',
            'id_eventomovil'=>'id_eventomovil',
            'evento_movil'=>'evento_movil',
            'eventomovil_descripcion'=>'eventomovil_descripcion',
            'cant_marcaciones'=>'cant_marcaciones',
            'eventomovil_observacion'=>'eventomovil_observacion',
            'eventomovil_estado'=>'eventomovil_estado',
            'eventomovil_estado_descripcion'=>'eventomovil_estado_descripcion',
            'id_eventomarcacion'=>'id_eventomarcacion',
            'num_marcacion'=>'num_marcacion',
            'tipo_marcacion'=>'tipo_marcacion',
            'tipo_marcacion_descripcion'=>'tipo_marcacion_descripcion',
            'referencia'=>'referencia',
            'latitud'=>'latitud',
            'longitud'=>'longitud',
            'radio'=>'radio',
            'planillable'=>'planillable',
            'planillable_descripcion'=>'planillable_descripcion',
            'fecha_ini'=>'fecha_ini',
            'fecha_fin'=>'fecha_fin',
            'plazo'=>'plazo',
            'plazo_descripcion'=>'plazo_descripcion',
            'eventomarcacion_descripcion'=>'eventomarcacion_descripcion',
            'eventomarcacion_observacion'=>'eventomarcacion_observacion',
            'eventomarcacion_estado'=>'eventomarcacion_estado',
            'eventomarcacion_estado_descripcion'=>'eventomarcacion_estado_descripcion',
            'eventomovil_user_reg_id'=>'eventomovil_user_reg_id',
            'eventomovil_fecha_reg'=>'eventomovil_fecha_reg',
            'eventomarcacion_user_reg_id'=>'eventomarcacion_user_reg_id',
            'eventomarcacion_fecha_reg'=>'eventomarcacion_fecha_reg',
            'eventomovil_user_mod_id'=>'eventomovil_user_mod_id',
            'eventomovil_fecha_mod'=>'eventomovil_fecha_mod',
            'eventomarcacion_user_mod_id'=>'eventomarcacion_user_mod_id',
            'eventomarcacion_fecha_mod'=>'eventomarcacion_fecha_mod'
        );
    }

    private $_db;

    /**
     * Función para obtener el listado de los registros de eventos móviles.
     * @param int $idEventoMovil
     * @param string $where
     * @param string $group
     * @param int $offset
     * @param int $limit
     * @return Resultset
     */
    public function getAll($idEventoMovil=0,$where='',$group='',$offset=0,$limit=0){
        $sql = "SELECT * FROM f_eventosmoviles($idEventoMovil,'$where','$group',$offset,$limit)";
        //echo "<p>------->".$sql;
        $this->_db = new Feventosmoviles();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
}