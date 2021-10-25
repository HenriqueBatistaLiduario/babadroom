<?php include 'head.php';?>

	<script language="javascript">
	
		$(document).ready(function(){
			$("#finvalorbaixa").maskMoney({showSymbol:false, decimal:".", thousands:""});
		});

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
		
	</script>
	
	<body>
		<div class='container-fluid'>

			<?php

				if(isset($_POST['pcodigo']) AND $PCODIGO != ''){
				
					$PCODIGO = $_POST['pcodigo'];
					$legend = "CONTAS A RECEBER | Projeto <b>$PCODIGO</b>";
				
				}else if(isset($_POST['accodcontrato']) AND $ACCODCONTRATO != ''){
					
					$ACCODCONTRATO = $_POST['accodcontrato'];
					$legend = "CONTAS A RECEBER | Contrato <b>$ACCODCONTRATO</b>";		
					
				}else{
					
					$PCODIGO = '';
					$ACCODCONTRATO = '';
					$legend = "CONTAS A RECEBER";
					
				}
				
				$FINCODTITULO2 = '';
				$OSNUMERO2 = '';
				
				if(isset($_POST['acao'])){
					$acao = $_POST['acao'];

					if($acao == 'BAIXAR'){
						$FINCODTITULOB = $_POST['fincodtitulo'];
						$FINVALOR      = $_POST['finvalor'];
						$FINPAGREC     = $_POST['finpagrec'];
						
						if(isset($_POST['finidrecurso'])){
							$FINIDRECURSO = $_POST['finidrecurso'];
						}
						
						?>
						<div class='panel panel-default'>
							<div class='panel-heading'>
								<span class='subpageName'>Baixar Lançamento <b><?php echo $FINCODTITULOB;?></b></span>

								<ul class='pull-right list-inline'>
									<li>
										<ul class='pull-right list-inline'>
											<li><a href='#' title='Ocultar' onclick="recolher_painel('painel_baixa')"><i class='fa fa-arrows-v' aria-hidden='true'></i></a></li>
										</ul>
									</li>
								</ul>
							</div>
							
							<div class='panel-body' id='painel_dc'>
								<form class='form-horizontal' name='form_baixa' method='POST' action='form_consulta_fincr.php' onsubmit = 'return confirma_baixa();'>
								
									<div class='form-group'>
										<label class='col-sm-2 control-label'>Data de Baixa:</label>
										<div class='col-sm-2'><input class='form-control' name='findtbaixa' type='date' id='findtbaixa' required value='<?php echo $hoje;?>'/></div>
									
										<label class='col-sm-2 control-label'>Valor:</label>
										<div class='col-sm-2'><input class='form-control' name='finvalorbaixa' id='finvalorbaixa' type='text' maxlength='10' value='<?php echo $FINVALOR;?>' title=' Valor pode ser Total ou Parcial. Se Parcial, o Lançamento ficará com o Status BAIXADO PARCIALMENTE.' required /></div>
									</div>
								
									<?php 
									
									$ADMselect  = mysql_query("SELECT ADMCPFCNPJ FROM administradores WHERE ADMIDENTIFICADOR = '$adm_session' ORDER BY ADMID DESC LIMIT 1") or print (mysql_error());
									$ADMCPFCNPJ = mysql_result($ADMselect,0,"ADMCPFCNPJ");				
									
									echo"
									<div class='form-group'>
										<label class='col-sm-2 control-label'>Conta Crédito:</label>
										<div class='col-sm-6'>
											<select class='form-control' name='finidcbcredito' required title='Selecione a Conta do Administrador na qual foi creditado o Valor pago pelo cliente'>";
											
											$CBselectCA = mysql_query("SELECT * FROM contas_bancarias WHERE CBIDRECURSO = '$ADMCPFCNPJ' AND CBTIPO IN (1,3)") or print(mysql_error());
											$CBtotalCA = mysql_num_rows($CBselectCA);
											
											if($CBtotalCA > 0){
												
												while($linha = mysql_fetch_array($CBselectCA)){
													$CBID          = $linha['CBID'];
													$CBCODBANCO    = $linha['CBCODBANCO'];
													$CBAGENCIA     = $linha['CBAGENCIA'];
													$CBNUMCONTA    = $linha['CBNUMCONTA'];
													$CBNOMETITULAR = $linha['CBNOMETITULAR'];

													echo "<option value='$CBID'>Nome Titular: $CBNOMETITULAR | Banco $CBCODBANCO | Agência $CBAGENCIA | Conta $CBNUMCONTA</option>";
												}
												
											}else{
												echo "<option value=''>Nenhuma Conta CRÉDITO ativa para o Administrador.</option>";
											}
											
											echo "
											</select>
										</div>
									</div>";
									
									?>				
									<div class='form-group'>
										<label class='col-sm-2 control-label'>Observações:</label>
										<div class='col-sm-6'><textarea class='form-control' name="finobs" id="finobs" rows="5" maxlength='300' placeholder='Informações relevantes sobre a Baixa...'/></textarea></div>
									</div>
									<input name='fincodtitulo'  type='hidden' value='<?php echo $FINCODTITULOB;?>'/>
									<input name='pcodigo'       type='hidden' value='<?php echo $PCODIGO;?>'/>
									<input name='accodcontrato' type='hidden' value='<?php echo $ACCODCONTRATO;?>'/>
									<input name='finvalor'      type='hidden' id='finvalor' value='<?php echo $FINVALOR;?>'/>
									<input name='acao'          type='hidden' value='CONFIRMAR_BAIXA'/>
									<div align='right'><input class='but but-azul' type='submit'   value='    Confirmar    ' onclick='return consiste_baixa();'/></div>
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
						 
						 $FINupdate = mysql_query("UPDATE financeiro SET FINVALORBAIXADO = (FINVALORBAIXADO + $FINVALORBAIXA), 
																														 FINVALOR = (FINVALORORIGINAL - FINVALORBAIXADO),
																														 FINDTBAIXA = '$FINDTBAIXA',
																														 FINUSRBAIXA = '$ident_session',
																														 FINIDCBCREDITO = $FINIDCBCREDITO
																														 WHERE FINCODTITULO = '$FINCODTITULO' ") or print (mysql_error());
						if($FINupdate){
							 $FINselectStatus = mysql_query("SELECT * FROM financeiro WHERE FINCODTITULO = '$FINCODTITULO'") or print (mysql_error());
							 $FINVALORORIGINAL = mysql_result($FINselectStatus,0,"FINVALORORIGINAL");
							 $FINVALOR = mysql_result($FINselectStatus,0,"FINVALOR");
							 
								if (($FINVALOR < $FINVALORORIGINAL) AND $FINVALOR > 0){
									$FINSTATUS = 1;
							 
								}else if ($FINVALOR == 0){
									$FINSTATUS = 5;
								}
							
							$FINSTATUSupdate = mysql_query("UPDATE financeiro SET FINSTATUS = $FINSTATUS WHERE FINCODTITULO = '$FINCODTITULO'") or print (mysql_error());
							
							if ($FINSTATUSupdate){
									$finvalorbaixa = number_format($FINVALORBAIXA,2,",",".");//Formatar o Valor.
									$findtbaixa = date("d/m/Y",strtotime($FINDTBAIXA));
									echo "<div id='alert' align='center' class='success'>Baixa no Lançamento <b>$FINCODTITULO</b>, no valor de <b>$finvalorbaixa</b> na Data <b>$findtbaixa</b> realizada com sucesso.</div>";
							}
						}
					}		
					
					if ($acao == 'ATIVIDADESOS'){
						$OSNUMERO2 = $_POST['osnumero'];
						$Aselect = mysql_query("SELECT * FROM agenda WHERE AFATCODOS = '$OSNUMERO2'") or print(mysql_error());
						
						echo "<fieldset><legend class='subpageName'>&nbsp;&nbsp;Atividades constantes na O.S. <b>$OSNUMERO2</b>&nbsp;&nbsp;</legend>
							 <div align='right'>
								<form name='fechar' method='POST' action='form_consulta_fincr.php'>
									<input class='inputsrc' type ='image' src='../imagens/close.png' title='Fechar'/>
								</form>
							</div>
							<div id='fieldsetheader'>
							<table width='99%' align='center'>
								<thead>
									<tr>
										<th width='2%' ><div align='center'></div>              </th>
										<th width='12%'><div align='center'>Patrocinador</div>  </th>
										<th width='4%' ><div align='center'>Projeto</div>       </th>
										<th width='25%'><div align='center'>Etapa / Tarefa</div></th>
										<th width='12%'><div align='center'>Prestador</div>     </th>
										<th width='4%' ><div align='center'>Data</div>          </th>
										<th width='4%' ><div align='center'>Período</div>       </th>
										<th width='5%' ><div align='center'>Hrs. alocadas</div> </th>
										<th width='4%' ><div align='center'>Hr Início</div>     </th>
										<th width='4%' ><div align='center'>Hr Fim</div>        </th>
										<th width='10%'><div align='center'>Status Tarefa</div> </th>
										<th width='7%' ><div align='center'>Duração</div>       </th>
										<th width='10%'><div align='center'>Custo</div>         </th>
									</tr>
								</thead>
								</table>
								</div>
								<div id='fieldsetpequeno'>
								<table align='center' width='99%' class='zebrado'>
								<tbody>";
								
						while ($busca = mysql_fetch_array($Aselect)){
							$osaid         	 = $busca["AID"          ];
							$osadata       	 = $busca["ADATA"        ];
							$osaperiodo      = $busca["APERIODO"     ];
							$osaidtarefa   	 = $busca["AIDTAREFA"    ];
							$osaidrecurso    = $busca["AIDRECURSO"   ];
							$osaidpatrocinador = $busca["AIDPATROCINADOR"   ];
							$osastatustarefa = $busca["ASTATUSTAREFA"];
							$osapontado    	 = $busca["AAPONTADO"    ];
							$osastatus    	 = $busca["ASTATUS"      ];
							$osahrinicio   	 = $busca["AHRINICIO"    ];
							$osahriniciop  	 = $busca["AHRINICIOPREV"];
							$osahrtermino  	 = $busca["AHRTERMINO"   ];
							$osaduracaoprev	 = $busca["ADURACAOPREV" ];
							$osaduracao    	 = $busca["ADURACAO"     ];
							$osAVALOR      	 = $busca["AVALOR"       ];
							$osadatac      	 = date('d/m/Y', strtotime($osadata));
							$osavalor        = number_format($osAVALOR,2,",",".");//Formatar o Valor.
							
							//Exibir minutos no formato de horas...
							$prevosminuth = $osaduracaoprev%60;
							$prevoshorar  =($osaduracaoprev-$prevosminuth)/60;
							
							$osminuth = $osaduracao%60;
							$oshorar  =($osaduracao-$osminuth)/60;

							switch($osastatus){
								case 0: $ossituacao = "<img src='../imagens/status/AZUL.png'   title='PENDENTE'/>"; break;
								case 1: $ossituacao = "<img src='../imagens/status/VERDE.png'  title='CONFIRMADO - NÃO APONTADO'/>"; break;
								case 2: $ossituacao = "<img src='../imagens/status/ROXO.png'   title='CANCELADO'/>";  break;
								case 3: $ossituacao = "<img src='../imagens/status/BRANCO.png' title='APONTADO - NÃO FATURADO'/>";   break;
								case 4: $ossituacao = "<img src='../imagens/status/PRETO.png'  title='FATURADO'/>";   break;
							}

							switch($osaperiodo){ 
								case 0: $osperiodosel = 'NÃO OBTIDO'; break;
								case 1: $osperiodosel = 'MANHÃ';      break;
								case 2: $osperiodosel = 'TARDE';      break;
								case 3: $osperiodosel = 'NOITE';      break;
								case 4: $osperiodosel = 'INTEGRAL';   break;
								case 5: $osperiodosel = 'HORÁRIO COMERCIAL'; break;	
							}
							
							$osselect3     = mysql_query("SELECT PARAZAONOME FROM patrocinadores WHERE PACPFCNPJ=$osaidpatrocinador") or print (mysql_error());
							$osPARAZAONOME = mysql_result($osselect3,0,"PARAZAONOME");

							$osselect4 = mysql_query("SELECT RNOME FROM recursos WHERE RCPFCNPJ=$osaidrecurso") or print (mysql_error());
							$osRNOME   = mysql_result($osselect4,0,"RNOME");

							$Tselect = mysql_query("SELECT * FROM tarefas WHERE TID = '$osaidtarefa'") or print (mysql_error());
							$osTID        = mysql_result($Tselect,0,"TID");
							$osTPROJETO   = mysql_result($Tselect,0,"TPROJETO");
							$osTMODULO    = mysql_result($Tselect,0,"TMODULO");
							$osTETAPA     = mysql_result($Tselect,0,"TETAPA");
							$osTSEQUENCIA = mysql_result($Tselect,0,"TSEQUENCIA");
							$osTDESCRICAO = mysql_result($Tselect,0,"TDESCRICAO");
								
							echo"
							<tr class='subHeader'>
								<td width='2%' ><div align='center'>$ossituacao</div></td>
								<td width='12%'><div align='center' title='Razão Social: $osPARAZAONOME'>$osaidpatrocinador</div></td>
								<td width='4%' ><div align='center'>$osTPROJETO</div></td>
								<td width='25%'><div align='center'>$osTETAPA / <b>$osTSEQUENCIA.</b> $osTDESCRICAO</div></td>
								<td width='12%'><div align='center' title='Razão Social/Nome: $osRNOME'>$osaidrecurso</div></td>
								<td width='4%' ><div align='center'>$osadatac</div></td>
								<td width='4%' ><div align='center'>$osperiodosel</div></td>
								<td width='5%' ><div align='center'>$prevoshorar:$prevosminuth h</div></td>
								<td width='4%' ><div align='center'>$osahrinicio</div></td>
								<td width='4%' ><div align='center'>$osahrtermino</div></td>
								<td width='10%'><div align='center'>$osastatustarefa%</div></td>
								<td width='7%' ><div align='center'>$osaduracao min ($oshorar:$osminuth h)</div></td>
								<td width='10%'><div align='center'><i>R$ $osavalor</i></div></td>
							</tr>";
						}
						echo "
						</tbody>
						</table>
						</div>
						</fieldset>";

						$FINCODTITULO2 = $_POST['fincodtitulo'];
						$OSselect = mysql_query("SELECT * FROM ordens WHERE OSFINCODTITULO = '$FINCODTITULO2'") or print(mysql_error());
						echo "<fieldset><legend class='subpageName'>&nbsp;&nbsp;Ordens de Serviço constantes no Lançamento Financeiro <b>$FINCODTITULO2</b>&nbsp;&nbsp;</legend><br>
							<div id='fieldsetheader'>
							<table width='99%' align='center'>
							<thead>				
							<tr>
								<th width='2%' ><div align='center'></div>               </th>
								<th width='10%'><div align='center'>Número</div>         </th>
								<th width='15%'><div align='center'>Patrocinador</div>   </th>
								<th width='10%'><div align='center'>Projeto</div>        </th>
								<th width='15%'><div align='center'>Tempo consumido</div></th>
								<th width='8%' ><div align='center'>Valor</div>          </th>
								<th width='8%' ><div align='center'>Emissão</div>        </th>
								<th width='8%' ><div align='center'>Autorização</div>    </th>
								<th width='8%' ><div align='center'>Limite Fat.</div>    </th>
								<th width='8%' ><div align='center'>Faturamento</div>    </th>
								<th width='3%' ></td>
							</tr>
							</thead>
							</table>
							</div>
							<div id='fieldsetpequeno'>
							<table align='center' width='99%' class='zebrado'>
							<tbody>";
						
						while ($OSlinha = mysql_fetch_array($OSselect)){
							$OSID             = $OSlinha['OSID'];
							$OSNUMERO         = $OSlinha['OSNUMERO'];
							$OSIDPATROCINADOR = $OSlinha['OSIDPATROCINADOR'];
							$OSCODPROJETO     = $OSlinha['OSCODPROJETO'];
							$OSCODCHAMADO     = $OSlinha['OSCODCHAMADO'];
							$OSQTDEHORAS      = $OSlinha['OSQTDEHORAS'];
							$OSVALOR          = $OSlinha['OSVALOR'];
							$OSDTEMISSAO      = $OSlinha['OSDTEMISSAO'];
							$OSDTLIMITEFAT    = $OSlinha['OSDTLIMITEFAT'];
							$OSDTAUTORIZACAO  = $OSlinha['OSDTAUTORIZACAO'];
							$OSDTFATURAMENTO  = $OSlinha['OSDTFATURAMENTO'];
							$OSSTATUS         = $OSlinha['OSSTATUS'];

							if ($OSNUMERO == $OSNUMERO2){
								$OSSELECIONADA = "<tr bgcolor = 'yellow'>";
							}else{ 
								$OSSELECIONADA = "<tr>";
							}				
							
							$totalminutos =  $OSQTDEHORAS%60;
							$totalhoras   = ($OSQTDEHORAS-$totalminutos)/60;
							
							$VALORTOTAL = number_format($OSVALOR,2,",",".");//Formatar o Valor.
							
							$osdtemissao     = date("d/m/Y H:i",strtotime($OSDTEMISSAO));
							$osdtautorizacao = date("d/m/Y H:i",strtotime($OSDTAUTORIZACAO));
							$osdtlimitefat   = date("d/m/Y H:i",strtotime($OSDTLIMITEFAT));
							$osdtfaturamento = date("d/m/Y H:i",strtotime($OSDTFATURAMENTO));
							
							switch($OSSTATUS){// Status da oS... 
								case 0: $situacao = "<img src='../imagens/status/AZUL.png'  title='AGUARDANDO AUTORIZAÇÃO DE FATURAMENTO'/>"; break;
								case 1: $situacao = "<img src='../imagens/status/VERDE.png' title='FATURAMENTO AUTORIZADO'/>";  break;
								case 2: $situacao = "<img src='../imagens/status/ROXO.png'  title='CANCELADA'/>";  break;
								case 3: $situacao = "<img src='../imagens/status/PRETO.png' title='FATURADA'/>";  break;
							}
								
							echo"
							$OSSELECIONADA
								<td width='2%' ><div align='center'>$situacao</div></td>
								<td width='10%'><div align='center'>$OSNUMERO</div></td>
								<td width='15%'><div align='center'>$OSIDPATROCINADOR</div></td>
								<td width='10%'><div align='center'>$OSCODPROJETO</div></td>
								<td width='15%'><div align='center'>$totalhoras:$totalminutos Hrs.</div></td>
								<td width='8%' ><div align='center'>R$ $VALORTOTAL</div></td>
								<td width='8%' ><div align='center'>$osdtemissao</div></td>
								<td width='8%' ><div align='center'>$osdtautorizacao</div></td>
								<td width='8%' ><div align='center'>$osdtlimitefat</div></td>
								<td width='8%' ><div align='center'>$osdtfaturamento</div></td>";
									 
								if($OSCODCHAMADO == NULL){
									echo"
									<td width='3%' >
										<div align='center'>
											<form name='detalhes' method='POST' action='form_consulta_fincr.php'>
												<input name='pcodigo'      type='hidden' value='$PCODIGO'/>
												<input name='osnumero'     type='hidden' value='$OSNUMERO'/>
												<input name='pcodigo'      type='hidden' value='$OSCODPROJETO'/>
												<input name='fincodtitulo' type='hidden' value='$FINCODTITULO2'/>
												<input name='acao'         type='hidden' value='ATIVIDADESOS'/>
												<input class='inputsrc'    type='image' src='../imagens/detalhes.png' title='Atividades contidas nesta Ordem de Serviço'/>
											</form>
										</div>
									</td>";
								
								}else{
									echo"
									<td width='3%' >
										<div align='center'>
											<form name='detalhes' method='POST' action='form_consulta_fincr.php'>
												<input name='pcodigo'   type='hidden' value='$PCODIGO'/>
												<input name='osnumero'  type='hidden' value='$OSNUMERO'/>
												<input name='ocodigo'   type='hidden' value='$OSCODCHAMADO'/>
												<input name='acao'      type='hidden' value='CHAMADO_ORIGEM'/>
												<input class='inputsrc' type='image' src='../imagens/interagir.png' title='Ver chamado origem.'/>
											</form>
										</div>
									</td>";
								}
								echo"
							</tr>";
						}
						echo "</tbody></table></div>
									</fieldset><br>";		
					}

					if($acao == 'CHAMADO_ORIGEM'){
						 $OCODIGO = $_POST['ocodigo'];
						 $OSNUMERO2 = $_POST['osnumero'];
						 $Oselect = mysql_query("SELECT * FROM ocorrencias WHERE OCODIGO = '$OCODIGO'") or print (mysql_error());
						 echo"
						<fieldset><legend class='subpageName'>Chamado constante na O.S. <b>$OSNUMERO2</b></legend>
						 <div align='right'>
								<form name='fechar' method='POST' action='form_consulta_fincr.php'>
									<input name='pcodigo'       type='hidden' value='$PCODIGO'/>
									<input name='accodcontrato' type='hidden' value='$ACCODCONTRATO'/>
									<input class='inputsrc'     type ='image' src='../imagens/close.png' title='Fechar'/>
								</form>
							</div>
						<div id='fieldsetheader' align='center'>
						<table align='center' width='99%'>
							<thead>
								<tr align='center'>
									<th width='2%' ><div>      </div>          </th>
									<th width='2%' ><div></div>                </th>
									<th width='2%' ><div></div>                </th>
									<th width='7%' ><div>Projeto</div>         </th>
									<th width='7%' ><div>Código</div>          </th>
									<th width='5%' ><div>Categoria</div>       </th>
									<th width='5%' ><div>Prioridade</div>      </th>
									<th width='5%' ><div>Módulo</div>          </th>
									<th width='5%' ><div>Rotina  </div>        </th>
									<th width='5%' ><div>Processo</div>        </th>
									<th width='27%'><div>Incidente</div>       </th>
									<th width='8%' ><div>Abertura</div>        </th>
									<th width='8%' ><div>Classificação</div>   </th>
									<th width='8%' ><div>Previsão Solução</div></th>
									<th width='2%' >                           </th>
									<th width='2%' >                           </th>
								</tr>
							</thead>
							</table>
					</div>
					<div id='fieldsetpequeno'>
					<table align='center' width='99%' class='zebrado'>
					<tbody>";
								
					while ($linha = mysql_fetch_array($Oselect)){
						$OCODIGO        = $linha["OCODIGO"];
						$ocoligada      = $linha["OCODCOLIGADA"];
						$ofilial        = $linha["OCODFILIAL"];
						$oidpatrocinador= $linha["OIDPATROCINADOR"];
						$ocodprojeto    = $linha["OCODPROJETO"];
						$osolicitante   = $linha["OIDSOLICITANTE"];
						$ocategoria     = $linha["OCATEGORIA"];
						$oidmodulo      = $linha["OIDMODULO"];
						$orotina        = $linha["OROTINA"];
						$osubrotina     = $linha["OSUBROTINA"];
						$oresumo        = $linha["ORESUMO"];
						$odetalhes      = $linha["ODETALHES"];
						$odtocorrencia  = $linha["ODTOCORRENCIA"];
						$odtabertura    = $linha["OCADASTRO"];
						$odtclassifica  = $linha["ODTCLASSIFICACAO"];
						$odtprevsolucao = $linha["ODTPREVSOLUCAO"];
						$odtinicioatend = $linha["ODTINICIOATEND"];
						$oultintsolpos  = $linha["OULTINTSOLPOS"];
						$oseqinteracoes = $linha["OSEQINTERACOES"];
						$odtultsolpos   = $linha["ODTULTSOLPOS"];
						$ocontsolpos    = $linha["OCONTSOLPOS"];
						$oatendente     = $linha["OIDRECALOCADO"];
						$osituacao      = $linha["OSITUACAO"];
						$ostatus        = $linha["OSTATUS"];	
						$osolucao       = $linha["OSOLUCAO"];			
						$oprioridade    = $linha["OPRIORIDADE"];
						$ocadby   	    = $linha["OCADBY"];
						$oultint        = $linha["OULTINT"];
						$odiscussao     = $linha["ODISCUSSAO"];
						$oseqinteracoes = $linha["OSEQINTERACOES"];
						$oqtdesolrej    = $linha["OQTDESOLREJ"];
						$opsresp        = $linha["OPSRESP"];
						$otipo          = $linha["OTIPO"];
						$ousradicional1 = $linha["OUSRADICIONAL1"];
						
						if($otipo == 2){
							$tipoo = "<font color='purple'><b>Demanda interna</b></font>";
						
						}else{
							$tipoo = "";
						}
						
						$select2 = mysql_query("SELECT * FROM patrocinadores WHERE PACPFCNPJ = '$oidpatrocinador'") or print(mysql_error());
						$PANOME  = mysql_result($select2,0,"PARAZAONOME");
						
						$select3 = mysql_query("SELECT * FROM usuarios WHERE USERIDENTIFICACAO = '$ocadby'") or print(mysql_error());
						$CADBYNOME = mysql_result($select3,0,"USERNOME");
						$CADBYEMAIL = mysql_result($select3,0,"USERLOGIN");
										
						$categoria = mysql_query("SELECT CATDESCRICAO FROM categorias WHERE CATCODIGO = '$ocategoria'") or print(mysql_error());
						$cat = mysql_result($categoria,0,"CATDESCRICAO");
						
						$mdesc = mysql_query("SELECT MDESCRICAO FROM modulos WHERE MCODIGO = '$oidmodulo'") or print (mysql_error());
						$MDESCRICAO = mysql_result($mdesc,0,"MDESCRICAO");
						
						if($orotina != NULL){
							$rotina   = mysql_query("SELECT RPGRUPO,RPTITULO FROM rotinas_modulo WHERE RPCODIGO = '$orotina'") or print(mysql_error());
							$RPGRUPO  = mysql_result($rotina,0,"RPGRUPO");
							$RPTITULO = mysql_result($rotina,0,"RPTITULO");
						
						}else{
							$RPGRUPO  = '';
							$RPTITULO = '';
						}
						
						if($osubrotina != NULL){
							$subrotina = mysql_query("SELECT SRTITULO FROM subrotinas WHERE SRCODIGO = '$osubrotina'") or print(mysql_error());
							$SRTITULO  = mysql_result($subrotina,0,"SRTITULO");
						
						}else{
								$SRTITULO = '';
						}

						$odtaberturax  = date("d/m/Y H:i",strtotime($odtabertura));
						$odtultsolposx = date("d/m/Y H:i",strtotime($odtultsolpos));		
						
						if ($odtclassifica != NULL){
								$odtclassificax = date("d/m/Y H:i",strtotime($odtclassifica));
						
						}else{
								$odtclassificax = "<img src='../imagens/indefinido.png' title='Aguardando Classificação'/>";
						}
						
						if ($odtprevsolucao != NULL){
								$odtprevsolucaox = date("d/m/Y",strtotime($odtprevsolucao));
						}else{
								$odtprevsolucaox = "<img src='../imagens/indefinido.png' title='Aguardando Classificação'/>";
						}
						
						if ($ousradicional1 != NULL AND $ousradicional1 != ''){
							$usrad1cons   = mysql_query("SELECT * FROM usuarios WHERE USERIDENTIFICACAO = '$ousradicional1'") or print(mysql_error());
							$ousrad1nome  = mysql_result($usrad1cons,0,"USERNOME");
							$ousrad1email = mysql_result($usrad1cons,0,"USERLOGIN");
							$ousuarioad1  = "Compartilhado com $ousradicional1 | $ousrad1nome ($ousrad1email)";
				
						}else{
							$ousuarioad1 = "Não compartilhado";
						}
						
						$solcons   = mysql_query("SELECT * FROM usuarios WHERE USERIDENTIFICACAO='$osolicitante'") or print(mysql_error());
						$osolnome  = mysql_result($solcons,0,"USERNOME");
						$osolemail = mysql_result($solcons,0,"USERLOGIN");
						$osoltel1  = mysql_result($solcons,0,"USERTEL1");
						$solfoto   = mysql_result($solcons,0,"USERFOTO");
						
						if ($solfoto == 1){
							$solicitante = "<img class='foto_perfilP' src='../imagens/Fotos/$osolicitante.jpg' 
							title='Patrocinador: $oidpatrocinador | $PANOME\nSolicitante: $osolicitante | $osolnome ($osolemail)\nAberto por $ocadby | $CADBYNOME ($CADBYEMAIL)\n$ousuarioad1'/>";

						}else{
							$solicitante = "<img class='foto_perfilP' src='../imagens/solicitante6.png' 
							title='Patrocinador: $oidpatrocinador | $PANOME\nSolicitante: $osolicitante | $osolnome ($osolemail)\nAberto por $ocadby | $CADBYNOME ($CADBYEMAIL)\n$ousuarioad1'/>";
						}
						
						if ($oatendente != NULL AND $oatendente != '999'){
							$atendcons  = mysql_query("SELECT * FROM usuarios WHERE USERIDENTIFICACAO = '$oatendente'") or print(mysql_error());
							$atendnome  = mysql_result($atendcons,0,"USERNOME");
							$atendemail = mysql_result($atendcons,0,"USERLOGIN");
							$atendfoto  = mysql_result($atendcons,0,"USERFOTO");
							
							if ($atendfoto == 1){
								$atendente = "<img class='foto_perfilP' src='../imagens/Fotos/$oatendente.jpg' title='$oatendente | $atendnome ($atendemail)'/>";

							}else{
								$atendente = "<img class='foto_perfilP' src='../imagens/atendente3.png' title='$oatendente | $atendnome ($atendemail)'/>";
							}

						}else if($oatendente == '999'){
							$atendente = "<img class='foto_perfilP' src='../imagens/atendente3.png' title='999 | Todos'/>";

						}else{
							$atendente = "<img class='foto_perfilP' src='../imagens/atendente_indefinido.jpg' title='Aguardando Classificação'/>";
						}
						
						if ($oprioridade != NULL){
							$prioridade  = $oprioridade;
							
						}else{
								$prioridade = "<img src='../imagens/indefinido.png' title='Aguardando Classificação'/>";
						}
						
						switch($osituacao){// Status do chamado...
							case 0: $situacao = "<img src='../imagens/status/AZUL.png'    title='NOVA - PENDENTE CLASSIFICAÇÃO'/>";break;
							case 1: $situacao = "<img src='../imagens/status/LARANJA.png' title='PENDENTE ATENDENTE'/>";break;
							case 2: $situacao = "<img src='../imagens/status/AMARELO.png' title='PENDENTE SOLICITANTE'/>";break;
							case 3: $situacao = "<img src='../imagens/status/LARANJA.png' title='PENDENTE TERCEIROS'/>";break;
							case 4: $situacao = "<img src='../imagens/status/LARANJA.png' title='PENDENTE ATENDENTE | $oqtdesolrej Solução(oes) rejeitada(s)'/>"; break;
							case 5: $situacao = "<img src='../imagens/status/SOLUCAO.png' title='PENDENTE SOLICITANTE - SOLUÇÃO APRESENTADA'/>"; break;
							case 6: $situacao = "<img src='../imagens/status/LARANJA.png' title='PENDENTE TERCEIROS'/>"; break;
							case 7: $situacao = "<img src='../imagens/status/VERDE.png'   title='SOLUCIONADO'/>"; break;
							case 8: $situacao = "<img src='../imagens/status/BRANCO.png'  title='ENCERRADO'/>"; break;
							case 9: $situacao = "<img src='../imagens/status/PRETO.png'   title='ENCERRADO AUTOMATICAMENTE'/>"; break;
							case 10:$situacao = "<img src='../imagens/status/ROXO.png'    title='CANCELADO PELO SOLICITANTE'/>"; break;
							case 11:$situacao = "<img src='../imagens/status/ROXO.png'    title='CANCELADO PELO SUPORTE'/>"; break;
						}
						
						if($osituacao == 7 AND $opsresp == 1){
							 $situacao = "<img src='../imagens/OK.png' title='SOLUCIONADO'/>";
						}
						
						switch($odtprevsolucao){// Status do chamado...
							case ($odtprevsolucao<$datetime) : $odtprevsolucaox = "<font color='red'  >$odtprevsolucaox</font>"; break;
							case ($odtprevsolucao=$datetime) : $odtprevsolucaox = "<font color='blue' ><b>$odtprevsolucaox</b></font>"; break;
							case ($odtprevsolucao>$datetime) : $odtprevsolucaox = "<font color='blue' >$odtprevsolucaox</font>"; break;
						}
						
						switch($oprioridade){// Status do chamado...
							case NULL: $priori = "Aguardando Classificação"; break;
							case 1: $priori = "URGENTE/CRÍTICO"; break;
							case 2: $priori = "ALTA"; break;
							case 3: $priori = "NORMAL"; break;
							case 4: $priori = "BAIXA";
						}
						
						echo "
						<tr class='subHeader'>
							<td width='2%'><div align='center'>$situacao   </div></td>
							<td width='2%'><div align='center'>$solicitante</div></td>
							<td width='2%'><div align='center'>$atendente  </div></td>
							<td width='7%'><div align='center'>$ocodprojeto </div></td>
							<td width='7%'><div align='center' class='subHeader2'><b>$OCODIGO</b></div></td>
							<td width='5%'><div align='center' title='$cat'>$ocategoria</div></td>
							<td width='5%'><div align='center' title='$priori'>$prioridade</div></td>
							<td width='5%'><div align='center' title='$MDESCRICAO'>$oidmodulo</div></td>
							<td width='5%'><div align='center' title='$RPGRUPO - $RPTITULO'>$orotina</div></td>
							<td width='5%'><div align='center' title='$SRTITULO'>$osubrotina</div></td>		
							<td width='27%' class='subHeader2'><div align='center' title='$odetalhes'>$tipoo $oresumo</div></td>					
							<td width='8%'><div align='center'>$odtaberturax</div></td>
							<td width='8%'><div align='center'>$odtclassificax</div></td>
							<td width='8%'><div align='center'>$odtprevsolucaox</div></td>";				
							
							if($odtinicioatend == NULL){
								echo "<td width='2%'><div align='center'><img src='../imagens/status/AZUL.png' title='NÃO INICIADO PELO ATENDENTE'/></div></td>";
								
							}else if($ocontsolpos > 0 AND $perfil_session != 'USR' AND $oseqinteracoes == $oultintsolpos AND ($osituacao==1 OR $osituacao==4)){
								echo"
								<td width='2%'><div align='center'>
									<input class='inputsrc' type='image' src='../imagens/posicionamento.png' title='Solicitação de Posicionamento em $odtultsolposx'/>
									</div>
								</td>";
							
							}else if(($osituacao == 7 OR $osituacao == 9) AND $osolicitante == $ident_session AND $opsresp == NULL){ //Se solução aceita ou chamado encerrado por falta de retorno...
								echo "
								<td width='2%'><div align='center'>
									<form name='ps' method='POST' action='pesquisa.php'>
										<input name='atendente' type='hidden' value='$oatendente'/>
										<input name='ocodigo'   type='hidden' value='$OCODIGO'/>
										 <input name='pcodigo'  type='hidden' value='$PCODIGO'/>
										<input class='inputsrc' type='image'  src='../imagens/ps.png' title='Responder Pesquisa de Satisfação'/>
									</form>
								</div>
								</td>";
							
							}else{
								echo "<td width='2%'></td>";
							}
							
							//Interagir...
							echo"
							<td width='2%'><div align='center'>
								<form name='ahd' target='_blank' method='POST' action='form_interacao_ocorrencia.php'>
									<input name='ocodigo' type='hidden' value='$OCODIGO'/>
									<input name='acao'    type='hidden' value='ABRIR_PAGINA'>
									<input class='inputsrcM' type='image'  src='../imagens/interagir.png' title='$oultint'/>
								</form>
							</div>
							</td>
							</tr>";
					}
						echo"
					</tbody>
					</table>
					</div>
					</fieldset>";

					}		
					
					if ($acao == 'OSTITULO'){
						$FINCODTITULO2 = $_POST['fincodtitulo'];
						$OSselect = mysql_query("SELECT * FROM ordens WHERE OSFINCODTITULO = '$FINCODTITULO2'") or print(mysql_error());
						
						echo "<fieldset><legend class='subpageName'>&nbsp;&nbsp;Ordens de Serviço constantes no Lançamento Financeiro <b>$FINCODTITULO2</b>&nbsp;&nbsp;</legend>
							<div align='right'>
								<form name='fechar' method='POST' action='form_consulta_fincr.php'>
									<input name='pcodigo'   type='hidden' value='$PCODIGO'/>
									<input class='inputsrc' type ='image' src='../imagens/close.png' title='Fechar'/>
								</form>
							</div>
							<div id='fieldsetheader'>
								<table width='99%' align='center'>
									<thead>
										<tr>
											<th width='2%' ><div align='center'>      </div>         </th>
											<th width='10%'><div align='center'>Número</div>         </th>
											<th width='16%'><div align='center'>Patrocinador</div>   </th>
											<th width='10%'><div align='center'>Projeto</div>        </th>
											<th width='15%'><div align='center'>Duração Total  </div></th>
											<th width='8%' ><div align='center'>Valor</div>          </th>
											<th width='8%' ><div align='center'>Emissão</div>        </th>
											<th width='8%' ><div align='center'>Autorização</div>    </th>
											<th width='8%' ><div align='center'>Limite Fat.</div>    </th>
											<th width='8%' ><div align='center'>Faturamento</div>    </th>
											<th width='2%' ></th>
										</tr>
									</thead>
								</table>
							</div>
							<div id='fieldsetpequeno'>
							<table align='center' width='99%' class='zebrado'>
							<tbody>";
						
						while ($OSlinha = mysql_fetch_array($OSselect)){
							$OSID             = $OSlinha['OSID'];
							$OSNUMERO         = $OSlinha['OSNUMERO'];
							$OSIDPATROCINADOR = $OSlinha['OSIDPATROCINADOR'];
							$OSCODPROJETO     = $OSlinha['OSCODPROJETO'];
							$OSCODCHAMADO     = $OSlinha['OSCODCHAMADO'];
							$OSQTDEHORAS      = $OSlinha['OSQTDEHORAS'];
							$OSVALOR          = $OSlinha['OSVALOR'];
							$OSDTEMISSAO      = $OSlinha['OSDTEMISSAO'];
							$OSDTLIMITEFAT    = $OSlinha['OSDTLIMITEFAT'];
							$OSDTAUTORIZACAO  = $OSlinha['OSDTAUTORIZACAO'];
							$OSDTFATURAMENTO  = $OSlinha['OSDTFATURAMENTO'];
							$OSSTATUS         = $OSlinha['OSSTATUS'];

							if ($OSNUMERO == $OSNUMERO2){
								$OSSELECIONADA = "<tr bgcolor = 'yellow' class='subHeader'>";
							}else{ 
								$OSSELECIONADA = "<tr>";
							}				
							
							$totalminutos  =  $OSQTDEHORAS%60;
							$totalhoras    = ($OSQTDEHORAS-$totalminutos)/60;
							
							$VALORTOTAL = number_format($OSVALOR,2,",",".");//Formatar o Valor.
							
							$osdtemissao     = date("d/m/Y H:i",strtotime($OSDTEMISSAO));
							$osdtautorizacao = date("d/m/Y H:i",strtotime($OSDTAUTORIZACAO));
							$osdtlimitefat   = date("d/m/Y H:i",strtotime($OSDTLIMITEFAT));
							$osdtfaturamento = date("d/m/Y H:i",strtotime($OSDTFATURAMENTO));
							
							switch($OSSTATUS){// Status da oS... 
								case 0: $situacao = "<img src='../imagens/status/AZUL.png'  title='AGUARDANDO AUTORIZAÇÃO DE FATURAMENTO'/>"; break;
								case 1: $situacao = "<img src='../imagens/status/VERDE.png' title='FATURAMENTO AUTORIZADO'/>";  break;
								case 2: $situacao = "<img src='../imagens/status/ROXO.png'  title='CANCELADA'/>";  break;
								case 3: $situacao = "<img src='../imagens/status/PRETO.png' title='FATURADA'/>";  break;
							}
								
							echo"
							$OSSELECIONADA
								<td width='2%' ><div align='center'>$situacao</div></td>
								<td width='10%'><div align='center'>$OSNUMERO</div></td>
								<td width='16%'><div align='center'>$OSIDPATROCINADOR</div></td>
								<td width='10%'><div align='center'>$OSCODPROJETO</div></td>
								<td width='15%'><div align='center'>$totalhoras:$totalminutos Hrs.</div></td>
								<td width='8%' ><div align='center'>R$ $VALORTOTAL</div></td>
								<td width='8%' ><div align='center'>$osdtemissao</div></td>
								<td width='8%' ><div align='center'>$osdtautorizacao</div></td>
								<td width='8%' ><div align='center'>$osdtlimitefat</div></td>
								<td width='8%' ><div align='center'>$osdtfaturamento</div></td>";
								if($OSCODCHAMADO == NULL){
									echo"
									<td width='2%' >
										<div align='center'>
											<form name='detalhes' method='POST' action='form_consulta_fincr.php'>
												<input name='pcodigo'      type='hidden' value='$PCODIGO'/>
												<input name='osnumero'     type='hidden' value='$OSNUMERO'/>
												<input name='pcodigo'      type='hidden' value='$OSCODPROJETO'/>
												<input name='fincodtitulo' type='hidden' value='$FINCODTITULO2'/>
												<input name='acao'         type='hidden' value='ATIVIDADESOS'/>
												<input class='inputsrc'    type='image' src='../imagens/detalhes.png' title='Atividades contidas nesta Ordem de Serviço'/>
											</form>
										</div>
									</td>";
								
								}else{
									echo"
									<td width='2%' >
										<div align='center'>
											<form name='detalhes'  method='POST' action='form_consulta_fincr.php'>
												<input name='pcodigo'   type='hidden' value='$PCODIGO'/>
												<input name='osnumero'  type='hidden' value='$OSNUMERO'/>
												<input name='ocodigo'   type='hidden' value='$OSCODCHAMADO'/>
												<input name='acao'      type='hidden' value='CHAMADO_ORIGEM'/>
												<input class='inputsrc' type='image' src='../imagens/interagir.png' title='Ver chamado origem.'/>
											</form>
										</div>
									</td>";
								}
								echo"
							</tr>";
						}
						echo "
						</tbody>
						</table>
						</div>
						</fieldset>";		
					}
					
				}//Fim das ações...
				
				
				if(isset($_POST['pcodigo']) AND $PCODIGO != ''){
				
					$FINselectR = mysql_query("SELECT * FROM financeiro WHERE FINCODPROJETO = '$PCODIGO' AND FINPAGREC = 1 ORDER BY FINDTVENCIMENTO") or print (mysql_error());
				
				}else if(isset($_POST['accodcontrato']) AND $ACCODCONTRATO != ''){
					
					$FINselectR = mysql_query("SELECT * FROM financeiro WHERE FINCODCONTRATO = '$ACCODCONTRATO' AND FINPAGREC = 1 ORDER BY FINDTVENCIMENTO") or print (mysql_error());
						
				}else{
					
					if($perfil_session == 'ADM'){
				
						$FINselectR = mysql_query("SELECT * FROM financeiro WHERE FINPAGREC = 1 ORDER BY FINDTVENCIMENTO") or print (mysql_error());
					
					}else if($perfil_session == 'GP'){
						
						$FINselectR = mysql_query("SELECT * FROM financeiro WHERE FINPAGREC = 1 AND FINCODPROJETO IN (SELECT UPCODPROJETO FROM usuarios_projeto WHERE UPIDUSUARIO = '$ident_session') ORDER BY FINDTVENCIMENTO") or print (mysql_error());
					
					}else if($perfil_session == 'GPC'){
						
						$FINselectR = mysql_query("SELECT * FROM financeiro WHERE FINPAGREC = 1 AND FINIDPATROCINADOR IN (SELECT UCIDCLIENTE FROM usuarios_clientes WHERE UCIDUSUARIO = '$ident_session' AND UCSITUACAO = 1) ORDER BY FINDTVENCIMENTO") or print (mysql_error());
						
					}
				}

				$total  = mysql_num_rows($FINselectR);
				
				$OSselect = mysql_query("SELECT * FROM ordens WHERE OSCODPROJETO = '$PCODIGO' AND OSSTATUS = 1") or print(mysql_error());
				$OStotal  = mysql_num_rows($OSselect);
				
				?>
				
				
				<div class='panel panel-default'>

					<div class='panel-heading'>
						
						<span class='subpageName'><?php echo $legend;?></span>

						<ul class='pull-right list-inline'>
							<li>
								<ul class='pull-right list-inline'>
									<li><a href='#' title='Ocultar' onclick="recolher_painel('painel_cr')"><i class='fa fa-arrows-v' aria-hidden='true'></i></a></li>
								</ul>
							</li>
						</ul>
						
					</div>

					<div class='panel-body' id='painel_cr'>
				
						<?php

						if($total > 0){
						
							echo"
							<table id='DataTables' width='100%' class='display table-responsive table-condensed table-action table-striped'>
								<thead>		
									<tr>
										<th width='2%' ><div align='center'>      </div>           </th>
										<th width='10%'><div align='center'>ID</div>               </th>
										<th width='10%'><div>Cliente</div>                         </th>
										<th width='8%' ><div align='center'>Mensalidade</div>      </th>
										<th width='7%' ><div align='center'>Parcela</div>          </th>
										<th width='10%'><div align='center'>Valor Original</div>   </th>
										<th width='10%'><div align='center'>Valor Baixado</div>    </th>
										<th width='10%'><div align='center'>Juros/Multa</div>      </th>
										<th width='10%'><div align='center'>Valor atualizado </div></th>
										<th width='10%'><div align='center'>Vencimento    </div>   </th>
										<th width='10%'><div align='center'>Última Baixa</div>     </th>
										<th width='3%' data-orderable='false'>
											<div align='center'>";
												
												if($perfil_session == 'ADM' OR $perfil_session == 'GP'){
													echo"
													<form name='financeiro' method='POST' action='emissao_financeiro.php'>
														<input name='pcodigo'    type='hidden' value='$PCODIGO'/>
														<input name='fintipo'    type='hidden' value='RECEBER'/>
														<input class='inputsrcM' type='image'  src='../imagens/add3.png' title='Gerar Lançamento Financeiro\n$OStotal novas O.S. aptas ao faturamento.'/>
													</form>";
												}
												
												echo"
											</div>             
										</th>
									</tr>
								</thead>
								<tbody>";
								
							while($busca = mysql_fetch_array($FINselectR)){
								$FINID             = $busca["FINID"];
								$FINIDPATROCINADOR = $busca["FINIDPATROCINADOR"];
								$FINCODPROJETO     = $busca["FINCODPROJETO"];
								$FINCODTITULO      = $busca["FINCODTITULO"];
								$FINMENSALIDADE    = $busca["FINMENSALIDADE"];
								$FINPARCELA        = $busca["FINPARCELA"];
								$FINVALORORIGINAL  = $busca["FINVALORORIGINAL"];
								$FINVALORJUROS     = $busca["FINVALORJUROS"];
								$FINVALORMULTA     = $busca["FINVALORMULTA"];
								$FINVALOR          = $busca["FINVALOR"];
								$FINVALORBAIXADO   = $busca["FINVALORBAIXADO"];
								$FINDTVENCIMENTO   = $busca["FINDTVENCIMENTO"];
								$FINSTATUS         = $busca["FINSTATUS"];
								$FINDTBAIXA        = $busca["FINDTBAIXA"];
								
								$FINJUROSMULTA = $FINVALORJUROS+$FINVALORMULTA;
								
								$valororiginal = number_format($FINVALORORIGINAL,2,",",".");//Formatar o Valor.
								$valorjm       = number_format($FINJUROSMULTA,2,",",".");//Formatar o Valor.
								$valorbaixado  = number_format($FINVALORBAIXADO,2,",",".");//Formatar o Valor.
								$valor         = number_format($FINVALOR,2,",",".");//Formatar o Valor.
										
								$verdtvenc = date("Y-m-d", strtotime($FINDTVENCIMENTO));
								$dtvenc = date("d/m/Y", strtotime($FINDTVENCIMENTO));
								
								if($verdtvenc < $hoje AND $FINSTATUS == 1){
									$dtvenc = "<font color='red'><b>$dtvenc</b></font>";
								}
								
								if($FINDTBAIXA != NULL){
									$dtbaixa  = date("d/m/Y H:i", strtotime($FINDTBAIXA));
								
								}else{
									$dtbaixa = '';
								}
								
								$hoje = date('Y-m-d');
								
								if ($FINCODTITULO == $FINCODTITULO2){
									$TITULOSELECIONADO =  "<tr bgcolor = 'yellow'>";
								
								}else{ 
									$TITULOSELECIONADO =  "<tr>";
								}
								
								switch($FINSTATUS){// Status do Projeto... 
									case 0: $finsituacao = "<img src='../imagens/status/AZUL.png'    title='EM ABERTO'/>"; break;
									case 1: $finsituacao = "<img src='../imagens/status/AMARELO.png' title='BAIXADO PARCIALMENTE'/>"; break;
									case 2: $finsituacao = "<img src='../imagens/status/ROXO.png'    title='CANCELADO'/>"; break;
									case 3: $finsituacao = "<img src='../imagens/status/PRETO.png'   title='BAIXADO por ACORDO'/>"; break;
									case 4: $finsituacao = "<img src='../imagens/status/PRETO.png'   title='BAIXADO por FATURA'/>"; break;
									case 5: $finsituacao = "<img src='../imagens/status/PRETO.png'   title='BAIXADO'/>";
								}
								
								$select2 = mysql_query("SELECT PARAZAONOME FROM patrocinadores WHERE PAIDENTIFICADOR = '$FINIDPATROCINADOR'") or print(mysql_error());
								$PANOME = mysql_result($select2,0,"PARAZAONOME");
								
								echo"
								$TITULOSELECIONADO
									<td data-order='$FINSTATUS'><div align='center'>$finsituacao</div></td>
									<td><div align='center'>$FINCODTITULO</div></td>
									<td><div title='Razão Social: $PANOME'>$FINIDPATROCINADOR</div></td>
									<td><div align='center'>$FINMENSALIDADE</div></td>
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
														<ul>
															<li>
																<form name='detalhes' method='POST' action='form_consulta_fincr.php'>
																	<input name='pcodigo'       type='hidden' value='$PCODIGO'/>
																	<input name='accodcontrato' type='hidden' value='$ACCODCONTRATO'/>
																	<input name='fincodtitulo'  type='hidden' value='$FINCODTITULO'/>
																	<input name='acao'          type='hidden' value='OSTITULO'/>
																	<input class='inputsrc'     type='image' src='../imagens/detalhes.png' title='Detalhes deste Título'/>
																</form>
															</li>";
									
															if($FINSTATUS <= 1 AND ($perfil_session == 'ADM' OR $perfil_session == 'GP')){
																echo"
																<li>
																	<form name='baixar' method='POST' action='form_consulta_fincr.php'>
																		<input name='pcodigo'       type='hidden' value='$PCODIGO'/>
																		<input name='accodcontrato' type='hidden' value='$ACCODCONTRATO'/>
																		<input name='fincodtitulo'  type='hidden' value='$FINCODTITULO'/>
																		<input name='finvalor'      type='hidden' value='$FINVALOR'/>
																		<input name='finpagrec'     type='hidden' value='$FINPAGREC'/>
																		<input name='acao'          type='hidden' value='BAIXAR'/>
																		<input class='inputsrc'     type='image' src='../imagens/baixar.png' title='Baixar Lançamento'/>
																	</form>
																</li>";
															}
															
															if ($FINSTATUS <= 1 AND ($perfil_session == 'TS' OR $perfil_session == 'GPC')){
																echo"
																<li>
																	<form target='_blank' name='pagar' method='POST' action='form_consulta_fincr.php'>
																		<input name='pcodigo'       type='hidden' value='$PCODIGO'/>
																		<input name='accodcontrato' type='hidden' value='$ACCODCONTRATO'/>
																		<input name='idlan'         type='hidden' value='$FINID'/>
																		<input name='acao'          type='hidden' value='PAGAR'/>
																		<input class='inputsrc'     type='image'  src='../imagens/boletow.png' title='Pagar'/>
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
							echo "<div align='center' class='info'>Nenhum Lançamento Financeiro encontrado.</div>";
							
							if($perfil_session == 'ADM' OR $perfil_session == 'GP'){
								echo"
								<div align='center'>
									<form name='financeiro' method='POST' action='emissao_financeiro.php'>
										<input name='pcodigo'    type='hidden' value='$PCODIGO'/>
										<input name='fintipo'    type='hidden' value='RECEBER'/>
										<input class='inputsrcM' type='image'  src='../imagens/add3.png' title='Gerar Lançamento Financeiro\n$OStotal novas O.S. aptas ao faturamento.'/>
									</form>
								</div>";
							}
						}
						
				echo "</div>
				</div>";

				?>

				
		</div>
	</body>
</html>