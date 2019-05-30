<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  05-01-2016
*/
require_once('libs/nusoap/nusoap.php');
//require_once('conexionpsql.php');

function verificar($user,$pass){
	$conexion = new conexionpsql();
	$con = $conexion->conectar();
	if($con){
		$pass = hash_hmac('sha256', $pass, '2, 4, 6, 7, 9, 15, 20, 23, 25, 30');
		$result = pg_query($con, "SELECT * FROM usuarios WHERE username = '$user' AND password='$pass' AND habilitado = 1");
		if (pg_num_rows($result)>0) {
			return 1;
		}else{
			return 0;
		}
		$conexion->destruir();
	}else{
		return -1;
	}
}

function getdatos($username) {
	$conexion = new conexionpsql();
	$con = $conexion->conectar();
	$datos = array();
	if($con){
		$result = pg_query($con, "SELECT * FROM f_relaborales_ultima_movilidad_por_id_persona((SELECT persona_id FROM usuarios where username = '$username')) where estado>=1;");
		while ($row = pg_fetch_assoc($result)) {
			$datos = array(
		'id_persona'=>$row['id_persona'],
		'nombres'=>$row['nombres'],
		'ci'=>$row['ci'],
		'exp'=>$row['exp'],
		'fecha_nac'=>$row['fecha_nac'],
		'genero'=>$row['genero'],
		'cargo'=>$row['cargo'],
		'departamento_administrativo'=>$row['departamento_administrativo']
		);
		}
		return $datos;
		$conexion->destruir();
	}else{
		return $datos;
	}
	
}

function datos($user) {
	return $user;
}


$namespace = "http://192.168.10.158/wsservicios/wsoperacion1.php";
$server = new soap_server();
$server->configureWSDL("verificar");
$server->wsdl->schemaTargetNamespace = $namespace;



$server->wsdl->addComplexType(
    'Usuarios',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id_persona' => array('name' => 'id_persona', 'type' => 'xsd:int'),
        'nombres' => array('name' => 'nombres', 'type' => 'xsd:string'),
        'ci' => array('name' => 'ci', 'type' => 'xsd:string'),
        'exp' => array('name' => 'exp', 'type' => 'xsd:string'),
        'fecha_nac' => array('name' => 'fecha_nac', 'type' => 'xsd:string'),
        'genero' => array('name' => 'genero', 'type' => 'xsd:string'),
        'cargo' => array('name' => 'cargo', 'type' => 'xsd:string'),
        'departamento_administrativo' => array('name' => 'departamento_administrativo', 'type' => 'xsd:string')
    )
);

// register the class method and the params of the method
$server->register("verificar"                       
                 ,array('user'=>'xsd:string','pass'=>'xsd:string')
                 ,array('return'=>'xsd:int')
                 ,$namespace
                 ,false
                 ,'rpc'
                 ,'encoded'
                 ,'Verifica si el usario existe en rrhh' 
                                );

//this is the second webservice entry point/function 
$server->register("getdatos"                       
                 ,array('user'=>'xsd:string')
                 ,array('return'=>'tns:Usuarios')
                 ,$namespace
                 ,false
                 ,'rpc'
                 ,'encoded'
                 ,'Verifica si el usario existe en rrhh' 
                                );

// Get our posted data if the service is being consumed
// otherwise leave this data blank.                
$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
// pass our posted data (or nothing) to the soap service                    
$server->service($POST_DATA);



?>