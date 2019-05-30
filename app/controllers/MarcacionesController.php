<?php

/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Vias Bolivia "Mi Teleférico"
*   Versión:  2.0.0
*   Usuario Creador: Lic. Javier Loza
 *  modificaido por fernando tarifa
*   Fecha Creación:  11-05-2019
 * 20-05-2019   modificacion conexio sql    Carla Blanco
*/

//require_once '/../../app/libs/Db/Adapter/Pdo/Sqlsrv.php';
//require_once "Phalcon/Db/Dialect/Sqlsrv.php";
//require_once "Phalcon/Db/Result/PdoSqlsrv.php";
use Phalcon\Db\Adapter\Pdo\Sqlsrv as MssqlAdapter;
use Phalcon\Db\Dialect\Sqlsrv as MssqlDialect;

//define ("DB_MSSQL_HOST", '192.168.1.15');
//define ("DB_MSSQL_PORT", '1433');
//define ("DB_MSSQL_USER", 'sa');
//define ("DB_MSSQL_PASSWD", 'S1stemas');
//define ("DB_MSSQL_NAME", 'BiometricoK30');
//define ("DB_MSSQL_SCHEMA", 'dbo');
//define ("DB_MSSQL_CHARSET", 'utf8');

class MarcacionesController extends ControllerBase
{
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
        $auth = $this->session->get('auth');
        if (isset($auth['version'])) {
            $version = $auth['version'];
        } else $version = "0.0.0";

        $this->assets->addCss('/assets/css/bootstrap-switch.css?v=' . $version);
        $this->assets->addJs('/js/switch/bootstrap-switch.js?v=' . $version);
        $this->assets->addCss('/assets/css/oasis.principal.css?v=' . $version);
        $this->assets->addCss('/assets/css/jquery-ui.css?v=' . $version);
        //$this->assets->addCss('/css/oasis.grillas.css?v=' . $version);
        $this->assets->addJs('/js/numeric/jquery.numeric.js?v=' . $version);
        $this->assets->addJs('/js/jquery.PrintArea.js?v=' . $version);
        $this->assets->addCss('/assets/css/PrintArea.css?v=' . $version);

        $this->assets->addCss('/assets/css/clockpicker.css?v=' . $version);
        $this->assets->addJs('/js/clockpicker/clockpicker.js?v=' . $version);

