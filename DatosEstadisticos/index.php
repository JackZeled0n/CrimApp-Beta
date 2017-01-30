<?php
  include_once ("../php/connection.php")
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
  <title>CrimApp</title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="../css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="../css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="../css/foo.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <!--api google & jquery-->
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCeItQZCR_6xO8IgjPUJqnuZM3VYdtYaa8&sensor=false"></script>
  <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.4.min.js"></script>

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  <style type="text/css">
${demo.css}
  </style>
  <script type="text/javascript">
$(function () {
  $('#container').highcharts({
      title: {
          text: 'Genero mas agredido',
          x: -20 //center
      },
      subtitle: {

          x: -20
      },
      xAxis: {
          categories: [
            <?php
              $sql = "SELECT * from reporte";
              $result = mysqli_query($connection,$sql);
              while ($registros = mysqli_fetch_array($result))
              {
                ?>
                '<?php echo $registros["FECHA"] ?>',
                <?php
              }
             ?>
          ]
      },
      yAxis: {
          title: {
              text: ''
          },
          plotLines: [{
              value: 0,
              width: 1,
              color: '#808080'
          }]
      },
      tooltip: {
          valueSuffix: ''
      },
      legend: {
          layout: 'vertical',
          align: 'right',
          verticalAlign: 'middle',
          borderWidth: 0
      },
      series: [{
          name: 'masculino',
          data: [
            <?php
              $sql = "SELECT count(genero.NOMBRE) as total,reporte.FECHA FROM genero INNER JOIN usuario ON genero.ID_GENERO=usuario.ID_GENERO INNER JOIN reporte ON usuario.ID_USUARIO=reporte.ID_USUARIO WHERE genero.NOMBRE = 'masculino' GROUP BY reporte.FECHA";
              $result = mysqli_query($connection,$sql);
              while ($registros = mysqli_fetch_array($result))
              {
                ?>
                <?php echo $registros["total"] ?>,
                <?php
              }
             ?>
          ]},
          {
            name: 'femenino',
            data: [
              <?php
                $sql = "SELECT count(genero.NOMBRE) as total,reporte.FECHA FROM genero INNER JOIN usuario ON genero.ID_GENERO=usuario.ID_GENERO INNER JOIN reporte ON usuario.ID_USUARIO=reporte.ID_USUARIO WHERE genero.NOMBRE = 'femenino' GROUP BY reporte.FECHA";
                $result = mysqli_query($connection,$sql);
                while ($registros = mysqli_fetch_array($result))
                {
                  ?>
                  <?php echo $registros["total"] ?>,
                  <?php
                }
               ?>
            ]
      }]
  });
});
  </script>

  <!--3d pie-->

  <style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
$(function () {
    $('#containerpie').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'Registro de delitos'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Porcentaje',
            data: [
                ['Hurto Simple',   1],
                ['Hurto Agravado',       2],
                {
                    name: 'Hurto de Uso',
                    y: 3,
                    sliced: true,
                    selected: true
                },
                ['Robo con fuerza en las cosas',    4],
                ['Robo con Violencia o Intimidacion en las Personas',     5],
                ['Robo Agravado',   6]
            ]
        }]
    });
});
		</script>

  <!--Map-->
  <script>
    var marcadores_bd = [];
    var mapa = null;

    $(document).on("ready", function(){

      var formulario = $("#formulario");

      var punto = new google.maps.LatLng(12.1149926,-86.23617439999998);
      var config = {
        zoom:13,
        center:punto,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };

      mapa = new google.maps.Map( $("#mapa")[0], config );

        listar();
      });

      function listar() {
        $.ajax({
          type:"POST",
          url:"../iajax.php",
          dataType:"JSON",
          data:"&tipo=listar",
          success:function(data){

            if(data.estado == "ok")
            {

              $.each(data.mensaje,function(i,item) {

                var posi = new google.maps.LatLng(item.LATITUD,item.LONGITUD);

                var marca = new google.maps.Marker({
                  idMarcador:item.FECHA,
                  position:posi,
                  titulo: item.NDELITO
                });

                google.maps.event.addListener(marca, "click" , function () {
                  alert ("Fecha: "+marca.idMarcador+" \nTipo de delito: "+marca.titulo);
                });
                marcadores_bd.push(marca);
                marca.setMap(mapa);

              });
            }
            else {
              alert("mal");
            }
          },
          beforeSend:function(){
          },
          complete:function(){

          }
        });
      }
  </script>

</head>
<body class="grey lighten-5">
  <div class="container section">
    <h3 class="center blue-grey-text text-darken-3 ">Datos Estadisticos</h3>
  </div>
  <div class="section">
    <div class="row">

      <div class="col s12 m6">
        <div class="col s12">
          <script src="../js/highcharts.js"></script>
          <script src="../js/modules/exporting.js"></script>
          <div id="container" class="z-depth-4" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
        <div class="col s12">
          <script src="../js/highcharts-3d.js"></script>
          <div id="containerpie" class="z-depth-4" style="height: 400px;"></div>
        </div>
      </div>
      <div class="col s12 m6">
        <div class="z-depth-1" id="mapa" style="height: 800px"></div>
    </div>
  </div>
    </div>
  </div>
</body>
</html>
