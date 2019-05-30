<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  27-11-2014
*/

class Paises extends \Phalcon\Mvc\Model
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
    public $iso2;

    /**
     *
     * @var string
     */
    public $iso3;

    /**
     *
     * @var integer
     */
    public $prefijo;

    /**
     *
     * @var string
     */
    public $pais;

    /**
     *
     * @var string
     */
    public $continente;

    /**
     *
     * @var string
     */
    public $subcontinente;

    /**
     *
     * @var string
     */
    public $iso_moneda;

    /**
     *
     * @var string
     */
    public $nombre_moneda;

    /**
     *
     * @var string
     */
    public $nacionalidad;

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
            'iso2' => 'iso2', 
            'iso3' => 'iso3', 
            'prefijo' => 'prefijo', 
            'pais' => 'pais', 
            'continente' => 'continente', 
            'subcontinente' => 'subcontinente', 
            'iso_moneda' => 'iso_moneda', 
            'nombre_moneda' => 'nombre_moneda', 
            'nacionalidad' => 'nacionalidad', 
            'observacion' => 'observacion', 
            'estado' => 'estado', 
            'baja_logica' => 'baja_logica', 
            'agrupador' => 'agrupador'
        );
    }

}
