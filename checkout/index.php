	<?php include 'header.php'; 

		//Se está logado e possui carrinho em sessão...
		
		if(!isset($_SESSION['bbdcartkey'])){
			
			echo "<script>location.href='https://babadroom.com';</script>";
			
			exit;
			
		}
		
		if($LOGADO){
			
			echo "<script>location.href='details.php';</script>";
			
		}		
		
		if(isset($_POST['action'])) {
				
			$action = $_POST['action'];
			
			if($action == "CADASTRAR"){					
				
				$LHREFwarning = "https://babadroom.com/checkout";
				$LHREFsuccess = "https://babadroom.com/checkout/details.php";
				
				include '../actions/cadastrar.php';

			}					
			
			if($action == "LOGIN"){
				
				$LHREFwarning = "https://babadroom.com/checkout";
				$LHREFsuccess = "https://babadroom.com/checkout/details.php";
		
				include '../actions/login.php';
				
			}
			
		} //Fim de actions...
		
	?>

   <!-- Page Title (Shop)-->
	<div class='page-title-overlap bg-dark pt-4'>
		<div class='container d-lg-flex justify-content-between py-2 py-lg-3'>
			
			<div class='order-lg-1 pr-lg-4 text-center text-lg-left'>
				<h1 class='h3 text-light mb-0'>Checkout <small>Autenticação</small></h1>
			</div>
			
		</div>
	</div>

	<!-- Page Content-->
	<div class='container py-4 py-lg-5 my-4'>
		
		<div class='row'>
		
			<div class='col-md-6'>
			  
				<div class='card border-0 box-shadow'>
					<div class='card-body'>
					
						<h2 class='h4 mb-1' style='color:#7c94c4;'>Já possuo conta</h2>
						<div class='py-3'>
							<p class='font-size-sm text-muted mb-4'>Informe seus dados de acesso</p>
							<!--<div class='d-inline-block align-middle'>							
								<a class='social-btn sb-google mr-2 mb-2'   href='#' data-toggle='tooltip' title='Sign in with Google'><i class='czi-google'></i></a>
								<a class='social-btn sb-facebook mr-2 mb-2' href='#' data-toggle='tooltip' title='Sign in with Facebook'><i class='czi-facebook'></i></a>
								<a class='social-btn sb-twitter mr-2 mb-2'  href='#' data-toggle='tooltip' title='Sign in with Twitter'><i class='czi-twitter'></i></a>
							</div>-->
						</div>
						<hr>
						
						<form class='needs-validation' method='POST' action='index.php'>
						
							<div class='input-group-overlay form-group'>
								<div class='input-group-prepend-overlay'><span class='input-group-text'><i class='far fa-envelope'></i></span></div>
								<input class='form-control prepended-form-control' name='email' type='email' placeholder='Email' required />
							</div>
							
							<div class='input-group-overlay form-group'>
								<div class='input-group-prepend-overlay'><span class='input-group-text'><i class='fas fa-user-lock'></i></span></div>
								<div class='password-toggle'>
									<input class='form-control prepended-form-control' name='senha' type='password' placeholder='Senha' required />
									<label class='password-toggle-btn'>
										<input class='custom-control-input' type='checkbox'><i class='far fa-eye password-toggle-indicator'></i><span class='sr-only'>Mostrar</span>
									</label>
								</div>
							</div>
							
							<div class='d-flex flex-wrap justify-content-between'>
								<div class='custom-control custom-checkbox'>
									<input class='custom-control-input' type='checkbox' checked id='remember_me'/>
									<label class='custom-control-label' for='remember_me'>Lembrar-me</label>
								</div>
								<a class='nav-link-inline font-size-sm' href='account-password-recovery.html'>Esqueceu a senha?</a>
							</div>
							
							<hr class='mt-4'>
							<div class='text-right pt-4'>
							  <input type='hidden' name='action' value='LOGIN'/>
								<button class='btn btn-primary' type='submit'><i class='fas fa-sign-in-alt mr-2 ml-n21'></i>&nbsp;Avançar</button>
							</div>
							
						</form>
						
					</div>
				</div>
				
			</div>
				
			<div class='col-md-6 pt-4 mt-3 mt-md-0'>
			
				<h2 class='h4 mb-3'><span style='color:#c44c9c;'>Não possui conta?</span><span style='color:#7c94c4;'> Cadastre-se</span></h2>
				<p class='font-size-sm text-muted mb-4'>O registro leva menos de um minuto, mas oferece controle total sobre seus pedidos.</p>
				
				<form class='needs-validation' method='POST' action='index.php'>
				
					<div class='row'>
						
						<div class='col-sm-12'>
							<div class='form-group'>
								<label for='reg-fn'>Nome Completo</label>
								<input class='form-control' name='ctmnome' type='text' required id='reg-fn'/>
								<div class='invalid-feedback'>Informe seu nome completo, sem abreviações!</div>
							</div>
						</div>
						
						<div class='col-sm-12'>
							<div class='form-group'>
								<label for='reg-email'>O e-mail</label>
								<input class='form-control' name='ctmemail' type='email' required id='reg-email'/>
								<div class='invalid-feedback'>Informe seu melhor e-mail!</div>
							</div>
						</div>
						
						<div class='col-sm-6'>
							<div class='form-group'>
								<label for='reg-phone'>Celular</label>
								<input class='form-control' type='tel' name='ctmtel1' required id='reg-phone'/>
								<div class='invalid-feedback'>Informe seu número de telefone com DDD!</div>
							</div>
						</div>
						
						<div class='col-sm-6'>
							<div class='form-group'>
								<label for='reg-phone'>CEP</label>
								<input class='form-control' type='tel' name='ctmcep' required id='reg-cep'/>
								<div class='invalid-feedback'>Informe um CEP válido!</div>
							</div>
						</div> 
						
						<div class='col-sm-6'>
							<div class='form-group'>
								<label for='reg-password'>Crie uma senha</label>
								<input class='form-control' type='password' required name='usersenha1' maxlength='11' id='reg-password' title='Crie uma senha alfanumérica com no máximo 11 caracteres'/>
								<div class='invalid-feedback'>Please enter password!</div>
							</div>
						</div>
						
						<div class='col-sm-6'>
							<div class='form-group'>
								<label for='reg-password-confirm'>Confirme sua senha</label>
								<input class='form-control' type='password' required name='usersenha2' maxlength='11' id='reg-password-confirm'/>
								<div class='invalid-feedback'>Passwords do not match!</div>
							</div>
						</div>
						
					</div>
					
					<div class='text-right'>
					  <input type='hidden' name='ctmidentificador' value='<?php echo uniqid();?>'/>
						<input type='hidden' name='action' value='CADASTRAR'/>
						<button class='btn btn-primary' type='submit'><i class='fas fa-sign-in-alt mr-2 ml-n1'></i>&nbsp;Confirmar e Avançar</button>
					</div>					
					
				</form>
				
			</div>
			
		</div>
		
	</div>
		
	<?php include 'footer.php'; ?>