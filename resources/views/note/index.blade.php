<x-app-layout>

    @vite(['resources/js/note.js'])
    <div class="">
        <div class="main-list note-container py-12">
            <div id="alert-container"></div>
            <div class="notes">
                <div class="notes-header flex justify-between w-full mx-2.5 mb-5 items-center">
                    <h4>List Notes</h4>
                    <!-- <a href="{{ route('note.create') }}" class="new-note-btn">Add New Note</a> -->
                    <button type="button" class="new-note-btn" onclick="toggleModal('createNoteModal')">Add New Note</button>
                </div>
                @foreach($notes as $note)
                <div class="note bg-clip-content">
                    <div class="note-body">
                        <div class="flex justify-between">
                            <h3 class="note-title font-bold text-lg">{{ $note->title }}</h3>
                            @if($note->file_path)
                                <img src="{{ asset($note->file_path) }}" alt="{{ $note->title }}" class="w-16 h-16 object-cover rounded" loading="lazy">
                            @else
                                <img src="{{ asset('uploads/default-image.png') }}" alt="Default Image" class="w-16 h-16 object-cover rounded">
                            @endif
                        </div>
                        <div class="">
                            {{ Str::words($note->note, 30) }}
                        </div>
                    </div>
                    <div class="note-buttons">
                        <button type="button" note-id="{{ $note->id }}" class="note-view-button" onclick="toggleModal('viewNoteModal')">View</button>
                        <button type="button" note-id="{{ $note->id }}" class="note-edit-button" onclick="toggleModal('editNoteModal')">Edit</button>
                        <button type="button" note-id="{{ $note->id }}" class="note-delete-button">Delete</button>
                    </div>
                </div>
                @endforeach
            </div>
            {{ $notes->links() }}
        </div>

        <!-- Create Modal -->
        <div class="modal fixed z-10 inset-0 overflow-y-auto hidden" id="createNoteModal">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-gray-500 opacity-0 modal-overlay" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full modal-content opacity-0 scale-95 -translate-y-20" id="modalContent">
                    <div class="modal-header flex justify-between">
                        <h5 class="modal-title text-lg font-medium">Create Note</h5>
                        <button type="button" class="btn-close text-gray-500 hover:text-black" onclick="toggleModal('createNoteModal')">&times;</button>
                    </div>
                    <div id="alert-container"></div>
                    <div class="modal-body">
                        <form id="create-note-form" action="{{ route('note.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <label for="customFileUpload" class="block text-sm font-medium text-gray-700">Attach a file (image, txt, or PDF)</label>
                            <input type="file" name="customFileUpload" id="file" class="w-full border-gray-300 rounded mt-1">
                            @error('customFileUpload')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                            <input type="text" name="title" class="note-title w-full border-gray-300 rounded" placeholder="Title" value="{{ old('title') }}">
                            @error('title')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                            <textarea name="note" rows="10" class="note-desc w-full border-gray-300 rounded" placeholder="Note" >{{ old('note') }}</textarea>
                            @error('note')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                            <div class="note-buttons flex justify-end">
                                <button type="button" class="note-cancel-button text-gray-500 mr-4" onclick="toggleModal('createNoteModal')">Cancel</button>
                                <button type="submit" class="note-submit-button bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Modal -->
        <div class="modal fixed z-10 inset-0 overflow-y-auto hidden" id="viewNoteModal">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-gray-500 opacity-0 modal-overlay" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full lg:w-full modal-content opacity-0 scale-95 -translate-y-20" id="modalContent">
                    <div class="modal-header flex justify-between">
                        <h5 class="modal-title text-lg font-medium">View Note</h5>
                        <button type="button" class="btn-close text-gray-500 hover:text-black" onclick="toggleModal('viewNoteModal')">&times;</button>
                    </div>
                    <div id="alert-container"></div>
                    <div class="modal-body">
                        <div class="note-container single-note">
                            <div class="note-header">
                                <div class="notes-header flex justify-between w-full mx-2.5 my-5 items-center">
                                    <h4 class="text-2xl note-title"></h4>
                                </div>
                                <div class="note-buttons">
                                    <button type="button" class="note-edit-button" onclick="toggleModal('editNoteModal')">Edit</button>
                                    <button type="button" class="note-delete-button">Delete</button>
                                </div>
                            </div>
                            <div class="note">
                                <div class="note-body"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fixed z-10 inset-0 overflow-y-auto hidden" id="editNoteModal">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-gray-500 opacity-0 modal-overlay" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full lg:w-full modal-content opacity-0 scale-95 -translate-y-20" id="modalContent">
                    <div class="modal-header flex justify-between">
                        <h5 class="modal-title text-lg font-medium">Edit Note</h5>
                        <button type="button" class="btn-close text-gray-500 hover:text-black" onclick="toggleModal('editNoteModal')">&times;</button>
                    </div>
                    <div id="alert-container"></div>
                    <div class="modal-body">
                        <div class="note-container single-note">
                            <form class="note" id="edit-note-form">
                                @csrf
                                <input type="hidden" name="id" class="note-id">
                                <input type="text" name="title" class="note-title" placeholder="Enter your note title">
                                <textarea name="note" rows="10" class="note-desc" placeholder="Enter your note here"></textarea>
                                <div class="note-buttons">
                                    <button type="button" onclick="toggleModal('editNoteModal')" class="note-cancel-button">Cancel</button>
                                    <button type="submit" class="note-submit-button">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
