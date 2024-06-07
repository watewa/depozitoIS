<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 ">
            {{ __('Šalinti įstaigą') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 ">
            {{ __('Pašalinus įstaigą, visa su ja susijusi informacija bus pašalinta. Prieš šalinant įstaigą įsitikinkite, kad išsisaugojote Jums svarbius duomenis.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-team-deletion')"
    >{{ __('Šalinti įstaigą') }}</x-danger-button>

    <x-modal name="confirm-team-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('teams.edit.delete', $team->id) }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 ">
                {{ __('Ar tikrai norite ištrinti įstaigą?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 ">
                {{ __('Pašalinus įstaigą, visa su ja susijusi informacija bus pašalinta. Prieš šalinant įstaigą įsitikinkite, kad išsisaugojote Jums svarbius duomenis.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Atšaukti') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Šalinti įstaigą') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
