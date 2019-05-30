<?php
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Ppostulantes extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $nombre;

    /**
     *
     * @var string
     */
    public $app;

    /**
     *
     * @var string
     */
    public $apm;

    /**
     *
     * @var string
     */
    public $sexo;

    /**
     *
     * @var string
     */
    public $ci;

    /**
     *
     * @var string
     */
    public $expedido;

    /**
     *
     * @var string
     */
    public $fecha_nac;

    /**
     *
     * @var string
     */
    public $nacionalidad;

    /**
     *
     * @var string
     */
    public $estado_civil;

    /**
     *
     * @var string
     */
    public $direccion;

    /**
     *
     * @var string
     */
    public $telefono;

    /**
     *
     * @var string
     */
    public $celular;

    /**
     *
     * @var string
     */
    public $correo;

    /**
     *
     * @var string
     */
    public $fax;

    /**
     *
     * @var string
     */
    public $casilla;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $lugar_postulacion;

    /**
     *
     * @var string
     */
    public $fecha_registro;

    /**
     *
     * @var string
     */
    public $estado;

    /**
     *
     * @var string
     */
    public $libreta_militar;

    /**
     *
     * @var string
     */
    public $empadronamiento;

    /**
     *
     * @var string
     */
    public $parentesco;

    /**
     *
     * @var integer
     */
    public $baja_logica;

    /**
     *
     * @var integer
     */
    public $logins;

    /**
     *
     * @var integer
     */
    public $nivel;

    /**
     *
     * @var integer
     */
    public $reg_dominio;

    /**
     *
     * @var integer
     */
    public $uid_facebook;
    /**
     *
     * @var integer
     */
    public $activo;

    /**
     *
     * @var integer
     */
    public $conalpedis;
    /**
     * Initialize method for model.
     */

    public $tiene_libreta_militar;

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
     private $_db;
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'nombre' => 'nombre', 
            'app' => 'app', 
            'apm' => 'apm', 
            'sexo' => 'sexo', 
            'ci' => 'ci', 
            'expedido' => 'expedido', 
            'fecha_nac' => 'fecha_nac', 
            'nacionalidad' => 'nacionalidad', 
            'estado_civil' => 'estado_civil', 
            'direccion' => 'direccion', 
            'telefono' => 'telefono', 
            'celular' => 'celular', 
            'correo' => 'correo', 
            'fax' => 'fax', 
            'casilla' => 'casilla', 
            'password' => 'password', 
            'lugar_postulacion' => 'lugar_postulacion', 
            'fecha_registro' => 'fecha_registro', 
            'estado' => 'estado', 
            'libreta_militar' => 'libreta_militar', 
            'empadronamiento' => 'empadronamiento', 
            'parentesco' => 'parentesco', 
            'baja_logica' => 'baja_logica',
            'logins' => 'logins',
            'nivel' => 'nivel',
            'reg_dominio' => 'reg_dominio',
            'uid_facebook' => 'uid_facebook',
            'activo' => 'activo',
            'conalpedis' => 'conalpedis',
            'tiene_libreta_militar' => 'tiene_libreta_militar',
            
        );
    }

    public function cargosConvocatoria()
    {
        $sql = "SELECT p.id AS proceso_contratacion_id,s.id,CONCAT(p.codigo_proceso,' ',c.cargo) AS cargo, p.dominio
        FROM procesoscontrataciones p
        INNER JOIN seguimientos s ON p.id = s.proceso_contratacion_id AND s.baja_logica=1
        INNER JOIN pacs pa ON s.pac_id= pa.id
        INNER JOIN cargos c ON pa.cargo_id=c.id
        WHERE CURRENT_DATE BETWEEN p.fecha_publ AND p.fecha_concl AND p.baja_logica=1 ";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));        
    }

