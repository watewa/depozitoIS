<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ __('Mano filialai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white mb-3 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="m-3">
                    <button type="button" class="bg-secondary btn btn-secondary" data-bs-toggle="modal" data-bs-target="#createTeamModal">
                        Nauja komanda
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="createTeamModal" tabindex="-1" aria-labelledby="createTeamModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTeamModalLabel">Nauja komanda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('teams.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="teamName" class="form-label">Komandos pavadinimas</label>
                                <input type="text" class="form-control rounded" id="teamName" name="team_name">
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="bg-primary btn btn-primary">Išsaugoti</button>
                                <button type="button" class="bg-danger btn btn-danger" data-bs-dismiss="modal">Atšaukti</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container-xxl m-0 p-0">
                    <div class="row m-0 p-0">
                        
                        <div class="col-12">

                            <div class="m-3 ">
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

                            <div class="m-3">{{ $teams->onEachSide(1)->links() }}</div>
                            
                            @foreach ($teams as $team)
                                <a href="{{ route('teams.show', $team->id) }}" >
                                    <div class="d-flex justify-content-between align-items-center border border-light rounded p-3 m-3 shadow-sm">
                                        <div>
                                            {{ $team->name }}
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                            <div class="m-3">{{ $teams->onEachSide(1)->links() }}</div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
