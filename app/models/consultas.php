<?php

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Consultas extends \Phalcon\Mvc\Model {
    /* personal activo de la instatitucion */

    public static function fileActivo($id) {
        $sql = "SELECT r.id,r.num_contrato,CONCAT(p.p_nombre,' ',p.s_nombre,' ',p.p_apellido,' ',p.s_apellido) as nombre,c.codigo,p.e_civil, p.nacionalidad,p.grupo_sanguineo,
                c.cargo,n.denominacion,n.sueldo,e.estado,to_char(r.fecha_ini, 'DD-mm-YYYY') as fecha_ini,
                to_char(r.fecha_incor, 'DD-mm-YYYY') as fecha_incorporacion,p.id as persona_id,
                to_char(p.fecha_nac, 'DD-mm-YYYY') as fecha_nac,p.ci,p.expd,o.unidad_administrativa,p.foto,x.direccion_dom,x.telefono_fijo,x.celular_per,x.e_mail_per
                FROM  (SELECT * FROM relaborales
                WHERE id='$id' ) as r
                INNER JOIN personas p ON r.persona_id=p.id
                INNER JOIN organigramas o ON r.organigrama_id=o.id
                INNER JOIN cargos c ON r.cargo_id=c.id
                INNER JOIN nivelsalariales n ON r.nivelsalarial_id=n.id
                INNER JOIN cargosestados e ON c.cargo_estado_id=e.id
                INNER JOIN personascontactos x ON p.id=x.persona_id
                WHERE r.baja_logica='1'";
        $db = new Personas();
        return new Resultset(null, $db, $db->getReadConnection()->query($sql));
    }
    public static function personalActivo() {
        $sql = "SELECT r.id,r.num_contrato,CONCAT(p.p_nombre,' ',p.s_nombre,' ',p.p_apellido,' ',p.s_apellido) as nombre,c.codigo, 
                c.cargo,n.denominacion,n.sueldo,e.estado,to_char(r.fecha_ini, 'DD-mm-YYYY') as fecha_ini,to_char(r.fecha_incor, 'DD-mm-YYYY') as fecha_incorporacion,
                to_char(p.fecha_nac, 'DD-mm-YYYY') as fecha_nac,CONCAT(p.ci,' ',p.expd) as ci,o.unidad_administrativa,p.foto
                FROM  (SELECT * FROM relaborales
                WHERE estado >='1' ) as r
                INNER JOIN personas p ON r.persona_id=p.id
                INNER JOIN organigramas o ON r.organigrama_id=o.id
                INNER JOIN cargos c ON r.cargo_id=c.id
                INNER JOIN nivelsalariales n ON r.nivelsalarial_id=n.id
                INNER JOIN cargosestados e ON c.cargo_estado_id=e.id
                WHERE r.baja_logica='1'
                ORDER BY r.estado";
        $db = new Personas();
        return new Resultset(null, $db, $db->getReadConnection()->query($sql));
    }
    public static function personigramacargo($id) {
        $sql = "SELECT r.id,r.num_contrato,CONCAT(p.p_nombre,' ',p.s_nombre,' ',p.p_apellido,' ',p.s_apellido) as nombre,c.codigo, c.id as cargo_id,
                c.cargo,e.estado,to_char(r.fecha_ini, 'DD-mm-YYYY') as fecha_ini,to_char(r.fecha_incor, 'DD-mm-YYYY') as fecha_incorporacion,
                to_char(p.fecha_nac, 'DD-mm-YYYY') as fecha_nac,CONCAT(p.ci,' ',p.expd) as ci,o.unidad_administrativa,p.foto
                FROM  (SELECT * FROM relaborales
                WHERE estado >='1' ) as r
                INNER JOIN personas p ON r.persona_id=p.id
                INNER JOIN organigramas o ON r.organigrama_id=o.id
                INNER JOIN cargos c ON r.cargo_id=c.id               
                INNER JOIN cargosestados e ON c.cargo_estado_id=e.id
                WHERE r.baja_logica='1' AND o.id='$id'
                ORDER BY r.estado";
        $db = new Personas();
        return new Resultset(null, $db, $db->getReadConnection()->query($sql));
    }
    //DETALLES PERSONA
    public static function archivoActivo($id) {
        $sql = "SELECT r.id,r.num_contrato,CONCAT(p.p_nombre,' ',p.s_nombre,' ',p.p_apellido,' ',p.s_apellido) as nombre,c.codigo, 
                c.cargo,n.denominacion,n.sueldo,e.estado,to_char(r.fecha_ini, 'DD-mm-YYYY') as fecha_ini,to_char(r.fecha_incor, 'DD-mm-YYYY') as fecha_incorporacion,
                to_char(p.fecha_nac, 'DD-mm-YYYY') as fecha_nac,CONCAT(p.ci,' ',p.expd) as ci,o.unidad_administrativa,p.foto
                FROM  (SELECT * FROM relaborales
                WHERE estado >='1' and id='$id' ) as r
                INNER JOIN personas p ON r.persona_id=p.id
                INNER JOIN organigramas o ON r.organigrama_id=o.id
                INNER JOIN cargos c ON r.cargo_id=c.id
                INNER JOIN nivelsalariales n ON r.nivelsalarial_id=n.id
                INNER JOIN cargosestados e ON c.cargo_estado_id=e.id
                WHERE r.baja_logica='1'
                ORDER BY r.estado";
        $db = new Personas();
        return new Resultset(null, $db, $db->getReadConnection()->query($sql));
    }

    public static function personasActivo() {
        // $sql = "SELECT p.id,CONCAT(p.p_nombre,' ',p.s_nombre) as nombres,CONCAT(p.p_apellido,' ',p.s_apellido) as apellidos,to_char(p.fecha_nac, 'DD-mm-YYYY') as fecha_nac,p.ci,p.expd,p.nacionalidad
        //         ,foto,genero,nacionalidad FROM personas p
        //         WHERE p.id not in 
        //         (
        //         select distinct persona_id FROM relaborales  WHERE baja_logica='1'
        //         ) ORDER BY p.p_apellido ASC";
        $sql="SELECT p.id,CONCAT(p.p_nombre,' ',p.s_nombre) as nombres,CONCAT(p.p_apellido,' ',p.s_apellido) as apellidos,to_char(p.fecha_nac, 'DD-mm-YYYY') as fecha_nac,p.ci,p.expd,p.nacionalidad
                ,foto,genero,nacionalidad,(select case when estado=1 then 'ACTIVO' WHEN estado=2 then 'EN PROCESO' WHEN estado=0 then 'PASIVO' else NULL END from relaborales where persona_id = p.id  order by fecha_ini DESC, estado LIMIT 1) as estado_actual
FROM personas p WHERE p.baja_logica = 1 ORDER BY p.p_apellido, s_apellido ASC"; 

        $db = new Personas();
        return new Resultset(null, $db, $db->getReadConnection()->query($sql));
    }
   

    public static function acefalos() {
        $sql = "SELECT c.id,c.cargo,c.codigo,o.unidad_administrativa as oficina,n.denominacion,n.sueldo,ce.estado from cargos c
                inner join organigramas o on c.organigrama_id = o.id
                inner join nivelsalariales n on c.codigo_nivel = n.nivel
                inner join ejecutoras e on c.ejecutora_id = e.id
                inner join cargosestados ce on c.cargo_estado_id = ce.id
                inner join finpartidas fp on c.fin_partida_id = fp.id
                inner join condiciones cs on fp.condicion_id=cs.id
                left join organigramas temporganigramas on temporganigramas.id = o.padre_id
                where c.id not in (
                        select distinct r.cargo_id from relaborales r	
                        where r.baja_logica=1
                        and r.estado>=1
                )
                
";
        $db = new Personas();
        return new Resultset(null, $db, $db->getReadConnection()->query($sql));
    }
    
    public static function organigrama(){
        $sql="SELECT o.id,n.nivel_estructural,o.padre_id,o.unidad_administrativa
               FROM  organigramas o 
               INNER JOIN nivelestructurales n ON o.nivel_estructural_id=n.id
               WHERE o.baja_logica='1' and o.visible='1'";
         $db = new Personas();
        return new Resultset(null, $db, $db->getReadConnection()->query($sql));
    }
    public static function personigrama($id){
        $sql="select id,organigrama_id,depende_id,cargo from cargos where organigrama_id='$id'  and baja_logica='1'";
         $db = new Personas();
        return new Resultset(null, $db, $db->getReadConnection()->query($sql));
    }
    public static function deletePersona($id)
    {
        $sql="update personas set baja_logica=0 where id=".$id;
         $db = new Personas();
        return new Resultset(null, $db, $db->getReadConnection()->query($sql));
    }

}
