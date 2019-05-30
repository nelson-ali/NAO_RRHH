<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  31-12-2015
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Ftiposdocumentos extends \Phalcon\Mvc\Model
{
    public $id;
    public $tipo_documento;
    public $codigo;
    public $indispensable;
    public $indispensable_descripcion;
    public $id_tipopresdoc;
    public $tipo_pres_doc;
    public $id_periodopresdoc;
    public $periodo_pres_doc;
    public $id_tipoperssoldoc;
    public $tipo_pers_sol_doc;
    public $id_tipoemisordoc;
    public $tipo_emisor_doc;
    public $id_tipofechaemidoc;
    public $tipo_fecha_emi_doc;
    public $hora;
    public $hora_descripcion;
    public $dia;
    public $dia_descripcion;
    public $mes;
    public $mes_descripcion;
    public $trimestre;
    public $trimestre_descripcion;
    public $semestre;
    public $semestre_descripcion;
    public $gestion;
    public $gestion_descripcion;
    public $tipofechaemidoc_descripcion;
    public $id_genero;
    public $genero;
    public $id_normativamod;
    public $normativamod;
    public $nivelsalarial_nivel;
    public $nivelsalarial_nivel_denominacion;
    public $permanente;
    public $permanente_descripcion;
    public $eventual;
    public $eventual_descripcion;
    public $consultor_linea;
    public $consultor_linea_descripcion;
    public $consultor_producto;
    public $consultor_producto_descripcion;
    public $id_grupoarchivo;
    public $grupo_archivo;
    public $ruta_carpeta;
    public $nombre_carpeta;
    public $formato_archivo_digital;
    public $resolucion_archivo_digital;
    public $altura_archivo_digital;
    public $anchura_archivo_digital;
    public $columnas_aux;
    public $columnas_aux_min;
    public $fecha_ini;
    public $fecha_fin;
    public $observacion;
    public $estado;
    public $estado_descripcion;
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
            'tipo_documento' => 'tipo_documento',
            'codigo' => 'codigo',
            'id_tipopresdoc' => 'id_tipopresdoc',
            'tipo_pres_doc' => 'tipo_pres_doc',
            'id_periodopresdoc' => 'id_periodopresdoc',
            'periodo_pres_doc' => 'periodo_pres_doc',
            'id_tipoperssoldoc' => 'id_tipoperssoldoc',
            'tipo_pers_sol_doc' => 'tipo_pers_sol_doc',
            'id_tipoemisordoc' => 'id_tipoemisordoc',
            'tipo_emisor_doc' => 'tipo_emisor_doc',
            'id_tipofechaemidoc' => 'id_tipofechaemidoc',
            'tipo_fecha_emi_doc' => 'tipo_fecha_emi_doc',
            'hora' => 'hora',
            'hora_descripcion' => 'hora_descripcion',
            'dia' => 'dia',
            'dia_descripcion' => 'dia_descripcion',
            'mes' => 'mes',
            'mes_descripcion' => 'mes_descripcion',
            'trimestre' => 'trimestre',
            'trimestre_descripcion' => 'trimestre_descripcion',
            'gestion' => 'gestion',
            'gestion_descripcion' => 'gestion_descripcion',
            'tipofechaemidoc_descripcion' => 'tipofechaemidoc_descripcion',
            'id_genero' => 'id_genero',
            'genero' => 'genero',
            'id_normativamod' => 'id_normativamod',
            'normativamod' => 'normativamod',
            'permanente' => 'permanente',
            'permanente_descripcion' => 'permanente_descripcion',
            'eventual' => 'eventual',
            'eventual_descripcion' => 'eventual_descripcion',
            'consultor_linea' => 'consultor_linea',
            'consultor_linea_descripcion' => 'consultor_linea_descripcion',
            'consultor_producto' => 'consultor_producto',
            'consultor_producto_descripcion' => 'consultor_producto_descripcion',
            'id_grupoarchivo' => 'id_grupoarchivo',
            'grupo_archivo' => 'grupo_archivo',
            'ruta_carpeta' => 'ruta_carpeta',
            'nombre_carpeta' => 'nombre_carpeta',
            'formato_archivo_digital' => 'formato_archivo_digital',
            'resolucion_archivo_digital' => 'resolucion_archivo_digital',
            'altura_archivo_digital' => 'altura_archivo_digital',
            'anchura_archivo_digital' => 'anchura_archivo_digital',
            'columnas_aux' => 'columnas_aux',
            'columnas_aux_min' => 'columnas_aux_min',
            'fecha_ini' => 'fecha_ini',
            'fecha_fin' => 'fecha_fin',
            'observacion' => 'observacion',
            'estado' => 'estado',
            'estado_descripcion' => 'estado_descripcion',
            'baja_logica' => 'baja_logica',
            'agrupador' => 'agrupador',
            'user_reg_id' => 'user_reg_id',
            'fecha_reg' => 'fecha_reg',
            'user_mod_id' => 'user_mod_id',
            'fecha_mod' => 'fecha_mod'
        );
    }

    private $_db;

    /**
     * Función para la obtención de los registros de tipos de documentos. En caso de que el parámetro $idTipoDocumento es distinto de cero, se devuelve el registro identificado con este datos, caso contrario todos los registos ACTIVOS.
     * @param int $idTipoDocumento
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function getAll($idTipoDocumento = 0, $where = '', $group = '')
    {
        $sql = "SELECT * FROM f_tiposdocumentos_por_id($idTipoDocumento)";
        if ($where != '') $sql .= $where;
        if ($group != '') $sql .= $group;
        $this->_db = new Ftiposdocumentos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para obtener el listado de tipos de documentos en función a un registro de relación laboral y agrupaciones.
     * @param int $idGrupo
     * @param $idRelaboral
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function getAllByGroups($idGrupo = 0, $idRelaboral, $where = '', $group = '')
    {
        if ($idGrupo > 0 && $idRelaboral >= 0) {
            $sql = "SELECT * FROM f_tiposdocumentos_agrupados($idGrupo,$idRelaboral)";
            if ($where != '') $sql .= $where;
            if ($group != '') $sql .= $group;
            $this->_db = new Ftiposdocumentos();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para la actualización de los campos considerados para un tipo de documento en particular.
     * @param $idTipoDocumento
     * @return mixed
     */
    public function updAllAuxColumnsInPresentacionDoc($idTipoDocumento)
    {
        if ($idTipoDocumento > 0) {
            $sql = "select f_presentacionesdoc_actualiza_campos_aux as o_resultado from f_presentacionesdoc_actualiza_campos_aux($idTipoDocumento)";
            $this->_db = new Ftiposdocumentos();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (count($arr) > 0) return $arr[0]->o_resultado;
        }
    }
} 