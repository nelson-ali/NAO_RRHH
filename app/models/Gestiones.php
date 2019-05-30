<?php
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Gestiones  extends \Phalcon\Mvc\Model {
    /**
     * Valor numérico de la gestión.
     * @var $gestion
     */
    public $gestion;
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
            'gestion' => 'gestion'
        );
    }
} 