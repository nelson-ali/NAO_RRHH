<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  28-01-2015
*/

class UbicacionesController extends ControllerBase{

    public function initialize()
    {
        parent::initialize();
    }
    /**
     * Función para la carga de la página de gestión de relaciones laborales.
     * Se cargan los combos necesarios.
     */
    public function indexAction()
    {
    }

    /**
     * Función para la carga del primer listado sobre la página de gestión de tolerancias de ingreso.
     * Se inhabilita la vista para el uso de jqwidgets,
     */
    public function listgruposAction()
    {
        $this->view->disable();
        $idUbicacion = 0;
        if(isset($_POST["id_ubicacion"])){
            $idUbicacion = $_POST["id_ubicacion"];
        }
        $obj = new Fubicaciones();
        $resul = $obj->obtenerGrupoUbicaciones($idUbicacion);

        $ubicaciones = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $ubicaciones[] = array(
                    'id'=>$v->id,
                    'padre_id' => $v->padre_id,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'id_estacion'=> $v->id_estacion,
                    'estacion'=> $v->id_estacion!=null?$v->estacion:"",
                    'color'=> $v->color!=null?$v->color:""
                );
            }
        }
        echo json_encode($ubicaciones);
    }

    /**
     * Función para listar las ubicaciones principales (Sin considerar líneas).
     */
    public function listprincipalesAction()
    {
        $this->view->disable();
        $obj = new Ubicaciones();
        $resul = $obj->getAllWithSon();
        $ubicaciones = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $ubicaciones[] = array(
                    'id'=>$v->id,
                    'padre_id' => $v->padre_id,
                    'ubicacion' => $v->ubicacion,
                    'color'=> $v->color!=null?$v->color:"",
                    'observacion'=> $v->observacion,
                    'estado'=>$v->estado,
                    'cant_nodos_hijos'=>$v->cant_nodos_hijos
                );
            }
        }
        echo json_encode($ubicaciones);
    }
    /**
     * Función para listar las estaciones por línea correspondiente a una ubicación principal.
     */
    public function listestacionesAction()
    {   $this->view->disable();
        $ubicaciones = Array();
        if(isset($_POST["id"])&&$_POST["id"]>0){
            $resul = Ubicaciones::find(array("padre_id = ".$_POST["id"]." AND agrupador=2 AND estado=1 AND baja_logica=1"));
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $ubicaciones[] = array(
                        'id'=>$v->id,
                        'padre_id' => $v->padre_id,
                        'ubicacion' => $v->ubicacion,
                        'color'=> $v->color!=null?$v->color:"",
                        'observacion'=> $v->observacion,
                        'estado'=>$v->estado
                    );
                }
            }
        }
        echo json_encode($ubicaciones);
    }

    /**
     * Función para la obtención del listado de ubicaciones con la relación de cupos de acuerdo
     * al identificador del perfil y rango de fechas del calendario seleccionado.
     */
    public function listgruposporcuposAction(){
        $this->view->disable();
        $idUbicacion = 0;
        if(isset($_POST["id_ubicacion"])){
            $idUbicacion = $_POST["id_ubicacion"];
        }
        if(isset($_POST["id_perfillaboral"])&&isset($_POST["fecha_ini"])&&isset($_POST["fecha_fin"])){
            $idPerfilLaboral = $_POST["id_perfillaboral"];
            $fechaIni = $_POST["fecha_ini"];
            $fechaFin = $_POST["fecha_fin"];
            $obj = new Fubicaciones();
            $resul = $obj->obtenerCuposPorGrupoUbicaciones($idUbicacion,$idPerfilLaboral,$fechaIni,$fechaFin);
            $ubicaciones = Array();
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $ubicaciones[] = array(
                        'id'=>$v->id,
                        'padre_id' => $v->padre_id,
                        'id_ubicacion' => $v->id_ubicacion,
                        'ubicacion' => $v->ubicacion,
                        'id_estacion'=> $v->id_estacion,
                        'estacion'=> $v->id_estacion!=null?$v->estacion:"",
                        'color'=> $v->color!=null?$v->color:"",
                        'id_cupoturno'=> $v->id_cupoturno,
                        'perfillaboral_id'=> $v->perfillaboral_id,
                        'fecha_ini'=> $v->fecha_ini,
                        'fecha_fin'=> $v->fecha_fin,
                        'cupo'=> $v->cupo
                    );
                }
            }
        }
        echo json_encode($ubicaciones);
    }
    /**
     * Función para la obtención del listado de ubicaciones con la relación de cupos de acuerdo
     * al identificador del perfil y rango de fechas del calendario seleccionado.
     */
    public function listubicacionespricipalesporperfilAction(){
        $this->view->disable();
        if(isset($_POST["id_perfillaboral"])){
            $idPerfilLaboral = $_POST["id_perfillaboral"];
            $obj = new Fubicaciones();
            $resul = $obj->obtenerUbicacionesPrincipalesPorPerfil($idPerfilLaboral);
            $ubicaciones = Array();
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $ubicaciones[] = array(
                        'id'=>$v->id,
                        'padre_id' => $v->padre_id,
                        'id_ubicacion' => $v->id_ubicacion,
                        'ubicacion' => $v->ubicacion,
                        'id_estacion'=> $v->id_estacion,
                        'estacion'=> $v->id_estacion!=null?$v->estacion:"",
                        'color'=> $v->color!=null?$v->color:"",
                        'cant_nodos_hijos'=>$v->cant_nodos_hijos
                    );
                }
            }
        }
        echo json_encode($ubicaciones);
    }

    /**
     * Función para la obtención de las estaciones relacionadas con un perfil y ubicación registrados.
     */
    public function listestacionesporubicacionparaperfilAction(){
        $this->view->disable();
        $ubicaciones = Array();
        if(isset($_POST["id_perfillaboral"])&&$_POST["id_perfillaboral"]>0&&isset($_POST["id_ubicacion"])&&$_POST["id_ubicacion"]>0){
            $idPerfilLaboral = $_POST["id_perfillaboral"];
            $idUbicacion = $_POST["id_ubicacion"];
            $obj = new Fubicaciones();
            $resul = $obj->obtenerEstacionesPorUbicacionPorPerfil($idPerfilLaboral,$idUbicacion);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $ubicaciones[] = array(
                        'id'=>$v->id,
                        'padre_id' => $v->padre_id,
                        'id_ubicacion' => $v->id_ubicacion,
                        'ubicacion' => $v->ubicacion,
                        'id_estacion'=> $v->id_estacion,
                        'estacion'=> $v->id_estacion!=null?$v->estacion:"",
                        'color'=> $v->color!=null?$v->color:""
                    );
                }
            }
        }
        echo json_encode($ubicaciones);
    }
}