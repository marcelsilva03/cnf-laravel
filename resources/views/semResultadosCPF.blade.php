@extends('layouts.bgSectionPage')

@section('box-content')
    @include('partials.sectionTitle', ['title' => 'A busca não gerou resultados'])
    @include(
        'partials.ctaForAdvancedSearch',
        ['message' => 'Solicite uma pesquisa avançada.', 'alertText' => 'Não encontrou o que procurava?']
    )
@endsection
