<?php

	/*#### Validações contratuais no Painel de Controle... #########################################################################################################################
			
		@mysql_close($conexao);
		
		include "../Conexoes/conecta_cpanel.php";
		
		$CONTRATOselect = mysql_query("SELECT * FROM adm_contratos WHERE ACCODCONTRATO = '$cnt_session'") or print (mysql_error());
		$ACLIMITEUSUARIOS  = mysql_result($CONTRATOselect,0,"ACLIMITEUSUARIOS");
		$ACLIMITECONEXOES  = mysql_result($CONTRATOselect,0,"ACLIMITECONEXOES");
		$ACMAILAUTO        = mysql_result($CONTRATOselect,0,"ACMAILAUTO");
		$ACSMSAUTO         = mysql_result($CONTRATOselect,0,"ACSMSAUTO");
		$ACINICIOVIGENCIA  = mysql_result($CONTRATOselect,0,"ACINICIOVIGENCIA");
		$ACTERMINOVIGENCIA = mysql_result($CONTRATOselect,0,"ACTERMINOVIGENCIA");
		
		//1. Verifica se o cliente possui contrato do GED ativo em seu domínio...	
		$ACGEDselect = mysql_query("SELECT * FROM adm_contratos WHERE ACDOMINIO = '$dominio_session' AND ACCODPRODUTO = 'GED' AND ACSITUACAO = 5") or print (mysql_error());
		$INTEGRAGED  = mysql_num_rows($ACGEDselect);
		
		//2. Verifica se o cliente possui contrato do SGP ativo em seu domínio...
		$ACSGPselect = mysql_query("SELECT * FROM adm_contratos WHERE ACDOMINIO = '$dominio_session' AND ACCODPRODUTO = 'SGP' AND ACSITUACAO = 5") or print (mysql_error());
		$INTEGRASGP  = mysql_num_rows($ACSGPselect);
		
		@mysql_close($conexao);
			
	#############################################################################################################################################################################*/
	
	$ADDOMINIO  = 'wishbaby';
	$ADSITUACAO = 5;
	$APLSESSION = 'CPANEL';
	$CNTSESSION = 'CNT001';
	$ADMSESSION = '35765822000148';
	
	$ACLIMITEUSUARIOS = 50;
	$ACLIMITECONEXOES = 50;
	$ACMAILAUTO = 1;      
	$ACSMSAUTO = 0;      
	$ACINICIOVIGENCIA  = '2020-01-19 00:01:01';
	$ACTERMINOVIGENCIA = '2020-12-31 23:59:59';
	
	$INTEGRAGED = 0;
	$INTEGRASGP = 0;

?>