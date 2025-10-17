<div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
    <div class="swiper-wrapper">
    @foreach ($homenagens as $homenagem)
        @include('partials.tributeCard')
    @endforeach
    </div>
    <div class="swiper-pagination"></div>
</div>

