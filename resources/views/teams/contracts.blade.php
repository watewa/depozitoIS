@extends('layouts.team-layout')

@section('header')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sutartys') }}
        </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show p-3 d-flex justify-content-between align-items-center" role="alert">
                    <div>{{ session('success') }}</div>
                    <div class="btn-close" data-bs-dismiss="alert"></div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="m-3 d-flex justify-content-between align-items-center">
                    
                    <form class="m-0" action="{{ route('teams.contracts', $team->id) }}" method="GET">
                        <div class="d-inline-flex">
                            <div class="border border-secondary rounded-start">
                                <input class="border-0 border-secondary rounded-start custom-input" type="text" name="search" placeholder="Ieškoti...">
                            </div>
                            <div class="border border-secondary px-3 rounded-end bg-secondary text-white d-inline-flex align-items-center">
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>

                    <div>
                        <button type="button" class="bg-secondary btn btn-secondary" data-bs-toggle="modal" data-bs-target="#createContractModal">
                            Nauja sutartis
                        </button>
                    </div>

                </div>
            </div>

            <br/>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="font-semibold text-xl text-gray-800 p-3 leading-tight">
                    {{ __('Galiojančios sutartys') }}
                </h2>
                @foreach ($contracts as $contract)
                    @if($contract->inviterTeam->id != $team->id)
                        <a href="{{ route('teams.showGuest', $contract->inviterTeam->id) }}">
                            <div class="d-flex align-items-center justify-content-left border border-light rounded shadow-sm m-3">
                                <div class="">
                                    <img style="height: 75px; width: 75px;" class="rounded-start" src="{{ $contract->inviterTeam->getTeamPicturePathAttribute() }}" alt="Thumbnail">
                                </div>
                                <div class="container m-0">
                                    <div class="row align-items-center">
                                        <div class="col-4 d-flex align-items-center ">
                                            {{ $contract->inviterTeam->name }}
                                        </div>
                                        <div class="col-6 d-flex align-items-center ">
                                            Pasirašyta: {{ $contract->updated_at }}
                                        </div>
                                        <div class="col-2 d-flex justify-content-end align-items-center">
                                            <form action="{{ route('teams.contracts-destroy', $contract->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-secondary bg-secondary">Šalinti</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @elseif($contract->invitedTeam->id != $team->id)
                        <a href="{{ route('teams.showGuest', $contract->invitedTeam->id) }}">
                            <div class="d-flex align-items-center justify-content-left border border-light rounded shadow-sm m-3">
                                <div class="">
                                    <img style="height: 75px; width: 75px;" class="rounded-start" src="{{ $contract->invitedTeam->getTeamPicturePathAttribute() }}" alt="Thumbnail">
                                </div>
                                <div class="container m-0">
                                    <div class="row align-items-center">
                                        <div class="col-4 d-flex align-items-center ">
                                            {{ $contract->invitedTeam->name }}
                                        </div>
                                        <div class="col-6 d-flex align-items-center ">
                                            Pasirašyta: {{ $contract->updated_at }}
                                        </div>
                                        <div class="col-2 d-flex justify-content-end align-items-center">
                                            <form action="{{ route('teams.contracts-destroy', $contract->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-secondary bg-secondary">Šalinti</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endif
                @endforeach
                <div class="m-3">{{ $contracts->onEachSide(1)->links() }}</div>
            </div>

            <br/>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="font-semibold text-xl text-gray-800 p-3 leading-tight">
                    {{ __('Kvietimai') }}
                </h2>
                @foreach ($invites as $invite)
                    <a href="{{ route('teams.showGuest', $invite->inviterTeam->id) }}">
                        <div class="d-flex align-items-center justify-content-left border border-light rounded shadow-sm m-3">
                            <div class="">
                                <img style="height: 75px; width: 75px;" class="rounded-start" src="{{ $invite->inviterTeam->getTeamPicturePathAttribute() }}" alt="Thumbnail">
                            </div>
                            <div class="container m-0">
                                <div class="row align-items-center">
                                    <div class="col-4 d-flex align-items-center ">
                                        {{ $invite->inviterTeam->name }}
                                    </div>
                                    <div class="col-5 d-flex align-items-center ">
                                        Pasirašyta: {{ $invite->updated_at }}
                                    </div>
                                    <div class="col-3 d-flex justify-content-end align-items-center">
                                        <form action="{{ route('teams.contracts-update', $invite->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-primary bg-primary me-2">Patvirtinti</button>
                                        </form>
                                        <form action="{{ route('teams.contracts-destroy', $invite->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-secondary bg-secondary">Atmesti</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
                <div class="m-3">{{ $invites->onEachSide(1)->links() }}</div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createContractModal" tabindex="-1" aria-labelledby="createContractModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createContractModalLabel">Nauja sutartis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createContractForm" action="{{ route('teams.contracts-store', $team->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="teamName" class="form-label">Komanda</label>
                            <input type="text" class="form-control rounded" id="teamName" name="teamName" placeholder="Veskite komandos pavadinimą..." required>
                            <input type="hidden" id="teamId" name="teamId">
                            <input type="hidden" id="inviter" name="inviter" value="{{$team->id}}">
                            <ul class="list-group" id="teamSuggestions" style="display: none;"></ul>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn bg-primary btn-primary">Pateikti</button>
                            <button type="button" class="btn bg-secondary btn-secondary" data-bs-dismiss="modal">Atšaukti</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const teamNameInput = document.getElementById('teamName');
        const teamSuggestions = document.getElementById('teamSuggestions');

        teamNameInput.addEventListener('input', function () {
            const query = teamNameInput.value;

            if (query.length > 1) {
                fetch(`{{ route('teams.search') }}?search=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        teamSuggestions.innerHTML = '';
                        teamSuggestions.style.display = 'block';

                        data.forEach(team => {
                            const li = document.createElement('li');
                            li.classList.add('list-group-item');
                            let path;
                            if (team.picture) {
                                path = `<img style="height: 40px; width: 40px; object-fit: cover; border-radius: 50%;" src="https://depozitois.cloud/storage/${team.picture}" alt="Thumbnail">`;
                            } else {
                                path = `<img style="height: 40px; width: 40px; object-fit: cover; border-radius: 50%;" src="https://via.placeholder.com/150?text=${team.name[0]}" alt="Thumbnail">`;
                            }
                            li.innerHTML = `<div class="d-flex align-items-center"><div class="px-1">${path}</div><div>${team.name}, ${team.city}, ${team.street} ${team.house_nr}, ${team.zip_code}</div></div>`;
                            li.addEventListener('click', function () {
                                teamNameInput.value = team.name + ', ' + team.city +', ' + team.street+' '+team.house_nr+', '+team.zip_code;
                                document.getElementById('teamId').value = team.id;
                                teamSuggestions.style.display = 'none';
                            });
                            teamSuggestions.appendChild(li);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                teamSuggestions.innerHTML = '';
                teamSuggestions.style.display = 'none';
            }
        });
    });
    </script>

@endsection