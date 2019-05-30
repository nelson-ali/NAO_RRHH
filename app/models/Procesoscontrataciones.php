<?php

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Procesoscontrataciones extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $normativamod_id;

    /**
     *
     * @var string
     */
    public $codigo_convocatoria;

    /**
     *
     * @var integer
     */
    public $regional_id;

    /**
     *
     * @var string
     */
    public $codigo_proceso;

    /**
     *
     * @var integer
     */
    public $gestion;

    /**
     *
     * @var string
     */
    public $fecha_publ;

    /**
     *
     * @var string
     */
    public $fecha_recep;

    /**
     *
     * @var string
     */
    public $fecha_concl;

    /**
     *
     * @var integer
     */
    public $tipoconvocatoria_id;

    /**
     *
     * @var string
     */
    public $observacion;

    /**
     *
     * @var integer
     */
    public $estado;

    /**
     *
     * @var integer
     */
    public $baja_logica;

    /**
     *
     * @var integer
     */
    public $agrupador;

    /**
     *
     * @var integer
     */
    public $user_reg_id;

    /**
     *
     * @var string
     */
    public $fecha_reg;

    /**
     *
     * @var integer
     */
    public $user_mod_id;

    /**
     *
     * @var string
     */
    public $fecha_mod;

    /**
     *
     * @var string
     */
    public $dominio;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("");
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'normativamod_id' => 'normativamod_id',
            'codigo_convocatoria' => 'codigo_convocatoria',
            'regional_id' => 'regional_id',
            'codigo_proceso' => 'codigo_proceso',
            'gestion' => 'gestion',
            'fecha_publ' => 'fecha_publ',
            'fecha_recep' => 'fecha_recep',
            'fecha_concl' => 'fecha_concl',
            'tipoconvocatoria_id' => 'tipoconvocatoria_id',
            'observacion' => 'observacion',
            'estado' => 'estado',
            'baja_logica' => 'baja_logica',
            'agrupador' => 'agrupador',
            'user_reg_id' => 'user_reg_id',
            'fecha_reg' => 'fecha_reg',
            'user_mod_id' => 'user_mod_id',
            'fecha_mod' => 'fecha_mod',
            'dominio' => 'dominio'
        );
    }

    private $_db;

    public function lista()
    {
        $sql = "SELECT p.*, n.normativa,n.modalidad,n.denominacion,pa.valor_1 as tipo
FROM procesoscontrataciones p 
INNER JOIN normativasmod n ON p.normativamod_id=n.id
LEFT JOIN parametros pa ON  pa.parametro='procesoscontrataciones_tipo' AND CAST(pa.nivel as INT) = p.tipoconvocatoria_id AND pa.baja_logica =1 
WHERE p.baja_logica=1
ORDER BY p.fecha_publ DESC";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function listseguimiento()
    {
        $sql = "SELECT s.id,s.pac_id,s.proceso_contratacion_id,se.estado, c.codigo,c.cargo,n.sueldo,o.unidad_administrativa
FROM seguimientos s 
INNER JOIN seguimientosestados se ON s.seguimiento_estado_id=se.id
INNER JOIN pacs p ON s.pac_id=p.id
INNER JOIN cargos c ON p.cargo_id=c.id
INNER JOIN nivelsalariales n ON c.nivelsalarial_id=n.id 
INNER JOIN organigramas o ON c.organigrama_id = o.id
WHERE s.baja_logica=1 ORDER BY s.id ASC";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function listseguimientoporid($id)
    {
        if ($id > 0) {
            $sql = "SELECT s.id,s.pac_id,s.proceso_contratacion_id,se.estado, c.codigo,c.cargo,n.sueldo,o.unidad_administrativa
                FROM seguimientos s 
                INNER JOIN seguimientosestados se ON s.seguimiento_estado_id=se.id
                INNER JOIN pacs p ON s.pac_id=p.id
                INNER JOIN cargos c ON p.cargo_id=c.id
                INNER JOIN nivelsalariales n ON c.nivelsalarial_id=n.id 
                INNER JOIN organigramas o ON c.organigrama_id = o.id
                WHERE s.baja_logica=1 AND s.proceso_contratacion_id = $id ORDER BY s.id ASC";
            $this->_db = new Procesoscontrataciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }


    public function getSeguimiento($id)
    {
        $sql = "SELECT s.id,s.codigo_proceso,to_char(s.fecha_sol, 'DD-MM-YYYY')as fecha_sol,s.cert_presupuestaria,to_char(s.fecha_cert_pre, 'DD-MM-YYYY')as fecha_cert_pre,
to_char(s.fecha_apr_mae, 'DD-MM-YYYY')as fecha_apr_mae,s.seguimiento_estado_id,s.organigrama_id,s.usuario_sol,p.codigo_convocatoria,n.denominacion 
FROM seguimientos s
INNER JOIN procesoscontrataciones p ON s.proceso_contratacion_id=p.id
INNER JOIN normativasmod n ON p.normativamod_id=n.id
WHERE s.id='$id'";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }


    public function getPerfilCargo($id)
    {
//         $sql = "SELECT o.area_sustantiva,ca.* 
// FROM seguimientos s, pacs p, cargos c, organigramas o, nivelsalariales n, cargosperfiles ca
// WHERE s.id='$id' AND s.pac_id=p.id AND p.cargo_id=c.id AND c.organigrama_id=o.id AND c.codigo_nivel=n.nivel AND n.baja_logica=1 AND n.activo=1  AND ca.nivelsalarial_id = n.id AND ca.baja_logica=1";
        $sql = "SELECT cp.*, concat(pr.valor_1,' - ',pr2.valor_1, ' - ( ',cp.detalle,' )') as formacion_academica
FROM seguimientos se 
INNER JOIN pacs pa ON se.pac_id = pa.id
INNER JOIN cargos ca ON pa.cargo_id = ca.id
INNER JOIN organigramas o ON ca.organigrama_id = o.id
INNER JOIN nivelsalariales ns ON ca.codigo_nivel = ns.nivel AND ns.activo = 1 
INNER JOIN cargosperfiles cp ON ns.id = cp.nivelsalarial_id AND cp.baja_logica = 1 AND o.area_sustantiva=cp.area_sustantiva
INNER JOIN parametros pr ON cp.formacion_academica_id = pr.id
LEFT JOIN parametros pr2 ON cp.documento_id = pr2.id
WHERE se.id ='$id' ORDER BY cp.prioridad ASC ";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function cargosConvocatoria()
    {
        $sql = "SELECT s.id,p.codigo_proceso,c.cargo
        FROM procesoscontrataciones p
        INNER JOIN seguimientos s ON p.id = s.proceso_contratacion_id
        INNER JOIN pacs pa ON s.pac_id= pa.id
        INNER JOIN cargos c ON pa.cargo_id=c.id
        WHERE CURRENT_DATE BETWEEN p.fecha_publ AND p.fecha_concl";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del listado de procesos disponibles de acuerdo a la condición referida en el parámetro enviado.
     * @param $id_condicion Identificador de la condición de relación laboral.
     * @param $sw Variable que identifica que debe considerarse procesos para consultoría por producto.
     * @author JLM
     * @return Resultset Conjunto de registros relacionados a los procesos de contrataciones.     *
     */
    public function listaProcesosPorCondicion($id_condicion, $sw = 0)
    {

        $sql = "SELECT pc.* FROM procesoscontrataciones pc ";
        $sql .= "INNER JOIN normativasmod nm ON nm.id = pc.normativamod_id ";
        switch ($id_condicion) {
            case 1:
                $sql .= "WHERE nm.permanente = 1 ";
                break;
            case 2:
                $sql .= "WHERE nm.eventual = 1 ";
                break;
            case 3:
                $sql .= "WHERE nm.consultor = 1 ";
                break;
        }
        $sql .= "ORDER BY pc.codigo_proceso";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function seguimientoCero($proceso_contratacion_id = '')
    {
        $sql = "UPDATE seguimientos SET baja_logica = 0 WHERE proceso_contratacion_id='$proceso_contratacion_id' AND baja_logica = 1";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function filtrarPostulantes($id = '')
    {
        $sql = "DELETE  from pcalificaciones WHERE proceso_contratacion_id=" . $id;
        $this->_db = new Procesoscontrataciones();
        new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));

        $sql = "select f_postulacion_consolidacion($id)";
        $this->_db = new Procesoscontrataciones();
        new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));

        $sql = "select f_postulacion_requerimiento($id)";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));

    }

    public function listaCalificados($seguimiento_id)
    {
        $sql = "SELECT po.*, pc.id as pcalificacion_id
        FROM pcalificaciones pc
        INNER JOIN ppostulantes po ON pc.postulante_id=po.id
        WHERE pc.seguimiento_id='$seguimiento_id' AND pc.cumple =1
        ORDER BY app, apm";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function listaNoCalificados($seguimiento_id)
    {
        $sql = "SELECT po.*, pc.id as pcalificacion_id
        FROM pcalificaciones pc
        INNER JOIN ppostulantes po ON pc.postulante_id=po.id
        WHERE pc.seguimiento_id='$seguimiento_id' AND pc.cumple =0
        ORDER BY app, apm";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function getcargopostula($seguimiento_id = '')
    {
        $sql = "SELECT s.id,CONCAT(p.codigo_proceso,' ',c.cargo) AS cargo
        FROM procesoscontrataciones p
        INNER JOIN seguimientos s ON p.id = s.proceso_contratacion_id AND s.baja_logica=1
        INNER JOIN pacs pa ON s.pac_id= pa.id
        INNER JOIN cargos c ON pa.cargo_id=c.id
        WHERE s.id='$seguimiento_id'";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
    public function getcargosprocesoscontratacion($pc = '')
    {
        $sql = "SELECT c.id,p.codigo_proceso,c.cargo
        FROM procesoscontrataciones p
        INNER JOIN seguimientos s ON p.id = s.proceso_contratacion_id
        INNER JOIN pacs pa ON s.pac_id= pa.id
        INNER JOIN cargos c ON pa.cargo_id=c.id
        WHERE p.id = '$pc'";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }
}
