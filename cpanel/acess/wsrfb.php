<?php 
	
	$json_file = file_get_contents("https://www.receitaws.com.br/v1/cnpj/$CNPJ");
	
	if($json_file){
	
		$json_str = json_decode($json_file, true);
		
		$Cnpj                  = $json_str['cnpj'];
		$AtividadePrincipal    = $json_str['atividade_principal'];
		$AtividadesSecundarias = $json_str['atividades_secundarias'];
		$RazaoSocial           = $json_str['nome'];
		$NomeFantasia          = $json_str['fantasia'];
		$NaturezaJuridica      = $json_str['natureza_juridica'];
		$DataAbertura          = $json_str['abertura'];
		$Situacao              = $json_str['situacao'];
		$DataSituacao          = $json_str['data_situacao'];
		$UltimaAtualizacao     = $json_str['ultima_atualizacao'];
		$Status                = $json_str['status'];
		$Logradouro            = $json_str['logradouro'];
		$Numero                = $json_str['numero'];
		$Complemento           = $json_str['complemento'];
		$Bairro                = $json_str['bairro'];
		$Cep                   = $json_str['cep'];
		$Municipio             = $json_str['municipio'];
		$Uf                    = $json_str['uf'];
		$Telefone              = $json_str['telefone'];
		$Email                 = $json_str['email'];
		$Tipo                  = $json_str['tipo']; // Se Matriz ou Filial...
		$QuadroSocios          = $json_str['qsa']; // Quadro de Sócios
		$Porte                 = $json_str['porte'];
		
		$NJ = explode(' ',$NaturezaJuridica);
		$NJCODIGO = $NJ[0];		
				
		foreach ( $AtividadePrincipal as $linha ) { 
			$AtividadePrincipalNome   = $linha['text'];
			$AtividadePrincipalCodigo = $linha['code'];	
			
			if($AtividadePrincipalCodigo != ''){
		
				$CNAE = explode('-',$AtividadePrincipalCodigo);
				$CLASSE0 = $CNAE[0];
				$CLASSE1 = $CNAE[1];
				
				$CNAECLASSECODIGO = "$CLASSE0-$CLASSE1";
		
			}else{
				
				$CNAECLASSECODIGO = 'NA';
				
			}		
			
		} 
		
		$dtabertura = date("Y-m-d",strtotime($DataAbertura));
		
		$TrataTel = str_replace("(","",$Telefone);
		$TrataTel = str_replace(")","",$TrataTel);		
		$Telefone = $TrataTel;
		
		$TrataCep = str_replace(".","",$Cep);
		$TrataCep = str_replace("-","",$TrataCep);		
		$Cep = $TrataCep;		
	
	}
	
?>