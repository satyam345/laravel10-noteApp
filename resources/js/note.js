document.addEventListener('DOMContentLoaded', function() {

  const form = $('#create-note-form');
  form.on('submit', function(e) {
    let alertContainer = $('#createNoteModal #alert-container');
    e.preventDefault();
    $.ajax({
      url: "/note/customCreate",
      method: 'POST',
      data: new FormData(this),
      processData: false,
      contentType: false,
      headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
      },
      success: function(data) {
        const colors = data.success ?
            { bg: 'bg-green-100', text: 'text-green-700', border: 'border-green-400' } :
            { bg: 'bg-red-100', text: 'text-red-700', border: 'border-red-400' };
        const alertHtml = `
          <div class="relative mb-4 px-4 py-3 ${colors.text} ${colors.bg} border ${colors.border} rounded flex justify-between" role="alert">
            <span class="block sm:inline">${data.message}</span>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
              <svg class="h-4 w-4 ${colors.text}" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
              </svg>
            </button>
          </div>`;
        alertContainer.empty().html(alertHtml);
        if(data.success) {
            setTimeout(function() {
              form[0].reset();
              alertContainer.empty()
              toggleModal('createNoteModal')
              window.location.href = data.redirect;
            }, 2000);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error:', error);
        const errorHtml = `
          <div class="relative mb-4 px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded flex justify-between" role="alert">
            <span class="block sm:inline">An error occurred while processing your request.</span>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
              <svg class="h-4 w-4 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
              </svg>
            </button>
          </div>`;
        alertContainer.empty().html(errorHtml);
      }
    });
  });

  $(document).on('click', '.note-delete-button', function(){
    let alertContainer = $('#viewNoteModal #alert-container');
    let mainListAlertContainer = $('.main-list.note-container #alert-container');
    const note_id = $(this).attr('note-id');
    console.log(note_id);

    if (confirm('Are you sure you want to delete this note?')) {
      $.ajax({
        url: "/note/customDelete",
        method: 'POST',
        data: {
          id: note_id,
        },
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(data) {
          console.log(data);
          const colors = data.success ?
            { bg: 'bg-green-100', text: 'text-green-700', border: 'border-green-400' } :
            { bg: 'bg-red-100', text: 'text-red-700', border: 'border-red-400' };
          // Create alert element
          const alertHtml = `
            <div class="relative mb-4 px-4 py-3 ${colors.text} ${colors.bg} border ${colors.border} rounded flex justify-between" role="alert">
              <span class="block sm:inline">${data.message}</span>
              <button type="button" class="btn-close" onclick="this.parentElement.remove()">
                <svg class="h-4 w-4 ${colors.text}" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
              </button>
            </div>`;
          if($('#viewNoteModal').hasClass('hidden')){
            mainListAlertContainer.empty().html(alertHtml)
          }
          else{
            alertContainer.empty().html(alertHtml);
          }

          if(data.success) {
            setTimeout(function() {
              if($('#viewNoteModal').hasClass('hidden')){
                mainListAlertContainer.empty()
              }
              else{
                alertContainer.empty()
                toggleModal('viewNoteModal')
              }
              window.location.href = data.redirect;
            }, 2000);
          }
        },
        error: function(xhr, status, error) {
          console.error('Error:', error);
          const errorHtml = `
            <div class="relative mb-4 px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded flex justify-between" role="alert">
              <span class="block sm:inline">An error occurred while processing your request.</span>
              <button type="button" class="btn-close" onclick="this.parentElement.remove()">
                <svg class="h-4 w-4 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
              </button>
            </div>`;
          if($('#viewNoteModal').hasClass('hidden')){
            mainListAlertContainer.empty().html(alertHtml)
          }
          else{
            alertContainer.empty().html(alertHtml);
          }
        }
      })
    }
  });

  $(document).on('click', '.note-view-button', function(){
    let alertContainer = $('#viewNoteModal #alert-container');
    const note_id = $(this).attr('note-id');
    $.ajax({
      url: "/note/customShow",
      method: 'POST',
      data: {
        id: note_id,
      },
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
      success: function(data) {
        // Set colors based on status
        const colors = data.success ?
            { bg: 'bg-green-100', text: 'text-green-700', border: 'border-green-400' } :
            { bg: 'bg-red-100', text: 'text-red-700', border: 'border-red-400' };
        // Create alert element
        const alertHtml = `
          <div class="relative mb-4 px-4 py-3 ${colors.text} ${colors.bg} border ${colors.border} rounded flex justify-between" role="alert">
            <span class="block sm:inline">${data.message}</span>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
              <svg class="h-4 w-4 ${colors.text}" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
              </svg>
            </button>
          </div>`;
        if(!data.success){
          alertContainer.empty().html(alertHtml);
        }
        $('#viewNoteModal .note-title').html(data.note.title);
        $('#viewNoteModal .note-body').html(data.note.note);
        $('#viewNoteModal .note-edit-button').attr('note-id', data.note.id);
        $('#viewNoteModal .note-delete-button').attr('note-id', data.note.id);
      },
      error: function(xhr, status, error) {
        console.error('Error:', error);
        const errorHtml = `
          <div class="relative mb-4 px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded flex justify-between" role="alert">
            <span class="block sm:inline">An error occurred while processing your request.</span>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
              <svg class="h-4 w-4 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
              </svg>
            </button>
          </div>`;
        alertContainer.empty().html(errorHtml);
      }
    });
  });

  const editForm = $('#edit-note-form');
  $(document).on('click', '.note-edit-button', function(){
    let alertContainer = $('#editNoteModal #alert-container');
    const note_id = $(this).attr('note-id');
    $.ajax({
      url: "/note/customShow",
      method: 'POST',
      data: {
        id: note_id,
      },
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
      success: function(data) {
        // Set colors based on status
        const colors = data.success ?
            { bg: 'bg-green-100', text: 'text-green-700', border: 'border-green-400' } :
            { bg: 'bg-red-100', text: 'text-red-700', border: 'border-red-400' };
        // Create alert element
        const alertHtml = `
          <div class="relative mb-4 px-4 py-3 ${colors.text} ${colors.bg} border ${colors.border} rounded flex justify-between" role="alert">
            <span class="block sm:inline">${data.message}</span>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
              <svg class="h-4 w-4 ${colors.text}" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
              </svg>
            </button>
          </div>`;
        if(!data.success){
          alertContainer.empty().html(alertHtml);
        }
        $('#editNoteModal #edit-note-form input[name="id"]').val(data.note.id);
        $('#editNoteModal #edit-note-form input[name="title"]').val(data.note.title);
        $('#editNoteModal #edit-note-form textarea[name="note"]').val(data.note.note);
      },
      error: function(xhr, status, error) {
        console.error('Error:', error);
        const errorHtml = `
          <div class="relative mb-4 px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded flex justify-between" role="alert">
            <span class="block sm:inline">An error occurred while processing your request.</span>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
              <svg class="h-4 w-4 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
              </svg>
            </button>
          </div>`;
        alertContainer.empty().html(errorHtml);
      }
    });
  });

  editForm.on('submit', function(e) {
    let alertContainer = $('#editNoteModal #alert-container');
    e.preventDefault();
    $.ajax({
      url: '/note/customUpdate',
      method: 'POST',
      data: new FormData(this),
      processData: false,
      contentType: false,
      headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
      },
      success: function(data) {
        const colors = data.success ?
            { bg: 'bg-green-100', text: 'text-green-700', border: 'border-green-400' } :
            { bg: 'bg-red-100', text: 'text-red-700', border: 'border-red-400' };
        const alertHtml = `
          <div class="relative mb-4 px-4 py-3 ${colors.text} ${colors.bg} border ${colors.border} rounded flex justify-between" role="alert">
            <span class="block sm:inline">${data.message}</span>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
              <svg class="h-4 w-4 ${colors.text}" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
              </svg>
            </button>
          </div>`;
        alertContainer.empty().html(alertHtml);
        if(data.success) {
            setTimeout(function() {
              form[0].reset();
              alertContainer.empty()
              toggleModal('editNoteModal')
              window.location.href = data.redirect;
            }, 2000);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error:', error);
        const errorHtml = `
          <div class="relative mb-4 px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded flex justify-between" role="alert">
            <span class="block sm:inline">An error occurred while processing your request.</span>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
              <svg class="h-4 w-4 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
              </svg>
            </button>
          </div>`;
        alertContainer.empty().html(errorHtml);
      }
    });

  })

});

