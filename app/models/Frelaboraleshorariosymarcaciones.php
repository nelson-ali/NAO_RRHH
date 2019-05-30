<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  13-03-2015
*/

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Frelaboraleshorariosymarcaciones extends \Phalcon\Mvc\Model
{
    #region Columnas del procedimiento f_relaborales()
    public $id_relaboral;
    public $id_persona;
    public $nombres;
    public $p_nombre;
    public $s_nombre;
    public $t_nombre;
    public $p_apellido;
    public $s_apellido;
    public $c_apellido;
    public $ci;
    public $expd;
    public $num_complemento;
    public $fecha_nac;
    public $edad;
    public $lugar_nac;
    public $genero;
    public $e_civil;
    public $item;
    public $carrera_adm;
    public $num_contrato;
    public $contrato_numerador_estado;
    public $id_solelabcontrato;
    public $solelabcontrato_regional_sigla;
    public $solelabcontrato_numero;
    public $solelabcontrato_gestion;
    public $solelabcontrato_codigo;
    public $solelabcontrato_user_reg_id;
    public $solelabcontrato_fecha_sol;
    public $fecha_ini;
    public $fecha_incor;
    public $fecha_fin;
    public $fecha_baja;
    public $fecha_ren;
    public $fecha_acepta_ren;
    public $fecha_agra_serv;
    public $motivo_baja;
    public $motivosbajas_abreviacion;
    public $descripcion_baja;
    public $descripcion_anu;
    public $id_cargo;
    public $cargo_codigo;
    public $cargo;
    public $cargo_resolucion_ministerial_id;
    public $cargo_resolucion_ministerial;
    public $id_nivelessalarial;
    public $nivelsalarial;
    public $nivelsalarial_resolucion_id;
    public $nivelsalarial_resolucion;
    public $numero_escala;
    public $gestion_escala;
    public $sueldo;
    public $id_procesocontratacion;
    public $proceso_codigo;
    public $id_convocatoria;
    public $convocatoria_codigo;
    public $convocatoria_tipo;
    public $id_fin_partida;
    public $fin_partida;
    public $id_condicion;
    public $condicion;
    public $tiene_item;
    public $categoria_relaboral;
    public $id_da;
    public $direccion_administrativa;
    public $organigrama_regional_id;
    public $organigrama_regional;
    public $id_regional;
    public $regional;
    public $regional_codigo;
    public $id_departamento;
    public $departamento;
    public $id_provincia;
    public $provincia;
    public $id_localidad;
    public $localidad;
    public $residencia;
    public $unidad_ejecutora;
    public $cod_ue;
    public $id_gerencia_administrativa;
    public $gerencia_administrativa;
    public $id_departamento_administrativo;
    public $departamento_administrativo;
    public $id_organigrama;
    public $unidad_administrativa;
    public $organigrama_sigla;
    public $organigrama_orden;
    public $id_area;
    public $area;
    public $id_ubicacion;
    public $ubicacion;
    public $unidades_superiores;
    public $unidades_dependientes;
    public $partida;
    public $fuente_codigo;
    public $fuente;
    public $organismo_codigo;
    public $organismo;
    public $relaborales_observacion;
    public $estado;
    public $estado_descripcion;
    public $estado_abreviacion;
    public $tiene_contrato_vigente;
    public $id_eventual;
    public $id_consultor;
    public $user_reg_id;
    public $fecha_reg;
    public $user_mod_id;
    public $fecha_mod;
    public $persona_user_reg_id;
    public $persona_fecha_reg;
    public $persona_user_mod_id;
    public $persona_fecha_mod;
    public $agrupador;//Dato adicionado para efectos de conocer si pertenece a un perfil laboral o no
    public $fecha_ing;//Fecha del primer ingreso dentro del grupo de registros laborales.
    public $relaboral_previo_id;//Identificador del registro de relación laboral previo
    #endregion Columnas del procedimiento f_relaborales()
    #region Columnas del procedimiento f_horariosymarcaciones_calculos_rango_dos_meses_contiguos(...)
    public $id_horarioymarcacion;
    public $relaboral_id;
    public $gestion;
    public $mes;
    public $mes_nombre;
    public $turno;
    public $grupo;
    public $clasemarcacion;
    public $clasemarcacion_descripcion;
    public $modalidadmarcacion_id;
    public $modalidad_marcacion;
    public $d1;
    public $calendariolaboral1_id;
    public $estado1;
    public $estado1_descripcion;
    public $d2;
    public $calendariolaboral2_id;
    public $estado2;
    public $estado2_descripcion;
    public $d3;
    public $calendariolaboral3_id;
    public $estado3;
    public $estado3_descripcion;
    public $d4;
    public $calendariolaboral4_id;
    public $estado4;
    public $estado4_descripcion;
    public $d5;
    public $calendariolaboral5_id;
    public $estado5;
    public $estado5_descripcion;
    public $d6;
    public $calendariolaboral6_id;
    public $estado6;
    public $estado6_descripcion;
    public $d7;
    public $calendariolaboral7_id;
    public $estado7;
    public $estado7_descripcion;
    public $d8;
    public $calendariolaboral8_id;
    public $estado8;
    public $estado8_descripcion;
    public $d9;
    public $calendariolaboral9_id;
    public $estado9;
    public $estado9_descripcion;
    public $d10;
    public $calendariolaboral10_id;
    public $estado10;
    public $estado10_descripcion;
    public $d11;
    public $calendariolaboral11_id;
    public $estado11;
    public $estado11_descripcion;
    public $d12;
    public $calendariolaboral12_id;
    public $estado12;
    public $estado12_descripcion;
    public $d13;
    public $calendariolaboral13_id;
    public $estado13;
    public $estado13_descripcion;
    public $d14;
    public $calendariolaboral14_id;
    public $estado14;
    public $estado14_descripcion;
    public $d15;
    public $calendariolaboral15_id;
    public $estado15;
    public $estado15_descripcion;
    public $d16;
    public $calendariolaboral16_id;
    public $estado16;
    public $estado16_descripcion;
    public $d17;
    public $calendariolaboral17_id;
    public $estado17;
    public $estado17_descripcion;
    public $d18;
    public $calendariolaboral18_id;
    public $estado18;
    public $estado18_descripcion;
    public $d19;
    public $calendariolaboral19_id;
    public $estado19;
    public $estado19_descripcion;
    public $d20;
    public $calendariolaboral20_id;
    public $estado20;
    public $estado20_descripcion;
    public $d21;
    public $calendariolaboral21_id;
    public $estado21;
    public $estado21_descripcion;
    public $d22;
    public $calendariolaboral22_id;
    public $estado22;
    public $estado22_descripcion;
    public $d23;
    public $calendariolaboral23_id;
    public $estado23;
    public $estado23_descripcion;
    public $d24;
    public $calendariolaboral24_id;
    public $estado24;
    public $estado24_descripcion;
    public $d25;
    public $calendariolaboral25_id;
    public $estado25;
    public $estado25_descripcion;
    public $d26;
    public $calendariolaboral26_id;
    public $estado26;
    public $estado26_descripcion;
    public $d27;
    public $calendariolaboral27_id;
    public $estado27;
    public $estado27_descripcion;
    public $d28;
    public $calendariolaboral28_id;
    public $estado28;
    public $estado28_descripcion;
    public $d29;
    public $calendariolaboral29_id;
    public $estado29;
    public $estado29_descripcion;
    public $d30;
    public $calendariolaboral30_id;
    public $estado30;
    public $estado30_descripcion;
    public $d31;
    public $calendariolaboral31_id;
    public $estado31;
    public $estado31_descripcion;
    public $ultimo_dia;
    public $atrasos;
    public $faltas;
    public $abandono;
    public $omision;
    public $lsgh;
    public $compensacion;
    public $observacion;
    #endregion Columnas del procedimiento f_horariosymarcaciones_calculos_rango_dos_meses_contiguos(...)

    #region Columnas del procedimiento almacenado f_relaborales()
    public $relaboral_observacion;
    public $relaboral_estado;
    public $relaboral_estado_descripcion;
    public $relaboral_baja_logica;
    public $relaboral_agrupador;
    public $relaboral_user_reg_id;
    public $relaboral_fecha_reg;
    public $relaboral_user_apr_id;
    public $relaboral_fecha_apr;
    public $relaboral_user_mod_id;
    public $relaboral_fecha_mod;
    #endregion Columnas del procedimiento almacenado f_relaborales()

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
            'id_relaboral' => 'id_relaboral',
            'id_persona' => 'id_persona',
            'nombres' => 'nombres',
            'p_nombre' => 'p_nombre',
            's_nombre' => 's_nombre',
            't_nombre' => 't_nombre',
            'p_apellido' => 'p_apellido',
            's_apellido' => 's_apellido',
            'c_apellido' => 'c_apellido',
            'ci' => 'ci',
            'expd' => 'expd',
            'num_complemento' => 'num_complemento',
            'fecha_nac' => 'fecha_nac',
            'edad' => 'edad',
            'lugar_nac' => 'lugar_nac',
            'genero' => 'genero',
            'e_civil' => 'e_civil',
            'item' => 'item',
            'carrera_amd' => 'carrera_amd',
            'num_contrato' => 'num_contrato',
            'contrato_numerador_estado' => 'contrato_numerador_estado',
            'id_solelabcontrato' => 'id_solelabcontrato',
            'solelabcontrato_regional_sigla' => 'solelabcontrato_regional_sigla',
            'solelabcontrato_numero' => 'solelabcontrato_numero',
            'solelabcontrato_gestion' => 'solelabcontrato_gestion',
            'solelabcontrato_codigo' => 'solelabcontrato_codigo',
            'solelabcontrato_user_reg_id' => 'solelabcontrato_user_reg_id',
            'solelabcontrato_fecha_sol' => 'solelabcontrato_fecha_sol',
            'fecha_ini' => 'fecha_ini',
            'fecha_incor' => 'fecha_incor',
            'fecha_fin' => 'fecha_fin',
            'fecha_baja' => 'fecha_baja',
            'fecha_ren' => 'fecha_ren',
            'fecha_acepta_ren' => 'fecha_acepta_ren',
            'fecha_agra_serv' => 'fecha_agra_serv',
            'motivo_baja' => 'motivo_baja',
            'motivosbajas_abreviacion' => 'motivosbajas_abreviacion',
            'descripcion_baja' => 'descripcion_baja',
            'descripcion_anu' => 'descripcion_anu',
            'id_cargo' => 'id_cargo',
            'cargo_codigo' => 'cargo_codigo',
            'cargo' => 'cargo',
            'cargo_resolucion_ministerial_id' => 'cargo_resolucion_ministerial_id',
            'cargo_resolucion_ministerial' => 'cargo_resolucion_ministerial',
            'id_nivelessalarial' => 'id_nivelessalarial',
            'nivelsalarial' => 'nivelsalarial',
            'nivelsalarial_resolucion_id' => 'nivelsalarial_resolucion_id',
            'nivelsalarial_resolucion' => 'nivelsalarial_resolucion',
            'numero_escala' => 'numero_escala',
            'gestion_escala' => 'gestion_escala',
            'sueldo' => 'sueldo',
            'id_procesocontratacion' => 'id_procesocontratacion',
            'proceso_codigo' => 'proceso_codigo',
            'id_convocatoria' => 'id_convocatoria',
            'convocatoria_codigo' => 'convocatoria_codigo',
            'convocatoria_tipo' => 'convocatoria_tipo',
            'id_fin_partida' => 'id_fin_partida',
            'fin_partida' => 'fin_partida',
            'id_condicion' => 'id_condicion',
            'condicion' => 'condicion',
            'tiene_item' => 'tiene_item',
            'categoria_relaboral' => 'categoria_relaboral',
            'id_da' => 'id_da',
            'direccion_administrativa' => 'direccion_administrativa',
            'organigrama_regional_id' => 'organigrama_regional_id',
            'organigrama_regional' => 'organigrama_regional',
            'id_regional' => 'id_regional',
            'regional' => 'regional',
            'regional_codigo' => 'regional_codigo',
            'id_departamento' => 'id_departamento',
            'departamento' => 'departamento',
            'id_provincia' => 'id_provincia',
            'provincia' => 'provincia',
            'id_localidad' => 'id_localidad',
            'localidad' => 'localidad',
            'residencia' => 'residencia',
            'unidad_ejecutora' => 'unidad_ejecutora',
            'cod_ue' => 'cod_ue',
            'id_gerencia_administrativa' => 'id_gerencia_administrativa',
            'gerencia_administrativa' => 'gerencia_administrativa',
            'id_departamento_administrativo' => 'id_departamento_administrativo',
            'departamento_administrativo' => 'departamento_administrativo',
            'id_organigrama' => 'id_organigrama',
            'unidad_administrativa' => 'unidad_administrativa',
            'organigrama_sigla' => 'organigrama_sigla',
            'organigrama_orden' => 'organigrama_orden',
            'id_area' => 'id_area',
            'area' => 'area',
            'id_ubicacion' => 'id_ubicacion',
            'ubicacion' => 'ubicacion',
            'unidades_superiores' => 'unidades_superiores',
            'unidades_dependientes' => 'unidades_dependientes',
            'partida' => 'partida',
            'fuente_codigo' => 'fuente_codigo',
            'fuente' => 'fuente',
            'organismo_codigo' => 'organismo_codigo',
            'organismo' => 'organismo',
            /*'observacion'=>'observacion',
            'estado'=>'estado',
            'estado_descripcion'=>'estado_descripcion',
            'estado_abreviacion'=>'estado_abreviacion',
            'tiene_contrato_vigente'=>'tiene_contrato_vigente',
            'id_eventual'=>'id_eventual',
            'id_consultor'=>'id_consultor',
            'user_reg_id'=>'user_reg_id',
            'fecha_reg'=>'fecha_reg',
            'user_mod_id'=>'user_mod_id',
            'fecha_mod'=>'fecha_mod',
            'persona_user_reg_id'=>'persona_user_reg_id',
            'persona_fecha_reg'=>'persona_fecha_reg',
            'persona_user_mod_id'=>'persona_user_mod_id',
            'persona_fecha_mod'=>'persona_fecha_mod',
            'agrupador'=>'agrupador',
            'fecha_ing'=>'fecha_ing',
            'relaboral_previo_id'=>'relaboral_previo_id'*/


            'id_horarioymarcacion' => 'id_horariosymarcacion',
            'relaboral_id' => 'relaboral_id',
            'gestion' => 'gestion',
            'mes' => 'mes',
            'mes_nombre' => 'mes_nombre',
            'turno' => 'turno',
            'grupo' => 'grupo',
            'clasemarcacion' => 'clasemarcacion',
            'clasemarcacion_descripcion' => 'clasemarcacion_descripcion',
            'modalidadmarcacion_id' => 'modalidadmarcacion_id',
            'modalidad_marcacion' => 'modalidad_marcacion',
            'd1' => 'd1',
            'calendariolaboral1_id' => 'calendariolaboral1_id',
            'estado1' => 'estado1',
            'estado1_descripcion' => 'estado1_descripcion',
            'd2' => 'd2',
            'calendariolaboral2_id' => 'calendariolaboral2_id',
            'estado2' => 'estado2',
            'estado2_descripcion' => 'estado2_descripcion',
            'd3' => 'd3',
            'calendariolaboral3_id' => 'calendariolaboral3_id',
            'estado3' => 'estado3',
            'estado3_descripcion' => 'estado3_descripcion',
            'd4' => 'd4',
            'calendariolaboral4_id' => 'calendariolaboral4_id',
            'estado4' => 'estado4',
            'estado4_descripcion' => 'estado4_descripcion',
            'd5' => 'd5',
            'calendariolaboral5_id' => 'calendariolaboral5_id',
            'estado5' => 'estado5',
            'estado5_descripcion' => 'estado5_descripcion',
            'd6' => 'd6',
            'calendariolaboral6_id' => 'calendariolaboral6_id',
            'estado6' => 'estado6',
            'estado6_descripcion' => 'estado6_descripcion',
            'd7' => 'd7',
            'calendariolaboral7_id' => 'calendariolaboral7_id',
            'estado7' => 'estado7',
            'estado7_descripcion' => 'estado7_descripcion',
            'd8' => 'd8',
            'calendariolaboral8_id' => 'calendariolaboral8_id',
            'estado8' => 'estado8',
            'estado8_descripcion' => 'estado8_descripcion',
            'd9' => 'd9',
            'calendariolaboral9_id' => 'calendariolaboral9_id',
            'estado9' => 'estado9',
            'estado9_descripcion' => 'estado9_descripcion',
            'd10' => 'd10',
            'calendariolaboral10_id' => 'calendariolaboral10_id',
            'estado10' => 'estado10',
            'estado10_descripcion' => 'estado10_descripcion',
            'd11' => 'd11',
            'calendariolaboral11_id' => 'calendariolaboral11_id',
            'estado11' => 'estado11',
            'estado11_descripcion' => 'estado11_descripcion',
            'd12' => 'd12',
            'calendariolaboral12_id' => 'calendariolaboral12_id',
            'estado12' => 'estado12',
            'estado12_descripcion' => 'estado12_descripcion',
            'd13' => 'd13',
            'calendariolaboral13_id' => 'calendariolaboral13_id',
            'estado13' => 'estado13',
            'estado13_descripcion' => 'estado13_descripcion',
            'd14' => 'd14',
            'calendariolaboral14_id' => 'calendariolaboral14_id',
            'estado14' => 'estado14',
            'estado14_descripcion' => 'estado14_descripcion',
            'd15' => 'd15',
            'calendariolaboral15_id' => 'calendariolaboral15_id',
            'estado15' => 'estado15',
            'estado15_descripcion' => 'estado15_descripcion',
            'd16' => 'd16',
            'calendariolaboral16_id' => 'calendariolaboral16_id',
            'estado16' => 'estado16',
            'estado16_descripcion' => 'estado16_descripcion',
            'd17' => 'd17',
            'calendariolaboral17_id' => 'calendariolaboral17_id',
            'estado17' => 'estado17',
            'estado17_descripcion' => 'estado17_descripcion',
            'd18' => 'd18',
            'calendariolaboral18_id' => 'calendariolaboral18_id',
            'estado18' => 'estado18',
            'estado18_descripcion' => 'estado18_descripcion',
            'd19' => 'd19',
            'calendariolaboral19_id' => 'calendariolaboral19_id',
            'estado19' => 'estado19',
            'estado19_descripcion' => 'estado19_descripcion',
            'd20' => 'd20',
            'calendariolaboral20_id' => 'calendariolaboral20_id',
            'estado20' => 'estado20',
            'estado20_descripcion' => 'estado20_descripcion',
            'd21' => 'd21',
            'calendariolaboral21_id' => 'calendariolaboral21_id',
            'estado21' => 'estado21',
            'estado21_descripcion' => 'estado21_descripcion',
            'd22' => 'd22',
            'calendariolaboral22_id' => 'calendariolaboral22_id',
            'estado22' => 'estado22',
            'estado22_descripcion' => 'estado22_descripcion',
            'd23' => 'd23',
            'calendariolaboral23_id' => 'calendariolaboral23_id',
            'estado23' => 'estado23',
            'estado23_descripcion' => 'estado23_descripcion',
            'd24' => 'd24',
            'calendariolaboral24_id' => 'calendariolaboral24_id',
            'estado24' => 'estado24',
            'estado24_descripcion' => 'estado24_descripcion',
            'd25' => 'd25',
            'calendariolaboral25_id' => 'calendariolaboral25_id',
            'estado25' => 'estado25',
            'estado25_descripcion' => 'estado25_descripcion',
            'd26' => 'd26',
            'calendariolaboral26_id' => 'calendariolaboral26_id',
            'estado26' => 'estado26',
            'estado26_descripcion' => 'estado26_descripcion',
            'd27' => 'd27',
            'calendariolaboral27_id' => 'calendariolaboral27_id',
            'estado27' => 'estado27',
            'estado27_descripcion' => 'estado27_descripcion',
            'd28' => 'd28',
            'calendariolaboral28_id' => 'calendariolaboral28_id',
            'estado28' => 'estado28',
            'estado28_descripcion' => 'estado28_descripcion',
            'd29' => 'd29',
            'calendariolaboral29_id' => 'calendariolaboral29_id',
            'estado29' => 'estado29',
            'estado29_descripcion' => 'estado29_descripcion',
            'd30' => 'd30',
            'calendariolaboral30_id' => 'calendariolaboral30_id',
            'estado30' => 'estado30',
            'estado30_descripcion' => 'estado30_descripcion',
            'd31' => 'd31',
            'calendariolaboral31_id' => 'calendariolaboral31_id',
            'estado31' => 'estado31',
            'estado31_descripcion' => 'estado31_descripcion',
            'ultimo_dia' => 'ultimo_dia',
            'atrasos' => 'atrasos',
            'faltas' => 'faltas',
            'abandono' => 'abandono',
            'omision' => 'omision',
            'lsgh' => 'lsgh',
            'compensacion' => 'compensacion',
            'observacion' => 'observacion',
            'estado' => 'estado',
            'estado_descripcion' => 'estado_descripcion',
            'baja_logica' => 'baja_logica',
            'agrupador' => 'agrupador',
            'user_reg_id' => 'user_reg_id',
            'fecha_reg' => 'fecha_reg',
            'user_apr_id' => 'user_apr_id',
            'fecha_apr' => 'fecha_apr',
            'user_mod_id' => 'user_mod_id',
            'fecha_mod' => 'fecha_mod',

        );
    }

    private $_db;

    /**
     * Función para la obtención del listado total de horarios y marcaciones filtrable de acuerdo a los parámetros enviados.
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function getAll($where = '', $group = '')
    {
        $sql = "SELECT * FROM f_horariosymarcaciones_calculos_totales_global() ";
        if ($where != '') $sql .= $where;
        if ($group != '') $sql .= $group;
        $this->_db = new Fexcepciones();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del conjunto de registros que cumple el criterio de búsqueda de acuerdo a un rango de fechas y/o un registro de relación laboral determinado.
     * @param $jsonIdRelaborales
     * @param $fechaIni
     * @param $fechaFin
     * @param string $where
     * @param string $group
     * @return Resultset
     */
    public function getAllByRangeTwoMonth($jsonIdRelaborales, $fechaIni, $fechaFin, $where = '', $group = '')
    {
        if ($jsonIdRelaborales != '' && $fechaIni != '' && $fechaFin != '') {
            $sql = "SELECT * FROM f_horariosymarcaciones_mas_relaborales(CAST('$jsonIdRelaborales' AS json),'" . $fechaIni . "','" . $fechaFin . "')";
            if ($where != '') $sql .= $where;
            if ($group != '') $sql .= $group;
            //echo "<p>-------->".$sql;
            $this->_db = new Frelaboraleshorariosymarcaciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función que se encarga de devolver en un solo resultado el conjunto de excepciones registradas para un registro de relación laboral determinado, considerando el
     * filtro de un tipo de excepción específico, una fecha, hora de inicio y finalización.
     * @param $idRelaboral
     * @param $idExcepcion
     * @param $gestion
     * @param $mes
     * @param $dia
     * @param $idCalendariolaboral
     * @return Resultset
     */
    public function getExcepcionesEnDia($idRelaboral, $idExcepcion, $gestion, $mes, $dia, $idCalendariolaboral, $opcion = 0)
    {
        if ($gestion > 0 && $mes > 0 && $dia > 0 && $idCalendariolaboral > 0) {
            $sql = "SELECT f_excepciones_en_dia FROM f_excepciones_en_dia($idRelaboral,$idExcepcion,$gestion,$mes,$dia,$idCalendariolaboral,$opcion) ";
            $this->_db = new Frelaboraleshorariosymarcaciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función que se encarga de devolver en un solo resultado el conjunto de excepciones registradas para un registro de relación laboral determinado, considerando el
     * filtro de un tipo de excepción específico, una fecha, hora de inicio y finalización. La diferencia con la función getExcepcionesEnDia se debe a que no es necesario recorrer el resultado
     * sino simplemente mostrarlo.
     * @param $idRelaboral
     * @param $idExcepcion
     * @param $gestion
     * @param $mes
     * @param $dia
     * @param $idCalendariolaboral
     * @param int $opcion
     * @return Resultset
     */
    public function obtenerExcepcionesEnDia($idRelaboral, $idExcepcion, $gestion, $mes, $dia, $idCalendariolaboral, $opcion = 0)
    {
        if ($gestion > 0 && $mes > 0 && $dia > 0 && $idCalendariolaboral > 0) {
            $sql = "SELECT f_excepciones_en_dia AS resultado FROM f_excepciones_en_dia($idRelaboral,$idExcepcion,$gestion,$mes,$dia,$idCalendariolaboral,$opcion) ";
            $this->_db = new Frelaboraleshorariosymarcaciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (is_object($arr) && $arr->count() > 0) return $arr[0]->resultado;
        }
        return "";
    }

    /**
     * Función para la obtención en un sólo resultado el conjunto de feriados en un día en particular.
     * @param $gestion
     * @param $mes
     * @param $dia
     * @param int $opcion
     * @return Resultset
     */
    public function getFeriadosEnDia($gestion, $mes, $dia, $opcion = 0)
    {
        if ($gestion > 0 && $mes > 0 && $dia > 0) {
            $sql = "SELECT f_feriados_en_dia FROM f_feriados_en_dia('$dia-$mes-$gestion',$opcion) ";
            $this->_db = new Frelaboraleshorariosymarcaciones();
            return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
        }
    }

    /**
     * Función para la obtención en un sólo resultado el conjunto de feriados en un día en particular.
     * @param $gestion
     * @param $mes
     * @param $dia
     * @param int $opcion
     * @return Resultset
     */
    public function obtenerFeriadosEnDia($gestion, $mes, $dia, $opcion = 0)
    {
        if ($gestion > 0 && $mes > 0 && $dia > 0) {
            $sql = "SELECT f_feriados_en_dia AS resultado FROM f_feriados_en_dia('$dia-$mes-$gestion',$opcion) ";
            $this->_db = new Frelaboraleshorariosymarcaciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (is_object($arr) && $arr->count() > 0) return $arr[0]->resultado;
        }
        return "";
    }

    /**
     * Función para la obtención de manera conjuncionada el detalle de permisos y feriados en un día en particular.
     * @param $idRelaboral
     * @param $idExcepcion
     * @param $gestion
     * @param $mes
     * @param $dia
     * @param $idCalendariolaboral
     * @param int $opcion
     * @return string
     */
    public function obtenerExcepcionesYFeriadosEnDia($idRelaboral, $idExcepcion, $gestion, $mes, $dia, $idCalendariolaboral, $opcion = 0)
    {
        if ($gestion > 0 && $mes > 0 && $dia > 0 && $idCalendariolaboral > 0) {
            $sql = "SELECT STRING_AGG(res,'') AS resultado FROM (SELECT f_excepciones_en_dia($idRelaboral,$idExcepcion,$gestion,$mes,$dia,$idCalendariolaboral,$opcion) AS res ";
            $sql .= "UNION SELECT f_feriados_en_dia('$dia-$mes-$gestion',$opcion) AS res ) AS reg ";
            $this->_db = new Frelaboraleshorariosymarcaciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (is_object($arr) && $arr->count() > 0) return $arr[0]->resultado;
        }
        return "";
    }

    /**
     * Función para determinar si un feriado se aplica a un determinado calendario.
     * @param string $fecha
     * @param int $idCalencdario
     * @return int
     */
    public function aplicaFeriadoEnCalendario($fecha = "", $idCalencdario = 0)
    {
        if ($fecha != "" && $idCalencdario > 0) {
            $sql = "SELECT f_considerar_feriado_en_calendario AS o_resultado FROM f_considerar_feriado_en_calendario('$fecha',$idCalencdario)";
            $this->_db = new Frelaboraleshorariosymarcaciones();
            $arr = new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
            if (is_object($arr) && $arr->count() > 0) return $arr[0]->o_resultado;
        }
        return -1;
    }
}