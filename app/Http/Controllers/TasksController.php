<?php

namespace App\Http\Controllers;
use Auth;

use App\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
{

    public function __construct() {
        $this->middleware('auth');


    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        DB::enableQueryLog();
        $request->validate([
            'search' => 'nullable|min:3|max:255|regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        $search = $request->search;
        $sort = $request->sort;
        $tasks = new Tasks();
        if ($sort == 'old') {
            $tasks = $tasks->orderBy('created_at', 'asc');
        } elseif ($sort == 'asc') {
            $tasks = $tasks->orderBy('title', 'asc');
        } elseif ($sort == 'desc') {
            $tasks = $tasks->orderBy('title', 'desc');
        } else {
            $tasks = $tasks->orderBy('created_at', 'desc');
        }
        if (!empty($search)) {
            $tasks = $tasks->where("title", "like", "%" . $search . "%");
        }

        $tasks = $tasks->paginate(3);

        return view('tasks.index', compact('tasks', 'sort', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|unique:tasks|max:30|regex:/^[a-zA-Z0-9_ -]+$/',
            'description' => 'nullable|regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        Tasks::create(['title' => $request->title,'description' => $request->description]);
        return redirect(route('tasks.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function show(Tasks $tasks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function edit(Tasks $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tasks $task)
    {
        $request->validate([
            'title' => 'required|min:3|unique:tasks|max:30|regex:/^[a-zA-Z0-9_ -]+$/',
            'desc' => 'nullable|regex:/^[a-zA-Z0-9_ -]+$/'
        ]);
        $task->update($request->only('title'));
        $page = $request->input('on_page');
        if (empty($page)) {
            $arr = array();
        } else {
            $arr = ['page' => $page];
        }

        return redirect()->route('tasks.index', $arr);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tasks $task)
    {
        $task->delete();
        return redirect(route('tasks.index'));
    }
}
