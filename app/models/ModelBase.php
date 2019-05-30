<?php

class ModelBase extends \Phalcon\Mvc\Model {

    protected $orcl;

    public function initialize() {
        // $this->hasOne('id','contrataciones','modalidad_id');        
        // $this->setConnectionService('oracle');   
        $this->hasMany('id', 'contrataciones', 'modalidad_id');
       // $this->orcl = $this->getDI()->get('oracle');
    }

    //metodo findAll = Select
    public function findAll($sql) {
        $result = $this->orcl->query($sql);
        $result->setFetchMode(\Phalcon\Db::FETCH_OBJ);
        return $result;
    }

    public function insert($tabla = false, $data = false) {
        if (!(($tabla == false) or ($data == false))) {
            $vcampo = '';
            $vvalor = '';
            $insertar = "INSERT INTO " . $tabla;
            foreach ($data as $campo => $valor) {
                $vcampo .= $campo . ",";
                $vvalor .= (is_numeric($valor) && (intval($valor) == $valor)) ? $valor . "," : "'$valor',";
            }
            //quito la ultima coma ingresada   
            $vcampo = substr($vcampo, 0, - 1);
            $vvalor = substr($vvalor, 0, - 1);
            $insertar .= " (" . $vcampo . ") VALUES (" . $vvalor . ")";
            try {
                $actualiza = $this->orcl->prepare($insertar);
                $actualiza->execute();
                return true;
            } catch (Exception $e) {
                //die ('Excepción capturada: '. $e->getMessage());
                return false;
            }
        } else {
            // die('Los Parametros no son correctos');
            return false;
        }
    }

    public function update($tabla = false, $data = false, $condicion = false) {
        if (!(($tabla == false) or ($data == false))) {
            $update = "UPDATE " . $tabla . " SET ";
            foreach ($data as $campo => $valor) {
                $update .= $campo . "=";
                $update .= (is_numeric($valor) && (intval($valor) == $valor)) ? $valor . "," : "'$valor',";
            }
            //quito la ultima coma ingresada   
            $update = substr($update, 0, - 1);
            if ($condicion) {
                $update .= " WHERE " . $condicion;
            }
            try {
                $actualiza = $this->orcl->prepare($update);
                $actualiza->execute();
                return true;
            } catch (Exception $e) {
                //die ('Excepción capturada: '. $e->getMessage());
                return false;
            }
        } else {
            //die('Los Parametros no son correctos');
            return false;
        }
    }

    public function deleteQuery($sql) {
        try {
            $pStatement = $this->orcl->prepare($sql);
            $pStatement->execute();
            return true;
        } catch (Exception $e) {
            //die ('Excepción capturada: '. $e->getMessage());
            return false;
        }
    }

    public function exec($sql) {
        try {
            $pStatement = $this->orcl->prepare($sql);
            $pStatement->execute();
            return true;
        } catch (Exception $e) {
            //die ('Excepción capturada: '. $e->getMessage());
            return false;
        }
    }

}

