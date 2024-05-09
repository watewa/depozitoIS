<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ $team->name}}
        </h2>
    </x-slot>


        <div class="py-12">

        <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white mb-3 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="m-3">
                    <button onclick="saveAsImage()" type="button" class="bg-secondary btn btn-secondary">
                        Atsisiųsti
                    </button>
                </div>
            </div>
        </div> -->
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="container-fluid p-0">
                    <div class="row my-3">
                        <div class="col-12">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="block p-6 text-gray-900 ">
                                    <div class="">Įstaigos žymos:</div>
                                    @foreach($teamDepositTags as $tag)
                                        <div class="d-inline-block shadow-sm rounded-pill px-1 m-1 text-sm" style="background-color:{{ $tag->color }}; color:{{ $tag->textColor() }};">{{ $tag->name }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="block p-6 text-gray-900 ">
                                <div class="">Kartotinumas:</div>
                                    @foreach($teamDepositTagsWithCount as $tag)
                                        <div class="d-inline-flex rounded justify-content-center align-items-center shadow-sm m-1 text-sm" style="background-color:{{ $tag->color }}; color:{{ $tag->textColor() }}; height: 52px; width: 52px;">
                                            <div class="p-1" style="font-size:48px;">
                                                {{ $tag->deposit_count }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col">
                            <div class="bg-white shadow-sm sm:rounded-lg">
                                <div class="p-3">Įstaigos nariai:</div>
                                <div class="block px-6 text-gray-900" style="max-height: 150px; overflow: auto;">
                                    @foreach($team->users as $user)
                                        <div class="d-flex m-1">
                                            <div class="flex-shrink-0 h-30 w-30">
                                                <img style="height: 30px; width: 30px; object-fit: cover; border-radius: 50%;" src="{{ $user->getProfilePicturePathAttribute() }}" alt="{{ 'Profile Picture' }}">
                                            </div>
                                            &nbsp;{{$user->name}}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="block p-6 text-gray-900 ">
                                    <div>Bendrinio tipo pakuočių: </div>
                                    <div style="font-size: 48px;">{{ $countType0 }}</div>
                                    <div class="text-muted">Vienetų: {{ $totalType0 }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="block p-6 text-gray-900 ">
                                    <div>Unikalaus tipo pakuočių: </div>
                                    <div style="font-size: 48px;">{{ $countType1 }}</div>
                                    <div class="text-muted">Vienetų: {{ $totalType1 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>