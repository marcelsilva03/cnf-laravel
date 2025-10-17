<script>
  document.addEventListener('DOMContentLoaded', function () {
    var exampleModal = document.getElementById('exampleModal');
    if (!exampleModal) return; // Se o modal não existe, não faz nada

    exampleModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // botão que acionou o modal
        if (!button) return; // Segurança extra

        // Busca os dados dos atributos do botão
        var falId = button.getAttribute('data-cnf-id') || '';
        var falUuid = button.getAttribute('data-cnf-uuid') || '';
        var nome = button.getAttribute('data-cnf-nome') || '';
        var cidade = button.getAttribute('data-cnf-cidade') || '';
        var uf     = button.getAttribute('data-cnf-uf') || '';
        var data   = button.getAttribute('data-cnf-data') || '';

        // Preenche os campos ocultos (hidden) do form
        if (exampleModal.querySelector('#idFal'))    exampleModal.querySelector('#idFal').value    = falId;
        if (exampleModal.querySelector('#uuidFal'))  exampleModal.querySelector('#uuidFal').value  = falUuid;
        if (exampleModal.querySelector('#nomeFal'))  exampleModal.querySelector('#nomeFal').value  = nome;
        if (exampleModal.querySelector('#cidadeFal'))exampleModal.querySelector('#cidadeFal').value= cidade;
        if (exampleModal.querySelector('#ufFal'))    exampleModal.querySelector('#ufFal').value    = uf;
        if (exampleModal.querySelector('#dataFal'))  exampleModal.querySelector('#dataFal').value  = data;

        // Preenche cada campo do título
        var campoId = exampleModal.querySelector('#tituloId');
        var campoNome = exampleModal.querySelector('#tituloNome');
        var campoLinha3 = exampleModal.querySelector('#tituloLinha3');
        if (campoId) campoId.innerText = falId;
        if (campoNome) campoNome.innerText = nome;
        if (campoLinha3) campoLinha3.innerText = `${data} | ${cidade}/${uf}`;

        // Preenche os campos no modal
        var campoIdFalecidoModal = exampleModal.querySelector('#idFal');
        var campoUuidFalecidoModal = exampleModal.querySelector('#uuidFal');
        var campoNomeFalecidoModal = exampleModal.querySelector('#erro-nome-falecido');
        var campoCidadeFalecidoModal = exampleModal.querySelector('#CidadeFal');
        var campoUfFalecidoModal = exampleModal.querySelector('#UfFal');
        var campoDataFalecidoModal = exampleModal.querySelector('#DataFal');

        if (campoIdFalecidoModal) campoIdFalecidoModal.value = falId;
        if (campoUuidFalecidoModal) campoUuidFalecidoModal.value = falUuid;
        if (campoNomeFalecidoModal) campoNomeFalecidoModal.innerText = nome;
        if (campoCidadeFalecidoModal) campoCidadeFalecidoModal.innerText = cidade;
        if (campoUfFalecidoModal) campoUfFalecidoModal.innerText = uf;
        if (campoDataFalecidoModal) campoDataFalecidoModal.innerText = data;
    });
  });
</script>
