<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ __('Mano įstaigos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show p-3 d-flex justify-content-between align-items-center" role="alert">
                    <div>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
                    <div class="btn-close" data-bs-dismiss="alert"></div>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show p-3 d-flex justify-content-between align-items-center" role="alert">
                    <div>{{ session('success') }}</div>
                    <div class="btn-close" data-bs-dismiss="alert"></div>
                </div>
            @endif
            <div class="bg-white mb-3 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="m-3 d-flex justify-content-between align-items-center">
                    <div>
                        <form action="{{ route('teams.index') }}" method="GET">
                            <div class="d-inline-flex">
                                <div class="border border-secondary rounded-start">
                                    <input class="border-0 border-secondary rounded-start custom-input" type="text" name="search" placeholder="Ieškoti...">
                                </div>
                                <div class="border border-secondary px-3 rounded-end bg-secondary text-white d-inline-flex align-items-center">
                                    <button type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div>
                        <button type="button" class="bg-secondary btn btn-secondary" data-bs-toggle="modal" data-bs-target="#createTeamModal">
                            Nauja įstaiga
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="createTeamModal" tabindex="-1" aria-labelledby="createTeamModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTeamModalLabel">Nauja įstaiga</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('teams.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="teamName" class="form-label">Pavadinimas</label>
                                <input type="text" class="form-control rounded" id="teamName" name="team_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="map" class="form-label">Vieta</label>
                                <div id="map" style="height: 300px;"></div>
                                <input type="text" class="form-control rounded" id="latitude" name="latitude" value="55.273330993227646" readonly style="display:none">
                                <input type="text" class="form-control rounded" id="longitude" name="longitude" value="23.81623077541362" readonly style="display:none">
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">Miestas</label>
                                <input type="text" class="form-control rounded" id="city" name="city" required>
                            </div>
                            <div class="mb-3">
                                <label for="street" class="form-label">Gatvė</label>
                                <input type="text" class="form-control rounded" id="street" name="street" required>
                            </div>
                            <div class="mb-3">
                                <label for="houseNr" class="form-label">Namo nr.</label>
                                <input type="text" class="form-control rounded" id="houseNr" name="house_nr" required>
                            </div>
                            <div class="mb-3">
                                <label for="zipCode" class="form-label">Pašto kodas</label>
                                <input type="text" class="form-control rounded" id="zipCode" name="zip_code" required>
                            </div>
                            <div class="mb-3">
                                <label for="extraLine" class="form-label">Papildoma linija</label>
                                <input type="text" class="form-control rounded" id="extraLine" name="extra_line">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefonas</label>
                                <input type="text" class="form-control rounded" id="phone" name="phone">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">El. paštas</label>
                                <input type="text" class="form-control rounded" id="email" name="email">
                            </div>
                            <div class="mb-3">
                                <label for="other" class="form-label">Kiti kontaktai</label>
                                <input type="text" class="form-control rounded" id="other" name="other">
                            </div>
                            <div class="mb-3">
                                <label for="picture" class="form-label">Paveikslėlis</label>
                                <div>
                                    <input type="file" id="picture" name="picture" accept="image/png, image/jpeg, image/jpg">
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

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">

            <div class="bg-white  overflow-hidden shadow-sm sm:rounded-lg mb-3 p-4">
                <div class="container-xxl m-0 p-0">
                    <div class="row m-0 p-0">
                        
                        <div class="col-12">

                            <h2 class="font-semibold text-xl text-gray-800  leading-tight">
                                Mano įstaigos
                            </h2>

                            <div class="m-3">{{ $teams->onEachSide(1)->links() }}</div>
                            
                            @foreach ($teams as $team)
                                <a href="{{ route('teams.show', $team->id) }}" >
                                    <div class="d-flex align-items-center justify-content-left border border-light rounded shadow-sm m-3">
                                        <div class="">
                                            <img style="height: 75px; width: 75px;" class="rounded-start" src="{{ $team->getTeamPicturePathAttribute() }}" alt="Thumbnail">
                                        </div>
                                        <div class="container m-0">
                                            <div class="row align-items-center">
                                                <div class="col-4 d-flex align-items-center ">
                                                    {{ $team->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                    
                            @endforeach

                            <div class="m-3">{{ $teams->onEachSide(1)->links() }}</div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white  overflow-hidden shadow-sm sm:rounded-lg mb-3 p-4">
                <div class="container-xxl m-0 p-0">
                    <div class="row m-0 p-0">
                        
                        <div class="col-12">

                            <h2 class="font-semibold text-xl text-gray-800  leading-tight">
                                Kvietimai į įstaigas
                            </h2>

                            <div class="m-3">{{ $invites->onEachSide(1)->links() }}</div>
                            
                            @foreach ($invites as $team)
                                <a href="{{ route('teams.showGuest', $team->id) }}" >
                                    <div class="d-flex align-items-center justify-content-left border border-light rounded shadow-sm m-3">
                                        <div class="">
                                            <img style="height: 75px; width: 75px;" class="rounded-start" src="{{ $team->getTeamPicturePathAttribute() }}" alt="Thumbnail">
                                        </div>
                                        <div class="d-flex w-full align-items-center justify-content-between">
                                            <div class="ms-2">
                                                {{ $team->name }}
                                            </div>
                                            <div class="d-flex me-2">
                                                <div>
                                                    <form action="{{ route('teams.acceptInvite', $team->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary bg-primary me-2">Priimti</button>
                                                    </form>
                                                </div>
                                                <div>
                                                    <form action="{{ route('teams.rejectInvite', $team->id) }}" method="POST">
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
            </div>
        </div>
    </div>

    
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&map_ids={{ env('GOOGLE_MAP_ID') }}&libraries=marker&callback=initMap"></script>
    <script>
        function initMap() {
            var initialLatLng = { lat: 55.273330993227646, lng: 23.81623077541362 };
            var map = new google.maps.Map(document.getElementById('map'), {
                center: initialLatLng,
                zoom: 6,
                mapId: '{!! env('GOOGLE_MAP_ID') !!}',
            });
            var marker = new google.maps.Marker({
                position: initialLatLng,
                map: map,
                draggable: true
            });

            google.maps.event.addListener(map, 'click', function(event) {
                placeMarker(event.latLng);
            });

            google.maps.event.addListener(marker, 'position_changed', function() {
                updateLatLngInputs(marker.getPosition());
            });

            function placeMarker(location) {
                marker.setPosition(location);
            }

            function updateLatLngInputs(location) {
                document.getElementById('latitude').value = location.lat();
                document.getElementById('longitude').value = location.lng();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initMap();
        });
    </script>

    <!-- NAUDOTI DEJIMUI I BIGCHAIN -->
    <!-- <script src="https://unpkg.com/bigchaindb-driver@4.2.0/dist/browser/bigchaindb-driver.window.min.js"></script>

        <script>
            // BigchainDB server instance (e.g. https://example.com/api/v1/)
            const API_PATH = 'https://869a-78-60-212-114.ngrok-free.app/api/v1/'

            // Create a new keypair.
            const alice = new BigchainDB.Ed25519Keypair()

            // Construct a transaction payload
            const tx = BigchainDB.Transaction.makeCreateTransaction(
                // Define the asset to store, in this example it is the current temperature
                // (in Celsius) for the city of Berlin.
                { city: 'Vyksta debatai', temperature: 22, datetime: new Date().toString() },

                // Metadata contains information about the transaction itself
                // (can be `null` if not needed)
                { what: 'My first BigchainDB transaction' },

                // A transaction needs an output
                [ BigchainDB.Transaction.makeOutput(
                        BigchainDB.Transaction.makeEd25519Condition(alice.publicKey))
                ],
                alice.publicKey
            )

            // Sign the transaction with private keys
            const txSigned = BigchainDB.Transaction.signTransaction(tx, alice.privateKey)

            // Send the transaction off to BigchainDB
            let conn = new BigchainDB.Connection(API_PATH)

            conn.postTransactionCommit(txSigned)
                .then(res => {
                    const elem = document.getElementById('lastTransaction')
                    elem.href = API_PATH + 'transactions/' + txSigned.id
                    elem.innerText = txSigned.id
                    console.log('Transaction', txSigned.id, 'accepted')
                })
            // Check console for the transaction's status
        </script> -->
</x-app-layout>
