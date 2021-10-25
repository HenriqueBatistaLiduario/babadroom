<?php

	########################## Data e Hora do sistema ############################################################################################

	date_default_timezone_set('America/Sao_Paulo');

	$datetime = date("Y-m-d H:i:s");
	
	$agora = date("d/m/Y H:i");
	
	$hoje = date("Y-m-d");
	
	$hora = date("H:i:s");
	
	$hora2 = date("H:i");
	
	$mes  = date('m');
	
	$ano  = date('Y');
	
	$ano2 = date('y');
	
	$dia  = date('w');
	
	$salutation = "Bom Dia!";
	
	if($hora > '11:59:59' AND $hora < '18:00:00'){ $salutation = "Boa Tarde!"; }
	
	if($hora > '17:59:59' AND $hora <= '23:59:59'){	$salutation = "Boa Noite!";	}
	
	switch($mes){
		
		case '01': $NomeMes = "Janeiro"; break;
		case '02': $NomeMes = "Fevereiro"; break;
		case '03': $NomeMes = "Março"; break;
		case '04': $NomeMes = "Abril"; break;
		case '05': $NomeMes = "Maio"; break;
		case '06': $NomeMes = "Junho"; break;
		case '07': $NomeMes = "Julho"; break;
		case '08': $NomeMes = "Agosto"; break;
		case '09': $NomeMes = "Setembro"; break;
		case '10': $NomeMes = "Outubro"; break;
		case '11': $NomeMes = "Novembro"; break;
		case '12': $NomeMes = "Dezembro"; break;	
		
	}
	
?>