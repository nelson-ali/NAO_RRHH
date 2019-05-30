<?php

class Pcarreracargos extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $cargo_id;

    /**
     *
     * @var integer
     */
    public $pcarrera_id;


    public function columnMap()
    {
        return array(
            'cargo_id' => 'cargo_id',
            'pcarrera_id' => 'pcarrera_id',
        );
    }

}
