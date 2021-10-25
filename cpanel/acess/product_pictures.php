<?php include 'head.php';

  $MSGalert = NULL;
				
	if(isset($_GET['prdcod'])){
		$PRDCOD = $_GET['prdcod'];
	}
	
	if(isset($_POST['prdcod'])){
		$PRDCOD = $_POST['prdcod'];
	}
	
	if(isset($_GET['success'])){
		$MSGalert = 
		"<div class='alert alert-success alert-dismissable'>
			<strong>SEU PRODUTO FOI CADASTRADO!</strong>&nbsp;Quase tudo pronto...<br>
			Inclua no mínimo duas fotos e em seguida você já poderá publicar seu produto.
		</div>";
	}
	
	if(isset($_GET['editsuccess'])){
		$MSGalert = 
		"<div class='alert alert-success alert-dismissable'>
			<a class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<strong>SUCESSO!</strong> As informações do seu produto foram atualizadas.<br>
			Te direcionamos pra cá caso queira atualizar as fotos também. Se estiver tudo certo, que tal publicar seu produto agora?
		</div>";
	}
	
	if(isset($_POST['acao'])){
		$acao = $_POST['acao'];
		
		if($acao == 'UPLOAD'){
			
			$nome_imagem = $_FILES['imagem']['name']; 
			
			$PXPNOMEselect = mysqli_query($con,"SELECT PXPNOMEORIGINAL FROM productxpictures WHERE PRDCOD = '$PRDCOD' AND PXPNOMEORIGINAL = '$nome_imagem'") or print (mysqli_error());
			$PXPNOMEexiste = mysqli_num_rows($PXPNOMEselect);
			
			if($PXPNOMEexiste > 0){
				
				$MSGalert = 
				"<div class='alert alert-warning alert-dismissable'>
					<a class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<strong>Ops!</strong>&nbsp;&nbsp;Parece que este arquivo já está associado a este produto.
				</div>";
				
			}else{
				
				$PastaDestino = "../../img/shop/catalog/";

				$MAXSEQUENCIAselect = mysqli_query($con,"SELECT PRDCOUNTPICTURES AS NUMERO FROM products WHERE PRDCOD = '$PRDCOD'") or print (mysqli_error());
				
				while($PICTURESrow = mysqli_fetch_assoc($MAXSEQUENCIAselect)){
					
					$NUMERO = $PICTURESrow["NUMERO"];
					
				}
				
				$NUMERO = $NUMERO + 1;
				
				/* formatos de imagem permitidos */
				$permitidos = array(".jpg",".jpeg",".png");
				$types = array("image/jpeg","image/pjpeg","image/png","image/x-png");
				
				$tamanho_imagem = $_FILES['imagem']['size'];
				$mimetype = $_FILES['imagem']['type'];
				
				/* pega a extensão do arquivo */
				$ext = ".png"; /*strtolower(strrchr($nome_imagem,"."));*/
				
				/*  verifica se a extensão está entre as extensões permitidas */
				if((in_array($ext,$permitidos)) AND (in_array($mimetype,$types))){
					
					/* converte o tamanho para KB */
					$tamanho = round($tamanho_imagem / 1024);
					
					if($tamanho < 8192){ //se imagem for até 8MB envia
					
						$RenameArquivo = "$PRDCOD-$NUMERO$ext";					
							
						$FOTOinsert = mysqli_query($con,"INSERT INTO productxpictures(PRDCOD,PXPSEQUENCIA,PXPNOMEORIGINAL,PXPNOMEARQUIVO,PXPCADASTRO,PXPCADBY) VALUES ('$PRDCOD' , $NUMERO , '$nome_imagem' , '$RenameArquivo' , '$datetime' , '$ident_session' )") or print (mysqli_error());
						
						if($FOTOinsert){
							
							mysqli_query($con,"UPDATE products SET PRDCOUNTPICTURES = $NUMERO WHERE PRDCOD = '$PRDCOD'") or print (mysqli_error());
							
							// Redimensionar a imagem para 400 por 400...
						
							$altura  = "1080";
							$largura = "1080";						
							
							/*if($mimetype == 'image/jpeg' OR $mimetype == 'image/pjpeg'){
								
								$imagem_original  = imagecreatefromjpeg($_FILES['imagem']['tmp_name']);
								$largura_original = imagesx($imagem_original);
								$altura_original  = imagesy($imagem_original);
								
								$nova_largura = $largura ? $largura : floor (($largura_original / $altura_original) * $altura);
								$nova_altura  = $altura  ? $altura  : floor (($altura_original / $largura_original) * $largura);
								
								$imagem_redimensionada = imagecreatetruecolor($nova_largura, $nova_altura);
								imagecopyresampled($imagem_redimensionada, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura_original, $altura_original);
								
								imagedestroy($PastaDestino.$RenameArquivo);		
								imagejpeg($imagem_redimensionada, $PastaDestino.$RenameArquivo);			
								
							}
							
							if($mimetype == 'image/png' OR $mimetype == 'image/x-png'){*/
								
								$imagem_original  = imagecreatefrompng($_FILES['imagem']['tmp_name']);
								$largura_original = imagesx($imagem_original);
								$altura_original  = imagesy($imagem_original);
								
								$nova_largura = $largura ? $largura : floor (($largura_original / $altura_original) * $altura);
								$nova_altura  = $altura  ? $altura  : floor (($altura_original / $largura_original) * $largura);
								
								$imagem_redimensionada = imagecreatetruecolor($nova_largura, $nova_altura);
								imagecopyresampled($imagem_redimensionada, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura_original, $altura_original);
								
								imagepng($imagem_redimensionada, $PastaDestino.$RenameArquivo);		
								
						  /*}*/
							
						}else{
						
							$MSGalert = 
							"<div class='alert alert-danger alert-dismissable'>
								<a class='close' data-dismiss='alert' aria-label='close'>&times;</a>
								<strong>Ops!</strong>&nbsp;&nbsp;Ocorreu um erro interno no processo de atualização da foto.
							</div>";
								
						}				
						
					}else{
						
						echo "<script> alert('FOTO MUITO GRANDE! O tamanho do arquivo é maior que o permitido (8Mb)'); </script>";
						
					}
				
				}else{
					echo "<script> alert('EXTENSÃO NÃO PERMITIDA! É permitido somente arquivos do tipo imagem (.jpg, .png)'); </script>";
				}
			}
		}
	}//Fim das ações...

	if(isset($_GET['action'])){
		$acao = $_GET['action'];
		
		if($acao == 'remover'){
			
			$PXPID = $_GET['ppid'];
			
			$PXPDELselect = mysqli_query($con,"SELECT PRDCOD,PXPNOMEARQUIVO FROM productxpictures WHERE PXPID = $PXPID") or print (mysqli_error());
			$PXPIDexiste  = mysqli_num_rows($PXPDELselect);
			
			if($PXPIDexiste > 0){
				
				while($PXPDELrow = mysqli_fetch_assoc($PXPDELselect)){
					$PRDCOD = $PXPDELrow["PRDCOD"];
					$PXPDELNOMEARQUIVO = $PXPDELrow["PXPNOMEARQUIVO"];
				}
				
				$arquivo = "../../img/shop/catalog/$PXPDELNOMEARQUIVO";
				imagedestroy($arquivo);
				
				$DELETEfoto = mysqli_query($con,"DELETE FROM productxpictures WHERE PXPID = $PXPID") or print (mysqli_error());
				
				if($DELETEfoto){
					
					mysqli_query($con,"UPDATE products SET PRDCOUNTPICTURES = PRDCOUNTPICTURES-1 WHERE PRDCOD = '$PRDCOD'");         					

					if(!unlink($arquivo)){
						
						echo 
						"<div class='alert alert-danger alert-dismissable'>
							<a href='#featured' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<strong>Ops!</strong> A foto <b>#$PXPID</b> não foi removida do servidor.<br>Entre em contato com o Suporte Técnico.
						</div>";

					}
				}
			}
			
		}
	}
	
	$PAGINA_ORIGINAL = 1;

	$FPERMITIDAS = 4;
		
	$FOTOSselect = mysqli_query($con,"SELECT * FROM productxpictures WHERE PRDCOD = '$PRDCOD' ORDER BY PXPSEQUENCIA") or print (mysqli_error());
	$FOTOStotal  = mysqli_num_rows($FOTOSselect);
	
	$fdisponiveis = $FPERMITIDAS - $FOTOStotal;
	
	?>
	
	<body>
		<div class='container-fluid'>			
        <div class='panel panel-default'>
					<div class='panel-heading'>
						<span class='subpageName'>Fotos do Produto <b><?php echo $PRDCOD;?></b></span>

						<ul class='pull-right list-inline'>
							<li><a href='form_cad_products.php' title='Fechar'><i class='fa fa-times'></i></a></li>
						</ul>
						
					</div>
					<div class='panel-body'>
					  
					  <?php echo $MSGalert;?>
					 
						<div class='row'>
							<!--<div class='col-sm-3 col-md-3' align='center'><img class='imgnatural img-responsive' src='assets/images/other_images/transp-image4.png' /></div>-->
							<div class='col-sm-9 col-md-9'>
							<!--<h4 align='center'>Galeria de Fotos do produto <b><?php echo $PRDCOD;?></b></h4>-->
							<!--<div class='alert alert-info'>Um produto com boas fotos é visualizado até 7x mais que produtos sem imagens, portanto
							capriche na qualidade das fotos apresentadas.</div>-->
							
								<?php
								
									if($fdisponiveis > 0){
										
										echo"
										<div class='row'>
											<div class='col-sm-6 col-md-6'>
												<div class='alert alert-info'><i class='fa fa-exclamation-triangle' aria-hidden='true' style='color: orange;'></i>&nbsp;Resolução ideal da imagem: 300px X 300px<br>As imagens serão redimensionadas a esta resolução.</h6></div>
											</div>
											<div class='col-sm-6 col-md-6'>
												<form id='formulario' class='form-horizontal' name='cadastro' method='POST' enctype='multipart/form-data' action='product_pictures.php' >
													
													<div class='form-group'>													
														<div class='col-sm-12 col-md-12'><input class='form-control' type='file' id='imagem' name='imagem' title='EXTENSÕES PERMITIDAS: .jpg , .jpeg, .gif, .png, .bmp'/></div>
													</div>
													
													<div class='form-group'>														
														<div class='col-sm-12 col-md-12'>
															<button type='submit' class='btn btn-info btn-sm'><i class='fa fa-download' aria-hidden='true'></i>&nbsp;&nbsp;Associar</button>
														</div>
													</div>
													
													<input name='prdcod' type='hidden' value='$PRDCOD'/>
													<input name='acao'   type='hidden' value='UPLOAD'/>
												</form>
											</div>
										</div>";
									
									}
								
								?>
							</div>
						</div>
						
						<div class='row'>
							<div class='col-sm-12 col-md-12 col-xs-12'>
							<?php
							  
							if($PAGINA_ORIGINAL == 1){

								if($FOTOStotal > 0){
									
									/*if($fdisponiveis > 0){
										echo "<div class='info'>Total de fotos neste produto: <b>$FOTOStotal</b><br>Fotos disponíveis: <b>$fdisponiveis</b></div>";
									}*/

									while($PXProw = mysqli_fetch_array($FOTOSselect)){
										
										$PXPID = $PXProw["PXPID"];
										$PXPNOMEARQUIVO = $PXProw["PXPNOMEARQUIVO"];
												
										echo"
										<div class='col-sm-3 col-md-3 col-xs-6' align='center' style='padding: 10px;'>
											<span><a href='?prdcod=$PRDCOD&ppid=$PXPID&action=remover' onclick='return confirma_excluir();'><i class='fa fa-trash-o' aria-hidden='true'></i></a></span>
											<img class='imgnatural img-responsive' src='../../img/shop/catalog/$PXPNOMEARQUIVO'/>
										</div>";
								
									}

								}
								
								$i = $FOTOStotal;

								while($i < $FPERMITIDAS){
									echo"
									<div class='col-sm-3 col-md-3 col-xs-6' align='center' style='padding: 10px;'>
									  <span><i class='fa fa-plus' aria-hidden='true'></i></span>
										<img class='imgnatural img-responsive' src='../images/ProdutoSemFoto.png' />
									</div>";

									$i = $i+1;
								}
								
							}
							
							if($fdisponiveis == 0){
								
								echo "<div align='center' class='warning'>Você associou a quantidade máxima de fotos permitida ($FPERMITIDAS</div>";
								
							}
							
							echo"
						  </div>
						</div>";						
										
						if($FOTOStotal >= 1){ //No mínimo uma foto...
							
							echo "<br><br>
							<div class='row' align='center'>
								<div class='col-sm-3 col-md-3 col-xs-12'>
									<a href='form_cad_products.php?prdcod=$PRDCOD&action=PUBLICAR' class='btn btn-success btn-sm'><i class='fa fa-cloud-upload' aria-hidden='true' title='SEU PRODUTO AINDA NÃO FOI PUBLICADO. PUBLIQUE-O AGORA!'></i>&nbsp;Publicar seu produto agora</a></li>
								</div>
							</div>";
						
						}		
							
						
						?>
					</div>
				</div>			
		</div>
	</body>
</html>
						


