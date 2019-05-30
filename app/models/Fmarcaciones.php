<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  02-04-2015
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Fmarcaciones extends \Phalcon\Mvc\Model
{
    public $id_persona;
    public $nombres;
    public $ci;
    public $expd;
    public $estado;
    public $estado_descripcion;
    public $gestion;
    public $mes;
    public $fecha;
    public $hora;
    public $id_maquina;
    public $maquina;
    public $user_reg_id;
    public $usuario;
    public $fecha_reg;
    public $fecha_ini_rango;
    public $fecha_fin_rango;

    public function initialize()
    {
        $this->_db = new Fmarcaciones();
    }

    /**
     * Función para el mapeado de las columnas.
     */
    public function columnMap()
    {
        return array(
            'id_persona' => 'id_persona',
            'nombres' => 'nombres',
            'ci' => 'ci',
            'expd' => 'expd',
            'estado' => 'estado',
            'estado_descripcion' => 'estado_descripcion',
            'gestion' => 'gestion',
            'mes' => 'mes',
            'fecha' => 'fecha',
            'hora' => 'hora',
            'id_maquina' => 'id_maquina',
            'maquina' => 'maquina',
            'user_reg_id' => 'user_reg_id',
            'usuario' => 'usuario',
            'fecha_reg' => 'fecha_reg',
            'fecha_ini_rango' => 'fecha_ini_rango',
            'fecha_fin_rango' => 'fecha_fin_rango'
        );
    }

    private $_db;

    /**
     * Función para la obtención de la totalidad de los registros de marcaciones de acuerdo al rango de marcaciones.
     * @param $fechaIni
     * @param $fechaFin
     * @param string $where
     * @param string $group
     * @param null $offset
     * @param null $limit
     * @return Resultset
     */
    public function getAll($fechaIni, $fechaFin, $where = '', $group = '', $offset = null, $limit = null)
    {
        $sql = "SELECT ";
        if ($where != '') {
            $sql .= "count(*) OVER() as total_rows,* FROM (SELECT ";
        } else {
            $sql .= "* FROM (SELECT m.total_rows,";
        }
        //$sql .= "p.id as id_persona,CAST(REPLACE(p.p_apellido||' '||CASE WHEN p.s_apellido IS NOT NULL THEN p.s_apellido ELSE '' END ||CASE WHEN p.c_apellido IS NOT NULL THEN ' '||p.c_apellido ELSE '' END ||CASE WHEN p.p_nombre IS NOT NULL THEN ' '||p.p_nombre ELSE '' END ||CASE WHEN p.s_nombre IS NOT NULL THEN ' '||p.s_nombre ELSE '' END ||CASE WHEN p.t_nombre IS NOT NULL THEN ' '||p.t_nombre ELSE '' END ,'  ',' ') AS character varying) AS nombres,";
        $sql .= "fr.id_gerencia_administrativa,fr.gerencia_administrativa,fr.id_departamento_administrativo,fr.departamento_administrativo,fr.id_area,fr.area,fr.cargo,p.id as id_persona,fr.nombres,";

        $sql .= "p.ci,CAST(TRIM(p.expd) AS character(2)),CAST(EXTRACT(YEAR from m.fecha) AS integer) AS gestion, CAST(EXTRACT(MONTH from m.fecha) AS integer) AS mes,";
        $sql .= "CASE CAST(EXTRACT(MONTH from m.fecha) AS integer) WHEN 1 THEN CAST('ENERO' AS character varying) WHEN 2 THEN CAST('FEBRERO' AS character varying) WHEN 3  THEN CAST('MARZO' AS character varying) ";
        $sql .= "WHEN 4 THEN CAST('ABRIL' AS character varying) WHEN 5 THEN CAST('MAYO' AS character varying) WHEN 6 THEN CAST('JUNIO' AS character varying) ";
        $sql .= "WHEN 7 THEN CAST('JULIO' AS character varying) WHEN 8 THEN CAST('AGOSTO' AS character varying) WHEN 9 THEN CAST('SEPTIEMBRE' AS character varying) ";
        $sql .= "WHEN 10 THEN CAST('OCTUBRE' AS character varying) WHEN 11 THEN CAST('NOVIEMBRE' AS character varying) ELSE CAST('DICIEMBRE' AS character varying) END AS mes_nombre,";
        $sql .= "m.fecha,m.hora,mq.id AS id_maquina,mq.maquina,m.observacion,m.estado,pa.valor_1 as estado_descripcion,m.user_reg_id,";
        $sql .= "CAST(REPLACE(pu.p_apellido||' '||CASE WHEN pu.s_apellido IS NOT NULL THEN pu.s_apellido ELSE '' END ||CASE WHEN pu.c_apellido IS NOT NULL THEN ' '||pu.c_apellido ELSE '' END ||CASE WHEN pu.p_nombre IS NOT NULL THEN ' '||pu.p_nombre ELSE '' END ||CASE WHEN pu.s_nombre IS NOT NULL THEN ' '||pu.s_nombre ELSE '' END ||CASE WHEN pu.t_nombre IS NOT NULL THEN ' '||pu.t_nombre ELSE '' END ,'  ',' ') AS character varying) AS usuario,";
        $sql .= "m.fecha_reg,m.fecha_ini_rango,m.fecha_fin_rango,TRIM(p.expd) AS expd ";
        $sql .= "FROM (";
        $sql .= "SELECT ";
        if ($where == '') {
            $sql .= "count(*) OVER() as total_rows,";
        }
        $sql .= "* FROM marcaciones WHERE 1=1 ";
        if ($fechaIni != '' && $fechaIni != null && $fechaFin != '' && $fechaFin != null)
            $sql .= " AND fecha BETWEEN '$fechaIni' AND '$fechaFin'";
        $sql .= " ORDER BY fecha DESC,hora DESC ";
        if ($where == '') {
            if ($offset != '' && $offset != null) $sql .= " OFFSET " . $offset;
            if ($limit != '' && $limit != null) $sql .= " LIMIT  " . $limit;
        }
        $sql .= ") AS m ";
        $sql .= "INNER JOIN personas p ON m.persona_id = p.id ";
        $sql .= "INNER JOIN maquinas mq ON m.maquina_id = mq.id ";
        $sql .= "INNER JOIN parametros pa ON pa.parametro LIKE 'ESTADO_REGISTRO' AND CAST(pa.nivel AS integer)=m.estado ";
        $sql .= "INNER JOIN usuarios u ON u.id =  m.user_reg_id ";
        $sql .= "LEFT JOIN personas pu ON pu.id=u.persona_id ";
        $sql .= "INNER JOIN relaborales re ON re.persona_id = p.id ";
        $sql .= "AND ( ";
        $sql .= "'$fechaIni' BETWEEN re.fecha_incor AND (CASE WHEN re.estado>0 THEN re.fecha_fin ELSE re.fecha_baja END) ";
        $sql .= "OR '$fechaFin' BETWEEN re.fecha_incor AND (CASE WHEN re.estado>0 THEN re.fecha_fin ELSE re.fecha_baja END) ";
        $sql .= "OR re.fecha_incor BETWEEN '$fechaIni' AND '$fechaFin' ";
        $sql .= "OR (CASE WHEN re.estado>0 THEN re.fecha_fin ELSE re.fecha_baja END) BETWEEN '$fechaIni' AND '$fechaFin' )";
        $sql .= "INNER JOIN f_relaborales_ultima_movilidad_por_id(re.id) fr ON TRUE ";
        $sql .= "ORDER BY m.fecha DESC,m.hora,p.p_apellido,p.s_apellido,p.p_nombre,p.s_nombre) AS REG";
        if ($where != '') {
            $sql .= $where;
            if ($offset != '' && $offset != null) $sql .= " OFFSET " . $offset;
            if ($limit != '' && $limit != null) $sql .= " LIMIT  " . $limit;
        }
        //echo "->>>".$sql;
        if ($group != '') $sql .= $group;
        $this->_db = new Fmarcaciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));

    }

    /**
     * Función para contabilizar la cantidad de registros totales para la consulta ejecutada en la función previa de arriva.
     * @param $fechaIni
     * @param $fechaFin
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function getCountAll($fechaIni, $fechaFin, $where = '', $group = '')
    {
        $sql = "SELECT COUNT (*) AS resultado FROM (";
        $sql .= "SELECT * FROM marcaciones ";
        if ($where != '') {
            $sql .= $where;
            if ($fechaIni != '' && $fechaIni != null && $fechaFin != '' && $fechaFin != null)
                $sql .= " AND fecha BETWEEN '$fechaIni' AND '$fechaFin'";
        } else {
            if ($fechaIni != '' && $fechaIni != null && $fechaFin != '' && $fechaFin != null)
                $sql .= " WHERE fecha BETWEEN '$fechaIni' AND '$fechaFin'";
        }
        $sql .= ") AS m ";
        $sql .= "INNER JOIN personas p ON m.persona_id = p.id ";
        $sql .= "INNER JOIN maquinas mq ON m.maquina_id = mq.id ";
        $sql .= "INNER JOIN parametros pa ON pa.parametro LIKE 'ESTADO_REGISTRO' AND CAST(pa.nivel AS integer)=m.estado ";
        $sql .= "INNER JOIN usuarios u ON u.id =  m.user_reg_id ";
        $sql .= "LEFT JOIN personas pu ON pu.id=u.persona_id";
        if ($group != '') $sql .= $group;
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención de registros de marcación para una persona en particular en un rango establecido de fechas.
     * @param $idRelaboral
     * @param $fechaIni
     * @param $fechaFin
     * @param string $where
     * @param string $group
     * @param null $offset
     * @param null $limit
     * @return Resultset
     */
    public function getAllByRelaboral($idRelaboral, $fechaIni, $fechaFin, $where = '', $group = '', $offset = null, $limit = null)
    {
        if ($idRelaboral != '') {
            $sql = "SELECT ";
            if ($where != '') {
                $sql .= "count(*) OVER() as total_rows,* FROM (SELECT ";
            } else {
                $sql .= "* FROM (SELECT m.total_rows,";
            }
            $sql .= "p.id as id_persona,CAST(REPLACE(p.p_apellido||' '||CASE WHEN p.s_apellido IS NOT NULL THEN p.s_apellido ELSE '' END ||CASE WHEN p.c_apellido IS NOT NULL THEN ' '||p.c_apellido ELSE '' END ||CASE WHEN p.p_nombre IS NOT NULL THEN ' '||p.p_nombre ELSE '' END ||CASE WHEN p.s_nombre IS NOT NULL THEN ' '||p.s_nombre ELSE '' END ||CASE WHEN p.t_nombre IS NOT NULL THEN ' '||p.t_nombre ELSE '' END ,'  ',' ') AS character varying) AS nombres,";
            $sql .= "p.ci,CAST(TRIM(p.expd) AS character(2)),CAST(EXTRACT(YEAR from m.fecha) AS integer) AS gestion, CAST(EXTRACT(MONTH from m.fecha) AS integer) AS mes,";
            $sql .= "CASE CAST(EXTRACT(MONTH from m.fecha) AS integer) WHEN 1 THEN CAST('ENERO' AS character varying) WHEN 2 THEN CAST('FEBRERO' AS character varying) WHEN 3  THEN CAST('MARZO' AS character varying) ";
            $sql .= "WHEN 4 THEN CAST('ABRIL' AS character varying) WHEN 5 THEN CAST('MAYO' AS character varying) WHEN 6 THEN CAST('JUNIO' AS character varying) ";
            $sql .= "WHEN 7 THEN CAST('JULIO' AS character varying) WHEN 8 THEN CAST('AGOSTO' AS character varying) WHEN 9 THEN CAST('SEPTIEMBRE' AS character varying) ";
            $sql .= "WHEN 10 THEN CAST('OCTUBRE' AS character varying) WHEN 11 THEN CAST('NOVIEMBRE' AS character varying) ELSE CAST('DICIEMBRE' AS character varying) END AS mes_nombre,";
            $sql .= "m.fecha,m.hora,mq.id AS id_maquina,mq.maquina,m.observacion,m.estado,pa.valor_1 as estado_descripcion,m.user_reg_id,";
            $sql .= "CAST(REPLACE(pu.p_apellido||' '||CASE WHEN pu.s_apellido IS NOT NULL THEN pu.s_apellido ELSE '' END ||CASE WHEN pu.c_apellido IS NOT NULL THEN ' '||pu.c_apellido ELSE '' END ||CASE WHEN pu.p_nombre IS NOT NULL THEN ' '||pu.p_nombre ELSE '' END ||CASE WHEN pu.s_nombre IS NOT NULL THEN ' '||pu.s_nombre ELSE '' END ||CASE WHEN pu.t_nombre IS NOT NULL THEN ' '||pu.t_nombre ELSE '' END ,'  ',' ') AS character varying) AS usuario,";
            $sql .= "m.fecha_reg,m.fecha_ini_rango,m.fecha_fin_rango,TRIM(p.expd) AS expd ";
            $sql .= "FROM (";
            $sql .= "SELECT ";
            if ($where == '') {
                $sql .= "count(*) OVER() as total_rows,";
            }
            $sql .= "* FROM marcaciones ";
            /*if ($where != '') {
                $sql .= $where;
                $sql .= " AND persona_id=(SELECT persona_id FROM relaborales WHERE id=$idRelaboral ";
            } else $sql .= " WHERE persona_id=(SELECT persona_id FROM relaborales WHERE id=$idRelaboral ";*/
            $sql .= " WHERE persona_id=(SELECT persona_id FROM relaborales WHERE id=$idRelaboral ";
            $sql .= "LIMIT 1) ";
            if ($fechaIni != '' && $fechaIni != null && $fechaFin != '' && $fechaFin != null)
                $sql .= " AND fecha BETWEEN '$fechaIni' AND '$fechaFin'";
            $sql .= " ORDER BY fecha DESC,hora DESC ";
            if ($where == '') {
                if ($offset != '' && $offset != null) $sql .= " OFFSET " . $offset;
                if ($limit != '' && $limit != null) $sql .= " LIMIT  " . $limit;
            }
            $sql .= ") AS m ";
            $sql .= "INNER JOIN personas p ON m.persona_id = p.id ";
            $sql .= "INNER JOIN maquinas mq ON m.maquina_id = mq.id ";
            $sql .= "INNER JOIN parametros pa ON pa.parametro LIKE 'ESTADO_REGISTRO' AND CAST(pa.nivel AS integer)=m.estado ";
            $sql .= "INNER JOIN usuarios u ON u.id =  m.user_reg_id ";
            $sql .= "LEFT JOIN personas pu ON pu.id=u.persona_id ";
            $sql .= "ORDER BY m.fecha DESC,m.hora,p.p_apellido,p.s_apellido,p.p_nombre,p.s_nombre) AS REG";
            if ($where != '') {
                $sql .= $where;
                if ($offset != '' && $offset != null) $sql .= " OFFSET " . $offset;
                if ($limit != '' && $limit != null) $sql .= " LIMIT  " . $limit;
            }
            if ($group != '') $sql .= $group;
            //echo "<p>---------------------->".$sql;
            $this->_db = new Fmarcaciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para contabilizar la cantidad de registros para la consulta realizada, considerando que no se toma en cuanta offset ni limit.
     * @param $idRelaboral
     * @param $fechaIni
     * @param $fechaFin
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function getCountAllByRelaboral($idRelaboral, $fechaIni, $fechaFin, $where = '', $group = '')
    {
        if ($idRelaboral != '') {
            $sql = "SELECT count(*) AS resultado FROM (";
            $sql .= "SELECT * FROM marcaciones ";
            if ($where != '') {
                $sql .= $where;
                $sql .= " AND persona_id=(SELECT persona_id FROM relaborales WHERE id=$idRelaboral ";
            } else $sql .= " WHERE persona_id=(SELECT persona_id FROM relaborales WHERE id=$idRelaboral ";
            $sql .= "LIMIT 1) ";
            if ($fechaIni != '' && $fechaIni != null && $fechaFin != '' && $fechaFin != null)
                $sql .= " AND fecha BETWEEN '$fechaIni' AND '$fechaFin'";
            $sql .= ") AS m ";
            $sql .= "INNER JOIN personas p ON m.persona_id = p.id ";
            $sql .= "INNER JOIN maquinas mq ON m.maquina_id = mq.id ";
            $sql .= "INNER JOIN parametros pa ON pa.parametro LIKE 'ESTADO_REGISTRO' AND CAST(pa.nivel AS integer)=m.estado ";
            $sql .= "INNER JOIN usuarios u ON u.id =  m.user_reg_id ";
            $sql .= "LEFT JOIN personas pu ON pu.id=u.persona_id ";
            if ($group != '') $sql .= $group;
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para conocer la cantidad de mese transcurridos entre dos fechas.
     * @param string $fechaIni
     * @param string $fechaFin
     * @return Resultset
     */
    public function getCantidadMesesEntreDosFechas($fechaIni = '', $fechaFin = '')
    {
        if ($fechaIni != '' && $fechaFin != '') {
            $sql = "SELECT f_cantidad_meses_entre_dos_fechas as resultado FROM f_cantidad_meses_entre_dos_fechas('$fechaIni','$fechaFin') ";
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para la obtención de la última fecha de una gestión y mes en particular.
     * @param $gestion
     * @param $mes
     * @return Resultset
     */
    public function getUltimaFecha($mes, $gestion)
    {
        if ($gestion > 0 && $mes > 0) {
            $sql = "SELECT f_ultimo_dia_mes FROM f_ultimo_dia_mes($mes,$gestion) ";
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para obtener la última fecha de un determinado mes en una gestión determinada.
     * @param $mes
     * @param $gestion
     * @return null
     */
    public function getUltimaFechaMesGestion($mes, $gestion)
    {   $fecha=null;
        if ($gestion > 0 && $mes > 0) {
            $sql = "SELECT f_ultimo_dia_mes FROM f_ultimo_dia_mes($mes,$gestion) ";
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if(count($arr)>0){
                $fecha = $arr[0]->f_ultimo_dia_mes;
            }
        }
        return $fecha;
    }

    /**
     * Función para el control de la existencia de al menos una marcación cruzada inicial en el rango de fechas para el registro de relación laboral.
     * @param $idRelaboral
     * @param $fechaIni
     * @param $fechaFin
     * @return integer
     */
    public function controlExisteMarcacionMixtaInicialEnRango($idRelaboral, $fechaIni, $fechaFin)
    {
        if ($idRelaboral > 0 && $fechaIni != '' && $fechaFin != '') {
            $sql = "SELECT f_existe_marcacion_mixta_inicial_en_rango_fechas as resultado from f_existe_marcacion_mixta_inicial_en_rango_fechas(" . $idRelaboral . ",'" . $fechaIni . "','" . $fechaFin . "')";
            $this->_db = new Fmarcaciones();
            $res = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $res[0]->resultado;
        }
        return 0;
    }

    /**
     * Función para el control de la existencia de al menos una marcación cruzada inicial en una determinada gestión y mes para el registro de relación laboral.
     * @param $idRelaboral
     * @param $gestion
     * @param $mes
     * @return int
     */
    public function controlExisteMarcacionMixtaInicialEnGestionMes($idRelaboral, $gestion, $mes)
    {
        if ($idRelaboral > 0 && $gestion > 0 && $mes > 0) {
            $sql = "SELECT f_existe_marcacion_mixta_inicial_en_gestion_mes as resultado from f_existe_marcacion_mixta_inicial_en_gestion_mes(" . $idRelaboral . ",$gestion,$mes)";
            $this->_db = new Fmarcaciones();
            $res = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $res[0]->resultado;
        }
        return 0;
    }

    /**
     * Función que verifica la existencia de una marcación prevista cruzada y una marcación regular en una misma fecha.
     * @param $idRelaboral
     * @param $fecha
     * @return Resultset
     */
    public function controlExisteMarcacionMixta($idRelaboral, $fecha)
    {
        if ($idRelaboral > 0 && $fecha != '') {
            $sql = "SELECT f_existe_marcacion_mixta as resultado from f_existe_marcacion_mixta(" . $idRelaboral . ",'" . $fecha . "')";
            $res = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            return $res[0]->resultado;
        }
    }

    /**
     * Función para la obtención del identificador del horario laboral del dia anterior, considerando que este es cruzado
     * que significa que en la fecha previa se tenía una marcación de entrada y en la fecha actual la marcación de salida.
     * @param $idRelaboral
     * @param $fecha
     * @return Resultset
     */
    public function obtenerIdHorarioLaboralCruzadoDiaPrevio($idRelaboral, $fecha)
    {
        if ($idRelaboral > 0 && $fecha != '') {
            $sql = "SELECT f_obtener_id_horariolaboral_cruzado_dia_previo as id_horariolaboral from f_obtener_id_horariolaboral_cruzado_dia_previo($idRelaboral,'$fecha')";
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para obtener una matriz de equipos vrs colores asignados.
     * @return Resultset
     */
    public function obtenerColorMaquinas()
    {
        $sql = "SELECT m.id,m.ip,m.num_serie,u.color FROM f_ubicaciones() u
              INNER JOIN maquinas m ON u.id = m.ubicacion_id
              ORDER BY m.num_serie";
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para obtener la versión de la aplicación del sistema.
     * @return string
     */
    public function obtenerVersionSistema()
    {
        $sql = "SELECT f_obtener_version_sistema AS o_resultado FROM f_obtener_version_sistema()";
        $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        if(count($arr)>0)return $arr[0]->o_resultado;
        else return '00.00.00';
    }
}

?>