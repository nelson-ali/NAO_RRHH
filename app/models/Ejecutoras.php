<?php
//use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Ejecutoras extends \Phalcon\Mvc\Model
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
    public $gestion;

    /**
     *
     * @var integer
     */
    public $organigrama_id;

    /**
     *
     * @var string
     */
    public $unidad_ejecutora;

    /**
     *
     * @var integer
     */
    public $cod_ue;

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
            'gestion' => 'gestion', 
            'organigrama_id' => 'organigrama_id', 
            'unidad_ejecutora' => 'unidad_ejecutora', 
            'cod_ue' => 'cod_ue', 
            'estado' => 'estado', 
            'baja_logica' => 'baja_logica',
            'agrupador' => 'agrupador'
        );
    }

    
    
}
