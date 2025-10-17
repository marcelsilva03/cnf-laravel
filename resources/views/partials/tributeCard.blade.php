<div class="swiper-slide">
    <section id="homenagem" class="resultados1">
        <img src="{{ asset($homenagem['hom_url_fundo']) }}" alt="" class="img-fluid fundoHomenagem" />
        <div class="overlay">
            <div class="container col-md-8 align-items-center justify-content-center mb-5" data-aos="fade-up">
                <section>
                    <div class="container section-bgHom">
                        <div class="d-flex flex-row align-items-center px-3 bg-white">
                            <figure class="mt-3 mr-5">
                                <img src="{{ asset($homenagem['hom_url_foto']) ?? '' }}" width="120" height="120" alt="Foto do Falecido" class="img-fluid" />
                            </figure>
                            <div>
                                <h3>{{ $falecido['fal_nome'] }}</h3>
                                <div>
                                    <p><i class="fas fa-star"></i> {{ $falecido['fal_data_nascimento'] }} <i class="fas fa-cross"></i> {{ $falecido['fal_data_falecimento'] }}</p>
                                </div>
                            </div>
                            <div class="ml-auto">
                                @php
                                    $hom_uuid_falecido = !empty($homenagem['hom_uuid_falecido']) ? $homenagem['hom_uuid_falecido'] : ($falecido->fal_uuid ?? null);
                                @endphp

                                <input
                                    type="hidden"
                                    name="{{ 'clipboard-homenagem-' . $homenagem['hom_codigo'] }}"
                                    value="{{ $hom_uuid_falecido && isset($homenagem['hom_codigo']) ? route('homenagem.detalhes', ['uuid' => $hom_uuid_falecido, 'code' => $homenagem['hom_codigo']]) : '#' }}"
                                />
                                <button data-clipboard-message="Homenagem copiada para área de transferência com sucesso." data-clipboard="{{ 'clipboard-homenagem-' . $homenagem['hom_codigo'] }}" class="btn btn-cnf-share"><i class="bi bi-share-fill"></i> Compartilhar</button>
                                <div class="text-secondary mt-2">Código <span class="text-uppercase">{{ $homenagem['hom_codigo'] }}</span></div>
                            </div>
                        </div>

                        <div class="d-flex flex-column p-4">
                            <i class="fa-solid fa-quote-left fa-2x text-secondary"></i>
                            <p class="h4 py-3 px-5 mx-4">
                                <em>{{ $homenagem['hom_mensagem'] }}</em>
                            </p>
                            <i class="fa-solid fa-quote-right fa-2x text-secondary text-right"></i>
                        </div>

                        <div class="d-flex p-2 mt-4 text-right">
                            <p class="ml-auto">
                                <span class="text-secondary">Enviado por:</span>
                                <strong> {{ $homenagem['hom_nome_autor'] }}</strong>
                                <br />({{ $homenagem['hom_parentesco'] }})
                            </p>
                        </div>

                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
