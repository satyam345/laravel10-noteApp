<x-app-layout>
    <div class="note-container single-note">
        <div class="note-header">
            <div class="notes-header flex justify-between w-full mx-2.5 my-5 items-center">
                <h4 class="text-2xl">Note: {{ $note->title }} (id: {{ $note->id }})</h4>
            </div>
            <div class="note-buttons">
                <a href="{{ route('note.edit', $note) }}" class="note-edit-button">Edit</a>
                <form action="{{ route('note.destroy', $note) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="note-delete-button">Delete</button>
                </form>
            </div>
        </div>
        <div class="note">
            <div class="note-body">
                {{ $note->note }}
            </div>
        </div>
    </div>
</x-app-layout>