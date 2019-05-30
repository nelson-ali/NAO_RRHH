<?php

/**
 *   Oasis - Sistema de Gesti�n para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Telef�rico"
 *   Versi�n:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creaci�n:  10-06-2016
 */

class Usuariosservicios extends \Phalcon\Mvc\Model
{
    /**
     * Función para la generación del token para el uso del sistema.
     * @param string $algoritmo
     * @param string $imei
     * @param string $key
     * @return string
     */
    public function generaToken($algoritmo = "sha256", $imei = "", $key = "")
    {
        $token = "";
        if ($imei != "") {
            $token = hash_hmac($algoritmo, $imei, $key);
        }
        return $token;
    }

    /**
     * Función para retornar una fecha con la adición de días, horas, minutos y/o segundos definidos en los parámetros.
     * @param string $fecha
     * @param int $diasLimite
     * @param int $horasLimite
     * @param int $minutosLimite
     * @param int $segundosLimite
     * @return false|int|string
     */
    public function sumaFechasHorasdd($fecha = "", $diasLimite = 0, $horasLimite = 0, $minutosLimite = 0, $segundosLimite = 0)
    {
        if ($fecha != "") {
            $sql = "SELECT (CAST('" . $fecha . "' AS TIMESTAMP WITHOUT TIME ZONE)  ";
            if ($diasLimite > 0) {
                $sql .= "+ INTERVAL '" . $diasLimite . " days'";
            }
            if ($horasLimite > 0) {
                $sql .= "+ INTERVAL '" . $horasLimite . " hours'";
            }
            if ($minutosLimite > 0) {
                $sql .= "+ INTERVAL '" . $minutosLimite . " minutes'";
            }
            if ($segundosLimite > 0) {
                $sql .= "+ INTERVAL '" . $segundosLimite . " seconds'";
            }
            $sql .= ") AS o_resultado";
            $this->_db = new Usuariosservicios();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (count($arr) > 0) {
                return $arr[0]->o_resultado;
            }
        }
        return $fecha;
    }

    /**
     * Función para sumar días, horas, minutos y segundos a una fecha determinada.
     * @param string $fecha
     * @param int $diasLimite
     * @param int $horasLimite
     * @param int $minutosLimite
     * @param int $segundosLimite
     * @return false|int|string
     */
    public function sumaFechasHoras($fecha = "", $diasLimite = 0, $horasLimite = 0, $minutosLimite = 0, $segundosLimite = 0)
    {
        if ($fecha != "") {
            $sw = 0;
            if ($diasLimite > 0) {
                $sw = 1;
                $hL = intval($diasLimite * 24);
                $fecha = strtotime('+' . $hL . ' hour', strtotime($fecha));
            }
            if ($horasLimite > 0) {
                if ($sw == 1) {
                    /**
                     * Si ya se aplicó se debe volverse a dar formato
                     */
                    $fecha = date("Y-m-d H:i:s", $fecha);
                }
                $sw = 1;
                $fecha = strtotime('+' . $horasLimite . ' hour', strtotime($fecha));
            }
            if ($minutosLimite > 0) {
                if ($sw == 1) {
                    /**
                     * Si ya se aplicó se debe volverse a dar formato
                     */
                    $fecha = date("Y-m-d H:i:s", $fecha);
                }
                $sw = 1;
                $fecha = strtotime('+' . $minutosLimite . ' minute', strtotime($fecha));
            }
            if ($segundosLimite > 0) {
                if ($sw == 1) {
                    /**
                     * Si ya se aplicó se debe volverse a dar formato
                     */
                    $fecha = date("Y-m-d H:i:s", $fecha);
                }
                $fecha = strtotime('+' . $segundosLimite . ' second', strtotime($fecha));
            }
            if($sw == 1){
                $fecha = date("Y-m-d H:i:s", $fecha);
            }
        }
        return $fecha;
    }

    /**
     * Función para validar si un token aún permanece vigente.
     * @param string $fechaHoraFin
     * @return bool
     */
    public function validaTokenTiempoAutorizacion($fechaHoraFin = "")
    {
        $hoy = date("Y-m-d H:i:s");
        if ($fechaHoraFin != "") {
            $datetime1 = new DateTime($hoy);
            $datetime2 = new DateTime($fechaHoraFin);
            return $datetime2 >= $datetime1;
        }
        return false;
    }
}