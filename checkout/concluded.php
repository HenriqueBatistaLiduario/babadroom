<?php
  
	if(isset($_SESSION['bbdcartkey'])){
		
		unset( $_SESSION['bbdcartkey'] );
		
	}
	
	include 'header.php'; 
	
	if(!isset($_GET['transaction_id'])){
		
		echo "<script>location.href='https://babadroom.com';</script>";
		
		exit;
		
	}
	
	$transactionCode = $_GET['transaction_id'];
		
	$email = "pagseguro@babadroom.com";
	$token = "6d9d14dc-a0d5-4b4b-9c12-1015ff329cce8e9fd4db4b3ea3aa67b0c5906acfee27b69f-977e-44a0-a669-61a1c3c34845";
	
	$url = "https://ws.pagseguro.uol.com.br/v3/transactions/$transactionCode?email=$email&token=$token";

	$curl = curl_init($url);
	
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
	$response = curl_exec($curl);
	$http = curl_getinfo($curl);
	
	curl_close($curl);
	
	$response = simplexml_load_string($response);
	
	$IDTRANSACAO    = $response->items->item->id;
	$STATUSPAGSEG   = $response->status;
	$DTSTATUSPAGSEG = $response->lastEventDate;
	
	$DTST1 = str_ireplace("T"," ",$DTSTATUSPAGSEG);
	$DTST2 = explode(".",$DTST1); 

	$DTSTATUS = $DTST2[0];
	
	//Atualizar Sales Order
	
	$Tupdate = mysqli_query($con,"UPDATE transacoes SET STATUSPAGSEG = $STATUSPAGSEG, DTSTATUSPAGSEG = '$DTSTATUS' WHERE IDTRANSACAO = '$IDTRANSACAO'");
	
	if($Tupdate){
		
		$SOCODEselect = mysqli_query($con,"SELECT SOCODE FROM transacoes WHERE IDTRANSACAO = '$IDTRANSACAO'");
		
		while($SOcol = mysqli_fetch_array($SOCODEselect)){
			$SOCODE = $SOcol["SOCODE"];
		}
	
		$SOupdate = mysqli_query($con,"UPDATE sales_orders SET SOSTATUS = 1, SODTSTATUS = '$datetime' WHERE SOCODE = '$SOCODE'");
		
		if($SOupdate){ //"Matar" o carrinho referente a transação...
		  
			$CARTupdate = mysqli_query($con,"UPDATE carts SET CARTSTATUS=1, CARTDTSTATUS='$datetime' WHERE CARTSESSION IN(SELECT CARTKEY FROM sales_orders WHERE SOCODE = '$SOCODE')");			
			
		}
	
	}
	
?>

<div class='container pb-5 mb-sm-4'>
	<div class='pt-5'>
		<div class='card py-3 mt-sm-3'>
			<div class='card-body text-center'>
				<h2 class='h4 pb-3'>Obrigado pelo seu pedido!</h2>
				<p class='font-size-sm mb-2'>Seu pedido foi feito e será processado o mais rápido possível.</p>
				<p class='font-size-sm mb-2'>Certifique-se de anotar o número do seu pedido, que é <span class='font-weight-medium'><?php echo $SOCODE;?></span></p>
				<p class='font-size-sm'>Em breve, você receberá um e-mail com a confirmação do seu pedido. <u>Agora você pode:</u></p>
				<a class='btn btn-secondary mt-3 mr-3' href='https://babadroom.com'>Voltar às compras</a>
				<a class='btn btn-primary mt-3' href='https://babadroom.com/order-tracking.php'><i class='czi-location'></i>&nbsp;Acompanhar seus Pedidos</a>
			</div>
		</div>
	</div>
</div>
  
<?php include 'footer.php'; ?>