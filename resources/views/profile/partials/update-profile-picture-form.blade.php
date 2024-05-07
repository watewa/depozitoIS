<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 ">
            {{ __('Profile Picture') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 ">
            {{ __("Update your account's profile picture.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update.picture') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('post')
        
        @if(Auth::user()->profile_picture)
            <x-input-label for="profile_picture" :value="__('Current Profile Picture')" />

            <img style="height: 200px; width: 200px; object-fit: cover;"
                src="{{ Storage::url(Auth::user()->profile_picture) }}"
                alt="{{ Auth::user()->name }}">
                
            <x-danger-button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-profile-picture-deletion')"
            >{{ __('Delete Picture') }}</x-danger-button>

            @if (session('status') === 'profile-picture-deleted')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 "
                >{{ __('Deleted.') }}</p>
            @endif
        @endif

        <div>
            <x-input-label for="profile_picture" :value="__('New Profile Picture')" />
            <input type="file" class="form-control" id="profile_picture" name="profile_picture">
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-picture-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 "
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <x-modal name="confirm-profile-picture-deletion" focusable>
        <form method="post" action="{{ route('profile.delete.picture') }}" class="p-6">
            @csrf
            @method('post')

            <h2 class="text-lg font-medium text-gray-900 ">
                {{ __('Are you sure you want to delete your profile picture?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 ">
                {{ __('Once your profile picture is deleted, you will not be able to recover it.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Profile Picture') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
