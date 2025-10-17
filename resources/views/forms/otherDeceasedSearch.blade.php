<form id="outro-falecido-form" method="GET" action="/homenagens/resultados">
@csrf
    @include('partials.resumoBreveFalecido')
    <div class="form-group mb-3 mt-3">
        <label for="nome-hom">Não é quem você procurava? Faça uma nova pesquisa:</label>
        <div class="input-group">
          <input type="text" name="nome" class="form-control" id="nome-hom" placeholder="Nome do falecido:" value="{{ old('nome') }}">
          <button type="submit" class="btn btn-cnf">Pesquisar</button>
        </div>
    </div>
</form>
<script>
    const form = document.querySelector('#outro-falecido-form');
    if (form) {
        form.querySelector('button[type=submit]').addEventListener('click', (ev) => {
            ev.preventDefault();
            const nome = form.querySelector('input[name=nome]').value.trim();
            if (nome.length < 3) {
                notificar('O campo Nome deve ter pelo menos 3 caracteres.', 'erro');
            } else {
                form.submit();
            }
        });
    }
</script>
