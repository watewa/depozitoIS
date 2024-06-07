@extends('layouts.team-layout')
<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

@section('header')
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ __('Pakuotės') }}
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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show p-3 d-flex justify-content-between align-items-center" role="alert">
                    <div>{{ session('error') }}</div>
                    <div class="btn-close" data-bs-dismiss="alert"></div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="m-3 d-flex justify-content-between align-items-center">
                    <form class="m-0" action="{{ route('teams.deposits', $team->id) }}" method="GET">
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
                        <button type="button" class="bg-secondary btn btn-secondary" data-bs-toggle="modal" data-bs-target="#createDepositModal2">
                            Pridėti pakuotė
                        </button>
                        <button type="button" class="bg-secondary btn btn-secondary" data-bs-toggle="modal" data-bs-target="#createDepositModal">
                            Kurti pakuotę
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="createDepositModal2" tabindex="-1" aria-labelledby="createDepositModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createDepositModalLabel">Pridėti pakuotę</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="createDepositForm" action="{{ route('teams.deposits.store2', $team->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="role" class="form-label">Paskirtis</label>
                                    <select class="form-control" id="role" name="role">
                                        <option value="p">Platinama pakuotė</option>
                                        <option value="c">Surenkama pakuotė</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="depositName" class="form-label">Pakuotės pavadinimas</label>
                                    <input type="text" class="form-control rounded" id="depositName" name="depositName" placeholder="Ieškoti pakuotės..." required>
                                    <input type="hidden" id="depositId" name="deposit">
                                    <ul class="list-group" id="depositSuggestions" style="display: none;"></ul>
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

            <div class="modal fade" id="createDepositModal" tabindex="-1" aria-labelledby="createDepositModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createDepositModalLabel">Kurti pakuotę</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('teams.deposits.store', $team->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="role" class="form-label">Paskirtis</label>
                                    <select class="form-control" id="role" name="role">
                                        <option value="p">Platinama pakuotė</option>
                                        <option value="c">Surenkama pakuotė</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Pavadinimas</label>
                                    <input type="text" class="form-control rounded" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Aprašymas</label>
                                    <input type="text" class="form-control rounded" id="description" name="description" required>
                                </div>
                                <div class="mb-3">
                                    <label for="type" class="form-label">Tipas</label>
                                    <select class="form-control" id="type" name="type" onchange="toggleCountInput()">
                                        <option value="0">Be individualių pakuočių sekimo</option>
                                        <option value="1">Su individualių pakuočių sekimu</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="countContainer" style="display: none;">
                                    <label for="units" class="form-label">Apskaitos vienetai</label>
                                    <select class="form-control" id="units" name="units">
                                        <option value="vnt">vnt</option>
                                        <option value="kg">kg</option>
                                        <option value="m³">m³</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="picture" class="form-label">Nuotrauka</label>
                                    <input type="file" class="form-control" id="picture" name="picture" accept="image/png, image/jpeg, image/jpg">
                                </div>
                                <div class="mb-3">
                                    <label for="tags" class="form-label">Žymos</label>
                                    <div>
                                        <select class="form-control rounded" id="tags" name="tags[]" multiple="multiple">
                                            @foreach($tags as $tag)
                                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="bg-primary btn btn-primary">Išsaugoti</button>
                                    <button type="button" class="bg-secondary btn btn-secondary" data-bs-dismiss="modal">Atšaukti</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <br/>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="font-semibold text-xl text-gray-800 p-3 leading-tight">
                    {{ __('Platinamos pakuotės') }}
                </h2>
                @foreach ($depositsP as $deposit)
                    <div class="d-none d-md-block">
                        <div class="d-flex align-items-center justify-content-left border border-light rounded shadow-sm m-3">
                            <div class="">
                                <img style="height: 75px; width: 75px;" class="rounded-start" src="{{ $deposit->getThumbnailPathAttribute() }}" alt="Thumbnail">
                            </div>
                            <div class="container m-0">
                                <div class="row align-items-center">
                    
                                    <div class="col-2 d-flex align-items-center ">
                                        <a class="" data-bs-toggle="modal" data-bs-target="#Modal-{{ $deposit->id }}-p" 
                                            data-bs-id="{{ $deposit->id }}"
                                            data-bs-name="{{ $deposit->name }}"
                                            data-bs-desc="{{ $deposit->description }}"
                                            data-bs-created_at="{{ $deposit->created_at }}"
                                            >
                                            {{ $deposit->name }}
                                        </a>
                                    </div>
                                    <div class="col-4" style="max-height: 75px; overflow: auto;">
                                        @foreach($deposit->tags as $tag)
                                            <div class="d-inline-block shadow-sm rounded-pill px-1 m-1 text-sm" style="background-color:{{ $tag->color }}; color:{{ $tag->textColor() }};">{{ $tag->name }}</div>
                                        @endforeach
                                    </div>
                                    <div class="col-3">
                                        <div><p class="text-muted">Atnaujinta: {{ $deposit->updated_at->format('Y-m-d') }}</p></div>
                                    </div>
                    
                                    <div class="col-3 d-flex justify-content-end align-items-center">
                                        @if($team->users()->where('user_id', Auth::user()->id)->wherePivot('is_admin', 1)->exists())
                                            <form id="deleteForm{{ $deposit->id }}" action="{{ route('teams.deposits.delete', [$deposit->id, $team->id, 'p']) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button" class="btn bg-secondary btn-secondary m-1" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $deposit->id }}">
                                                    Naikinti
                                                </button>

                                                <div class="modal fade" id="confirmDeleteModal{{ $deposit->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="confirmDeleteModalLabel">Patvirtinti šalinimą</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Ar tikrai norite pašalinti pasirinktą pakuotę?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary bg-secondary" data-bs-dismiss="modal">Atšaukti</button>
                                                                <button type="submit" class="btn btn-danger bg-danger">Šalinti</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                        <button type="button" class="btn bg-secondary btn-secondary m-1" data-bs-toggle="modal" data-bs-target="#updateModal-{{ $deposit->id }}-p">
                                            Pridėti
                                        </button>

                                        <div class="modal fade" id="updateModal-{{ $deposit->id }}-p" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="updateModalLabel">Didinti kiekį</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('teams.deposits.update', [$deposit->id, $team->id, 'p']) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="mb-3">
                                                                <label for="count" class="form-label">Kiekis</label>
                                                                <input type="number" class="form-control rounded" id="count" name="count" required>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <button type="submit" class="btn btn-primary bg-primary">Pridėti</button>
                                                                <button type="button" class="btn bg-secondary btn-secondary" data-bs-dismiss="modal">Atšaukti</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wrapper modal fade" id="Modal-{{ $deposit->id }}-p" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"></h5>
                                    <button class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span style="font-size:200%;" aria-hidden="true"><h1>&times;</h1></span>
                                    </button>
                                </div>
                                <div class="modal-body a">
                                    <p><strong>id: </strong><span class="modal-id"></span></p>
                                    <p><strong>Pavadinimas: </strong><span class="modal-name"></span></p>
                                    <p><strong>Aprašymas: </strong><span class="modal-desc"></span></p>
                                    <p><strong>Sukurta: </strong><span class="modal-created_at"></span></p>
                                </div>
                                <div class="modal-body b" style="display: none;">
                                    
                                </div>
                                <div class="modal-footer justify-content-between">
                                    @if($deposit->type != 0)
                                    <button id="content-button" class="btn btn-primary" onclick='changeBody(event, "{{ $deposit->id }}")'>Daugiau</button>
                                    @endif
                                    <button class="btn btn-danger" data-bs-dismiss="modal">Uždaryti</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="m-3">{{ $depositsP->onEachSide(1)->links() }}</div>
            </div>

            <br/>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="font-semibold text-xl text-gray-800 p-3 leading-tight">
                    {{ __('Surenkamos pakuotės') }}
                </h2>
                @foreach ($depositsC as $deposit)
                    <div class="d-none d-md-block">
                        <div class="d-flex align-items-center justify-content-left border border-light rounded shadow-sm m-3">
                            <div class="">
                                <img style="height: 75px; width: 75px;" class="rounded-start" src="{{ $deposit->getThumbnailPathAttribute() }}" alt="Thumbnail">
                            </div>
                            <div class="container m-0">
                                <div class="row align-items-center">
                                    <div class="col-2 d-flex align-items-center ">
                                        <a class="" data-bs-toggle="modal" data-bs-target="#Modal-{{ $deposit->id }}-c" 
                                            data-bs-id="{{ $deposit->id }}"
                                            data-bs-name="{{ $deposit->name }}"
                                            data-bs-desc="{{ $deposit->description }}"
                                            data-bs-created_at="{{ $deposit->created_at }}"
                                            >
                                            {{ $deposit->name }}
                                        </a>
                                    </div>
                                    <div class="col-4" style="max-height: 75px; overflow: auto;">
                                        @foreach($deposit->tags as $tag)
                                            <div class="d-inline-block shadow-sm rounded-pill px-1 m-1 text-sm" style="background-color:{{ $tag->color }}; color:{{ $tag->textColor() }};">{{ $tag->name }}</div>
                                        @endforeach
                                    </div>
                                    <div class="col-3">
                                        <div><p class="text-muted">Atnaujinta: {{ $deposit->updated_at->format('Y-m-d') }}</p></div>
                                    </div>
                                    <div class="col-3 d-flex justify-content-end align-items-center">
                                        @if($team->users()->where('user_id', Auth::user()->id)->wherePivot('is_admin', 1)->exists())
                                            <form id="deleteForm{{ $deposit->id }}" action="{{ route('teams.deposits.delete', [$deposit->id, $team->id, 'c']) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button" class="btn bg-secondary btn-secondary m-1" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $deposit->id }}">
                                                    Naikinti
                                                </button>

                                                <div class="modal fade" id="confirmDeleteModal{{ $deposit->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="confirmDeleteModalLabel">Patvirtinti šalinimą</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Ar tikrai norite pašalinti pasirinktą pakuotę?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary bg-secondary" data-bs-dismiss="modal">Atšaukti</button>
                                                                <button type="submit" class="btn btn-danger bg-danger">Šalinti</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                        <button type="button" class="btn bg-secondary btn-secondary m-1" data-bs-toggle="modal" data-bs-target="#updateModal-{{ $deposit->id }}-c">
                                            Pridėti
                                        </button>

                                        <div class="modal fade" id="updateModal-{{ $deposit->id }}-c" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="updateModalLabel">Didinti kiekį</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('teams.deposits.update', [$deposit->id, $team->id, 'c']) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="mb-3">
                                                                <label for="count" class="form-label">Kiekis</label>
                                                                <input type="number" class="form-control rounded" id="count" name="count" required>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <button type="submit" class="btn btn-primary bg-primary">Pridėti</button>
                                                                <button type="button" class="btn bg-secondary btn-secondary" data-bs-dismiss="modal">Atšaukti</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wrapper modal fade" id="Modal-{{ $deposit->id }}-c" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"></h5>
                                    <button class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span style="font-size:200%;" aria-hidden="true"><h1>&times;</h1></span>
                                    </button>
                                </div>
                                <div class="modal-body a">
                                    <p><strong>id: </strong><span class="modal-id"></span></p>
                                    <p><strong>Pavadinimas: </strong><span class="modal-name"></span></p>
                                    <p><strong>Aprašymas: </strong><span class="modal-desc"></span></p>
                                    <p><strong>Sukurta: </strong><span class="modal-created_at"></span></p>
                                </div>
                                <div class="modal-body b" style="display: none;">
                                    
                                </div>
                                <div class="modal-footer justify-content-between">
                                    @if($deposit->type != 0)
                                    <button id="content-button" class="btn btn-primary" onclick='changeBody(event, "{{ $deposit->id }}")'>Daugiau</button>
                                    @endif
                                    <button class="btn btn-danger" data-bs-dismiss="modal">Uždaryti</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="m-3">{{ $depositsC->onEachSide(1)->links() }}</div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const depositNameInput = document.getElementById('depositName');
            const depositSuggestions = document.getElementById('depositSuggestions');

            depositNameInput.addEventListener('input', function () {
                const query = depositNameInput.value;

                if (query.length > 1) {
                    fetch(`{{ route('teams.deposits.search', $team->id) }}?q=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            depositSuggestions.innerHTML = '';
                            depositSuggestions.style.display = 'block';

                            data.forEach(deposit => {
                                const li = document.createElement('li');
                                li.classList.add('list-group-item');
                                li.textContent = deposit.name;
                                li.addEventListener('click', function () {
                                    depositNameInput.value = deposit.name;
                                    document.getElementById('depositId').value = deposit.id;
                                    depositSuggestions.style.display = 'none';
                                });
                                depositSuggestions.appendChild(li);
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    depositSuggestions.innerHTML = '';
                    depositSuggestions.style.display = 'none';
                }
            });
        });


        $(document).ready(function() {
            $('#tags').select2({
                placeholder: "Pasirinkti žymas",
                allowClear: true,
                dropdownParent: $('#createDepositModal')
            });
        });

        function toggleCountInput() {
            var typeSelect = document.getElementById('type');
            var countContainer = document.getElementById('countContainer');
            if (typeSelect.value === '0') {
                countContainer.style.display = 'block';
            } else {
                countContainer.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleCountInput();
        });

        var Modals = document.getElementsByClassName("wrapper")
        for (var i = 0; i < Modals.length; i++) {
            Modals[i].addEventListener('show.bs.modal', function (event) {
            
            // Button that triggered the modal
            var button = event.relatedTarget

            // Extract info from data-bs-* attributes
            var id = button.getAttribute('data-bs-id')
            var name = button.getAttribute('data-bs-name')
            var desc = button.getAttribute('data-bs-desc')
            var created_at = button.getAttribute('data-bs-created_at')

            // Update the modal's content.
            var modalTitle = event.target.querySelector('.modal-title')
            var modalId = event.target.querySelector('.modal-id')
            var modalName = event.target.querySelector('.modal-name')
            var modalDesc = event.target.querySelector('.modal-desc')
            var modalCreated_at = event.target.querySelector('.modal-created_at')

            modalTitle.textContent = name;
            modalId.textContent = id;
            modalName.textContent = name;
            modalDesc.textContent = desc;
            modalCreated_at.textContent = created_at;

        })}

        async function changeBody(event, depositId) {
            var button = event.target
            fetch(`/deposits/${depositId}/units`)
                .then(response => response.json())
                .then(units => {
                    var modalBody = event.target.parentElement.parentElement.querySelector('.b');
                    modalBody.innerHTML = '';

                    var container = document.createElement('div');
                        container.classList.add('container');

                    var headRow = document.createElement('div');
                    headRow.classList.add('row','align-items-center','justify-content-center');
                    
                    var headId = document.createElement('div');
                    headId.textContent = 'ID';
                    headId.classList.add('col-1')

                    var headStatus = document.createElement('div');
                    headStatus.textContent = 'Būsena';
                    headStatus.classList.add('col-8')

                    var headQr = document.createElement('div'); 
                    headQr.textContent = 'Veiksmai';
                    headQr.classList.add('col-3')

                    headRow.appendChild(headId);
                    headRow.appendChild(headStatus);
                    headRow.appendChild(headQr);
                    container.appendChild(headRow);


                    units.forEach(unit => {
                        

                        var row = document.createElement('div');
                        row.classList.add('row','align-items-center','justify-content-center');
                        
                        var idDiv = document.createElement('div');
                        idDiv.textContent = unit.id;
                        idDiv.classList.add('col-1')

                        var progressContainer = document.createElement('div');
                        progressContainer.classList.add('col-8')

                        var qr = document.createElement('div');
                        qr.innerHTML = '<a href="http://127.0.0.1:8000' + unit.link_ext + '"><svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg></a>';
                        qr.innerHTML = qr.innerHTML + '&nbsp;<a href="http://127.0.0.1:8000/unit/update/'+ unit.id +'"><svg xmlns="http://www.w3.org/2000/svg" height="14" width="12.25" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/></svg></a>';
                        qr.classList.add('col-3')
                        qr.classList.add('d-flex')


                        var progress = document.createElement('div');
                        progress.classList.add('progress')

                        if (unit.state === "Prekyboje") {
                            progress.innerHTML = `<div class="progress-bar bg-warning" role="progressbar" aria-valuenow="33"
                                                    aria-valuemin="0" aria-valuemax="100" style="width:33%">
                                                        ` + unit.state + `
                                                </div>`;
                        } else if (unit.state === "Parduotas") {
                            progress.innerHTML = `<div class="progress-bar bg-info" role="progressbar" aria-valuenow="67"
                                                    aria-valuemin="0" aria-valuemax="100" style="width:67%">
                                                    ` + unit.state + `
                                                </div>`;
                        } else if (unit.state === "Grąžintas") {
                            progress.innerHTML = `<div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                                    aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                                    ` + unit.state + `
                                                </div>`;
                        } else {
                            progress.innerHTML = `<div class="progress-bar bg-danger" role="progressbar" aria-valuenow="100"
                                                    aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                                        Klaida: ` + unit.state + `
                                                </div>`;
                        }

                        row.appendChild(idDiv);
                        row.appendChild(progressContainer);
                        row.appendChild(qr);
                        progressContainer.appendChild(progress);
                        container.appendChild(row);

                        modalBody.appendChild(container);
                    });

                    
                }).then(() => {
                    if (button.innerText === "Daugiau")
                    {
                        button.innerText = "Aprašymas"
                        var Body1 = button.parentElement.parentElement.querySelector('.b');
                        Body2 = button.parentElement.parentElement.querySelector('.a');
                        Body1.style.display = ''
                        Body2.style.display = 'none'
                    }
                    else
                    {
                        button.innerText = "Daugiau"
                        var Body1 = button.parentElement.parentElement.querySelector('.b');
                        Body2 = button.parentElement.parentElement.querySelector('.a');
                        Body1.style.display = 'none'
                        Body2.style.display = ''
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            
        }
    </script>

@endsection