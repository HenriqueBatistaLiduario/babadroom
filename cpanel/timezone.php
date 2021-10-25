<?php

	########################## Data e Hora do sistema ############################################################################################

	date_default_timezone_set('America/Sao_Paulo');

	$fuso = mktime(date("H")-3, date("i"), date("s"), date("m"), date("d"), date("Y")); //Para Horário de Verão, mudar para -2
	$ONTEMfuso          = mktime(date("H")-3, date("i"), date("s"), date("m"),   date("d")-1, date("Y"));
	$AMANHAfuso         = mktime(date("H")-3, date("i"), date("s"), date("m"),   date("d")+1, date("Y"));
	$SEMANAPREVIOUSfuso = mktime(date("H")-3, date("i"), date("s"), date("m"),   date("d")-7, date("Y"));
	$SEMANANEXTfuso     = mktime(date("H")-3, date("i"), date("s"), date("m"),   date("d")+7, date("Y"));
	$MESPREVIOUSfuso    = mktime(date("H")-3, date("i"), date("s"), date("m")-1, date("d"),   date("Y"));
	$MESNEXTfuso        = mktime(date("H")-3, date("i"), date("s"), date("m")+1, date("d"),   date("Y"));
	
	$datetime = gmdate("Y-m-d H:i:s", $fuso);
	$agora  	= gmdate("d/m/Y H:i", $fuso);
	$hoje   	= gmdate("Y-m-d", $fuso);
	$ontem  	= gmdate("Y-m-d", $ONTEMfuso);
	$amanha 	= gmdate("Y-m-d", $AMANHAfuso);
	$hora   	= gmdate("H:i:s", $fuso);
	$hora2   	= gmdate("H:i", $fuso);
	$mes    	= date('m');
	$ano    	= date('Y');
	$ano2     = date('y');
	
	$SemanaPrevious = gmdate("Y-m-d", $SEMANAPREVIOUSfuso);
	$SemanaNext     = gmdate("Y-m-d", $SEMANANEXTfuso);
	$MesPrevious    = gmdate("Y-m-d", $MESPREVIOUSfuso);
	$MesNext        = gmdate("Y-m-d", $MESNEXTfuso);	

	##############################################################################################################################################
	
	$TOKENDTVALIDADEPADRAO = mktime(date("H")-3, date("i"), date("s"), date("m"), date("d")+180, date("Y"));
	$TOKENDTVALIDADEPADRAO = date("Y-m-d H:i:s",$TOKENDTVALIDADEPADRAO);
	
	$FINDTVENCIMENTOPADRAO = mktime(date("H")-3, date("i"), date("s"), date("m"), date("d")+30, date("Y"));
	$FINDTVENCIMENTOPADRAO = date("Y-m-d",$FINDTVENCIMENTOPADRAO);
	
	$DiaSemanaVencimento = date("w",strtotime($FINDTVENCIMENTOPADRAO));
	
	if($DiaSemanaVencimento == 6){
		$FINDTVENCIMENTOPADRAO = $FINDTVENCIMENTOPADRAO + 172800; //Joga pra segunda...
	}
	
	if($DiaSemanaVencimento == 0){
		$FINDTVENCIMENTOPADRAO = $FINDTVENCIMENTOPADRAO + 86400; //Joga pra segunda...
	}
	
	#### Saudação... ###############################################################################################################################
	
	if($hora > '11:59:59' AND $hora < '18:00:00'){
		$saudacaoPtBr = "Boa tarde";
	
	}else if($hora > '17:59:59' AND $hora <= '23:59:59'){
		$saudacaoPtBr = "Boa noite";
	
	}else{
		$saudacaoPtBr = "Bom dia";
	}
	
?>