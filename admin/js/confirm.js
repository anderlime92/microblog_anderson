'use strict';

const links = document.querySelectorAll('.excluir');
const modalExclusao = document.querySelector("#modal-exclusao");
const botaoSim = modalExclusao.querySelector("#sim");
const botaoNao = modalExclusao.querySelector("#nao");

// Guarda o endereço do link que abriu o diálogo.
let hrefSelecionado = null;

// Cada link apenas escolhe o registro e abre o mesmo diálogo.
for (const link of links) {
    link.addEventListener("click", function(event) {
        event.preventDefault();
        hrefSelecionado = link.getAttribute("href");
        modalExclusao.showModal();
    });
}

// Os eventos dos botões são registrados uma vez, fora do loop dos links.
botaoSim.addEventListener("click", function() {
    if (hrefSelecionado !== null) {
        location.href = hrefSelecionado;
    }
});

botaoNao.addEventListener("click", function() {
    modalExclusao.close();
});

// O evento close também ocorre quando o diálogo é fechado com Escape.
modalExclusao.addEventListener("close", function() {
    hrefSelecionado = null;
});
