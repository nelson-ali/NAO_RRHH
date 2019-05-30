<?php

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Contrataciones extends ModelBase {

    public function initialize() {
        $this->hasMany('id', 'ContratacionesFinanciador', 'contrataciones_id');
        $this->hasOne('modalidad_id', 'modalidad', 'id');
        $this->hasOne('tipo_id', 'tipo', 'id');
        parent::initialize();
        /*  $this->hasManyToMany(
          "id", "ContratacionesFinanciador", "contrataciones_id", "financiador_id", "Financiador", "id"
          ); */
    }

    public function chart_index() {
        $sql = "SELECT SUM(d.no_iniciado) as no_iniciado,SUM(d.iniciado) as iniciado,SUM(d.concluido) as concluido, d.id,d.mes FROM (
                SELECT COUNT(*) as no_iniciado,0 as iniciado, 0 as concluido, m.mes ,m.id
                FROM contrataciones c 
                INNER JOIN mes m ON c.mes_solicitud=m.id
                WHERE c.estado_id='1'
                GROUP BY c.mes_solicitud
                UNION
                SELECT 0 as no_iniciado,COUNT(*)  as iniciado, 0 as concluido, m.mes ,m.id
                FROM contrataciones c 
                INNER JOIN mes m ON c.mes_solicitud=m.id
                WHERE c.estado_id not IN (1,15)
                GROUP BY c.mes_solicitud
                UNION
                SELECT 0 as no_iniciado,0 as iniciado, COUNT(*) as concluido, m.mes ,m.id
                FROM contrataciones c 
                INNER JOIN mes m ON c.mes_solicitud=m.id
                WHERE c.estado_id='15'
                GROUP BY c.mes_solicitud
                ) as d
                GROUP BY d.mes
                ORDER BY d.id";
       // $query = new Phalcon\Mvc\Model\Query($sql);
        //$robots = $app->modelsManager->executeQuery($phql);
        $contratacion = new Contrataciones();
        return new Resultset(null, $contratacion, $contratacion->getReadConnection()->query($sql));
      //  return $query->execute();
    }

    public function lista_json() {
        $sql = "SELECT c.ID,u.UNIDAD,c.OBJETO,mo.MODALIDAD,t.TIPO
            FROM PAC_CONTRATOS c 
           INNER JOIN PAC_MODALIDADES mo ON c.MODALIDAD_ID=mo.ID
           INNER JOIN PAC_TIPOS t ON c.TIPO_ID=t.ID
           INNER JOIN PAC_UNIDADES u ON c.UNIDAD_ID=u.ID";
        $result = $this->findAll($sql);
        return $result;
    }

    public function listarTodo() {
        $sql = "SELECT 1 as suma,c.precio_referencial,c.id,c.codigo,c.cuce_sicoes,c.objeto,c.precio_referencial,
            m.modalidad,u.nombre,u.cargo,t.tipo,e.etapa,s.estado,ms.mes as mes_solicitud,me.mes as mes_estimado,o.oficina,p.descripcion,p.codigo,fi.financiador,
            c.mes_solicitud-MONTH(NOW()) as resto
            FROM contrataciones c 
            INNER JOIN modalidad AS m ON (c.modalidad_id=m.id)
            INNER JOIN users AS u  ON (c.responsable=u.id)
            INNER JOIN partidas AS p ON (c.partida_id=p.id) 
            INNER JOIN financiador AS fi ON (c.financiador_id=fi.id) 
            INNER JOIN tipo AS t ON  (c.tipo_id=t.id)
            INNER JOIN etapa AS e ON (c.etapa_id=e.id)
            INNER JOIN estado AS s ON (c.estado_id=s.id)
            INNER JOIN mes AS ms ON (c.mes_solicitud=ms.id)
            INNER JOIN mes AS me ON (c.mes_solicitud=me.id)
            INNER JOIN oficinas o ON (c.unidad_id=o.id)";
        $contratacion = new Contrataciones();
        return new Resultset(null, $contratacion, $contratacion->getReadConnection()->query($sql, null));
    }

    public function listarUnidad($id) {
        $sql = "SELECT c.precio_referencial,c.id,c.codigo,c.cuce_sicoes,c.objeto,c.precio_referencial,
            m.modalidad,u.nombre,u.cargo,t.tipo,e.etapa,s.estado,ms.mes as mes_solicitud,me.mes as mes_estimado,o.oficina,p.descripcion,p.id as codigo,fi.financiador,
            c.mes_solicitud-MONTH(NOW()) as resto
            FROM contrataciones c 
            INNER JOIN modalidad AS m ON (c.modalidad_id=m.id)
            INNER JOIN users AS u  ON (c.responsable=u.id)
            INNER JOIN partidas AS p ON (c.partida_id=p.id) 
            INNER JOIN financiador AS fi ON (c.financiador_id=fi.id) 
            INNER JOIN tipo AS t ON  (c.tipo_id=t.id)
            INNER JOIN etapa AS e ON (c.etapa_id=e.id)
            INNER JOIN estado AS s ON (c.estado_id=s.id)
            INNER JOIN mes AS ms ON (c.mes_solicitud=ms.id)
            INNER JOIN mes AS me ON (c.mes_solicitud=me.id)
            INNER JOIN oficinas o ON (c.unidad_id=o.id)
            WHERE o.id='$id'";
        $contratacion = new Contrataciones();
        return new Resultset(null, $contratacion, $contratacion->getReadConnection()->query($sql, null));
    }

    //detalle de contrataciones
    public function detalle($id) {
        $sql = "SELECT c.precio_referencial,c.tipo_id,c.id,c.codigo,c.cuce_sicoes,c.objeto,c.precio_referencial,
            m.modalidad,u.nombre,u.cargo,t.tipo,e.etapa,s.estado,ms.mes as mes_solicitud,me.mes as mes_estimado,o.oficina,p.descripcion,p.id AS codigo,fi.financiador
            FROM contrataciones c 
            INNER JOIN modalidad AS m ON (c.modalidad_id=m.id)
            INNER JOIN users AS u  ON (c.responsable=u.id)
            INNER JOIN partidas AS p ON (c.partida_id=p.id) 
            INNER JOIN financiador AS fi ON (c.financiador_id=fi.id) 
            INNER JOIN tipo AS t ON  (c.tipo_id=t.id)
            INNER JOIN etapa AS e ON (c.etapa_id=e.id)
            INNER JOIN estado AS s ON (c.estado_id=s.id)
            INNER JOIN mes AS ms ON (c.mes_solicitud=ms.id)
            INNER JOIN mes AS me ON (c.mes_solicitud=me.id)
            INNER JOIN oficinas o ON (c.unidad_id=o.id)
            WHERE c.id='$id'";
        $contratacion = new Contrataciones();
        return new Resultset(null, $contratacion, $contratacion->getReadConnection()->query($sql, null));
    }

}
