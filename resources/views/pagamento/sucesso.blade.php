@extends('layouts.bgSectionPage')

@section('box-content')
<div class="container mb-5" data-aos="fade-up">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">

      {{-- Box verde com ícone e título --}}
      <div class="d-flex justify-content-center align-items-center bg-success text-white rounded p-3 mb-4" style="border-radius: 0.65rem !important;">
        <i class="bi bi-check-circle-fill me-2" style="font-size: 1.5rem;"></i>
        <h4 class="mb-0">Pagamento Aprovado!</h4>
      </div>

      {{-- Detalhes da solicitação --}}
      <ul class="list-unstyled mb-4">
        <li class="mb-2"><strong>Produto:</strong> {{ $produto }} nº {{ $sol_id }}</li>
        <li class="mb-2"><strong>Código da Solicitação:</strong> {{ $codigo }}</li>
        @if(!empty($chargeId))
        <li class="mb-2"><strong>ID da Cobrança:</strong> {{ $chargeId }}</li>
        @endif
        <li class="mb-2"><strong>Status:</strong> <span class="text-success">{{ $status }}</span></li>
      </ul>

    </div>
  </div>
</div>
    @include('partials.agradecimentoPagamentoPesquisa')
@endsection