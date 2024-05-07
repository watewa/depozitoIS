<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ __('Depozitinės pakuotės') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

            <div class="m-3 ">
                    <form action="{{ route('deposits.guest') }}" method="GET">
                        <div class="d-inline-flex">
                            <div class="border border-secondary rounded-start">
                                <input class="border-0 border-secondary rounded-start custom-input" type="text" name="search" placeholder="Search...">
                            </div>
                            <div class="border border-secondary px-3 rounded-end bg-secondary text-white d-inline-flex align-items-center">
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="m-3">{{ $deposits->onEachSide(1)->links() }}</div>

                @foreach ($deposits as $deposit)
                    <div class="d-none d-md-block">
                        <a class="" data-bs-toggle="modal" data-bs-target="#Modal-{{ $deposit->id }}" 
                                                data-bs-id="{{ $deposit->id }}"
                                                data-bs-name="{{ $deposit->name }}"
                                                data-bs-team_id="{{ $deposit->team_id }}"
                                                data-bs-created_at="{{ $deposit->created_at }}"
                                                data-bs-updated_at="{{ $deposit->updated_at }}"
                                                >
                            <div class="d-flex align-items-center justify-content-left border border-light rounded shadow-sm m-3">
                                <div class="">
                                    <img style="height: 75px; width: 75px;" class="rounded-start" src="{{ $deposit->getThumbnailPathAttribute() }}" alt="Thumbnail">
                                </div>
                                <div class="container m-0">
                                    <div class="row align-items-center">
                                        <div class="col-4 d-flex align-items-center ">
                                            {{ $deposit->name }}
                                        </div>
                                        <div class="col-4" style="max-height: 75px; overflow: auto;">
                                            @foreach($deposit->tags as $tag)
                                                <div class="d-inline-block shadow-sm rounded-pill px-1 m-1 text-sm" style="background-color:{{ $tag->color }}; color:{{ $tag->textColor() }};">{{ $tag->name }}</div>
                                            @endforeach
                                        </div>
                                        <div class="col-4">
                                            <div><p class="text-muted">Sukurta: {{ $deposit->created_at->format('Y-m-d') }}</p></div>
                                            <div><p class="text-muted">Atnaujinta: {{ $deposit->updated_at->format('Y-m-d') }}</p></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="wrapper modal fade" id="Modal-{{ $deposit->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
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
                                    <p><strong>name: </strong><span class="modal-name"></span></p>
                                    <p><strong>team_id: </strong><span class="modal-team_id"></span></p>
                                    <p><strong>created_at: </strong><span class="modal-created_at"></span></p>
                                    <p><strong>updated_at: </strong><span class="modal-updated_at"></span></p>
                                </div>
                                <div class="modal-body b" style="display: none;">
                                    
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button id="content-button" class="btn btn-primary" onclick='changeBody(event, "{{ $deposit->id }}")'>Daugiau</button>
                                    <button class="btn btn-danger" data-bs-dismiss="modal">Uždaryti</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="m-3">{{ $deposits->onEachSide(1)->links() }}</div>

            </div>
        </div>
    </div>

    <script>
        var Modals = document.getElementsByClassName("wrapper")
        for (var i = 0; i < Modals.length; i++) {
            Modals[i].addEventListener('show.bs.modal', function (event) {
            
            // Button that triggered the modal
            var button = event.relatedTarget

            // Extract info from data-bs-* attributes
            var id = button.getAttribute('data-bs-id')
            var name = button.getAttribute('data-bs-name')
            var team_id = button.getAttribute('data-bs-team_id')
            var created_at = button.getAttribute('data-bs-created_at')
            var updated_at = button.getAttribute('data-bs-updated_at')

            // Update the modal's content.
            var modalTitle = event.target.querySelector('.modal-title')
            var modalId = event.target.querySelector('.modal-id')
            var modalName = event.target.querySelector('.modal-name')
            var modalTeam_id = event.target.querySelector('.modal-team_id')
            var modalCreated_at = event.target.querySelector('.modal-created_at')
            var modalUpdated_at = event.target.querySelector('.modal-updated_at')

            modalTitle.textContent = name;
            modalId.textContent = id;
            modalName.textContent = name;
            modalTeam_id.textContent = team_id;
            modalCreated_at.textContent = created_at;
            modalUpdated_at.textContent = updated_at;

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
                        qr.innerHTML = '<a href="http://127.0.0.1:8000/units/' + unit.link_ext + '"><svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg><img src="" /></a>';
                        qr.classList.add('col-3')


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
</x-app-layout>