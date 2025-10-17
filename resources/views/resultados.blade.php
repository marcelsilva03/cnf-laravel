@extends('layouts.bgSectionPage')

@section('box-content')
    @include('partials.resultsTitle')
    @include('partials.resultsFilterCard')
    @foreach ($resultados as $resultado)
        @include('partials.deceasedCard', ['fluxo' => 'principal'])
    @endforeach
@endsection
@section('after-box')
    @include('partials.modal')
    @if(!empty($paginacao['paginas']))
        @include('partials.pagination')
    @endif
    @include('partials.ctaForAdvancedSearch', [
        'message' => 'Solicite uma Pesquisa de Registro de Óbito e Indicação de Cartório.',
        'alertText' => 'Não achou o que procurava?'
      ])
@endsection
@section('template-js')
    @include('scripts.dadosParaModal')
@endsection
