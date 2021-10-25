<?php
 
	$cep = $_POST['address'];
	 
	$reg = simplexml_load_file("http://cep.republicavirtual.com.br/web_cep.php?cep=$cep&formato=xml");
	 
	$dados['sucesso'] = (string) $reg->resultado;
	$dados['rua']     = (string) $reg->tipo_logradouro . ' ' . $reg->logradouro;
	$dados['bairro']  = (string) $reg->bairro;
	$dados['cidade']  = (string) $reg->cidade;
	$dados['estado']  = (string) $reg->uf;
	 
	echo json_encode($dados);
 
?>