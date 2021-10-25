<?php include 'head.php';?>

	<script language="javascript">

		function consiste_baixa(){
			var valido = false;
			
			if (document.form_baixa.finvalorbaixa.value <= document.form_baixa.finvalor.value){
				valido = true;
			
			}else{
				
				valido = false;
				document.form_baixa.finvalorbaixa.focus();
				alert ('Valor da Baixa maior que Valor do Lançamento!');
			
			}
			
			return valido;
		}

		function confirma_baixa(){
			if(confirm('ATENÇÃO! CONFIRMA BAIXA NESTE LANÇAMENTO?'))
				return true;
			else
				return false;
		}
		
		$(document).ready(function(){
			
			$("#finvalorjuros").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});
			$("#finvalormulta").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});
			$("#finvalorad1").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});
			$("#finvalorbaixa").maskMoney({showSymbol:false, decimal:".", thousands:""});
			
		});
		
	</script>

	<body>
		<div class='container-fluid'>			
			
			<div class='panel panel-default'>

				<div class='panel-heading'>					
					<span class='subpageName'>Lançamentos Financeiros A PAGAR (Despesas)</span>
					<ul class='pull-right list-inline'>
						<li><a href='home.php' title='Fechar'><i class='fa fa-arrows-v' aria-hidden='true'></i></a></li>
					</ul>					
				</div>

				<div class='panel-body'>

		<?php
		
		  if(isset($_GET['status']) AND $_GET['status'] == 'success'){
				
				$FINCODTITULO = $_GET['id'];
				
				echo "<div id='alert' align='center' class='success'>TÍTULO FINANCEIRO lançado com sucesso! Identificador: <b>$FINCODTITULO</b></div>";	
				
			}
			
			if(isset($_POST['acao'])){
				
				$acao = $_POST['acao'];

				if($acao == 'BAIXAR'){
					
					$FINCODTITULO = $_POST['fincodtitulo'];
					
					$FINBAIXAselect = mysqli_query($con,"SELECT * FROM financeiro WHERE FINCODTITULO = '$FINCODTITULO'");
					
					while($FINBAIXArow = mysqli_fetch_array($FINBAIXAselect)){
						
						$FINVALORORIGINAL = $FINBAIXArow['FINVALORORIGINAL'];
						$FINMETODOPAG     = $FINBAIXArow['FINMETODOPAG'];
						$FINIDFORNECEDOR  = $FINBAIXArow['FINIDFORNECEDOR'];
						$FINLINHADIGIT    = $FINBAIXArow['FINLINHADIGIT'];
						
					}
					
					?>
					
					<div class='panel panel-default'>
						<div class='panel-heading'>
							<span class='subpageName'><i class='fa fa-level-down' aria-hidden='true'></i>&nbsp;&nbsp;Baixar Lançamento <b><?php echo $FINCODTITULOB;?></b></span>

							<ul class='pull-right list-inline'>
								<li>
									<ul class='pull-right list-inline'>
										<li><a href='#' title='Ocultar' onclick="recolher_painel('painel_baixa')"><i class='fa fa-arrows-v' aria-hidden='true'></i></a></li>
									</ul>
								</li>
							</ul>
						</div>
						
						<div class='panel-body' id='painel_dc'>
							
							<form class='form-horizontal' name="form_baixa" method='POST' action="form_consulta_financeiro.php" onsubmit = 'return confirma_baixa();'>
							  <div class='row'>
									<div class='col-sm-6 col-md-6'>
								
										<div class='form-group'>
											<label class='col-sm-4 col-md-4 control-label'>Data de Baixa:</label>
											<div class='col-sm-8 col-md-8'><input class='form-control' name='findtbaixa' type='date' id='findtbaixa' value='<?php echo $hoje;?>' readonly /></div>									
										</div>
										
										<div class='form-group'>					
											<label class='col-sm-4 col-md-4 control-label'>Meio de Pagamento:</label>
											<div class='col-sm-8 col-md-8'>
												<select class='form-control' name='finmetodopag' required>
													<option value='<?php echo $FINMETODOPAG;?>'><?php echo $FINMETODOPAG;?></option>
													<option value='BOLETO'>BOLETO</option>
													<option value='TED'>TED</option>
													<option value='DOC'>DOC</option>
													<option value='CCPRESENCIAL'>CARTÃO DE CRÉDITO - PRESENCIAL</option>
													<option value='CCONLINE'>CARTÃO DE CRÉDITO - ONLINE</option>
													<option value='CDPRESENCIAL'>CARTÃO DE DÉBITO - PRESENCIAL</option>
													<option value='CDONLINE'>CARTÃO DE DÉBITO - ONLINE</option>
												</select>
											</div>				
										</div>	
										
										<?php 
										
										$ADMselect  = mysqli_query($con,"SELECT ADMCPFCNPJ FROM administradores WHERE ADMIDENTIFICADOR = '$adm_session' ORDER BY ADMID DESC LIMIT 1") or print (mysqli_error());
										
										while($ADMrow = mysqli_fetch_array($ADMselect)){
											$ADMCPFCNPJ = $ADMrow["ADMCPFCNPJ"];
										}
										
										$CBselectC = mysqli_query($con,"SELECT * FROM contas_bancarias WHERE CBIDRECURSO = '$FINIDFORNECEDOR' AND CBTIPO = 1 AND CBSTATUS = 1") or print(mysqli_error());
										$CBselectD = mysqli_query($con,"SELECT * FROM contas_bancarias WHERE CBIDRECURSO = '$ADMCPFCNPJ' AND CBTIPO IN (2,3) AND CBSTATUS = 1") or print(mysqli_error());
										$CBtotalC  = mysqli_num_rows($CBselectC);
										$CBtotalD  = mysqli_num_rows($CBselectD);
										
										echo"
										<div class='form-group'>
											<label class='col-sm-4 col-md-4 control-label'>Conta Crédito:</label>
											<div class='col-sm-8 col-md-8'>
												<select class='form-control' name='finidcbcredito' title='Selecione a Conta Bancária do Fornecedor na qual ocorreu o CRÉDITO (No caso de DOC ou TED)...'>";
												
												if($CBtotalC > 0){
													
													echo "<option value=''>Selecione...</option>";
													
													while($CBrow = mysqli_fetch_array($CBselectC)){
														
														$CBID          = $CBrow['CBID'];
														$CBCODBANCO    = $CBrow['CBCODBANCO'];
														$CBAGENCIA     = $CBrow['CBAGENCIA'];
														$CBNUMCONTA    = $CBrow['CBNUMCONTA'];
														$CBNOMETITULAR = $CBrow['CBNOMETITULAR'];

														echo "<option value='$CBID'>Nome Titular: $CBNOMETITULAR | Banco $CBCODBANCO | Agência $CBAGENCIA | Conta corrente $CBNUMCONTA</option>";
														
													}
													
												}else{
													
													echo "<option value=''>Nenhuma Conta CRÉDITO ativa para este Fornecedor.</option>";
													
												}
											
											echo "</select>
											</div>
										</div>										
										<div class='form-group'>
											<label class='col-sm-4 col-md-4 control-label'>Conta Débito:</label>
											<div class='col-sm-8 col-md-8'>
												<select class='form-control' name='finidcbdebito' title='Selecione a Conta Bancária do Administrador na qual ocorreu o DÉBITO (Exceto para Cartão de Crédito)...'>";
												
												if($CBtotalD > 0){
													echo "<option value=''>Selecione...</option>";
													
													while($CBDrow = mysqli_fetch_array($CBselectD)){
														
														$CBID          = $CBDrow['CBID'];
														$CBCODBANCO    = $CBDrow['CBCODBANCO'];
														$CBAGENCIA     = $CBDrow['CBAGENCIA'];
														$CBNUMCONTA    = $CBDrow['CBNUMCONTA'];
														$CBNOMETITULAR = $CBDrow['CBNOMETITULAR'];
					
														echo "<option value='$CBID'>Nome Titular: $CBNOMETITULAR | Banco $CBCODBANCO | Agência $CBAGENCIA | Conta $CBNUMCONTA</option>";
														
													}
												
												}else{
													
													echo "<option value=''>Nenhuma Conta DÉBITO ativa para o Administrador.</option>";
													
												}
												
											echo "</select>
											</div>
										</div>";
										
										?>														

										<div class='form-group'>								
											<label class='col-sm-4 col-md-4 control-label'>Linha Digitável:</label>
											<div class='col-sm-8 col-md-8'>
												<input class='form-control' type='text' name='finlinhadigit' placeholder='Linha Digitável do Boleto Bancário (apenas Números)' value='<?php echo $FINLINHADIGIT;?>' />
											</div>									
										</div>				

										<div class='form-group'>
											<label class='col-sm-4 col-md-4 control-label'>Autenticação Bancária:</label>
											<div class='col-sm-8 col-md-8'><input class='form-control' name='finautenticpag' maxlength='100' placeholder='Autenticação Bancária no comprovante de pagamento/transferência...' /></div>
										</div>								
										
										<div class='form-group'>
											<label class='col-sm-2 control-label'>Histórico:</label>
											<div class='col-sm-6'><textarea class='form-control' name='finhistorico' id='finhistorico' maxlength='300' placeholder='Informações relevantes sobre esta operação de baixa...'/></textarea></div>
										</div>
										
									</div>
									
									<div class='col-sm-6 col-md-6'>
									
									  <div class='form-group'>							
											<label class='col-sm-5 col-md-5 control-label'>Valor Original:</label>
											<div class='col-sm-7 col-md-7'>
												<input class='form-control' id='finvalororiginal' name='finvalororiginal' type='text' value='<?php echo $FINVALORORIGINAL;?>' readonly />						
											</div>
										</div>
									
										<div class='form-group'>							
											<label class='col-sm-5 col-md-5 control-label norequired'>Valor Juros:</label>
											<div class='col-sm-7 col-md-7'>
												<input class='form-control' id='finvalorjuros' name='finvalorjuros' type='text' required value='0.00'/>						
											</div>
										</div>
										
										<div class='form-group'>	
											<label class='col-sm-5 col-md-5 control-label norequired'>Valor Multa:</label>
											<div class='col-sm-7 col-md-7'>
												<input class='form-control' id='fintxjuros' name='finvalormulta' type='text' value='0.00' required />
											</div>
										</div>
										
										<div class='form-group'>											
											<label class='col-sm-5 col-md-5 control-label norequired'>Despesa Extra:</label>
											<div class='col-sm-7 col-md-7'>
												<input class='form-control' id='finvalorad1' name='finvalorad1' type='text' value='0.00' required />
											</div>
										</div>
										
										<div class='form-group'>											
											<label class='col-sm-5 col-md-5 control-label'>Valor da Baixa:</label>
											<div class='col-sm-7 col-md-7'><input class='form-control' name='finvalorbaixa' id='finvalorbaixa' type='text' maxlength='10' value='<?php echo $FINVALORORIGINAL;?>' title=' Valor pode ser Total ou Parcial. Se Parcial, o Lançamento ficará com o Status BAIXADO PARCIALMENTE.' required /></div>
										</div>
										
										<input name='fincodtitulo' type='hidden' value='<?php echo $FINCODTITULOB;?>'/>
									  <input name='acao'         type='hidden' value='CONFIRMAR_BAIXA'/>
									  <div align='right'><input class='but but-azul' type='submit'   value='    Confirmar    ' onclick='return consiste_baixa();'/></div>
										
                  </div>	
                </div>								
								
							</form>
						</div>
					</div>
					
					<?php
					
				}
				
				if($acao == 'CONFIRMAR_BAIXA'){
					
					 $FINCODTITULO   = $_POST['fincodtitulo'];
					 $FINVALORBAIXA  = $_POST['finvalorbaixa'];
					 $FINDTBAIXA     = $_POST['findtbaixa'];
					 $FINIDCBCREDITO = $_POST['finidcbcredito'];
					 $FINIDCBDEBITO  = $_POST['finidcbdebito'];
					 $FINAUTENTICPAG = $_POST['finautenticpag'];
					 $FINHISTORICO   = $_POST['finhistorico'];
					 $FINLINHADIGIT  = $_POST['finlinhadigit'];
					 $FINMETODOPAG   = $_POST['finmetodopag'];
					 $FINVALORJUROS  = $_POST['finvalorjuros'];
					 $FINVALORMULTA  = $_POST['finvalormulta'];
					 $FINVALORAD1    = $_POST['finvalorad1'];
					 
					 $FINupdate = mysqli_query($con,"UPDATE financeiro SET FINVALORBAIXADO = (FINVALORBAIXADO + $FINVALORBAIXA), 
																													              FINVALOR = (FINVALORORIGINAL - FINVALORBAIXADO),
																																	 FINVALORJUROS = (FINVALORJUROS + $FINVALORJUROS),
																																	 FINVALORMULTA = (FINVALORMULTA + $FINVALORMULTA),
																																	   FINVALORAD1 = (FINVALORAD1 + $FINVALORAD1),
																																		FINMETODOPAG = '$FINMETODOPAG',
																																	 FINLINHADIGIT = '$FINLINHADIGIT',
																													            FINDTBAIXA = '$FINDTBAIXA',
																													           FINUSRBAIXA = '$ident_session',
																													        FINIDCBCREDITO = $FINIDCBCREDITO,
																													         FINIDCBDEBITO = $FINIDCBDEBITO,
																																	FINAUTENTICPAG = '$FINAUTENTICPAG' WHERE FINCODTITULO = '$FINCODTITULO'") or print (mysqli_error());
					if($FINupdate){
						
						 $FINselectStatus = mysqli_query($con,"SELECT FINVALORORIGINAL,FINVALOR FROM financeiro WHERE FINCODTITULO = '$FINCODTITULO'") or print (mysqli_error());
						 
						 while($FVrow = mysqli_fetch_array($FINselectStatus)){
							 
							$FINVALORORIGINAL = $FVrow["FINVALORORIGINAL"];
							$FINVALOR = $FVrow["FINVALOR"];
							
						 }
						 
						if (($FINVALOR < $FINVALORORIGINAL) AND $FINVALOR > 0){
							$FINSTATUS = 1;
					 
						}else if ($FINVALOR == 0){
							$FINSTATUS = 5;
						}
					
						$FINSTATUSupdate = mysqli_query($con,"UPDATE financeiro SET FINSTATUS = $FINSTATUS WHERE FINCODTITULO = '$FINCODTITULO'") or print (mysqli_error());
						
						if($FINSTATUSupdate){
							
							if($FINHISTORICO != ""){
								$FINHISTORICOupdate = mysqli_query($con,"UPDATE financeiro SET FINHISTORICO = concat(FINHISTORICO,'<br><br>$ident_session em $agora:<br>',$FINHISTORICO) WHERE FINCODTITULO = '$FINCODTITULO'") or print (mysqli_error());
							}
							
							$finvalorbaixa = number_format($FINVALORBAIXA,2,",",".");//Formatar o Valor.
							$findtbaixa = date("d/m/Y",strtotime($FINDTBAIXA));
							
							echo "<div id='alert' align='center' class='success'>Baixa no Lançamento <b>$FINCODTITULO</b>, no valor de <b>$finvalorbaixa</b> na Data <b>$findtbaixa</b> realizada com sucesso.</div>";
								
						}
						
					}
					
				}					
				
			}//Fim das ações...			
		
		  $FINselectP = mysqli_query($con,"SELECT * FROM financeiro WHERE FINPAGREC = 'PAG' ORDER BY FINDTVENCIMENTO") or print (mysqli_error());		
			$totalP = mysqli_num_rows($FINselectP);		
						
				if ($totalP > 0){
					echo "
					<table id='DataTables' width='100%' class='display table-responsive table-condensed table-action table-striped'>
						<thead>							
							<th width='2%' ><div align='center'>      </div>                </th>
							<th width='10%'><div align='center'>ID</div>                    </th>
							<th width='15%'><div>Fornecedor</div>                           </th>
							<th width='10%'><div align='center'>Parcela</div>               </th>
							<th width='10%'><div align='center'>Valor Original</div>        </th>
							<th width='10%'><div align='center'>Valor Baixado</div>         </th>
							<th width='10%'><div align='center' title='Soma dos valores de Juros, Multa e/ou demais encargos da dívida'>Encargos</div></th>
							<th width='10%'><div align='center'>Valor atualizado </div>     </th>
							<th width='10%'><div align='center'>Vencimento    </div>        </th>
							<th width='10%'><div align='center'>Última Baixa</div>          </th>
							<th width='3%' data-orderable='false'>
								<div align='center'>
									<form name='financeiro' method='POST' action='emissao_financeiro.php'>
										<input name='fintipo' type='hidden' value='PAGAR'/>
										<button type='submit' class='btn btn-info btn-xs' title='Lançar Título'><i class='fa fa-plus'></i></button>
									</form>
							</div>							
							</th>							
						</thead>
						<tbody>";
						
						while ($FINrow = mysqli_fetch_array($FINselectP)){
							$FINID            = $FINrow["FINID"];
							$FINPAGREC        = $FINrow["FINPAGREC"];
							$FINIDFORNECEDOR  = $FINrow["FINIDFORNECEDOR"];
							$FINCODTITULO     = $FINrow["FINCODTITULO"];
							$FINPARCELA       = $FINrow["FINPARCELA"];
							$FINVALORORIGINAL = $FINrow["FINVALORORIGINAL"];
							$FINVALORJUROS    = $FINrow["FINVALORJUROS"];
							$FINVALORMULTA    = $FINrow["FINVALORMULTA"];
							$FINVALORAD1      = $FINrow["FINVALORAD1"];
							$FINVALOR         = $FINrow["FINVALOR"];
							$FINVALORBAIXADO  = $FINrow["FINVALORBAIXADO"];
							$FINDTVENCIMENTO  = $FINrow["FINDTVENCIMENTO"];
							$FINSTATUS        = $FINrow["FINSTATUS"];
							$FINDTBAIXA       = $FINrow["FINDTBAIXA"];
							
							$FINVALORADD = $FINVALORJUROS+$FINVALORMULTA+$FINVALORAD1;
							
							$valororiginal = number_format($FINVALORORIGINAL,2,",",".");//Formatar o Valor.
							$valorjm       = number_format($FINVALORADD,2,",",".");//Formatar o Valor.
							$valorbaixado  = number_format($FINVALORBAIXADO,2,",",".");//Formatar o Valor.
							$valor         = number_format($FINVALOR,2,",",".");//Formatar o Valor.
									
							$verdtvenc = date("Y-m-d", strtotime($FINDTVENCIMENTO));
							$dtvenc = date("d/m/Y", strtotime($FINDTVENCIMENTO));
							
							if($verdtvenc < $hoje AND $FINSTATUS == 1){
								$dtvenc = "<font color='red'><b>$dtvenc</b></font>";
							}
							
							if($FINDTBAIXA != NULL){
								$dtbaixa  = date("d/m/Y", strtotime($FINDTBAIXA));
							
							}else{
								$dtbaixa = '';
							}
							
							$hoje = date('Y-m-d');
							
							switch($FINSTATUS){
								case 0: $finsituacao = "<img src='../images/status/AZUL.png'    title='EM ABERTO'/>"; break;
								case 1: $finsituacao = "<img src='../images/status/AMARELO.png' title='BAIXADO PARCIALMENTE'/>"; break;
								case 2: $finsituacao = "<img src='../images/status/ROXO.png'    title='CANCELADO'/>"; break;
								case 3: $finsituacao = "<img src='../images/status/PRETO.png'   title='BAIXADO por ACORDO'/>"; break;
								case 4: $finsituacao = "<img src='../images/status/PRETO.png'   title='BAIXADO por FATURA'/>"; break;
								case 5: $finsituacao = "<img src='../images/status/PRETO.png'   title='TOTALMENTE BAIXADO'/>";
							}
							
							$PVDselect = mysqli_query($con,"SELECT PVDRAZAONOME,PVDNOMEFANT FROM providers WHERE PVDIDENTIFICADOR = '$FINIDFORNECEDOR'") or print(mysqli_error());
							
							while($PVDrow = mysqli_fetch_array($PVDselect)){
								
								$PVDRAZAONOME = $PVDrow["PVDRAZAONOME"];
								$PVDNOMEFANT  = $PVDrow["PVDNOMEFANT"];
								
							}
							
							echo"
							<tr>
								<td data-order='$FINSTATUS'><div align='center'>$finsituacao</div></td>
								<td><div align='center'><b>$FINCODTITULO</b></div></td>
								<td><div title='Fornecedor: $PVDRAZAONOME'>$FINIDFORNECEDOR</div></td>
								<td><div align='center'>$FINPARCELA</div></td>
								<td><div align='center'>$valororiginal</div></td>
								<td><div align='center'>$valorbaixado</div></td>
								<td><div align='center'>$valorjm</div></td>
								<td><div align='center'><b>$valor</b></div></td>
								<td><div align='center'>$dtvenc</div></td>
								<td><div align='center'>$dtbaixa</div></td>
								<td>
									<div id='cssmenu' align='center'>
										<ul>
											<li class='has-sub'><a href='#'><span></span></a>
												<ul>";						
								
													if($FINSTATUS <= 1){
														
														echo"
														<li>
															<form name='baixar' method='POST' action='form_consulta_financeiro.php'>
																<input name='pcodigo'         type='hidden' value='$PCODIGO'/>
																<input name='finidfornecedor' type='hidden' value='$FINIDFORNECEDOR'/>
																<input name='fincodtitulo'    type='hidden' value='$FINCODTITULO'/>
																<input name='finvalor'        type='hidden' value='$FINVALOR'/>
																<input name='finpagrec'       type='hidden' value='$FINPAGREC'/>
																<input name='finidfornecedor' type='hidden' value='$FINIDFORNECEDOR'/>
																<input name='acao'            type='hidden' value='BAIXAR'/>
																<button class='btn btn-default btn-xs' title='Baixar'><i class='fa fa-level-down' aria-hidden='true'></i></button>														
															</form>
														</li>";
														
													}
												
												echo "
												</ul>
											</li>
										</ul>
									</div>
								</td>
							</tr>";
						}
									
					echo "</tbody>
					</table>";
			
				}else{
					
					echo "<div align='center' class='info'>Nenhum Título a Pagar lançado.</div>
					<div align='center'>
						<form name='financeiro' method='POST' action='emissao_financeiro.php'>
							<input name='fintipo' type='hidden' value='PAGAR'/>
							<button type='submit' class='btn btn-info btn-sm'><i class='fa fa-plus'></i>&nbsp;&nbsp;Lançar Título a Pagar</button>
						</form>
					</div>";
					
				}
				
				echo "</div>
				</div>";

			?>

		</div>
	</body>
</html>