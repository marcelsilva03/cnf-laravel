<div class="row align-items-center homenagem-box">
    <div class="col-md-2">
        @if(!empty($falecido['fal_foto']))
            <img src="{{ asset($falecido['fal_foto']) }}" alt="Foto do Falecido" class="img-fluid">
        @endif
    </div>
    <div class="{{ !empty($falecido['fal_foto']) ? 'col-md-10' : 'col-md-12' }}">
        <h3>{{ $falecido['fal_nome'] }}</h3>
        <div class="d-flex flex-column flex-md-row justify-content-start align-items-center">
            <div>
                <i class="fas fa-star mr-2"></i>
                {{ \Carbon\Carbon::parse($falecido['fal_data_nascimento'])->format('d-m-Y') }}
            </div>
            <div>
                <i class="fas fa-cross ml-1 ml-md-4 mr-2"></i>
                {{ \Carbon\Carbon::parse($falecido['fal_data_falecimento'])->format('d-m-Y') }}
            </div>
        </div>
    </div>
</div>
