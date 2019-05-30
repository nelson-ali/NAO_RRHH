<?php
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Parametros extends \Phalcon\Mvc\Model
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
    public $parametro;

    /**
     *
     * @var string
     */
    public $nivel;

    /**
     *
     * @var string
     */
    public $valor_1;

    /**
     *
     * @var string
     */
    public $valor_2;

    /**
     *
     * @var string
     */
    public $valor_3;

    /**
     *
     * @var string
     */
    public $valor_4;

    /**
     *
     * @var string
     */
    public $valor_5;

    /**
     *
     * @var string
     */
    public $descripcion;

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
            'parametro' => 'parametro',
            'nivel' => 'nivel',
            'valor_1' => 'valor_1',
            'valor_2' => 'valor_2',
            'valor_3' => 'valor_3',
            'valor_4' => 'valor_4',
            'valor_5' => 'valor_5',
            'descripcion' => 'descripcion',
            'observacion' => 'observacion',
            'estado' => 'estado',
            'baja_logica' => 'baja_logica',
            'agrupador' => 'agrupador'
        );
    }

    /**
     * Función para la obtención del plazo máximo para poder imprimir boletas y aprobarse físicamente.
     * @return int
     */
    public function getPlazoMaximoAprobacionBoletasFisicas()
    {
        $sql = "SELECT nivel FROM parametros WHERE parametro LIKE 'PLAZO_MAXIMO_APROBACION_BOLETAS_FISICA' AND estado=1 AND baja_logica=1 LIMIT 1";
        $this->_db = new Parametros();
        $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        if(count($arr)>0) return $arr[0]->nivel;
        else return 0;
    }
}
