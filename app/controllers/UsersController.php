<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class UsersController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for users
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Users", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $users = Users::find($parameters);
        if (count($users) == 0) {
            $this->flash->notice("The search did not find any users");

            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $users,
            "limit"=> 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a user
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $user = Users::findFirstByid($id);
            if (!$user) {
                $this->flash->error("user was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "users",
                    "action" => "index"
                ));
            }

            $this->view->id = $user->id;

            $this->tag->setDefault("id", $user->id);
            $this->tag->setDefault("superior", $user->superior);
            $this->tag->setDefault("id_oficina", $user->id_oficina);
            $this->tag->setDefault("dependencia", $user->dependencia);
            $this->tag->setDefault("username", $user->username);
            $this->tag->setDefault("password", $user->password);
            $this->tag->setDefault("nombre", $user->nombre);
            $this->tag->setDefault("last_login", $user->last_login);
            $this->tag->setDefault("mosca", $user->mosca);
            $this->tag->setDefault("cargo", $user->cargo);
            $this->tag->setDefault("email", $user->email);
            $this->tag->setDefault("logins", $user->logins);
            $this->tag->setDefault("fecha_creacion", $user->fecha_creacion);
            $this->tag->setDefault("habilitado", $user->habilitado);
            $this->tag->setDefault("nivel", $user->nivel);
            $this->tag->setDefault("genero", $user->genero);
            $this->tag->setDefault("prioridad", $user->prioridad);
            $this->tag->setDefault("id_entidad", $user->id_entidad);
            $this->tag->setDefault("super", $user->super);
            $this->tag->setDefault("cedula_identidad", $user->cedula_identidad);
            $this->tag->setDefault("expedido", $user->expedido);
            $this->tag->setDefault("theme", $user->theme);
            $this->tag->setDefault("cite_despacho", $user->cite_despacho);
            
        }
    }

    /**
     * Creates a new user
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "index"
            ));
        }

        $user = new Users();

        $user->id = $this->request->getPost("id");
        $user->superior = $this->request->getPost("superior");
        $user->id_oficina = $this->request->getPost("id_oficina");
        $user->dependencia = $this->request->getPost("dependencia");
        $user->username = $this->request->getPost("username");
        $user->password = $this->request->getPost("password");
        $user->nombre = $this->request->getPost("nombre");
        $user->last_login = $this->request->getPost("last_login");
        $user->mosca = $this->request->getPost("mosca");
        $user->cargo = $this->request->getPost("cargo");
        $user->email = $this->request->getPost("email", "email");
        $user->logins = $this->request->getPost("logins");
        $user->fecha_creacion = $this->request->getPost("fecha_creacion");
        $user->habilitado = $this->request->getPost("habilitado");
        $user->nivel = $this->request->getPost("nivel");
        $user->genero = $this->request->getPost("genero");
        $user->prioridad = $this->request->getPost("prioridad");
        $user->id_entidad = $this->request->getPost("id_entidad");
        $user->super = $this->request->getPost("super");
        $user->cedula_identidad = $this->request->getPost("cedula_identidad");
        $user->expedido = $this->request->getPost("expedido");
        $user->theme = $this->request->getPost("theme");
        $user->cite_despacho = $this->request->getPost("cite_despacho");
        

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "new"
            ));
        }

        $this->flash->success("user was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "users",
            "action" => "index"
        ));
    }

    /**
     * Saves a user edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $user = Users::findFirstByid($id);
        if (!$user) {
            $this->flash->error("user does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "index"
            ));
        }

        $user->id = $this->request->getPost("id");
        $user->superior = $this->request->getPost("superior");
        $user->id_oficina = $this->request->getPost("id_oficina");
        $user->dependencia = $this->request->getPost("dependencia");
        $user->username = $this->request->getPost("username");
        $user->password = $this->request->getPost("password");
        $user->nombre = $this->request->getPost("nombre");
        $user->last_login = $this->request->getPost("last_login");
        $user->mosca = $this->request->getPost("mosca");
        $user->cargo = $this->request->getPost("cargo");
        $user->email = $this->request->getPost("email", "email");
        $user->logins = $this->request->getPost("logins");
        $user->fecha_creacion = $this->request->getPost("fecha_creacion");
        $user->habilitado = $this->request->getPost("habilitado");
        $user->nivel = $this->request->getPost("nivel");
        $user->genero = $this->request->getPost("genero");
        $user->prioridad = $this->request->getPost("prioridad");
        $user->id_entidad = $this->request->getPost("id_entidad");
        $user->super = $this->request->getPost("super");
        $user->cedula_identidad = $this->request->getPost("cedula_identidad");
        $user->expedido = $this->request->getPost("expedido");
        $user->theme = $this->request->getPost("theme");
        $user->cite_despacho = $this->request->getPost("cite_despacho");
        

        if (!$user->save()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "edit",
                "params" => array($user->id)
            ));
        }

        $this->flash->success("user was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "users",
            "action" => "index"
        ));

    }

    /**
     * Deletes a user
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $user = Users::findFirstByid($id);
        if (!$user) {
            $this->flash->error("user was not found");

            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "index"
            ));
        }

        if (!$user->delete()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "search"
            ));
        }

        $this->flash->success("user was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "users",
            "action" => "index"
        ));
    }

}
