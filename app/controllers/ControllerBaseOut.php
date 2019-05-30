<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\Controller;

class ControllerBaseOut extends Controller
{

    public function beforeExecuteRoute()
    {
        return true;
    }

    protected function initialize()
    {
        $this->tag->setTitle('VB - SRRHH');
    }

    /**
     * Función para sumar dias a una determinada fecha.
     * @param $fecha
     * @param $dia
     * @param string $sep
     * @return false|string
     */
    public function sumarDiasFecha($fecha, $dia, $sep = "-")
    {
        list($day, $mon, $year) = explode($sep, $fecha);
        return date('d' . $sep . 'm' . $sep . 'Y', mktime(0, 0, 0, $mon, $day + $dia, $year));
    }
}
