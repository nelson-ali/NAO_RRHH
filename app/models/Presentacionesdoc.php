<?php

/*
*   Oasis - Sistema de Gesti�n para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Telef�rico"
*   Versi�n:  2.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creaci�n:  24-02-2016
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Presentacionesdoc extends \Phalcon\Mvc\Model
{
    private $_db;

    /**
     * Funci�n para la obtenci�n del conjunto de registros de presentaciones de un tipo particular de documento para un registro de relaci�n laboral determinado.
     * @param $opcion
     * @param $idPersona
     * @param $idRelaboral
     * @param $idTipoDocumento
     * @return Resultset
     */
    public function getAllByPersonaRelaboralAndTipoDocumento($opcion, $idPersona, $idRelaboral, $idTipoDocumento)
    {
        if ($idPersona >= 0 && $idRelaboral >= 0 && $idTipoDocumento > 0) {
            $sql = "select fp.*,fr.nombres,fr.ci,fr.expd,fr.ubicacion,fr.id_organigrama,fr.id_gerencia_administrativa,fr.gerencia_administrativa,fr.id_departamento_administrativo,fr.departamento_administrativo,
                    fr.id_area,fr.area,fr.id_nivelsalarial,fr.nivelsalarial,fr.id_ubicacion,fr.ubicacion,fr.proceso_codigo,fr.fin_partida,fr.cargo,
                    fr.sueldo,fr.id_condicion,fr.condicion,fr.partida,fr.fecha_ing,fr.fecha_ini,fr.fecha_incor,fr.fecha_fin,fr.fecha_ren,fr.fecha_acepta_ren,fr.fecha_agra_serv,fr.fecha_baja,fr.motivo_baja,fp.estado as presentacionesdoc_estado,
                    fp.estado_descripcion as presentacionesdoc_estado_descripcion,fp.observacion as presentacionesdoc_observacion,
                    fr.estado as relaboral_estado,fr.estado_descripcion as relaboral_estado_descripcion,fr.observacion as relaboral_observacion from f_presentacionesdoc($idPersona, $idRelaboral,$idTipoDocumento) fp ";
            if ($opcion == 2) {
                $sql .= "INNER JOIN f_relaborales_ultima_movilidad_por_id(fp.id_relaboral) fr on  fp.id_relaboral= fr.id_relaboral";
            } else {
                $sql .= "LEFT JOIN f_relaborales_ultima_movilidad_por_id(0) fr on  fp.id_relaboral>0 ";
            }
            $this->_db = new Frelaborales();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Funci�n para la obtenci�n del conjunto de registros de presentaciones de un tipo particular de documento para un registro de persona determinado.
     * @param $idPersona
     * @param $idTipoDocumento
     * @return Resultset
     */
    public function getAllByPersona($idPersona, $idTipoDocumento)
    {
        if ($idPersona > 0) {
            $sql = "SELECT pd.*,ins.id AS id_institucion, ins.razon_social AS institucion, ";
            $sql .= "CASE WHEN td.tipofechaemidoc_id = 3 THEN ";
            $sql .= "CAST(pd.dia_emi||'-'||pd.mes_emi||'-'||pd.gestion_emi AS timestamp) ";
            $sql .= "WHEN td.tipofechaemidoc_id = 4 THEN ";
            $sql .= "CAST(pd.gestion_emi||'-'||pd.mes_emi||'-'||pd.dia_emi||' '||pd.hora_emi AS timestamp) ";
            $sql .= "ELSE NULL END AS fecha_emi ";
            $sql .= "FROM presentacionesdoc pd ";
            $sql .= "INNER JOIN tiposdocumentos td ON pd.tipodocumento_id = td.id ";
            $sql .= "LEFT JOIN instituciones ins on pd.institucion_id = ins.id ";
            $sql .= "WHERE pd.baja_logica=1 ";
            $sql .= "AND pd.persona_id=" . $idPersona . " ";
            if ($idTipoDocumento > 0) {
                $sql .= "AND pd.tipodocumento_id=" . $idTipoDocumento . " ";
            }
            $this->_db = new Presentacionesdoc();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Funci�n para renombrar un archivo debido a un cambio en la fecha de presentaci�n.
     * @param $objPresentacionDoc
     * @param $fechaPresNueva
     * @return null|string
     */
    public function renameFile($objPresentacionDoc, $fechaPresNueva)
    {
        $ruta = null;
        $separador = "_";
        if ($objPresentacionDoc->id > 0) {
            $objRel = new Frelaborales();
            $rel = $objRel->getOne($objPresentacionDoc->relaboral_id);
            if (count($rel) > 0) {
                $relaboral = $rel[0];
            }
            $objTipoDocumento = Tiposdocumentos::findFirstById($objPresentacionDoc->tipodocumento_id);
            $directorio = "files/pres/" . trim($relaboral->ci);
            $fechaPresAntigua = date("Y-m-d", strtotime($objPresentacionDoc->fecha_pres));
            $fechaPresNueva = date("Y-m-d", strtotime($fechaPresNueva));
            $findme = ',';
            $pos = strpos($objTipoDocumento->formato_archivo_digital, $findme);
            if ($pos === false) {
                $extension = strtolower($objTipoDocumento->formato_archivo_digital);
                $rutaArchivoAntiguo = $directorio . "/" . trim($relaboral->ci) . $separador . trim($objTipoDocumento->codigo) . $separador . $fechaPresAntigua . $separador . $objPresentacionDoc->id . "." . $extension;
                $rutaArchivoNuevo = $directorio . "/" . trim($relaboral->ci) . $separador . trim($objTipoDocumento->codigo) . $separador . $fechaPresNueva . $separador . $objPresentacionDoc->id . "." . $extension;
                if (file_exists($rutaArchivoAntiguo)) {
                    if (rename($rutaArchivoAntiguo, $rutaArchivoNuevo)) {
                        $ruta = $rutaArchivoNuevo;
                    }
                }
            } else {
                $formatos = explode(",", strtolower($objTipoDocumento->formato_archivo_digital));
                foreach ($formatos as $extension) {
                    $rutaArchivoAntiguo = $directorio . "/" . trim($relaboral->ci) . $separador . trim($objTipoDocumento->codigo) . $separador . $fechaPresAntigua . $separador . $objPresentacionDoc->id . "." . $extension;
                    $rutaArchivoNuevo = $directorio . "/" . trim($relaboral->ci) . $separador . trim($objTipoDocumento->codigo) . $separador . $fechaPresNueva . $separador . $objPresentacionDoc->id . "." . $extension;
                    if (file_exists($rutaArchivoAntiguo)) {
                        if (file_exists($rutaArchivoAntiguo)) {
                            if (rename($rutaArchivoAntiguo, $rutaArchivoNuevo)) {
                                $ruta = $rutaArchivoNuevo;
                            }
                        }
                    }
                }
            }
        }
        return $ruta;
    }
}