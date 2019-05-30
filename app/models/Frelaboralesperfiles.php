<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  30-01-2015
*/

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Frelaboralesperfiles extends \Phalcon\Mvc\Model
{
    public $padre_id;
    public $id;
    public $id_ubicacion;
    public $ubicacion;
    public $id_Estacion;
    public $estacion;
    public $color;
    public $id_perfillaboral;
    public $perfil_laboral;
    public $fecha_ini;
    public $fecha_fin;
    public $estado;
    public $estado_descripcion;
    public $tipo_horario;
    public $tipo_horario_descripcion;

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
            'padre_id' => 'padre_id',
            'id' => 'id',
            'id_ubicacion' => 'id_ubicacion',
            'ubicacion' => 'ubicacion',
            'id_Estacion' => 'id_Estacion',
            'estacion' => 'estacion',
            'color' => 'color',
            'id_perfillaboral' => 'id_perfillaboral',
            'perfil_laboral' => 'perfil_laboral',
            'fecha_ini' => 'fecha_ini',
            'fecha_fin' => 'fecha_fin',
            'estado' => 'estado',
            'estado_descripcion' => 'estado_descripcion',
            'tipo_horario' => 'tipo_horario',
            'tipo_horario_descripcion' => 'tipo_horario_descripcion'
        );
    }

    private $_db;

    /**
     * Función para la obtención de la totalidad de los registros de asignaciones de perfiles laborales por ubicación y rango de fechas.
     * @return Resultset
     */
    public function getAll()
    {
        $sql = "SELECT * from f_listado_asignaciones_perfiles_agrupado()";
        $this->_db = new Frelaboralesperfiles();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del listado de registros de asignación de ubicación y rango de fechas para personal de la empresa.
     * @param $idPerfilLaboral
     * @return Resultset
     */
    public function getAllByPerfil($idPerfilLaboral)
    {
        $sql = "SELECT * from f_listado_asignaciones_perfiles_agrupado()";
        if ($idPerfilLaboral > 0) $sql .= " WHERE id_perfillaboral=" . $idPerfilLaboral;
        $this->_db = new Frelaboralesperfiles();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del listado de registros de asignación de perfil laboral a un registro de relación laboral.
     * @param $idRelaboral
     * @param $idPerfilLaboral
     * @param $idUbicacion
     * @param $fechaIni
     * @param $fechaFin
     */
    public function getAllByRelaboral($idRelaboral, $idPerfilLaboral, $idUbicacion, $fechaIni, $fechaFin)
    {
        $sql = "SELECT * FROM f_relaborales_asignados_a_perfil($idPerfilLaboral,$idUbicacion,'$fechaIni','$fechaFin')";
        if ($idRelaboral > 0) $sql .= " WHERE id_relaboral=" . $idRelaboral;
        $this->_db = new Frelaboralesperfiles();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para el intercambio de perfiles a través del procedimiento regular.
     * @param $idPerfilLaboralOrigen
     * @param $idPerfilLaboralDestino
     * @param int $idOrganigrama
     * @param int $genero
     * @param $fecha
     * @param $observacion
     * @param $idUsuario
     * @return null
     */
    public function intercambiarPerfilesRegular($idPerfilLaboralOrigen, $idPerfilLaboralDestino, $idOrganigrama = 0, $genero = 0, $fecha, $observacion, $idUsuario, $todaMarcacion=0)
    {
        if ($idPerfilLaboralOrigen > 0 && $idPerfilLaboralDestino > 0 && $idUsuario > 0) {
            $sql = "SELECT f_intercambio_asignacion_perfillaboral_en_dia AS o_resultado FROM f_intercambio_asignacion_perfillaboral_en_dia($idPerfilLaboralOrigen,$idPerfilLaboralDestino,$idOrganigrama,$genero,'$fecha','$observacion',$idUsuario, $todaMarcacion)";
            //echo "--->".$sql;
            $this->_db = new Frelaboralesperfiles();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (count($arr) > 0) return $arr[0]->o_resultado;
        }
        return null;
    }

    /**
     * Función para el intercambio de perfiles en base a un listado de carnets de identidad. Que implica que se hará el intercambio de las personas referidas en específico a través de su CI.
     * @param $idPerfilLaboralOrigen
     * @param $idPerfilLaboralDestino
     * @param $listCi
     * @param $fecha
     * @param $observacion
     * @param $idUsuario
     * @return null
     */
    public function intercambiarPerfilesPorCi($idPerfilLaboralOrigen, $idPerfilLaboralDestino, $listCi, $fecha, $observacion, $idUsuario, $todaMarcacion = 0)
    {
        if ($idPerfilLaboralOrigen > 0 && $idPerfilLaboralDestino > 0 && $listCi != '' && $idUsuario > 0) {
            $sql = "SELECT f_intercambio_asignacion_perfillaboral_en_dia_ci AS o_resultado FROM f_intercambio_asignacion_perfillaboral_en_dia_ci($idPerfilLaboralOrigen,$idPerfilLaboralDestino,'$fecha','$observacion','$listCi',$idUsuario, $todaMarcacion)";
            //echo "-->".$sql;
            $this->_db = new Frelaboralesperfiles();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (count($arr) > 0) return $arr[0]->o_resultado;
        }
        return null;
    }

    /**
     * Función para la obtención del reporte de asignación de perfiles para conocer en qué perfiles se asignaron a las personas y también a qué personas les falta tal asignación.
     * @param $idRelaborales
     * @param string $fechaIni
     * @param string $fechaFin
     * @return null|Resultset
     */
    public function obtenerResumenAsignaciones($idRelaborales, $fechaIni="", $fechaFin="")    {
        if ($idRelaborales != '' && $fechaIni != "" && $fechaFin != "") {
            $sql = "SELECT * FROM f_relaboralesperfiles_rango_fechas('".$idRelaborales."','$fechaIni','$fechaFin')";
            //echo $sql;
            $this->_db = new Frelaboralesperfiles();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
        return null;
    }
} 