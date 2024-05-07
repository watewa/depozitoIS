@extends('layouts.team-layout')

@section('header')
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ $team->name }}
        </h2>
@endsection

@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="container-fluid p-0">
                    <div class="row my-3">
                        <div class="col">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="block p-6 text-gray-900 ">
                                    {{ __("1") }}
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="block p-6 text-gray-900 ">
                                    {{ __("2") }}
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="block p-6 text-gray-900 ">
                                    {{ __("3") }}
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="block p-6 text-gray-900 ">
                                    {{ __("4") }}
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="block p-6 text-gray-900 ">
                                    {{ __("5") }}
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="block p-6 text-gray-900 ">
                                    {{ __("6") }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="block p-6 text-gray-900 ">
                                    {{ __("grupes useriu sarasas scrollable") }}
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="block p-6 text-gray-900 ">
                                    {{ __("pakuociu kiekis ir vienetu kiekis") }}
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="block p-6 text-gray-900 ">
                                    {{ __("circle chart all time waste types") }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="block p-6 text-gray-900 ">
                                    {{ __("stacked bar chart, week - 7 dienu output, month - 4 savaiciu, year - 12 menesiu") }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection