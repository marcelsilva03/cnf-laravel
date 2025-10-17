@extends('layouts.app')

@section('title', 'CNF - Cadastro Nacional de Falecidos')

@section('content')
    <section id="resultados" class="resultados section-bg topo">
        <div class="container fundo" data-aos="fade-up">
            @yield('box-content')
        </div>
        <div class="row justify-content-center topo"></div>
    </section>
    @yield('after-box')
    @include('partials.brands')
@endsection
@section('modal-title')
    @yield('titulo-do-modal')
@endsection
@section('modal-content')
    @yield('conteudo-do-modal')
@endsection
@section('modal-footer')
    @yield('rodape-do-modal')
@endsection
@section('template-js')
    @yield('custom-js')
@endsection
