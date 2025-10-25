<?php 
include_once('functions.php');
//login();

if (login()){	
	}else{
            echo "Error en el login";
		if(getErrors()){
			echo getErrors();
		}
		}
?>

