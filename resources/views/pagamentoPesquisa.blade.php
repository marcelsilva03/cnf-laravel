@extends('layouts.bgSectionPage')

@section('box-content')
    <div class="section-title">
      <h4>SOLICITAÇÃO DE PESQUISA</h4>
    </div>
    @include('partials.alertaPagamentoPesquisa')
    @include('partials.formasDePagamento')
@endsection
@section('custom-js')
    @include('scripts.formasDePagamento')
@endsection
