<?php

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Cargos extends \Phalcon\Mvc\Model
{
    private $_db;

    //public function lista($organigrama_id = '', $estado2 = '', $condicion = '')
    public function lista($where = '', $group = '')
    {
        $sql = "SELECT * FROM f_listado_cargos() " . $where . " " . $group;
        //echo "---->".$sql;
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del listado paginado de cargos de acuerdo a los parámetros enviados.
     * @param int $gestion
     * @param int $idResolucionOrg
     * @param int $idResolucionEsc
     * @param int $idOrganigrama
     * @param string $where
     * @param string $group
     * @param int $offset
     * @param int $limit
     * @return Resultset
     */
    public function getPaged($gestion = 0, $idResolucionOrg = 0, $idResolucionEsc, $idOrganigrama = 0, $where = '', $group = '', $offset = 0, $limit = 0)
    {
        if ($gestion >= 0 && $idResolucionOrg >= 0 && $idResolucionEsc >= 0 && $idOrganigrama >= 0 && $offset >= 0 && $limit >= 0) {
            $sql = "SELECT * FROM f_cargos_paged($gestion, $idResolucionOrg, $idResolucionEsc, $idOrganigrama,'" . $where . "', '" . $group . "', $offset, $limit)";
            //echo "---->".$sql;
            $this->_db = new Cargos();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
        return new Resultset();
    }

    //public function listapac($estado = '', $organigrama_id = '',$fecha_ini='',$fecha_fin='')
    public function listapac($estado = '', $where = '')
    {
        //$where = '';
        if ($estado == 1) {
            $where .= " and estado is NULL ";
        }
                $sql = "select * from f_listado_pacs() where baja_logica=1 " . $where . "  order by fecha_ini asc";
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del listado de pac's de personal.
     * @param int $gestion
     * @param int $idResolucionOrg
     * @param $idResolucionEsc
     * @param int $idOrganigrama
     * @param string $where
     * @param string $group
     * @param int $offset
     * @param int $limit
     * @return Resultset
     */
    public function getPacPaged($gestion = 0, $idResolucionOrg = 0, $idResolucionEsc, $idOrganigrama = 0, $where = '', $group = '', $offset = 0, $limit = 0)
    {
        if ($gestion >= 0 && $idResolucionOrg >= 0 && $idResolucionEsc >= 0 && $idOrganigrama >= 0 && $offset >= 0 && $limit >= 0) {
            $sql = "SELECT * FROM f_pacs($gestion, $idResolucionOrg, $idResolucionEsc, $idOrganigrama,'" . $where . "', '" . $group . "', $offset, $limit)";
            //echo "---->".$sql;
            $this->_db = new Cargos();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
        return new Resultset();
    }
    public function listaeditpac($proceso_contratacion_id)
    {
        $sql = "SELECT  p.*, c.cargo,c.codigo,n.sueldo,o.unidad_administrativa, se.estado as estado1, s.proceso_contratacion_id,re.tipo_resolucion
        FROM pacs p
        INNER JOIN cargos c ON p.cargo_id=c.id
        INNER JOIN resoluciones re ON c.resolucion_ministerial_id=re.id
        INNER JOIN organigramas o ON c.organigrama_id=o.id
        INNER JOIN nivelsalariales n ON c.nivelsalarial_id=n.id
        LEFT JOIN seguimientos s ON s.pac_id=p.id AND s.baja_logica=1
        LEFT JOIN seguimientosestados se ON s.seguimiento_estado_id=se.id
        WHERE p.baja_logica=1  AND (se.estado is NULL OR proceso_contratacion_id='$proceso_contratacion_id') order by p.fecha_ini asc";
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));

    }

    public function getEstadoSeguimiento($cargo_id)
    {
        $sql = "SELECT p.*,s.seguimiento_estado_id ,CASE WHEN s.seguimiento_estado_id is NULL  THEN '0' ELSE s.seguimiento_estado_id  END as estado1
        FROM pacs p 
        LEFT JOIN seguimientos s ON p.id=s.pac_id AND s.baja_logica=1
        WHERE p.baja_logica = 1 AND p.cargo_id = '$cargo_id'";
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function getCI($cargo_id = '')
    {
        $sql = "select p.ci,p.p_nombre,p.p_apellido,p.s_apellido from relaborales r,personas p 
        where r.cargo_id='$cargo_id' and r.estado > 0 and r.baja_logica=1 and r.persona_id = p.id";
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function getPersonalOrganigrama($organigrama_id = '')
    {
        $sql = "SELECT r.id,CONCAT(p.p_apellido,' ',p.s_apellido,' ',p.p_nombre,' ',p.s_nombre) as nombre,c.cargo
        FROM relaborales r, personas p, cargos c 
        WHERE r.estado = 1 AND r.baja_logica=1 AND r.organigrama_id = '$organigrama_id' AND r.persona_id=p.id AND r.cargo_id=c.id
        ORDER BY p.p_apellido ASC";
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function listPersonal($organigrama_id = '', $depende_id = 0, $gestion = 0)
    {
        $where = "";
        if ($organigrama_id > 0) {
            $where = " AND c.organigrama_id='$organigrama_id'";
        }

        $sql = "SELECT c.*, r.estado as estado1
                FROM cargos c
                LEFT JOIN relaborales r ON c.id = r.cargo_id AND r.baja_logica = 1 AND r.estado = 1
                WHERE c.baja_logica = 1 and c.gestion='$gestion' AND c.depende_id = '$depende_id' " . $where;
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function countDepende($depende)
    {
        $sql = "SELECT  cast(count(id) as integer) as cantidad from cargos where depende_id='$depende'";
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function listGerencias()
    {
        $sql = "SELECT o.id, o.padre_id, o.unidad_administrativa
        FROM nivelestructurales n
        INNER JOIN organigramas o ON n.id=o.nivel_estructural_id
        WHERE o.baja_logica =1 AND n.estado=1";
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function dependientes($organigrama_id = '', $gestion = 0)
    {
        $sql = "SELECT id,cargo,gestion FROM cargos WHERE organigrama_id=(SELECT padre_id FROM organigramas WHERE id=" . $organigrama_id . ") AND jefe=1 AND baja_logica = 1 AND gestion = '$gestion'
        UNION ALL
        SELECT id,cargo,gestion FROM cargos WHERE organigrama_id=" . $organigrama_id . " AND baja_logica = 1 AND gestion = '$gestion' ORDER BY cargo ASC";
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para listar los nombres de cargos registrados en la tabla de cargos.
     * @author JLM
     * @return Resultset
     */
    public function listNombresCargos()
    {
        $sql = "SELECT DISTINCT cargo FROM cargos ORDER BY cargo";
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para desplegar el registro del cargo del inmediato superior de un cargo identificado mediante el parámetro enviado.
     * @author JLM
     * @return Resultset
     */
    public function getCargoSuperior($id_cargo)
    {
        $sql = "SELECT * FROM f_cargo_inmediato_superior(" . $id_cargo . ")";
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para desplegar el registro del cargo del inmediato superior de acuerdo al identificador del registro de relación laboral enviado como parámetro.
     * @author JLM
     * @return Resultset
     */
    public function getCargoSuperiorPorRelaboral($id_relaboral)
    {
        $sql = "SELECT * FROM f_cargo_inmediato_superior(" . $id_relaboral . ")";
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /*
     * Get Filtros resolucion administrativa
     * */
    public function getFiltroResolucion()
    {
        $resultado = array();
        $sql = "
        SELECT STRING_AGG(v.tipo_resolucion,',') AS o_resultado FROM ( 
SELECT CAST(r.tipo_resolucion AS CHARACTER VARYING)
FROM cargos c
INNER JOIN resoluciones r ON c.resolucion_ministerial_id = r.id
WHERE c.baja_logica = 1
GROUP BY r.id,r.tipo_resolucion
ORDER BY r.id
) AS v
        ";
        $this->_db = new Cargos();
        $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        if (count($arr) > 0) {
            /**
             * Para agregar un valor nulo al inicio se añade una coma
             */
            $res = $arr[0]->o_resultado;
            $resultado = explode(",", $res);
        }
        return $resultado;
    }

    /*
     * Get Filtros Organigrama
     * */
    public function getFiltroOrganigrama()
    {
        $resultado = array();
        $sql = "
      SELECT STRING_AGG(v.unidad_administrativa,',') AS o_resultado FROM ( 
SELECT CAST(o.unidad_administrativa AS CHARACTER VARYING)
FROM cargos c
INNER JOIN organigramas o ON c.organigrama_id = o.id
WHERE c.baja_logica = 1
GROUP BY o.unidad_administrativa
ORDER BY o.unidad_administrativa
) AS v
        ";
        $this->_db = new Cargos();
        $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        if (count($arr) > 0) {
            /**
             * Para agregar un valor nulo al inicio se añade una coma
             */
            $res = $arr[0]->o_resultado;
            $resultado = explode(",", $res);
        }
        return $resultado;
    }

    /*
     * Get Filtros Gestiones
     * */
    public function getFiltroGestiones()
    {
        $resultado = array();
        $sql = "
      SELECT STRING_AGG(v.gestion,',') AS o_resultado FROM ( 
SELECT CAST(c.gestion AS CHARACTER VARYING)
FROM cargos c
WHERE c.baja_logica = 1
GROUP BY c.gestion
ORDER BY c.gestion
) AS v
        ";
        $this->_db = new Cargos();
        $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        if (count($arr) > 0) {
            /**
             * Para agregar un valor nulo al inicio se añade una coma
             */
            $res = $arr[0]->o_resultado;
            $resultado = explode(",", $res);
        }
        return $resultado;
    }

    /*
     * Get Filtros Gestiones
     * */
    public function getFiltroTipoCargo()
    {
        $resultado = array();
        $sql = "
        SELECT STRING_AGG(v.condicion,',') AS o_resultado FROM ( 
SELECT
co.condicion
FROM cargos c 
INNER JOIN finpartidas f ON c.fin_partida_id = f.id
INNER JOIN condiciones co ON f.condicion_id = co.id
WHERE c.baja_logica=1 
GROUP BY co.condicion
ORDER BY co.condicion asc 
) AS v";
        $this->_db = new Cargos();
        $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        if (count($arr) > 0) {
            /**
             * Para agregar un valor nulo al inicio se añade una coma
             */
            $res = $arr[0]->o_resultado;
            $resultado = explode(",", $res);
        }
        return $resultado;
    }
    //  public function getAll($where='',$group='')
    // {
    //     $sql = "SELECT * FROM cargos";
    //     if($where!='')$sql .= $where;
    //     if($group!='')$sql .= $group;
    //     $this->_db = new Cargos();
    //     return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    // }
    // 
    // 
    // 


    public function serverlista($sql)
    {
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function update_escala($sql)
    {
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function getGestiones($organigrama_id = '0')
    {
        $sql = "SELECT DISTINCT (gestion) FROM cargos WHERE organigrama_id = '$organigrama_id' ORDER BY gestion DESC";
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function getGestion($fin_partida_id)
    {
        $sql = "SELECT cp.gestion
        FROM finpartidas fp
        INNER JOIN financiamientos f ON fp.financiamiento_id = f.id
        INNER JOIN categoriasprog cp ON f.categoriaprog_id = cp.id
        WHERE fp.id = '" . $fin_partida_id . "'";
        $this->_db = new Cargos();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
}
