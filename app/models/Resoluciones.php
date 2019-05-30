<?php
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Resoluciones extends \Phalcon\Mvc\Model
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
    public $tipo_resolucion;

    /**
     *
     * @var integer
     */
    public $numero_res;

    /**
     *
     * @var integer
     */
    public $institucion_sector_id;

    /**
     *
     * @var integer
     */
    public $institucion_rectora_id;

    /**
     *
     * @var string
     */
    public $instituciones_otras;

    /**
     *
     * @var integer
     */
    public $gestion_res;

    /**
     *
     * @var string
     */
    public $fecha_emi;

    /**
     *
     * @var string
     */
    public $fecha_apr;

    /**
     *
     * @var string
     */
    public $fecha_fin;

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
    public $activo;

    /**
     *
     * @var integer
     */
    public $uso;

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
            'tipo_resolucion' => 'tipo_resolucion',
            'numero_res' => 'numero_res',
            'institucion_sector_id' => 'institucion_sector_id',
            'institucion_rectora_id' => 'institucion_rectora_id',
            'instituciones_otras' => 'instituciones_otras',
            'gestion_res' => 'gestion_res',
            'fecha_emi' => 'fecha_emi',
            'fecha_apr' => 'fecha_apr',
            'fecha_fin' => 'fecha_fin',
            'estado' => 'estado',
            'baja_logica' => 'baja_logica',
            'activo' => 'activo',
            'uso' => 'uso'
        );
    }

    private $_db;
    public function desactivar($uso) {
        $sql = "UPDATE resoluciones SET activo = 0 ";
        switch ($uso){
            case 1:
            case 2:$sql .= "WHERE uso = $uso or uso = 3";break;
            case 3:$sql .= "WHERE uso in (1,2,3)";break;
            default:$sql .= "WHERE uso = $uso";break;
        }
        $this->_db = new Resoluciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
}
