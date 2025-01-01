<x-app-layout>
    <div class="note-container single-note">
        <div class="notes-header flex justify-between w-full mx-2.5 my-5 items-center">
            <h4>Add New Note</h4>
        </div>
        <form id="create-note-form" action="{{ route('note.store') }}" method="POST" class="note">
            @csrf
            <input type="text" name="title" class="note-title" placeholder="Title">
            <textarea name="note" rows="10" class="note-desc" placeholder="Note"></textarea>
            <div class="error-message text-red-500 hidden"></div>
            <div class="success-message text-green-500 hidden"></div>
            <div class="note-buttons">
                <a href="{{ route('note.index') }}" class="note-cancel-button">Cancel</a>
                <button type="submit" class="note-submit-button">Submit</button>
            </div>
        </form>

        <script>
            // Change to vanilla JavaScript or wait for app.js to load
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('create-note-form');
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    fetch(this.getAttribute('action'), {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        if (response.headers.get('content-type')?.includes('application/json')) {
                            return response.json();
                        }
                    })
                    .then(data => {
                        console.log('Success:', data);
                        window.location.href = '{{ route("note.index") }}';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        </script>
    </div>
</x-app-layout>