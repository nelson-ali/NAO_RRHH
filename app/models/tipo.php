<?php

class Tipo extends \Phalcon\Mvc\Model {

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $tipo;

    public function initialize() {
        $this->hasMany('id', 'contrataciones', 'tipo_id');
    }

}
