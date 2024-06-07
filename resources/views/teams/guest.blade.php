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
                    <div class="row m-0 p-0 d-none d-sm-flex">
                        
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
                                <a href="{{ route('teams.showGuest', $team->id) }}" >
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

                            <div class="rounded" id="map" style="height: 400px; width: 100%;"></div>
                                
                        </div>

                    </div>
                    <div class="row m-0 p-0 d-block d-sm-none">
                        <div class="col-12 p-3">
                            <div class="rounded" id="map2" style="height: 300px; width: 100%;"></div>
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
                                <a href="{{ route('teams.showGuest', $team->id) }}" >
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
        var prev_infoWindow = false;

        function getImage(img, name)
        {
            if(img)
            {
                return 'https://depozitois.cloud/storage/' + img;
            }
            else
            {
                return 'https://via.placeholder.com/150?text=' + name.charAt(0).toUpperCase();
            }
        }

        function initMap() {
            // create map
            const map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 55.273330993227646, lng: 23.81623077541362 },
                zoom: 6,
                mapId: '{!! env('GOOGLE_MAP_ID') !!}',
            });

            // team data to array
            var teams = {!! json_encode($teams->toArray()) !!};

            // make markers
            teams.data.forEach(team => {
                if(team.extra_line)
                {
                    var infoWindow = new google.maps.InfoWindow({
                        content: '<div style="font-size: 20px;font-weight: bold;">' + team.name + '</div>' + 
                        '<img src="' + getImage(team.picture, team.name) + '" style="height: 80px; object-fit: cover;">' +
                        '<div>' + team.street + ' ' + team.house_nr + ' ' + team.extra_line + '</div>' +
                        '<div>' + team.city + '</div>' +
                        '<div>' + team.zip_code + '</div><br/>' +
                        '<a href="https://www.google.com/maps/search/?api=1&query=' + team.location.split(', ')[0] + ',' + team.location.split(', ')[1] + '" target="_blank" id="google_link">Atidaryti "Google Maps"</a>'
                        ,
                    });
                }
                else
                {
                    var infoWindow = new google.maps.InfoWindow({
                        content: '<div style="font-size: 20px;font-weight: bold;">' + team.name + '</div>' + 
                        '<img src="' + getImage(team.picture, team.name) + '" style="height: 80px; object-fit: cover;">' +
                        '<div>' + team.street + ' ' + team.house_nr + '</div>' +
                        '<div>' + team.city + '</div>' +
                        '<div>' + team.zip_code + '</div><br/>' +
                        '<a href="https://www.google.com/maps/search/?api=1&query=' + team.location.split(', ')[0] + ',' + team.location.split(', ')[1] + '" target="_blank" id="google_link">Atidaryti "Google Maps"</a>'
                        ,
                    }); 
                }
            
                if (team.location) {
                    const [lat, lng] = team.location.split(', ');
                    const marker = new google.maps.marker.AdvancedMarkerElement({
                        position: { lat: parseFloat(lat), lng: parseFloat(lng) },
                        map: map,
                        title: team.name,
                    });
                    marker.addListener('click', () => {
                        if ( prev_infoWindow )
                        {
                            prev_infoWindow.close();
                        }
                        prev_infoWindow = infoWindow;
                        infoWindow.open(map, marker);
                    });
                }

                map.addListener('click', () => {
                    if ( prev_infoWindow )
                    {
                        prev_infoWindow.close();
                        prev_infoWindow = false;
                    }
                });
            });

            

            // create map2
            const map2 = new google.maps.Map(document.getElementById("map2"), {
                center: { lat: 55.273330993227646, lng: 23.81623077541362 },
                zoom: 6,
                mapId: '{!! env('GOOGLE_MAP_ID') !!}',
            });

            // add markers
            teams.data.forEach(team => {
                if(team.extra_line)
                {
                    var infoWindow = new google.maps.InfoWindow({
                        content: '<div style="font-size: 20px;font-weight: bold;">' + team.name + '</div>' + 
                        '<img src="' + getImage(team.picture, team.name) + '" style="height: 80px; object-fit: cover;">' +
                        '<div>' + team.street + ' ' + team.house_nr + ' ' + team.extra_line + '</div>' +
                        '<div>' + team.city + '</div>' +
                        '<div>' + team.zip_code + '</div><br/>' +
                        '<a href="https://www.google.com/maps/search/?api=1&query=' + team.location.split(', ')[0] + ',' + team.location.split(', ')[1] + '" target="_blank" id="google_link">Atidaryti "Google Maps"</a>'
                        ,
                    });
                }
                else
                {
                    var infoWindow = new google.maps.InfoWindow({
                        content: '<div style="font-size: 20px;font-weight: bold;">' + team.name + '</div>' + 
                        '<img src="' + getImage(team.picture, team.name) + '" style="height: 80px; object-fit: cover;">' +
                        '<div>' + team.street + ' ' + team.house_nr + '</div>' +
                        '<div>' + team.city + '</div>' +
                        '<div>' + team.zip_code + '</div><br/>' +
                        '<a href="https://www.google.com/maps/search/?api=1&query=' + team.location.split(', ')[0] + ',' + team.location.split(', ')[1] + '" target="_blank" id="google_link">Atidaryti "Google Maps"</a>'
                        ,
                    }); 
                }
            
                if (team.location) {
                    const [lat, lng] = team.location.split(', ');
                    const marker = new google.maps.marker.AdvancedMarkerElement({
                        position: { lat: parseFloat(lat), lng: parseFloat(lng) },
                        map: map2,
                        title: team.name,
                    });
                    marker.addListener('click', () => {
                        if ( prev_infoWindow )
                        {
                            prev_infoWindow.close();
                        }
                        prev_infoWindow = infoWindow;
                        infoWindow.open(map2, marker);
                    });
                }

                map2.addListener('click', () => {
                    if ( prev_infoWindow )
                    {
                        prev_infoWindow.close();
                        prev_infoWindow = false;
                    }
                });
            });
            
        }

    </script>


<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&map_ids={{ env('GOOGLE_MAP_ID') }}&libraries=marker&callback=initMap"></script>

</x-app-layout>
<style>
    #google_link
    {
        color: blue;
    }

    #google_link:hover
    {
        text-decoration: underline;
    }

    #google_link:visited
    {
        color: purple;
    }
</style>