<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  02-04-2015
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Marcaciones extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->_db = new Marcaciones();
    }

    private $_db;

    /**
     * Función para obtener la marcación valida dentro para una persona, en una fecha y rango de marcaciones válidas.
     * En caso de enviarse un valor no nulo para el parámetro i_id_maquina se realiza el filtro con dicha máquina, en caso contrario
     * se buscará de acuerdo a los registros establecidos en el registro de perfil laboral.
     * @param $idMaquina
     * @param $idRelaboral
     * @param $fecha
     * @param $horaIniRango
     * @param $horaFinRango
     */
    public function getMarcacionValida($idMaquina, $idRelaboral, $fecha, $horaIniRango, $horaFinRango)
    {
        $sql = "SELECT CASE WHEN f_obtener_marcacion_valida IS NULL THEN 0 ELSE 1 END as resultado from f_obtener_marcacion_valida($idMaquina,$idRelaboral,'$fecha','$horaIniRango','$horaFinRango')";
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para obtener el registro de la marcación válida
     * @param $idRelaboral
     * @param $idMaquina
     * @param $fecha
     * @param $horaIniRango
     * @param $horaFinRango
     * @param $entradaSalida
     * @return Resultset
     */
    public function getOneMarcacionValida($idRelaboral, $idMaquina, $fecha, $horaIniRango, $horaFinRango, $entradaSalida = 0)
    {
        if ($idMaquina >= 0 && $idRelaboral > 0 && $fecha != null && $fecha != '' && $horaIniRango != null && $horaIniRango != '' && $horaFinRango != null && $horaFinRango != '') {
            $sql = "SELECT * FROM f_obtener_marcacion_valida($idRelaboral,$idMaquina,'$fecha','$horaIniRango','$horaFinRango',$entradaSalida) ";
            $this->_db = new Marcaciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Funcion para la obtencion de la marcacion valida en base al calculo de marcaciones registradas y los datos implicados debido al identificador del horario laboral correspondiente.
     * @param $idRelaboral
     * @param $idMaquina
     * @param $fecha
     * @param $idHorarioLaboral
     * @param int $entradaSalida
     * @return Resultset
     */
    public function obtenerMarcacionValida($idRelaboral, $idMaquina, $fecha, $idHorarioLaboral, $entradaSalida = 0)
    {
        if ($idMaquina >= 0 && $idRelaboral > 0 && $fecha != null && $fecha != '' && $idHorarioLaboral > 0) {
            $sql = "SELECT * FROM f_obtener_marcacion_valida_por_id_horariolaboral($idRelaboral,$idMaquina,'$fecha',$idHorarioLaboral,$entradaSalida) ";
            $this->_db = new Marcaciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para la obtención de la marcación válida como resultado directo.
     * @param $idRelaboral
     * @param $idMaquina
     * @param $fecha
     * @param $idHorarioLaboral
     * @param int $entradaSalida
     * @return mixed
     */
    public function obtenerHoraMarcacionValida($idRelaboral, $idMaquina, $fecha, $idHorarioLaboral, $entradaSalida = 0)
    {
        if ($idMaquina >= 0 && $idRelaboral > 0 && $fecha != null && $fecha != '' && $idHorarioLaboral > 0) {
            $sql = "SELECT hora FROM f_obtener_marcacion_valida_por_id_horariolaboral($idRelaboral,$idMaquina,'$fecha',$idHorarioLaboral,$entradaSalida)";
            $this->_db = new Marcaciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (is_object($arr)) {
                foreach ($arr as $obs) {
                    return $obs->hora;
                    break;
                }
            }
        }
        return null;
    }

    /**
     * Función para la depuración de los registros duplicados en un rango determinado de fechas en caso de enviarse valores distintos a nulos
     * @param string $fechaIni
     * @param string $fechaFin
     * @return Resultset
     */
    public function depuraRegistrosDuplicados($fechaIni = null, $fechaFin = null)
    {
        $sql = "SELECT * FROM f_marcaciones_depuracion_duplicados(";
        if ($fechaIni == null || $fechaFin == null) {
            $sql .= "null,null";
        } else $sql .= "'$fechaIni','$fechaFin'";
        $sql .= ") ";
        $this->_db = new Marcaciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
}

?>