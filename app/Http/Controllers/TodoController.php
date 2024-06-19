<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoController extends Controller
{
    public function destroy($id)
    {
        if($id != null){
            $todo = Todo::find($id);
            if($todo){
                $todo->delete();
            }
        }
    }
    public function index()
    {
        $todos = Todo::orderBy('created_at', 'desc')->get();
        return Inertia::render("Home", ['todos' => $todos]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'max:255',
            'subject' => 'required|max:1000'
        ]);

        if($request->id != null){
            $todo = Todo::find($request->id);
            if($todo){
                $todo->title = $validated['title'];
                $todo->subject = $validated['subject'];
                $todo->save();
            }
        }else{
            $date = date('Y-m-d', time());
            $todo = Todo::create([
                'title' => $validated['title'],
                'subject' => $validated['subject'],
                'date' => $date,
            ]);
        }
    }
}
