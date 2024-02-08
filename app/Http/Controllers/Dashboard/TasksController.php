<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskFile;
use App\Models\ProjectRate;
use App\Models\UserTask;
use App\Models\Project;
use Illuminate\Http\Request;
use Storage;
use DB;

class TasksController extends Controller
{
    public function index()
    {   

        if (auth()->user()->can('belongs-tasks')) {

            $projectIds = DB::table('projects')
                                ->join('project_users', 'projects.id', '=', 'project_users.project_id')
                                ->where('project_users.user_id', auth()->id())
                                ->select('projects.id')
                                ->get()
                                ->pluck('id');

            $tasks = Task::whereIn('project_id', $projectIds)->whereNull('task_id')->orderBy('created_at', 'desc')->paginate(100);
            
        }else{
            $tasks = Task::orderBy('created_at','desc')->whereNull('task_id')->paginate(100); // Retrieve all tasks from the database
        }
 
        return view('dashboard.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::select('id','title')->get();

        return view('dashboard.tasks.create',[
            'projects' => $projects
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
            'order' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id',
        ]);

        // Create a new task
        $task = Task::create([
            'order' => $request->input('order'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'project_id' => $request->input('project_id'),
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
        $userTasks = [];
        $tasks = [];
        $taskAnalytics = [];
        $report = true; // report about userts who make this task 

        if (auth()->user()->can('belongs-tasks')) {
            // Check if the user is assigned to the project
            if (!$project->users->contains(auth()->user())) {
                abort(403); // User is not assigned to the project, so forbid access
            }
         
            $tasks = Task::where('task_id', $taskId)
                        ->orWhere('id', $taskId)
                        ->orderByRaw("CASE WHEN id = $taskId THEN 0 ELSE 1 END, `order` ASC")
                        ->get();


                                
            foreach($tasks as $relatedTask)
            {
                $attributes = [
                    'user_id' => auth()->user()->id,
                    'task_id' => $relatedTask->id,
                    'project_id' => $project->id
                ];
                
                $userTask = UserTask::firstOrCreate($attributes);
            }
            
            $userTasks = UserTask::OrwhereIn('task_id',$tasks->pluck('id')->toArray())->get();

            $report = false;
        }else{
            $taskAnalytics = UserTask::where('task_id',$task->id)->get();
        }

        $projectTasks = Task::where('project_id',$project->id)->whereNull('task_id')->orderBy('order','asc')->get();
        $ProjectRate = ProjectRate::where('project_id',$project->id)->where('user_id', auth()->id())->first();

        
         return view('dashboard.tasks.show',[
            'task' => $task,
            'projectTasks' => $projectTasks,
            'ProjectRate' => $ProjectRate,
            'tasks' => $tasks,
            'project' => $project,
            'report' => $report,
            'taskAnalytics' => $taskAnalytics,
            'userTasks' => $userTasks,
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
        $projects = Project::select('id','title')->get();
        $task = Task::findOrFail($task);

        return view('dashboard.tasks.create',[
            'projects' => $projects,
            'task' => $task
        ]);
    }

    
    public function update(Request $request,  $task)
    {
        $task = Task::findOrFail($task);

        $request->validate([
            'order' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id',
        ]);

        // Create a new task
        $task->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'project_id' => $request->input('project_id'),
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

    public function rateTask(Request $request,$userTask)
    {
        $userTask = UserTask::where('id',$userTask)->where('user_id',auth()->id())->first();

        if(!$userTask) return abort(404);

        $data = $request->validate([
            "notes" => "nullable",
            "errors_count" => "nullable",
            "assisted" => "nullable",
            "assisted_count" => "nullable",
            "status" => "nullable",
            'time_spent_searching' => "nullable",
            'time_spent_getting_help' => "nullable",
        ]);

        $userTask->update($data);

        return back()->withSuccess('Saved');
    }

   

    public function timeTask(UserTask $userTask, Request $request)
    {
        if ($request->ajax()) {
            $action = $request->input('action');

            if ($action === 'start') {
                $userTask->start_time = now();
                $userTask->status = 'Working';
            } elseif ($action === 'end') {
                $userTask->end_time = now();
            }

            $userTask->save();

            return response()->json([
                'start_time' => $userTask->start_time,
                'end_time' => $userTask->end_time,
            ]);
        }
    }


}
