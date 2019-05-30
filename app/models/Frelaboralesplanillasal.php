<?php
/**
 * Created by PhpStorm.
 * User: GGE-JLOZA
 * Date: 27/05/2015
 * Time: 04:33 PM
 */
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Frelaboralesplanillasal  extends \Phalcon\Mvc\Model {
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
    public $bonos;
    public $faltas;
    public $faltas_rip;
    public $atrasos;
    public $atrasos_rip;
    public $faltas_atrasos;
    public $lsgh;
    public $abandono;
    public $abandono_rip;
    public $omision;
    public $omision_rip;
    public $otros;
    public $total_descuentos_sancionados;
    public $rc_iva;
    public $rentencion;
    public $compensacion;
    public $aporte_laboral_afp;
    public $total_descuentos;
    public $total_ganado;
    public $total_liquido;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->_db = new Frelaboralesplanillasal();
    }
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
    'id_relaboral'=>'id_relaboral',
        'gestion'=>'gestion',
        'mes'=>'mes',
        'mes_nombre'=>'mes_nombre',
        'nombres'=>'nombres',
        'ci'=>'ci',
        'expd'=>'expd',
        'id_gerencia_administrativa'=>'id_gerencia_administrativa',
        'gerencia_administrativa'=>'gerencia_administrativa',
        'id_departamento_administrativo'=>'id_departamento_administrativo',
        'departamento_administrativo'=>'departamento_administrativo',
        'id_organigrama'=>'id_organigrama',
        'unidad_administrativa'=>'unidad_administrativa',
        'organigrama_sigla'=>'organigrama_sigla',
        'organigrama_orden'=>'organigrama_orden',
        'id_area'=>'id_area',
        'area'=>'area',
        'id_ubicacion'=>'id_ubicacion',
        'ubicacion'=>'ubicacion',
        'id_nivelsalarial'=>'id_nivelsalarial',
        'nivel_salarial'=>'nivel_salarial',
        'cargo'=>'cargo',
        'sueldo'=>'sueldo',
        'convocatoria_tipo'=>'convocatoria_tipo',
        'id_procesocontratacion'=>'id_procesocontratacion',
        'procesocontratacion_codigo'=>'procesocontratacion_codigo',
        'id_finpartida'=>'id_finpartida',
        'fin_partida'=>'fin_partida',
        'id_condicion'=>'id_condicion',
        'condicion'=>'condicion',
        'tiene_item'=>'tiene_item',
        'tiene_contrato_vigente'=>'tiene_contrato_vigente',
        'fecha_ini'=>'fecha_ini',
        'fecha_incor'=>'fecha_incor',
        'fecha_fin'=>'fecha_fin',
        'fecha_baja'=>'fecha_baja',
        'estado'=>'estado',
        'estado_descripcion'=>'estado_descripcion',
        'dias_efectivos'=>'dias_efectivos',
        'bonos'=>'bonos',
        'faltas'=>'faltas',
        'faltas_rip'=>'faltas_rip',
        'atrasos'=>'atrasos',
        'atrasos_rip'=>'atrasos_rip',
        'faltas_atrasos'=>'faltas_atrasos',
        'lsgh'=>'lsgh',
        'abandono'=>'abandono',
        'abandono_rip'=>'abandono_rip',
        'omision'=>'omision',
        'omision_rip'=>'omision_rip',
        'otros'=>'otros',
        'total_descuentos_sancionados'=>'total_descuentos_sancionados',
        'rc_iva'=>'rc_iva',
        'rentencion'=>'rentencion',
        'compensacion'=>'compensacion',
        'aporte_laboral_afp'=>'aporte_laboral_afp',
        'total_descuentos'=>'total_descuentos',
        'total_ganado'=>'total_ganado',
        'total_liquido'=>'total_liquido'
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
    public function desplegarPlanillaPrevia($gestion,$mes,$idFinPartida,$jsonIdRelaborales,$where='',$group=''){
        if($gestion>0&&$mes>0&&$idFinPartida>0){
            if($jsonIdRelaborales!='')
                $sql = "SELECT * FROM f_relaborales_planillasal_generacion_totales($gestion,$mes,$idFinPartida,'$jsonIdRelaborales')";
            else
                $sql = "SELECT * FROM f_relaborales_planillasal_generacion($gestion,$mes,$idFinPartida,NULL)";
            if($where!='')$sql .= $where;
            if($group!='')$sql .= $group;
            $this->_db = new Frelaboralesplanillasal();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }
    /**
     * Función para el despliegue de la planilla salarial generada (efectiva) de acuerdo al identificador de planilla enviado como parámetro.
     * @param $idPlanillaSal
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function desplegarPlanillaSalEfectiva($idPlanillaSal,$where='',$group=''){
        if($idPlanillaSal>0){
            $sql = "SELECT * FROM f_relaborales_planillasal($idPlanillaSal)";
            if($where!='')$sql .= $where;
            if($group!='')$sql .= $group;
            $this->_db = new Frelaboralesplanillasal();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }
}