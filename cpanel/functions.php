<?php

	function valida_CPF($cpf){
	 
		// Verifica se um número foi informado
		if(empty($cpf)) {
			return false;
		}

		// Elimina possivel mascara
		$cpf = ereg_replace('[^0-9]', '', $cpf);
		$cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
		 
		// Verifica se o numero de digitos informados é igual a 11 
		if (strlen($cpf) != 11) {
				return false;
		}
		// Verifica se nenhuma das sequências invalidas abaixo 
		// foi digitada. Caso afirmativo, retorna falso
		else if ($cpf == '00000000000' || 
			$cpf == '11111111111' || 
			$cpf == '22222222222' || 
			$cpf == '33333333333' || 
			$cpf == '44444444444' || 
			$cpf == '55555555555' || 
			$cpf == '66666666666' || 
			$cpf == '77777777777' || 
			$cpf == '88888888888' || 
			$cpf == '99999999999') {
			return false;
		 // Calcula os digitos verificadores para verificar se o
		 // CPF é válido
		 } else {   
				 
				for ($t = 9; $t < 11; $t++) {
						 
					for ($d = 0, $c = 0; $c < $t; $c++) {
							$d += $cpf{$c} * (($t + 1) - $c);
					}
					
					$d = ((10 * $d) % 11) % 10;
					
					if ($cpf{$c} != $d) {
						return false;
					}
				}
			return true;
		}
	} 

	function valida_CNPJ($cnpj){
		// Deixa o CNPJ com apenas números
		$cnpj = preg_replace( '/[^0-9]/', '', $cnpj);
		
		// Garante que o CNPJ é uma string
		$cnpj = (string)$cnpj;
		
		// Verifica se o numero de digitos informados é igual a 11 
		if(strlen($cnpj) != 14){
			return false;
		}
		// Verifica se nenhuma das sequências invalidas abaixo 
		// foi digitada. Caso afirmativo, retorna falso
		else if(	$cnpj == '00000000000000' || 
							$cnpj == '11111111111111' || 
							$cnpj == '22222222222222' || 
							$cnpj == '33333333333333' || 
							$cnpj == '44444444444444' || 
							$cnpj == '55555555555555' || 
							$cnpj == '66666666666666' || 
							$cnpj == '77777777777777' || 
							$cnpj == '88888888888888' || 
							$cnpj == '99999999999999') {
			return false;
		 // Calcula os digitos verificadores para verificar se o CNPJ é válido...
		 
		 } else {   
		
				// O valor original
				$cnpj_original = $cnpj;
				
				// Captura os primeiros 12 números do CNPJ
				$primeiros_numeros_cnpj = substr( $cnpj, 0, 12 );
				
				/**
				 * Multiplicação do CNPJ
				 *
				 * @param string $cnpj Os digitos do CNPJ
				 * @param int $posicoes A posição que vai iniciar a regressão
				 * @return int O
				 *
				 */
				if ( ! function_exists('multiplica_cnpj') ) {
					function multiplica_cnpj( $cnpj, $posicao = 5 ) {
						// Variável para o cálculo
						$calculo = 0;
						
						// Laço para percorrer os item do cnpj
						for ( $i = 0; $i < strlen( $cnpj ); $i++ ) {
								// Cálculo mais posição do CNPJ * a posição
								$calculo = $calculo + ( $cnpj[$i] * $posicao );
								
								// Decrementa a posição a cada volta do laço
								$posicao--;
								
								// Se a posição for menor que 2, ela se torna 9
								if ( $posicao < 2 ) {
										$posicao = 9;
								}
						}
						// Retorna o cálculo
						return $calculo;
					}
				}
				
				// Faz o primeiro cálculo
				$primeiro_calculo = multiplica_cnpj( $primeiros_numeros_cnpj );
				
				// Se o resto da divisão entre o primeiro cálculo e 11 for menor que 2, o primeiro
				// Dígito é zero (0), caso contrário é 11 - o resto da divisão entre o cálculo e 11
				$primeiro_digito = ( $primeiro_calculo % 11 ) < 2 ? 0 :  11 - ( $primeiro_calculo % 11 );
				
				// Concatena o primeiro dígito nos 12 primeiros números do CNPJ
				// Agora temos 13 números aqui
				$primeiros_numeros_cnpj .= $primeiro_digito;

				// O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
				$segundo_calculo = multiplica_cnpj( $primeiros_numeros_cnpj, 6 );
				$segundo_digito = ( $segundo_calculo % 11 ) < 2 ? 0 :  11 - ( $segundo_calculo % 11 );
				
				// Concatena o segundo dígito ao CNPJ
				$cnpj = $primeiros_numeros_cnpj . $segundo_digito;
				
				// Verifica se o CNPJ gerado é idêntico ao enviado
				if ( $cnpj === $cnpj_original ) {
						return true;
				}
		 }
	}

?>
	