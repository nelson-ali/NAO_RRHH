<?php
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Componentes extends \Phalcon\Mvc\Model
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
    public $fk_normativa;

    /**
     *
     * @var string
     */
    public $componente;

    /**
     *
     * @var string
     */
    public $descripcion;

    /**
     *
     * @var string
     */
    public $activo;

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
            'fk_normativa' => 'fk_normativa', 
            'componente' => 'componente', 
            'descripcion' => 'descripcion', 
            'activo' => 'activo'
        );
    }

    private $_db;

    
    public function lista() {
        $sql = "SELECT c.*,n.normativa FROM componentes c, normativas n WHERE c.activo=TRUE  AND c.fk_normativa=n.id ORDER BY c.id asc";
        $this->_db = new Componentes();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

}
