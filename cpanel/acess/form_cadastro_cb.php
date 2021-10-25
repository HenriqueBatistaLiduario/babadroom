<?php include 'head.php';?>

<script type="text/javascript">

	function confirma_ativar(){
		if(confirm('CONFIRMA ATIVAÇÃO desta Conta Bancária? Lançamentos Financeiros já gravados não serão afetados.\n\nClique em OK para continuar...'))
			return true;
		else
			return false;
	}

	function confirma_inativar(){
		if(confirm('CONFIRMA INATIVAÇÃO desta Conta Bancária? Lançamentos Financeiros já gravados não serão afetados.\n\nClique em OK para continuar...'))
			return true;
		else
			return false;
	}

	function confirma_cb(){
		if(confirm('CONFIRMA CADASTRO DE CONTA BANCÁRIA?\nCertifique-se de ter revisto todos os dados informados. Ao confirmar você assume a exclusiva responsabilidade legal sobre estas informações.\n\nClique em OK para continuar...'))
			return true;
		else
			return false;
	}
</script>

<body>
<div class='container-fluid'>

<?php 

$CBTIPOTITULAR = $_POST['cbtipotitular'];
$CBIDTITULAR   = $_POST['cbidtitular'];
$CBNOMETITULAR = $_POST['cbnometitular'];
$CBCGCTITULAR  = $_POST['cbcgctitular'];

$voltar = $_POST['voltar'];

