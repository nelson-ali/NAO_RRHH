<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  06-07-2015
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Frelaboralesplanillaref extends \Phalcon\Mvc\Model
{
    public $id_relaboral;
    public $gestion;
    public $mes;
    public $mes_nombre;
    public $nombres;
    public $ci;
    public $expd;
    public $id_gerencia_administrativa;
    public $gerencia_administrativa;
    public $id_departamento_administrativo;
    public $departamento_administrativo;
    public $id_organigrama;
    public $unidad_administrativa;
    public $organigrama_sigla;
    public $organigrama_orden;
    public $id_area;
    public $area;
    public $id_ubicacion;
    public $ubicacion;
    public $id_nivelsalarial;
    public $nivel_salarial;
    public $cargo;
    public $sueldo;
    public $convocatoria_tipo;
    public $id_procesocontratacion;
    public $procesocontratacion_codigo;
    public $id_finpartida;
    public $fin_partida;
    public $id_condicion;
    public $condicion;
    public $tiene_item;
    public $tiene_contrato_vigente;
    public $fecha_ini;
    public $fecha_incor;
    public $fecha_fin;
    public $fecha_baja;
    public $estado;
    public $estado_descripcion;
    public $dias_efectivos;
    public $faltas;
    public $lsgh;
    public $vacacion;
    public $otros;
    public $id_form110impref;
    public $importe;
    public $rc_iva;
    public $retencion;
    public $form110impref_observacion;
    public $fecha_form;
    public $total_descuentos;
    public $total_ganado;
    public $total_liquido;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->_db = new Frelaboralesplanillaref();
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id_relaboral' => 'id_relaboral',
            'gestion' => 'gestion',
            'mes' => 'mes',
            'mes_nombre' => 'mes_nombre',
            'nombres' => 'nombres',
            'ci' => 'ci',
            'expd' => 'expd',
            'id_gerencia_administrativa' => 'id_gerencia_administrativa',
            'gerencia_administrativa' => 'gerencia_administrativa',
            'id_departamento_administrativo' => 'id_departamento_administrativo',
            'departamento_administrativo' => 'departamento_administrativo',
            'id_organigrama' => 'id_organigrama',
            'unidad_administrativa' => 'unidad_administrativa',
            'organigrama_sigla' => 'organigrama_sigla',
            'organigrama_orden' => 'organigrama_orden',
            'id_area' => 'id_area',
            'area' => 'area',
            'id_ubicacion' => 'id_ubicacion',
            'ubicacion' => 'ubicacion',
            'id_nivelsalarial' => 'id_nivelsalarial',
            'nivel_salarial' => 'nivel_salarial',
            'cargo' => 'cargo',
            'sueldo' => 'sueldo',
            'convocatoria_tipo' => 'convocatoria_tipo',
            'id_procesocontratacion' => 'id_procesocontratacion',
            'procesocontratacion_codigo' => 'procesocontratacion_codigo',
            'id_finpartida' => 'id_finpartida',
            'fin_partida' => 'fin_partida',
            'id_condicion' => 'id_condicion',
            'condicion' => 'condicion',
            'tiene_item' => 'tiene_item',
            'tiene_contrato_vigente' => 'tiene_contrato_vigente',
            'fecha_ini' => 'fecha_ini',
            'fecha_incor' => 'fecha_incor',
            'fecha_fin' => 'fecha_fin',
            'fecha_baja' => 'fecha_baja',
            'estado' => 'estado',
            'estado_descripcion' => 'estado_descripcion',
            'dias_efectivos' => 'dias_efectivos',
            'faltas' => 'faltas',
            'lsgh' => 'lsgh',
            'vacacion' => 'vacacion',
            'otros' => 'otros',
            'id_form110impref' => 'id_form110impref',
            'importe' => 'importe',
            'rc_iva' => 'rc_iva',
            'rentencion' => 'rentencion',
            'form110impref_observacion' => 'form110impref_observacion',
            'fecha_form' => 'fecha_form',
            'total_descuentos' => 'total_descuentos',
            'total_ganado' => 'total_ganado',
            'total_liquido' => 'total_liquido'
        );
    }

    private $_db;

    /**
     * Función para generar la planilla salarial
     * @param $gestion
     * @param $mes
     * @param $idFinPartida
     * @param $jsonIdRelaborales
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function desplegarPlanillaPreviaRef($gestion, $mes, $idFinPartida, $jsonIdRelaborales, $where = '', $group = '')
    {
        if ($gestion > 0 && $mes > 0 && $idFinPartida > 0) {
            if ($jsonIdRelaborales != '')
                $sql = "SELECT * FROM f_relaborales_planillaref_generacion_totales($gestion,$mes,$idFinPartida,'$jsonIdRelaborales')";
            else
                $sql = "SELECT * FROM f_relaborales_planillaref_generacion_totales($gestion,$mes,$idFinPartida,NULL)";
            if ($where != '') $sql .= $where;
            if ($group != '') $sql .= $group;
            $this->_db = new Frelaboralesplanillaref();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }


    }

    /**
     * Función para el despliegue de la planilla de refrigerio generada (efectiva) de acuerdo al identificador de planilla enviado como parámetro.
     * @param $idPlanillaRef
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function desplegarPlanillaRefEfectiva($idPlanillaRef, $where = '', $group = '')
    {
        if ($idPlanillaRef > 0) {
            $sql = "SELECT * FROM f_relaborales_planillaref($idPlanillaRef)";
            if ($where != '') $sql .= $where;
            if ($group != '') $sql .= $group;
            $this->_db = new Frelaboralesplanillaref();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para la obtención del número de planilla de refrigerio. Esto se realiza a objeto de posibilitar los procesos masivos de planillas.
     * Es decir, que más de un usuario pueda generar al mismo tiempo planillas de refrigerio, dejando de lado el número de planilla enviado, haciendo valer el correspondiente, en orden de generación.
     * @param $gestion
     * @param $mes
     * @param $idFinPartida
     * @return mixed
     */
    public function obtenerNumeroDePlanillaRef($gestion, $mes, $idFinPartida)
    {
        $sql = "SELECT CASE WHEN MAX(numero) > 0 THEN MAX(numero)+1 ELSE 1 END AS o_resultado FROM planillasref WHERE gestion=" . $gestion . " and mes=" . $mes . " AND finpartida_id=" . $idFinPartida . " AND baja_logica=1";
        $this->_db = new Frelaboralesplanillaref();
        $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        if (count($arr) > 0) return $arr[0]->o_resultado;
        else return 1;
    }
}