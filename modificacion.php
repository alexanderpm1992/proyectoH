<?php require_once('Connections/conexion_libros.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO hotel (nombre, razonsocial, rut, nombrecontacto, correo, direccion, telefono) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['razonsocial'], "text"),
                       GetSQLValueString($_POST['rut'], "text"),
                       GetSQLValueString($_POST['nombrecontacto'], "text"),
                       GetSQLValueString($_POST['correo'], "text"),
					   GetSQLValueString($_POST['direccion'], "text"),
					   GetSQLValueString($_POST['telefono'], "text"));

  mysql_select_db($database_conexion_libros, $conexion_libros);
  $Result1 = mysql_query($insertSQL, $conexion_libros) or die(mysql_error());

  $insertGoTo = "ingreso_exitoso.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$maxRows_consulta_libros = 10;
$pageNum_consulta_libros = 0;
if (isset($_GET['pageNum_consulta_libros'])) {
  $pageNum_consulta_libros = $_GET['pageNum_consulta_libros'];
}
$startRow_consulta_libros = $pageNum_consulta_libros * $maxRows_consulta_libros;

mysql_select_db($database_conexion_libros, $conexion_libros);
$query_consulta_libros = "SELECT * FROM hotel";
$query_limit_consulta_libros = sprintf("%s LIMIT %d, %d", $query_consulta_libros, $startRow_consulta_libros, $maxRows_consulta_libros);
$consulta_libros = mysql_query($query_limit_consulta_libros, $conexion_libros) or die(mysql_error());
$row_consulta_libros = mysql_fetch_assoc($consulta_libros);

if (isset($_GET['totalRows_consulta_libros'])) {
  $totalRows_consulta_libros = $_GET['totalRows_consulta_libros'];
} else {
  $all_consulta_libros = mysql_query($query_consulta_libros);
  $totalRows_consulta_libros = mysql_num_rows($all_consulta_libros);
}
$totalPages_consulta_libros = ceil($totalRows_consulta_libros/$maxRows_consulta_libros)-1;

