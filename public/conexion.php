<?php
$DBHOST = "localhost";$DBUSER = "user_rrhh";$DBPSW = "pass_rrhh";$DBNAME = "bd_rrhh_publicado";
$MSQLSERVER = "192.168.1.15";$MSQLUSER = "sa";$MSQLPSW = "Sistemas";$MSSQLDB = "BiometricoK30";
//$db = Conexion();
$dbms = ConexionMSql();
ShowAllMarcaciones();
/**
 * Función para la conexión con MySQL
 * @return resource
 */
function Conexion() {
    global $DBHOST, $DBUSER, $DBPSW, $DBNAME;
    /*$cn = pg_connect ( $DBHOST, $DBUSER, $DBPSW );
    if (! $cn) {
        echo "Error al efectuar la conexi&oacute;n";
        exit ();
    }
    //$bd = pg_select_db ( $DBNAME, $cn );
    return ($cn);*/
    // Conectando y seleccionado la base de datos
    $dbconn = pg_connect("host=localhost dbname=bd_rrhh user=user_rrhh password=pass_rrhh")
    or die('No se ha podido conectar: ' . pg_last_error());

// Realizando una consulta SQL
    $query = 'SELECT * FROM relaborales';
    $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

// Imprimiendo los resultados en HTML
    echo "<table>\n";
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        echo "\t<tr>\n";
        foreach ($line as $col_value) {
            echo "\t\t<td>$col_value</td>\n";
        }
        echo "\t</tr>\n";
    }
    echo "</table>\n";

// Liberando el conjunto de resultados
    pg_free_result($result);

// Cerrando la conexión
    pg_close($dbconn);
}
/**
 * Función para la conexión con SQL Server
 * @return false|resource
 */
function ConexionMSql(){
    global $MSQLSERVER, $MSQLUSER, $MSQLPSW, $MSSQLDB;
    //$MSQLSERVER = "192.168.10.40";$MSQLUSER = "sa";$MSQLPSW = "Sistemas2015";$MSSQLDB = "asistencia";
    /* Array asociativo con la información de la conexion */
    $connectionInfo = array( "UID"=>$MSQLUSER,"PWD"=>$MSQLPSW,"Database"=>$MSSQLDB);

    /* Nos conectamos mediante la autenticación de SQL Server . */
    $conn = sqlsrv_connect( $MSQLSERVER, $connectionInfo);
    if( $conn === false )
    {
        die ( "Falla en la conexion..." );
        die( print_r( sqlsrv_errors(), true));
    }
    return $conn;
}
/**
 * Funci�n para listar todos los cargos de todas las certificaciones de la gesti�n 2011 (dato modificable).
 */
function ShowAllMarcaciones() {
    $msql = ConexionMSql();
    //$tsql = "[SP_RPT_MARCACIONES_BIOMETRICOS]0,0,0,'21-01-2015','22-01-2015'";
    $tsql = "[SP_RPT_MARCACIONES_BIOMETRICOS]";
    $stmt = sqlsrv_query( $msql, $tsql);
    if( $stmt === false )
    {
        echo "Error al ejecutar consulta.</br>";
        die( print_r( sqlsrv_errors(), true));
    }
    $con = 1;
    $vocales = array ("Á", "É", "Í", "Ó", "Ú", "Ñ" );
    $vocales2 = array ("A", "E", "I", "O", "U", "N" );
    echo "<table><tr><td>Nro.</td><td>UBICACION</td><td>NOMBRES</td><td>CI</td><td>CARGO</td><td>FECHA</td><td>HORA</td><td>CODIGO MAQUINA</td><td>ESTACION</td>";
    while ( $result = sqlsrv_fetch_array ($stmt) ) {
        echo "<tr><td class='opcion'>" . $con . "</td>";
        echo "<td class='opcion'>" . $result ["UBICACION"] ."</td>";
        echo "<td class='opcion'>" . $result ["NOMBRES"] . "</td>";
        echo "<td class='opcion'>" . $result ["CI"] . "</td>";
        echo "<td class='opcion'>" . str_replace ( $vocales, $vocales2, strtoupper ( $result ['CARGO'] ) ) ."</td>";
        echo "<td class='opcion'>" . $result ["MARCACION_FECHA"] . "</td>";
        echo "<td class='opcion'>" . $result ["MARCACION_HORA"] . "</td>";
        echo "<td class='opcion'>" . $result ["CODIGO_MAQUINA"] . "</td>";
        echo "<td class='opcion'>" . $result ["ESTACION"] . "</td>";
        echo "</td></tr>";
        $con ++;
    }
    echo "</tr></table>";
    sqlsrv_free_stmt( $stmt);
    sqlsrv_close( $msql);
}
/**
 * Función para cargar una tabla de acuerdo a consulta a procedimiento del sistema de control biométrico
 */
