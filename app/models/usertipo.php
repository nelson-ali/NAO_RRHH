<?php

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Usertipo extends \Phalcon\Mvc\Model {

    public function initialize() {
        $this->_db = new usertipo();
        //   parent::initialize();
    }

    public function tipos($id) {
        $sql = "SELECT t.id,t.tipo,t.plural FROM usertipo u INNER JOIN tipos t ON u.tipo_id=t.id
                WHERE u.user_id='$id'";
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

}
