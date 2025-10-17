<section id="homenagem" class="resultados1">
  <img src="{{ asset('images/tribute-backgrounds/'.$item['hom_url_fundo']) }}" alt="" class="img-fluid fundoHomenagem" />
  <div class="overlay">
      <div class="container col-md-8 align-items-center justify-content-center mb-5" data-aos="fade-up">
          <section>
            <div class="container section-bg">
              <div class="row align-items-center homenagem-box">
                <div class="col-md-2">
                  <img src="{{ asset('images/users-upload/'.$item['hom_url_foto']) }}" alt="Foto do Falecido" class="img-fluid">
                </div>
                <div class="col-md-10">
                  <h3>{{ $item['fal_nome'] }}</h3>
                  <div class="row">
                    <div class="col-md-6">
                      <p><i class="fas fa-star"></i> {{ $item['fal_data_nascimento'] }} <i class="fas fa-cross"></i> {{ $item['fal_data_falecimento'] }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row justify-content-center">
                <div class="col-12 tribute">
                  <span>"{{ $item['hom_mensagem'] }}"</span>
                </div>
              </div>

              <div class="row usuario">
                <div class="col-12">
                  <span>Enviado por:</span> <span> {{ $item['hom_nome_autor'] }}</span>
                  <span>
                    <a href="#" class="btn btn-cnf-share"><i class="bi bi-share-fill"></i> Compartilhar</a>
                  </span>
                </div>
              </div>
            </div>
          </section>
      </div>
  </div>
</section>
