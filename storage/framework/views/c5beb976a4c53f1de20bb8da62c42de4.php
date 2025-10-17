<form class="col-md-6" id="homenagem-form" action="/homenagens/resultados" method="GET">
<?php echo csrf_field(); ?> 
  <div class="form-group">
    <label for="nome-hom">Digite o nome de quem você ver ou fazer homenagens:</label>
    <div class="input-group">
      <input type="text" name="nome" class="form-control" id="nome-hom" minlength="3" placeholder="Nome do falecido:" value="<?php echo e(old('nome')); ?>" required>
      <button type="submit" class="btn btn-large btn-cnf">Pesquisar</button>
    </div>
  </div>
</form>
<script>
    const form = document.querySelector('#homenagem-form');
    if (form) {
        const nameField = form.querySelector('input[name=nome]');
        form.querySelector('button[type=submit]').addEventListener('click',(ev) => {
            ev.preventDefault();
            if (nameField.value.length < 3) {
                notificar('O nome deve conter pelo menos 3 caracteres.', 'erro');
            } else {
                form.submit();
            }
        });
    }
</script>
<?php /**PATH /home/cnfbr/laravel_teste/resources/views/forms/buscaHomenagemFalecido.blade.php ENDPATH**/ ?>