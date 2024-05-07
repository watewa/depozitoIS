

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ $newsPost->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  overflow-hidden shadow-sm sm:rounded-lg">
                <div class="block p-6 text-gray-900">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <img style="height: 30px; width: 30px; object-fit: cover; border-radius: 50%;" src="{{ $newsPost->author->getProfilePicturePathAttribute() }}" alt="{{ 'Profile Picture' }}">
                            <span class="ml-3">
                                {{ $newsPost->author->name }}
                            </span>
                        </div>
                        <div>
                            <div><p class="text-muted">Paskelbta: {{ $newsPost->updated_at}}</p></div>
                            <div><p class="text-muted">Atnaujinta: {{ $newsPost->updated_at }}</p></div>
                        </div>
                    </div>
                    <div>
                        <p class="paragraph">
                            {{ $newsPost->content }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>