<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 ">
            {{ __('Įstaigos pavadinimas') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 ">
            {{ __("Čia galite pakeisti savo komandos pavadinimą.") }}
        </p>
    </header>

    <form method="post" action="{{ route('teams.edit.name', $team->id) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="team_name" :value="__('Įstaigos pavadinimas')" />
            <x-text-input id="team_name" name="team_name" type="text" class="mt-1 block w-full" :value="old('name', $team->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Išsaugoti') }}</x-primary-button>
        </div>
    </form>
</section>
