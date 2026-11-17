'use strict';

const formularioBusca = document.querySelector('#form-busca');
const campoBusca = document.querySelector('#campo-busca');
const areaResultados = document.querySelector('#resultados');

if (formularioBusca && campoBusca && areaResultados) {
	let temporizadorBusca;
	let requisicaoAtual;

	const pesquisar = async () => {
		const termoBusca = campoBusca.value.trim();

		if (termoBusca.length < 2) {
			if (requisicaoAtual) {
				requisicaoAtual.abort();
			}

			areaResultados.innerHTML = termoBusca.length === 0
				? ''
				: '<p class="small text-muted mb-0">Digite pelo menos 2 caracteres para pesquisar.</p>';
			areaResultados.hidden = termoBusca.length === 0;
			areaResultados.setAttribute('aria-busy', 'false');
			return;
		}

		if (requisicaoAtual) {
			requisicaoAtual.abort();
		}

		requisicaoAtual = new AbortController();
		areaResultados.hidden = false;
		areaResultados.setAttribute('aria-busy', 'true');
		areaResultados.innerHTML = '<p class="small text-muted mb-0">Pesquisando...</p>';

		const dadosBusca = new FormData(formularioBusca);

		try {
			const resposta = await fetch(formularioBusca.action, {
				method: 'POST',
				body: dadosBusca,
				headers: { 'X-Requested-With': 'XMLHttpRequest' },
				signal: requisicaoAtual.signal
			});

			if (!resposta.ok) {
				throw new Error('Falha ao consultar os resultados.');
			}

			areaResultados.innerHTML = await resposta.text();
		} catch (erro) {
			if (erro.name !== 'AbortError') {
				areaResultados.innerHTML = '<p class="small text-danger mb-0">Não foi possível realizar a busca. Tente novamente.</p>';
			}
		} finally {
			areaResultados.setAttribute('aria-busy', 'false');
		}
	};

	campoBusca.addEventListener('input', () => {
		window.clearTimeout(temporizadorBusca);
		temporizadorBusca = window.setTimeout(pesquisar, 300);
	});

	formularioBusca.addEventListener('submit', (evento) => {
		evento.preventDefault();
		window.clearTimeout(temporizadorBusca);
		pesquisar();
	});

	document.addEventListener('click', (evento) => {
		if (!formularioBusca.contains(evento.target) && !areaResultados.contains(evento.target)) {
			areaResultados.hidden = true;
		}
	});
}

