<?php
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Menus extends Phalcon\Mvc\Model {
    //put your code here
    private $_db;
        
     public function initialize() {
        $this->_db = new menus();
     //   parent::initialize();
    }
    //lista de menus
    public function listaNivel($nivel){
        /*$sql="SELECT m.id, m.menu, m.descripcion, m.controlador,s.id as id_submenu  ,s.submenu,s.accion,s.descripcion,m.icon
                FROM menus m
                INNER JOIN nivelmenu AS n ON (m.id=n.id_menu )
                INNER JOIN submenus AS s ON (m.id=s.id_menu)
                WHERE n.id_nivel='$nivel'
                AND s.habilitado='1'
                ORDER BY m.index";*/
        $sql="SELECT m.id, m.menu, m.descripcion, m.controlador,s.id as id_submenu  ,s.submenu,s.accion,s.descripcion,m.icon
              FROM menus m
              INNER JOIN nivelmenu AS n ON (m.id=n.id_menu ) AND m.habilitado=1
              LEFT JOIN submenus AS s ON (m.id=s.id_menu) AND s.habilitado=1
              WHERE n.id_nivel='$nivel'
              ORDER BY m.index";
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
}
