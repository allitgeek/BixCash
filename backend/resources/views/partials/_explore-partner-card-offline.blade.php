{{-- Offline partner card — rendered per ExplorePartner in the Offline grid. --}}
@php
    $mediaItems = $partner->media;
    $hasMedia = $mediaItems->count() > 0;
    $isSingle = $mediaItems->count() === 1;
    $animDelay = 0.05 * ($loop->iteration);
    $firstBranch = $partner->branches->first();
    $branchCount = $partner->branches->count();
    $branchLabel = $partner->branch_count_label ?: ($branchCount > 0 ? $branchCount.' '.Str::plural('branch', $branchCount) : null);
    $status = $partner->cardStatus();  // 'open' | 'closing_soon' | 'closed' | null
    $hoursLabel = $partner->cardHoursLabel();
@endphp
<div class="partner-card" style="animation-delay: {{ $animDelay }}s">
  <div class="card-inner">
    <div class="card-top-bar">
      <span class="type-badge type-badge-offline">
        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
        Offline Store
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

      <div class="offline-info">
        @if($branchLabel)
          <span class="offline-info-item">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
            {{ $branchLabel }}
          </span>
          @if($hoursLabel || $status)
            <span class="offline-info-sep"></span>
          @endif
        @endif
        @if($hoursLabel)
          <span class="offline-info-item">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            {{ $hoursLabel }}
          </span>
        @endif
        @if($status === 'open')
          <span class="open-status">
            <span class="live-dot"></span> Open
          </span>
        @elseif($status === 'closing_soon')
          <span class="closing-status">
            <span class="closing-dot"></span> Closing soon
          </span>
        @elseif($status === 'closed')
          <span class="closing-status">
            <span class="closing-dot" style="background:#6B7280"></span> Closed
          </span>
        @endif
      </div>

      <div class="grid grid-cols-2 gap-2">
        <button class="ep-btn-outline" data-drawer data-slug="{{ $partner->slug }}" data-brand="{{ $partner->name }}" data-count="{{ $branchCount }}">
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/></svg>
          All Branches
        </button>
        <a @if($firstBranch && $firstBranch->resolvedDirectionsUrl()) href="{{ $firstBranch->resolvedDirectionsUrl() }}" target="_blank" rel="noopener" @else href="#" @endif class="ep-btn-primary">
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m15.2 8.8-2.1 5.5-5.5 2.1 2.1-5.5 5.5-2.1Z"/></svg>
          Directions
        </a>
      </div>
    </div>
  </div>
</div>
