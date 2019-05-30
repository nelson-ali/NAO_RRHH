<?php

class UserController extends \Phalcon\Mvc\Controller {

    //put your code here

    public function loginAction() {
        $auth = $this->session->get('auth');
        if ($auth) {
            $this->response->redirect('/');
        }
        $this->view->setMainView('login');
        $this->view->setLayout('login');

        if ($this->request->isPost()) {
            $usuario = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $password = hash_hmac('sha256', $password, '2, 4, 6, 7, 9, 15, 20, 23, 25, 30'); //sigec users
            //$password = sha1($password);                        
            $user = usuarios::findFirst(
                            array(
                                "username = :usuario: AND password = :password: AND habilitado= :estado:",
                                "bind" => array('usuario' => $usuario, 'password' => $password, 'estado' => 1)
            ));
            if ($user != false) {
                //acutalizamos la cantidad de ingresos
                $user->logins = $user->logins + 1;
                $user->lastlogin = time();
                $user->save();
                $this->_registerSession($user);
                $this->flashSession->success('Bienvenido <i>' . $user->nombre . '</i>');
                $this->response->redirect('/dashboard');
            }
            $this->flashSession->error('<b>Acceso denegado :</b> Usuario/contraseña incorrectos, o usuario No habilitado');
        }
        // return $this->forward('session/index');                
    }

    private function _registerSession($user) {
        $this->session->set('auth', array(
            'id' => $user->id,
            'name' => $user->username,
            'nombre' => $user->nombre,
            'cargo' => $user->cargo,
            'nivel' => $user->nivel,
        ));
    }
    public function logoutAction() {
        $this->session->remove('auth');
        $this->flash->success('Goodbye!');
        $this->response->redirect('/user/login');
    }

    public function listaAction() {
        $mUser = new usuarios();
        $usuarios = $mUser->lista();
        //$usuarios->findFirst();
        //$usuarios->setConnectionService('db');
        //$usuarios=  Users::find();
        $this->view->setVar('usuarios', $usuarios);
    }

}
