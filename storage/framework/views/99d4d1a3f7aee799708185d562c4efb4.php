<?php $__env->startSection('content'); ?>
    <div class="max-w-md mx-auto">
         <?php $__env->slot('header', null, ['class' => 'text-center']); ?> 
            <h1 class="text-2xl font-bold">Login de Usuário</h1>
         <?php $__env->endSlot(); ?>

        <?php echo $__env->make('usuario.forms.login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('registrationForm');
            const submitButton = document.getElementById('submitButton');
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            // Enable the submit button only when all fields are valid
            form.addEventListener('input', function () {
                const isValid = [...form.querySelectorAll('input')].every(input => input.checkValidity());
                submitButton.disabled = !isValid;
            });

            // Toggle password visibility
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/cnfbr/laravel_teste/resources/views/usuario/login.blade.php ENDPATH**/ ?>