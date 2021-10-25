<?php

  $PACPRAZO = 20;
	
	echo "<h1>PRAZO Original: $PACPRAZO<br>";
	
	$DIASAMAIS = $PACPRAZO%7 +2; 		

  $PRAZO = $PACPRAZO + $DIASAMAIS;	 
	
	echo "Dias a Mais: $DIASAMAIS<br>Prazo Final: $PRAZO<br>";
				
	$DTPREVISTA = date("Y-m-d", strtotime("+$PRAZO days"));

	echo $DTPREVISTA;

?>