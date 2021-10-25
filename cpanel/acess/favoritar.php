<!DOCTYPE HTML>
<html>

<?php

	include 'conecta_pets.php';
	include 'timezone.php';
	
	@session_start();
	
	$ident_session = $_SESSION['ident_session'];

	$IDANUNCIO = $_GET['idanuncio'];
	$acao = $_GET['action'];
	
	if($acao == 'addfav'){
		$FAVinsert = mysql_query("INSERT INTO favoritos (IDANUNCIO,FAVIDUSUARIO,FAVCADASTRO,FAVCADBY) VALUES ('$IDANUNCIO','$ident_session','$datetime','$ident_session')") or print (mysql_error());
	
		if($FAVinsert){
			echo '1';
		}
	}
	
	if($acao == 'removerfav'){
		$FAVdelete = mysql_query("DELETE FROM favoritos WHERE FAVIDUSUARIO = '$ident_session' AND IDANUNCIO = '$IDANUNCIO'") or print (mysql_error());
		
		if($FAVdelete){
			echo '1';
		}
	}

?>
</html>