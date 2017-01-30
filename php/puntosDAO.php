<?php
include_once 'conex.php';//INCLUIR CONEXION DE BASE DE DATOS

class puntosDao
{
    private $r;
    public function __construct()
    {
        $this->r = array();
    }
    public function grabar($idusuario,$iddelito,$direccion,$descripcion,$fecha,$hdelito,$lat,$long)//METODO PARA GRABAR A LA BD
    {
        $con = conex::con();
        $idusuario = mysql_real_escape_string($idusuario);
        $iddelito = mysql_real_escape_string($iddelito);
        $direccion = mysql_real_escape_string($direccion);
        $descripcion = mysql_real_escape_string($descripcion);
        $fecha = mysql_real_escape_string($fecha);
        $hdelito = mysql_real_escape_string($hdelito);
        $lat = mysql_real_escape_string($lat);
        $long = mysql_real_escape_string($long);
        $q = "insert into reporte (ID_USUARIO,ID_DELITO,DIRECCION, DESCRIP, FECHA, HORA, LATITUD, LONGITUD)".
             "values ('".addslashes($idusuario)."','".addslashes($iddelito)."','".addslashes($direccion)."','".addslashes($descripcion)."','".addslashes($fecha)."','".addslashes($hdelito)."','".addslashes($lat)."','".addslashes($long)."')";
        $rpta = mysql_query($q, $con);
        mysql_close($con);
        if($rpta==1)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function listar_todo()
    {
        $q = "select * from reporte INNER JOIN delito on reporte.ID_DELITO=delito.ID_DELITO";
        $con = conex::con();
        $rpta = mysql_query($q,$con);
        mysql_close($con);
        while($fila = mysql_fetch_assoc($rpta))
        {
            $this->r[] = $fila;
        }
        return $this->r;
    }
 }
?>
