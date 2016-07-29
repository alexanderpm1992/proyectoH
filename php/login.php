<?
session_start();
$_SESSION["user"]=$_POST['usuario'];
$_SESSION["pass"]=$_POST['pass'];
$_SESSION["falla"]=0;
?>


<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title></title>
	
</head>
<body>
	<?php
$user=$_POST["usuario"];
$pass=$_POST["pass"];
/* Asignamos a las variables $user y $pass los valores “usuario” y “clave” recogidos de nuestro formulario de ingreso de la página HTML. */
if(empty($user))
{
echo "No ha ingresado un nombre de usuario. <br> Sera redirigido de vuelta.";
$_SESSION["falla"]=1;
}
/* Utilizaremos la función empty de PHP mediante la cual preguntaremos si nuestra variable $user (la que contiene el valor de usuario del formulario) se encuentra vacia, lo que significaría que el usuario no ingreso nada en el campo. Si este fuera el caso, desplegaríamos un mensaje en la página con “echo” y luego cambiariamos el valor de nuestra variable “falla” (la bandera definida en el vector de sesión) a 1. En caso de que el usuario no este vacío, pasamos al else y revisamos lo demás */

else
{
if(empty($pass))
{
echo "No ha ingresado una clave. <br> Sera redirigido de vuelta.";
$_SESSION["falla"]=1;
}
/* Haremos la misma comprobación anterior pero en este caso con la variable $pass (que almacena el valor de clave del formulario). En caso de que no este vacía, pasamos al else */
else
{
$con=mysql_connect("localhost","hospingc_admin","Alonsomp1");
/* Declaramos una variable llamada $con a la que le almacenaremos el resultado de la función mysql_connect, la cual se encarga de establecer una conexión entre nuestra página PHP y nuestra base de datos MySQL para poder realizar las consultas que necesitamos para validar los datos. Esta función utiliza 3 parametros y devuelve un resultado de tipo entero (int):
Host: La dirección del servidor donde tenemos alojada nuestra base de datos. Comúnmente este valor debiera ser localhost (en caso de que esta página la ejecutemos en el mismo servidor donde esta alojada la BD), si no, es un dato que su proveedor de hosting debería darles.
User: El usuario con el que ingresan a la BD. Tambien otorgado por su proveedor de hosting.
Password: Contraseña para ingresar a la BD. Lo mismo que el caso anterior. */
mysql_select_db("hospingc_software",$con);
/* Llamamos a la función mysql_select_db la cual nos ayudará a seleccionar nuestra base de datos (la cual esta dentro de la base de datos) y que requiere de 2 parametros:
Database: Nombre de nuestra base de datos. (La que creamos al principio).
Identificador de conexión: El cual le otorga los datos de conexión a la función, en nuestro caso la variable $con. */

$sql="SELECT usuario, clave, nombreusuario FROM login WHERE usuario='$user'";
/* Definimos una variable $sql , la cual guardará la consulta que haremos en la base de datos. En este caso, pediremos seleccionar el usuario, la clave y el nombre correspondientes al registro del usuario que se ingresó mediante el formulario */

$resultado=mysql_query($sql,$con);
/* Definimos una variable llamada $resultado en la cual almacenaremos, valga la redundancia, el resultado de la ejecución de nuestra consulta mediante la función mysql_query, la cual requiere de 2 parametros: la consulta recien definida, y el identificador de conexión que definimos anteriormente. */

if(!$resultado)
{
$error=mysql_error();
print $error;
$_SESSION["falla"]=1;
exit();
}
/* Luego preguntamos mediante un if si no hubo resultado a la ejecución de la consulta y almacenamos en la variable $error la falla otorgada por la base de datos para presentarla en la página mediante la sentencia print (que es similar a “echo”) y cambiamos el valor de la variable “falla” de nuestro vector de sesión. Finalmente hacemos uso de la función exit(); para que nuestro código termine de ejecutarse aquí y no sigan corriendo las líneas siguientes. Este paso puede obviarse ya que a los usuarios no es necesario enseñarles el error que nos da la base de datos, yo decidí incluirlo para que uds. puedan probar e informarse de las distintas razones por las que puede haber una falla en este proceso. */

if(mysql_affected_rows()==0)
{
echo "El usuario no fue encontrado. <br> Sera redirigido de vuelta.";
$_SESSION["falla"]=1;
exit();
}
/* Luego mediante otro if , hacemos un llamado a la función mysql_affected_rows() la cual se encarga de notificar si es que la consulta no afecto a ninguna fila de nuestra tabla (o sea, no hubo coincidencias), esta función retorna un entero, que es 0 en caso de no haber filas afectadas. En caso de que así sea desplegamos un mensaje informando que el usuario no fue encontrado mediante la sentencia “echo”, cambiamos el valor de la variable falla del vector de sesión y finalmente salimos del código mediante la función exit();. Si el resultado de la función no es cero, significa que hubo coincidencias y pasamos al else */

else
{
$row=mysql_fetch_array($resultado);
/* Definimos una variable $row y a esta le asignamos el resultado de la función mysql_fetch_array, la que utiliza como parametro $resultado (el resultado de la consulta ejecutada). Este paso es necesario, ya que cuando hacemos una consulta sobre una tabla de una base de datos, en caso de haber coincidencia, estos datos no están disponibles para que nosotros los podamos manipular, si no que se seleccionan de forma “virtual”. Normalmente las bases de datos definen cursores, los cuales al hacer un fetch, extraen los datos y nos permiten manipularlos de una forma más “fisica” por decirlo de alguna forma. En este caso, la variable $row se transformará en un vector, con posiciones de nombre igual a cada uno de los campos de la fila, los cuales podremos comparar. */

$nombre=$row["nombre"];
/* Definimos una variable $nombre y a este le asignamos el valor de la posición “nombre” del vector $row, o sea, el campo nombre extraído de la coincidencia de la tabla usuario */
if($user==$row["usuario"])
{
if($pass==$row["clave"])
{
echo "<b>Bienvenido $nombre</b>. <br> Espere mientras es redirigido";
$_SESSION["nombre"]=$nombre;
header('Location:../dashbord.html');
}
else
{
echo "Hay un error en la clave. <br> Espere mientras es redirigido";
$_SESSION["falla"]=1; ?>
<script>alert('Clave incorrecta')</script>";
<?php
header('Location:../index.php');
}
}
else
{
echo "Hay un error en el nombre de usuario. <br> Espere mientras es redirigido";
$_SESSION["falla"]=1;
echo"<script>alert('Usuario Invalido')</script>";

header('Location:../index.php');
}
/* Y Finalmente mediante una serie de if y else, comparamos los valores recibidos por el formulario (almacenados en las variables $user y $pass) con los extraídos de la fila de la tabla de la base de datos (almacenados en el vector $row). En caso de coincidir el nombre de usuario y la contraseña, desplegamos un mensaje dandole la bienvenida al usuario con una sentencia echo (al usar el mensaje con la variable $nombre, dejamos definido un mensaje que cambiará dependiendo del nombre de cada usuario que entre) y le informaremos que será redirigido, para finalmente registrar en el vector de sesión el nombre del usuario, en caso de que necesitemos usarlo más adelante. De lo contrario mostraremos los correspondientes mensajes de error y marcaremos la variable falla para más adelante. */
}
}
}
?>

</body>
</html>