window.toggleModal = function(modalId) {

  const modal = document.getElementById(modalId);
  const alertContainer = modal.querySelector('#alert-container');
  const overlay = modal.querySelector('.modal-overlay');
  const content = modal.querySelector('.modal-content');
  alertContainer.innerHTML = '';

  if (modal.classList.contains('hidden')) {

      document.querySelectorAll('.modal').forEach(el => el.classList.add('hidden'));
      document.querySelectorAll('.modal-overlay').forEach(el => el.classList.remove('active'));
      document.querySelectorAll('.modal-content').forEach(el => el.classList.remove('active'));

      // Set the initial state for animation
      content.classList.remove('active');
      overlay.classList.remove('active');
      setTimeout(() => {
        modal.classList.remove('hidden');
      }, 350)

      // Trigger reflow to ensure the transition starts correctly
      void content.offsetWidth;

      // Add the active class to start the animation
      overlay.classList.add('active');
      content.classList.add('active');
  } else {
      overlay.classList.remove('active');
      content.classList.remove('active');

      if (modal.querySelector('.note-edit-button')) {
        const deleteButton = modal.querySelector('.note-delete-button');
        if (deleteButton) {
          deleteButton.removeAttribute('note-id');
        } else {
          modal.querySelector('.note-edit-button').removeAttribute('note-id');
        }
      }

      // Wait for the animation to complete before hiding the modal
      setTimeout(() => {
          modal.classList.add('hidden');
      }, 300); // Match the duration of the CSS animation
  }
}
