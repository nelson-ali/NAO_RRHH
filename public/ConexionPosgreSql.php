<?php
$config = array(
"host" => "192.168.10.158",
"dbname" => "bd_rrhh",
"username" => "postgres",
"password" => "miteleferico123"
);
$connection = new Phalcon\Db\Adapter\Pdo\Postgresql($config);
$robot = $connection->fetchAll("SELECT * FROM f_relaborales()");
print_r($robot);
?>