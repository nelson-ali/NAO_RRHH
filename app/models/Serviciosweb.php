<?php
/**
 *   Oasis - Sistema de Gesti�n para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Telef�rico"
 *   Versi�n:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creaci�n:  10-06-2016
 */
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Serviciosweb extends \Phalcon\Mvc\Model
{
    private $_db;
    private $algorithm = 'sha256';
    private $secretKey = '1, 0, 0, 6, 1, 20, 16, 16, 54, 10';

    public function initialize()
    {
        $this->_db = new Usuariosservicios();
    }

    /**
     * Funci�n para la validaci�n de la clave env�ada.
     * @param string $clave
     * @return bool
     */
    public function validacion($clave = '')
    {
        $ok = true;
        if ($clave == '') return false;
        else {
            $us = Usuariosservicios::findFirst(array('password =:password: AND estado=:estado: AND baja_logica=:baja_logica:', 'bind' => array('password' => $clave, 'estado' => 1, 'baja_logica' => 1)));
            if (!is_object($us)) return false;
        }
        return $ok;
    }
    public function getOneByKey($clave = '')
    {
        $ok = false;
        if ($clave == '') return false;
        else {
            $us = Usuariosservicios::findFirst(array('password =:password: AND estado=:estado: AND baja_logica=:baja_logica:', 'bind' => array('password' => $clave, 'estado' => 1, 'baja_logica' => 1)));
            if (is_object($us)) return $us;
        }
        return $ok;
    }
}