<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  03-03-2015
*/

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Controlexcepciones extends \Phalcon\Mvc\Model
{
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->_db = new Controlexcepciones();
    }

    private $_db;

    /**
     * Función para el registro masivo de boletas de control de excepciones de forma masiva.
     * @param string $jsonIdRelaborales
     * @param int $idExcepcion
     * @param $fechaIni
     * @param $horaIni
     * @param $fechaFin
     * @param $horaFin
     * @param int $turno
     * @param int $entradaSalida
     * @param string $justificacion
     * @param string $destino
     * @param string $observacion
     * @param int $estado
     * @param int $idUsuario
     * @return int
     */
    public function registroMasivoPorRelaboral($jsonIdRelaborales = '{"0":0}', $idExcepcion = 0, $fechaIni, $horaIni, $fechaFin, $horaFin, $turno = 0, $entradaSalida = 0, $justificacion = '', $destino = '', $observacion = '', $estado = 1, $idUsuario = 0)
    {
        if ($jsonIdRelaborales != '' && $idExcepcion > 0 && $fechaIni != '' && $horaIni != '' && $fechaFin != '' && $horaFin != '' && $estado > 0 && $idUsuario > 0) {
            $sql = "SELECT f_controlexcepciones_registro_masivo_por_lista_relaborales AS o_resultado FROM f_controlexcepciones_registro_masivo_por_lista_relaborales";
            $sql .= "(CAST('$jsonIdRelaborales' AS json),$idExcepcion,'$fechaIni','$horaIni','$fechaFin','$horaFin',$turno,$entradaSalida,'$justificacion','$destino','$observacion',$estado,$idUsuario)";
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (count($arr) > 0) return $arr[0]->o_resultado;
        }
        return 0;
    }

    /**
     * Función para el registro masivo de un determinado permiso en base a su perfil laboral.
     * @param $idPerfilLaboral
     * @param $fechaIni
     * @param $horaIni
     * @param $fechaFin
     * @param $horaFin
     * @param $justificacion
     * @param $observacion
     * @param $estado
     * @param $idUsuario
     */
    public function registroMasivoPorPerfil($idPerfilLaboral, $idExcepcion, $fechaIni, $horaIni, $fechaFin, $horaFin, $justificacion, $observacion, $estado, $idUsuario)
    {
        if ($idPerfilLaboral > 0 && $idExcepcion > 0 && $fechaIni != '' && $horaIni != '' && $fechaFin != '' && $horaFin != '' && $estado > 0 && $idUsuario > 0) {
            $db = $this->getDI()->get('db');
            $sql = "SELECT * FROM f_controlexcepciones_registro_masivo_por_perfil";
            $sql .= "($idPerfilLaboral,$idExcepcion,'$fechaIni','$horaIni','$fechaFin','$horaFin','$justificacion','$observacion',$estado,$idUsuario)";
            $res = $db->execute($sql);
            return $res;
        }
        return false;
    }

    /**
     * Función para saber si una hora es mayor a otra.
     * @param $horaIni
     * @param $horaFin
     * @return int
     */
    public function horaEsMayorAhora($horaIni, $horaFin)
    {
        if ($horaIni != '' && $horaFin != '') {
            $sql = "SELECT CASE WHEN cast('$horaIni' as time without time zone)>CAST('$horaFin' AS time without time zone) THEN 1 ELSE ";
            $sql .= "CASE WHEN cast('$horaIni' as time without time zone)=CAST('$horaFin' AS time without time zone) THEN 2 ELSE 0 END END AS cantidad";
            //echo $sql;
            $this->_db = new Frelaborales();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (count($arr) > 0) return $arr[0]->cantidad;
        }
        return 0;
    }

    /**
     * Función para verificar la validez de una solicitud.
     * @param $idRelaboral
     * @param $fecha
     * @param int $opcion
     * @param int $nivel
     * @return int
     */
    public function verificaPlazoValidezSolicitud($idRelaboral, $fecha, $opcion = 0, $nivel = 0)
    {
        if ($idRelaboral > 0 && $fecha != '') {
            $sql = "SELECT f_controlexcepciones_plazo_vencido AS resultado FROM f_controlexcepciones_plazo_vencido($idRelaboral, '$fecha',$opcion,$nivel)";
            $this->_db = new Frelaborales();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (count($arr) > 0) return $arr[0]->resultado;
        }
        return 0;
    }

    /**
     * Función para corregir las boletas desfasadas.
     * @param int $idPersona
     * @param int $idRelaboral
     * @return int
     */
    public function corrigeBoletasDesfasadas($idPersona = 0, $idRelaboral = 0)
    {
        $resultado = 0;
        if ($idPersona >= 0 && $idRelaboral >= 0) {
            $sql = "SELECT f_controlexcepciones_desfasadas_correccion as o_resultado FROM f_controlexcepciones_desfasadas_correccion($idPersona,$idRelaboral);";
            $this->_db = new Frelaborales();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (count($arr) > 0) $resultado = $arr[0]->o_resultado;
        }
        return $resultado;
    }
} 