<!-- Contact Form Section -->
<section id="contact" class="appointment section-bg">
    <div class="container" data-aos="fade-up">
        <div class="section-title">
            <h2>Tire suas dúvidas</h2>
            <p>Fale com O CNF Brasil e tire suas dúvidas, temos uma equipe pronta para auxiliar você!</p>
        </div>
        <?php echo $__env->make('forms.contato', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</section><?php /**PATH /home/cnfbr/laravel_teste/resources/views/partials/contact.blade.php ENDPATH**/ ?>