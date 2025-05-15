<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ForumPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['body' => 'required|string']);
    
    $thread->posts()->create([
        'user_id' => auth()->id(),
        'body' => $validated['body']
    ]);

    return redirect()->route('forum.threads.show', $thread);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    
        $post->delete();
        return redirect()->route('forum.threads.show', $thread);
    }
}
