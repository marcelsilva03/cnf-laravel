 <div class="nomeHomenagem text-center">
  <h5>Para fazer uma homenagem é necessário fazer uma pesquisa pelo falecido</h5>
  <div class="d-flex flex-column justify-content-center align-items-center my-4">
    <?php echo $__env->make('forms.buscaHomenagemFalecido', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>
</div>
<?php /**PATH /home/cnfbr/laravel_teste/resources/views/partials/buscaHomenagemFalecido.blade.php ENDPATH**/ ?>