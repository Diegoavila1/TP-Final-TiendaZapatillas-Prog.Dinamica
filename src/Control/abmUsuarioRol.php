<?php

class abmUsuarioRol{

        // Espera como parámetro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
        public function abm($datos) {
            $resp = false;
    
            if ($datos['accion'] == 'editar') {
                if ($this->modificacion($datos)) {
                    $resp = true;
                }
            }

            if ($datos['accion'] == 'borrar') {
                if ($this->baja($datos)) {
                    $resp = true;
                }           
            }

            if ($datos['accion'] == 'nuevo') {
                if ($this->alta($datos)) {
                    $resp = true;
                }
            }
    
            return $resp;
        }
    
        /**
         * Espera como parámetro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
         * @param array $param
         * @return Auto
         */
        private function cargarObjeto($param) {
            $obj = null;
            if (array_key_exists('idRol', $param) && array_key_exists('rolDescripcion', $param)) {
                $obj = new Rol();
                $obj->cargar($param['idRol'], $param['rolDescripcion']);
               
            }
            return $obj;
        }
    
        /**
         * Espera como parámetro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
         * @param array $param
         * @return Auto
         */
        private function cargarObjetoConClave($param) {
            $obj = null;
            if (isset($param['idRol'])) {
                $obj = new Rol();
                $obj->cargar($param['idRol'], null, null, null);
            }
            return $obj;
        }
    
        /**
         * Corrobora que dentro del arreglo asociativo están seteados los campos claves
         * @param array $param
         * @return boolean
         */
        private function seteadosCamposClaves($param) {
            $resp = false;
            if (isset($param['idRol']))
                $resp = true;
            return $resp;
        }
    
        /**
         * Da de alta un auto
         * @param array $param
         * @return boolean
         */
        public function alta($param) {
            $resp = false;
            $elObjtTabla = $this->cargarObjeto($param);
            if ($elObjtTabla != null && $elObjtTabla->insertar()) {
                $resp = true;
            }
            return $resp;
        }
    
        /**
         * Permite eliminar un objeto
         * @param array $param
         * @return boolean
         */
        public function baja($param) {
            $resp = false;
            if ($this->seteadosCamposClaves($param)) {
                $elObjtTabla = $this->cargarObjetoConClave($param);
                if ($elObjtTabla != null && $elObjtTabla->eliminar()) {
                    $resp = true;
                }
            }
    
            return $resp;
        }
    
        /**
         * Permite modificar un objeto
         * @param array $param
         * @return boolean
         */
    
        public function modificacion($param) {
            $resp = false;

            if ($this->seteadosCamposClaves($param)) {
                $elObjtTabla = $this->cargarObjeto($param);
                if ($elObjtTabla != null && $elObjtTabla->modificar()) {
                    $resp = true;
                }                
            }

            return $resp;
        }
    
        /**
         * Permite buscar un objeto
         * @param array $param
         * @return array
         */
        public function buscar($param) {
            $where = " true ";
            if ($param <> NULL) {
                if (isset($param['idRol']))
                    $where .= " and idRol ='" . $param['idRol'] . "'";
                if (isset($param['rolDescripcion']))
                    $where .= " and rolDescripcion ='" . $param['rolDescripcion'] . "'";
            }
            
            $obj = new Rol();
            $arreglo = $obj->listar($where);
            return $arreglo;
        }
    
        public function obtenerDatos($param){
            $where = " true ";
            if ($param <> NULL) {
                if (isset($param['idRol']))
                    $where .= " and idRol ='" . $param['idRol'] . "'";
                if (isset($param['rolDescripcion']))
                    $where .= " and rolDescripcion ='" . $param['rolDescripcion'] . "'";
            }

            $obj = new Rol();
            $arreglo = $obj->listar($where);
            $result = [];
            if (!empty($arreglo)) {
                foreach ($arreglo as $rol) {
                    $result[] = ["idRol" => $rol->getRol(),
                                 "rolDescripcion" => $rol->getRolDescripcion()];
                }
            }
            return $result;
        }    
        
}
