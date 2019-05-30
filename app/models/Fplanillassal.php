<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  02-04-2015
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Fplanillassal extends \Phalcon\Mvc\Model {
    public $id;
    public $da_id;
    public $ejecutora_id;
    public $unidad_ejecutora;
    public $regional_id;
    public $regional;
    public $gestion;
    public $mes;
    public $mes_nombre;
    public $finpartida_id;
    public $fin_partida;
    public $condicion_id;
    public $condicion;
    public $tipoplanilla_id;
    public $tipo_planilla;
    public $numero;
    public $total_descuentos;
    public $total_ganado;
    public $total_liquido;
    public $cantidad_relaborales;
    public $observacion;
    public $motivo_anu;
    public $estado;
    public $estado_descripcion;
    public $baja_logica;
    public $agrupador;
    public $user_reg_id;
    public $fecha_reg;
    public $user_mod_id;
    public $fecha_mod;
    public $user_ver_id;
    public $fecha_ver;
    public $user_apr_id;
    public $fecha_apr;
    public $user_rev_id;
    public $fecha_rev;
    public $user_anu_id;
    public $fecha_anu;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->_db = new Fplanillassal();
    }
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id'=>'id',
            'da_id'=>'da_id',
            'ejecutora_id'=>'ejecutora_id',
            'unidad_ejecutora'=>'unidad_ejecutora',
            'regional_id'=>'regional_id',
            'regional'=>'regional',
            'gestion'=>'gestion',
            'mes'=>'mes',
            'mes_nombre'=>'mes_nombre',
            'finpartida_id'=>'finpartida_id',
            'fin_partida'=>'fin_partida',
            'condicion_id'=>'condicion_id',
            'condicion'=>'condicion',
            'tipoplanilla_id'=>'tipoplanilla_id',
            'tipo_planilla'=>'tipo_planilla',
            'numero'=>'numero',
            'total_ganado'=>'total_ganado',
            'total_liquido'=>'total_liquido',
            'cantidad_relaborales'=>'cantidad_relaborales',
            'observacion'=>'observacion',
            'motivo_anu'=>'motivo_anu',
            'estado'=>'estado',
            'estado_descripcion'=>'estado_descripcion',
            'baja_logica'=>'baja_logica',
            'agrupador'=>'agrupador',
            'user_reg_id'=>'user_reg_id',
            'fecha_reg'=>'fecha_reg',
            'user_mod_id'=>'user_mod_id',
            'fecha_mod'=>'fecha_mod',
            'user_ver_id'=>'user_ver_id',
            'fecha_ver'=>'fecha_ver',
            'user_apr_id'=>'user_apr_id',
            'fecha_apr'=>'fecha_apr',
            'user_rev_id'=>'user_rev_id',
            'fecha_rev'=>'fecha_rev',
            'user_anu_id'=>'user_anu_id',
            'fecha_anu'=>'fecha_anu'
        );
    }
    private $_db;

    /**
     * Función para la obtención del listado de planillas generadas a un momento en particular.
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function getAll($where='',$group=''){
        $sql = "SELECT * FROM f_planillassal()";
        if($where!='')$sql .= $where;
        if($group!='')$sql .= $group;
        $this->_db = new Fplanillassal();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del registro correspondiente a una planilla salarial.
     * @param $idPlanillaSal
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function getOne($idPlanillaSal,$where='',$group=''){
        if($idPlanillaSal>0){
            $sql = "SELECT * FROM f_planillassal() WHERE id=".$idPlanillaSal;
            if($where!='')$sql .= $where;
            if($group!='')$sql .= $group;
            $this->_db = new Fplanillassal();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para la obtención del listado de gestiones disponibles para la generación de planillas salariales.
     * @param null $fecha
     * @return Resultset
     */
    public function getGestionesGeneracionPlanillas($fecha=NULL){
        if($fecha!=''&&$fecha!=NULL)
            $sql = "SELECT f_listado_gestiones_generacion_planillasssal AS o_gestiones FROM f_listado_gestiones_generacion_planillasssal('".$fecha."')";
        else $sql = "SELECT f_listado_gestiones_generacion_planillasssal AS o_gestiones FROM f_listado_gestiones_generacion_planillasssal(NULL)";
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del listado de meses disponibles para la generación de planillas salariales.
     * @param $gestion
     * @return Resultset
     */
    public function getMesesGeneracionPlanillas($gestion){
        if($gestion>0){
            $sql = "SELECT * FROM f_listado_meses_generacion_planillasssal($gestion)";
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }
    /**
     * Función para la obtención del listado de financiamientos por partida (Fuentes de Financimiento) disponibles para la generación de planillas salariales.
     * Se considera una gestión y mes en particular.
     * @param $gestion
     * @return Resultset
     */
    public function getFinPartidasGeneracionPlanillas($gestion,$mes){
        if($gestion>0&&$mes>0){
            $sql = "SELECT * FROM f_listado_finpartidas_generacion_planillasssal($gestion,$mes)";
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para la obtención del listado de tipos de planillas para la generación de planillas salariales.
     * @param $gestion
     * @param $mes
     * @param $idFinPartida
     * @return Resultset
     */
    public function getTiposPlanillasGeneracionPlanillas($gestion,$mes,$idFinPartida){
        if($gestion>0&&$mes>0&&$idFinPartida>0){
            $sql = "SELECT * FROM f_listado_tipos_planillas_diponibles_generacion_planillassal($gestion,$mes,$idFinPartida)";
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }
    /**
     * Función para el registro de acumulación de sanciones por gestión, mes y registro de relación laboral, considerando
     * las fechas de corte establecidas para planillas salariales.
     * @param $idRelaboral
     * @param $gestion
     * @param $mes
     * @param $atrasos
     * @param $faltas
     * @param $abandono
     * @param $omision
     * @param $lsgh
     * @param $otros
     * @param $observacion
     * @param $idUsuario
     * @return Resultset
     */
    public function registraAcumulacionSancion($idRelaboral,$gestion,$mes,$atrasos=0,$faltas=0,$abandono=0,$omision=0,$lsgh=0,$otros=0,$observacion,$idUsuario)
    {   if($idRelaboral>0&&$gestion>0&&$mes>0&&$idUsuario>0) {
            $sql = "SELECT f_registra_acumulacionsancion AS resultado FROM f_registra_acumulacionsancion($idRelaboral,$gestion,$mes,$atrasos,$faltas,$abandono,$omision,$lsgh,$otros,'$observacion',$idUsuario)";
            $this->_db = new Fplanillassal();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $arr[0]->resultado;
        }
    }
}