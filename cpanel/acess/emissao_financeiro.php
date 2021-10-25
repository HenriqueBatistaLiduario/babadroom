<?php include 'head.php';?>

	<script language="javascript">
		
		<!--Bloqueio da tecla F5-->

		function confirma(){
			if(confirm('ATENÇÃO: CONFIRMA A EMISSÃO DO TÍTULO FINANCEIRO? Clique em OK para continuar...'))
			return true;
			else
			return false;
		}
		
		$(document).ready(function(){
			
			$("#finvalororiginal").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});
			$("#fintxjuros").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});
			$("#fintxmulta").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});
			
		});
		
		$(document).ready(function(){
			if(document.getElementById("MetodoPag").value=='BOLETO'){
				 document.getElementById("ValueBoleto").style.display=block;
			}
		}
	
	</script> 

	<body>
		<div class='container-fluid'>
	
			<?php
			
			$FINTIPO = $_POST['fintipo'];
			
			if($FINTIPO == 'PAGAR'){
				
				$voltar = 'form_consulta_fincp.php';
				$FINCAMPOBD = 'FINIDFORNECEDOR';
				$FINPAGREC  = 'PAG';
				$FINSIGLA   = 'LFP';
				
			}
			
			if($FINTIPO == 'RECEBER'){
				
				$voltar = 'form_consulta_fincr.php';
				$FINCAMPOBD = 'FINIDCLIENTE';
				$FINPAGREC  = 'REC';
				$FINSIGLA   = 'LFR';
				
			}
			
			echo "
			
			<div class='panel panel-default'>
				
				<div class='panel-heading'>
					<span class='subpageName'>Lançamento de Título Financeiro a <b>$FINTIPO</b></span>

						<ul class='pull-right list-inline'>
							<li>
								<form name='voltar' method='POST' action='$voltar'>
									<input class='inputsrc' type='image' src='../images/close.png' title='Fechar'/>
								</form>
							</li>
						</ul>
				</div>

				<div class='panel-body'>";
			
				if(isset($_POST['acao'])){
					$acao = $_POST['acao'];
					
					if ($acao == 'LANCAR'){					
						
						$FINIDTITULAR     = $_POST['finidtitular'];
						$FNATCOD          = $_POST['fnatcod'];						
						$FINPARCELA       = $_POST['finparcela'];
						$FINMENSALIDADE   = $_POST['finmensalidade'];
						$FINDTVENCIMENTO  = $_POST['findtvencimento'];		
            $FINVALORORIGINAL = $_POST['finvalororiginal'];
            $FINTXJUROS       = $_POST['fintxjuros'];
            $FINTXMULTA       = $_POST['fintxmulta'];
						$FINMETODOPAG     = $_POST['finmetodopag'];
						$FINLINHADIGIT    = $_POST['finlinhadigit'];
            $FINHISTORICO     = $_POST['finhistorico'];						
						
						$FINinsert = mysqli_query($con,"INSERT INTO financeiro(IDDOMINIO,IDADMINISTRADOR,FINIDADMINISTRADORA,$FINCAMPOBD,FINPAGREC,FINPARCELA,FINVALORORIGINAL,FINVALOR,FINTXJUROS,FINTXMULTA,FINDTVENCIMENTO,FINMETODOPAG,FINLINHADIGIT,FINMENSALIDADE,FINHISTORICO,FINCADBY) VALUES 
						('$dominio_session','$adm_session','$adm_session','$FINIDTITULAR','$FINPAGREC',$FINPARCELA,'$FINVALORORIGINAL','$FINVALORORIGINAL','$FINTXJUROS','$FINTXMULTA','$FINDTVENCIMENTO','$FINMETODOPAG','$FINLINHADIGIT',$FINMENSALIDADE,'$FINHISTORICO','$ident_session')") or print "<div class='alert alert-danger'>ERRO DE BANCO DE DADOS: <b>".(mysqli_error($con))."</div>";
						
						if($FINinsert){
							
							$MAXFINID = mysqli_query($con,"SELECT MAX(FINID) AS ULTIMO FROM financeiro WHERE FINPAGREC = '$FINPAGREC'") or print(mysqli_error($con));
							
							while($FINMAXIDrow = mysqli_fetch_array($MAXFINID)){
								$finid = $FINMAXIDrow["ULTIMO"];
							}
							
									 if(($finid>0)&& ($finid<10))  {$finnumero = '000'.$finid.'';}
							else if(($finid>9)&& ($finid<100)) {$finnumero = '00'.$finid.''; }
							else if(($finid>99)&&($finid<1000)){$finnumero = '0'.$finid.'';  }
							else{$finnumero=$finid;}
							
							$ano = gmdate("Y",$fuso);
							$mes = gmdate("m",$fuso);
							
							$FINCODTITULO = "$FINSIGLA$ano$mes$finnumero";
							
							$UPDATEcod = mysqli_query($con,"UPDATE financeiro SET FINCODTITULO = '$FINCODTITULO' WHERE FINID = $finid") or print(mysqli_error($con));
							
							if($UPDATEcod){
								
								echo "<script>location.href='$voltar?status=success&id=$FINCODTITULO';</script>";						
								
							}
						
						}
					}
				} //Fim de ações...
				
				?>
				
				<form class='form-horizontal' name='lf' method='POST'	action='emissao_financeiro.php'>
				
				  <?php
				
					if($FINTIPO == 'PAGAR'){
						
						echo "
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'>Fornecedor:</label>
							<div class='col-sm-4 col-md-4'>
								<select class='form-control' name='finidtitular' required>";
								
								$PVDselect = mysqli_query($con,"SELECT * FROM providers WHERE PVDSTATUS=1 ORDER BY PVDRAZAONOME");
								$PVDtotal  = mysqli_num_rows($PVDselect);
								
								if($PVDtotal > 0){
									echo "<option value=''>Selecione...</option>";
									
									while($PVDrow = mysqli_fetch_array($PVDselect)){
										
										$PVDIDENTIFICADOR = $PVDrow["PVDIDENTIFICADOR"];
										$PVDRAZAONOME = $PVDrow["PVDRAZAONOME"];
										
										echo "<option value='$PVDIDENTIFICADOR'>$PVDRAZAONOME ($PVDIDENTIFICADOR)</option>";
										
									}
								
								}else{
									echo "<option value=''>Nenhum Fornecedor ativo.</option>";
								}
								
								echo "
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label'>Natureza:</label>
							<div class='col-sm-4 col-md-4'>
								<select class='form-control' name='fnatcod' required>";
								
								$FNATDselect = mysqli_query($con,"SELECT * FROM financial_natures WHERE FNATSTATUS=1 AND FNATAPLICAVEL = 'DESPESAS' ORDER BY FNATCOD");
								$FNATDtotal  = mysqli_num_rows($FNATDselect);
								
								if($FNATDtotal > 0){
									echo "<option value=''>Selecione...</option>";
									
									while($FNATDrow = mysqli_fetch_array($FNATDselect)){							
									
										$FNATDCOD   = $FNATDrow["FNATCOD"];
										$FNATDNAME  = $FNATDrow["FNATNAME"];
										$FNATDDESC  = $FNATDrow["FNATDESC"];
										
										echo "<option value='$FNATDCOD' title='$FNATDDESC'>$FNATDCOD |  $FNATDNAME</option>";
									
									}
								
								}else{
									echo "<option value=''>Nenhuma Natureza de DESPESA ativa.</option>";
								}
								
								echo "
								</select>
							</div>
						</div>";								
					}
					
					if($FINTIPO == 'RECEBER'){
						
						echo "
						<div class='form-group'>
							<label class='col-sm-2 col-md-2 control-label'>Cliente:</label>
							<div class='col-sm-4 col-md-4'>
								<select class='form-control' name='finidtitular' required>";
								
								$CTMselect = mysqli_query($con,"SELECT * FROM customers WHERE CTMSTATUS=1 ORDER BY CTMNOME");
								$CTMtotal  = mysqli_num_rows($CTMselect);
								
								if($CTMtotal > 0){
									echo "<option value=''>Selecione...</option>";
									
									while($CTMrow = mysqli_fetch_array($CTMselect)){
										
										$CTMIDENTIFICADOR = $CTMrow["CTMIDENTIFICADOR"];
										$CTMNOME = $CTMrow["CTMNOME"];
										
										echo "<option value='$CTMIDENTIFICADOR'>$CTMNOME ($CTMIDENTIFICADOR)</option>";
										
									}
								
								}else{
									echo "<option value=''>Nenhum Cliente ativo.</option>";
								}
								
								echo "
								</select>
							</div>
							
							<label class='col-sm-2 col-md-2 control-label'>Natureza:</label>
							<div class='col-sm-4 col-md-4'>
								<select class='form-control' name='fnatcod' required>";
								
								$FNATRselect = mysqli_query($con,"SELECT * FROM financial_natures WHERE FNATSTATUS=1 AND FNATAPLICAVEL = 'RECEITAS' ORDER BY FNATCOD");
								$FNATRtotal  = mysqli_num_rows($FNATRselect);
								
								if($FNATRtotal > 0){
									echo "<option value=''>Selecione...</option>";
								 
									while($FNATRrow = mysqli_fetch_array($FNATRselect)){							
									
										$FNATRCOD   = $FNATRrow["FNATCOD"];
										$FNATRNAME  = $FNATRrow["FNATNAME"];
										$FNATRDESC  = $FNATRrow["FNATRESC"];
										
										echo "<option value='$FNATRCOD' title='$FNATRDESC'>$FNATRCOD |  $FNATRNAME</option>";
									
									}
								
								}else{
									
									echo "<option value=''>Nenhuma Natureza de RECEITA ativa.</option>";
									
								}
								
								echo "
								</select>
							</div>
						</div>";																
						
					}		
          
          ?>	

          <div class='form-group'>
					
						<label class='col-sm-2 col-md-2 control-label'>Parcela:</label>
						<div class='col-sm-1 col-md-1'>
							<input class='form-control' name='finparcela' type='number' min=1 max=99 value='1' required />						
						</div>
						
						<label class='col-sm-1 col-md-1 control-label'>de:</label>
						<div class='col-sm-1 col-md-1'>
							<input class='form-control' name='finmensalidade' type='number' min=1 max=99 value='1' required />						
						</div>
						
						<label class='col-sm-5 col-md-5 control-label'>Data Vencimento:</label>
						<div class='col-sm-2 col-md-2'>
							<input class='form-control' name='findtvencimento' type='date' required />
						</div>

          </div>				

          <div class='form-group'>
					
						<label class='col-sm-2 col-md-2 control-label'>Valor Original:</label>
						<div class='col-sm-4 col-md-4'>
							<input class='form-control' id='finvalororiginal' name='finvalororiginal' type='text' required />						
						</div>
						
						<label class='col-sm-1 col-md-1 control-label'>Tx.Juros:</label>
						<div class='col-sm-2 col-md-2'>
							<input class='form-control' id='fintxjuros' name='fintxjuros' type='text' value='0.00' required />
						</div>
						<label class='col-sm-1 col-md-1 control-label'>Tx.Multa:</label>
						<div class='col-sm-2 col-md-2'>
							<input class='form-control' id='fintxmulta' name='findtvencimento' type='text' value='0.00' required />
						</div>

          </div>		
					
					<div class='form-group'>
					
						<label class='col-sm-2 col-md-2 control-label'>Meio de Pagamento:</label>
						<div class='col-sm-4 col-md-4'>
							<select class='form-control' id='MetodoPag' name='finmetodopag' required>
							  <option value=''>Selecione...</option>
								<option value='BOLETO'>BOLETO</option>
								<option value='TED'>TED</option>
								<option value='DOC'>DOC</option>
								<option value='CCPRESENCIAL'>CARTÃO DE CRÉDITO - PRESENCIAL</option>
								<option value='CCONLINE'>CARTÃO DE CRÉDITO - ONLINE</option>
								<option value='CDPRESENCIAL'>CARTÃO DE DÉBITO - PRESENCIAL</option>
								<option value='CDONLINE'>CARTÃO DE DÉBITO - ONLINE</option>
							</select>
						</div>			

            <div id='ValueBoleto' class='col-sm-6 col-md-6' style='display;none;'>
							<input class='form-control' id='finlinhadigit' name='finlinhadigit' maxlength='100' placeholder='Linha Digitável do Boleto Bancário...'/>
						</div>								

          </div>	          				

          <div class='form-group'>					
            <label class='col-sm-2 col-md-2 control-label'>Histórico:</label>
						<div class='col-sm-10 col-md-10'>
							<textarea class='form-control' rows='5' name='finhistorico' placeholder='Informe a que se refere esse lançamento. Informações concatenam-se enquanto o título não estiver baixado...' required ></textarea>
						</div>						
          </div>	
          
          <div align='right'>
					
						<input type='hidden' name='fintipo' value='<?php echo $FINTIPO; ?>' />
						<input type='hidden' name='acao'    value='LANCAR' />
						<button type='submit' class='btn btn-info btn-sm'>Confirmar Lançamento</button>
						
					</div>         					
				
				</form>			
				
				</div>
			</div>
		</div>
	</body>
</html>