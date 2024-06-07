@extends('layouts.team-layout')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800  leading-tight">
        {{ $team->name }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @elseif(session('failed'))
                <div class="alert alert-danger">
                    {{ session('failed') }}
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white  shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('teams.partials.update-team-name-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white  shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('teams.partials.update-team-picture-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white  shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('teams.partials.update-team-contacts-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white  shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('teams.partials.update-team-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white  shadow sm:rounded-lg">
                <div class="max-w-xxl">
                    @include('teams.partials.update-team-member-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white  shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('teams.partials.delete-team-form')
                </div>
            </div>
            
        </div>
    </div>
@endsection

