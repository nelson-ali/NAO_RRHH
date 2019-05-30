<?php
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Pasantes extends \Phalcon\Mvc\Model
{
	private $_db;
	

	public function lista()
	{
		$sql="SELECT p.*,pa.valor_1 as universidad
		FROM pasantes p
		INNER JOIN parametros pa ON CAST(pa.nivel AS INTEGER)=p.universidad_id  AND pa.parametro='pasantes_universidad'
		WHERE p.baja_logica = 1
		ORDER BY p.app,p.apm,p.nombre";
		$this->_db = new Pasantes();
		return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
	}


}
