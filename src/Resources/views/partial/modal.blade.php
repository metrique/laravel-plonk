<div id="{{ $modalId or '' }}" class="reveal-modal" data-reveal data-reveal-auto aria-labelledby="{{ $modalId or '' }}" aria-hidden="true" role="dialog">
  <h2 id="{{ $modalId or '' }}-title">{{ $title }}</h2>
  <p>{{ $body }}</p>
  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>