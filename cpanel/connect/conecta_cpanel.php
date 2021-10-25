<?php

  $server  = 'localhost';
	$usuario = 'u649003091_babadroom';
	$senha   = 'Ftz0y#CZ9d5[';
	$banco   = 'u649003091_babadroom';
	
	$con = mysqli_connect($server,$usuario,$senha,$banco);
	
	if(!$con){
		
		echo "<div align='center' class='warning'>CONEXÃO COM O BANCO DE DADOS NÃO ESTABELECIDA.</div>";
		
		exit;
		
	}
	
?>