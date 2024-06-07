<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  overflow-hidden shadow-sm sm:rounded-lg">
                <div class="block p-6 text-gray-900">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3">
                            {{ $newsPost->title }}
                        </h2>
                        <div>
                            <div><p class="text-muted">Paskelbta: {{ $newsPost->updated_at}}</p></div>
                            <div><p class="text-muted">Atnaujinta: {{ $newsPost->updated_at }}</p></div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <img style="height: 30px; width: 30px; object-fit: cover; border-radius: 50%;" src="{{ $newsPost->author->getProfilePicturePathAttribute() }}" alt="{{ 'Profile Picture' }}">
                        <span class="ml-3">
                            {{ $newsPost->author->name }}
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <img style="width: 100%; max-height:720px; object-fit: cover;" src="{{ Storage::url($newsPost->thumbnail) }}" alt="{{ 'News post thumbnail' }}" class="my-3">
                    </div>
                    <div class="">
                        {!! $newsPost->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>