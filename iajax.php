<?php

sleep(2);
header('content-type: application/json; charset=utf-8');//HEADER PARA JSON
include_once 'php/puntosDAO.php';
$ac = isset($_POST["tipo"])?$_POST["tipo"]:"x"; //PARAMETRO PARA DETERMINAR LA ACCION

switch ($ac) {
    case "grabar":
        $p = new puntosDao();
        $exito = $p->grabar($_POST["idusuariobd"],$_POST["tdelito"],$_POST["direccion"],$_POST["descripcion"],$_POST["fechadelito"],$_POST["horadelito"],$_POST["latitud"],$_POST["longitud"]);
        if($exito)
        {
            $r["estado"] = "ok";
            $r["mensaje"] = "Recibido Correctamente";
        }
        else
        {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Enviar!";
        }
    break;

    case "listar":
        $p = new puntosDao();
        $resultados = $p->listar_todo();
        if(sizeof($resultados)>0)
        {
            $r["estado"] = "ok";
            $r["mensaje"] = $resultados;
        }
        else
        {
            $r["estado"] = "error";
            $r["mensaje"] = "No hay registros";
        }
    break;

   default:
        $r["estado"] = "error";
        $r["mensaje"] = "Datos no Válidos";
    break;
}
echo json_encode($r);//IMPRIMIR JSON
?>
