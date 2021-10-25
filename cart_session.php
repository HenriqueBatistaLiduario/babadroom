<?php 

    /* NOME DA SESSÃO: bbdcartkey
		   CONTEÚDO DA SESSÃO: uniqid() */

    include 'connect.php'; 		
		include 'timezone.php';
		
		$PRDCOD = $_GET['p']; //Produto clicado
		$QTDE   = $_GET['q']; //Quantidade
		
		$ITMselect = mysqli_query($con,"SELECT PRDPRICEOFICIAL,PRDINSALE,PRDPRICESALE FROM products WHERE PRDCOD='$PRDCOD'");
									
		while($ITMcol = mysqli_fetch_array($ITMselect)){
			
			$PRDPRICEOFICIAL = $ITMcol["PRDPRICEOFICIAL"];
			$PRDINSALE       = $ITMcol["PRDINSALE"];
			$PRDPRICESALE    = $ITMcol["PRDPRICESALE"];
			
		}
		
		$VALORITEM = $PRDPRICEOFICIAL;								
									
		if($PRDINSALE == 1){
			
			$VALORITEM = $PRDPRICESALE;			
			
		}      
		
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
				
			$SAIP = $_SERVER["HTTP_CLIENT_IP"];
	
		}else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
			
			$SAIP = $_SERVER["HTTP_X_FORWARDED_FOR"];
		
		}else{
			
			$SAIP = $_SERVER["REMOTE_ADDR"];
			
		}
		
		$SABROWSER = $_SERVER['HTTP_USER_AGENT'];
	
		session_start();
		
		if(!isset($_SESSION['bbdcartkey'])){ //Verifica se já existe sessão aberta no dispositivo...
			
			$_SESSION['bbdcartkey'] = uniqid();			
			
		}
		
		$CARTSESSION = $_SESSION['bbdcartkey'];
		
		//Verifica se o Produto já existe no carrinho. Se sim, apenas altera a quantidade a cada inserção...
		
		$CARTverifica  = mysqli_query($con,"SELECT DISTINCT PRDCOD FROM carts WHERE PRDCOD = '$PRDCOD' AND CARTSESSION = '$CARTSESSION'");
		$PRDEXISTECART = mysqli_num_rows($CARTverifica);
		
		if($PRDEXISTECART == 0){ //Se não existe ítem no carrinho em sessão, então insere...
		
			$CARTcomand = mysqli_query($con,"INSERT INTO carts(CARTSESSION,PRDCOD,CARTVALORITEM,CARTQTDEITEM,CARTIP,CARTBROWSER,CARTCREATEDON) VALUES ('$CARTSESSION','$PRDCOD','$VALORITEM','$QTDE','$SAIP','$SABROWSER','$datetime')");		
		
		}else{ //Se existe ítem no carrinho em sessão, atualiza a quantidade...
		
			$CARTcomand = mysqli_query($con,"UPDATE carts SET CARTQTDEITEM = CARTQTDEITEM+1 WHERE CARTSESSION = '$CARTSESSION' AND PRDCOD = '$PRDCOD'");
			
		}
		
		if($CARTcomand){
			
			$MSGALERT = "$QTDE ÍTENS adicionados à sua sacola";
			
			if($QTDE == 1){ $MSGALERT = "1 ÍTEM adicionado à sua sacola!"; }
			
			echo "<script>alert('$MSGALERT');location.href='cart.php';</script>";
			
		}	
		
		
?>