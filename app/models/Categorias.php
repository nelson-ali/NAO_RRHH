<?php
/**
 * Created by PhpStorm.
 * User: JLOZA
 * Date: 24/10/2014
 * Time: 11:18 AM
 */

class Categorias extends \Phalcon\Mvc\Model{

    public $id;
    public $categoria;
    public $observacion;
    public $estado;
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
            'categoria' => 'categoria',
            'observacion' => 'observacion',
            'estado' => 'estado'
        );
    }
}