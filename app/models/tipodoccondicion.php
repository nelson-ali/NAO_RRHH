<?php
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class tipodoccondicion extends \Phalcon\Mvc\Model
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
    public $tipodocumento_id;
    
    /**
     * 
     * @var integer
     */
    public $condicion_id;
    
    /**
     * 
     * @var integer
     */
    public $baja_logica;
    
    /**
     * Initialize method for model.
     */
    public function initialize() {
        $this->setSchema("");
    }
    
    /**
     * Independent Column Mapping
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'tipodocumento_id' => 'tipodocumento_id',
            'condicion_id' => 'condicion_id',
            'baja_logica' => 'baja_logica'
        );
    }
    
    private $_db;
    /**
     * Lista documentos presentados y no presentados por ci de persona
     */
    public function listaDocXPersona($ci,$sexo) {
        $sql_query = "SELECT p.id AS per_id, rl.id AS rellaboral_id, c.condicion, td.id AS tipo_doc_id, td.tipo_documento, td.codigo,  td.sexo , pd.id AS doc_presentado_id, pd.nombre, pr.valor_1 as grupoarchivos,
                      td.campo_aux_a AS campo_a, td.tipo_dato_campo_aux_a AS tipo_a, td.campo_aux_b AS campo_b, td.tipo_dato_campo_aux_b AS tipo_b, td.campo_aux_c AS campo_c, td.tipo_dato_campo_aux_c AS tipo_c,
                      (dia_emi || '-' || mes_emi || '-' || gestion_emi) AS fecha_emi, fecha_pres, campo_aux_v1, campo_aux_v2, campo_aux_v3, campo_aux_d1, campo_aux_d2, campo_aux_d3, pd.observacion,
                      tamanio, tipo
                      FROM personas p, relaborales rl, finpartidas fp, condiciones c, tipodoccondicion tdc, parametros pr, tipodocumento td 
                      LEFT JOIN presentaciondoc pd ON pd.tipodocumento_id = td.id
                      WHERE p.id = rl.persona_id AND fp.id = rl.finpartida_id AND c.id = fp.condicion_id AND c.id = tdc.condicion_id AND td.id = tdc.tipodocumento_id
                      AND td.grupoarchivos_id = pr.id AND (pd.id IS NULL OR (rellaboral_id = rl.id AND pd.baja_logica = 1))
                      AND p.baja_logica = 1 AND rl.baja_logica = 1 AND tdc.baja_logica = 1 AND td.baja_logica = 1 AND rl.estado = 1 AND rl.baja_logica = 1
                      AND (td.sexo = 'I' OR td.sexo = '".$sexo."')
                      AND p.ci = '".$ci."'
                      ORDER BY pr.id, tdc.tipodocumento_id";
        $this->_db = new tipodoccondicion();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql_query));
    }
    
    /**
     * Lista de grupo de documentos enviados
     */
    public function listaGrupoDoc($ci,$sexo) {
        $sql_query = "SELECT pr.id, pr.valor_1 as grupoarchivos
                      FROM personas p, relaborales rl, finpartidas fp, condiciones c, tipodoccondicion tdc, parametros pr, tipodocumento td 
                      LEFT JOIN presentaciondoc pd ON pd.tipodocumento_id = td.id
                      WHERE p.id = rl.persona_id AND fp.id = rl.finpartida_id AND c.id = fp.condicion_id AND c.id = tdc.condicion_id AND td.id = tdc.tipodocumento_id
                      AND td.grupoarchivos_id = pr.id
                      AND (td.sexo = 'I' OR td.sexo = '".$sexo."')
                      AND p.ci = '".$ci."'
                      GROUP BY pr.id
                      ORDER BY pr.id";
        $this->_db = new tipodoccondicion();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql_query));
    }
    
    /**
     *  Lista los tipos de documentos asociados a una condición
     */
    public function listaDocCond($id_condiciones){
        $sql_query = "SELECT td.id AS id, td.tipo_documento, td.codigo, td.tipopresdoc_id, td.periodopresdoc_id, 
                        td.tipoemisordoc_id, td.tipofechaemidoc_id, td.tipoperssoldoc_id, td.grupoarchivos_id
                      FROM tipodoccondicion tdc, tipodocumento td 
                      WHERE tipodocumento_id = td.id AND condicion_id =".$id_condiciones." 
                      ORDER BY id";
        $this->_db = new tipodoccondicion();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql_query));
    }
}
