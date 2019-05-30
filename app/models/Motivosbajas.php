<?php
/**
 * Created by PhpStorm.
 * User: JLOZA
 * Date: 27/10/2014
 * Time: 05:36 PM
 */
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Motivosbajas extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $motivo_baja;

    /**
     *
     * @var string
     */
    public $abreviacion;

    /**
     *
     * @var integer
     */
    public $permanente;

    /**
     *
     * @var integer
     */
    public $eventual;

    /**
     *
     * @var integer
     */
    public $consultor;
    /**
     * @var
     */
    public $fecha_ren;
    /**
     * @var
     */
    public $fecha_acepta_ren;
    /**
     * @var
     */
    public $fecha_agra_serv;
    /**
     *
     * @var string
     */
    public $observacion;

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
    public $agrupador;

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
            'motivo_baja' => 'motivo_baja',
            'abreviacion' => 'abreviacion',
            'permanente' => 'permanente',
            'eventual' => 'eventual',
            'consultor' => 'consultor',
            'fecha_ren' => 'fecha_ren',
            'fecha_acepta_ren' => 'fecha_acepta_ren',
            'fecha_agra_serv' => 'fecha_agra_serv',
            'observacion' => 'observacion',
            'estado' => 'estado',
            'baja_logica' => 'baja_logica',
            'agrupador' => 'agrupador'
        );
    }
}