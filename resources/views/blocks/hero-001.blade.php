
<section class="{{ $block->classes }} section-home-hero">
    <div class="wrapper-video">
      @if(isset($background_video) and $background_video)
      <video 
        src="{!! esc_url($background_video) !!}" 
        autoplay muted defaultMuted playsinline loop 
        @if (isset($background_image) and $background_image) poster="{{ $background_image['url'] }}"@endif
      ></video>
      @endif
    </div>
    <div class="wrapper-overlay">
      <div class="container animate-fiu">
          @if (isset($logo) and $logo)
            <img src="{!! esc_url($logo['url']) !!}" alt="{{ $logo['alt'] }}" class="img-fluid">
          @endif
  
          
          @if(isset($heading) and $heading)
            <h1>{{ $heading }}</h1>
          @endif
  
          @if(isset($subheading) and $subheading)
            <h3 class="subheading-line mb-2xl">{{ $subheading }}</h3>
          @endif
  
          @if(isset($description) and $description)
            {!! $description !!}
          @endif
      </div>
  </div>
  </section>
  @if($show_arrow_down)
    <section class="section-btn-scroll-down">
      <div class="outer-circle">
          <div class="spinner"></div>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
              class="bi bi-arrow-down bounce" viewBox="0 0 16 16">
              <path fill-rule="evenodd"
                  d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z" />
          </svg>
      </div>
    </section>
  @endif