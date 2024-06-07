<x-qr-layout>
    <div>
        <a href="http://127.0.0.1:8000/units/{{ $unit->link_ext }}">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=http://127.0.0.1:8000/units/{{ $unit->link_ext }}"/>
        </a>
    </div>
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white  shadow-md overflow-hidden sm:rounded-lg">
                
        <div class="container">
            <h1>Deposit information</h1>
            <p>Type: {{ $unit->deposit->name }}
            <p>ID: {{ $unit->id }}</p>
            @if( $unit->state == "Prekyboje")
                <div class="progress">
                    <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="33"
                    aria-valuemin="0" aria-valuemax="100" style="width:33%">
                        {{ $unit->state}}
                    </div>
                </div>
            @elseif( $unit->state == "Parduotas")
                <div class="progress">
                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="67"
                    aria-valuemin="0" aria-valuemax="100" style="width:67%">
                        {{ $unit->state}}
                    </div>
                </div>
            @elseif( $unit->state == "Grąžintas")
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100" style="width:100%">
                        {{ $unit->state}}
                    </div>
                </div>
            @else
                <div class="progress">
                    <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100" style="width:100%">
                        {{ $unit->state}}
                    </div>
                </div>
            @endif
        </div>

    </div>
</x-qr-layout>