<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ForumThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $threads = Thread::with('tags', 'user')->latest()->paginate(10);
        return inertia('Forum/Threads/Index', ['threads' => $threads]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Forum/Threads/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'tags' => 'array',
        ]);
    
        $thread = auth()->user()->threads()->create($validated);
        $thread->tags()->sync($validated['tags'] ?? []);
    
        return redirect()->route('forum.threads.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $thread->load(['posts.user', 'tags', 'user']);
        return inertia('Forum/Threads/Show', ['thread' => $thread]);
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
        if ($thread->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    
        $thread->delete();
        return redirect()->route('forum.threads.index');
    }
}
