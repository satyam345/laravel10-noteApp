<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::query()
            ->where('user_id', request()->user()->id)
            ->orderBy('updated_at', 'desc')
            ->paginate(15);
        return view('note.index', ['notes' => $notes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('note.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string'],
            'note' => ['required', 'string']
        ]);
        $data['user_id'] = $request->user()->id;
        $note = Note::create($data);

        return to_route('note.index', $note)->with('message', 'Note was created');
    }

    public function storeAjax(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => ['required', 'string'],
                'note' => ['required', 'string'],
                'customFileUpload' => ['nullable', 'mimes:jpeg,png,jpg,gif,txt,pdf', 'max:2048']
            ]);
            
            $filePath = null;
            if ($request->hasFile('customFileUpload')) {
                $image = $request->file('customFileUpload');
    
                // Optimize and resize the image
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Resize and save optimized image
                $optimizedImage = Image::make($image)
                    ->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode($image->getClientOriginalExtension(), 75); // Reduce quality to 75%
    
                $optimizedImage->save(public_path('uploads') . '/' . $filename);
                $filePath = 'uploads/' . $filename;
            }

            $data['file_path'] = $filePath;
            $data['user_id'] = $request->user()->id;
            
            $note = Note::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Note created successfully',
                'note' => $note,
                'redirect' => route('note.index')
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the note',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        if($note->user_id !== request()->user()->id){
            abort(403);
        }

        return view('note.show', ['note' => $note]);
    }

    public function showAjax(Request $request)
    {
        $note_id = $request->input('id');
        $note = Note::findOrFail($note_id);

        if (!$note) {
            return response()->json([
                'success' => false,
                'message' => 'Note not found'
            ], 404);
        }

        // Check authorization
        if ($note->user_id !== $request->user()->id) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            abort(403);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'note' => $note,
                'message' => 'Note Found'
            ]);
        }

        return view('note.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        if($note->user_id !== request()->user()->id){
            abort(403);
        }
        return view('note.edit', ['note' => $note]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        if($note->user_id !== request()->user()->id){
            abort(403);
        }
        $data = $request->validate([
            'title' => ['required', 'string'],
            'note' => ['required', 'string']
        ]);
        $note->update($data);

        return to_route('note.index', $note)->with('message', 'Note was updated');
    }

    public function updateAjax(Request $request){
        try {
            $data = $request->validate([
                'title' => ['required', 'string'],
                'note' => ['required', 'string'],
                'id' => ['required', 'integer']
            ]);

            $note = Note::where('id', $data['id'])->first();

            if (!$note) {
                return response()->json([
                    'success' => false,
                    'message' => 'Note not found'
                ], 404);
            }

            if ($note->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $note->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Note updated successfully',
                'note' => $note,
                'redirect' => route('note.index')
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        if($note->user_id !== request()->user()->id){
            abort(403);
        }
        $note->delete();

        return to_route('note.index')->with('message', 'Note was deleted');

    }

    public function destroyAjax(Request $request)
    {
        $note_id = $request->input('id');
        $note = Note::findOrFail($note_id);

        if (!$note) {
            return response()->json([
                'success' => false,
                'message' => 'Note not found'
            ], 404);
        }

        // Check authorization
        if ($note->user_id !== $request->user()->id) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            abort(403);
        }
        $note->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Note deleted successfully',
                'redirect' => route('note.index')
            ]);
        }

        return view('note.index', compact('note'));

    }
}
