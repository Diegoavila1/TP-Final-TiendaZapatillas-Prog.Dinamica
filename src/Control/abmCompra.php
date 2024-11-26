<?php

class abmCompra
{
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
    private function cargarObjeto($param)
    {
        $obj = null;
        if (array_key_exists('idusuario', $param) && array_key_exists('idcompra', $param)) {
            $obj = new Compra();
            $obj->setear($param);
        }
        return $obj;
    }

    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if (isset($param['idcompra'])) {
            $obj = new Compra();
            $obj->setear($param);
        }
        return $obj;
    }

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idcompra']))
            $resp = true;
        return $resp;
    }

    public function alta($param)
    {
        $resp = false;
        $param['idcompra'] = null;
        $param['cefechaini'] = null;
        $param['idcompraestadotipo'] = 1;
        $param['cefechafin'] = null;
        
        $elObjtArchivoE = $this->cargarObjeto($param);
        if ($elObjtArchivoE != null and $elObjtArchivoE->insertar()) {
            $param['idcompra'] = $elObjtArchivoE->getIdcompra();
            $param['accion'] = 'nuevo';
            $objCompraEstado = new abmCompraEstado();
            $objCompraEstado->abm($param);
            $objCompraItem = new abmCompraItem();
            $objCompraItem->abm($param);
            $resp = true;
        }
        return $resp;
    }

    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtArchivoE = $this->cargarObjetoConClave($param);
            if ($elObjtArchivoE != null and $elObjtArchivoE->eliminar()) {
                $resp = true;
            }
        }

        return $resp;
    }

    public function modificacion($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtArchivoE = $this->cargarObjeto($param);
            if ($elObjtArchivoE != null and $elObjtArchivoE->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param)
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idcompra']))
                $where .= " and idcompra =" . $param['idcompra'];
            if (isset($param['cofecha']))
                $where .= " and cofecha =" . $param['cofecha'];
            if (isset($param['idusuario']))
                $where .= " and idusuario ='" . $param['idusuario'] . "'";
        }
        $obj = new Compra();
        $arreglo = $obj->listar($where);

        return $arreglo;
    }

    public function obtenerDatos($param) {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idcompra']))
                $where .= " and idcompra =" . $param['idcompra'];
            if (isset($param['idusuario']))
                $where .= " and idusuario ='" . $param['idusuario'] . "'";
            if (isset($param['cofecha']))
                $where .= " and cofecha ='" . $param['cofecha'] . "'";
        }

        $obj = new Compra();
        $arreglo = $obj->listar($where);
        $result = [];

        if (!empty($arreglo)) {
            foreach ($arreglo as $compra) {
                $result[] = [
                    "idcompra" => $compra->getIdcompra(),
                    "idusuario" => $compra->getIdusuario(),
                    "cofecha" => $compra->getCofecha()
                ];
            }
        }
        return $result;
    }

    public function iniciarCompra($datos){
        $abmCompraEstado = new abmCompraEstado();
        $abmCompraItem = new abmCompraItem();
        $abmCompra = new abmCompra();
        $session = new Session();
        $result = false;

        $productos = $datos['productos'];   

        foreach($productos as $producto){
            $datos['accion'] = 'nuevo';
            $datos['idusuario'] = $session->getUsuario()['idusuario'];
            $datos['cofecha'] = date('Y-m-d H:i:s');
            $datos['idproducto'] = $producto['idproducto'];
            $datos['cicantidad'] = $producto['cantidad'];
            if($abmCompra->abm($datos)){
                $result = true;
            }
        }
    }


    public function enviarCorreo($datos){
        $bool = false;
        $session = new Session();
        
        $estado = $datos['estado'];

        $cliente = $session->getUsuario();
        
        if ($cliente != null) {
            $bool = true;
            $headers = "From: zapatillasempresaseria@gmail.com\r\n";
            $headers .= "To: {$cliente['usmail']}\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();

            if($estado == 'iniciado'){
                $to = $cliente['usmail'];
                $pedidos = $datos['productos'];
                $subject = "Sus pedidos están siendo procesados";
                $message = "Gracias por tu compra ".$cliente['usnombre']."!. Te informamos que tus pedidos están siendo procesados por separado, 
                esto quiere decir que evaluamos individualmente para que en caso de no contar con el stock de un solo par, nos aseguramos que te lleguen los demas, 
                puede ocurrir que los paquetes los recibas en diferido.\n
                Tienes un total de ".count($pedidos)." pedidos activos.\n
                -------------------------\n";
                foreach ($pedidos as $pedido) {
                    $message .= "Producto: " . $pedido['nombre'] . "\n";
                    $message .= "Cantidad: " . $pedido['cantidad'] . "\n";
                    $message .= "Precio Total: $" . $pedido['precio'] * $pedido['cantidad'] . " USD\n";
                    $message .= "-------------------------\n";
                }
                mail($to, $subject, $message, $headers);
            }
            
            if ($estado == 'aceptado'){
                $compra = $datos['compra'];
                $abmCompraItem = new abmCompraItem();
                $abmProducto = new abmProducto();
                $abmUsuario = new abmUsuario();
                $abmCompra = new abmCompra();
                $compraItem = $abmCompraItem->obtenerDatos(['idcompra' => $compra])[0];
                $producto = $abmProducto->obtenerDatos(['idproducto' => $compraItem['idproducto']])[0];
                $compra = $abmCompra->obtenerDatos(['idcompra' => $compra])[0];
                $cliente = $abmUsuario->obtenerDatos(['idusuario' => $compra['idusuario']])[0];
                $to = $cliente['usmail'];
        
                $subject = "Su pedido por el producto ".$producto['pronombre']." ha sido aceptado";
                $message = "Gracias por tu compra ".$cliente['usnombre']."!
                su pedido se ha aceptado, en breve se le enviará un correo avisando cuando este despachado.
                debera llegarle ". $compraItem['cicantidad'] ." unidades del producto y el precio abonado fue de $". $compraItem['cicantidad'] * $producto['proprecio'] ." USD\n";
                mail($to, $subject, $message, $headers);
                $estado = 'listoParaEnviar';
            }
        
            if($estado == 'rechazado'){
                $compra = $datos['compra'];
                $abmCompraItem = new abmCompraItem();
                $abmProducto = new abmProducto();
                $abmUsuario = new abmUsuario();
                $abmCompra = new abmCompra();
                $compraItem = $abmCompraItem->obtenerDatos(['idcompra' => $compra])[0];
                $producto = $abmProducto->obtenerDatos(['idproducto' => $compraItem['idproducto']])[0];
                $compra = $abmCompra->obtenerDatos(['idcompra' => $compra])[0];
                $cliente = $abmUsuario->obtenerDatos(['idusuario' => $compra['idusuario']])[0];
                $to = $cliente['usmail'];
                $subject = "Su pedido por el producto ".$producto['pronombre']." ha sido rechazado";
                $message = "Gracias por confiar en nosotros ".$cliente['usnombre']."!.
                Lamentablemente no podemos completar su pedido ya que ha solicitado una cantidad de productos mayor al stock disponible. 
                Le pedimos disculpas por cualquier inconveniente que esto pueda causar.
                En un plazo de 72hs habiles se le devolverá el dinero abonado por el pedido.\n";
                mail($to, $subject, $message, $headers);
            }
            if ($estado == 'listoParaEnviar'){
                $compra = $datos['compra'];
                $abmCompraItem = new abmCompraItem();
                $abmProducto = new abmProducto();
                $abmUsuario = new abmUsuario();
                $abmCompra = new abmCompra();
                $compraItem = $abmCompraItem->obtenerDatos(['idcompra' => $compra])[0];
                $producto = $abmProducto->obtenerDatos(['idproducto' => $compraItem['idproducto']])[0];
                $compra = $abmCompra->obtenerDatos(['idcompra' => $compra])[0];
                $cliente = $abmUsuario->obtenerDatos(['idusuario' => $compra['idusuario']])[0];
                $to = $cliente['usmail'];
                $subject = "Su pedido por el producto ".$producto['pronombre']." ha sido despachado";
                $message = "Gracias por tu compra ".$cliente['usnombre']."!
                su pedido ha sido despachado.
                debera llegarle ". $compraItem['cicantidad'] ." unidades del producto y el precio abonado fue de $". $compraItem['cicantidad'] * $producto['proprecio'] ." USD\n";
                mail($to, $subject, $message, $headers);
            }

        }
        return $bool;
    }
    
}