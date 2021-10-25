  <?php
	
	/*$ACLASSIFICARtotal = NULL;
	
	$ACLASSIFICARselect = mysqli_query($con,"SELECT * FROM ocorrencias WHERE OSITUACAO = 0") or print (mysqli_error());
	$ACLASStotal  = mysqli_num_rows($ACLASSIFICARselect);
	
	//Inertes...
	$INERTEStotal  = NULL;
	
	$INERTESselect = mysqli_query($con,"SELECT * FROM ocorrencias WHERE ODTULTINT < (CURRENT_DATE()-$PRMSGA0001) AND OSITUACAO IN(2,5,7)") or print(mysqli_error());
	$INTtotal = mysqli_num_rows($INERTESselect);
	
	//Badges Tickets EM ANDAMENTO...
	
	$EMANDAMENTOtotal  = NULL;
	$EMANDAMENTO1total = NULL;
	$EMANDAMENTO2total = NULL;
	$EMANDAMENTO3total = NULL;
	$EMANDAMENTO4total = NULL;
	$EMANDAMENTO5total = NULL;
	$EMANDAMENTO7total = NULL;	
	
	$EMANDAMENTOselect = mysqli_query($con,"SELECT * FROM ocorrencias WHERE OSITUACAO BETWEEN 1 AND 7") or print(mysqli_error());
	
	$EMANDAMENTO1select = mysqli_query($con,"SELECT * FROM ocorrencias WHERE OSITUACAO = 1") or print(mysqli_error());
	$EMANDAMENTO2select = mysqli_query($con,"SELECT * FROM ocorrencias WHERE OSITUACAO = 2") or print(mysqli_error());
	$EMANDAMENTO3select = mysqli_query($con,"SELECT * FROM ocorrencias WHERE OSITUACAO = 3") or print(mysqli_error());
	$EMANDAMENTO4select = mysqli_query($con,"SELECT * FROM ocorrencias WHERE OSITUACAO = 4") or print(mysqli_error());
	$EMANDAMENTO5select = mysqli_query($con,"SELECT * FROM ocorrencias WHERE OSITUACAO = 5") or print(mysqli_error());
	$EMANDAMENTO7select = mysqli_query($con,"SELECT * FROM ocorrencias WHERE OSITUACAO = 7") or print(mysqli_error());
	
	$EAtotal  = mysqli_num_rows($EMANDAMENTOselect);
	$EA1total = mysqli_num_rows($EMANDAMENTO1select);
	$EA2total = mysqli_num_rows($EMANDAMENTO2select);
	$EA3total = mysqli_num_rows($EMANDAMENTO3select);
	$EA4total = mysqli_num_rows($EMANDAMENTO4select);
	$EA5total = mysqli_num_rows($EMANDAMENTO5select);
	$EA7total = mysqli_num_rows($EMANDAMENTO7select);
	
	if($ACLASStotal > 0){ $ACLASSIFICARtotal = "&nbsp;&nbsp;<span class='badge' style='background-color: blue;  color: white;'>$ACLASStotal</span>";}
	
	if($EAtotal > 0) { $EMANDAMENTOtotal  = "&nbsp;&nbsp;<span class='badge' style='background-color: black;  color: white;'>$EAtotal</span>"; }
	if($EA1total > 0){ $EMANDAMENTO1total = "&nbsp;&nbsp;<span class='badge' style='background-color: orange; color: white;'>$EA1total</span>"; }
	if($EA2total > 0){ $EMANDAMENTO2total = "&nbsp;&nbsp;<span class='badge' style='background-color: yellow; color: black;'>$EA2total</span>"; }
	if($EA3total > 0){ $EMANDAMENTO3total = "&nbsp;&nbsp;<span class='badge' style='background-color: orange; color: white;'>$EA3total</span>"; }
	if($EA4total > 0){ $EMANDAMENTO4total = "&nbsp;&nbsp;<span class='badge' style='background-color: red;    color: white;'>$EA4total</span>"; }
	if($EA5total > 0){ $EMANDAMENTO5total = "&nbsp;&nbsp;<span class='badge' style='background-color: gray;   color: white;'>$EA5total</span>"; }
	if($EA7total > 0){ $EMANDAMENTO7total = "&nbsp;&nbsp;<span class='badge' style='background-color: green;  color: white;'>$EA7total</span>"; }
	
	if($INTtotal > 0){ $INERTEStotal = "&nbsp;&nbsp;<span class='badge'>$INTtotal</span>";}
	
	//Apontamentos aptos ao Faturamento...
	$APMENUtotal = NULL;
		
	if($apl_session == 'SGSD'){
		$APMENUselect = mysqli_query($con,"SELECT * FROM apontamentos WHERE APSTATUS = 0") or print(mysqli_error());		
	}
	
	$OAPTMtotal  = mysqli_num_rows($APMENUselect);
	
	if($OAPTMtotal > 0){ $APMENUtotal = "&nbsp;&nbsp;<span class='badge' style='background-color: green;  color: white;'>$OAPTMtotal</span>";}
	
	//Ordens de Serviço autorizadas, aptas ao faturamento...	
	$OSMENUtotal = NULL;
	
	$OSMENUselect = mysqli_query($con,"SELECT OSNUMERO FROM ordens WHERE OSSTATUS = 1") or print(mysqli_error());
	$OSMtotal  = mysqli_num_rows($OSMENUselect);
	
	if($OSMtotal > 0){ $OSMENUtotal = "&nbsp;&nbsp;<span class='badge' style='background-color: green;  color: white;'>$OSMtotal</span>";}*/
	
	$MSGNaoLidas = 0;
	
	?>
	
	<form id='menuos' name='ordens' method='POST' action='form_consulta_os.php'>
		<input name='origem' type='hidden' value='MENU'/>
	</form>

	<nav class='navbar navbar-default navbar-fixed-top'>
		<div class='container-fluid'>
			<div class='navbar-header'>
				<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1' aria-expanded='false'>
					<span class='icon-bar'></span>
					<span class='icon-bar'></span>
					<span class='icon-bar'></span>
				</button>
				<?php //Logo do Cliente...
					
					echo "<a class='navbar-brand' href='https://wishbaby.com.br' target='_blank'><img class='imgnatural' id='site-logo' src='../images/LogoHeader.png' alt='WISHBABY'/></a>";
				
				?>
			</div>
			
			<?php
			
			$ExibirMenu = 1;
		
			if(isset($_GET['menu'])){
				$ExibirMenu = $_GET['menu'];			
			}
				
			if(isset($_POST['menu'])){
				$ExibirMenu = $_POST['menu'];			
			}
		
			if($ExibirMenu == 1){ ?>
			
				<div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
					<ul class='nav navbar-nav'>
						<li></li>
						<li><a href='home.php'>Home</a></li>
						<li class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>Cadastros<span class='caret'></span></a>
							<ul class='dropdown-menu'>
								<li><a href='form_cadastro_administradores.php'>Administradoras </a></li>
								<li><a href='form_cad_providers.php'>Fornecedores               </a></li>
								<li><a href='form_cadastro_patrocinadores.php'>Clientes         </a></li>
								<li><a href='form_cadastro_interessados.php'>Pessoas            </a></li>								
								<li><a href='form_cadastro_recursos.php'>Agentes de Atendimento </a></li>
								<li><a href='form_cadastro_modulos.php'>Estrutura operacional   </a></li>
								<li><a href='form_cadastro_equipes.php'>Equipes                 </a></li>
								<li><a href='form_cadastro_sla.php'>Acordos de Nível de Serviço (<i>SLA</i>)</a></li>
								<li><a href='form_modelos_ps.php' title='Modelos de Pesquisas de Satisfação'>Modelos de PS</a></li>
								<li class='divider'></li>
								<li><a href='form_cad_products.php'><i>Products</i></a></li>
								<li class='divider'></li>
								<li><a href='#'>Tabelas auxiliares<span class='caret'></span></a>
									<ul class='dropdown-menu'>
										<li class='dropdown-header'>Shop</a></li>
										<li><a href='form_cad_departments.php'><i>Departments</i></a></li>
										<li><a href='form_cad_categories.php'><i>Categories</i></a></li>
										<li><a href='form_cad_brands.php'><i>Brands</i></a></li>
										<li><a href='form_cad_colors.php'><i>Colors</i></a></li>
										<li><a href='form_cad_genders.php'><i>Genders</i></a></li>
										<li><a href='form_cad_groups.php'><i>Groups</i></a></li>
										<li><a href='form_cad_sizes.php'><i>Sizes</i></a></li>
										<li><a href='form_cad_financial_natures.php'><i>Fin. Natures</i></a></li>
										<li><a href='form_cad_prmcodes.php'><i>Promotional Codes</i></a></li>
										<li><a href='form_cad_segments.php'><i>Segments</i></a></li>
										<li class='divider'></li>
										<li><a href='form_cadastro_categorias.php'>Categorias de Atendimento</a></li>
										<li><a href='form_condicoes_pagamento.php'>Condições de Pagamento   </a></li>
										<li><a href='form_cadastro_segmentos.php'>Segmentos                 </a></li>
										<li><a href='form_cadastro_excecoes.php'>Exceções de Calendário     </a></li>
										<li><a href='form_cadastro_municipios.php'>Municípios</a></li>
									</ul>
								</li>
								<li><a href='form_cadastro_usuarios.php'>    Usuários                  </a></li>
							</ul>
						</li>
						
						<?php 
						
							if($dominio_session == 'cpanel'){ 
							
								echo "
								<li class='dropdown'><a href='#'>Gestão<span class='caret'></span></a>
									<ul class='dropdown-menu'>
										<li><a href='form_cadastro_dominios.php'> Domínios</a></li>
										<li><a href='form_cadastro_contratos.php'> Contratos</a></li>
									</ul>
								</li>";
							}
						
						?>
								
						<li class='dropdown'><a href='#'>Tickets<span class='caret'></span></a>
							<ul class='dropdown-menu'>	
								<li><a href='form_abertura_ticket.php'><i class='fa fa-file-o' aria-hidden='true'></i>&nbsp;&nbsp;<b>Novo</b></a></li>
								<li class='divider'></li>
								<li><a href='form_consulta_classificar.php'>A classificar<?php echo $ACLASSIFICARtotal;?></a></li>
								
								<li><a href='form_consulta_ticket.php'>Em andamento<?php echo $EMANDAMENTOtotal;?><span class='caret'></span></a>
									<ul class='dropdown-menu'>
										<li><a href='form_consulta_ticket.php?s=1'>Pendentes AGENTE     <?php echo $EMANDAMENTO1total;?></a></li>
										<li><a href='form_consulta_ticket.php?s=2'>Pendentes SOLICITANTE<?php echo $EMANDAMENTO2total;?></a></li>
										<li><a href='form_consulta_ticket.php?s=3'>Pendentes TERCEIROS  <?php echo $EMANDAMENTO3total;?></a></li>
										<li><a href='form_consulta_ticket.php?s=4'>Soluções RECUSADAS   <?php echo $EMANDAMENTO4total;?></a></li>
										<li><a href='form_consulta_ticket.php?s=5'>Soluções PENDENTES   <?php echo $EMANDAMENTO5total;?></a></li>
										<li><a href='form_consulta_ticket.php?s=7'>Soluções ACEITAS     <?php echo $EMANDAMENTO7total;?></a></li>
									</ul>
								</li>
								
								<li title='INERTES: Sem interação do Solicitante há mais de N dias.'><a href='form_encerra_auto.php'>Inertes<?php echo $INERTEStotal;?></a></li>
								<li title='Aplicar uma mesma interação em diversos Tickets simultaneamente'><a href='form_selecao_solucionar.php'>Interação simultânea</a></li>
								<li class='divider'></li>
								<li><a href='FiltroPesquisarTickets.php'><i class='fa fa-search'></i>&nbsp;Pesquisar</a></li>
								<li class='divider'></li>
								<li><a href='form_consulta_apontamentos.php'>Apontamentos<?php echo $APMENUtotal;?></a></li>
								<li><a href='#' onclick="document.getElementById('menuos').submit();">Ordens de Serviço<?php echo $OSMENUtotal;?></a></li>							  
							</ul>
						</li>
						
						<li class='dropdown'><a href='#'>Financeiro<span class='caret'></span></a>
							<ul class='dropdown-menu'>
								<li><a href='form_consulta_sp.php'>Solicitações de Pagamento</a></li>
								<li><a href='form_consulta_fincp.php'>Lançamentos a Pagar</a></li>
								<li><a href='form_consulta_fincr.php'>Lançamentos a Receber</a></li>
							</ul>
						</li>
						
						<li class='dropdown'><a href='#'>Utilitários<span class='caret'></span></a>
							<ul class='dropdown-menu'>
								<li><a href='#'>Configurações<span class='caret'></span></a>
									<ul class='dropdown-menu'>	
										<li><a href='dicionario_dados.php'>Dicionário de Dados</a></li>								
										<li><a href='parametros.php'>Parâmetros da Aplicação  </a></li>
										<li><a href='mensagens_auto.php'>Mensagens automáticas</a></li>
										<li><a href='schedules.php'><i>Schedules (Cron)</i>   </a></li>
									</ul>
								</li>
								<li><a href='filtro_busca_recurso.php'>Buscar recursos          </a></li>
								<li><a href='importar_municipios.php'> Importar Municípios IBGE </a></li>
								<li><a href='teste_email.php'>         Teste de e-mail          </a></li>
								<li><a href='teste_phpmailer.php'>         Teste PHPMailer      </a></li>
								<li><a href='teste_arquivo.php'>       Teste de Upload          </a></li>
								<li><a href='executar_sql.php'>    Comandos SQL                 </a></li>
								<li><a href='#'>Simuladores<span class='caret'></span></a>
									<ul class='dropdown-menu'>
										<li><a href='teste_cep.php'>Carregamento por CEP</a></li>
										<li><a href='simulador_calculosla.php'>Cálculo conforme SLA</a></li>
										<li><a href='simulador_ppg.php'>Plano de Pagamento</a></li>
										<li><a href='simulador_wsrfb.php'>Consulta CNPJ RFB</a></li>
									</ul>
								</li>
								<li><a href='#'>Aceleradores<span class='caret'></span></a>
									<ul class='dropdown-menu'>
										<li><a href='importar_feriados.php'>Feriados Nacionais</a></li>
									</ul>
								</li>
								<li><a href='filtro_sessoes.php'>Sessões de Usuário</a></li>							
								
								<?php
								
									if($ident_session == 'mestre'){
										echo"
										<li><a href='#'>Manutenção<span class='caret'></span></a>
											<ul class='dropdown-menu'>	
												<li><a href='manutencao_comunicar.php'>Comunicar</a></li>
												<li><a href='manutencao_ar.php'>Estado site</a></li>								
											</ul>
										</li>";							
									}
								
								?>
							</ul>
						</li>
						
						<li class='dropdown'><a href='#'>Indicadores<span class='caret'></span></a>
							<ul class='dropdown-menu'>
								<li><a href='#'>Quantitativos por período<span class='caret'></span></a>
									<ul class='dropdown-menu'>
										<li><a href='filtro_ind_categoria.php'>por Categoria</a></li>
										<li><a href='filtro_ind_modulo.php'>por Módulo</a></li>
										<li><a href='filtro_ind_cliente.php'>por Cliente</a></li>
										<li><a href='filtro_ind_solicitante.php'>por Solicitante</a></li>									
									</ul>
								</li>   
								<li><a href='#'>Tempo Médio de Interação<span class='caret'></span></a>
									<ul class='dropdown-menu'>
										<li><a href='filtro_ind_tmr.php'>Sintético</a></li>
										<li><a href='filtro_linha_tempo.php'>Analítico</a></li>	
									</ul>
								</li>
								<li><a href='#'>Curvas<span class='caret'></span></a>
									<ul class='dropdown-menu'>
										<li><a href='filtro_curva_tms.php'>TMS (Tempo Médio de Solução)</a></li>
									</ul>
								</li> 							
								<li class='divider'></li>
								<li><a href='#'>SLA<span class='caret'></span></a>
									<ul class='dropdown-menu'>
										<li><a href='filtro_apuracao_sla.php'>Análise por Métrica</a></li>
										<li title='Selecione a Categoria...'><a href=''>Métrica X Categoria</a></li>
										<li title='Selecione o Módulo...'><a href=''>Métrica X Módulo</a></li>
										<li title='Selecione a Equipe...'><a href=''>Métrica X Equipe</a></li>
										<li title='Selecione o Agente...'><a href=''>Métrica X Agente</a></li>
									</ul>
								</li>
								<li class='divider'></li>
								<li><a href='filtro_ind_pesquisas.php'> Pesquisas de Satisfação</a></li>
							</ul>	
						</li>			
						
						<li class='dropdown'><a href='#'>Conhecimento<span class='caret'></span></a>
							<ul class='dropdown-menu'>	
								<li><a href='form_cadastro_bc.php'> Cadastrar </a></li>
								<li><a href='filtrobc_consulta.php'>Pesquisar soluções</a></li>
							</ul>
						</li>	

						<li class='dropdown'><a href='#'>Histórico<span class='caret'></span></a>
							<ul class='dropdown-menu'>	
								<li title='TICKETS: Consultar tickets encerrados/cancelados por período'><a href='filtro_historico_tickets.php'>Tickets</a></li>
								<li title='APONTAMENTOS: Consultar apontamentos apurados por período'><a href='filtro_historico_apontamentos.php'>Apontamentos</a></li>
								<li title='ORDENS DE SERVIÇO: Consultar Ordens de Serviço faturadas por período'><a href='filtro_historico_os.php'>Ordens de Serviço</a></li>
								<li title='FINANCEIRO: Consultar Títulos baixados por período'><a href='filtro_historico_financeiro.php'>Financeiro</a></li>
								<li title='NOTIFICAÇÕES: Consultar notificações lidas por período'><a href='filtro_historico_notificacoes.php'>Notificações</a></li>							
							</ul>
						</li>

						<li class='dropdown'><a href='#'>Relatórios<span class='caret'></span></a>
							<ul class='dropdown-menu'>	
								<li><a href='ExportarTicketsExcel.php'>Listagem de Tickets (Excel)</a></li>
								
							</ul>
						</li>						
					</ul>
					
					<ul class='nav navbar-nav navbar-right'>
						
						<?php
								
							if($PRMGLB0009 == 1 AND $ident_session == 'mestre'){
								echo"
								<li>
									<form class='form-inline' method='POST' action='manutencao_site.php'>
										<div class='form-group'>
											<div class='col-sm-12 col-md-12 col-xs-12'>
												<input name='acao' type='hidden' value='DESBLOQUEAR'/>
												<button type='submit' class='btn btn-info btn-xs'>Desbloquear site</button>
											</div>
										</div>
									</form>
								</li>";							
							}
							
							if($PRMGLB0009 == 0 AND $ident_session == 'mestre'){
								echo"
								<li>
									<form class='form-inline' method='POST' action='manutencao_site.php'>
										<div class='form-group'>
											<div class='col-sm-12 col-md-12 col-xs-12'>
												<input name='acao' type='hidden' value='BLOQUEAR'/>
												<button type='submit' class='btn btn-warning btn-xs'>Bloquear para manutenção</button>
											</div>
										</div>
									</form>							
								</li>";							
							}
						
						?>
						
						<li class='dropdown'><a href='#'><?php echo $login_icone;?>&nbsp;&nbsp;<font class='subHeader2'><?php echo $login_nome;?></font></a>
							
							<ul class='dropdown-menu'>	
								<li><a href='form_consulta_perfil.php'><i class='fa fa-user' aria-hidden='true'></i>&nbsp;&nbsp;Perfil de Usuário</a></li>
								<li><a href='form_consulta_mensagens.php?acao=entrada'><i class='fa fa-envelope' aria-hidden='true'></i>&nbsp;&nbsp;Notificações&nbsp;&nbsp;<span class='badge badge-inverse'><?php echo $MSGNaoLidas;?></span></a></li>
								
								<li class='divider'></li>
								<li class='dropdown-header'>Dados em sessão:</a></li>
								<li class='disabled'><a href='#'><i class='fa fa-cloud' aria-hidden='true'></i>&nbsp;&nbsp;Domínio: <b><?php echo $dominio_session;?></b></a></li>
								<li class='disabled'><a href='#'><i class='fa fa-cloud' aria-hidden='true'></i>&nbsp;&nbsp;Login: <b><?php echo $ident_session;?></b></a></li>
								<li class='disabled'><a href='#'><i class='fa fa-cloud' aria-hidden='true'></i>&nbsp;&nbsp;Perfil: <b><?php echo $perfil_session;?></b></a></li>
								<li class='disabled'><a href='#'><i class='fa fa-university' aria-hidden='true'></i>&nbsp;&nbsp;Administradora: <b><?php echo $adm_session;?></b></a></li>
								<li class='disabled'><a href='#'><i class='fa fa-laptop' aria-hidden='true'></i>&nbsp;&nbsp;Aplicação: <b><?php echo $apl_session;?></b></a></li>
								<li class='disabled'><a href='#'><i class='fa fa-handshake-o' aria-hidden='true'></i>Assinatura: <b><?php echo $cnt_session;?></b></a></li>
								<li class='divider'></li>
								<li><a href='contexto.php'>&nbsp;&nbsp;Alterar contexto</a></li>
								<li class='divider'></li>
								<li><a href='../logout.php?id_session=<?php echo $id_session;?>&dominio=<?php echo $dominio_session;?>' onclick='return encerrar_sessao()'><i class='fa fa-sign-out' aria-hidden='true'></i>&nbsp;&nbsp;Sair</a></li>
							</ul>
							
						</li>
					</ul>
				</div>	

				<?php

			}

			?>			
		</div>
	</nav>