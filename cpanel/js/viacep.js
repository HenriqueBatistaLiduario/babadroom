function limpa_formulário_cep() {
	//Limpa valores do formulário de cep.
	document.getElementById('rua').value=("");
	document.getElementById('bairro').value=("");
	document.getElementById('cidade').value=("");
	document.getElementById('uf').value=("");
	document.getElementById('ibge').value=("");
	
	document.getElementById('rua2').value=("");
	document.getElementById('bairro2').value=("");
	document.getElementById('cidade2').value=("");
	document.getElementById('uf2').value=("");
	document.getElementById('ibge2').value=("");
}

function meu_callback(conteudo) {
	if (!("erro" in conteudo)) {
	//Atualiza os campos com os valores.
	document.getElementById('rua').value=(conteudo.logradouro);
	document.getElementById('bairro').value=(conteudo.bairro);
	document.getElementById('cidade').value=(conteudo.localidade);
	document.getElementById('uf').value=(conteudo.uf);
	document.getElementById('ibge').value=(conteudo.ibge);
	
	document.getElementById('rua2').value=(conteudo.logradouro);
	document.getElementById('bairro2').value=(conteudo.bairro);
	document.getElementById('cidade2').value=(conteudo.localidade);
	document.getElementById('uf2').value=(conteudo.uf);
	document.getElementById('ibge2').value=(conteudo.ibge);
	} //end if.
	else {
	//CEP não Encontrado.
	limpa_formulário_cep();
	alert("CEP não encontrado.");
	}
}

function pesquisacep(valor) {

	//Nova variável "cep" somente com dígitos.
	var cep = valor.replace(/\D/g, '');

	//Verifica se campo cep possui valor informado.
	if (cep != "") {

	//Expressão regular para validar o CEP.
	var validacep = /^[0-9]{8}$/;

	//Valida o formato do CEP.
	if(validacep.test(cep)) {

		/*Preenche os campos com "..." enquanto consulta webservice.
		document.getElementById('rua').value="Carregando...";
		document.getElementById('bairro').value="Carregando...";
		document.getElementById('cidade').value="Carregando...";
		document.getElementById('uf').value="Carregando...";
		document.getElementById('ibge').value="Carregando...";
		
		document.getElementById('rua2').value="Carregando...";
		document.getElementById('bairro2').value="Carregando...";
		document.getElementById('cidade2').value="Carregando...";
		document.getElementById('uf2').value="Carregando...";
		document.getElementById('ibge2').value="Carregando...";*/

		//Cria um elemento javascript.
		var script = document.createElement('script');

		//Sincroniza com o callback.
		script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

		//Insere script no documento e carrega o conteúdo.
		document.body.appendChild(script);

	} //end if.
	else {
	//cep é inválido.
	limpa_formulário_cep();
	alert("Formato de CEP inválido.");
	}
	} //end if.
	else {
	//cep sem valor, limpa formulário.
	limpa_formulário_cep();
	}
};