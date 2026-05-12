@php
  $languageOptions = \App\Models\Languages::orderBy('name')->get();

  $flagMap = [
    'en' => 'gb',
    'es' => 'es',
    'fr' => 'fr',
    'ua' => 'ua',
  ];
@endphp

<div class="modal fade" id="languagePreferenceModal" data-clear-url="{{ url('settings/language/dismiss') }}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content border-0 shadow-lg language-preference-modal">
      <div class="modal-header border-0 pb-0">
        <div>
          <h5 class="modal-title mb-1">{{ __('general.language') }}</h5>
        </div>
        <button type="button" class="close close-inherit" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body pt-2">
        <form method="POST" action="{{ url('settings/language') }}" class="language-preference-form">
          @csrf

          <div class="row">
            @foreach ($languageOptions as $language)
              @php
                $locale = strtolower($language->abbreviation);
                $flagCode = $flagMap[$locale] ?? 'un';
              @endphp

              <div class="col-12 mb-2">
                <button type="submit" name="language" value="{{ $language->abbreviation }}" class="btn btn-light btn-block language-choice-btn">
                  <span class="language-choice-flag">
                    <img
                      src="https://flagcdn.com/w20/{{ $flagCode }}.png"
                      srcset="https://flagcdn.com/w40/{{ $flagCode }}.png 2x"
                      alt="{{ $language->name }} flag"
                      width="20"
                      height="15"
                    >
                  </span>
                  <span class="language-choice-name">{{ $language->name }}</span>
                </button>
              </div>
            @endforeach
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
  .language-preference-modal {
    border-radius: 18px;
  }

  .language-choice-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    justify-content: flex-start;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 14px;
    padding: 12px 14px;
    text-align: left;
    background: rgba(248, 249, 250, 0.98);
  }

  [data-bs-theme="dark"] .language-choice-btn {
    background: rgba(31, 33, 41, 0.92);
    border-color: rgba(255, 255, 255, 0.08);
    color: #fff;
  }

  .language-choice-flag {
    min-width: 20px;
    width: 20px;
    height: 15px;
    text-align: center;
    flex: 0 0 20px;
  }

  .language-choice-flag img {
    display: block;
    width: 100%;
    height: 100%;
    border-radius: 2px;
    object-fit: cover;
  }

  .language-choice-name {
    font-weight: 600;
    font-size: 0.95rem;
  }
</style>
