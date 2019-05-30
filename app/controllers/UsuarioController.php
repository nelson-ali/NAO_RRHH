<?php

class UsuarioController extends ControllerBase {

    public function initialize() {
        parent::initialize();
    }

    public function profileAction() {
        $id=$this->_user->id;
        $mUsuario=new usuarios();
        $user=$mUsuario->profileUsuario($id);        
        $this->view->setVar('user', $user[0]);        

    }

    public function logoutAction() {
        $this->session->remove('auth');
        $this->flash->success('');
        $this->flash->error('');        
        $this->response->redirect('/login');
    }

}
