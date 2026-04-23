{{-- Online partner card — rendered per ExplorePartner in the Online grid. --}}
@php
    $mediaItems = $partner->media;
    $hasMedia = $mediaItems->count() > 0;
    $isSingle = $mediaItems->count() === 1;
    $animDelay = 0.05 * ($loop->iteration);
@endphp
<div class="partner-card" style="animation-delay: {{ $animDelay }}s">
  <div class="card-inner">
    <div class="card-top-bar">
      <span class="type-badge type-badge-online">
        <span class="live-dot"></span> Online Store
      </span>
      <div class="brand-logo-chip">
        @if($partner->logoUrl())
          <img src="{{ $partner->logoUrl() }}" alt="{{ $partner->name }}" class="w-full h-full object-cover">
        @else
          <span class="text-[10px] font-extrabold text-[#1a3352] tracking-wider">{{ Str::upper(Str::substr($partner->name, 0, 7)) }}</span>
        @endif
      </div>
    </div>

    <div class="media-area" @if(! $isSingle && $hasMedia) data-carousel data-interval="4500" @endif>
      @if(! $hasMedia)
        {{-- Logo fallback banner --}}
        <div class="media-slide active banner-logo-fallback">
          <div class="text-center">
            <div class="logo-fallback-mark">{{ $partner->name }}</div>
            @if($partner->category_tag)
              <div class="logo-fallback-sub">{{ $partner->category_tag }}</div>
            @endif
          </div>
        </div>
      @else
        @foreach($mediaItems as $m)
          @if($m->kind === 'video')
            <div class="media-slide @if($loop->first) active @endif video-thumb" data-video data-embed-url="{{ $m->embedUrl() }}">
              <div class="play-button">
                <svg class="w-6 h-6 text-[#1a3352] ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M5 3.5v17l15-8.5L5 3.5Z"/></svg>
              </div>
              @if($m->caption)
                <span class="video-label">
                  <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z"/></svg>
                  {{ $m->caption }}
                </span>
              @endif
            </div>
          @else
            <div class="media-slide @if($loop->first) active @endif" style="background: #f3f4f6;">
              @if($m->imageUrl())
                <img src="{{ $m->imageUrl() }}" alt="{{ $m->caption ?: $partner->name }}" class="absolute inset-0 w-full h-full object-cover">
              @endif
              @if($m->caption)
                <div class="absolute bottom-3 left-3 right-3 text-center z-[2] text-xs font-bold text-white drop-shadow-[0_1px_3px_rgba(0,0,0,0.6)]">{{ $m->caption }}</div>
              @endif
            </div>
          @endif
        @endforeach
        @if(! $isSingle)
          <div class="carousel-dots">
            @foreach($mediaItems as $m)
              <span class="carousel-dot @if($loop->first) active @endif" data-slide="{{ $loop->index }}"></span>
            @endforeach
          </div>
        @endif
      @endif
    </div>

    <div class="card-content">
      <div class="meta-row">
        @if($partner->category_tag)
          <span class="category-tag">{{ $partner->category_tag }}</span>
          <span class="dot-sep"></span>
        @endif
        <span class="earn-chip">
          <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-2.21 0-4-1.79-4-4s1.79-4 4-4a4 4 0 0 1 4 4"/></svg>
          Earn with BixCash
        </span>
        @if($partner->is_featured)
          <span class="merchandise-chip chip-featured">
            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.2H22l-6 4.4 2.3 7.2L12 16.4 5.7 20.8 8 13.6 2 9.2h7.6L12 2z"/></svg>
            Featured
          </span>
        @endif
        @if($partner->is_new)
          <span class="merchandise-chip chip-new">
            <svg fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/></svg>
            New
          </span>
        @endif
      </div>
      <h3 class="brand-name">{{ $partner->name }}</h3>
      @if($partner->short_description)
        <p class="brand-desc">{{ $partner->short_description }}</p>
      @endif
      <a @if($partner->visit_url) href="{{ $partner->visit_url }}" target="_blank" rel="noopener" @else href="#" @endif class="ep-btn-primary w-full">
        Visit Store
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
      </a>
    </div>
  </div>
</div>
