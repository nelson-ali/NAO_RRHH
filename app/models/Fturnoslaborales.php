<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  18-12-2014
*/


use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Fturnoslaborales extends \Phalcon\Mvc\Model {
    public $id_perfillaboral;
    public $perfil_laboral;
    public $grupo;
    public $gestion;
    public $numero_mes;
    public $mes;
    public $fecha_ini;
    public $fecha_fin;
    public $tipo_horario;
    public $tipo_horario_descripcion;
    public $estado;
    public $estado_descripcion;
    public $id_tolerancia;
    public $tipo_tolerancia;
    public $id_jornada_laboral;
    public $jornada_laboral;
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
            'id_perfillaboral'=>'id_perfillaboral',
            'perfil_laboral'=>'perfil_laboral',
            'grupo'=>'grupo',
            'gestion'=>'gestion',
            'numero_mes'=>'numero_mes',
            'mes'=>'mes',
            'fecha_ini'=>'fecha_ini',
            'fecha_fin'=>'fecha_fin',
            'tipo_horario'=>'tipo_horario',
            'tipo_horario_descripcion'=>'tipo_horario_descripcion',
            'estado'=>'estado',
            'estado_descripcion'=>'estado_descripcion',
            'id_tolerancia'=>'id_tolerancia',
            'tipo_tolerancia'=>'tipo_tolerancia',
            'id_jornada_laboral'=>'id_jornada_laboral',
            'jornada_laboral'=>'jornada_laboral'
        );
    }
    private $_db;

    /**
     * Función para la obtención del listado de todos los turnos laborales registrados en el sistema.
     * @return Resultset
     */
    public function getAll(){
        $sql = "SELECT * FROM f_listado_turnos_laborales_todos()";
        $this->_db = new Fturnoslaborales();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
    /**
     * Función para la obtención del listado de turnos laborales de acuerdo a un perfil laboral en una determinada gestión.
     * @return Resultset
     */
    public function getAllByOne($idPerfilLaboral)
    {
        $sql = "SELECT * from f_listado_turnos_laborales(".$idPerfilLaboral.")";
        $this->_db = new Fturnoslaborales();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

}