if(isset($_POST['acao'])){
  $acao = $_POST['acao'];
   
  if($acao == 'SALVAR'){
		
		$cbcgctitular = $_POST['cbcgctitular'];
		$cbcodbanco   = $_POST['cbcodbanco'];
		$cbagencia    = $_POST['cbagencia'];
		$cbdigagencia = $_POST['cbdigagencia'];
		$cbnumconta   = $_POST['cbnumconta'];
		$cbdigconta   = $_POST['cbdigconta'];
		$cbtipo       = $_POST['cbtipo'];
		
		if($cbdigagencia == ''){
			$cbdigagencia = 99;
		}
		
		$CBselect = mysqli_query($con,"SELECT CBID FROM contas_bancarias WHERE CBCODBANCO = '$cbcodbanco' AND CBAGENCIA = '$cbagencia' AND CBNUMCONTA = '$cbnumconta' AND CBSTATUS = 1") or print (mysqli_error());
		$CBtotal  = mysqli_num_rows ($CBselect);
		
		if($CBtotal > 0){
			echo "<div align='center' class='warning'>ATENÇÃO: Dados bancários já existentes ATIVOS na Base de Dados! Verifique os dados informados.<br>Se necessário, inative a anterior primeiro.</div>";
			
		}else{
		
			$CBinsert = mysqli_query ($con,"INSERT INTO contas_bancarias(IDDOMINIO,IDADMINISTRADOR,CBTIPOTITULAR,CBIDTITULAR,CBCGCTITULAR,CBNOMETITULAR,CBCODBANCO,CBAGENCIA,CBDIGAGENCIA,CBNUMCONTA,CBDIGCONTA,CBTIPO,CBSTATUS,CBDTSTATUS,CBUSRSTATUS,CBCADASTRO,CBCADBY) 
			VALUES('$dominio_session','$adm_session','$CBTIPOTITULAR','$CBIDTITULAR','$cbcgctitular','$CBNOMETITULAR','$cbcodbanco','$cbagencia',$cbdigagencia,'$cbnumconta',$cbdigconta,'$cbtipo',1,'$datetime','$ident_session','$datetime','$ident_session')") or print (mysqli_error());
		
			if($CBinsert){
				echo "<div align='center' class='success'>Conta Bancária cadastrada com sucesso.</div>";
			
			}else{
				echo "<div align='center' class='error'>Erro no cadastro! Entre em contato com o Administrador do sistema.</div>";
			}
			
		}
		
	} 
	
	if($acao == 'INCLUIR_CB'){
		
		echo "
		
		<div class='panel panel-default'>
			<div class='panel-heading'>
				<span class='subpageName'>Cadastrar Conta Bancária</span>

				<ul class='pull-right list-inline'>
					<form name='dados_bancarios' method='POST' action='form_cadastro_cb.php' >
						<input name='cbtipotitular' type='hidden' value='$CBTIPOTITULAR'/>
						<input name='cbidtitular'   type='hidden' value='$CBIDTITULAR'/>
						<input name='cbnometitular' type='hidden' value='$CBNOMETITULAR'/>
						<input name='cbcgctitular'  type='hidden' value='$CBCGCTITULAR'/>
						<input name='voltar'        type='hidden' value='$voltar'/>
						<input class='inputsrc'     type='image' src='../images/close.png' title='Fechar'/>
					</form>
				</ul>
			</div>
			
			<div class='panel-body'>
		
				<form class='form-horizontal' name='incluir_cb' method='POST' action='form_cadastro_cb.php' onsubmit='return confirma_cb();'>
					<div class='form-group'>
						<label class='col-sm-6 control-label'>Tipo Titular:</label>
						<div class='col-sm-4'><input class='form-control' name='cbtipotitular' type='text' value='$CBTIPOTITULAR' readonly/></div>
					</div>
					
					<div class='form-group'>
						<label class='col-sm-6 control-label'>Identificação:</label>
						<div class='col-sm-4'><input class='form-control' name='cbidtitular' type='text' value='$CBIDTITULAR' readonly /></div>
					</div>
					
					<div class='form-group'>
						<label class='col-sm-6 control-label'>Nome do Titular:</label>
						<div class='col-sm-4'><input class='form-control' name='cbnometitular' type='text' value='$CBNOMETITULAR' /></div>
					</div>
					
					<div class='form-group'>
						<label class='col-sm-6 control-label'>CPF/CNPJ do Titular:</label>
						<div class='col-sm-4'><input class='form-control' name='cbcgctitular' type='text' maxlength='18' onkeypress='if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;' value='$CBCGCTITULAR' required /></div>
					</div>
					
					<div class='form-group'>
						<label class='col-sm-6 control-label'>Banco:</label>
						<div class='col-sm-4'>
							<select class='form-control' name='cbcodbanco' required >
								<option value=''>Selecione...</option>					 
								<option value='001'>001 | Banco do Brasil S/A</option>
								<option value='033'>033 | Banco Santander S/A</option>
								<option value='104'>104 | Caixa Econômica Federal</option>
								<option value='237'>237 | Banco Bradesco S/A</option>	
								<option value='341'>341 | Itaú Unibanco S/A</option>
							</select>
						</div>
					</div>
					
					<div class='form-group'>
						<label class='col-sm-6 control-label'>Agência:</label>
						<div class='col-sm-3'><input class='form-control' name='cbagencia' type='text' onkeypress='if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;' maxlength='5' required /></div>
						<div class='col-sm-1'><input class='form-control' name='cbdigagencia' type='text' placeholder='Dígito' onkeypress='if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;'/></div>
					</div>
					
					<div class='form-group'>
						<label class='col-sm-6 control-label'>Conta:</label>
						<div class='col-sm-3'><input class='form-control' name='cbnumconta' type='text' onkeypress='if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;' maxlength='15' required /></div>					
						<div class='col-sm-1'><input class='form-control' name='cbdigconta' type='text' required placeholder='Dígito' onkeypress='if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;'/></div>
					</div>
					
					
					<div class='form-group'>
						<label class='col-sm-6 control-label'>Natureza:</label>
						<div class='col-sm-4'>
							<select class='form-control' name='cbtipo' required >
								<option value=''>Selecione...</option>					 
								<option value=1>CRÉDITO | Recebimentos</option>
								<option value=2>DÉBITO | Pagamentos</option>
								<option value=3>AMBOS | Recebimentos e Pagamentos</option>
							</select>
					  </div>
          </div>						
					
					<input name='voltar' type='hidden' value='$voltar'/>
					<input name='acao'   type='hidden' value='SALVAR'>
					<div align='right'><input class='but but-rc but-azul' type='submit' value='      Salvar      '/></div>
				</form>
			</div>
		</div>";	
  }
	
	if($acao == 'ATIVAR'){
		
		$CBID = $_POST['cbid'];
		$CBupdate = mysqli_query($con,"UPDATE contas_bancarias SET CBSTATUS = 1, CBDTSTATUS = '$datetime', CBUSRSTATUS = '$ident_session' WHERE CBID = $CBID ") or print(mysqli_error());

		if($CBupdate){
			echo "<div align='center' class='success'>Conta corrente ativada com sucesso.</div>";
		}
		
	}

	if($acao == 'INATIVAR'){
		
		$CBID = $_POST['cbid'];
		$CBupdate = mysqli_query($con,"UPDATE contas_bancarias SET CBSTATUS = 0, CBDTSTATUS = '$datetime', CBUSRSTATUS = '$ident_session' WHERE CBID = $CBID ") or print(mysqli_error());

		if($CBupdate){
			echo "<div align='center' class='success'>Conta corrente desativada com sucesso.</div>";
		}
		
	}
	
}//Fim das ações.

?>

	<div class='panel panel-default'>
								
		<div class='panel-heading'>
			<span class='subpageName'>Contas Bancárias</span>

			<ul class='pull-right list-inline'>
				<li>
					<form id='fechar' name='fechar' method='POST' action='<?php echo $voltar;?>'>
						<input class='inputsrc' type='image' src='../images/close.png' title='Fechar'/>
					</form>
				</li>
			</ul>
		</div>
		
		<div class='panel-body'>

			<?php

			$CBselect = mysqli_query($con,"SELECT * FROM contas_bancarias WHERE CBIDTITULAR = '$CBIDTITULAR'") or print (mysqli_error());
			$CBtotal = mysqli_num_rows($CBselect);

			if ($CBtotal > 0){
				echo "
				<table id='DataTables' width='100%' class='display table-responsive table-condensed table-action table-striped'>
					<thead>
						<th width='2%' ><div align='center'></div></th>
						<th width='3%' ><div align='center'>Tipo</div></th>
						<th width='25%'><div>Nome do Titular</div></th>
						<th width='15%'><div>CPF/CNPJ do Titular</div></th>
						<th width='10%'><div align='center'>Banco</div></th>
						<th width='10%'><div align='center'>Agência</div></th>
						<th width='15%'><div align='center'>Conta</div></th>
						<th width='15%'><div align='center'>Natureza</div></th>
						<th width='3%' data-orderable='false'>
							<div align='center'>		
								<form name='dados_bancarios' method='POST' action='form_cadastro_cb.php' >
									<input name='cbtipotitular' type='hidden' value='$CBTIPOTITULAR'/>
									<input name='cbidtitular'   type='hidden' value='$CBIDTITULAR'/>
									<input name='cbnometitular' type='hidden' value='$CBNOMETITULAR'/>
									<input name='cbcgctitular'  type='hidden' value='$CBCGCTITULAR'/>
									<input name='voltar'        type='hidden' value='$voltar'/>
									<input name='acao'          type='hidden' value='INCLUIR_CB'/>
									<button type='submit' class='btn btn-default btn-sm' title='Incluir'><i class='fa fa-plus'></i></button>
								</form>
							</div>
						</th>
					</thead>
					<tbody>";	
				
				while($CBrow = mysqli_fetch_array($CBselect)){
					$CBID          = $CBrow["CBID"];
					$CBTIPOTITULAR = $CBrow["CBTIPOTITULAR"];
					$CBIDRECURSO   = $CBrow["CBIDRECURSO"];
					$CBCGCTITULAR  = $CBrow["CBCGCTITULAR"];
					$CBNOMETITULAR = $CBrow["CBNOMETITULAR"];
					$CBCODBANCO    = $CBrow["CBCODBANCO"];
					$CBAGENCIA     = $CBrow["CBAGENCIA"];
					$CBDIGAGENCIA  = $CBrow["CBDIGAGENCIA"];
					$CBNUMCONTA    = $CBrow["CBNUMCONTA"];
					$CBDIGCONTA    = $CBrow["CBDIGCONTA"];
					$CBTIPO        = $CBrow["CBTIPO"];
					$CBSTATUS      = $CBrow["CBSTATUS"];
					$CBDTSTATUS    = $CBrow["CBDTSTATUS"];
					$CBUSRSTATUS   = $CBrow["CBUSRSTATUS"];
					$CBCADASTRO    = $CBrow["CBCADASTRO"];
					$CBCADBY       = $CBrow["CBCADBY"];
					
					$cbdtstatus = date("d/m/Y H:i", strtotime($CBDTSTATUS));
					
					switch($CBCODBANCO){
					
						case '001' : $cbcodbanco = "001 | Banco do Brasil S/A"; break;
						case '033' : $cbcodbanco = "033 | Banco Santander S/A"; break;
						case '104' : $cbcodbanco = "104 | Caixa Econômica Federal"; break;
						case '237' : $cbcodbanco = "237 | Banco Bradesco S/A"; break;
						case '341' : $cbcodbanco = "341 | Itaú Unibanco S/A"; break;
					
					}
					
					if($CBDIGAGENCIA == 99 OR $CBDIGAGENCIA == NULL){
						$cbagencia = "$CBAGENCIA";
					
					}else{
						$cbagencia = "$CBAGENCIA-$CBDIGAGENCIA";
					}
					
					switch($CBSTATUS){
						case 0: $situacao = "<img src='../images/status/VERMELHO.png' title='INATIVO desde $cbdtstatus'/>";break;
						case 1: $situacao = "<img src='../images/status/VERDE.png' title='ATIVO desde $cbdtstatus'/>";break;
					}
					
					switch($CBTIPO){
						case 1: $tipo = "CRÉDITO | Recebimentos";break;
						case 2: $tipo = "DÉBITO | Pagamentos";break;
						case 3: $tipo = "AMBOS | Recebimentos e Pagamentos";break;
					}
					
					echo "
					<tr>
					<td data-order='$CBSTATUS'><div align='center'>$situacao</div></td>
					<td><div>$CBTIPOTITULAR</div></td>
					<td><div>$CBNOMETITULAR</div></td>
					<td><div>$CBCGCTITULAR</div></td>
					<td><div align='center'>$cbcodbanco</div></td>
					<td><div align='center'>$cbagencia</div></td>
					<td><div align='center'>$CBNUMCONTA - $CBDIGCONTA</div></td>
					<td><div align='center'>$tipo</div>      </td>
						<td>
							<div id='cssmenu' align='center'>
								<ul>
									<li class='has-sub'><a href='#'><span></span></a>
										<ul>";
					
										if ($CBSTATUS == 1){//BLOQUEAR
											echo "
											<li>
												<form name='inativar' method='POST' action='form_cadastro_cb.php' onsubmit='return confirma_inativar()'>
													<input name='cbid'          type='hidden' value='$CBID'/>
													<input name='cbtipotitular' type='hidden' value='$CBTIPOTITULAR'/>
													<input name='cbidtitular'   type='hidden' value='$CBIDTITULAR'/>
													<input name='cbnometitular' type='hidden' value='$CBNOMETITULAR'/>
													<input name='voltar'        type='hidden' value='$voltar'/>
													<input name='acao'          type='hidden' value='INATIVAR'/>
													<button class='btn btn-default btn-xs' title='INATIVAR'><i class='fa fa-ban' aria-hidden='true'></i></button>
												</form>
											</li>";
										}
											
										if ($CBSTATUS == 0){//DESBLOQUEAR
											echo "
											<li>
												<form name='ativar' method='POST' action='form_cadastro_cb.php' onsubmit='return confirma_ativar()'>
													<input name='cbid'          type='hidden' value='$CBID'/>
													<input name='cbtipotitular' type='hidden' value='$CBTIPOTITULAR'/>
													<input name='cbidtitular'   type='hidden' value='$CBIDTITULAR'/>
													<input name='cbnometitular' type='hidden' value='$CBNOMETITULAR'/>
													<input name='voltar'        type='hidden' value='$voltar'/>
													<input name='acao'          type='hidden' value='ATIVAR'/>
													<button class='btn btn-default btn-xs' title='ATIVAR'><i class='fa fa-ban' aria-hidden='true'></i></button>
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
				echo "<div align='center' class='info'>Nenhuma Conta Bancária cadastrada para este $CBTIPOTITULAR</div>
					<div align='center'>		
						<form name='dados_bancarios' method='POST' action='form_cadastro_cb.php' >
							<input name='cbtipotitular' type='hidden' value='$CBTIPOTITULAR'/>
							<input name='cbidtitular'   type='hidden' value='$CBIDTITULAR'/>
							<input name='cbnometitular' type='hidden' value='$CBNOMETITULAR'/>
							<input name='voltar'        type='hidden' value='$voltar'/>
							<input name='acao'          type='hidden' value='INCLUIR_CB'/>
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