//    public function listas()
//    {
//        $sql = "SELECT p.id, p.nombre, concat(p.app,' ',p.apm) as apellidos, p.sexo, concat(p.ci, ' ', p.expedido) as ci,p.fecha_nac,p.nacionalidad, p.estado_civil, p.direccion, p.telefono,p.celular, p.correo,p.libreta_militar,
//fo.institucion, fo.grado,pa.valor_1
//from ppostulantes p
//LEFT JOIN pformaciones fo ON p.id = fo.postulante_id
//LEFT JOIN parametros pa ON fo.detalle = pa.id
//WHERE p.baja_logica = 1
//ORDER BY p.id ASC";
//
//      $this->_db = new Procesoscontrataciones();
//        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
//    }


    public function lista($where="",$orderby="",$limit="")
    {
        $sql = "SELECT * FROM (SELECT p.id, p.nombre, concat(p.app,' ',p.apm) as apellidos, p.sexo, concat(p.ci, ' ', p.expedido) as ci,p.fecha_nac,p.nacionalidad, p.estado_civil, p.direccion, p.telefono,p.celular, p.correo,p.libreta_militar,p.reg_dominio,
fo.institucion, fo.grado,pa.valor_1
from ppostulantes p
LEFT JOIN pformaciones fo ON p.id = fo.postulante_id
LEFT JOIN parametros pa ON fo.detalle = pa.id
WHERE p.baja_logica = 1 AND p.activo =1
ORDER BY p.id ASC) r " . $where . " " . $orderby . " " . $limit;
        $this->_db = new Ppostulantes();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function listaRegistrate($where="",$orderby="",$limit="")
    {
        $sql = "SELECT * FROM (SELECT p.id, p.nombre, concat(p.app,' ',p.apm) as apellidos, p.sexo, concat(p.ci, ' ', p.expedido) as ci,p.fecha_nac,p.nacionalidad, p.estado_civil, p.direccion, p.telefono,p.celular, p.correo,p.libreta_militar,p.reg_dominio,
fo.institucion, fo.grado,pa.valor_1,par.valor_1 as conalpedis
from ppostulantes p
LEFT JOIN pformaciones fo ON p.id = fo.postulante_id
LEFT JOIN parametros pa ON fo.detalle = pa.id
LEFT JOIN parametros par ON p.conalpedis = CAST(par.nivel as INTEGER) AND par.parametro = 'registro conalpedis'
WHERE p.baja_logica = 1 AND p.activo =1 AND p.estado = '3'
ORDER BY p.id ASC) r " . $where . " " . $orderby . " " . $limit;
        $this->_db = new Ppostulantes();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }


    public function listpformacion($postulante_id)
    {
        $sql="SELECT pf.*, pa.valor_1,pa2.valor_1 documento_text FROM pformaciones pf
        INNER JOIN parametros pa ON pf.detalle = pa.id
        LEFT JOIN parametros pa2 ON pf.documento_id = pa2.id
        WHERE pf.postulante_id='$postulante_id' AND pf.baja_logica=1 ORDER BY pf.id ASC";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function listpexplabespecifica($postulante_id,$seguimiento_id,$estado=0)
    {
        $where = '';
        if ($estado==0) {
            $where.=' AND pe.estado = 0';
        }
        if ($seguimiento_id!=0) {
            $where.=' AND pe.seguimiento_id='.$seguimiento_id;   
        }
        $sql="SELECT pe.*, pr.codigo_convocatoria, CONCAT(pr.codigo_proceso,' ',ca.cargo) as codigo_proceso
        FROM pexplabespecificas pe
        INNER JOIN seguimientos se ON pe.seguimiento_id = se.id
        INNER JOIN procesoscontrataciones pr ON se.proceso_contratacion_id = pr.id
        INNER JOIN pacs pa ON se.pac_id = pa.id
        INNER JOIN cargos ca ON pa.cargo_id = ca.id
        WHERE pe.postulante_id='$postulante_id' AND pe.baja_logica=1 ".$where ." ORDER BY pe.gestion_desde ASC, pe.mes_desde ASC
        ";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function convocatoriasPostuladas($postulante_id)
    {
        $sql="SELECT proceso_contratacion_id 
        FROM pexplabespecificas WHERE postulante_id='$postulante_id' AND estado=0 AND baja_logica=1 GROUP BY proceso_contratacion_id ";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function verificarangofecha()
    {
        $sql="SELECT * 
        FROM procesoscontrataciones p
        WHERE CURRENT_DATE BETWEEN p.fecha_publ AND p.fecha_concl AND p.baja_logica=1";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));

    }

    public function verificarPostulacion($postulante_id)
    {
        $sql="SELECT * 
        FROM procesoscontrataciones p
        INNER JOIN pposcontrataciones pc ON p.id=pc.proceso_contratacion_id 
        WHERE CURRENT_DATE BETWEEN p.fecha_publ AND p.fecha_concl AND p.baja_logica=1 AND pc.estado =1 AND pc.postulante_id='$postulante_id'";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    public function cerrarExpEspecifica($proceso_contratacion_id='')
    {
        $sql="UPDATE pexplabespecificas SET estado = 1 WHERE proceso_contratacion_id ='$proceso_contratacion_id' ";
        $this->_db = new Procesoscontrataciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

}
