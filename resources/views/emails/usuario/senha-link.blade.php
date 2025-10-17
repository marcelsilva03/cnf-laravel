@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4 text-center">Recuperação de Senha</h1>
            <p class="text-gray-600 mb-6 text-center">
                Recebemos uma solicitação para redefinir sua senha. Clique no link abaixo ou copie e cole no seu navegador para continuar.
            </p>
            <div class="text-center">
                <a href="{{ $link }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    {{ $link }}
                </a>
            </div>
            <p class="text-sm text-gray-500 mt-6 text-center">
                Se você não solicitou a redefinição de senha, ignore esta mensagem.
            </p>
        </div>
    </div>
@endsection
