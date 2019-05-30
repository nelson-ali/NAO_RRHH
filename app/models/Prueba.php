<?php
/**
 * Created by PhpStorm.
 * User: Javialex
 * Date: 26/10/2014
 * Time: 08:38 AM
 */

class Prueba  extends \Phalcon\Mvc\Model{
    public $id;
    public $prueba;
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
            'prueba' => 'prueba'
            );
    }
    private $_db;
    public function saveWithProcedure($prueba)
    {
        $sql = "SELECT * from f_savePrueba(".$prueba.")";
        $this->_db = new Relaborales();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
}