<?php

/**
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  07-07-2016
 */
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Publicaciones extends \Phalcon\Mvc\Model
{

    private $_db;

    public function initialize()
    {
        $this->_db = new Publicaciones();
    }

    /**
     * Función para la obtención del listado de publicaciones para el foro.
     * @param $orderTab
     * @param $idUsuario
     * @param $where
     * @param $group
     * @param $offset
     * @param $limit
     * @return Resultset
     */
    public function getPublicacionesPrincipales($orderTab, $idUsuario, $where = '', $group = '', $offset = 0, $limit = 0)
    {
        if ($idUsuario > 0) {
            $sql = "SELECT COUNT(*) OVER() AS total,pu.*,pr2.valor_1 AS genero_publicacion,CAST(pr2.valor_2 AS INTEGER) AS genero_publicacion_opcion,";
            $sql .= "f_obtener_tiempo_transcurrido(pu.fecha_reg) as tiempo_transcurrido,";
            $sql .= "us.pseudonimo,(CASE WHEN us.avatar IS NULL THEN CASE WHEN UPPER(pe.genero) = 'M' THEN 'hombre.jpg' ELSE 'mujer.jpg' END ELSE us.avatar END) AS avatar,";
            $sql .= "pup.emoticon_id,pr1.valor_1 AS emoticon,pr1.valor_2 AS class_emoticon,";
            $sql .= "f_obtener_contabilizador_emoticones(pu.id," . $idUsuario . ") as contabilizador_emoticones ";
            $sql .= "FROM publicaciones pu ";
            $sql .= "INNER JOIN usuarios us ON us.id = pu.user_reg_id ";
            $sql .= "INNER JOIN personas pe ON pe.id = us.persona_id ";
            $sql .= "LEFT JOIN publicacionesemoticones pup ON pup.publicacion_id = pu.id AND pup.user_reg_id = " . $idUsuario . " AND pup.estado=1 AND pup.baja_logica=1 ";
            $sql .= "LEFT JOIN parametros pr1 ON pr1.parametro LIKE 'EMOCIONES_PUBLICACIONES' AND CAST(pr1.nivel AS INTEGER) = pup.emoticon_id ";
            $sql .= "LEFT JOIN parametros pr2 ON pr2.parametro LIKE 'GENERO_PUBLICACIONES' AND CAST(pr2.nivel AS INTEGER) = pu.genero_id ";
            $sql .= "WHERE pu.padre_id is null AND pu.baja_logica=1 ";
            if ($orderTab == 1) {
                $sql .= "AND pu.user_reg_id=" . $idUsuario . " ";
            } else {
                if ($orderTab == 3) {
                    $sql .= "AND pu.genero_id IN (2,3) ";
                } else {
                    $sql .= "AND pu.genero_id NOT IN (2,3) ";
                }
            }
            $sql .= "ORDER BY pu.fecha_reg desc ";
            if ($limit > 0) {
                $sql .= "offset $offset limit $limit";
            }
            //echo "<p>------>" . $sql;
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para la obtención del listado de publicaciones.
     * @param int $orderTab
     * @param $idUsuario
     * @param int $idPublicacion
     * @param string $where
     * @param string $group
     * @param int $offset
     * @param int $limit
     * @return Resultset
     */
    public function getPublicaciones($orderTab = 0, $idUsuario, $idPublicacion = 0, $where = '', $group = '', $offset = 0, $limit = 0)
    {
        if ($idUsuario > 0 && $idPublicacion >= 0) {
            /*$sql = "SELECT COUNT(*) OVER() AS total,pu.*,pr2.valor_1 AS genero_publicacion,CAST(pr2.valor_2 AS INTEGER) AS genero_publicacion_opcion,";
            $sql .= "f_obtener_tiempo_transcurrido(pu.fecha_reg) as tiempo_transcurrido,";
            $sql .= "us.pseudonimo,(CASE WHEN us.avatar IS NULL THEN CASE WHEN UPPER(pe.genero) = 'M' THEN 'hombre.jpg' ELSE 'mujer.jpg' END ELSE us.avatar END) AS avatar,";
            $sql .= "pup.emoticon_id,pr1.valor_1 AS emoticon,pr1.valor_2 AS class_emoticon,f_obtener_contabilizador_emoticones(pu.id," . $idUsuario . ") as contabilizador_emoticones,";
            $sql .= "(CASE WHEN pu.padre_id IS NULL OR compartido=1 THEN 1 ELSE 0 END) AS principal ";
            $sql .= " FROM publicaciones pu ";
            $sql .= "INNER JOIN usuarios us ON us.id = pu.user_reg_id ";
            $sql .= "INNER JOIN personas pe ON pe.id = us.persona_id ";
            $sql .= "LEFT JOIN publicacionesemoticones pup ON pup.publicacion_id = pu.id AND pup.user_reg_id = " . $idUsuario . " AND pup.estado=1 AND pup.baja_logica=1 ";
            $sql .= "LEFT JOIN parametros pr1 ON pr1.parametro LIKE 'EMOCIONES_PUBLICACIONES' AND CAST(pr1.nivel AS INTEGER) = pup.emoticon_id ";
            $sql .= "LEFT JOIN parametros pr2 ON pr2.parametro LIKE 'GENERO_PUBLICACIONES' AND CAST(pr2.nivel AS INTEGER) = pu.genero_id ";
            $sql .= "WHERE pu.padre_id = " . $idPublicacion . " AND compartido=0 AND pu.baja_logica=1 AND pu.estado>=1 ";
            if ($orderTab == 1) {
                $sql .= "AND pu.user_reg_id=" . $idUsuario . " ";
            }
            $sql .= "ORDER BY pu.fecha_reg asc ";*/
            $sql = "select * from f_publicaciones($orderTab, $idUsuario, $idPublicacion,'$where','$group',$offset,$limit)";
            /*echo "<p>------>".$sql;*/
            $this->_db = new Publicaciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para obtener en formato texto el contabilizador de emoticones.
     * @param $idPublicacion
     */
    public function getContadorEmoticones($idPublicacion, $idUsuario)
    {
        if ($idPublicacion > 0 && $idUsuario > 0) {
            $sql = "SELECT f_obtener_contabilizador_emoticones(" . $idPublicacion . "," . $idUsuario . ") AS o_resultado ";
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (count($arr) > 0) {
                return $arr[0]->o_resultado;
            }
        }
    }
}