<?php
include($_SERVER['DOCUMENT_ROOT'].'/ldap/classes/ldap.php');

//Nuevo Objeto Ldap
$ldap = new ldap();

// Construimos el objeto
$ldap->__construct();

//Cargamos la direccion de correo que viene en el formulario en el metodo Load del objeto y el resultado lo metemos en $result
if (isset($_POST["user"])) 
{
	$result = $ldap->load($_POST["user"]);
}	
?>

<form method="post" action="index.php">

Type your desired email here: <input type="text" name="user" value="<?php if (isset($_POST["user"])) { echo $_POST["user"];} ?>"> <input type="submit" value="Search the HP LDAP Server">

</form>

<?php 
if (isset($result['count']) && $result['count'] > 0)
{
?>

Showing information for <strong><?php if (isset($result[0]['givenname'])) { echo $result[0]['givenname'][0]; };?> <?php if (isset($result[0]['sn']))  { echo $result[0]['sn'][0]; }?> <?php if (isset($result[0]['secondfamilyname'])) { echo $result[0]['secondfamilyname'][0]; } ?></strong>
<p>
OK... LDAP server says that:
<p>
This Person's Given Name is: <strong><?php if (isset($result[0]['givenname'])) { echo $result[0]['givenname'][0];}?></strong>
<br>
This Person's Last Name is: <strong><?php if (isset($result[0]['sn'])) { echo $result[0]['sn'][0];}?></strong>
<br>
This Person's Second Last Name is: <strong><?php if (isset($result[0]['secondfamilyname'])) { echo $result[0]['secondfamilyname'][0];}?></strong>
<br>
This Person Started at HP: <strong><?php if (isset($result[0]['hpstartdate'])) { echo date("D d M, Y",strtotime($result[0]['hpstartdate'][0]));}?></strong>
<br>
This Person Works at: <strong><?php if (isset($result[0]['buildingname'])) { echo $result[0]['buildingname'][0];}?></strong>, Located in <strong><?php if (isset($result[0]['co'])) { echo $result[0]['co'][0];}?></strong>
<br>
This Person is an <strong><?php if (isset($result[0]['hpstatus'])) { echo $result[0]['hpstatus'][0];}?></strong> Employee.
<br>
<?php 
// Un par de lineas para demostrar como hacer una busqueda dentro de una busqueda :)

// Esto funciona asi:  Al traerse toda la informacion de alguien, la entrada de manager viene como un DN, es decir, algo asi:

// uid=michael.dunninger@hp.com,ou=People,o=hp.com

// Esto no nos sirve, porque el objeto LDAP solo puede cargar UIDs con formato de email .. como franklin@hp.com y no uid=michael.dunninger@hp.com,ou=People,o=hp.com
// esto quiere decir que tenemos que cortar ese string tan largo para poder sacar el email.  Vamos a usar explode() de PHP para eso.

// primero vamos a cortar el DN para sacar el correo separando el string en cada coma y metiendolo en un array
$resultManager = explode(",",$result[0]['manager'][0]);

// Lo anterior lo que hace es cortar el string uid=michael.dunninger@hp.com , ou=People , o=hp.com por las comas y meterlo en un array llamado $resultManager[index]
// Eso quiere decir que el array en el indice 0 tiene ahora uid=michael.dunninger@hp.com. El resto del array no nos interesa.

// Un detalle muy importante:  Yo cuando hago estas cosas uso la misma variable.  Es una cuestion personal para tener codigo mas ordenado y usar menos memoria en el servidor
// Por supuesto, cada quien programa como mas le guste, pero lo que quiero decir es que el hecho que todas las variables se llaman igual es a proposito.

// ahora tenemos que quitarle el uid= para que nos quede solo el email:
$resultManager = explode("=",$resultManager[0]);

// Finalmente solo nos queda michael.dunninger@hp.com en un array con el indice 1.  El indice 0 tiene uid= que no nos interesa. 
// Esta es una direccion de correo normal que podemos cargar en nuestro objeto LDAP.
$resultManager = $ldap->load($resultManager[1]);

// Y ahora $resultManager tiene un array con toda la info del Manager de la persona que buscamos arriba.  Vamos a ponerlo en Bonito.  Vamos a poner el nombre
// dentro de un link al email.
?>

This Person's Manager is: <strong><a href="mailto:<?php echo $resultManager[0]['mail'][0];?>"><?php echo $resultManager[0]['cn'][0];?></a></strong>
<br>
	
<?php 
}
else
{
	if (isset($_POST["user"]))
	{
?>
OK... LDAP server says that it cannot find this person.  
<p>
Maybe you typed an incorrect email address, or this person not longer works at HP.	Or maybe you typed an email alias insted of the real email address?
<?php 
	}
}

// Finalmente asesinamos sin misericordia y a sangre fria el objeto Ldap
$ldap->__destroy();
?>