$queryString_consulta_libros = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_consulta_libros") == false && 
        stristr($param, "totalRows_consulta_libros") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_consulta_libros = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_consulta_libros = sprintf("&totalRows_consulta_libros=%d%s", $totalRows_consulta_libros, $queryString_consulta_libros);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Modificar Usuario</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <section id="container" >
      <!-- **********************************************************************************************************************************************************
      TOP BAR CONTENT & NOTIFICATIONS
      *********************************************************************************************************************************************************** -->
     <!--header start-->
      <!--header start-->
      <header class="header black-bg">
              <div class="sidebar-toggle-box">
                  <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
              </div>
            <!--logo start-->
            <a href="index.html" class="logo"><b>Panel de Control</b></a>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu">
                <!--  notification start -->
                <ul class="nav top-menu">
                    <!-- settings start -->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">
                            Redes Sociales
                        </a>
                        <ul class="dropdown-menu extended tasks-bar">
                            <div class="notify-arrow notify-arrow-green"></div>
                            <li>
                                <p class="green">You have 4 pending tasks</p>
                            </li>
                            <li>
                                <a href="index.html#">
                                    <div class="task-info">
                                        <div class="desc">DashGum Admin Panel</div>
                                        <div class="percent">40%</div>
                                    </div>
                                    <div class="progress progress-striped">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                            <span class="sr-only">40% Complete (success)</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">
                                    <div class="task-info">
                                        <div class="desc">Database Update</div>
                                        <div class="percent">60%</div>
                                    </div>
                                    <div class="progress progress-striped">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                            <span class="sr-only">60% Complete (warning)</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">
                                    <div class="task-info">
                                        <div class="desc">Product Development</div>
                                        <div class="percent">80%</div>
                                    </div>
                                    <div class="progress progress-striped">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                            <span class="sr-only">80% Complete</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">
                                    <div class="task-info">
                                        <div class="desc">Payments Sent</div>
                                        <div class="percent">70%</div>
                                    </div>
                                    <div class="progress progress-striped">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
                                            <span class="sr-only">70% Complete (Important)</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="external">
                                <a href="#">See All Tasks</a>
                            </li>
                        </ul>
                    </li>
                    <!-- settings end -->
                    <!-- inbox dropdown start-->
                    <li id="header_inbox_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">
                            Canales de Ventas
                        </a>
                        <ul class="dropdown-menu extended inbox">
                            <div class="notify-arrow notify-arrow-green"></div>
                            <li>
                                <p class="green">You have 5 new messages</p>
                            </li>
                            <li>
                                <a href="index.html#">
                                    <span class="photo"><img alt="avatar" src="assets/img/ui-zac.jpg"></span>
                                    <span class="subject">
                                    <span class="from">Zac Snider</span>
                                    <span class="time">Just now</span>
                                    </span>
                                    <span class="message">
                                        Hi mate, how is everything?
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">
                                    <span class="photo"><img alt="avatar" src="assets/img/ui-divya.jpg"></span>
                                    <span class="subject">
                                    <span class="from">Divya Manian</span>
                                    <span class="time">40 mins.</span>
                                    </span>
                                    <span class="message">
                                     Hi, I need your help with this.
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">
                                    <span class="photo"><img alt="avatar" src="assets/img/ui-danro.jpg"></span>
                                    <span class="subject">
                                    <span class="from">Dan Rogers</span>
                                    <span class="time">2 hrs.</span>
                                    </span>
                                    <span class="message">
                                        Love your new Dashboard.
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">
                                    <span class="photo"><img alt="avatar" src="assets/img/ui-sherman.jpg"></span>
                                    <span class="subject">
                                    <span class="from">Dj Sherman</span>
                                    <span class="time">4 hrs.</span>
                                    </span>
                                    <span class="message">
                                        Please, answer asap.
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">See all messages</a>
                            </li>
                        </ul>
                    </li>
                    <!-- inbox dropdown end -->
					<li id="header_inbox_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">
                            Imagenes
                        </a> </li>
                </ul>
                <!--  notification end -->
            </div>
		
            <div class="top-menu">
            	<ul class="nav pull-right top-menu">
                    <li><a class="logout" href="login.html">Logout</a></li>
            	</ul>
            </div>
				<div class="top-menu">
            	<ul class="nav pull-right top-menu">
                    <li><a class="logout" href="login.html">Configuración de cuenta</a></li>
            	</ul>
            </div>
        </header>
      <!--header end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
<aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">
              
              	  <p class="centered"><a href="#"><img src="assets/img/logo.png" class="img-circle" width="200"></a></p>
              	  <h5 class="centered">Hosping</h5>
              	  	
                  <li class="mt">
                      <a class="active" href="index.php">
                          <i class="fa fa-dashboard"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>

                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-desktop"></i>
                          <span>Hotel</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="registrarhotel.php">Agregar Hotal</a></li>
                          <li><a  href="modificacion.php">Modificar Hotel</a></li>
                          <li><a  href="#"></a></li>
                      </ul>
                  </li>

                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-cogs"></i>
                          <span>Usuario</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="registrarusuario.php">Agregar Usuario</a></li>
                          <li><a  href="gallery.html">Buscar Usuario</a></li>
                          <li><a  href="todo_list.html">Eliminar Usuario</a></li>
                      </ul>
                  </li>
				    <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-cogs"></i>
                          <span>Finanzas</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="#"></a></li>
                          <li><a  href="#"></a></li>
                          <li><a  href="#"></a></li>
                      </ul>
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-book"></i>
                          <span>Sincronización</span>
                      </a>
                      <ul class="sub">
					  <?php
require_once('src/facebook.php');
$facebook = new facebook(array(
"appId" => "171138483291811",
"secret"=> "c6fb29543c365fd4c8e97b2255933716"
));
$url = $facebook->getLoginUrl(array('redirect_uri'=>"http://panel.hosping.com/facebook_callback.php")); 

