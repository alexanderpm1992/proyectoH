<?
session_start();
/* Iniciamos sesión dentro de esta página también mediante la función session_start(); */
if($_SESSION["falla"]==0)
{
$_SESSION["autorizacion"]="si";
header("Location: ../dashbord.html");
}
/* Lo primero que hacemos es preguntar si la variable “falla” del vector de sesión tiene valor igual a 0. 
Si esto es así significa que paso todo el proceso de validación sin errores, ya que en la página anterior ante cualquier 
problema le asignamos otro valor. En caso de que sea así, hacemos una redirección por PHP mediante “header” y dentro de 
Location ponemos la página de destino donde llegaran los usuarios autorizados. Además definimos una variable llamada “autorización” 
y le asignamos el valor “si”. Este variable nos permitirá hacer un manejo de sesiones en cualquiera de las páginas que sigan 
preguntando simplemente si esta activado el valor si, y de esa forma mostrar el contenido autorizado */
else
{
unset($_SESSION["falla"]);
session_destroy();
header("Location:../index.php");
}
?>
