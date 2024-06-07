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
                        <img style="max-height:480px; object-fit: cover;" src="{{ Storage::url($newsPost->thumbnail) }}" alt="{{ 'News post thumbnail' }}" class="my-3">
                    </div>
                    <div contentEditable="true" id="newsPostContent">
                        {!! $newsPost->content !!}
                    </div>

                    <button type="button" class="bg-secondary btn btn-secondary" id="addParagraph">
                        Naujas paragrafas
                    </button>
                    <button type="button" class="bg-secondary btn btn-secondary" id="addTitle">
                        Naujas paragrafo pavadinimas
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('addParagraph').addEventListener('click', function() {

            const paragraph = document.createElement('p');
            paragraph.setAttribute('class', 'm-3');
            paragraph.innerHTML = "paragrafas...<br/>";

            document.getElementById('newsPostContent').appendChild(paragraph);
        });
        document.getElementById('addTitle').addEventListener('click', function() {

            const title = document.createElement('h2');
            title.setAttribute('class', 'font-semibold text-xl text-gray-800 leading-tight m-3');
            title.innerHTML = "Paragrafo pavadinimas<br/>";

            document.getElementById('newsPostContent').appendChild(title);
        });
        document.getElementById('newsPostContent').addEventListener('keydown', function(event) {
            if (event.keyCode === 13 && !event.shiftKey) {
                event.preventDefault();
                
                var selection = window.getSelection();
                var range = selection.getRangeAt(0);
                var br = document.createElement('br');
                range.deleteContents();
                range.insertNode(br);
                range.setStartAfter(br);
                range.setEndAfter(br);

                selection.removeAllRanges();
                selection.addRange(range);
            }
        });
    </script>
    
</x-app-layout>