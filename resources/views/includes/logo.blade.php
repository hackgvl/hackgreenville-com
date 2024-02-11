<img class="navbar-brand-img"
     alt="{{ config('app.name', 'HackGreenville') }}"
     {{-- this was added to make it look nice on Filament regardless of light or dark mode --}}
     src="{{ match($darkMode ?? true) {
        true => asset('img/logo-v2.png'),
        false => asset('img/logo-v2-dark-text.png'),
    } }}"/>
