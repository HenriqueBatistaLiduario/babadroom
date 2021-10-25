<?php
		
	$SAIDSESSION = $_GET['id_session'];
	
	include "connect.php";	
	include "timezone.php";
	
	$SESSAOselect = mysqli_query($con,"SELECT * FROM sessoes WHERE SAIDSESSION = '$SAIDSESSION'");
	
	while($SAcol = mysqli_fetch_array($SESSAOselect)){
	
	  $SALOGIN = $SAcol["SALOGIN"];
	
	}
		
	$H = date('H',strtotime($SALOGIN));
	$i = date('i',strtotime($SALOGIN));
	$s = date('s',strtotime($SALOGIN));
	$m = date('m',strtotime($SALOGIN));
	$d = date('d',strtotime($SALOGIN));
	$Y = date('Y',strtotime($SALOGIN));

	$H2 = date('H');
	$i2 = date('i');
	$s2 = date('s');
	$m2 = date('m');
	$d2 = date('d');
	$Y2 = date('Y');

	$LOGIN  = mktime($H,$i,$s,$m,$d,$Y);
	$LOGOUT = mktime($H2,$i2,$s2,$m2,$d2,$Y2);
	
	$DuracaoSegundos = $LOGOUT - $LOGIN; //Em segundos...
	$DuracaoMinutos = $DuracaoSegundos/60; //Em minutos...
	
	//Exibir duração no formato de horas...
	$DURACAOminutos = $DuracaoMinutos%60;
	$DURACAOhoras =($DuracaoMinutos-$DURACAOminutos)/60;

	if($DURACAOminutos < 10){
		
		$DURACAOminutos = "0$DURACAOminutos";
		
	}
	
	$DURACAO = "$DURACAOhoras:$DURACAOminutos";
	
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
		$SAIP = $_SERVER['HTTP_CLIENT_IP'];

	}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$SAIP = $_SERVER['HTTP_X_FORWARDED_FOR'];

	}else{
		$SAIP = $_SERVER['REMOTE_ADDR'];
	}
	
	$SESSAOupdate = mysqli_query($con,"UPDATE sessoes SET SALOGOUT = '$datetime', 
																									      SASTATUS = 3,
																								       SADURACAO = '$DURACAO',
																								      SALOGOUTIP = '$SAIP' WHERE SAIDSESSION = '$SAIDSESSION'") or print (mysqli_error($con));
	if($SESSAOupdate){
	
		@session_start();

		$_SESSION = array();
		$destruiu = session_destroy();
		
		if($destruiu){
			
			echo "<script> alert('Sessão encerrada com sucesso.');location.href='index.php';</script>";
			
			exit;	
			
		}
		
	}

?>