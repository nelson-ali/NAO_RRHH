<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  18-01-2016
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Bonos  extends \Phalcon\Mvc\Model {
    private $_db;

    /**
     * Función para la obtención del cálculo para el bono de antigüedad, en función de los parámetros enviados.
     * @param $anios
     * @param $diasEfectivos
     * @return int
     */
    public function getCalculoBonoAntiguedad($anios,$diasEfectivos)
    {
        if($anios>=0&&$diasEfectivos>0){
            $sql = "SELECT f_calcula_bono_antiguedad AS o_resultado FROM f_calcula_bono_antiguedad($anios,$diasEfectivos)";
            $this->_db = new Bonos();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if(count($arr)>0)return $arr[0]->o_resultado;
        }
        return -3;
    }
}