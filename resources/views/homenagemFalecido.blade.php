@extends('layouts.app')

@section('title', 'CNF - Cadastro Nacional de Falecidos')

@section('content')
    @if(empty($homenagens))
        @include('partials.homenagemCTAPrimeira')
    @else
        @include('components.tributeSlider')
    @endif
@endsection
