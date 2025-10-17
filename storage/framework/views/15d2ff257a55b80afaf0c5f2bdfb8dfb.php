<section id="resultados" class="resultados section-bg topo">
    <div class="container fundo" data-aos="fade-up">
        <div class="section-title">
            <h4>COMUNICAR ÓBITO</h4>
        </div>
        <div class="container align-items-center justify-content-center mb-5" data-aos="fade-up">
            <div class="row">
                <div class="mb-3">
                    <p class="regrasp">
                        <strong>
                            Você pode colaborar para aumentar nossos registros de óbitos e
                            auxiliar pessoas que necessitam dessa informação.
                        </strong>
                        <br>
                        Preencha o formulário corretamente e com o maior numero de dados possível, iremos checar
                        as informações para adicionar o registro ao nosso banco de dados.
                    </p>
                </div>
            </div>
        </div>
        <div class="container solicitante align-items-center justify-content-center mb-4" data-aos="fade-up">
            <?php echo $__env->make('forms.comunicarobitoform', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</section>
<?php /**PATH /home/cnfbr/laravel_teste/resources/views/partials/comunicarobitocard.blade.php ENDPATH**/ ?>