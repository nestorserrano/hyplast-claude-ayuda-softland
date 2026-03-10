<div class="language-selector">
    <form action="{{ route('locale.set') }}" method="POST" id="locale-form">
        @csrf
        <select name="locale" id="locale-select" class="form-control form-control-sm" onchange="document.getElementById('locale-form').submit();">
            <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>🇩🇴 Español</option>
            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>🇺🇸 English</option>
        </select>
    </form>
</div>

<style>
    .language-selector {
        display: inline-block;
    }
    .language-selector select {
        width: auto;
        min-width: 140px;
    }
</style>
