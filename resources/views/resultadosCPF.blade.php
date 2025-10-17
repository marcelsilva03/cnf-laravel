@extends('layouts.bgSectionPage')

@section('box-content')
    <div class="section-title">
      <h3>Resultados da pesquisa por CPF</h3>
    </div>
    @foreach ($resultados as $resultado)
        @include('partials.deceasedCard')
    @endforeach
@endsection
@section('after-box')
    @include('partials.modal')
@endsection
