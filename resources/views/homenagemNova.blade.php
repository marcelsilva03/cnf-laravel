 @extends('layouts.bgSectionPage')

@section('box-content')
    @include('partials.tributeDeceasedName')
    @include('partials.newTribute')
@endsection
@section('titulo-do-modal', 'Selecione uma Imagem')
@section('conteudo-do-modal')
    @include('partials.homenagemImagemDeFundoModalDialog')
@endsection
@section('rodape-do-modal')
    <button type="button" class="btn btn-cnf" id="envio-opcao-fundo">Concluído</button>
@endsection
@section('custom-js')
    <script src="{{ asset('js/homenagemOpcaoFundo.js') }}"></script>
@endsection
