<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <?php echo $__env->make('forms.reportarErro', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>
<?php /**PATH /home/cnfbr/laravel_teste/resources/views/partials/modal.blade.php ENDPATH**/ ?>