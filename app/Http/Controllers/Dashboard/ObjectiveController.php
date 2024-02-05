<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskFile;
use App\Models\UserTask;
use App\Models\Project;
use Illuminate\Http\Request;
use Storage;
use DB;

class ObjectiveController extends Controller
{
    public function index()
    {   
        $tasks = Task::orderBy('created_at','desc')->whereNotNull('task_id')->paginate(100); // Retrieve all tasks from the database
        
        return view('dashboard.objectives.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tasks = Task::select('id','title')->get();

        return view('dashboard.objectives.create',[
            'tasks' => $tasks
        ]);
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'task_id' => 'required|exists:projects,id',
            'order' => "required"
        ]);

        $parentTask = Task::findOrFail($request->task_id);
        // Create a new task
        $task = Task::create([
            'project_id' => $parentTask->project_id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'order' => $request->input('order'),
            'task_id' => $request->input('task_id'),
        ]);

        // Upload task files
        if ($files = $request->file('files')) {
            foreach ($files as $file) {
                $fileExtension = $file->getClientOriginalExtension();
                $fileName = $file->getClientOriginalName();

                // Store the file and associate it with the task
                $taskFile = TaskFile::create([
                    'task_id' => $task->id,
                    'file' => $file, // Customize the storage path as needed
                    'extension' => $fileExtension,
                    'name' => $fileName,
                ]);
            }
        }

        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show( $taskId)
    {
        $task = Task::findOrFail($taskId);
        $project = $task->project;
        $userTask = [];
        $taskAnalytics = [];
        $report = true; // report about userts who make this task 

        if (auth()->user()->can('belongs-tasks')) {
            // Check if the user is assigned to the project
            if (!$project->users->contains(auth()->user())) {
                abort(403); // User is not assigned to the project, so forbid access
            }

            $attributes = [
                'user_id' => auth()->user()->id,
                'task_id' => $task->id,
            ];
            
            $userTask = UserTask::firstOrCreate($attributes);
            $report = false;
        }else{
            $taskAnalytics = UserTask::where('task_id',$task->id)->get();
        }
        

        
         return view('dashboard.objectives.show',[
            'task' => $task,
            'project' => $project,
            'report' => $report,
            'taskAnalytics' => $taskAnalytics,
            'userTask' => $userTask,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit( $task)
    {
        $tasks = Task::select('id','title')->get();
        $task = Task::findOrFail($task);

        return view('dashboard.objectives.create',[
            'tasks' => $tasks,
            'task' => $task
        ]);
    }

    
    public function update(Request $request,  $task)
    {
        $task = Task::findOrFail($task);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'task_id' => 'required|exists:projects,id',
            "order" => "required"
        ]);

        // Create a new task
        $task->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'task_id' => $request->input('task_id'),
            'order' => $request->input('order'),
        ]);

        // Upload task files
        if ($files = $request->file('files')) {
            foreach ($files as $file) {
                $fileExtension = $file->getClientOriginalExtension();
                $fileName = $file->getClientOriginalName();

                // Store the file and associate it with the task
                $taskFile = TaskFile::create([
                    'task_id' => $task->id,
                    'file' => $file, // Customize the storage path as needed
                    'extension' => $fileExtension,
                    'name' => $fileName,
                ]);
            }
        }

        return response()->json();
    }

   
    public function destroy( $task)
    {
        $task = Task::findOrFail($task);
        $task->delete();
        return response()->json();
    }

    public function deleteFile($id)
    {
        $file = TaskFile::findOrFail($id);
        Storage::delete( $file->getRawOriginal('file'));
        $file->delete();

        return response()->json([
            'status' => 200,
            'msg' => 'File deleted'
        ]);
    }

}
