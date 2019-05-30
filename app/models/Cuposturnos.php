<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  29-01-2015
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Cuposturnos extends \Phalcon\Mvc\Model {
    public $id;
    public $perfillaboral_id;
    public $ubicacion_id;
    public $fecha_ini;
    public $fecha_fin;
    public $cupo;
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
            'id'=>'id',
            'perfillaboral_id'=>'perfillaboral_id',
            'ubicacion_id'=>'ubicacion_id',
            'fecha_ini'=>'fecha_ini',
            'fecha_fin'=>'fecha_fin',
            'cupo'=>'cupo',
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
    private $_db;
    /**
     * Función para la obtención de la totalidad de los registros de  cupos por turnos.
     * @return Resultset
     */
    public function getAll()
    {
        $sql = "SELECT * from cuposturnos";
        $this->_db = new Cuposturnos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

} 