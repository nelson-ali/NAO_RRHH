<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  14-10-2014
*/

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Relaborales extends \Phalcon\Mvc\Model
{
    /**
     * Identificador único del registro de la relación laboral.
     * @var integer
     */
    public $id;

    /**
     * Identificador único de la persona al cual refiere la relación laboral.
     * @var integer
     */
    public $persona_id;

    /**
     * Número de ítem correspondiente al contrato.
     * @var integer
     */
    //   public $item;

    /**
     * Número de contrato. el tamaño se amplia en función del registro de contratos previos a 2012.
     * @var string
     */
    public $num_contrato;

    /**
     * Identificador único de la dirección administrativa. para la primera versión sólo se considera una que es la dirección ejecutiva de la cual dependen todas las direcciones y unidades dependientes de la empresa Mi Teleférico.
     * @var integer
     */
    public $da_id;
    /**
     * Identificador único de la regional a la cual corresponde trabajo del funcionario. es el indicativo referencial sobre la región donde el funcionario ejerce sus labores. es admisible que no concuerde con el identificador regional_id de la tabla organigrama por que puede pertenecer a una unidad, pero trabajar en una region diferente.
     * @var integer
     */
    public $regional_id;

    /**
     * Identificador de la unidad administrativa de la cual depende el funcionario.
     * @var integer
     */
    public $organigrama_id;

    /**
     * Identificador de la unidad ejecutora de la cual depende el funcionario. su denominación se hace preveyendo su incorporación y la reestructuración presupuestaria en el sigma.
     * @var integer
     */
    public $ejecutora_id;

    /**
     * Identificador del proceso mediante el cual se registra el contrato.
     * @var integer
     */
    public $procesocontratacion_id;
    /**
     * Identificador del cargo referido al contrato registrado.
     * @var integer
     */
    public $cargo_id;
    /**
     * Identificador único del item dentro de una certificación presupuestaria.
     * @var integer
     */
    public $certificacionitem_id;

    /**
     * Identificador del financiamiento mediante el cual se harán los pagos por la relación laboral.
     * @var integer
     */
    public $finpartida_id;

    /**
     * Identificador de la condición de la ralación laboral ("consultor";"eventual";"permanente").
     * @var integer
     */
    public $condicion_id;
    /**
     * Valor identificatorio de si el funcionario es de carrera. el requisito previo es que también tenga condición permanente debido a un financiamiento con partida presupuestaria 117.
     * @var integer
     */
    public $carrera_adm;

    /**
     * Identificador numérico de la categoría del contrato (1:técnico;2:jurídico;3:administrativo;4:apoyo administrativo).
     * @var integer
     */
    //public $categoria_id;

    /**
     * Identificador del nivel salarial correspondiente a la relación laboral.
     * @var integer
     */
    public $nivelsalarial_id;
    /**
     * Fecha de inicio prevista del contrato.
     * @var timestamp
     */
    public $fecha_ini;
    /**
     * Fecha de incorporación del contrato (fecha de inicio real).
     * @var timestamp
     */
    public $fecha_incor;
    /**
     * Fecha prevista de finalización del contrato.
     * @var timestamp
     */
    public $fecha_fin;

    /**
     * Fecha de baja del contrato (fecha de finalización real).
     * @var timestamp
     */
    public $fecha_baja;
    /**
     * Fecha de presentación de renuncia al contrato por parte del funcionario.
     * @var timestamp
     */
    public $fecha_ren;
    /**
     * Fecha de la aceptación de la renuncia.
     * @var timestamp
     */
    public $fecha_acepta_ren;
    /**
     * Fecha de agradecimiento de servicios.
     * @var timestamp
     */
    public $fecha_agra_serv;
    /**
     * Identificador del motivo de la baja de la relación laboral.
     * @var integer
     */
    public $motivobaja_id;

    /**
     * Descripcion adicional a la baja de la relación laboral.
     * @var string
     */
    public $descripcion_baja;
    /**
     * Descripción adicional de la anulación (baja lógica) de la relación laboral en caso de haberse anulado.
     * @var string
     */
    public $descripcion_anu;
    /**
     * Identificador del registro de solicitud de elaboración de contrato.
     * @var integer
     */
    public $solelabcontrato_id;
    /**
     * Identificador del registro al cual se adscribe el presente contrato para su solicitud de ampliación de vigencia de contrato.
     * @var integer
     */
    public $solampliacioncontrato_id;
    /**
     * Identificador del registro de relación laboral previo.
     * @var integer
     */
    public $relaboral_previo_id;
    /**
     * Observación sobre el registro de la relación laboral.
     * @var string
     */
    public $observacion;
    /**
     * Variable que identifica el haber realizado por lo menos un pago mediante planillas por este contrato.
     * @var integer
     */
    public $pagado;
    /**
     * Estado del contrato (0: pasivo; 1: activo; 2:en proceso)
     * @var integer
     */
    public $estado;
    /**
     * Valor indicativo si un registro ha sido eliminado. (1:no ha sido eliminado;0:ha sido eliminado).
     * @var integer
     */
    public $baja_logica;
    /**
     * Valor que agrupa un conjunto registros de acuerdo a un criterio para los contratos registrados.
     * @var integer
     */
    public $agrupador;
    /**
     * Identificador del usuario que realizó el registro del contrato.
     * @var integer
     */
    public $user_reg_id;
    /**
     * Fecha y hora del registro de la relación laboral.
     * @var timestamp
     */
    public $fecha_reg;
    /**
     * Identificador del usuario que realizó la última modificación sobre el registro de la relación laboral.
     * @var integer
     */
    public $user_mod_id;
    /**
     * Fecha y hora de la última modificación sobre el registro de la relación laboral.
     * @var timestamp
     */
    public $fecha_mod;
    /**
     * Identificador del usuario que realizó la anulación del registro de la relación laboral.
     * @var integer
     */
    public $user_baja_log_id;
    /**
     * Fecha y hora de la anulación del registro de la relación laboral.
     * @var timestamp
     */
    public $fecha_baja_log;


    public function initialize()
    {
        $this->_db = new Relaborales();
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'persona_id' => 'persona_id',
            'num_contrato' => 'num_contrato',
            'da_id' => 'da_id',
            'regional_id' => 'regional_id',
            'organigrama_id' => 'organigrama_id',
            'ejecutora_id' => 'ejecutora_id',
            'procesocontratacion_id' => 'procesocontratacion_id',
            'cargo_id' => 'cargo_id',
            'certificacionitem_id' => 'certificacionitem_id',
            'finpartida_id' => 'finpartida_id',
            'condicion_id' => 'condicion_id',
            'carrera_adm' => 'carrera_adm',
            'categoria_id' => 'categoria_id',
            'nivelsalarial_id' => 'nivelsalarial_id',
            'fecha_ini' => 'fecha_ini',
            'fecha_incor' => 'fecha_incor',
            'fecha_fin' => 'fecha_fin',
            'fecha_baja' => 'fecha_baja',
            'fecha_ren' => 'fecha_ren',
            'fecha_acepta_ren' => 'fecha_acepta_ren',
            'fecha_agra_serv' => 'fecha_agra_serv',
            'motivobaja_id' => 'motivobaja_id',
            'descripcion_baja' => 'descripcion_baja',
            'descripcion_anu' => 'descripcion_anu',
            'solelabcontrato_id' => 'solelabcontrato_id',
            'solampliacioncontrato_id' => 'solampliacioncontrato_id',
            'relaboral_previo_id' => 'relaboral_previo_id',
            'observacion' => 'observacion',
            'pagado' => 'pagado',
            'estado' => 'estado',
            'baja_logica' => 'baja_logica',
            'agrupador' => 'agrupador',
            'user_reg_id' => 'user_reg_id',
            'fecha_reg' => 'fecha_reg',
            'user_mod_id' => 'user_mod_id',
            'fecha_mod' => 'fecha_mod',
            'user_baja_log_id' => 'user_baja_log_id',
            'fecha_baja_log' => 'fecha_baja_log'
        );
    }

    private $_db;

    public function getAll()
    {
        $sql = "SELECT * from f_relaborales()";
        $this->_db = new Relaborales();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para obtener el listado de gestiones para una determinada persona
     * @return array
     */
    public function getCol($id_persona)
    {
        $sql = "select distinct gestion from f_listado_gestiones(" . $id_persona . ") ORDER BY gestion DESC";
        $this->_db = new Gestiones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del listado de gestiones registrados para las relaciones laborales.
     * @return Resultset
     */
    public function getAllGestionesRelaboral()
    {
        //$sql = "SELECT * FROM (SELECT DISTINCT EXTRACT(YEAR FROM fecha_incor) AS gestion FROM relaborales WHERE baja_logica=1 AND EXTRACT(YEAR FROM fecha_incor) IS NOT NULL) AS REG ORDER BY gestion desc;";
        $sql = "SELECT * FROM (SELECT DISTINCT EXTRACT(YEAR FROM fecha_fin) AS gestion FROM relaborales WHERE baja_logica=1 AND EXTRACT(YEAR FROM fecha_fin) IS NOT NULL) AS REG ORDER BY gestion desc;";
        $this->_db = new Gestiones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del identificador del registro de relación laboral ampliado considerando
     * una persona con una fecha de inicio de relación laboral.
     * @param $idPersona
     * @param $fechaIni
     * @return Resultset
     */
    public function getIdRelaboralAmpliado($idPersona, $fechaIni)
    {
        if ($idPersona > 0 && $fechaIni != '') {
            $sql = "SELECT * FROM f_id_relaboral_ampliado(" . $idPersona . ",'" . $fechaIni . "')";
            $this->_db = new Relaborales();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para la obtención de la cantidad de personal con contrato activo en una determinada condición.
     * @param $idCondicion
     * @return mixed
     */
    public function getCantidadPersonalActivoPorCondicion($idCondicion)
    {
        if ($idCondicion > 0) {
            $sql = "SELECT COUNT(*) AS resultado FROM relaborales r ";
            $sql .= "INNER JOIN finpartidas f ON f.id = r.finpartida_id ";
            $sql .= "WHERE r.estado>=1 AND r.baja_logica=1 ";
            $sql .= "AND f.condicion_id = " . $idCondicion;
            $this->_db = new Gestiones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (is_object($arr)) return $arr[0]->resultado;
        }
        return 0;
    }

    /**
     * Función para verificar si a una persona le corresponde aplicarle intermediación.
     * @param $idRelaboral
     * @return int
     */
    public function verificaAplicacionIntermediacion($idRelaboral)
    {
        if ($idRelaboral > 0) {
            $sql = "SELECT f_verifica_aplica_intermediacion AS o_resultado FROM f_verifica_aplica_intermediacion($idRelaboral)";
            $this->_db = new Gestiones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (is_object($arr)) return $arr[0]->o_resultado;
        } else return -1;
    }
}