        $this->assets->addJs('/js/marcaciones/oasis.marcaciones.tab.js?v=' . $version);
        $this->assets->addJs('/js/marcaciones/oasis.marcaciones.index.js?v=' . $version);
        $this->assets->addJs('/js/marcaciones/oasis.marcaciones.list.js?v=' . $version);
        $this->assets->addJs('/js/marcaciones/oasis.marcaciones.approve.js?v=' . $version);
        $this->assets->addJs('/js/marcaciones/oasis.marcaciones.download.js?v=' . $version);
        $this->assets->addJs('/js/marcaciones/oasis.marcaciones.new.edit.js?v=' . $version);
        $this->assets->addJs('/js/marcaciones/oasis.marcaciones.turns.excepts.js?v=' . $version);
        $this->assets->addJs('/js/marcaciones/oasis.marcaciones.down.js?v=' . $version);
        $this->assets->addJs('/js/marcaciones/oasis.marcaciones.move.js?v=' . $version);
        $this->assets->addJs('/js/marcaciones/oasis.marcaciones.view.js?v=' . $version);
        $this->assets->addJs('/js/marcaciones/oasis.marcaciones.export.controlmarc.js?v=' . $version);
        $this->assets->addJs('/js/marcaciones/oasis.marcaciones.export.download.js?v=' . $version);
        $this->assets->addJs('/js/marcaciones/oasis.marcaciones.view.splitter.js?v=' . $version);
    }

    /**
     * Función para la obtención del listado de marcaciones descargados en el sistema OASIS
     * desde la base de datos del sistema de control de personal.
     */
    public function listAction()
    {
        $this->view->disable();
        $obj = new Fmarcaciones();
        $marcaciones = Array();
        $ci = $fechaIni = $fechaFin = "";
        if (isset($_GET["ci"])) {
            $ci = $_GET["ci"];
        }
        if (isset($_GET["fecha_ini"])) {
            $fechaIni = $_GET["fecha_ini"];
        }
        if (isset($_GET["fecha_fin"])) {
            $fechaFin = $_GET["fecha_fin"];
        }
        $where = "";
        $pagenum = $_GET['pagenum'];
        $pagesize = $_GET['pagesize'];
        $total_rows = 0;
        $start = $pagenum * $pagesize;

        // filter data.
        if (isset($_GET['filterscount'])) {
            $filterscount = $_GET['filterscount'];

            if ($filterscount > 0) {
                $where = " WHERE (";
                $tmpdatafield = "";
                $tmpfilteroperator = "";
                for ($i = 0; $i < $filterscount; $i++) {
                    // get the filter's value.
                    $filtervalue = $_GET["filtervalue" . $i];
                    // get the filter's condition.
                    $filtercondition = $_GET["filtercondition" . $i];
                    // get the filter's column.
                    $filterdatafield = $_GET["filterdatafield" . $i];
                    // get the filter's operator.
                    $filteroperator = $_GET["filteroperator" . $i];

                    if ($tmpdatafield == "") {
                        $tmpdatafield = $filterdatafield;
                    } else if ($tmpdatafield <> $filterdatafield) {
                        $where .= ")AND(";
                    } else if ($tmpdatafield == $filterdatafield) {
                        if ($tmpfilteroperator == 0) {
                            $where .= " AND ";
                        } else
                            $where .= " OR ";
                    }

                    // build the "WHERE" clause depending on the filter's condition, value and datafield.
                    switch ($filtercondition) {
                        case "NOT_EMPTY":
                        case "NOT_NULL":
                            $where .= " " . $filterdatafield . " NOT LIKE '" . "" . "'";
                            break;
                        case "EMPTY":
                        case "NULL":
                            $where .= " " . $filterdatafield . " LIKE '" . "" . "'";
                            break;
                        case "CONTAINS_CASE_SENSITIVE":
                            $where .= " BINARY  " . $filterdatafield . " LIKE '%" . $filtervalue . "%'";
                            break;
                        case "CONTAINS":
                            $where .= " UPPER(" . $filterdatafield . ") LIKE UPPER('%" . $filtervalue . "%')";
                            break;
                        case "DOES_NOT_CONTAIN_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " NOT LIKE '%" . $filtervalue . "%'";
                            break;
                        case "DOES_NOT_CONTAIN":
                            $where .= " " . $filterdatafield . " NOT LIKE '%" . $filtervalue . "%'";
                            break;
                        case "EQUAL_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " = '" . $filtervalue . "'";
                            break;
                        case "EQUAL":
                            $where .= " " . $filterdatafield . " = '" . $filtervalue . "'";
                            break;
                        case "NOT_EQUAL_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " <> '" . $filtervalue . "'";
                            break;
                        case "NOT_EQUAL":
                            $where .= " " . $filterdatafield . " <> '" . $filtervalue . "'";
                            break;
                        case "GREATER_THAN":
                            $where .= " " . $filterdatafield . " > '" . $filtervalue . "'";
                            break;
                        case "LESS_THAN":
                            $where .= " " . $filterdatafield . " < '" . $filtervalue . "'";
                            break;
                        case "GREATER_THAN_OR_EQUAL":
                            $where .= " " . $filterdatafield . " >= '" . $filtervalue . "'";
                            break;
                        case "LESS_THAN_OR_EQUAL":
                            $where .= " " . $filterdatafield . " <= '" . $filtervalue . "'";
                            break;
                        case "STARTS_WITH_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " LIKE '" . $filtervalue . "%'";
                            break;
                        case "STARTS_WITH":
                            $where .= " UPPER(" . $filterdatafield . ") LIKE UPPER('" . $filtervalue . "%')";
                            break;
                        case "ENDS_WITH_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " LIKE '%" . $filtervalue . "'";
                            break;
                        case "ENDS_WITH":
                            $where .= " " . $filterdatafield . " LIKE '%" . $filtervalue . "'";
                            break;
                    }

                    if ($i == $filterscount - 1) {
                        $where .= ")";
                    }

                    $tmpfilteroperator = $filteroperator;
                    $tmpdatafield = $filterdatafield;
                }
            }
        }
        if ($ci != '' && $ci > 0) {
            if ($where != '') {
                $where .= " AND id_persona=(SELECT id FROM personas WHERE ci = '$ci' LIMIT 1)";
            } else {
                $where .= " WHERE id_persona=(SELECT id FROM personas WHERE ci = '$ci' LIMIT 1)";
            }
        }
        if (isset($_GET["opcion"]) && $_GET["opcion"] == 1) {
            if ($fechaIni != '' && $fechaFin != '') {
                $resul = $obj->getAll($fechaIni, $fechaFin, $where, "", $start, $pagesize);
                if ($resul->count() > 0) {
                    foreach ($resul as $v) {
                        $total_rows = $v->total_rows;
                        $marcaciones[] = array(
                            'nro_row' => 0,
                            'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                            'gerencia_administrativa' => $v->gerencia_administrativa,
                            'id_departamento_administrativo' => $v->id_departamento_administrativo,
                            'departamento_administrativo' => $v->departamento_administrativo,
                            'id_area' => $v->id_area,
                            'area' => $v->area,
                            'nombres' => $v->nombres,
                            'ci' => $v->ci,
                            'expd' => $v->expd,
                            'estado' => $v->estado,
                            'estado_descripcion' => $v->estado_descripcion,
                            'gestion' => $v->gestion,
                            'mes' => $v->mes,
                            'mes_nombre' => $v->mes_nombre,
                            'fecha' => $v->fecha != "" ? date("d-m-Y", strtotime($v->fecha)) : "",
                            'hora' => $v->hora,
                            'id_maquina' => $v->id_maquina,
                            'maquina' => $v->maquina,
                            'user_reg_id' => $v->user_reg_id,
                            'usuario' => $v->usuario,
                            'fecha_reg' => $v->fecha_reg != "" ? date("d-m-Y", strtotime($v->fecha_reg)) : "",
                            'fecha_ini_rango' => $v->fecha_ini_rango != "" ? date("d-m-Y", strtotime($v->fecha_ini_rango)) : "",
                            'fecha_fin_rango' => $v->fecha_fin_rango != "" ? date("d-m-Y", strtotime($v->fecha_fin_rango)) : ""
                        );
                    }
                }
            }
        }
        $data[] = array(
            'TotalRows' => $total_rows,
            'Rows' => $marcaciones
        );
        echo json_encode($data);
    }

    /**
     * Función para la obtención de los registros de marcación correspondientes a un registro de relación laboral.
     */
    public function listbyrelaboralAction()
    {
        $this->view->disable();
        $obj = new Fmarcaciones();
        $marcaciones = Array();
        $where = "";
        $pagenum = $_GET['pagenum'];
        $pagesize = $_GET['pagesize'];
        $filtercount = 0;
        $fechaIni = $fechaFin = "";
        if (isset($_GET["fecha_ini"])) {
            $fechaIni = $_GET["fecha_ini"];
        }
        if (isset($_GET["fecha_fin"])) {
            $fechaFin = $_GET["fecha_fin"];
        }
        $total_rows = 0;
        $start = $pagenum * $pagesize;

        // filter data.
        if (isset($_GET['filterscount'])) {
            $filterscount = $_GET['filterscount'];

            if ($filterscount > 0) {
                $where = " WHERE (";
                $tmpdatafield = "";
                $tmpfilteroperator = "";
                for ($i = 0; $i < $filterscount; $i++) {
                    // get the filter's value.
                    $filtervalue = $_GET["filtervalue" . $i];
                    // get the filter's condition.
                    $filtercondition = $_GET["filtercondition" . $i];
                    // get the filter's column.
                    $filterdatafield = $_GET["filterdatafield" . $i];
                    // get the filter's operator.
                    $filteroperator = $_GET["filteroperator" . $i];

                    if ($tmpdatafield == "") {
                        $tmpdatafield = $filterdatafield;
                    } else if ($tmpdatafield <> $filterdatafield) {
                        $where .= ")AND(";
                    } else if ($tmpdatafield == $filterdatafield) {
                        if ($tmpfilteroperator == 0) {
                            $where .= " AND ";
                        } else
                            $where .= " OR ";
                    }

                    // build the "WHERE" clause depending on the filter's condition, value and datafield.
                    switch ($filtercondition) {
                        case "NOT_EMPTY":
                        case "NOT_NULL":
                            $where .= " " . $filterdatafield . " NOT LIKE '" . "" . "'";
                            break;
                        case "EMPTY":
                        case "NULL":
                            $where .= " " . $filterdatafield . " LIKE '" . "" . "'";
                            break;
                        case "CONTAINS_CASE_SENSITIVE":
                            $where .= " BINARY  " . $filterdatafield . " LIKE '%" . $filtervalue . "%'";
                            break;
                        case "CONTAINS":
                            $where .= " UPPER(" . $filterdatafield . ") LIKE UPPER('%" . $filtervalue . "%')";
                            break;
                        case "DOES_NOT_CONTAIN_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " NOT LIKE '%" . $filtervalue . "%'";
                            break;
                        case "DOES_NOT_CONTAIN":
                            $where .= " " . $filterdatafield . " NOT LIKE '%" . $filtervalue . "%'";
                            break;
                        case "EQUAL_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " = '" . $filtervalue . "'";
                            break;
                        case "EQUAL":
                            $where .= " " . $filterdatafield . " = '" . $filtervalue . "'";
                            break;
                        case "NOT_EQUAL_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " <> '" . $filtervalue . "'";
                            break;
                        case "NOT_EQUAL":
                            $where .= " " . $filterdatafield . " <> '" . $filtervalue . "'";
                            break;
                        case "GREATER_THAN":
                            $where .= " " . $filterdatafield . " > '" . $filtervalue . "'";
                            break;
                        case "LESS_THAN":
                            $where .= " " . $filterdatafield . " < '" . $filtervalue . "'";
                            break;
                        case "GREATER_THAN_OR_EQUAL":
                            $where .= " " . $filterdatafield . " >= '" . $filtervalue . "'";
                            break;
                        case "LESS_THAN_OR_EQUAL":
                            $where .= " " . $filterdatafield . " <= '" . $filtervalue . "'";
                            break;
                        case "STARTS_WITH_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " LIKE '" . $filtervalue . "%'";
                            break;
                        case "STARTS_WITH":
                            $where .= " " . $filterdatafield . " LIKE '" . $filtervalue . "%'";
                            break;
                        case "ENDS_WITH_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " LIKE '%" . $filtervalue . "'";
                            break;
                        case "ENDS_WITH":
                            $where .= " " . $filterdatafield . " LIKE '%" . $filtervalue . "'";
                            break;
                    }

                    if ($i == $filterscount - 1) {
                        $where .= ")";
                    }

                    $tmpfilteroperator = $filteroperator;
                    $tmpdatafield = $filterdatafield;
                }
            }
        }
        if (isset($_GET["id"]) && $fechaIni != '' && $fechaFin != '') {
            //if($where!="")$where = "WHERE ".$where;
            $resul = $obj->getAllByRelaboral($_GET["id"], $fechaIni, $fechaFin, $where, "", $start, $pagesize);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                //$arrTotal = $obj->getCountAllByRelaboral($_GET["id"], $fechaIni, $fechaFin, $where, "");
                //$total_rows = $arrTotal[0]->resultado;
                foreach ($resul as $v) {
                    $total_rows = $v->total_rows;
                    $marcaciones[] = array(
                        'nro_row' => 0,
                        'id_persona' => $v->id_persona,
                        'nombres' => $v->nombres,
                        'ci' => $v->ci,
                        'expd' => $v->expd,
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'gestion' => $v->gestion,
                        'mes' => $v->mes,
                        'mes_nombre' => $v->mes_nombre,
                        'fecha' => $v->fecha != "" ? date("d-m-Y", strtotime($v->fecha)) : "",
                        'hora' => $v->hora,
                        'id_maquina' => $v->id_maquina,
                        'maquina' => $v->maquina,
                        'user_reg_id' => $v->user_reg_id,
                        'usuario' => $v->usuario,
                        'fecha_reg' => $v->fecha_reg != "" ? date("d-m-Y", strtotime($v->fecha_reg)) : "",
                        'fecha_ini_rango' => $v->fecha_ini_rango != "" ? date("d-m-Y", strtotime($v->fecha_ini_rango)) : "",
                        'fecha_fin_rango' => $v->fecha_fin_rango != "" ? date("d-m-Y", strtotime($v->fecha_fin_rango)) : ""
                    );
                }
            }
        }
        $data[] = array(
            'TotalRows' => $total_rows,
            'Rows' => $marcaciones
        );
        echo json_encode($data);
    }

    /**
     * Función para la conexión con SQL Server
     * @return false|resource
     */
    function conexionMSql()
    {

        $serverName = "192.168.131.241";
        $connectionInfo = array("Database"=>"BiometricoK30", "UId"=>"sa", "PWD"=>"S1stemas", "CharacterSet"=>"UTF-8");

        /* Nos conectamos mediante la autenticación de SQL Server . */
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if ($conn === false) {
            die ("Falla en la conexion...");
            die(print_r(sqlsrv_errors(), true));
        }
        //var_dump($conn);
        return $conn;

    }

    function pruebaAction()
    {
        //$cn = $this->conexionMSql(); phpinfo(); exit(0);
        $this->view->disable();
        $dbh = $this->conexionMsqlPdo();
        $sql = "SELECT * FROM USERINFO";
        $stmt = $dbh->prepare($sql);
        if ($stmt->execute()) {
            while ($result = $stmt->fetch()) {
                echo '<p>nRO---->'.$result['BADGENUMBER'];
//                 echo var_dump ($result);

            }
        }

    }

    /**
     * Función alternativa para la obtencion de la conexión controlando el uso de linux y windows.
     * @return PDOF
     */
    function conexionMsqlPdo()
    {
        $dbh = false;
        $option = 1;
        switch ($option) {
            case 1:
                $HOST = "192.168.131.241";
                $USERNAME = "sa";
                $PASSWORD = "S1stemas";
                $DBNAME = "BiometricoK30";
                break;
            default:
                $HOST = "192.168.131.241";
                $USERNAME = "sa";
                $PASSWORD = "S1stemas";
                $DBNAME = "BiometricoK30";
        }
        try {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                /**
                 * Usando Windows
                 */
                $dbh = new PDO("sqlsrv:Server=$HOST;Database=$DBNAME", $USERNAME, $PASSWORD);
            } else {
                /**
                 * Usando Linux
                 */
                $dbh = new PDO("sqlsrv:Server=$HOST;Database=$DBNAME", $USERNAME, $PASSWORD);
            }
        } catch (PDOException $e) {
            echo "Failed to get DB handle: " . $e->getMessage() . "\n";
            exit;
        }
        return $dbh;
    }

    /**
     * Disables FOREIGN_KEY_CHECKS and truncates database table
     * @param string $table table name
     * @return bool result of truncate operation
     */
    public function truncateTable($table)
    {
        $db = $this->getDI()->get('db');
        $success = $db->execute("TRUNCATE TABLE $table, $table RESTART IDENTITY");
        return $success;
    }

    /**
     * Función para el borrado de los registros de acuerdo al rango de fechas establecidas.
     * @param $ci
     * @param $fechaIni
     * @param $fechaFin
     * @return mixed
     */
    public function borrarRangoEnTabla($ci, $fechaIni, $fechaFin)
    {
        $db = $this->getDI()->get('db');
        /**
         * Se agrega el control de eliminación de registros con agrupador = 0 debido a que en caso de que una persona sea eliminada del biométrico
         * y/o sea dado de baja no se modifique más sus registros.
         */
        $sql = "DELETE FROM marcaciones where fecha BETWEEN '$fechaIni' AND '$fechaFin' AND agrupador=0";
        if ($ci != '' && $ci > 0) $sql .= " AND persona_id=(SELECT id FROM personas WHERE CI='$ci' LIMIT 1)";
        $success = $db->execute($sql);
        return $success;
    }

    /**
     * Función para obtener el listado global de marcaciones de acuerdo al rango enviado
     */
    function getallmarcacionesAction($idUsuario = 0, $ci = 0, $idMaquina = 0, $fechaIni, $fechaFin)
    {
        $this->view->disable();
        $result = array();
        if ($fechaIni != '' && $fechaFin != '') {
            $msql = $this->conexionMSql();
            $tsql = "[]$idUsuario,'$ci',$idMaquina,'$fechaIni','$fechaFin'";
            $stmt = sqlsrv_query($msql, $tsql);
            if ($stmt === false) {
                echo "Error al ejecutar consulta.</br>";
                die(print_r(sqlsrv_errors(), true));
            } else {
                $result = sqlsrv_fetch_array($stmt);
                sqlsrv_free_stmt($stmt);
                sqlsrv_close($msql);

            }
        }
        return $result;
    }

    /**
     * Función para la descarga de los registros almacenados en la base de datos correspondiente a marcaciones de equipos biométricos.
     */
    public function downloadAction()
    {
        $ok = true;
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        $vocales = array("Á", "É", "Í", "Ó", "Ú", "Ñ");
        $vocales2 = array("A", "E", "I", "O", "U", "N");
        $this->view->disable();
        if (isset($_POST["fecha_ini"]) && isset($_POST["fecha_fin"])) {
            $fechaIni = $_POST["fecha_ini"];
            $fechaFin = $_POST["fecha_fin"];
            $ci = '';
            $idPersona = 0;
            if (isset($_POST["ci"]) && $_POST["ci"] != '') {
                $ci = $_POST["ci"];
            }
            if (isset($_POST["id_persona"]) && $_POST["id_persona"] != '') {
                $idPersona = $_POST["id_persona"];
            }
            $msql = $this->conexionMSql();
            $tsql = "[]0,'$ci',0,'" . $fechaIni . "','" . $fechaFin . "'";
            $stmt = sqlsrv_query($msql, $tsql);
            if ($stmt === false) {
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guardaron los registros de marcaciones.');
            } else {
                $errores = 0;
                $tot = 0;
                $good = 0;
                //$this->borrarRangoEnTabla($ci, $fechaIni, $fechaFin);
                $err = array();
                $db = $this->getDI()->get('db');
                $sqlA = "INSERT INTO marcaciones(id, persona_id, maquina_id, fecha, hora, observacion, estado,baja_logica, agrupador, user_reg_id, fecha_reg, fecha_ini_rango, fecha_fin_rango) VALUES ";
                $sqlB = "";
                while ($result = sqlsrv_fetch_array($stmt)) {
                    $tot++;
                    if ($ci != '' && $ci != null)
                        $persona = Personas::findFirst(array("ci LIKE '" . trim($ci) . "'"));
                    else
                        $persona = Personas::findFirst(array("ci LIKE '" . trim($result ["CI"]) . "'"));

                    $maquina = Maquinas::findFirst(array("num_serie LIKE '" . trim($result ["CODIGO_MAQUINA"]) . "'"));
                    $idPersona = $idMaquina = 0;
                    if ($persona) {
                        $idPersona = $persona->id;
                    }
                    if ($maquina) {
                        $idMaquina = $maquina->id;
                    }
                    if ($idPersona != null && $idPersona > 0 && $maquina != null && $idMaquina > 0) {
                        $good++;
                        /**
                         * Se almacenan los registros con valor de 1 para el vampo agrupador debido a que ya no se eliminan los registros en el momento inicial, sino se hace una depuración de datos duplicados al final de la descarga.
                         * Fecha de modificación: 28/07/2016
                         */
                        $sqlB .= "(default,$idPersona,$idMaquina,'" . $result ["MARCACION_FECHA"] . "','" . $result ["MARCACION_HORA"] . "',NULL,1,1,1,$user_reg_id,'$hoy','$fechaIni','$fechaFin'),";
                        if ($good % 10000 == 0) {
                            $sqlB .= ",";
                            $sqlB = str_replace(",,", "", $sqlB);
                            $ok = $db->execute($sqlA . $sqlB);
                            $sqlB = "";
                        }
                    } else {
                        $err[] = array('ci' => $result ["CI"], 'id_persona' => $idPersona, 'codigo_maquina' => $result ["CODIGO_MAQUINA"], 'id_maquina' => $idMaquina);
                        $ok = false;
                        $errores++;
                        /**
                         * En caso de que la persona no este registrada y se haya solicitado específicamente sólo sus marcaciones.
                         */
                        if ($ci != '') {
                            break;
                        }
                    }
                }
                if ($sqlB != "") {
                    $sqlB .= ",";
                    $sqlB = str_replace(",,", "", $sqlB);
                    $ok = $db->execute($sqlA . $sqlB);
                }
                if ($ok) $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se realizaron las descargas para el rango solicitado de modo satisfactorio.', 'total' => $tot, 'correctas' => $good, 'errores' => $errores);
                else {

                    $msj = array('result' => 0, 'msj' => 'Se realizaron las descargas para el rango solicitado. Sin embargo, hubieron ' . $errores . " marcaciones que no se descargaron por inconsistencia de datos.", 'cant_total' => $tot, 'cant_correctas' => $good, 'cant_errores' => $errores, 'errores' => $err);
                }
            }
            sqlsrv_free_stmt($stmt);
            sqlsrv_close($msql);
        }
        echo json_encode($msj);
    }

    /**
     * Función para descargar las marcaciones del Servidor de equipos biométricos.
     */
    public function descargarAction()
    {
        $ok = true;
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        $vocales = array("Á", "É", "Í", "Ó", "Ú", "Ñ");
        $vocales2 = array("A", "E", "I", "O", "U", "N");
        $this->view->disable();
        if (isset($_POST["fecha_ini"]) && isset($_POST["fecha_fin"])) {
            $fechaIni = $_POST["fecha_ini"];
            $fechaFin = $_POST["fecha_fin"];
            $ci = '';
            $idPersona = 0;
            if (isset($_POST["ci"]) && $_POST["ci"] != '') {
                $ci = $_POST["ci"];
            }
            if (isset($_POST["id_persona"]) && $_POST["id_persona"] != '') {
                $idPersona = $_POST["id_persona"];
            }
            $dbh = $this->conexionMsqlPdo();
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $sql = "[SP_RPT_MARCACIONES_BIOMETRICOS]0,'" . $ci . "',0,'" . $fechaIni . "','" . $fechaFin . "'";
            } else {
                $sql = "SELECT d.DEPTNAME AS UBICACION,u.NAME AS NOMBRES,CASE WHEN u.SSN IS NOT NULL THEN u.SSN ELSE u.BADGENUMBER END AS CI,
                        u.TITLE AS CARGO,CONVERT(VARCHAR(10),c.CHECKTIME,103) AS MARCACION_FECHA,CONVERT(VARCHAR(10),c.CHECKTIME,108) AS MARCACION_HORA,
                        c.sn AS CODIGO_MAQUINA,m.MachineAlias AS ESTACION FROM dbo.USERINFO u
                        INNER JOIN dbo.CHECKINOUT c ON u.USERID = c.USERID
                        INNER JOIN dbo.DEPARTMENTS d ON d.DEPTID = u.DEFAULTDEPTID
                        INNER JOIN dbo.Machines m ON m.sn = c.sn ";
                //echo "-->".$sql;
                $arrFechaIni = explode("-", $fechaIni);
                $arrFechaFin = explode("-", $fechaFin);
                $fechaIni = $arrFechaIni[2] . "-" . $arrFechaIni[1] . "-" . $arrFechaIni[0];
                $fechaFin = $arrFechaFin[2] . "-" . $arrFechaFin[1] . "-" . $arrFechaFin[0];
                $sql .= "WHERE (c.CHECKTIME BETWEEN '" . $fechaIni . " 00:00:00' AND '" . $fechaFin . " 23:59:59' ";
                $sql .= "OR CAST(c.CHECKTIME AS DATE) BETWEEN CAST('" . $fechaIni . "' AS DATE) AND CAST('" . $fechaFin . "' AS DATE)) ";
                if ($ci != "" && $ci != NULL) {
                    $sql .= "AND (u.BADGENUMBER LIKE CAST('%$ci%' AS VARCHAR(24)) OR u.SSN LIKE CAST('%$ci%' AS VARCHAR(24))) ";
                }
            }

            //echo $sql;
            $stmt = $dbh->prepare($sql);
            if ($stmt->execute()) {
                $errores = 0;
                $tot = 0;
                $good = 0;
                $err = array();
                $db = $this->getDI()->get('db');
                $sqlA = "INSERT INTO marcaciones(id, persona_id, maquina_id, fecha, hora, observacion, estado,baja_logica, agrupador, user_reg_id, fecha_reg, fecha_ini_rango, fecha_fin_rango) VALUES ";
                $sqlB = "";
                $contador = 0;
                while ($result = $stmt->fetch()) {
                    $tot++;
                    if ($ci != '' && $ci != null)
                        $persona = Personas::findFirst(array("ci LIKE '" . trim($ci) . "'"));
                    else
                        $persona = Personas::findFirst(array("ci LIKE '" . trim($result ["CI"]) . "'"));

                    $maquina = Maquinas::findFirst(array("num_serie LIKE '" . trim($result ["CODIGO_MAQUINA"]) . "'"));
                    $idPersona = $idMaquina = 0;
                    if (is_object($persona)) {
                        $idPersona = $persona->id;
                    }
                    if (is_object($maquina)) {
                        $idMaquina = $maquina->id;
                    }
                    if ($idPersona != null && $idPersona > 0 && $maquina != null && $idMaquina > 0) {
                        $good++;
                        //Se modifica el valor del agrupador debido a que se incorpora un depurador de registros al final de la descarga.
                        $sqlB .= "(default,$idPersona,$idMaquina,'" . $result ["MARCACION_FECHA"] . "','" . $result ["MARCACION_HORA"] . "',NULL,1,1,1,$user_reg_id,'$hoy','$fechaIni','$fechaFin'),";
                        if ($good % 10000 == 0) {
                            $sqlB .= ",";
                            $sqlB = str_replace(",,", "", $sqlB);
                            $ok = $db->execute($sqlA . $sqlB);
                            $sqlB = "";
                        }
                    } else {
                        $err[] = array('ci' => $result ["CI"], 'id_persona' => $idPersona, 'codigo_maquina' => $result ["CODIGO_MAQUINA"], 'id_maquina' => $idMaquina);
                        $ok = false;
                        $errores++;
                        //En caso de que la persona no este registrada y se haya solicitado específicamente sólo sus marcaciones.
                        if ($ci != '') {
                            break;
                        }
                    }
                }
                if ($sqlB != "") {
                    $sqlB .= ",";
                    $sqlB = str_replace(",,", "", $sqlB);
                    $ok = $db->execute($sqlA . $sqlB);
                }
                if ($ok) {
                    $marks = new Marcaciones();
                    $marks->depuraRegistrosDuplicados($_POST["fecha_ini"], $_POST["fecha_fin"]);
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se realizaron las descargas para el rango solicitado de modo satisfactorio (Se realiz&oacute; la depuraci&oacute;n respectiva).', 'total' => $tot, 'correctas' => $good, 'errores' => $errores, "sql" => $sql, "lista_errores" => $err);

                } else {

                    $msj = array('result' => 0, 'msj' => 'Se realizaron las descargas para el rango solicitado. Sin embargo, hubieron ' . $errores . " marcaciones que no se descargaron por inconsistencia de datos.", 'cant_total' => $tot, 'cant_correctas' => $good, 'cant_errores' => $errores, 'errores' => $err);
                }
            } else {
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guardaron los registros de marcaciones.', 'sql' => $sql);
            }
            unset($dbh);
            unset($stmt);
        }
        echo json_encode($msj);
    }

    /**
     * Función para la obtención de las marcaciones en un rango determinad de tiempo.
     */
    public function getmarcacionesAction()
    {

        $ok = true;
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        $vocales = array("Á", "É", "Í", "Ó", "Ú", "Ñ");
        $vocales2 = array("A", "E", "I", "O", "U", "N");
        $this->view->disable();
        if (isset($_POST["fecha_ini"]) && isset($_POST["fecha_fin"])) {
            $fechaIni = $_POST["fecha_ini"];
            $fechaFin = $_POST["fecha_fin"];
            $ci = '';
            $idPersona = 0;
            if (isset($_POST["ci"]) && $_POST["ci"] != '') {
                $ci = $_POST["ci"];
            }
            if (isset($_POST["id_persona"]) && $_POST["id_persona"] != '') {
                $idPersona = $_POST["id_persona"];
            }
            $dbh = $this->conexionMsqlPdo();
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                /**
                 * Consulta de un procedimiento almacenado en sistemas operativos Windows
                 */
                $sql = "[SP_RPT_MARCACIONES_BIOMETRICOS]0,'" . $ci . "',0,'" . $fechaIni . "','" . $fechaFin . "'";
            } else {
                /**
                 * Consulta completa en sistemas Linux
                 */
                $sql = "SELECT d.DEPTNAME AS UBICACION,u.NAME AS NOMBRES,CASE WHEN u.SSN IS NOT NULL THEN u.SSN ELSE u.BADGENUMBER END AS CI,
                        u.TITLE AS CARGO,CONVERT(VARCHAR(10),c.CHECKTIME,103) AS MARCACION_FECHA,CONVERT(VARCHAR(10),c.CHECKTIME,108) AS MARCACION_HORA,
                        c.sn AS CODIGO_MAQUINA,m.MachineAlias AS ESTACION FROM dbo.USERINFO u
                        INNER JOIN dbo.CHECKINOUT c ON u.USERID = c.USERID
                        INNER JOIN dbo.DEPARTMENTS d ON d.DEPTID = u.DEFAULTDEPTID
                        INNER JOIN dbo.Machines m ON m.sn = c.sn
                        WHERE (u.BADGENUMBER LIKE '%$ci%' OR u.SSN LIKE '%$ci%') ";
                $arrFechaIni = explode("-", $this->sumarDiasFecha($fechaIni, -1));
                $arrFechaFin = explode("-", $this->sumarDiasFecha($fechaFin, 1));
                //$arrFechaIni = explode("-", $fechaIni);
                //$arrFechaFin = explode("-", $fechaFin);
                $fechaIni = $arrFechaIni[2] . "-" . $arrFechaIni[1] . "-" . $arrFechaIni[0];
                $fechaFin = $arrFechaFin[2] . "-" . $arrFechaFin[1] . "-" . $arrFechaFin[0];
                $sql = $sql."AND c.CHECKTIME BETWEEN '" . $fechaIni . " 00:00:00' AND '" . $fechaFin . " 23:59:59' ";
            }
            //echo "->>>".$sql;
            $stmt = $dbh->prepare($sql);
            $data = array();
            $stations = array();
            $colors = array();
            if ($stmt->execute()) {
                $objM = new Fmarcaciones();
                $arrColoresMaquinas = $objM->obtenerColorMaquinas();
                $matrizMaquinasColores = array();
                if (count($arrColoresMaquinas) > 0) {
                    foreach ($arrColoresMaquinas as $maq) {
                        $matrizMaquinasColores[$maq->num_serie] = $maq->color;
                    }
                }
                while ($row = $stmt->fetch()) {
                    $fecha = str_replace("/", "-", $row["MARCACION_FECHA"]);
                    $marcacion = $row["MARCACION_HORA"];
                    $estacion = $row["ESTACION"];
                    $data[$fecha][] = $marcacion;
                    $stations[$fecha][$marcacion] = $estacion;
                    if (array_key_exists($row["CODIGO_MAQUINA"], $matrizMaquinasColores)) {
                        $colors[$fecha][$marcacion] = $matrizMaquinasColores[$row["CODIGO_MAQUINA"]];
                    }
                }
                $msj = array('result' => 1, 'msj' => 'Se encontraron estas marcaciones.', 'data' => $data, 'stations' => $stations, 'colors' => $colors);
            } else {
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guardaron los registros de marcaciones.', 'data' => $data, 'stations' => $stations, 'colors' => $colors, 'sql' => $sql);
            }
            unset($dbh);
            unset($stmt);
        }
        echo json_encode($msj);
    }

    /**
     * Función para obtener las marcaciones y guardarlas al mismo tiempo, de una persona en un determinado rango de fechas.
     */
    public function getandsavemarcacionesAction()
    {

        $ok = true;
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        $vocales = array("Á", "É", "Í", "Ó", "Ú", "Ñ");
        $vocales2 = array("A", "E", "I", "O", "U", "N");
        $this->view->disable();
        if (isset($_POST["fecha_ini"]) && isset($_POST["fecha_fin"])) {
            $fechaIni = $_POST["fecha_ini"];
            $fechaFin = $_POST["fecha_fin"];
            $ci = '';
            $idPersona = 0;
            if (isset($_POST["ci"]) && $_POST["ci"] != '') {
                $ci = $_POST["ci"];
            }
            if (isset($_POST["id_persona"]) && $_POST["id_persona"] != '') {
                $idPersona = $_POST["id_persona"];
            }
            $dbh = $this->conexionMsqlPdo();
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                /**
                 * Consulta de un procedimiento almacenado en sistemas operativos Windows
                 */
                $sql = "[]0,'" . $ci . "',0,'" . $fechaIni . "','" . $fechaFin . "'";
            } else {
                /**
                 * Consulta completa en sistemas Linux
                 */
                $sql = "SELECT d.DEPTNAME AS UBICACION,u.NAME AS NOMBRES,CASE WHEN u.SSN IS NOT NULL THEN u.SSN ELSE u.BADGENUMBER END AS CI,
                        u.TITLE AS CARGO,CONVERT(VARCHAR(10),c.CHECKTIME,103) AS MARCACION_FECHA,CONVERT(VARCHAR(10),c.CHECKTIME,108) AS MARCACION_HORA,
                        c.sn AS CODIGO_MAQUINA,m.MachineAlias AS ESTACION FROM dbo.USERINFO u 
                        INNER JOIN dbo.CHECKINOUT c ON u.USERID = c.USERID
                        INNER JOIN dbo.DEPARTMENTS d ON d.DEPTID = u.DEFAULTDEPTID
                        INNER JOIN dbo.Machines m ON m.ID = c.SENSORID
                        WHERE (u.BADGENUMBER LIKE '%$ci%' OR u.SSN LIKE '%$ci%') ";
                $arrFechaIni = explode("-", $this->sumarDiasFecha($fechaIni, -1));
                $arrFechaFin = explode("-", $this->sumarDiasFecha($fechaFin, 1));
                //$arrFechaIni = explode("-", $fechaIni);
                //$arrFechaFin = explode("-", $fechaFin);
                $fechaIni = $arrFechaIni[2] . "-" . $arrFechaIni[1] . "-" . $arrFechaIni[0];
                $fechaFin = $arrFechaFin[2] . "-" . $arrFechaFin[1] . "-" . $arrFechaFin[0];
                $sql .= "AND c.CHECKTIME BETWEEN '" . $fechaIni . " 00:00:00' AND '" . $fechaFin . " 23:59:59' ";
            }
            //echo "->>>".$sql;
            $stmt = $dbh->prepare($sql);
            $data = array();
            $stations = array();
            $colors = array();
            if ($stmt->execute()) {
                #region Almacenamiento de marcaciones en arrays distintos
                $objM = new Fmarcaciones();
                $arrColoresMaquinas = $objM->obtenerColorMaquinas();
                $matrizMaquinasColores = array();
                if (count($arrColoresMaquinas) > 0) {
                    foreach ($arrColoresMaquinas as $maq) {
                        $matrizMaquinasColores[$maq->num_serie] = $maq->color;
                    }
                }
                #endregion

                #region Inicializando variables de descarga
                $errores = 0;
                $tot = 0;
                $good = 0;
                $err = array();
                $db = $this->getDI()->get('db');
                $sqlA = "INSERT INTO marcaciones(id, persona_id, maquina_id, fecha, hora, observacion, estado,baja_logica, agrupador, user_reg_id, fecha_reg, fecha_ini_rango, fecha_fin_rango) VALUES ";
                $sqlB = "";
                $contador = 0;
                #endregion

                while ($row = $stmt->fetch()) {
                    #region Carga de marcaciones por fecha, estaciones, códigos de máquina
                    $fecha = str_replace("/", "-", $row["MARCACION_FECHA"]);
                    $marcacion = $row["MARCACION_HORA"];
                    $estacion = $row["ESTACION"];
                    $data[$fecha][] = $marcacion;
                    $stations[$fecha][$marcacion] = $estacion;
                    if (array_key_exists($row["CODIGO_MAQUINA"], $matrizMaquinasColores)) {
                        $colors[$fecha][$marcacion] = $matrizMaquinasColores[$row["CODIGO_MAQUINA"]];
                    }
                    #endregion

                    #region Descarga de marcaciones
                    $tot++;
                    if ($ci != '' && $ci != null)
                        $persona = Personas::findFirst(array("ci LIKE '" . trim($ci) . "'"));
                    else
                        $persona = Personas::findFirst(array("ci LIKE '" . trim($row ["CI"]) . "'"));

                    $maquina = Maquinas::findFirst(array("num_serie LIKE '" . trim($row ["CODIGO_MAQUINA"]) . "'"));
                    $idPersona = $idMaquina = 0;
                    if (is_object($persona)) {
                        $idPersona = $persona->id;
                    }
                    if (is_object($maquina)) {
                        $idMaquina = $maquina->id;
                    }
                    if ($idPersona != null && $idPersona > 0 && $maquina != null && $idMaquina > 0) {
                        $good++;
                        //Se modifica el valor del agrupador debido a que se incorpora un depurador de registros al final de la descarga.
                        $sqlB .= "(default,$idPersona,$idMaquina,'" . $row ["MARCACION_FECHA"] . "','" . $row ["MARCACION_HORA"] . "',NULL,1,1,1,$user_reg_id,'$hoy','$fechaIni','$fechaFin'),";
                        if ($good % 10000 == 0) {
                            $sqlB .= ",";
                            $sqlB = str_replace(",,", "", $sqlB);
                            $ok = $db->execute($sqlA . $sqlB);
                            $sqlB = "";
                        }
                    } else {
                        $err[] = array('ci' => $row ["CI"], 'id_persona' => $idPersona, 'codigo_maquina' => $row ["CODIGO_MAQUINA"], 'id_maquina' => $idMaquina);
                        $ok = false;
                        $errores++;
                        //En caso de que la persona no este registrada y se haya solicitado específicamente sólo sus marcaciones.
                        if ($ci != '') {
                            break;
                        }
                    }
                    #endregion
                }
                #region Descarga de marcaciones residuales
                if ($sqlB != "") {
                    $sqlB .= ",";
                    $sqlB = str_replace(",,", "", $sqlB);
                    $ok = $db->execute($sqlA . $sqlB);
                }
                #endregion
                if ($ok) {
                    $marks = new Marcaciones();
                    $marks->depuraRegistrosDuplicados($_POST["fecha_ini"], $_POST["fecha_fin"]);
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se realizaron las descargas para el rango solicitado de modo satisfactorio (Se realiz&oacute; la depuraci&oacute;n respectiva).', 'total' => $tot, 'correctas' => $good, 'errores' => $errores, "sql" => $sql, "lista_errores" => $err, 'data' => $data, 'stations' => $stations, 'colors' => $colors);

                } else {

                    $msj = array('result' => 0, 'msj' => 'Se realizaron las descargas para el rango solicitado. Sin embargo, hubieron ' . $errores . " marcaciones que no se descargaron por inconsistencia de datos.", 'cant_total' => $tot, 'cant_correctas' => $good, 'cant_errores' => $errores, 'errores' => $err);
                }

            } else {
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guardaron los registros de marcaciones.', 'data' => $data, 'stations' => $stations, 'colors' => $colors, 'sql' => $sql);
            }
            unset($dbh);
            unset($stmt);
        }
        echo json_encode($msj);
    }

    /**
     * Función para la obtención del listado de marcaciones registradas en el sistema OASIS para un registro de relación laboral en particular.
     * @param $id_relaboral
     * @param $fecha_ini
     * @param $fecha_fin
     * @param $n_rows
     * @param $columns
     * @param $filtros
     * @param $groups
     * @param $sorteds
     */
    public function exportcontrolmarcacionexcelAction($id_relaboral, $fecha_ini, $fecha_fin, $n_rows, $columns, $filtros, $groups, $sorteds)
    {
        $this->view->disable();
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null) $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);
        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'ci' => array('title' => 'CI', 'width' => 12, 'align' => 'C', 'type' => 'varchar'),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
            'nombres' => array('title' => 'Apellidos y Nombres', 'width' => 15, 'align' => 'L', 'type' => 'varchar'),
            'nombres' => array('title' => 'Apellidos y Nombres', 'width' => 15, 'align' => 'L', 'type' => 'varchar'),
            'gestion' => array('title' => 'Gestion', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'mes_nombre' => array('title' => 'Mes', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'fecha' => array('title' => 'Fecha', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'hora' => array('title' => 'Hora', 'width' => 18, 'align' => 'C', 'type' => 'time'),
            'maquina' => array('title' => 'Maquina', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'usuario' => array('title' => 'Usuario Descarga', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'fecha_ini_rango' => array('title' => 'Fecha Ini Rango', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_fin_rango' => array('title' => 'Fecha Ini Rango', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'observacion' => array('title' => 'Observacion', 'width' => 15, 'align' => 'L', 'type' => 'varchar')
        );
        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if ($fecha_ini != '' && $fecha_fin != '' && count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            $excel = new exceloasis();
            $excel->tableWidth = $ancho;
            #region Proceso de generación del documento Excel
            $excel->debug = 0;
            $excel->title_rpt = utf8_decode('Reporte Control de Marcaciones (' . $fecha_ini . ' AL ' . $fecha_fin . ')');
            $excel->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $excel->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $excel->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $excel->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $excel->DefineTitleAligns(count($colTitleSelecteds));
            $formatTypes = $excel->DefineTypeCols($generalConfigForAllColumns, $columns, $agruparPor);
            $gruposSeleccionadosActuales = $excel->DefineDefaultValuesForGroups($groups);
            $excel->generalConfigForAllColumns = $generalConfigForAllColumns;
            $excel->colTitleSelecteds = $colTitleSelecteds;
            $excel->widthsSelecteds = $widthsSelecteds;
            $excel->alignSelecteds = $alignSelecteds;
            $excel->alignTitleSelecteds = $alignTitleSelecteds;

            $cantCol = count($colTitleSelecteds);
            $excel->ultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 1];
            $excel->penultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 2];
            $excel->numFilaCabeceraTabla = 4;
            $excel->primeraLetraCabeceraTabla = "A";
            $excel->segundaLetraCabeceraTabla = "B";
            $excel->celdaInicial = $excel->primeraLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            $excel->celdaFinal = $excel->ultimaLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            if ($cantCol <= 9) {
                $excel->defineOrientation("V");
                $excel->defineSize("C");
            } elseif ($cantCol <= 13) {
                $excel->defineOrientation("H");
                $excel->defineSize("C");
            } else {
                $excel->defineOrientation("H");
                $excel->defineSize("O");
            }
            if ($excel->debug == 1) {
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ RANGO FECHAS ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
                echo "<p>" . $fecha_ini . "-" . $fecha_fin;
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%COLUMNAS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                print_r($columns);
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%FILTROS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                print_r($filtros);
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%GRUPOS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                echo "<p>" . $groups;
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%ORDEN%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                print_r($sorteds);
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $this->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $this->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($excel->debug == 1) {
                        echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%YA CONSIDERADOS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%:<p>";
                        print_r($yaConsiderados);
                        echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%YA CONSIDERADOS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                if ($filtros[$k]['columna'] == "nombres") {
                                    $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                                } else {
                                    $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                }
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($excel->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if ($filtros[$k]['columna'] == "nombres") {
                                $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                            } else {
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            }
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Fmarcaciones();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                /*if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";*/
                if ($excel->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($excel->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($excel->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            $resul = $obj->getAllByRelaboral($id_relaboral, $fecha_ini, $fecha_fin, $where, "", null, null);
            $marcaciones = array();
            $listaExcepciones = array();
            $contador = 0;
            foreach ($resul as $v) {
                $marcaciones[] = array(
                    'id_persona' => $v->id_persona,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'gestion' => $v->gestion,
                    'mes' => $v->mes,
                    'mes_nombre' => $v->mes_nombre,
                    'fecha' => $v->fecha != "" ? date("d-m-Y", strtotime($v->fecha)) : "",
                    'hora' => $v->hora,
                    'id_maquina' => $v->id_maquina,
                    'maquina' => $v->maquina,
                    'user_reg_id' => $v->user_reg_id,
                    'usuario' => $v->usuario,
                    'fecha_reg' => $v->fecha_reg != "" ? date("d-m-Y", strtotime($v->fecha_reg)) : "",
                    'fecha_ini_rango' => $v->fecha_ini_rango != "" ? date("d-m-Y", strtotime($v->fecha_ini_rango)) : "",
                    'fecha_fin_rango' => $v->fecha_fin_rango != "" ? date("d-m-Y", strtotime($v->fecha_fin_rango)) : "",
                    'observacion' => $v->observacion
                );
            }
            #region Espacio para la definición de valores para la cabecera de la tabla
            $excel->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();
            $excel->header();
            $fila = $excel->numFilaCabeceraTabla;
            if (count($marcaciones) > 0) {
                $excel->RowTitle($colTitleSelecteds, $fila);
                $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                if ($excel->debug == 1) {
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                    print_r($marcaciones);
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                }
                foreach ($marcaciones as $i => $val) {
                    if (count($agrupadores) > 0) {
                        if ($excel->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $agr = $excel->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            if ($excel->debug == 1) {
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                                print_r($agr);
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                            }
                            $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
                            $fila++;
                            /*
                             * Si es que hay agrupadores, se inicia el conteo desde donde empieza el agrupador
                             */
                            $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                            $excel->Agrupador($agr, $fila);
                            $excel->RowTitle($colTitleSelecteds, $fila);
                        }
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $marcaciones[$j], $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;

                    } else {
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $val, $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA DATA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA DATA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;
                    }
                    $j++;
                }
                $fila--;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
            }
            $excel->ShowLeftFooter = true;
            //$excel->secondPage();
            if ($excel->debug == 0) {
                $excel->display("AppData/reporte_controlmarcaciones.xls", "I");
            }
            #endregion Proceso de generación del documento PDF
        }
    }

    /**
     * Función para efectuar la exportación del reporte de descarga de marcaciones en formato Excel.
     * @param $ci
     * @param $fecha_ini
     * @param $fecha_fin
     * @param $n_rows
     * @param $columns
     * @param $filtros
     * @param $groups
     * @param $sorteds
     */
    public function exportdescargasexcelAction($ci, $fecha_ini, $fecha_fin, $n_rows, $columns, $filtros, $groups, $sorteds)
    {
        $this->view->disable();
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null) $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);
        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'ci' => array('title' => 'CI', 'width' => 12, 'align' => 'C', 'type' => 'varchar'),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
            'nombres' => array('title' => 'Apellidos y Nombres', 'width' => 15, 'align' => 'L', 'type' => 'varchar'),
            'nombres' => array('title' => 'Apellidos y Nombres', 'width' => 15, 'align' => 'L', 'type' => 'varchar'),
            'gestion' => array('title' => 'Gestion', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'mes_nombre' => array('title' => 'Mes', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'fecha' => array('title' => 'Fecha', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'hora' => array('title' => 'Hora', 'width' => 18, 'align' => 'C', 'type' => 'time'),
            'maquina' => array('title' => 'Maquina', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'gerencia_administrativa' => array('title' => 'Gerencia', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'departamento_administrativo' => array('title' => 'Departamento', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'area' => array('title' => 'Area', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'cargo' => array('title' => 'Cargo', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'usuario' => array('title' => 'Usuario Descarga', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'fecha_ini_rango' => array('title' => 'Fecha Ini Rango', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_fin_rango' => array('title' => 'Fecha Ini Rango', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'observacion' => array('title' => 'Observacion', 'width' => 15, 'align' => 'L', 'type' => 'varchar')
        );
        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if ($fecha_ini != '' && $fecha_fin != '' && count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            $excel = new exceloasis();
            $excel->tableWidth = $ancho;
            #region Proceso de generación del documento Excel
            $excel->debug = 0;
            $excel->title_rpt = utf8_decode('Reporte Descarga Marcaciones (' . $fecha_ini . ' AL ' . $fecha_fin . ')');
            $excel->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $excel->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $excel->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $excel->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $excel->DefineTitleAligns(count($colTitleSelecteds));
            $formatTypes = $excel->DefineTypeCols($generalConfigForAllColumns, $columns, $agruparPor);
            $gruposSeleccionadosActuales = $excel->DefineDefaultValuesForGroups($groups);
            $excel->generalConfigForAllColumns = $generalConfigForAllColumns;
            $excel->colTitleSelecteds = $colTitleSelecteds;
            $excel->widthsSelecteds = $widthsSelecteds;
            $excel->alignSelecteds = $alignSelecteds;
            $excel->alignTitleSelecteds = $alignTitleSelecteds;

            $cantCol = count($colTitleSelecteds);
            $excel->ultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 1];
            $excel->penultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 2];
            $excel->numFilaCabeceraTabla = 4;
            $excel->primeraLetraCabeceraTabla = "A";
            $excel->segundaLetraCabeceraTabla = "B";
            $excel->celdaInicial = $excel->primeraLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            $excel->celdaFinal = $excel->ultimaLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            if ($cantCol <= 9) {
                $excel->defineOrientation("V");
                $excel->defineSize("C");
            } elseif ($cantCol <= 13) {
                $excel->defineOrientation("H");
                $excel->defineSize("C");
            } else {
                $excel->defineOrientation("H");
                $excel->defineSize("O");
            }
            if ($excel->debug == 1) {
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ RANGO FECHAS ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
                echo "<p>" . $fecha_ini . "-" . $fecha_fin;
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%COLUMNAS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                print_r($columns);
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%FILTROS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                print_r($filtros);
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%GRUPOS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                echo "<p>" . $groups;
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%ORDEN%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                print_r($sorteds);
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $this->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $this->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($excel->debug == 1) {
                        echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%YA CONSIDERADOS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                        print_r($yaConsiderados);
                        echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%YA CONSIDERADOS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                if ($filtros[$k]['columna'] == "nombres") {
                                    $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                                } else {
                                    $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                }
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($excel->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if ($filtros[$k]['columna'] == "nombres") {
                                $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                            } else {
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            }
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Fmarcaciones();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                /*if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";*/
                if ($excel->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($ci != '' && $ci > 0) {
                if ($where != '') $where .= " AND id_persona=(SELECT id FROM personas WHERE ci='$ci' LIMIT 1)";
                else $where .= " WHERE id_persona=(SELECT id FROM personas WHERE ci='$ci' LIMIT 1)";
            }
            if ($excel->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($excel->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            $resul = $obj->getAll($fecha_ini, $fecha_fin, $where, "");
            $marcaciones = array();
            $listaExcepciones = array();
            $contador = 0;
            foreach ($resul as $v) {
                $marcaciones[] = array(
                    'id_persona' => $v->id_persona,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'gestion' => $v->gestion,
                    'mes' => $v->mes,
                    'mes_nombre' => $v->mes_nombre,
                    'fecha' => $v->fecha != "" ? date("d-m-Y", strtotime($v->fecha)) : "",
                    'hora' => $v->hora,
                    'id_maquina' => $v->id_maquina,
                    'maquina' => $v->maquina,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'area' => $v->area,
                    'cargo' => $v->cargo,
                    'user_reg_id' => $v->user_reg_id,
                    'usuario' => $v->usuario,
                    'fecha_reg' => $v->fecha_reg != "" ? date("d-m-Y", strtotime($v->fecha_reg)) : "",
                    'fecha_ini_rango' => $v->fecha_ini_rango != "" ? date("d-m-Y", strtotime($v->fecha_ini_rango)) : "",
                    'fecha_fin_rango' => $v->fecha_fin_rango != "" ? date("d-m-Y", strtotime($v->fecha_fin_rango)) : "",
                    'observacion' => $v->observacion
                );
            }
            #region Espacio para la definición de valores para la cabecera de la tabla
            $excel->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();
            $excel->header();
            $fila = $excel->numFilaCabeceraTabla;
            if (count($marcaciones) > 0) {
                $excel->RowTitle($colTitleSelecteds, $fila);
                $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                if ($excel->debug == 1) {
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                    print_r($marcaciones);
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                }
                foreach ($marcaciones as $i => $val) {
                    if (count($agrupadores) > 0) {
                        if ($excel->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $agr = $excel->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            if ($excel->debug == 1) {
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                                print_r($agr);
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                            }
                            $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
                            $fila++;
                            /*
                             * Si es que hay agrupadores, se inicia el conteo desde donde empieza el agrupador
                             */
                            $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                            $excel->Agrupador($agr, $fila);
                            $excel->RowTitle($colTitleSelecteds, $fila);
                        }
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $marcaciones[$j], $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;

                    } else {
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $val, $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA DATA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA DATA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;
                    }
                    $j++;
                }
                $fila--;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
            }
            $excel->ShowLeftFooter = true;
            //$excel->secondPage();
            if ($excel->debug == 0) {
                $excel->display("AppData/reporte_marcaciones.xls", "I");
            }
            #endregion Proceso de generación del documento PDF
        }
    }

    /**
     * Función para efectural la exportación del reporte de marcaciones registradas en el sistema OASIS
     * para un persona identificada mediante el registro de relación laboral en formato PDF.
     * @param $id_relaboral
     * @param $fecha_ini
     * @param $fecha_fin
     * @param $n_rows
     * @param $columns
     * @param $filtros
     * @param $groups
     * @param $sorteds
     */
    public function exportcontrolmarcacionpdfAction($id_relaboral, $fecha_ini, $fecha_fin, $n_rows, $columns, $filtros, $groups, $sorteds)
    {
        $this->view->disable();
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null) $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);

        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'nombres' => array('title' => 'Nombres', 'width' => 40, 'align' => 'L', 'type' => 'varchar'),
            'ci' => array('title' => 'CI', 'width' => 12, 'align' => 'C', 'type' => 'varchar'),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
            'gestion' => array('title' => 'Gestion', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'mes_nombre' => array('title' => 'Mes', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'fecha' => array('title' => 'Fecha', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'hora' => array('title' => 'Hora', 'width' => 18, 'align' => 'C', 'type' => 'time'),
            'maquina' => array('title' => 'Maquina', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'usuario' => array('title' => 'Usuario', 'width' => 40, 'align' => 'L', 'type' => 'varchar'),
            'fecha_ini_rango' => array('title' => 'Ini Rango', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_fin_rango' => array('title' => 'Fin Rango', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'observacion' => array('title' => 'Obs.', 'width' => 15, 'align' => 'L', 'type' => 'varchar')
        );
        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if (count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            if ($ancho > 215.9) {
                if ($ancho > 270) {
                    $pdf = new pdfoasis('L', 'mm', 'Legal');
                    $pdf->pageWidth = 355;
                } else {
                    $pdf = new pdfoasis('L', 'mm', 'Letter');
                    $pdf->pageWidth = 280;
                }
            } else {
                $pdf = new pdfoasis('P', 'mm', 'Letter');
                $pdf->pageWidth = 215.9;
            }
            $pdf->tableWidth = $ancho;
            #region Proceso de generación del documento PDF
            $pdf->debug = 0;
            $pdf->title_rpt = utf8_decode('Reporte Control de Marcaciones (' . $fecha_ini . ' - ' . $fecha_fin . ')');
            $pdf->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $pdf->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $pdf->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $pdf->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $pdf->DefineTitleAligns(count($colTitleSelecteds));
            $gruposSeleccionadosActuales = $pdf->DefineDefaultValuesForGroups($groups);
            $pdf->generalConfigForAllColumns = $generalConfigForAllColumns;
            $pdf->colTitleSelecteds = $colTitleSelecteds;
            $pdf->widthsSelecteds = $widthsSelecteds;
            $pdf->alignSelecteds = $alignSelecteds;
            $pdf->alignTitleSelecteds = $alignTitleSelecteds;
            if ($pdf->debug == 1) {
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%COLUMNAS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                print_r($columns);
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%FILTROS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                print_r($filtros);
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%GRUPOS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                echo "<p>" . $groups;
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%ORDEN%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                print_r($sorteds);
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $this->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $this->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($pdf->debug == 1) {
                        echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%YA CONSIDERADOS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                        print_r($yaConsiderados);
                        echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%YA CONSIDERADOS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                if ($filtros[$k]['columna'] == "nombres") {
                                    $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                                } else {
                                    $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                }
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($pdf->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if ($filtros[$k]['columna'] == "nombres") {
                                $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                            } else {
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            }
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Fmarcaciones();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                /*if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";*/
                if ($pdf->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($pdf->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($pdf->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            $resul = $obj->getAllByRelaboral($id_relaboral, $fecha_ini, $fecha_fin, $where, "", null, null);
            $marcaciones = array();
            foreach ($resul as $v) {
                $marcaciones[] = array(
                    'id_persona' => $v->id_persona,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'gestion' => $v->gestion,
                    'mes' => $v->mes,
                    'mes_nombre' => $v->mes_nombre,
                    'fecha' => $v->fecha != "" ? date("d-m-Y", strtotime($v->fecha)) : "",
                    'hora' => $v->hora,
                    'id_maquina' => $v->id_maquina,
                    'maquina' => $v->maquina,
                    'user_reg_id' => $v->user_reg_id,
                    'usuario' => $v->usuario,
                    'fecha_reg' => $v->fecha_reg != "" ? date("d-m-Y", strtotime($v->fecha_reg)) : "",
                    'fecha_ini_rango' => $v->fecha_ini_rango != "" ? date("d-m-Y", strtotime($v->fecha_ini_rango)) : "",
                    'fecha_fin_rango' => $v->fecha_fin_rango != "" ? date("d-m-Y", strtotime($v->fecha_fin_rango)) : "",
                    'observacion' => $v->observacion
                );
            }

            /**
             * Si el ancho supera el establecido para una hoja tamaño carta, se la pone en posición horizontal
             */

            $pdf->AddPage();
            if ($pdf->debug == 1) {
                echo "<p>El ancho es:: " . $ancho;
            }
            #region Espacio para la definición de valores para la cabecera de la tabla
            $pdf->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            //echo "<p>++++++++++>".$groups;
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();

            if (count($marcaciones) > 0) {
                foreach ($marcaciones as $i => $val) {
                    if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                    if (count($agrupadores) > 0) {
                        if ($pdf->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $pdf->Ln();
                            $pdf->DefineColorHeaderTable();
                            $pdf->SetAligns($alignTitleSelecteds);
                            //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                            $agr = $pdf->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            $pdf->Agrupador($agr);
                            $pdf->RowTitle($colTitleSelecteds);
                        }
                        $pdf->DefineColorBodyTable();
                        if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                        $rowData = $pdf->DefineRows($j + 1, $marcaciones[$j], $colSelecteds);
                        $pdf->SetAligns($alignSelecteds);
                        $pdf->Row($rowData);
                    } else {
                        if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                        $pdf->DefineColorBodyTable();
                        $rowData = $pdf->DefineRows($j + 1, $val, $colSelecteds);
                        $pdf->SetAligns($alignSelecteds);
                        $pdf->Row($rowData);
                    }
                    //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                    $j++;
                }
            }
            $pdf->ShowLeftFooter = true;
            if ($pdf->debug == 0) $pdf->Output('reporte_controlmarcaciones.pdf', 'I');
            #endregion Proceso de generación del documento PDF
        }
    }

    /**
     * Función para efectuar la descarga del reporte de descarga de marcaciones en formato PDF.
     * @param $ci
     * @param $fecha_ini
     * @param $fecha_fin
     * @param $n_rows
     * @param $columns
     * @param $filtros
     * @param $groups
     * @param $sorteds
     */
    public function exportdescargaspdfAction($ci, $fecha_ini, $fecha_fin, $n_rows, $columns, $filtros, $groups, $sorteds)
    {
        $this->view->disable();
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null) $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);

        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'nombres' => array('title' => 'Nombres', 'width' => 40, 'align' => 'L', 'type' => 'varchar'),
            'ci' => array('title' => 'CI', 'width' => 12, 'align' => 'C', 'type' => 'varchar'),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
            'gestion' => array('title' => 'Gestion', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'mes_nombre' => array('title' => 'Mes', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'fecha' => array('title' => 'Fecha', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'hora' => array('title' => 'Hora', 'width' => 18, 'align' => 'C', 'type' => 'time'),
            'maquina' => array('title' => 'Maquina', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'gerencia_administrativa' => array('title' => 'Gerencia', 'width' => 25, 'align' => 'C', 'type' => 'varchar'),
            'departamento_administrativo' => array('title' => 'Departamento', 'width' => 25, 'align' => 'C', 'type' => 'varchar'),
            'area' => array('title' => 'Area', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'cargo' => array('title' => 'Cargo', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'usuario' => array('title' => 'Usuario', 'width' => 40, 'align' => 'L', 'type' => 'varchar'),
            'fecha_ini_rango' => array('title' => 'Ini Rango', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_fin_rango' => array('title' => 'Fin Rango', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'observacion' => array('title' => 'Obs.', 'width' => 15, 'align' => 'L', 'type' => 'varchar')
        );
        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if (count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            if ($ancho > 215.9) {
                if ($ancho > 270) {
                    $pdf = new pdfoasis('L', 'mm', 'Legal');
                    $pdf->pageWidth = 355;
                } else {
                    $pdf = new pdfoasis('L', 'mm', 'Letter');
                    $pdf->pageWidth = 280;
                }
            } else {
                $pdf = new pdfoasis('P', 'mm', 'Letter');
                $pdf->pageWidth = 215.9;
            }
            $pdf->tableWidth = $ancho;
            #region Proceso de generación del documento PDF
            $pdf->debug = 0;
            $pdf->title_rpt = utf8_decode('Reporte Descarga de Marcaciones');
            $pdf->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $pdf->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $pdf->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $pdf->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $pdf->DefineTitleAligns(count($colTitleSelecteds));
            $gruposSeleccionadosActuales = $pdf->DefineDefaultValuesForGroups($groups);
            $pdf->generalConfigForAllColumns = $generalConfigForAllColumns;
            $pdf->colTitleSelecteds = $colTitleSelecteds;
            $pdf->widthsSelecteds = $widthsSelecteds;
            $pdf->alignSelecteds = $alignSelecteds;
            $pdf->alignTitleSelecteds = $alignTitleSelecteds;
            if ($pdf->debug == 1) {
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%COLUMNAS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                print_r($columns);
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%FILTROS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                print_r($filtros);
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%GRUPOS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                echo "<p>" . $groups;
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%ORDEN%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                print_r($sorteds);
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $this->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $this->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($pdf->debug == 1) {
                        echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%YA CONSIDERADOS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                        print_r($yaConsiderados);
                        echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%YA CONSIDERADOS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                if ($filtros[$k]['columna'] == "nombres") {
                                    $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                                } else {
                                    $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                }
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($pdf->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if ($filtros[$k]['columna'] == "nombres") {
                                $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                            } else {
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            }
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Fmarcaciones();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                /*if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";*/
                if ($pdf->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($ci != '' && $ci > 0) {
                if ($where != '') $where .= " AND id_persona=(SELECT id FROM personas WHERE ci='$ci' LIMIT 1)";
                else $where .= " WHERE id_persona=(SELECT id FROM personas WHERE ci='$ci' LIMIT 1)";
            }
            if ($pdf->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($pdf->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            $resul = $obj->getAll($fecha_ini, $fecha_fin, $where, "");
            $marcaciones = array();
            foreach ($resul as $v) {
                $marcaciones[] = array(
                    'id_persona' => $v->id_persona,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'gestion' => $v->gestion,
                    'mes' => $v->mes,
                    'mes_nombre' => $v->mes_nombre,
                    'fecha' => $v->fecha != "" ? date("d-m-Y", strtotime($v->fecha)) : "",
                    'hora' => $v->hora,
                    'id_maquina' => $v->id_maquina,
                    'maquina' => $v->maquina,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'area' => $v->area,
                    'cargo' => $v->cargo,
                    'user_reg_id' => $v->user_reg_id,
                    'usuario' => $v->usuario,
                    'fecha_reg' => $v->fecha_reg != "" ? date("d-m-Y", strtotime($v->fecha_reg)) : "",
                    'fecha_ini_rango' => $v->fecha_ini_rango != "" ? date("d-m-Y", strtotime($v->fecha_ini_rango)) : "",
                    'fecha_fin_rango' => $v->fecha_fin_rango != "" ? date("d-m-Y", strtotime($v->fecha_fin_rango)) : "",
                    'observacion' => $v->observacion
                );
            }

            /**
             * Si el ancho supera el establecido para una hoja tamaño carta, se la pone en posición horizontal
             */

            $pdf->AddPage();
            if ($pdf->debug == 1) {
                echo "<p>El ancho es:: " . $ancho;
            }
            #region Espacio para la definición de valores para la cabecera de la tabla
            $pdf->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            //echo "<p>++++++++++>".$groups;
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();

            if (count($marcaciones) > 0) {
                foreach ($marcaciones as $i => $val) {
                    if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                    if (count($agrupadores) > 0) {
                        if ($pdf->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $pdf->Ln();
                            $pdf->DefineColorHeaderTable();
                            $pdf->SetAligns($alignTitleSelecteds);
                            //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                            $agr = $pdf->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            $pdf->Agrupador($agr);
                            $pdf->RowTitle($colTitleSelecteds);
                        }
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                        $rowData = $pdf->DefineRows($j + 1, $marcaciones[$j], $colSelecteds);
                        $pdf->Row($rowData);

                    } else {
                        //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        $rowData = $pdf->DefineRows($j + 1, $val, $colSelecteds);
                        $pdf->Row($rowData);
                    }
                    //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                    $j++;
                }
            }
            $pdf->ShowLeftFooter = true;
            if ($pdf->debug == 0) $pdf->Output('reporte_marcaciones.pdf', 'I');
            #endregion Proceso de generación del documento PDF
        }
    }

    /**
     * Función para la generación del array con los anchos de columna definido, en consideración a las columnas mostradas.
     * @param $generalWiths Array de los anchos y alineaciones de columnas disponibles.
     * @param $columns Array de las columnas con las propiedades de oculto (hidden:1) y visible (hidden:null).
     * @return array Array con el listado de anchos por columna a desplegarse.
     */
    function DefineWidths($widthAlignAll, $columns, $exclude = array())
    {
        $arrRes = Array();
        $arrRes[] = 8;
        foreach ($columns as $key => $val) {
            if (isset($widthAlignAll[$key])) {
                if (!isset($val['hidden']) || $val['hidden'] != true) {
                    if (!in_array($key, $exclude) || count($exclude) == 0)
                        $arrRes[] = $widthAlignAll[$key]['width'];
                }
            }
        }
        return $arrRes;
    }

    /*
     * Función para obtener la cantidad de veces que se considera una misma columna en el filtrado.
     * @param $columna
     * @param $array
     * @return int
     */
    function obtieneCantidadVecesConsideracionPorColumnaEnFiltros($columna, $array)
    {
        $cont = 0;
        if (count($array) >= 1) {
            foreach ($array as $key => $val) {
                if (in_array($columna, $val)) {
                    $cont++;
                }
            }
        }
        return $cont;
    }

    /**
     * Función para la obtención de los valores considerados en el filtro enviado.
     * @param $columna Nombre de la columna
     * @param $array Array con los registro de busquedas.
     * @return array Array con los valores considerados en el filtrado enviado.
     */
    function obtieneValoresConsideradosPorColumnaEnFiltros($columna, $array)
    {
        $arr_col = array();
        $cont = 0;
        if (count($array) >= 1) {
            foreach ($array as $key => $val) {
                if (in_array($columna, $val)) {
                    $arr_col[] = $val["valor"];
                }
            }
        }
        return $arr_col;
    }

    /**
     * Función para la obtención de la cantidad de meses transcurridos entre dos fechas.
     */
    function cantidadmesesentrefechasAction()
    {
        $this->view->disable();
        /**
         *  Consideración: La función getCantidadMesesEntreDosFechas sólo se ejecuta correctamente cuando en el método initialize de la clase Fmarcaciones
         *  se establece: $this->_db = new Fmarcaciones();
         */
        $obj = new Fmarcaciones();
        $resultado = 0;
        if (isset($_POST["fecha_ini"]) && isset($_POST["fecha_fin"])) {
            if ($_POST["fecha_ini"] != '' && $_POST["fecha_fin"] != '' && $_POST["fecha_ini"] != $_POST["fecha_fin"]) {
                $resul = $obj->getCantidadMesesEntreDosFechas($_POST["fecha_ini"], $_POST["fecha_fin"]);
                if (is_object($resul)) {
                    $resultado = $resul[0]->resultado;
                }
            } elseif ($_POST["fecha_ini"] != '' && $_POST["fecha_fin"] != '' && $_POST["fecha_ini"] == $_POST["fecha_fin"]) {
                $resultado = 1;
            }
        }
        echo $resultado;
    }

    /**
     * Función para la exportación de modo global o total de marcaciones, de tal manera de no restringir sólo
     * a aquellos registros de personas registradas en el sistema.
     * @param $id_relaboral
     * @param $fecha_ini
     * @param $fecha_fin
     * @param $n_rows
     * @param $columns
     * @param $filtros
     * @param $groups
     * @param $sorteds
     */
    public function exportcontrolmarcaciontodoexcelAction($id_relaboral, $fecha_ini, $fecha_fin, $n_rows, $columns, $filtros, $groups, $sorteds)
    {
        $this->view->disable();
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null) $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);
        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'ci' => array('title' => 'CI', 'width' => 12, 'align' => 'C', 'type' => 'varchar'),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
            'nombres' => array('title' => 'Apellidos y Nombres', 'width' => 15, 'align' => 'L', 'type' => 'varchar'),
            'nombres' => array('title' => 'Apellidos y Nombres', 'width' => 15, 'align' => 'L', 'type' => 'varchar'),
            'gestion' => array('title' => 'Gestion', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'mes_nombre' => array('title' => 'Mes', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'fecha' => array('title' => 'Fecha', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'hora' => array('title' => 'Hora', 'width' => 18, 'align' => 'C', 'type' => 'time'),
            'maquina' => array('title' => 'Maquina', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'usuario' => array('title' => 'Usuario Descarga', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'fecha_ini_rango' => array('title' => 'Fecha Ini Rango', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_fin_rango' => array('title' => 'Fecha Ini Rango', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'observacion' => array('title' => 'Observacion', 'width' => 15, 'align' => 'L', 'type' => 'varchar')
        );
        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if ($fecha_ini != '' && $fecha_fin != '' && count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            $excel = new exceloasis();
            $excel->tableWidth = $ancho;
            #region Proceso de generación del documento Excel
            $excel->debug = 0;
            $excel->title_rpt = utf8_decode('Reporte Control de Marcaciones (' . $fecha_ini . ' AL ' . $fecha_fin . ')');
            $excel->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $excel->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $excel->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $excel->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $excel->DefineTitleAligns(count($colTitleSelecteds));
            $formatTypes = $excel->DefineTypeCols($generalConfigForAllColumns, $columns, $agruparPor);
            $gruposSeleccionadosActuales = $excel->DefineDefaultValuesForGroups($groups);
            $excel->generalConfigForAllColumns = $generalConfigForAllColumns;
            $excel->colTitleSelecteds = $colTitleSelecteds;
            $excel->widthsSelecteds = $widthsSelecteds;
            $excel->alignSelecteds = $alignSelecteds;
            $excel->alignTitleSelecteds = $alignTitleSelecteds;

            $cantCol = count($colTitleSelecteds);
            $excel->ultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 1];
            $excel->penultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 2];
            $excel->numFilaCabeceraTabla = 4;
            $excel->primeraLetraCabeceraTabla = "A";
            $excel->segundaLetraCabeceraTabla = "B";
            $excel->celdaInicial = $excel->primeraLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            $excel->celdaFinal = $excel->ultimaLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            if ($cantCol <= 9) {
                $excel->defineOrientation("V");
                $excel->defineSize("C");
            } elseif ($cantCol <= 13) {
                $excel->defineOrientation("H");
                $excel->defineSize("C");
            } else {
                $excel->defineOrientation("H");
                $excel->defineSize("O");
            }
            if ($excel->debug == 1) {
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ RANGO FECHAS ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
                echo "<p>" . $fecha_ini . "-" . $fecha_fin;
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%COLUMNAS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                print_r($columns);
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%FILTROS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                print_r($filtros);
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%GRUPOS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                echo "<p>" . $groups;
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%ORDEN%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                print_r($sorteds);
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $this->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $this->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($excel->debug == 1) {
                        echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%YA CONSIDERADOS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                        print_r($yaConsiderados);
                        echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%YA CONSIDERADOS%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                if ($filtros[$k]['columna'] == "nombres") {
                                    $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                                } else {
                                    $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                }
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($excel->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if ($filtros[$k]['columna'] == "nombres") {
                                $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                            } else {
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            }
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Fmarcaciones();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                /*if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";*/
                if ($excel->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($excel->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($excel->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            $resul = $obj->getAllByRelaboral($id_relaboral, $fecha_ini, $fecha_fin, $where, "", null, null);
            $marcaciones = array();
            $listaExcepciones = array();
            $contador = 0;
            foreach ($resul as $v) {
                $marcaciones[] = array(
                    'id_persona' => $v->id_persona,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'gestion' => $v->gestion,
                    'mes' => $v->mes,
                    'mes_nombre' => $v->mes_nombre,
                    'fecha' => $v->fecha != "" ? date("d-m-Y", strtotime($v->fecha)) : "",
                    'hora' => $v->hora,
                    'id_maquina' => $v->id_maquina,
                    'maquina' => $v->maquina,
                    'user_reg_id' => $v->user_reg_id,
                    'usuario' => $v->usuario,
                    'fecha_reg' => $v->fecha_reg != "" ? date("d-m-Y", strtotime($v->fecha_reg)) : "",
                    'fecha_ini_rango' => $v->fecha_ini_rango != "" ? date("d-m-Y", strtotime($v->fecha_ini_rango)) : "",
                    'fecha_fin_rango' => $v->fecha_fin_rango != "" ? date("d-m-Y", strtotime($v->fecha_fin_rango)) : "",
                    'observacion' => $v->observacion
                );
            }
            #region Espacio para la definición de valores para la cabecera de la tabla
            $excel->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();
            $excel->header();
            $fila = $excel->numFilaCabeceraTabla;
            if (count($marcaciones) > 0) {
                $excel->RowTitle($colTitleSelecteds, $fila);
                $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                if ($excel->debug == 1) {
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                    print_r($marcaciones);
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                }
                foreach ($marcaciones as $i => $val) {
                    if (count($agrupadores) > 0) {
                        if ($excel->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $agr = $excel->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            if ($excel->debug == 1) {
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                                print_r($agr);
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                            }
                            $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
                            $fila++;
                            /*
                             * Si es que hay agrupadores, se inicia el conteo desde donde empieza el agrupador
                             */
                            $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                            $excel->Agrupador($agr, $fila);
                            $excel->RowTitle($colTitleSelecteds, $fila);
                        }
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $marcaciones[$j], $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;

                    } else {
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $val, $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA DATA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA DATA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;
                    }
                    $j++;
                }
                $fila--;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
            }
            $excel->ShowLeftFooter = true;
            //$excel->secondPage();
            if ($excel->debug == 0) {
                $excel->display("AppData/reporte_controlmarcaciones.xls", "I");
            }
            #endregion Proceso de generación del documento PDF
        }
    }
}