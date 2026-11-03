'use strict';

/* Conta os caracteres do resumo nas páginas noticia-insere.php e noticia-atualiza.php.
   O limite acompanha o maxlength definido no textarea dessas páginas. */

// Limite de caracteres (igual ao definido no maxlength do <textarea>)
const MAX_CARACTERES = 300;

// O span exibe a contagem; o textarea contém o texto digitado.
const spanMaximo = document.querySelector("#maximo"); // mostrará a contagem
const textResumo = document.querySelector("#resumo"); // campo <textarea> do resumo

// Inicializa a cor do contador de acordo com a quantidade atual.
spanMaximo.classList.remove("bg-danger");
spanMaximo.classList.add("bg-success");

// Lê também o valor inicial, importante ao editar uma notícia existente.
let quantidadeDigitada = textResumo.value.length;

// Atualiza o número e a cor antes de qualquer nova digitação.
atualizarContador(quantidadeDigitada);

function atualizarContador (quantidade) {
  // textContent insere o número como texto, sem interpretar HTML.
  spanMaximo.textContent = quantidade;

  // O contador fica vermelho no início e ao atingir o limite de 300 caracteres.
  if (quantidade === 0 || quantidade === MAX_CARACTERES) {
    spanMaximo.classList.remove("bg-success");
    spanMaximo.classList.add("bg-danger");
  } else {
    spanMaximo.classList.remove("bg-danger");
    spanMaximo.classList.add("bg-success");
  }
};

// O evento input também ocorre ao colar, apagar ou alterar o texto por outros meios.
textResumo.addEventListener("input", function() {
  quantidadeDigitada = textResumo.value.length;
  atualizarContador(quantidadeDigitada);
});
