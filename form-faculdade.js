function limpa_formulario_cep() {
    $('#logradouro').val('');
    $('#cidade').val('');
    $('#estado').val('');
}

function preencher_endereco(conteudo) {
    if (!("erro" in conteudo)) {
        $('#logradouro').val(conteudo.logradouro);
        $('#cidade').val(conteudo.localidade);
        $('#estado').val(conteudo.uf);
    } else {
        limpa_formulario_cep();
        alert("CEP não encontrado.");
    }
}

function pesquisar_cep(cep) {
    var cep_limpo = cep.replace(/\D/g, '');
    if (cep_limpo != '') {
        var validacep = /^[0-9]{8}$/;
        if (validacep.test(cep_limpo)) {
            $('#logradouro').val('...');
            $('#cidade').val('...');
            $('#estado').val('...');
            $.getJSON('https://viacep.com.br/ws/' + cep_limpo + '/json/', preencher_endereco)
                .fail(function () {
                    limpa_formulario_cep();
                    alert("CEP não encontrado.");
                });
        } else {
            limpa_formulario_cep();
            alert("Formato de CEP inválido.");
        }
    } else {
        limpa_formulario_cep();
    }
};

$(document).ready(function () {
    $('#email').mask("A", {
        translation: {
            "A": { pattern: /[\w@\-.+]/, recursive: true }
        }
    });
    $('#cep').mask('00000-000');

    $('#cep').blur(function () {
        pesquisar_cep($(this).val());
    });
});