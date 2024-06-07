<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 ">
            {{ __('Įstaigos profilio nuotrauka') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 ">
            {{ __("Čia galite pridėti/pakeisti įstaigos profilio nuotrauką.") }}
        </p>
    </header>

    <form method="post" action="{{ route('teams.edit.picture', $team->id) }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('post')
        
        @if($team->picture)
            <x-input-label for="picture" :value="__('Komandos profilio nuotrauka')" />

            <img style="height: 200px; width: 200px; object-fit: cover;"
                src="{{ Storage::url($team->picture) }}"
                alt="{{ $team->name }}">
                
            <x-danger-button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-team-picture-deletion')"
                >
                {{ __('Šalinti nuotrauką') }}
            </x-danger-button>
        @endif

        <div>
            <x-input-label for="picture" :value="__('Nauja įstaigos profilio nuotrauka')" />
            <input type="file" id="picture" name="picture" accept="image/png, image/jpeg, image/jpg">
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Išsaugoti') }}</x-primary-button>
        </div>
    </form>

    <x-modal name="confirm-team-picture-deletion" focusable>
        <form method="post" action="{{ route('teams.edit.picture.delete', $team->id) }}" class="p-6">
            @csrf
            @method('post')

            <h2 class="text-lg font-medium text-gray-900 ">
                {{ __('Ar tikrai norite pašalinti įstaigos profilio nuotrauka?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 ">
                {{ __('Ištrynus profilio nuotrauka, nuotraukos failas serveryje bus pašalintas ir jo atkurti nebebus galima.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Atšaukti') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Šalinti įstaigos nuotrauką') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
