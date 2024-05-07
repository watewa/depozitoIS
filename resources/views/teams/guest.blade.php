<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ __('Naujienos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    
            <div class="container-xxl m-0 p-0">
                    <div class="row m-0 p-0 d-none d-md-flex">
                        
                        <div class="col-6">

                            <div class="m-3 ">
                                <form action="{{ route('teams.guest') }}" method="GET">
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

                        <div class="col-6 p-3">

                            <div id="map" style="height: 400px; width: 100%;"></div>
                                
                        </div>

                    </div>
                    <div class="row m-0 p-0 d-block d-sm-none">
                        <div class="col-12 p-3">
                            <div id="map2" style="height: 300px; width: 100%;"></div>
                        </div>
                    </div>
                    <div class="row m-0 p-0 d-block d-sm-none">
                        <div class="col-12">
                            <div class="m-3 ">
                                <form action="{{ route('teams.guest') }}" method="GET">
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

    <script>
        function initMap() {
            const map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 55.273330993227646, lng: 23.81623077541362 },
                zoom: 6,
                mapId: "TEST",
            });

            var teams = {!! json_encode($teams->toArray()) !!};

            teams.data.forEach(team => {
                if (team.location) {
                    const [lat, lng] = team.location.split(', ');
                    const marker = new google.maps.marker.AdvancedMarkerElement({
                        position: { lat: parseFloat(lat), lng: parseFloat(lng) },
                        map: map,
                        title: team.name + '\n',
                    });
                }
            });

            const map2 = new google.maps.Map(document.getElementById("map2"), {
                center: { lat: 55.273330993227646, lng: 23.81623077541362 },
                zoom: 6,
                mapId: "TEST",
            });

            var teams = {!! json_encode($teams->toArray()) !!};

            teams.data.forEach(team => {
                if (team.location) {
                    const [lat, lng] = team.location.split(', ');
                    const marker = new google.maps.marker.AdvancedMarkerElement({
                        position: { lat: parseFloat(lat), lng: parseFloat(lng) },
                        map: map2,
                        title: team.name + '\n',
                    });
                }
            });
            
        }

    </script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&callback=initMap&v=weekly&libraries=marker"></script>
</x-app-layout>