<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 ">
            {{ __('Įstaigos kontaktai') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 ">
            {{ __("Čia galite atnaujinti įstaigos kontaktinę informaciją.") }}
        </p>
    </header>

    <form method="post" action="{{ route('teams.edit.contacts', $team->id) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
                <div class="mb-3">
                    <label for="phone" class="form-label">Telefonas</label>
                    <input type="text" class="form-control rounded" id="phone" name="phone" value="{{$team->phone}}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">El. paštas</label>
                    <input type="text" class="form-control rounded" id="email" name="email" value="{{$team->email}}" required>
                </div>
                <div class="mb-3">
                    <label for="other" class="form-label">Kiti kontaktai</label>
                    <input type="text" class="form-control rounded" id="other" name="other" value="{{$team->other}}" required>
                </div>


            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Išsaugoti') }}</x-primary-button>
            </div>
    </form>
</section>
