<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Task;

class TasksController extends Controller
{
    public function index()
    {
        // $tasks = Task::all();

        // return view('tasks.index', [
        //     'tasks' => $tasks,
        // ]);

        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
            $data += $this->counts($user);
            
            return view('tasks.index', $data);
        }else {
            return view('welcome');
        }
    }
    
    public function show($id)
    {
        $data = [];
        $task = Task::find($id);

        if (\Auth::user()->id === $task->user_id) {
            $user = \Auth::user();
            
            $data = [
                'user' => $user,
                'task' => $task,
            ];

            return view('tasks.show', $data);
        }else {
            return redirect('/');
        }
    }    
    
    
    // public function show($id)
    // {
    //     $task = Task::find($id);
        
    //     return view('tasks.show', [
    //         'task' => $task,
    //     ]);
    // }
    
    
    public function create()
    {
        $task = new Task;
        
        return view('tasks.create', [
            'task' => $task,
        ]);
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|max:255',
            'content' => 'required|max:255',
        ]);

        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
        ]);

        return redirect('/');
    }
    
    public function edit($id)
    {
        $data = [];
        $task = Task::find($id);

        if (\Auth::user()->id === $task->user_id) {
            $user = \Auth::user();
            
            $data = [
                'user' => $user,
                'task' => $task,
            ];

            return view('tasks.edit', $data);
        }else {
            return redirect('/');
        }
    } 
    
    
    // public function edit($id)
    // {
    //     $task = Task::find($id);
        
    //     return view('tasks.edit', [
    //         'task' => $task,
    //     ]);
    // }
    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required|max:191',
            'content' => 'required|max:191',
            ]);
        
        $task = Task::find($id);

        if (\Auth::user()->id === $task->user_id) {
            $task->status = $request->status;
            $task->content = $request->content;
            $task->save();

            return redirect('/');
        }
    }
    
    
    // public function update(Request $request, $id)
    // {
    //     $this->validate($request, [
    //         'status' => 'required|max:191',
    //         'content' => 'required|max:191',
    //     ]);
        
    //     $task = Task::find($id);
    //     $task->status = $request->status;
    //     $task->content = $request->content;
    //     $task->save();
        
    //     return redirect('/');
    // }
    
    
    public function destroy($id)
    {
        $task = \App\Task::find($id);

        if (\Auth::user()->id === $task->user_id) {
            $task->delete();
        }

        // return redirect()->back();
        
        // $task->delete();
        return redirect('/');

    }
}
