@extends('layouts.bgSectionPage')

@section('box-content')
    @include('partials.resultsTitle')
    @include('partials.resultsFilterCard')
    @foreach ($resultados as $resultado)
        @include('partials.deceasedCard', ['fluxo' => 'homenagens'])
    @endforeach
@endsection
@section('after-box')
    @include('partials.modal')
    @if(!empty($paginacao['paginas']))
        @include('partials.pagination')
    @else
        @include('partials.ctaComunicarObito')
    @endif
@endsection
