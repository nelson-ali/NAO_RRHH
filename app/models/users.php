<?php

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Users extends \Phalcon\Mvc\Model {

    public function initialize() {
        //  $this->setConnectionService('sigec');
        $this->setConnectionService('db');
    }

    public function lista() {
        $this->setConnectionService('db');
        $sql = "SELECT * FROM users";
        $users = new Users();
        return new Resultset(null, $users, $users->getReadConnection()->query($sql));
    }

}
