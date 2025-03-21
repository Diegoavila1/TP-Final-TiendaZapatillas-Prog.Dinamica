<?php

class Producto extends BaseDatos
{
    private $idproducto;
    private $pronombre;
    private $prodetalle;
    private $procantstock;
    private $proprecio;
    private $promarca;
    private $proimagen1;
    private $proimagen2;
    private $proimagen3;
    private $proimagen4;
    private $mensajeOperacion;


    // Getters
    public function getIdproducto()
    {
        return $this->idproducto;
    }
    public function getPronombre()
    {
        return $this->pronombre;
    }

    public function getProdetalle()
    {
        return $this->prodetalle;
    }
    public function getProcantstock()
    {
        return $this->procantstock;
    }
    
    public function getProPrecio()
    {
        return $this->proprecio;
    }
    public function getPromarca() {
        return $this->promarca;
    }

    public function getProimagen1()
    {
        return $this->proimagen1;
    }

    //SETTERS
    public function setIdproducto($idproducto)
    {
        $this->idproducto = $idproducto;
    }
    public function setPronombre($pronombre)
    {
        $this->pronombre = $pronombre;
    }
    public function setProdetalle($prodetalle)
    {
        $this->prodetalle = $prodetalle;
    }
    public function setProcantstock($procantstock)
    {
        $this->procantstock = $procantstock;
    }

    public function setProPrecio($proprecio)
    {
        $this->proprecio = $proprecio;
    }

    public function setProimagen1($proimagen1)
    {
        $this->proimagen1 = $proimagen1;
    }


    public function setMensajeOperacion($mensajeOperacion)
    {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    public function setPromarca($promarca){
        $this->promarca = $promarca;
    }


    // Metodos
    public function setear($param)
    {
        $this->setIdproducto($param['idproducto']);
        $this->setPronombre($param['pronombre']);
        $this->setProPrecio($param['proprecio']);
        $this->setpromarca($param['promarca']);
        $this->setProdetalle($param['prodetalle']);
        $this->setProcantstock($param['procantstock']);
        $this->setProimagen1($param['proimagen1']);
    }

    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();

        $sql = "SELECT * FROM producto WHERE idproducto = '" . $this->getIdproducto() . "'";

        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();
                    $this->setear($row);
                    $resp = true;
                }
            }
        } else {
            $this->setMensajeOperacion("Producto->listar: " . $base->getError());
        }

        return $resp;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();

        $sql = "INSERT INTO producto (pronombre, prodetalle, procantstock, proprecio, promarca, proimagen1) VALUES ('".$this->getPronombre()."', '".$this->getProdetalle()."',".$this->getProcantstock().",".$this->getProPrecio().",'".$this->getProMarca()."','".$this->getProimagen1()."')";
        if ($base->Iniciar()) {
            if ($base = $base->Ejecutar($sql)) {
                $this->setIdproducto($base);
                $resp = true;
            } else {
                $this->setMensajeOperacion("Producto->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Producto->insertar: " . $base->getError());
        }
        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();

        $sql = "UPDATE `producto` SET pronombre = '" . $this->getPronombre() . "', prodetalle = '" . $this->getProdetalle() . "', proprecio = " . $this->getProPrecio() . ", procantstock = " . $this->getProcantstock() . ", promarca = '" . $this->getPromarca() . "', proimagen1 = '" . $this->getProimagen1() . "' WHERE idproducto = " . $this->getIdproducto() . ";";
        if($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Producto->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Producto->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM producto WHERE idproducto='" . $this->getIdproducto() . "'";
        
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                return true;
            } else {
                $this->setMensajeOperacion("Producto->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Producto->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public function listar($parametro = "")
    {
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM producto ";
        
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $obj = new Producto();
                    $obj->setear($row);
                    $arreglo[] = $obj;
                }
            }
        } else {
            $this->setMensajeOperacion("Producto->listar: " . $base->getError());
        }
        return $arreglo;
    }

}