<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  17-11-2014
*/


use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Frelaboralesmovilidad extends \Phalcon\Mvc\Model {
    public $id_relaboral;
    public $id_relaboralmovilidad;
    public $id_gerencia_administrativa;
    public $gerencia_administrativa;
    public $id_departamento_administrativo;
    public $departamento_administrativo;
    public $id_organigrama;
    public $unidad_administrativa;
    public $organigrama_sigla;
    public $organigrama_codigo;
    public $id_area;
    public $area;
    public $id_ubicacion;
    public $ubicacion;
    public $numero;
    public $cargo;
    public $evento_id;
    public $evento;
    public $motivo;
    public $id_pais;
    public $pais;
    public $id_departamento;
    public $departamento;
    public $lugar;
    public $fecha_ini;
    public $hora_ini;
    public $fecha_fin;
    public $hora_fin;
    public $id_memorandum;
    public $id_tipomemorandum;
    public $tipo_memorandum;
    public $memorandum_correlativo;
    public $memorandum_gestion;
    public $fecha_mem;
    public $observacion;
    public $estado;
    public $estado_descripcion;
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
            'id_relaboral'=>'id_relaboral',
            'id_relaboralmovilidad'=>'id_relaboralmovilidad',
            'id_gerencia_administrativa'=>'id_gerencia_administrativa',
            'gerencia_administrativa'=>'gerencia_administrativa',
            'id_departamento_administrativo'=>'id_departamento_administrativo',
            'departamento_administrativo'=>'departamento_administrativo',
            'id_organigrama'=>'id_organigrama',
            'unidad_administrativa'=>'unidad_administrativa',
            'organigrama_sigla'=>'organigrama_sigla',
            'organigrama_codigo'=>'organigrama_codigo',
            'id_area'=>'id_area',
            'area'=>'area',
            'id_ubicacion'=>'id_ubicacion',
            'ubicacion'=>'ubicacion',
            'numero'=>'numero',
            'cargo'=>'cargo',
            'evento_id'=>'evento_id',
            'evento'=>'evento',
            'motivo'=>'motivo',
            'id_pais'=>'id_pais',
            'pais'=>'pais',
            'id_departamento'=>'id_departamento',
            'departamento'=>'departamento',
            'lugar'=>'lugar',
            'hora_ini'=>'hora_ini',
            'fecha_ini'=>'fecha_ini',
            'fecha_fin'=>'fecha_fin',
            'hora_fin'=>'hora_fin',
            'id_memorandum'=>'id_memorandum',
            'id_tipomemorandum'=>'id_tipomemorandum',
            'tipo_memorandum'=>'tipo_memorandum',
            'memorandum_correlativo'=>'memorandum_correlativo',
            'memorandum_gestion'=>'memorandum_gestion',
            'fecha_mem'=>'fecha_mem',
            'observacion'=>'observacion',
            'estado'=>'estado',
            'estado_descripcion'=>'estado_descripcion'
        );
    }
    private $_db;
    /**
     * Función para la obtención de la totalidad de los registros de relaciones laborales.
     * @return Resultset
     */
    public function getAll()
    {
        $sql = "SELECT * from f_relaborales_movilidad()";
        $this->_db = new Frelaboralesmovilidad();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del registro correspondiente al identificador de la relación laboral por movilidad.
     * @param $idRelaboralMovilidad
     * @return Resultset
     */
    public function getOne($idRelaboralMovilidad)
    {
        if($idRelaboralMovilidad>0){
            $sql = "SELECT * from f_relaborales_movilidad()";
            $sql .=" WHERE id_relaboralmovilidad=".$idRelaboralMovilidad;
            $this->_db = new Frelaboralesmovilidad();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }else return new Frelaboralesmovilidad();
    }
    /**
     * Función para la obtención de la totalidad de los registros de relaciones laborales para un persona en particular.
     * @return Resultset
     */
    public function getAllByOne($idRelaboral)
    {
        if($idRelaboral>0){
            $sql = "SELECT * from f_relaborales_movilidad()";
            $sql .=" WHERE id_relaboral=".$idRelaboral;
            $this->_db = new Frelaboralesmovilidad();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }else return new Frelaboralesmovilidad();
    }
} 