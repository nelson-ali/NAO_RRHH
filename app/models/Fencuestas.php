<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  02-12-2015
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Fencuestas  extends \Phalcon\Mvc\Model{
    public $id_encuesta;
    public $codigo;
    public $titulo;
    public $descripcion;
    public $fecha_ini;
    public $hora_ini;
    public $fecha_fin;
    public $hora_fin;
    public $estado_descripcion;
    public $tiempo_restante_vencido;
    public $permanentes;
    public $permanentes_descripcion;
    public $eventuales;
    public $eventuales_descripcion;
    public $consultores;
    public $consultores_descripcion;
    public $otros;
    public $otros_descripcion;
    public $gerencia_administrativa_id;
    public $gerencia_administrativa;
    public $departamento_administrativo_id;
    public $departamento_administrativo;
    public $area_id;
    public $area;
    public $ubicacion_id;
    public $ubicacion;
    public $num_preguntas;
    public $num_respondidas;
    public $observacion;

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
            'id_encuesta'=>'id_encuesta',
            'codigo'=>'codigo',
            'titulo'=>'titulo',
            'descripcion'=>'descripcion',
            'fecha_ini'=>'fecha_ini',
            'hora_ini'=>'hora_ini',
            'fecha_fin'=>'fecha_fin',
            'hora_fin'=>'hora_fin',
            'estado_descripcion'=>'estado_descripcion',
            'permanentes'=>'permanentes',
            'permanentes_descripcion'=>'permanentes_descripcion',
            'eventuales'=>'eventuales',
            'eventuales_descripcion'=>'eventuales_descripcion',
            'consultores'=>'consultores',
            'consultores_descripcion'=>'consultores_descripcion',
            'otros'=>'otros',
            'otros_descripcion'=>'otros_descripcion',
            'gerencia_administrativa_id'=>'gerencia_administrativa_id',
            'gerencia_administrativa'=>'gerencia_administrativa',
            'departamento_administrativo_id'=>'departamento_administrativo_id',
            'departamento_administrativo'=>'departamento_administrativo',
            'area_id'=>'area_id',
            'area'=>'area',
            'ubicacion_id'=>'ubicacion_id',
            'ubicacion'=>'ubicacion',
            'num_preguntas'=>'num_preguntas',
            'num_respondidas'=>'num_respondidas',
            'agrupador'=>'agrupador',
        );
    }
    private $_db;

    /**
     * Función para la obtencion de los registros de encuestas disponibles de acuerdo a la relación laboral.
     * @param $idRelaboral
     * @return Resultset
     */
    public function getAllByRelaboral($idRelaboral,$estado=100)
    {
        if($idRelaboral>0){
            $sql = "SELECT * FROM f_encuestas_disponibles_por_relaboral($idRelaboral,$estado)";
            //echo "<p>--->".$sql;
            $this->_db = new Fexcepciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
        return false;
    }

    /**
     * Función para la obtención del listado de preguntas y respuesta por encuesta, considerados los ya respondidos.
     * @param $idRelaboral
     * @param $idEncuesta
     * @return bool|Resultset
     */
    public function getAllQuestionsAndAnswers($idRelaboral,$idEncuesta)
    {
        if($idRelaboral>0&&$idEncuesta>0){
            $sql = "SELECT * FROM f_encuestas_preguntas_respuestas($idRelaboral,$idEncuesta)";
            //echo $sql;
            $this->_db = new Fexcepciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
        return false;
    }
    /*
     * Función para la obtención del detalle de cantidades de respuestas por preguntas.
     */
    public function getCountByQuestionsOptionsRestricted($idEncuesta,$idPregunta)
    {
        if($idEncuesta>0&&$idPregunta>0){
            $sql = "SELECT * FROM f_encuesta_cantidades_preguntas_opcion_multiple_restringida($idEncuesta,$idPregunta)";
            $this->_db = new Fexcepciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
        return false;
    }
} 