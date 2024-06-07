<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Įstaigos nariai') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Čia galite tvarkyti/pridėti įstaigos narius ir jų teises.") }}
        </p>
    </header>

    
    <div class="container pt-3">
        <div class="row align-items-center p-1">
            <div class="col-2">
                Nuotrauka
            </div>
            <div class="col-1">
                Vardas
            </div>
            <div class="col-3">
                El. paštas
            </div>
            <div class="col-4">
                Teisės
            </div>
            <div class="col-2">
                Veiksmai
            </div>
        </div>
        <hr/>
        @foreach ($team->users as $user)
            <div class="row align-items-center p-1">
                <div class="col-2">
                    <img style="height: 40px; width: 40px; object-fit: cover; border-radius: 50%;" src="{{ $user->getProfilePicturePathAttribute() }}" alt="{{ 'Profile Picture' }}">
                </div>
                <div class="col-1">
                    {{$user->name}}
                </div>
                <div class="col-3">
                    {{$user->email}}
                </div>
                <div class="col-4">
                    <select class="form-control role-dropdown" data-user-id="{{ $user->id }}" data-team-id="{{ $team->id }}" {{ $user->pivot->is_admin == 2 ? 'disabled' : '' }}>
                        @if($user->pivot->is_admin == 2)
                            <option value="2" selected>Pakviestas</option>
                        @else
                            <option value="0" {{ $user->pivot->is_admin == 0 ? 'selected' : '' }}>Įstaigos narys</option>
                            <option value="1" {{ $user->pivot->is_admin == 1 ? 'selected' : '' }}>Įstaigos administratorius</option>
                        @endif
                    </select>
                </div>
                <div class="col-2">
                    <form method="POST" action="{{ route('teams.edit.member.remove', [$team->id, $user->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm bg-danger">{{ __('Pašalinti') }}</button>
                    </form>
                </div>
            </div>
            <hr/>
        @endforeach
    </div>

    <form method="post" action="{{ route('teams.edit.member', $team->id) }}" class="mt-6 space-y-6">
        @csrf

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Pridėti narį') }}
        </h2>

        <div class="max-w-xl">
            <x-input-label for="user_search" :value="__('Vartotojo paieška')" />
            <x-text-input id="user_search" name="user_search" type="text" class="mt-1 block w-full" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('user_search')" />
            <input type="hidden" id="user_id" name="user_id">
            <ul id="user_suggestions" class="list-group mt-2"></ul>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Pridėti') }}</x-primary-button>
        </div>
    </form>
</section>

<script>
    document.getElementById('user_search').addEventListener('input', function () {
        let query = this.value;
        if (query.length > 2) {
            fetch(`{{ route('users.search') }}?search=${query}`)
                .then(response => response.json())
                .then(data => {
                    let suggestions = document.getElementById('user_suggestions');
                    suggestions.innerHTML = '';
                    data.forEach(user => {
                        let li = document.createElement('li');
                        li.className = 'list-group-item';
                        let path;
                        if (user.profile_picture) {
                            path = `<img style="height: 40px; width: 40px; object-fit: cover; border-radius: 50%;" src="http://127.0.0.1:8000/storage/${user.profile_picture}" alt="Profile Picture">`;
                        } else {
                            path = `<img style="height: 40px; width: 40px; object-fit: cover; border-radius: 50%;" src="https://via.placeholder.com/150?text=${user.name[0]}" alt="Profile Picture">`;
                        }
                        li.innerHTML = `<div class="d-flex align-items-center"><div class="px-1">${path}</div><div>${user.name} (${user.email})</div></div>`;
                        li.dataset.userId = user.id;
                        li.addEventListener('click', function () {
                            document.getElementById('user_search').value = user.name + ' (' + user.email + ')';
                            document.getElementById('user_id').value = user.id;
                            suggestions.innerHTML = '';
                        });
                        suggestions.appendChild(li);
                    });
                });
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const roleDropdowns = document.querySelectorAll('.role-dropdown');

        roleDropdowns.forEach(dropdown => {
            dropdown.addEventListener('change', function () {
                const userId = this.getAttribute('data-user-id');
                const teamId = this.getAttribute('data-team-id');
                const isAdmin = this.value;

                fetch(`{{ url('/my-teams/${teamId}/edit/member/role/${userId}') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ is_admin: isAdmin })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Teisės sėkmingai pakeistos');
                    } else {
                        alert(data.message || 'Nepavyko pakeisti teisių');
                    }
                });
            });
        });
    });
</script>