?>

                          <li><a  href="<?php echo $url;?>">Facebook</a></li>
                          <li><a  href="#">Twitter</a></li>
                          <li><a  href="lock_screen.html">Lock Screen</a></li>
                      </ul>
                  </li>
                  
                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-th"></i>
                          <span>Post</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="muro.php">Muros</a></li>
                          <li><a  href="responsive_table.html">Postear</a></li>
						   <li><a  href="responsive_table.html">Templates</a></li>
                      </ul>
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class=" fa fa-bar-chart-o"></i>
                          <span>Estadisticas</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="morris.html">Morris</a></li>
                          <li><a  href="chartjs.html">Chartjs</a></li>
                      </ul>
                  </li>

              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper site-min-height">
          	<h3><i class="fa fa-angle-right"></i> Hoteles Registrados</h3>
          	<div class="row mt">
          		<div class="col-lg-12" style="width:80%">
          		<p></p>
<table border="1" width="100%">
  <tr>
    <td><div align="center"><strong>Id</strong></div></td>
    <td><div align="center"><strong>Nombre</strong></div></td>
    <td><div align="center"><strong>Razón Social</strong></div></td>
    <td><div align="center"><strong>Rut</strong></div></td>
    <td><div align="center"><strong>Nombre de Contacto</strong></div></td>
    <td><div align="center"><strong>Correo</strong></div></td>
	<td><div align="center"><strong>Dirección</strong></div></td>
	<td><div align="center"><strong>Telefono</strong></div></td>
	
    <td colspan="2"><div align="center"><strong>Operaciones</strong></div></td>
  </tr>
  <?php do { ?>
    <tr>
	  <td><?php echo $row_consulta_libros['id']; ?></td>
      <td><?php echo $row_consulta_libros['nombre']; ?></td>
      <td><?php echo $row_consulta_libros['razonsocial']; ?></td>
      <td><?php echo $row_consulta_libros['rut']; ?></td>
      <td><div align="center"><?php echo $row_consulta_libros['nombrecontacto']; ?></div></td>
      <td><div align="center"><?php echo $row_consulta_libros['correo']; ?></div></td>
	  <td><div align="center"><?php echo $row_consulta_libros['direccion']; ?></div></td>
	  <td><div align="center"><?php echo $row_consulta_libros['telefono']; ?></div></td>
      <td><a href="modificar.php?id=<?php echo $row_consulta_libros['id']; ?>">Modificar</a></td>
      <td><a href="borrar.php?id=<?php echo $row_consulta_libros['id']; ?>">Eliminar</a></td>
    </tr>
    <?php } while ($row_consulta_libros = mysql_fetch_assoc($consulta_libros)); ?>
</table>
<p>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" align="center"><?php if ($pageNum_consulta_libros > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_consulta_libros=%d%s", $currentPage, 0, $queryString_consulta_libros); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center"><?php if ($pageNum_consulta_libros > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_consulta_libros=%d%s", $currentPage, max(0, $pageNum_consulta_libros - 1), $queryString_consulta_libros); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_consulta_libros < $totalPages_consulta_libros) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_consulta_libros=%d%s", $currentPage, min($totalPages_consulta_libros, $pageNum_consulta_libros + 1), $queryString_consulta_libros); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_consulta_libros < $totalPages_consulta_libros) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_consulta_libros=%d%s", $currentPage, $totalPages_consulta_libros, $queryString_consulta_libros); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
</p>
				
				
				</div>
          	</div>
			
		</section><! --/wrapper -->
      </section><!-- /MAIN CONTENT -->

      <!--main content end-->
      <!--footer start-->
      <footer class="site-footer">
          <div class="text-center">
              2014 - Alvarez.is
              <a href="blank.html#" class="go-top">
                  <i class="fa fa-angle-up"></i>
              </a>
          </div>
      </footer>
      <!--footer end-->
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="assets/js/jquery.ui.touch-punch.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>


    <!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>

    <!--script for this page-->
    
  <script>
      //custom select box

      $(function(){
          $('select.styled').customSelect();
      });

  </script>

  </body>
</html>
