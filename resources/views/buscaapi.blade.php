@extends('layouts.bgSectionPage')

@section('box-content')
    <section id="duvidas" class="appointment section-bg">
        <div class="container" data-aos="fade-up">
            @include('partials.buscaapiIntroducao')
            @include('forms.buscaapiSolicitarOrcamento')
        </div>
    </section>
@endsection
