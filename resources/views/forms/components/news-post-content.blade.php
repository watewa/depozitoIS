<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div class="bg-white shadow-sm rounded p-3">
        @if($getRecord() != null)
            <div contentEditable="true" id="newsPostContent">
                {!! $getRecord()->content !!}
            </div>
        @else
            <div contentEditable="true" id="newsPostContent">
            </div>
        @endif
    </div>
    <div>  
        <a id="addParagraph" class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 disabled:pointer-events-none disabled:opacity-70 rounded-lg fi-btn-color-gray gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 fi-ac-btn-action" >
            New paragraph
        </a>
        <a id="addTitle" class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 disabled:pointer-events-none disabled:opacity-70 rounded-lg fi-btn-color-gray gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 fi-ac-btn-action" >
            New heading
        </a>
        <a id="addImage" class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 disabled:pointer-events-none disabled:opacity-70 rounded-lg fi-btn-color-gray gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 fi-ac-btn-action" >
            Insert image
        </a>
        <div id="imageDropdown" class="hidden absolute mt-1 w-48 rounded-md bg-white shadow-lg z-10">
            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                @if($getRecord() != null)
                    @if($getRecord()->pictures != null)
                        @foreach ($getRecord()->pictures as $picture)
                            <button id="imageOption" role="menuitem" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" onclick="setImage('{{ $picture }}')">{{ $picture }}</button>
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
    
    <input type="hidden" id="hiddenContentInput" name="hiddenContentInput" wire:model="{{ $getStatePath() }}"/>
    
    <script>
        function setImage(picture) {
            const title = document.createElement('img');
            title.setAttribute('src', 'https://depozitois.cloud/storage/' + picture);

            document.getElementById('newsPostContent').appendChild(title);
            updateHiddenInput();
        }

        document.getElementById('addImage').addEventListener('click', function() {
            document.getElementById('imageDropdown').classList.toggle('hidden');
        });

        document.getElementById('newsPostContent').addEventListener('input', function() {
            updateHiddenInput();
        });

        function updateHiddenInput() {
            const content = document.getElementById('newsPostContent').innerHTML;
            document.getElementById('hiddenContentInput').value = content;
            document.getElementById('hiddenContentInput').dispatchEvent(new Event('input'))
        }
        
        document.getElementById('addParagraph').addEventListener('click', function() {

            const paragraph = document.createElement('p');
            paragraph.setAttribute('class', 'm-3');
            paragraph.innerHTML = "paragrafas...<br/>";

            document.getElementById('newsPostContent').appendChild(paragraph);
            updateHiddenInput();
        });

        document.getElementById('addTitle').addEventListener('click', function() {

            const title = document.createElement('h2');
            title.setAttribute('class', 'font-semibold text-xl text-gray-800 leading-tight m-3');
            title.innerHTML = "Paragrafo pavadinimas<br/>";

            document.getElementById('newsPostContent').appendChild(title);
            updateHiddenInput();
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
</x-dynamic-component>
