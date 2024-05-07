<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ __('Naujienos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    
                <div class="m-3 ">
                    <form action="{{ route('news.index') }}" method="GET">
                        <div class="d-inline-flex">
                            <div class="border border-secondary rounded-start">
                                <input class="border-0 border-secondary rounded-start custom-input" type="text" name="search" placeholder="Search...">
                            </div>
                            <div class="border border-secondary px-3 rounded-end bg-secondary text-white d-inline-flex align-items-center">
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="m-3">{{ $newsPosts->onEachSide(1)->links() }}</div>
                
                @foreach ($newsPosts as $newsPost)
                    <div class="d-none d-md-block">
                        <a href="{{ route('news.show', $newsPost->id) }}" >
                            <div class="d-flex align-items-center justify-content-left border border-light rounded shadow-sm m-3">
                                <div class="">
                                    <img style="height: 75px; width: 75px;" class="rounded-start" src="{{ $newsPost->getThumbnailPathAttribute() }}" alt="Thumbnail">
                                </div>
                                <div class="container m-0">
                                    <div class="row">
                                        <div class="col-4 d-flex align-items-center">
                                            <div>
                                                {{ Str::limit($newsPost->title, 40) }}
                                            </div>
                                        </div>
                                        <div class="col-4 d-flex align-items-center ">
                                            Autorius:&nbsp;
                                            <img style="height: 30px; width: 30px; object-fit: cover; border-radius: 50%;" src="{{ $newsPost->author->getProfilePicturePathAttribute() }}" alt="{{ 'Profile Picture' }}">
                                            <span class="">
                                                &nbsp;{{ $newsPost->author->name }}
                                            </span>
                                        </div>
                                        <div class="col-4 align-items-center">
                                            <div><p class="text-muted">Paskelbta: {{ $newsPost->updated_at->format('Y-m-d') }}</p></div>
                                            <div><p class="text-muted">Atnaujinta: {{ $newsPost->updated_at->format('Y-m-d') }}</p></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="d-block d-md-none">
                        <a href="{{ route('news.show', $newsPost->id) }}" >
                            <div class="d-flex align-items-center justify-content-left border border-light rounded shadow-sm m-3">
                                <div class="">
                                    <img style="height: 75px; width: 75px;" class="rounded-start" src="{{ $newsPost->getThumbnailPathAttribute() }}" alt="Thumbnail">
                                </div>
                                <div class="container m-0">
                                    <div class="row">
                                        <div class="col-12 d-flex align-items-center">
                                            <div>
                                                {{ Str::limit($newsPost->title, 40) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
                
                <div class="m-3">{{ $newsPosts->onEachSide(1)->links() }}</div>
                
            </div>
        </div>
    </div>
</x-app-layout>
