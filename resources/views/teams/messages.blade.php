@extends('layouts.team-layout')

@section('header')
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ $team->name }}
        </h2>
@endsection

@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="container-fluid p-3 bg-white shadow-sm rounded" style="max-height: 50vh; overflow: auto;" id="messageContainer">
                    @foreach($messages as $message)
                    @if($message->user_id == Auth::user()->id)
                    <div class="row my-3">
                        <div class="col-8">
                            <div class="d-flex flex-row bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="flex-shrink-0 h-30 w-30 m-3">
                                    <img style="height: 30px; width: 30px; object-fit: cover; border-radius: 50%;" src="{{ $message->user->getProfilePicturePathAttribute() }}" alt="{{ 'Profile Picture' }}">
                                </div>
                                <div class="p-1">
                                    <div class="text-muted">
                                        {{ $message->updated_at }}
                                    </div>
                                    <div>
                                        {{ $message->content }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">

                        </div>
                    </div>
                    @else
                    <div class="row my-3">
                        <div class="col-4">

                        </div>
                        <div class="col-8">
                            <div class="d-flex flex-row-reverse bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="flex-shrink-0 h-30 w-30 m-3">
                                    <img style="height: 30px; width: 30px; object-fit: cover; border-radius: 50%;" src="{{ $message->user->getProfilePicturePathAttribute() }}" alt="{{ 'Profile Picture' }}">
                                </div>
                                <div class="p-1">
                                    <div class="text-muted">
                                        {{ $message->updated_at }}
                                    </div>
                                    <div style="text-align: right;">
                                        {{ $message->content }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form action="{{ route('teams.messages-store', $team->id) }}" method="post" id="messageForm">
                    @csrf
                    <div class="input-group">
                        <input type="text" class="form-control rounded-start" placeholder="Rašykite žinutę..." name="content">
                        <div class="border border-secondary px-3 rounded-end bg-secondary text-white d-inline-flex align-items-center">
                            <button type="submit">Siųsti</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script>
            function scrollToBottom() {
                var container = document.getElementById('messageContainer');
                container.scrollTop = container.scrollHeight;
            }

            window.onload = function() {
                scrollToBottom();
                setInterval(refreshContent, 2000);
            };

            function refreshContent() {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var container = document.getElementById('messageContainer');
                        container.innerHTML = '';

                        var response = JSON.parse(this.responseText);
                        var messages = response.messages;
                        var user = {!! json_encode(Auth::user()->id) !!};
                        messages.forEach(function(message) {
                            if( message.user_id === user )
                            {
                                var messageContent = `
                                <div class="row my-3">
                                    <div class="col-8">
                                        <div class="d-flex flex-row bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                            <div class="flex-shrink-0 h-30 w-30 m-3">
                                                <img style="height: 30px; width: 30px; object-fit: cover; border-radius: 50%;" src="${message.profile_picture_path}" alt="Profile Picture">
                                            </div>
                                            <div class="p-1">
                                                <div class="text-muted">
                                                    ${new Date(message.updated_at).toISOString().slice(0, 19).replace('T', ' ')}
                                                </div>
                                                <div>
                                                    ${message.content}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">

                                    </div>
                                </div>
                                `;
                                container.innerHTML = container.innerHTML + messageContent;
                            }
                            else
                            {
                                var messageContent = `
                                <div class="row my-3">
                                    <div class="col-4">

                                    </div>
                                    <div class="col-8">
                                        <div class="d-flex flex-row-reverse bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                            <div class="flex-shrink-0 h-30 w-30 m-3">
                                                <img style="height: 30px; width: 30px; object-fit: cover; border-radius: 50%;" src="${message.profile_picture_path}" alt="{{ 'Profile Picture' }}">
                                            </div>
                                            <div class="p-1">
                                                <div class="text-muted">
                                                    ${new Date(message.updated_at).toISOString().slice(0, 19).replace('T', ' ')}
                                                </div>
                                                <div style="text-align: right;">
                                                    ${message.content}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `;
                                container.innerHTML = container.innerHTML + messageContent;
                            }
                        });

                        scrollToBottom();
                    }
                };
                xhttp.open("GET", "{{ route('teams.messages-dynamic', $team->id) }}", true);
                xhttp.send();
            }
        </script>
@endsection