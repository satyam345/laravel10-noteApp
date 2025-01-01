<x-app-layout>
    <div class="note-container single-note">
        <div class="notes-header flex justify-between w-full mx-2.5 my-5 items-center">
            <h4>Edit your note</h4>
        </div>
        <form action="{{ route('note.update', $note) }}" method="POST" class="note">
            @csrf
            @method('PUT')
            <input type="text" name="title" class="note-title" placeholder="Enter your note title" value="{{ $note->title }}">
            <textarea name="note" rows="10" class="note-desc" placeholder="Enter your note here">{{ $note->note }}</textarea>
            <div class="note-buttons">
                <a href="{{ route('note.index') }}" class="note-cancel-button">Cancel</a>
                <button class="note-submit-button">Submit</button>
            </div>
        </form>
    </div>
</x-app-layout>