<?php
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Cargosperfiles extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $nivelsalarial_id;

    /**
     *
     * @var integer
     */
    public $estado;

    /**
     *
     * @var integer
     */
    public $baja_logica;

    /**
     *
     * @var integer
     */
    public $documento_id;

    /**
     *
     * @var integer
     */
    public $exp_general;

    /**
     *
     * @var string
     */
    public $exp_general_aniomes;

    /**
     *
     * @var integer
     */
    public $exp_profesional;

    /**
     *
     * @var string
     */
    public $exp_profesional_aniomes;

    /**
     *
     * @var integer
     */
    public $exp_relacionado;

    /**
     *
     * @var string
     */
    public $exp_relacionado_aniomes;

    /**
     *
     * @var integer
     */
    public $prioridad;

    /**
     *
     * @var integer
     */
    public $formacion_academica_id;

    /**
     *
     * @var string
     */
    public $detalle;

    /**
     *
     * @var string
     */
    public $area_sustantiva;

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
            'nivelsalarial_id' => 'nivelsalarial_id', 
            'estado' => 'estado', 
            'baja_logica' => 'baja_logica', 
            'documento_id' => 'documento_id', 
            'exp_general' => 'exp_general', 
            'exp_general_aniomes' => 'exp_general_aniomes', 
            'exp_profesional' => 'exp_profesional', 
            'exp_profesional_aniomes' => 'exp_profesional_aniomes', 
            'exp_relacionado' => 'exp_relacionado', 
            'exp_relacionado_aniomes' => 'exp_relacionado_aniomes', 
            'prioridad' => 'prioridad', 
            'formacion_academica_id' => 'formacion_academica_id', 
            'detalle' => 'detalle',
            'area_sustantiva' => 'area_sustantiva'
        );
    }

    private $_db;

    public function lista($nivelsalarial_id){
        $sql = "SELECT cp.*,concat(cp.exp_general,' ',cp.exp_general_aniomes) as exp_general_text,
concat(cp.exp_profesional,' ',cp.exp_profesional_aniomes) as exp_profesional_text,concat(cp.exp_relacionado,' ',cp.exp_relacionado_aniomes) as exp_relacionado_text, 
pa.valor_1 as formacion_academica,par.valor_1 as documento, (CASE cp.area_sustantiva WHEN 1 THEN 'AREA SUSTANTIVA' ELSE 'AREA NO SUSTANTIVA' END) as area_sustantiva_text
FROM cargosperfiles  cp
INNER JOIN parametros pa ON cp.formacion_academica_id = pa.id
LEFT JOIN parametros par ON cp.documento_id= par.id
WHERE cp.nivelsalarial_id='$nivelsalarial_id' AND cp.baja_logica=1 ORDER BY cp.prioridad ASC";
        $this->_db = new Nivelsalariales();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
}
