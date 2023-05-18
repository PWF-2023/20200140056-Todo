<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class TodoController extends Controller
{
    public function index()
    {

        $todos = Todo::where('user_id', auth()->user()->id)

        ->orderBy('is_complete', 'asc')
        ->orderBy('created_at', 'desc')
        ->get();

        // $todos = Todo::where('user_id', auth()->user()->id)->get();

        // dd($todos);

        $todosCompleted = Todo::where('user_id', auth()->user()->id)
        ->where('is_complete', true)
        ->count();
        return view('todo.index', compact('todos', 'todosCompleted'));
    }

    public function create()
    {
        return view('todo.create');
    }

    public function edit(Todo $todo)
    {
        // if (auth()->user()->id == $todo->user_id)
        // {
        //     //dd($todo);
        //     return view('todo.edit', compact('todo'));
        // } else {
        //     // abort(403);
        //     // abort(403, 'Not authorized');
        //     return redirect()->route('todo.index')->with('danger',
        //     'You are not authorized to edit this todo!');
        // }

        //Code after Refactoring
        if(auth()->user()->id = $todo->user_id){
            return view('todo.edit', compact('todo'));
        }
        return redirect()->route('todo.index')->with('danger', 'You are not authorized to edit this todo!');
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);


        // Practical
        // $todo = new Todo;
        // $todo->title = $request->title;
        // $todo->user_id = auth()->user()->id;
        // $todo->save();

        // Query Builder way
        // DB::table('todos')->insert([
        //      'title'=>$request->title,
        //      'user_id => auth()->user()->id,
        //      'created_at' => now(),
        //      'updated_at' => now(),
        // ])

        // Eloquent Way - Readable
        // $todo = Todo::create([
        //     'title' => ucfirst($request->title),
        //     'user_id' => auth()->user()->id,
        // ]);

        // Eloquent Way - Shortest
        // $request->user()->todos()->create($request->all());
        // $request->user()->todos()->create([

        //     'title' => ucfirst($request->title),

        // ]);

        $todo->update([
            'title' => ucfirst($request->title),
        ]);
        return redirect()->route('todo.index')->with('success', 'Todo updated successfully!');
    }

    public function complete(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id)
        {
            $todo->update([
                'is_complete' => true,
            ]);
            return redirect()->route('todo.index')->with('success', 'Todo completed succesfully!');
        } else {
            return redirect()->route('todo.index')->with('danger','You are not authorized to complete this todo!');
        }
    }
    public function uncomplete(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id)
        {
            $todo->update([
                'is_complete' => false,
            ]);
            return redirect()->route('todo.index')->with('success', 'Todo uncompleted succesfully!');
        } else {
            return redirect()->route('todo.index')->with('danger','You are not authorized to uncomplete this todo!');
        }
    }


    public function store(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);


        // Practical
        // $todo = new Todo;
        // $todo->title = $request->title;
        // $todo->user_id = auth()->user()->id;
        // $todo->save();

        // Query Builder way
        // DB::table('todos')->insert([
        //      'title'=>$request->title,
        //      'user_id => auth()->user()->id,
        //      'created_at' => now(),
        //      'updated_at' => now(),
        // ])

        // Eloquent Way - Readable
        // $todo = Todo::create([
        //     'title' => ucfirst($request->title),
        //     'user_id' => auth()->user()->id,
        // ]);

        // Eloquent Way - Shortest
        // $request->user()->todos()->create($request->all());
        $request->user()->todos()->create([

            'title' => ucfirst($request->title),

        ]);
        return redirect()->route('todo.index')->with('success', 'Todo created successfully!');
    }

    public function destroy(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->delete();
            // return redirect()->route('todo.index')->with('success', 'Todo deleted successfully');
            return to_route('todo.index')->with('success', 'Todo deleted successfully');
        }else{
            return to_route('todo.index')->with('danger', 'You are not authorized to delete this todo!');
        }
    }

    public function destroyCompleted(Todo $todo)
    {
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
        ->where('is_complete', true)
        ->get();
        foreach ($todosCompleted as $todo){
            $todo->delete();
        }

        // dd($todoscompleted);
        return to_route('todo.index')->with('success', 'All completed todos deleted successfully!');
    }
}
