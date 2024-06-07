<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 ">
            {{ __('Įstaigos adresas') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 ">
            {{ __("Čia galite atnaujinti įstaigos adreso informaciją.") }}
        </p>
    </header>

    <form method="post" action="{{ route('teams.edit.address', $team->id) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

                <div class="mb-3">
                    <label for="map" class="form-label">Vieta</label>
                    <div id="map" style="height: 300px;"></div>
                    <input type="text" class="form-control rounded" id="latitude" name="latitude" value="{{explode(', ', $team->location)[0]}}" readonly style="display:none">
                    <input type="text" class="form-control rounded" id="longitude" name="longitude" value="{{explode(', ', $team->location)[1]}}" readonly style="display:none">
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">Miestas</label>
                    <input type="text" class="form-control rounded" id="city" name="city" value="{{$team->city}}" required>
                </div>
                <div class="mb-3">
                    <label for="street" class="form-label">Gatvė</label>
                    <input type="text" class="form-control rounded" id="street" name="street" value="{{$team->street}}" required>
                </div>
                <div class="mb-3">
                    <label for="houseNr" class="form-label">Namo nr.</label>
                    <input type="text" class="form-control rounded" id="houseNr" name="house_nr" value="{{$team->house_nr}}" required>
                </div>
                <div class="mb-3">
                    <label for="zipCode" class="form-label">Pašto kodas</label>
                    <input type="text" class="form-control rounded" id="zipCode" name="zip_code" value="{{$team->zip_code}}" required>
                </div>
                <div class="mb-3">
                    <label for="extraLine" class="form-label">Papildoma linija</label>
                    <input type="text" class="form-control rounded" id="extraLine" name="extra_line" value="{{$team->extra_line}}">
                </div>

            <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Išsaugoti') }}</x-primary-button>
        </div>
    </form>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&map_ids={{ env('GOOGLE_MAP_ID') }}&libraries=marker&callback=initMap"></script>
    <script>
        function initMap() {
            var initialLatLng = { lat: {!! explode(', ', $team->location)[0] !!}, lng: {!! explode(', ', $team->location)[1] !!} };
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
</section>
