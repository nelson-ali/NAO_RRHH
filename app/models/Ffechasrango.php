<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  07-04-2015
*/

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Ffechasrango extends \Phalcon\Mvc\Model {
    public $fecha;
    public $dia;
    public $dia_nombre;
    public $dia_nombre_abr_ing;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("");
    }
    /**
     * Mapeo de columnas.
     */
    public function columnMap()
    {
        return array(
            'fecha'=>'fecha',
            'dia'=>'dia',
            'dia_nombre'=>'dia_nombre',
            'dia_nombre_abr_ing'=>'dia_nombre_abr_ing'
        );
    }
    private $_db;
    /**
     * Función para la obtención del listado de fechas de acuerdo a un rango establecido.
     * @param string $fechaIni
     * @param string $fechaFin
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function getAll($fechaIni,$fechaFin,$where='',$group='')
    {
        $sql = "SELECT * FROM f_listado_fechas_rango('$fechaIni','$fechaFin')";
        if($where!='')$sql .= $where;
        if($group!='')$sql .= $group;
        $this->_db = new Ffechasrango();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
}
