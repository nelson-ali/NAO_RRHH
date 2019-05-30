<?php

/**
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  08-07-2016
 */
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Publicacionesemoticones extends \Phalcon\Mvc\Model
{
    private $_db;

    public function initialize()
    {
        $this->_db = new Publicacionesemoticones();
    }
}