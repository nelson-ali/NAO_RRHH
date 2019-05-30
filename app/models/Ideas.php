<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  26-10-2015
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Ideas extends \Phalcon\Mvc\Model  {
    private $_db;

    public function initialize() {
        $this->_db = new Ideas();
    }
} 