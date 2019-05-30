<?php

use Phalcon\Mvc\Controller;
use Phalcon\Events\Event;

class ControllerBaseFreddy extends Controller {

    protected $_user;
    protected $_con;

    public function beforeExecuteRoute() {
        //Check whether the "auth" variable exists in session to define the active role
        $auth = $this->session->get('auth');
        if (!$auth) {
            header('Location: /login');
        } 
        return true;
    }

    protected function initialize() {
        $auth = $this->session->get('auth');
        if (!isset($auth['id'])) {
            $this->response->redirect('/login');
            //parent::initialize();
        } else {

            //obtenemos la instancia del usuario
            $user_id = $auth['id'];
            $this->_user = usuarios::findFirst("id = '$user_id'");
            //Prepend the application name to the title
            $this->tag->setTitle('Sistema de RRHH');
            $this->assets
                    ->addCss('/assets/css/bootstrap.min.css')
                    ->addCss('/assets/css/plugins.css')
                    ->addCss('/assets/css/main.css')
                    ->addCss('/assets/css/themes.css')
                    // //CSS ingresados por freddy
                   ->addCss('/js/datepicker/datepicker.css')
                    ->addCss('/js/datatables/dataTables.bootstrap.css')
                    ->addCss('/js/jqwidgets/styles/jqx.base.css')

                    ->addCss('/js/jqwidgets/styles/jqx.blackberry.css')
                    ->addCss('/js/jqwidgets/styles/jqx.windowsphone.css')
                    ->addCss('/js/jqwidgets/styles/jqx.blackberry.css')
                    ->addCss('/js/jqwidgets/styles/jqx.mobile.css')
                    ->addCss('/js/jqwidgets/styles/jqx.android.css')

                    /*Adicionado por Javier*/
                    // ->addCss('/assets/css/jquery-ui.css')

            ;
            $this->assets
                    ->addJs('/js/jqwidgets/scripts/jquery-1.11.1.min.js')
                    //->addJs('/js/bootstrap.min.js')
                    ->addJs('/js/wizard/jquery-latest.js')
                    ->addJs('/js/wizard/jquery.bootstrap.wizard.min.js')
                    ->addJs('/js/wizard/prettify.js')
                    ->addJs('/assets/js/vendor/modernizr-2.7.1-respond-1.4.2.min.js')
                    ->addJs('/assets/js/vendor/bootstrap.min.js')
                    ->addJs('/assets//js/plugins.js')
                    ->addJs('/assets/js/app.js')
                    //  ->addJs('/assets/js/helpers/gmaps.min.js')
                    ->addJs('/js/app.plugin.js')
                    //  ->addJs('/js/jquery-ui-1.9.0.custom.min.js')
                    //->addJs('/js/bootstrap.min.js')
                    //JS ingresado por Freddy
                    
                    // ->addJs('/js/jqwidgets/simulator.js')
                    // ->addJs('/js/jqwidgets/jqxcore.js')
                    // ->addJs('/js/jqwidgets/jqxdata.js')
                    // ->addJs('/js/jqwidgets/jqxbuttons.js')
                    // ->addJs('/js/jqwidgets/jqxscrollbar.js')
                    // ->addJs('/js/jqwidgets/jqxdatatable.js')
                    // ->addJs('/js/jqwidgets/jqxlistbox.js')
                    // ->addJs('/js/jqwidgets/jqxdropdownlist.js')
                    // ->addJs('/js/jqwidgets/jqxpanel.js')
                    // ->addJs('/js/jqwidgets/jqxradiobutton.js')
                    // ->addJs('/js/jqwidgets/jqxinput.js')
                    // ->addJs('/js/datepicker/bootstrap-datepicker.js')
                    // ->addJs('/js/datatables/dataTables.bootstrap.js')

                    // ->addJs('/js/jqwidgets/jqxmenu.js')
                    // ->addJs('/js/jqwidgets/jqxgrid.js')
                    // ->addJs('/js/jqwidgets/jqxgrid.filter.js')
                    // ->addJs('/js/jqwidgets/jqxgrid.sort.js')
                    // ->addJs('/js/jqwidgets/jqxtabs.js')
                    // ->addJs('/js/jqwidgets/jqxgrid.selection.js')
                    // ->addJs('/js/jqwidgets/jqxcalendar.js')
                    // ->addJs('/js/jqwidgets/jqxdatetimeinput.js')
                    // ->addJs('/js/jqwidgets/jqxcheckbox.js')
                    // ->addJs('/js/jqwidgets/jqxgrid.grouping.js')
                    // ->addJs('/js/jqwidgets/jqxgrid.pager.js')
                    // ->addJs('/js/jqwidgets/jqxnumberinput.js')
                    // ->addJs('/js/jqwidgets/jqxwindow.js')
                    // ->addJs('/js/jqwidgets/globalization/globalize.js')
                    // ->addJs('/js/jqwidgets/jqxcombobox.js')
                    // ->addJs('/js/jqwidgets/jqxexpander.js')
                    // ->addJs('/js/jqwidgets/globalization/globalize.js')
                    // ->addJs('/js/jqwidgets/jqxvalidator.js')
                    // ->addJs('/js/jqwidgets/jqxmaskedinput.js')
                    // ->addJs('/js/jqwidgets/jqxchart.js')
                    // ->addJs('/js/jqwidgets/jqxgrid.columnsresize.js')
                    // ->addJs('/js/jqwidgets/jqxsplitter.js')
                    // ->addJs('/js/jqwidgets/jqxtree.js')
                    // ->addJs('/js/jqwidgets/jqxdata.export.js')
                    // ->addJs('/js/jqwidgets/jqxgrid.export.js')
                    // ->addJs('/js/jqwidgets/jqxgrid.edit.js')
                    // ->addJs('/js/jqwidgets/jqxnotification.js')
                    // ->addJs('/js/jqwidgets/jqxbuttongroup.js')
                    // ->addJs('/js/bootbox.js')

            ;
            //menu
            $this->menu($this->_user->nivel);
            $this->view->setVar('user', $this->_user);
        }
    }

    protected function usuario() {
        $auth = $this->session->get('cite');
        if ($auth) {
            $user_id = $auth['id'];
            $this->_user = usuarios::findFirst("id = '$user_id'");
            return $this->_user;
        } else {
            return false;
        }
    }

    protected function forward($uri) {
        $uriParts = explode('/', $uri);
        return $this->dispatcher->forward(
                        array(
                            'controller' => $uriParts[0],
                            'action' => $uriParts[1]
                        )
        );
    }

    //menu de acuerdo al nivel
    protected function menu($nivel) {
        $mMenu = new menus();
        $menus = $mMenu->listaNivel($nivel);
        $this->view->setVar('menus', $menus);
    }

    /* protected function menu($nivel) {

      $phql= "SELECT m.id, m.menu, m.descripcion, m.controlador,s.id as id_submenu  ,s.submenu,s.accion,s.descripcion,m.icon
      FROM menus m
      INNER JOIN nivelmenu AS n ON (m.id=n.id_menu )
      INNER JOIN submenus AS s ON (m.id=s.id_menu)
      WHERE n.id_nivel='$nivel'
      AND s.habilitado='1'
      ORDER BY m.index";
      $query = $this->modelsManager->createQuery($phql);
      $menus = $query->execute();
      $this->view->setVar('menus', $menus);
      } */
}
