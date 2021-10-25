<?php include 'head.php';?>

	<script type="text/javascript">
		function confirma_inativar(){
			
			if(confirm('ATENÇÃO: INATIVAR ESTE PRODUTO? Pedidos lançados não serão afetados.\n\nClique em OK para continuar...'))
			return true;
			else
			return false;
			
		}

		function confirma_ativar(){
			
			if(confirm('ATENÇÃO: ATIVAR ESTE PRODUTO? Estará imediatamente disponível no e-commerce\n\nClique em OK para continuar...'))
			return true;
			else
			return false;
			
		}

		$(document).ready(function(){
			
			$("#prdpricebuy").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});
			$("#prdtaxes").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});
			$("#prdpriceoficial").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});
			$("#prdpricesale").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});      		
			
		});

	</script> 
	
<body>
	<div class='container-fluid'>
	
		<div class='panel panel-default'>
			<div class='panel-heading'>
				<span class='subpageName'>Products</span>

				<ul class='pull-right list-inline'>
					<li><a href='home.php' title='Fechar'><i class='fa fa-times'></i></a></li>
				</ul>
			</div>

			<div class='panel-body'>
			
	<?php 
	
	  $action = NULL;
	
	  if(isset($_GET['action'])){
			$action = $_GET['action'];
			
			$PRDCOD = $_GET['prdcod'];
		}
		
		if (isset($_POST['acao'])){
			$action = $_POST['acao'];	
		}
			
			if($action == 'PUBLICAR'){
				
				$PRDupdate = mysqli_query($con,"UPDATE products SET PRDSTATUS = 2, 
				                                                  PRDDTSTATUS = '$datetime', 
																												 PRDUSRSTATUS = '$ident_session' WHERE PRDCOD = '$PRDCOD'") or print(mysqli_error());				
				if($PRDupdate){
					
					echo "<div id='alert' align='center' class='success'><i>Product</i> <b>$PRDCOD</b> PUBLICADO com sucesso.</div>";
					
				}
				
			}
			
			if ($action == 'INATIVAR'){
				
				$PRDCOD = $_POST['prdcod'];
				
				$PRDupdate = mysqli_query($con,"UPDATE products SET PRDSTATUS = 0, PRDDTSTATUS = '$datetime', PRDUSRSTATUS = '$ident_session' WHERE PRDCOD = '$PRDCOD'") or print(mysqli_error());
				
				if($PRDupdate){
					echo "<div align='center' class='success'><i>Product</i> <b>$PRDCOD</b> desativado.</div>";
					
				}else{
					echo "<div align='center' class='error'><i>Product</i> <b>$PRDCOD</b> não pode ser desativado.</div>";
				}
				
			}
			
			if ($action == 'ATIVAR'){
				
				$PRDCOD = $_POST['prdcod'];
				
				$PRDupdate = mysqli_query($con,"UPDATE products SET PRDSTATUS=1,PRDDTSTATUS='$datetime',PRDUSRSTATUS='$ident_session' WHERE PRDCOD = '$PRDCOD'") or print(mysqli_error());
				
				if($PRDupdate){
					echo "<div id='alert' align='center' class='success'><i>Product</i> <b>$PRDCOD</b> ativado com sucesso.</div>";
					
				}else{
					echo "<div align='center' class='error'><i>Product</i> <b>$PRDCOD</b> não pode ser ativado.</div>";
				}
			}
			
			if($action == 'EDITAR'){
				
				$PRDCOD = $_POST['prdcod'];				
								
				$PRDEDITselect = mysqli_query($con,"SELECT * FROM products WHERE PRDCOD = '$PRDCOD'") or print(mysqli_error());
				
				while($PRDEDITrow = mysqli_fetch_assoc($PRDEDITselect)){
					
					$PRDID             = $PRDEDITrow["PRDID"];					
					$GENCOD            = $PRDEDITrow["GENCOD"];
					$SEGCOD            = $PRDEDITrow["SEGCOD"];
					$DEPTCOD           = $PRDEDITrow["DEPTCOD"];
					$CATCOD            = $PRDEDITrow["CATCOD"];
					$GRPCOD            = $PRDEDITrow["GRPCOD"];
					$BRDCOD            = $PRDEDITrow["BRDCOD"];
					$COLORCOD          = $PRDEDITrow["COLORCOD"];
					$SIZECOD           = $PRDEDITrow["SIZECOD"];
					$PRDCOD            = $PRDEDITrow["PRDCOD"];
					$PRDNAME           = $PRDEDITrow["PRDNAME"];
					$PRDDESCRIPTION    = $PRDEDITrow["PRDDESCRIPTION"];
					$PRDAPRESENTATION  = $PRDEDITrow["PRDAPRESENTATION"];
					$PRDESPECIFICATION = $PRDEDITrow["PRDESPECIFICATION"];
					$PRDPRICEBUY       = $PRDEDITrow["PRDPRICEBUY"];
					$PRDIDEALMARGIM    = $PRDEDITrow["PRDIDEALMARGIM"];
					$PRDPRICEOFICIAL   = $PRDEDITrow["PRDPRICEOFICIAL"];
					$PRDINSALE         = $PRDEDITrow["PRDINSALE"];
					$PRDPRICESALE      = $PRDEDITrow["PRDPRICESALE"];
					$PRDUNITWEIGHT     = $PRDEDITrow["PRDUNITWEIGHT"];
					$PRDUNITHEIGHT     = $PRDEDITrow["PRDUNITHEIGHT"];
					$PRDUNITWIDTH      = $PRDEDITrow["PRDUNITWIDTH"];
					$PRDUNITLENGTH     = $PRDEDITrow["PRDUNITLENGTH"];
					
					$GRPEDITselect = mysqli_query($con,"SELECT GRPNAME FROM groups WHERE GRPAPPLY IN('DEPT') AND GRPCOD = '$GRPCOD'");					
					while($GRPcol  = mysqli_fetch_assoc($GRPEDITselect)){	$grpname = $GRPcol["GRPNAME"];	}
					
					$DEPTEDITselect = mysqli_query($con,"SELECT DEPTNAME FROM departments WHERE DEPTCOD = '$DEPTCOD'");
					while($DEPTcol  = mysqli_fetch_assoc($DEPTEDITselect)){	$deptname = $DEPTcol["DEPTNAME"]; }

        }			
				
				echo "
				
				<fieldset><legend class='subpageName'>&nbsp;&nbsp;Editar <i>Product</i>&nbsp;&nbsp;</legend>
					<div align='right'>
						<form name='fechar' method='POST' action='form_cad_products.php'>
							<input class='inputsrc' type='image' src='../images/close.png' title='Fechar'/>
						</form>
					</div>
					
					<form class='form-horizontal' name='new' method='POST' action='form_cad_products.php'>
						
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'>Código:</label>
							<div class='col-sm-4 col-md-4'><input class='form-control' name='prdcod' type='text' id='prdcod' value='$PRDCOD' readonly /></div>
							
							<label class='col-sm-2 col-md-2 control-label'>Name:</label>
							<div class='col-sm-4 col-md-4'><input class='form-control' name='prdname' type='text' id='prdname' maxlength='100' value='$PRDNAME' placeholder='Nome reduzido do ítem (sem especificações)...' required /></div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'><i>Segment:</i></label>
							<div class='col-sm-4 col-md-4'>
								<select class='form-control' name='segcod' required>
								  <option value='$SEGCOD'>$SEGCOD | </option>
									<option value='SEGSHOP001'>SEGSHOP001 | Moda Bebê</option>
									<option value='SEGSHOP002'>SEGSHOP002 | Moda Gestante</option>
									<option value='SEGSHOP003'>SEGSHOP003 | Moda Mamãe/Bebê</option>
									<option value='SEGSHOP004'>SEGSHOP004 | Moda Papai/Bebê</option>									
									<option value='SEGSHOP005'>SEGSHOP005 | Moda Infantil</option>
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label'><i>Gender:</i></label>
							<div class='col-sm-4 col-md-4'>
								<select class='form-control' name='gencod' required>
									<option value='$GENCOD'>$GENCOD |</option>
									<option value='M'>M | Masculino</option>
									<option value='F'>F | Feminino</option>
									<option value='N'>N | Neutro</option>
								</select>
							</div>
						</div>
						
						<div class='form-group'>
						
						  <label class='col-sm-2 col-md-2 control-label'><i>Group:</i></label>
							<div class='col-sm-4 col-md-4'>
							
								<select class='form-control' name='grpcod' required>
								  <option value='$GRPCOD'>$GRPCOD | $grpname</option>";
									
									  $GRPselect = mysqli_query($con,"SELECT * FROM groups WHERE GRPAPPLY IN('DEPT') AND GRPSTATUS = 1");
										$GRPtotal  = mysqli_num_rows($GRPselect);
										
										if($GRPtotal > 0){									
											
											while($GRProw = mysqli_fetch_assoc($GRPselect)){
												
												$GRPCOD  = $GRProw["GRPCOD"];
												$GRPNAME = $GRProw["GRPNAME"];
												
												echo "<option value='$GRPCOD'>$GRPCOD | $GRPNAME</option>";
												
											}
										
										}else{
											
											echo "<option value=''>Nenhum Group ativo.</option>";			
											
										}
										
									echo "
									
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label'><i>Department:</i></label>
							<div class='col-sm-4 col-md-4'>
								<select class='form-control' name='deptcod' required>
								  <option value='$DEPTCOD'>$DEPTCOD | $deptname</option>";
									
									  $DEPTselect = mysqli_query($con,"SELECT * FROM departments WHERE DEPTSTATUS = 1");
										$DEPTtotal  = mysqli_num_rows($DEPTselect);
										
										if($DEPTtotal > 0){
											
											while($DEPTrow = mysqli_fetch_assoc($DEPTselect)){
												
												$DEPTCOD  = $DEPTrow["DEPTCOD"];
												$DEPTNAME = $DEPTrow["DEPTNAME"];
												
												echo "<option value='$DEPTCOD'>$DEPTCOD | $DEPTNAME</option>";
											}
										
										}else{
											
											echo "<option value=''>Nenhum Department ativo.</option>";			
											
										}
										
								  echo "
								</select>
							</div>						
							
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'><i>Categorie:</i></label>
							<div class='col-sm-4 col-md-4'>
								<select class='form-control' name='catcod' required>
									<option value='$CATCOD'>$CATCOD |</option>
									<option value='PM'>PM | Prematuro</option>
									<option value='RN'>RN | Recém-Nascido</option>
									<option value='36'>36 |3 a 6 meses</option>
									<option value='712'> 712 | 7 a 12 meses</option>
									<option value='1324'>1324 | 1 a 2 anos</option>
									<option value='INDIFERENTE'>INDIFERENTE</option>
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label'><i>Brand:</i></label>
							<div class='col-sm-4 col-md-4'>
								<select class='form-control' name='brdcod' required>
									<option value='$BRDCOD'>$BRDCOD</option>
									<option value='BRD000'>BRD000 | ARTE MINAS (Private Label)</option>
									<option value='BRD001'>BRD001 | BABADROOM (Private Label)</option>
									<option value='BRD002'>BRD002 | TORCIDA BABY</option>
									<option value='BRD003'>BRD003 | NILLY BABY</option>
								</select>
							</div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'>Peso:</label>
							<div class='col-sm-1 col-md-1'>
								<select class='form-control' name='prdunitweight' required>
									<option value='$PRDUNITWEIGHT'>Até $PRDUNITWEIGHT Kg</option>
									<option value='0.3'>Até 300gr</option>
									<option value='1'>Até 1 Kg</option>
									<option value='2'>Até 2 Kg</option>
									<option value='3'>Até 3 Kg</option>
									<option value='4'>Até 4 Kg</option>
									<option value='5'>Até 5 Kg</option>
									<option value='6'>Até 6 Kg</option>
									<option value='7'>Até 7 Kg</option>
									<option value='8'>Até 8 Kg</option>
									<option value='9'>Até 9 Kg</option>
									<option value='10'>Até 10 Kg</option>
									<option value='11'>Até 11 Kg</option>
									<option value='12'>Até 12 Kg</option>
									<option value='13'>Até 13 Kg</option>
									<option value='14'>Até 14 Kg</option>
									<option value='15'>Até 15 Kg</option>
									<option value='16'>Até 16 Kg</option>
									<option value='17'>Até 17 Kg</option>
									<option value='18'>Até 18 Kg</option>
									<option value='19'>Até 19 Kg</option>
									<option value='20'>Até 20 Kg</option>
									<option value='21'>Até 21 Kg</option>
									<option value='22'>Até 22 Kg</option>
									<option value='23'>Até 23 Kg</option>
									<option value='24'>Até 24 Kg</option>
									<option value='25'>Até 25 Kg</option>
									<option value='26'>Até 26 Kg</option>
									<option value='27'>Até 27 Kg</option>
									<option value='28'>Até 28 Kg</option>
									<option value='29'>Até 29 Kg</option>
									<option value='30'>Até 30 Kg</option>
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label'>Altura:</label>
							<div class='col-sm-1 col-md-1'>
								<select class='form-control' name='prdunitheight' required>
									<option value=$PRDUNITHEIGHT>Até $PRDUNITHEIGHT cm</option>
									<option value=5>Até 5cm</option>
									<option value=10>Até 10cm</option>
									<option value=15>Até 15cm</option>
									<option value=20>Até 20cm</option>
									<option value=25>Até 25cm</option>
									<option value=30>Até 30cm</option>
									<option value=35>Até 35cm</option>
									<option value=40>Até 40cm</option>
									<option value=45>Até 45cm</option>
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label'>Largura:</label>
							<div class='col-sm-1 col-md-1'>
								<select class='form-control' name='prdunitwidth' required>
									<option value=$PRDUNITWIDTH>Até $PRDUNITWIDTH cm</option>
									<option value=15>Até 15cm</option>
									<option value=20>Até 20cm</option>
									<option value=25>Até 25cm</option>
									<option value=30>Até 30cm</option>
									<option value=35>Até 35cm</option>
									<option value=40>Até 40cm</option>
									<option value=45>Até 45cm</option>
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label'>Comprimento:</label>
							<div class='col-sm-1 col-md-1'>
								<select class='form-control' name='prdunitlength' required>
								  <option value=$PRDUNITLENGTH>Até $PRDUNITLENGTH cm</option>
									<option value=15>Até 15cm</option>
									<option value=20>Até 20cm</option>
									<option value=25>Até 25cm</option>
									<option value=30>Até 30cm</option>
									<option value=35>Até 35cm</option>
									<option value=40>Até 40cm</option>
									<option value=45>Até 45cm</option>
								</select>
							</div>
						</div>
						
						<div class='form-group'>
						
							<label class='col-sm-2 col-md-2 control-label'>Preço Custo(R$):</label>
							<div class='col-sm-2'><input class='form-control' name='prdpricebuy' id='prdpricebuy' type='text' value='$PRDPRICEBUY' required title='Preço de Custo Total...'/></div>
							
							<label class='col-sm-2 col-md-2 control-label'>Taxas:</label>
							<div class='col-sm-2 col-md-2'><input class='form-control' name='prdtaxes' id='prdtaxes' type='text' value='$PRDTAXES' required title='Valor Total de Impostos, Taxas, etc...'/></div>
							
							<label class='col-sm-2 col-md-2 control-label'>Margem ideal(%):</label>
							<div class='col-sm-2 col-md-2'><input class='form-control' name='prdidealmargin' id='prdidealmargin' type='number' min='0' max='300' value='$PRDIDEALMARGIM' required title='Margem de Lucro ideal (comporá o Preço de Venda 1 do Produto)...'/></div>
						
						</div>
						
						<div class='form-group'>
						
							<label class='col-sm-2 col-md-2 control-label'>Preço Venda:</label>
							<div class='col-sm-2 col-md-2'><input class='form-control' name='prdpriceoficial' id='prdpriceoficial' type='text' value='$PRDPRICEOFICIAL' required title='Preço de Venda Principal...'/></div>
							
							<label class='col-sm-2 col-md-2 control-label'>Em Promoção?:</label>
							<div class='col-sm-2 col-md-2'>
								<select class='form-control' name='prdinsale' required>
								  <option value='$PRDINSALE'>$PRDINSALE</option>
									<option value=0>NÃO</option>
									<option value=1>SIM</option>
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label norequired'>Preço Promocional:</label>
							<div class='col-sm-2 col-md-2'><input class='form-control' name='prdpricesale' id='prdpricesale' type='text' value='$PRDPRICESALE' title='Preço Promocional Ativo'/></div>
						
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'>Descrição:</label>
						  <div class='col-sm-10 col-md-10'><textarea class='form-control' name='prddescription' rows='5' placeholder='Descrição completa do Produto (Feed do Instagram)...' required >$PRDDESCRIPTION</textarea></div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'>Apresentação Comercial:</label>
						  <div class='col-sm-10 col-md-10'><textarea class='form-control' name='prdapresentation' rows='5' placeholder='Porque o cliente deve comprar este ítem?' required >$PRDAPRESENTATION</textarea></div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'>Especificação Técnica:</label>
						  <div class='col-sm-10 col-md-10'><textarea class='form-control' name='prdespecification' rows='10' placeholder='Especificação técnica completa do produto...' required >$PRDESPECIFICATION</textarea></div>
						</div>
						
						<input name='acao' type='hidden' value='CONFIRMAR_EDICAO'/></input>
						<div align='right'><button class='btn btn-info btn-sm' type='submit'>Salvar</button></div>
						
					</form>
				</fieldset><br>";
				
			}
			
			if ($action == 'CONFIRMAR_EDICAO'){
				
				$GENCOD            = $_POST["gencod"];
				$SEGCOD            = $_POST["segcod"];
				$DEPTCOD           = $_POST["deptcod"];
				$CATCOD            = $_POST["catcod"];
				$GRPCOD            = $_POST["grpcod"];
				$BRDCOD            = $_POST["brdcod"];
				$PRDCOD            = $_POST["prdcod"];
				$PRDNAME           = $_POST["prdname"];
				$PRDDESCRIPTION    = $_POST["prddescription"];
				$PRDAPRESENTATION  = $_POST["prdapresentation"];
				$PRDESPECIFICATION = $_POST["prdespecification"];
				$PRDPRICEBUY       = $_POST["prdpricebuy"];
				$PRDIDEALMARGIM    = $_POST["prdidealmargin"];
				$PRDTAXES          = $_POST["prdtaxes"];
				$PRDPRICEOFICIAL   = $_POST["prdpriceoficial"];
				$PRDINSALE         = $_POST["prdinsale"];
				$PRDPRICESALE      = $_POST["prdpricesale"];			
				$PRDUNITWEIGHT     = $_POST["prdunitweight"];
				$PRDUNITHEIGHT     = $_POST["prdunitheight"];
				$PRDUNITWIDTH      = $_POST["prdunitwidth"];
				$PRDUNITLENGTH     = $_POST["prdunitlength"];				
					
				$PRDupdate = mysqli_query($con,"UPDATE products SET PRDNAME = '$PRDNAME',
																														 GENCOD = '$GENCOD',           
																														 SEGCOD = '$SEGCOD',           
																														DEPTCOD = '$DEPTCOD',          
																														 CATCOD = '$CATCOD',           
																														 GRPCOD = '$GRPCOD',           
																														 BRDCOD = '$BRDCOD',           
																														 PRDCOD = '$PRDCOD',           
																														PRDNAME = '$PRDNAME',          
																										 PRDDESCRIPTION = '$PRDDESCRIPTION',   
																									 PRDAPRESENTATION = '$PRDAPRESENTATION', 
																									PRDESPECIFICATION = '$PRDESPECIFICATION',
																												PRDPRICEBUY = '$PRDPRICEBUY',      
																										 PRDIDEALMARGIM = '$PRDIDEALMARGIM',   
																													 PRDTAXES = '$PRDTAXES',         
																										PRDPRICEOFICIAL = '$PRDPRICEOFICIAL',  
																													PRDINSALE = $PRDINSALE,        
																											 PRDPRICESALE = '$PRDPRICESALE',     
																											PRDUNITWEIGHT = '$PRDUNITWEIGHT',    
																											PRDUNITHEIGHT = $PRDUNITHEIGHT,    
																											 PRDUNITWIDTH = $PRDUNITWIDTH,     
																											PRDUNITLENGTH = $PRDUNITLENGTH,
																												 ULTALTERON = '$datetime',
																												 ULTALTERBY = '$ident_session' WHERE PRDCOD='$PRDCOD'") or print (mysqli_error($con));				
				if($PRDupdate){
					
					echo "<div id='alert' align='center' class='success'><i>Product</i> <b>$PRDCOD</b> alterado com sucesso.</div>";

				}		
				
			}
			
			if($action == 'ADD'){
				
				?>
				
				<fieldset><legend class='subpageName'>&nbsp;&nbsp;Incluir <i>Product</i>&nbsp;&nbsp;</legend>
					<div align='right'>
						<form name='fechar' method='POST' action='form_cad_products.php'>
							<input class='inputsrc' type='image' src='../images/close.png' title='Fechar'/>
						</form>
					</div>
					
					<form class='form-horizontal' name='new' method='POST' action='form_cad_products.php'>
						
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'>Código:</label>
							<div class='col-sm-4 col-md-4'><input class='form-control' name='prdcod' type='text' id='prdcod' value='<?php echo uniqid();?>' required /></div>
							
							<label class='col-sm-2 col-md-2 control-label'>Name:</label>
							<div class='col-sm-4 col-md-4'><input class='form-control' name='prdname' type='text' id='prdname' maxlength='100' placeholder='Nome reduzido do ítem (sem especificações)...' required /></div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'><i>Segment:</i></label>
							<div class='col-sm-4 col-md-4'>
								<select class='form-control' name='segcod' required>
									<option value='SEGSHOP001'>SEGSHOP001 | Moda Bebê</option>
									<option value='SEGSHOP002'>SEGSHOP002 | Moda Gestante</option>
									<option value='SEGSHOP003'>SEGSHOP003 | Moda Mamãe/Bebê</option>
									<option value='SEGSHOP004'>SEGSHOP004 | Moda Papai/Bebê</option>									
									<option value='SEGSHOP005'>SEGSHOP005 | Moda Infantil</option>
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label'><i>Gender:</i></label>
							<div class='col-sm-4 col-md-4'>
								<select class='form-control' name='gencod' required>
									<option value=''>Selecione...</option>
									<option value='M'>M | Masculino</option>
									<option value='F'>F | Feminino</option>
									<option value='N'>N | Neutro</option>
								</select>
							</div>
						</div>
						
						<div class='form-group'>
						
						  <label class='col-sm-2 col-md-2 control-label'><i>Group:</i></label>
							<div class='col-sm-4 col-md-4'>
							
								<select class='form-control' name='grpcod' required>
								
									<?php
									
									  $GRPselect = mysqli_query($con,"SELECT * FROM groups WHERE GRPAPPLY IN('DEPT') AND GRPSTATUS = 1");
										$GRPtotal  = mysqli_num_rows($GRPselect);
										
										if($GRPtotal > 0){
											
											echo "<option value=''>Selecione...</option>";
											
											while($GRProw = mysqli_fetch_assoc($GRPselect)){
												
												$GRPCOD  = $GRProw["GRPCOD"];
												$GRPNAME = $GRProw["GRPNAME"];
												
												echo "<option value='$GRPCOD'>$GRPCOD | $GRPNAME</option>";
												
											}
										
										}else{
											
											echo "<option value=''>Nenhum Group ativo.</option>";			
											
										}
										
									?>
									
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label'><i>Department:</i></label>
							<div class='col-sm-4 col-md-4'>
								<select class='form-control' name='deptcod' required>
									<?php
									
									  $DEPTselect = mysqli_query($con,"SELECT * FROM departments WHERE DEPTSTATUS = 1");
										$DEPTtotal  = mysqli_num_rows($DEPTselect);
										
										if($DEPTtotal > 0){
											
											echo "<option value=''>Selecione...</option>";
											
											while($DEPTrow = mysqli_fetch_assoc($DEPTselect)){
												
												$DEPTCOD  = $DEPTrow["DEPTCOD"];
												$DEPTNAME = $DEPTrow["DEPTNAME"];
												
												echo "<option value='$DEPTCOD'>$DEPTCOD | $DEPTNAME</option>";
											}
										
										}else{
											
											echo "<option value=''>Nenhum Department ativo.</option>";			
											
										}
										
									?>
								</select>
							</div>						
							
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'><i>Categorie:</i></label>
							<div class='col-sm-4 col-md-4'>
								<select class='form-control' name='catcod' required>
									<option value=''>Selecione...</option>
									<option value='PM'>PM | Prematuro</option>
									<option value='RN'>RN | Recém-Nascido</option>
									<option value='36'>36 |3 a 6 meses</option>
									<option value='712'> 712 | 7 a 12 meses</option>
									<option value='1324'>1324 | 1 a 2 anos</option>
									<option value='INDIFERENTE'>INDIFERENTE</option>
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label'><i>Brand:</i></label>
							<div class='col-sm-4 col-md-4'>
								<select class='form-control' name='brdcod' required>
									<option value=''>Selecione...</option>
									<option value='BRD000'>BRD000 | Arte Minas (Private Label)</option>
									<option value='BRD001'>BRD001 | BABADROOM</option>
									<option value='BRD002'>BRD002 | TORCIDA BABY</option>
									<option value='BRD003'>BRD003 | NILLY BABY</option>
								</select>
							</div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'>Peso:</label>
							<div class='col-sm-1 col-md-1'>
								<select class='form-control' name='prdunitweight' required>
									<option value=''>PESO...</option>
									<option value='0.3'>Até 300gr</option>
									<option value='1'>Até 1Kg</option>
									<option value='2'>Até 2Kg</option>
									<option value='3'>Até 3Kg</option>
									<option value='4'>Até 4Kg</option>
									<option value='5'>Até 5Kg</option>
									<option value='6'>Até 6Kg</option>
									<option value='7'>Até 7Kg</option>
									<option value='8'>Até 8Kg</option>
									<option value='9'>Até 9Kg</option>
									<option value='10'>Até 10Kg</option>
									<option value='11'>Até 11Kg</option>
									<option value='12'>Até 12Kg</option>
									<option value='13'>Até 13Kg</option>
									<option value='14'>Até 14Kg</option>
									<option value='15'>Até 15Kg</option>
									<option value='16'>Até 16Kg</option>
									<option value='17'>Até 17Kg</option>
									<option value='18'>Até 18Kg</option>
									<option value='19'>Até 19Kg</option>
									<option value='20'>Até 20Kg</option>
									<option value='21'>Até 21Kg</option>
									<option value='22'>Até 22Kg</option>
									<option value='23'>Até 23Kg</option>
									<option value='24'>Até 24Kg</option>
									<option value='25'>Até 25Kg</option>
									<option value='26'>Até 26Kg</option>
									<option value='27'>Até 27Kg</option>
									<option value='28'>Até 28Kg</option>
									<option value='29'>Até 29Kg</option>
									<option value='30'>Até 30Kg</option>
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label'>Altura:</label>
							<div class='col-sm-1 col-md-1'>
								<select class='form-control' name='prdunitheight' required>
									<option value=5>Até 5cm</option>
									<option value=10>Até 10cm</option>
									<option value=15>Até 15cm</option>
									<option value=20>Até 20cm</option>
									<option value=25>Até 25cm</option>
									<option value=30>Até 30cm</option>
									<option value=35>Até 35cm</option>
									<option value=40>Até 40cm</option>
									<option value=45>Até 45cm</option>
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label'>Largura:</label>
							<div class='col-sm-1 col-md-1'>
								<select class='form-control' name='prdunitwidth' required>
									<option value=15>Até 15cm</option>
									<option value=20>Até 20cm</option>
									<option value=25>Até 25cm</option>
									<option value=30>Até 30cm</option>
									<option value=35>Até 35cm</option>
									<option value=40>Até 40cm</option>
									<option value=45>Até 45cm</option>
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label'>Comprimento:</label>
							<div class='col-sm-1 col-md-1'>
								<select class='form-control' name='prdunitlength' required>
									<option value=15>Até 15cm</option>
									<option value=20>Até 20cm</option>
									<option value=25>Até 25cm</option>
									<option value=30>Até 30cm</option>
									<option value=35>Até 35cm</option>
									<option value=40>Até 40cm</option>
									<option value=45>Até 45cm</option>
								</select>
							</div>
						</div>
						
						<div class='form-group'>
						
							<label class='col-sm-2 col-md-2 control-label'>Preço Custo(R$):</label>
							<div class='col-sm-2'><input class='form-control' name='prdpricebuy' id='prdpricebuy' type='text' value='0.00' required title='Preço de Custo Total...'/></div>
							
							<label class='col-sm-2 col-md-2 control-label'>Taxas:</label>
							<div class='col-sm-2 col-md-2'><input class='form-control' name='prdtaxes' id='prdtaxes' type='text' value='0.00' required title='Valor Total de Impostos, Taxas, etc...'/></div>
							
							<label class='col-sm-2 col-md-2 control-label'>Margem ideal(%):</label>
							<div class='col-sm-2 col-md-2'><input class='form-control' name='prdidealmargin' id='prdidealmargin' type='number' min='0' max='300' required title='Margem de Lucro ideal (comporá o Preço de Venda 1 do Produto)...'/></div>
						
						</div>
						
						<div class='form-group'>
						
							<label class='col-sm-2 col-md-2 control-label'>Preço Venda:</label>
							<div class='col-sm-2 col-md-2'><input class='form-control' name='prdpriceoficial' id='prdpriceoficial' type='text' value='0.00' required title='Preço de Venda Principal...'/></div>
							
							<label class='col-sm-2 col-md-2 control-label'>Em Promoção?:</label>
							<div class='col-sm-2 col-md-2'>
								<select class='form-control' name='prdinsale' required>
									<option value=0>NÃO</option>
									<option value=1>SIM</option>
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label norequired'>Preço Promocional:</label>
							<div class='col-sm-2 col-md-2'><input class='form-control' name='prdpricesale' id='prdpricesale' type='text' value='0.00' title='Preço de Venda 3...'/></div>
						
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'>Descrição:</label>
						  <div class='col-sm-10 col-md-10'><textarea class='form-control' name='prddescription' rows='5' placeholder='Descrição completa do Produto (Feed do Instagram)...' required ></textarea></div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'>Apresentação Comercial:</label>
						  <div class='col-sm-10 col-md-10'><textarea class='form-control' name='prdapresentation' rows='5' placeholder='Porque o cliente deve comprar este ítem?' required ></textarea></div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'>Especificação Técnica:</label>
						  <div class='col-sm-10 col-md-10'><textarea class='form-control' name='prdespecification' rows='10' placeholder='Especificação técnica completa do produto...' required ></textarea></div>
						</div>
						
						<input name='acao' type='hidden' value='SALVAR'/></input>
						<div align='right'><button class='btn btn-info btn-sm' type='submit'>Salvar</button></div>
						
					</form>
				</fieldset><br>
				
				<?php
				
			}
			
			if($action == 'SALVAR'){
							
				$GENCOD            = $_POST["gencod"];
				$SEGCOD            = $_POST["segcod"];
				$DEPTCOD           = $_POST["deptcod"];
				$CATCOD            = $_POST["catcod"];
				$GRPCOD            = $_POST["grpcod"];
				$BRDCOD            = $_POST["brdcod"];
				$PRDCOD            = $_POST["prdcod"];
				$PRDNAME           = $_POST["prdname"];
				$PRDDESCRIPTION    = $_POST["prddescription"];
				$PRDAPRESENTATION  = $_POST["prdapresentation"];
				$PRDESPECIFICATION = $_POST["prdespecification"];
				$PRDPRICEBUY       = $_POST["prdpricebuy"];
				$PRDIDEALMARGIM    = $_POST["prdidealmargin"];
				$PRDTAXES          = $_POST["prdtaxes"];
				$PRDPRICEOFICIAL   = $_POST["prdpriceoficial"];
				$PRDINSALE         = $_POST["prdinsale"];
				$PRDPRICESALE      = $_POST["prdpricesale"];
				
				$PRDUNITWEIGHT     = $_POST["prdunitweight"];
				$PRDUNITHEIGHT     = $_POST["prdunitheight"];
				$PRDUNITWIDTH      = $_POST["prdunitwidth"];
				$PRDUNITLENGTH     = $_POST["prdunitlength"];
				
				$PRDverifica = mysqli_query($con,"SELECT PRDID FROM products WHERE PRDCOD = '$PRDCOD'") or print (mysql_error());
				$PRDexiste   = mysqli_num_rows($PRDverifica);
				
				if($PRDexiste == 0){
					
					$PRDINSERTquery = "INSERT INTO products (
					GENCOD,
					SEGCOD,
					DEPTCOD,
					CATCOD,
					GRPCOD,
					BRDCOD,
					PRDCOD,
					PRDNAME,
					PRDDESCRIPTION,
					PRDAPRESENTATION,
					PRDESPECIFICATION,
					PRDPRICEBUY,
					PRDIDEALMARGIM,
					PRDTAXES,
					PRDPRICEOFICIAL,
					PRDINSALE,
					PRDPRICESALE,    
					PRDUSRSTATUS,
					PRDCADBY,
					PRDUNITWEIGHT,
					PRDUNITHEIGHT,
					PRDUNITWIDTH,
					PRDUNITLENGTH) VALUES (
					'$GENCOD',
					'$SEGCOD',
					'$DEPTCOD',
					'$CATCOD',
					'$GRPCOD',
					'$BRDCOD',
					'$PRDCOD',
					'$PRDNAME',
					'$PRDDESCRIPTION',
					'$PRDAPRESENTATION', 
					'$PRDESPECIFICATION',
					'$PRDPRICEBUY',
					'$PRDIDEALMARGIM',
					'$PRDTAXES',
					'$PRDPRICEOFICIAL',
					$PRDINSALE,
					'$PRDPRICESALE',
					'$ident_session',
					'$ident_session',
					'$PRDUNITWEIGHT',
					$PRDUNITHEIGHT,
					$PRDUNITWIDTH,
					$PRDUNITLENGTH)";
					
					$PRDinsert = mysqli_query($con,$PRDINSERTquery) or print "<div align='center' class='alert alert-error'>ERRO NO INSERT NO BANCO DE DADOS.</div>";
				
					if($PRDinsert){
						
						echo "<script>location.href='product_pictures.php?prdcod=$PRDCOD&success=1';</script>";
					
					}
					
				}
			}
			
			//Fim de ações

			$PRDselect = mysqli_query($con,"SELECT * FROM products ORDER BY PRDNAME");
			$PRDtotal  = mysqli_num_rows($PRDselect);

			if($PRDtotal > 0){
				echo "
				<table id='DataTables' width='100%' class='display table-responsive table-action table-condensed table-striped'>
					<thead>
						<th width='2%' data-orderable='false'><div align='center' ></div></th>
						<th width='10%'><div align='center'>Código       </div></th>
						<th width='35%'><div>Descrição    </div></th>
						<th width='10%'><div align='center'>Preço Custo(R$)</div></th>
						<th width='10%'><div align='center'>Margem Ideal (%)</div></th>
						<th width='10%'><div align='center'>Preço Oficial(R$)</div></th>
						<th width='10%'><div align='center'>Promoção Ativa(R$)</div></th>
						<th width='10%'><div align='center'>Qtde. Fotos</div></th>
						<th width='3%' data-orderable='false'>
							<div align='center'>
								<form name='novo' method='POST' action='form_cad_products.php'>
									<input name='acao'  type='hidden' value='ADD'/>
									<button type='submit' class='btn btn-default btn-sm' title='Incluir'><i class='fa fa-plus'></i></button>
								</form>
							</div>
						</th>
					</thead>
					<tbody>";
							
					while($PRDrow = mysqli_fetch_assoc($PRDselect)){
						
						$PRDID              = $PRDrow["PRDID"];					
						$GENCOD             = $PRDrow["GENCOD"];
						$SEGCOD             = $PRDrow["SEGCOD"];
						$DEPTCOD            = $PRDrow["DEPTCOD"];
						$CATCOD             = $PRDrow["CATCOD"];
						$GRPCOD             = $PRDrow["GRPCOD"];
						$BRDCOD             = $PRDrow["BRDCOD"];
						$COLORCOD           = $PRDrow["COLORCOD"];
						$SIZECOD            = $PRDrow["SIZECOD"];
						$PRDCOD             = $PRDrow["PRDCOD"];
						$PRDNAME            = $PRDrow["PRDNAME"];
						$PRDDESCRIPTION     = $PRDrow["PRDDESCRIPTION"];
						$PRDAPRESENTATION   = $PRDrow["PRDAPRESENTATION"];
						$PRDESPECIFICATION  = $PRDrow["PRDESPECIFICATION"];
						$PRDPRICEBUY        = $PRDrow["PRDPRICEBUY"];
						$PRDIDEALMARGIM     = $PRDrow["PRDIDEALMARGIM"];
						$PRDPRICEOFICIAL    = $PRDrow["PRDPRICEOFICIAL"];
						$PRDINSALE          = $PRDrow["PRDINSALE"];
						$PRDPRICESALE       = $PRDrow["PRDPRICESALE"];
						$PRDCOUNTPICTURES   = $PRDrow["PRDCOUNTPICTURES"];
						$PRDSTATUS          = $PRDrow["PRDSTATUS"];
						$PRDDTSTATUS        = $PRDrow["PRDDTSTATUS"];
						$PRDUSRSTATUS       = $PRDrow["PRDUSRSTATUS"];
						$PRDCADASTRO        = $PRDrow["PRDCADASTRO"];
						$PRDCADBY           = $PRDrow["PRDCADBY"];						
						
						$PRECOCUSTO   = number_format($PRDPRICEBUY,2,",",".");
			      $PRECOOFICIAL = number_format($PRDPRICEOFICIAL,2,",",".");
						
						$PRECOSALE = NULL;
						
						if($PRDINSALE == 1){
							
							$PRECOSALE = number_format($PRDPRICESALE,2,",",".");
							
						}
						
						switch($PRDSTATUS){
							case 0: $situacao = "<img src='../images/status/VERMELHO.png' title='INATIVO'/>"; break;
							case 1: $situacao = "<img src='../images/status/AZUL.png'     title='NOVO - NÃO PUBLICADO'/>"; break;
							case 2: $situacao = "<img src='../images/status/VERDE.png'    title='PUBLICADO / ATIVO'/>"; break;
						}
						
						if($PRDCOUNTPICTURES == 0){
							$PRDCOUNTPICTURES = "<img src='../images/ProdutoSemFoto.png' width='32' height='32'/>";
						}
						
						echo "
						<tr>
							<td><div align='center'>$situacao</div></td>
							<td><div align='center'><b>$PRDCOD</b></div></td>
							<td><div title='$PRDDESCRIPTION'>$PRDNAME</div></td>
							<td><div align='center'>$PRECOCUSTO</div></td>
							<td><div align='center'>$PRDIDEALMARGIM</div></td>
							<td><div align='center'>$PRECOOFICIAL</div></td>
							<td><div align='center'>$PRECOSALE</div></td>
							<td><div align='center'>$PRDCOUNTPICTURES</div></td>
							<td>
								<div id='cssmenu' align='center'>
									<ul>
										<li class='has-sub'><a href='#'><span></span></a>
											<ul>
												<li>
													<form name='editar' method='POST' action='form_cad_products.php'>
														<input name='prdcod' type='hidden' value='$PRDCOD'/>
														<input name='acao'   type='hidden' value='EDITAR'/>
														<button class='btn btn-default btn-xs' title='EDITAR'><i class='fa fa-pencil'></i></button>
													</form>
												</li>
												<li>
													<form name='pictures' method='POST' action='product_pictures.php'>
														<input name='prdcod' type='hidden' value='$PRDCOD'/>
														<button class='btn btn-default btn-xs' title='PICTURES'><i class='fa fa-picture-o' aria-hidden='true'></i></button>
													</form>
												</li>
												<li>
													<form name='grade' method='POST' action='product_colors.php'>
														<input name='prdcod' type='hidden' value='$PRDCOD'/>
														<button class='btn btn-default btn-xs' title='Cores'><i class='fa fa-paint-brush' aria-hidden='true'></i></button>
													</form>
												</li>													
												<li>
													<form name='grade' method='POST' action='product_sizes.php'>
														<input name='prdcod' type='hidden' value='$PRDCOD'/>
														<button class='btn btn-default btn-xs' title='Tamanhos'><i class='fa fa-bar-chart' aria-hidden='true'></i></button>
													</form>
												</li>";
								
												if($PRDSTATUS == 1){
													
													echo"
													<li>
														<form name='publicar' method='GET' action='form_cad_products.php' onsubmit='return confirma_publicar();'>
															<input name='prdcod' type='hidden' value='$PRDCOD'/>
															<input name='action' type='hidden' value='PUBLICAR'/>
															<button class='btn btn-default btn-xs' title='PUBLICAR'><i class='fa fa-share-square-o' aria-hidden='true'></i></button>
														</form>
													</li>
													<li>
														<form name='inativar' method='POST' action='form_cad_products.php' onsubmit='return confirma_inativar();'>
															<input name='prdcod' type='hidden' value='$PRDCOD'/>
															<input name='acao'   type='hidden' value='INATIVAR'/>
															<button class='btn btn-default btn-xs' title='INATIVAR'><i class='fa fa-ban' aria-hidden='true'></i></button>
														</form>
													</li>";
												
												}
												
												if($PRDSTATUS == 2){
													
													echo"
													<li>
														<form name='inativar' method='POST' action='form_cad_products.php' onsubmit='return confirma_inativar();'>
															<input name='prdcod' type='hidden' value='$PRDCOD'/>
															<input name='acao'   type='hidden' value='INATIVAR'/>
															<button class='btn btn-default btn-xs' title='INATIVAR'><i class='fa fa-ban' aria-hidden='true'></i></button>
														</form>
													</li>";
													
												}
												
												if($PRDSTATUS == 0){
													
													echo"
													<li>
														<form name='ativar' method='POST' action='form_cad_products.php' onsubmit='return confirma_ativar();'>
															<input name='deptcod'   type='hidden' value='$DEPTCOD'/>
															<input name='acao'      type='hidden' value='ATIVAR'/>
															<input class='inputsrc' type='image'  src='../imagens/ativar.png' title='REATIVAR'/>
														</form>
													</li>";
													
												}
												
												echo"
											</ul>
										</li>
									</ul>
								</div>
							</td>
						</tr>";
						
					}
					
					echo "<tbody>
					</table>";
				
				}else{
					
					echo "<div align='center' class='info'>Nenhum Product cadastrado!</div>
					<div align='center'>
						<form name='novo' method='POST' action='form_cad_products.php'>
							<input name='acao' type='hidden' value='ADD'/>
							<button type='submit' class='btn btn-info btn-sm'><i class='fa fa-plus'></i>&nbsp;&nbsp;Incluir</button>
						</form>
					</div>";
					
				} 
				
				?>
				
			</div>
		</div>
	</div>
</body>
</html>