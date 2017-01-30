<?php
class conex
{
  public static function con()
  {
    $conexion = mysql_connect("localhost","root","");
    mysql_select_db("testcrimapp");
    mysql_query("SET NAMES 'utf8'");
    if (!$conexion)
    {
      return FALSE;
    }
    else
    {
      return $conexion;
    }
  }
}

?>
