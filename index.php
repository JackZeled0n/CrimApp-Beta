<?php
  require 'app/fb_init.php';
  mysql_connect("localhost","root","");
  mysql_select_db("testcrimapp");
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
  <title>CrimApp</title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/foo.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <!--reCaptcha-->
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <!--api google & jquery-->
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCeItQZCR_6xO8IgjPUJqnuZM3VYdtYaa8&sensor=false"></script>
  <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.4.min.js"></script>

  <!--Map-->
  <script>

  //lista con nuevos markers
  var markers_nuevos = [];

  //Quitar markers de mapa

  function quitar_markers(coorlist)
  {
    for (i in coorlist)
    {
      coorlist[i].setMap(null);
    }

  }

  $(document).on("ready", function(){

    var formulario = $("#formulario");

    var punto = new google.maps.LatLng(12.1149926,-86.23617439999998);
    var config = {
      zoom:13,
      center:punto,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var mapa = new google.maps.Map( $("#mapa")[0], config );

    google.maps.event.addListener(mapa, "click", function(event){

      var coor = event.latLng.toString();

      coor = coor.replace("(","");
      coor = coor.replace(")","");

      var coorlist = coor.split(",");

      var direccion = new google.maps.LatLng(coorlist[0], coorlist[1]);



      var mkr = new google.maps.Marker({
        position: direccion,
        map: mapa,
        animation: google.maps.Animation.Drop,
        draggable: false
      });

      //coordenas al formulario
      formulario.find("input[name='longitud']").val(coorlist[1]);
      formulario.find("input[name='latitud']").val(coorlist[0]);

      //un solo marker en el mapa
      markers_nuevos.push(mkr);


      google.maps.event.addListener(mkr, "click", function() {

      });

      //antes de ubicar el marker en el mapa quitar todos los demas
      //ubicar solo un unico marker
      quitar_markers(markers_nuevos);


      mkr.setMap(mapa);

    });

    $("#btn_grabar").on("click",function(){

      var f = $("#formulario");
      if(f.hasClass("busy"))
      {
        return false;
      }
      f.addClass("busy");
      var loader_grabar = $("#loader_grabar");
      $.ajax({
        type:"POST",
        url:"iajax.php",
        dataType:"JSON",
        data:f.serialize()+"&tipo=grabar",
        success:function(data){

          if (data.estado=="ok")
          {
            loader_grabar.addClass("flow-text").text("Guardado").delay(4000).slideUp();
          }
          if (data.estado=="error")
          {
            loader_grabar.addClass("flow-text").text("Error").delay(4000).slideUp();
          }

        },
        beforeSend:function(){
          loader_grabar.addClass("flow-text").text("Enviando...").slideDown();
        },
        complete:function(){
          f.removeClass("busy");
          f[0].reset();

        }
      });
      return false;
    })



    });
</script>

</head>
<body class="grey lighten-5">
   <!--Menu -->
   <div class="navbar-fixed">
    <nav class="blue-grey darken-3">
      <div class="nav-wrapper container">
        <a href="#!" class="brand-logo yellow-text">CrimApp</a>
        <a href="#" data-activates="mobile" class="button-collapse yellow-text text-accent-2 "><i class="material-icons">menu</i></a>
        <ul class="right hide-on-med-and-down">
          <li><a class="yellow-text text-accent-2" href="index.php">INICIO</a></li>
          <li><a class="yellow-text text-accent-2" href="#caracteristicas">CARACTERISTICAS</a></li>
          <li><a class="yellow-text text-accent-2" href="#reportes">REPORTES</a></li>
          <li><a class="yellow-text text-accent-2" href="#datosestadisticos">DATOS ESTADISTICOS</a></li>
          <!--Login facebook-->
          <?php

          if(isset($_SESSION['facebook_access_token'])){
          	$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
          	try
            {
          	  $response = $fb->get('/me?fields=id,first_name,last_name,name, link,picture ,email,gender');
              #$userData = $response->getDecodedBody();
          	  $userNode = $response->getGraphUser();
          	}
            catch(Facebook\Exceptions\FacebookResponseException $e)
            {
          	  echo 'Graph returned an error: ' . $e->getMessage();
          	  exit;
          	}
            catch(Facebook\Exceptions\FacebookSDKException $e)
            {
          	  echo 'Facebook SDK returned an error: ' . $e->getMessage();
          	  exit;
          	}

            $fname = $userNode->getFirstName();
            $name = $userNode->getName();
            $imageb = 'https://graph.facebook.com/'.$userNode->getId().'/picture?width=18 ';
            $imagen = 'https://graph.facebook.com/'.$userNode->getId().'/picture?width=200';
            $correo = $userNode->getProperty('email');
            $fbgender = $userNode->getProperty('gender');
            $fbid = $userNode->getId();
            #boton Mostrar informacion de usuario
            echo "<li><a class='waves-effect waves-light btn-large btn modal-trigger' style='background:#36528C' data-target='modal1'><i class='material-icons left'><img src='$imageb' /></i>Hola, ",$fname,"</a></li>";

            if ($fbgender== "male")
            {
              $idgenero = 2;
            }
            else
            {
              $idgenero = 1;
            }
            //Ejecuto la sentencia
            $consultausuario = "SELECT NOMBRE,ID_FACEBOOK FROM USUARIO WHERE NOMBRE='$name' and CORREO='$correo' ";
            // Ejecutar la consulta
            $resultadousuario = mysql_query($consultausuario);
            //retorna el numero de resultados de la consulta
            $total = mysql_num_rows($resultadousuario);
            if($total==0)
            {
              $_guardar_usuario = "INSERT INTO USUARIO(NOMBRE,CORREO,ID_FACEBOOK,ID_GENERO) VALUES ('$name','$correo','$fbid','$idgenero')";
              $iuc = mysql_query($_guardar_usuario);
            }

            mysql_free_result($resultadousuario);

          }
          else
          {
          	$helper = $fb->getRedirectLoginHelper();
          	$permissions = ['email'];
          	$loginUrl = $helper->getLoginUrl('http://localhost:8080/CrimApp/login-callback.php', $permissions);
            echo '<li><a class="waves-effect waves-light btn-large z-depth-3" style="background:#36528C" href="' . $loginUrl . '"><i class="material-icons left"><img src="img/fblogo.png" /></i>Iniciar Sesión</a></li>';
          }
           ?>

        </ul>
        <ul class="side-nav blue-grey darken-3 " id="mobile">
          <li><a class="yellow-text text-accent-2" href="active">INICIO</a></li>
          <li><a class="yellow-text text-accent-2" href="#caracteristicas">CARACTERISTÎCAS</a></li>
          <li><a class="yellow-text text-accent-2" href="#reportes">REPORTES</a></li>
          <li><a class="yellow-text text-accent-2" href="">DATOS ESTADISTICOS</a></li>
          <!--Facebook login mobile-->
          <?php
          if(isset($_SESSION['facebook_access_token']))
          {
            echo "<li><a class='waves-effect waves-light btn-large btn modal-trigger' style='background:#36528C' data-target='modal1'><i class='material-icons left'><img src='$imageb' /></i>Hola, ",$fname,"</a></li>";
          }
          else
          {
            echo '<li><a class="waves-effect waves-light btn-large z-depth-3" style="background:#36528C" href="' . $loginUrl . '"><i class="material-icons left"><img src="img/fblogo.png" /></i>Iniciar Sesión</a></li>';
          }
          ?>
        </ul>
      </div>
    </nav>
  </div>

<!--modal popup muestra la info del usuario logeado-->

<?php
  echo '<div id="modal1" class="modal modal-fixed-footer"  style="background:#36528C">';
    echo '<div class="modal-content ">';
      echo '<h4 class="left white-text">Perfil de Usuario</h4>';
      echo '<div class="row">';
        echo '<div class="col s12 m12 l12">';
          echo '<div class="col s6 m6 l6">';
            echo "<img class='responsive-img' src='$imagen' />";
          echo '</div>';
          echo '<div class="col s6 m6 l6">';
            echo '<h5 class="left white-text">Nombre: ',$name,'</h5>';
            echo '<h5 class="left white-text">Correo: ',$correo,'</h5>';
            if ($fbgender== "male")
            {
              echo '<h5 class="left white-text">Genero: Masculino</h5>';
            }
            else {
              echo '<h5 class="left white-text">Genero: Femenino</h5>';
            }
          echo '</div>';
        echo '</div>';
        echo '<div class="col s12 center m12">';
          echo '<a class="waves-effect waves-light btn-large z-depth-3" style="background:#f44336" href="logout.php"><i class="material-icons left"><img src="img/fblogo.png" /></i>Cerrar Sesión</a>';
        echo '</div>';
      echo '</div>';
    echo '</div>';
    echo '<div class="modal-footer" style="background:#E2E5F0">';
      echo '<a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>';
    echo '</div>';
  echo '</div>';
 ?>

    <!--Parallax-->

  <div id="index-banner" class="parallax-container">
    <div class="parallax responsive-img" ><img src="img/bg.jpg" alt="Unsplashed background img 1" style="display: block; transform: translate3d(-50%, 273px, 0px);"></div>
  </div>

  <!--Caracteristicas-->
  <div id="caracteristicas">
    <div class="container">
      <div class="section">
        <div class="col 12" ><h3 class="center blue-grey-text text-darken-3 ">Características</h3></div>
        <div class="row">


            <div class="col s12 m4 l4">
              <div class="center promo">
                  <img src='img/forma.png'/>
                  <p class="promo-caption">Reportes</p>
                  <p class="light center">Reportes de crimenes de una manera interactiva.</p>
                </div>
            </div>

            <div class="col s12 m4 l4">
              <div class="center promo">
                  <img src='img/mapa-de-ubicacion.png'/>
                  <p class="promo-caption">Mapa Interactivo</p>
                  <p class="light center">Mapa de las zonas rojas de Managua.</p>
                </div>
            </div>

            <div class="col s12 m4 l4">
              <div class="center promo">
                  <img src='img/grafico-de-lineas.png'/>
                  <p class="promo-caption">Datos Estadísticos</p>
                  <p class="light center">Datos generados por los reportes de los usuarios.</p>
                </div>
            </div>

        </div>
      </div>
    </div>
  </div>


  <!--Reporte-->
  <div id="reportes">
    <div class="divider"></div>
    <form id="formulario" method="post">
      <div class="container">
        <div class="section">
          <div class="col s12" ><h3 class="center blue-grey-text text-darken-3">Reportes</h3></div>
          <div class="row">
            <div class="col s12 m6">

              <div class="input-field col s12">
                  <select name='tdelito' required>
                    <option value="" disabled selected>Tipo de Delito</option>
                    <optgroup label="HURTOS">
                      <option value='1'>Hurto Simple</option>
                      <option value='2'>Hurto Agravado</option>
                      <option value='3'>Hurto de Uso</option>
                      <optgroup label="ROBOS">
                      <option value='4'>Robo con fuerza en las cosas</option>
                      <option value='5'>Robo con Violencia o Intimidacion en las Personas</option>
                      <option value='6'>Robo Agravado</option>
                  </select>
              </div>

              <div class="input-field col s12">
                <textarea  id="dirref" class="materialize-textarea" length="120" name="direccion"></textarea>
                <label for="textarea1">Dirección - Referencia</label>
              </div>

              <div class="input-field col s12">
                <textarea id="DescripciondelIncidente" class="materialize-textarea" length="250" name="descripcion"></textarea>
                <label for="textarea1">Descripción del Incidente</label>
              </div>

              <div class="input-field col s6">
                <label>Fecha del Delito</label>
                <br></br>
                <input type="date" class="datepicker"  name="fechadelito">
              </div>

              <div class="input-field col s6">
                <label>Hora del Delito</label>
                <br></br>
                <input type="time" class="timepicker" name="horadelito">
              </div>

              <div class="input-field col s6">
                <h6>Latitud</h6>
                <input class="validate" readonly name="latitud">
              </div>

              <div class="input-field col s6">
                <h6>Longitud</h6>
                <input class="validate" readonly name="longitud">
              </div>
            </div>

            <!--Id usuario-->
            <?php
              #buscar en bd id usuario
              if (isset($correo))
              {
                $bbdiu = "SELECT ID_USUARIO FROM USUARIO WHERE NOMBRE='$name' and CORREO='$correo'";
                $rbdiu = mysql_query($bbdiu);
                while ($fbdiu = mysql_fetch_assoc($rbdiu))
                {
                    break;
                }
                $iduser = $fbdiu['ID_USUARIO'];
                mysql_free_result($rbdiu);
              }

            ?>

            <input type="text" class="hide" name="idusuariobd" value="<?php echo $iduser ?>" >

            <!--Mapa-->
            <div class="col s12 m6" >
              <div class="z-depth-4" id="mapa"></div>
            </div>
          </div>


          <!--REload-->
          <div class=" col s12 m6"><span id ="loader_grabar" class=""></span></div>

          <!--Boton--->

          <div class="col s12">
            <button class="btn waves-effect waves-light blue-grey darken-3 yellow-text text-accent-2" id="btn_grabar" type="submit" name="action">Enviar
              <i class="material-icons right">send</i>
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>

  <!--Datos estadisticos-->
  <div class="divider"></div>
  <div class="container">
    <div class="section">
      <div class="col 12" id="datosestadisticos"><h3 class="center blue-grey-text text-darken-3">Datos Estadísticos</h3></div>
      <div class="row">

          <div class="col s12 m12" style="text-align:center">
            <img   class="responsive-img" src="img/estadistica.png" width="400" height="400">
          </div>

          <div class="col s12 center m12">
            <a class="waves-effect waves-light btn-large blue-grey darken-3 yellow-text text-accent-2" href="http://localhost:8080/CrimApp/DatosEstadisticos/" ><i class="material-icons right">insert_chart</i>Mostrar Datos Estadísticos</a>
          </div>
      </div>
    </div>
  </div>


  <!--footer-->

  <footer class="page-footer blue-grey darken-4">
    <div id="infrastructure" class="blue-grey darken-4">
      <div class="container">
        <div class="row">

          <div class="col l6 s12">
            <h5 class="yellow-text text-accent-2">Acerca de Nosotros</h5>
            <div class="col s4" ><img class="responsive-img"src="img/logoatm.png"/></div>
            <p class=""></p>
          </div>

          <div class="col l6 s12">
            <h5 class="yellow-text text-accent-2">Colaboradores</h5>
            <div class="section">
              <div class="col s8" ><img class="responsive-img"src="img/FU-White.svg"/></div>
              <div class="col s4" ><img class="responsive-img"src="img/UNI-White.svg"/></div>
            </div>
          </div>

        </div>
      </div>
    </div>
    <div class="footer-copyright blue-grey darken-4">
      <div class="container">
        <a class=" grey-text text-lighten-5 ">Made by <a class=" yellow-text text-accent-2" href="javascript:window.open('https://www.facebook.com/Atomic-Developers-1656333991272801/?fref=ts','','width=1400,height=600,left=50,top=50,toolbar=yes');void 0">Atomic Developers</a>
      </div>
    </div>
  </footer>



  <!--Scripts materialize-->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <script>
    //selectitem
    $(document).ready(function() {
      $('select').material_select();
    });

    //fecha
    $(document).ready(function(){
      var date = new Date();
      var currentMonth = date.getMonth();
      var currentDate = date.getDate();
      var currentYear = date.getFullYear();
      $('.datepicker').pickadate({

        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar',


        selectMonths: false,
        selectYears: 1,
        firstDay: true,
        min: new Date(currentYear,currentMonth,01),
        max: new Date(currentYear, currentMonth, currentDate),
        format: 'yyyy-mm-dd'
      });
    });
    //contador de caracteres
    $(document).ready(function() {
      $('input#input_text, textarea#textarea1').characterCounter();
    });
    //modal
    $(document).ready(function(){
      $('.modal-trigger').leanModal();
    });
  </script>

  </body>
</html>
