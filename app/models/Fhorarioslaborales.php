<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  09-12-2014
*/


use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Fhorarioslaborales extends \Phalcon\Mvc\Model
{
    public $id_horario_laboral;
    public $nombre;
    public $nombre_alternativo;
    public $hora_entrada;
    public $hora_salida;
    public $horas_laborales;
    public $dias_laborales;
    public $rango_entrada;
    public $rango_salida;
    public $hora_inicio_rango_ent;
    public $hora_final_rango_ent;
    public $hora_inicio_rango_sal;
    public $hora_final_rango_sal;
    public $color;
    public $fecha_ini;
    public $fecha_fin;
    public $observacion;
    public $estado;
    public $baja_logica;
    public $agrupador;
    public $user_reg_id;
    public $fecha_reg;
    public $user_mod_id;
    public $fecha_mod;

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
            'id_horariolaboral' => 'id_horariolaboral',
            'nombre' => 'nombre',
            'nombre_alternativo' => 'nombre_alternativo',
            'hora_entrada' => 'hora_entrada',
            'hora_salida' => 'hora_salida',
            'horas_laborales' => 'horas_laborales',
            'dias_laborales' => 'dias_laborales',
            'rango_entrada' => 'rango_entrada',
            'rango_salida' => 'rango_salida',
            'hora_inicio_rango_ent' => 'hora_inicio_rango_ent',
            'hora_final_rango_ent' => 'hora_final_rango_ent',
            'hora_inicio_rango_sal' => 'hora_inicio_rango_sal',
            'hora_final_rango_sal' => 'hora_final_rango_sal',
            'color' => 'color',
            'fecha_ini' => 'fecha_ini',
            'fecha_fin' => 'fecha_fin',
            'observacion' => 'observacion',
            'estado' => 'estado',
            'baja_logica' => 'baja_logica',
            'agrupador' => 'agrupador',
            'user_reg_id' => 'user_reg_id',
            'fecha_reg' => 'fecha_reg',
            'user_mod_id' => 'user_mod_id',
            'fecha_mod' => 'fecha_mod'
        );
    }

    /**
     * Función para la obtención del listado de horarios laborales disponibles en el sistema.
     * @return Resultset
     */
    public function getHorariosLaborales()
    {
        $sql = "SELECT * FROM f_horarioslaborales()";
        $this->_db = new Fhorarioslaborales();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del listado de horarios laborales disponibles en el sistema.
     * @return Resultset
     */
    public function getHorariosLaboralesDisponibles()
    {
        $sql = "SELECT * FROM f_horarioslaborales() where estado>=1";
        $this->_db = new Fhorarioslaborales();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del listado de horarios laborales disponibles en el sistema en un rango de tiempo y estado.
     * @param $estado
     * @param $fecha_ini
     * @param $fecha_fin
     * @return Resultset
     */
    public function getHorariosLaboralesDisponiblesRango($estado, $fechaIni, $fechaFin)
    {
        $sql = "SELECT * FROM f_horarioslaborales_rango($estado,CAST('$fechaIni' AS DATE),";
        if ($fechaFin == null || $fechaFin == '') {
            $arr = explode("-", $fechaIni);
            $sql .= "f_ultimo_dia_mes(" . $arr[1] . "," . $arr[2] . "))";
        } else {
            $sql .= "'$fechaFin')";
        }
        $this->_db = new Fhorarioslaborales();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

    /**
     * Función para la obtención del registro correspondiente al horario laboral solicitado
     * @param $id
     * @return Resultset
     */
    public function getOne($id)
    {
        $sql = "SELECT * FROM f_horarioslaborales() where id_horariolaboral=" . $id;
        $this->_db = new Fhorarioslaborales();
        return new Resultset(null, $this->_db, $this->_db->getReadConnection()->query($sql));
    }

}