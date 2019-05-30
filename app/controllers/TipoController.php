<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class TipoController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for tipo
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Tipo", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $tipo = Tipo::find($parameters);
        if (count($tipo) == 0) {
            $this->flash->notice("The search did not find any tipo");

            return $this->dispatcher->forward(array(
                "controller" => "tipo",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $tipo,
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
     * Edits a tipo
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $tipo = Tipo::findFirstByid($id);
            if (!$tipo) {
                $this->flash->error("tipo was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "tipo",
                    "action" => "index"
                ));
            }

            $this->view->id = $tipo->id;

            $this->tag->setDefault("id", $tipo->id);
            $this->tag->setDefault("tipo", $tipo->tipo);
            
        }
    }

    /**
     * Creates a new tipo
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "tipo",
                "action" => "index"
            ));
        }

        $tipo = new Tipo();

        $tipo->id = $this->request->getPost("id");
        $tipo->tipo = $this->request->getPost("tipo");
        

        if (!$tipo->save()) {
            foreach ($tipo->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "tipo",
                "action" => "new"
            ));
        }

        $this->flash->success("tipo was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "tipo",
            "action" => "index"
        ));

    }

    /**
     * Saves a tipo edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "tipo",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $tipo = Tipo::findFirstByid($id);
        if (!$tipo) {
            $this->flash->error("tipo does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "tipo",
                "action" => "index"
            ));
        }

        $tipo->id = $this->request->getPost("id");
        $tipo->tipo = $this->request->getPost("tipo");
        

        if (!$tipo->save()) {

            foreach ($tipo->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "tipo",
                "action" => "edit",
                "params" => array($tipo->id)
            ));
        }

        $this->flash->success("tipo was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "tipo",
            "action" => "index"
        ));

    }

    /**
     * Deletes a tipo
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $tipo = Tipo::findFirstByid($id);
        if (!$tipo) {
            $this->flash->error("tipo was not found");

            return $this->dispatcher->forward(array(
                "controller" => "tipo",
                "action" => "index"
            ));
        }

        if (!$tipo->delete()) {

            foreach ($tipo->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "tipo",
                "action" => "search"
            ));
        }

        $this->flash->success("tipo was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "tipo",
            "action" => "index"
        ));
    }

}