function cargaMarcaciones(&$db) {
    $msql = ConexionMSql();
    $sqly = "TRUNCATE marcaciones";
    $tsql = "[SP_RPT_MARCACIONES_BIOMETRICOS]0,0,0,'21-01-2015','22-01-2015'";
    $stmt = sqlsrv_query( $msql, $tsql);
    if( $stmt === false )
    {
        echo "Error al ejecutar consulta.</br>";
        die( print_r( sqlsrv_errors(), true));
    }
    $vocales = array ("Á", "É", "Í", "Ó", "Ú", "Ñ" );
    $vocales2 = array ("A", "E", "I", "O", "U", "N" );
    while ( $result = sqlsrv_fetch_array ($stmt) ) {
        if ($result ['NOMBRE'] != ""){//Nombre del proyecto
            $sqlins = "INSERT INTO plan_contrataciones VALUES(NULL,
				 '" . $result ['GESTION'] . "'";
            $sqlins.=",'0000-00-00',";
            $sqlins.="'" . $result ['COD_CARGO'] . "',
				 '" . $result ["GLOSA_CARGO"] . "','" . $result ["DESCRIPCION_CARGO"] . "',
				 " . $result ['CANTIDAD'] . "," . $result ['COSTO_UNITARIO'] . ",
				 " . $result ['COSTO_TOTAL'] . ",'" . $result ['NRO_CERT'] . "',
			     " . $result ['COD_DA'] . ",'" . $result ['DA'] . "'," . $result ['COD_UE'] . ",
			     '" . $result ['UE'] . "'," . $result ['PROYECTO'] . ",
			     '" .str_replace ( $vocales, $vocales2, strtoupper ($result ['NOMBRE'])). "'," . $result ['FUENTE'] . ",
			     " . $result ['ORGANISMO'] . ",'" . str_replace ( $vocales, $vocales2, strtoupper ($result ['ORGANISMO_DESC'])) . "'," . $result ['ID_PARTIDA'] . ",
			     " . $result ['ENE'] . ",'" . $result ['FEB'] . "',
			     " . $result ['MAR'] . ",'" . $result ['ABR'] . "',
			     " . $result ['MAY'] . ",'" . $result ['JUN'] . "',
			     " . $result ['JUL'] . ",'" . $result ['AGO'] . "',
			     " . $result ['SEP'] . ",'" . $result ['OCT'] . "',
			     " . $result ['NOV'] . ",'" . $result ['DIC'] . "',
			     " . $result ['CERT_ENE'] . ",'" . $result ['CERT_FEB'] . "',
			     " . $result ['CERT_MAR'] . ",'" . $result ['CERT_ABR'] . "',
			     " . $result ['CERT_MAY'] . ",'" . $result ['CERT_JUN'] . "',
			     " . $result ['CERT_JUL'] . ",'" . $result ['CERT_AGO'] . "',
			     " . $result ['CERT_SEP'] . ",'" . $result ['CERT_OCT'] . "',
			     " . $result ['CERT_NOV'] . ",'" . $result ['CERT_DIC'] . "')";
            if (! $db->Execute ( $sqlins ))
                echo "<p>" . $sqlins;
        }
    }
}

?>