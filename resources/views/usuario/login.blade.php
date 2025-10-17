@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto">
        <x-slot name="header" class="text-center">
            <h1 class="text-2xl font-bold">Login de Usuário</h1>
        </x-slot>

        @include('usuario.forms.login')
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
@endsection
