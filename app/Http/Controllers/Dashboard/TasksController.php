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

            $tasks = Task::whereIn('project_id', $projectIds)->whereNull('task_id')->orderBy('created_at', 'desc')->orderBy('order','asc')->paginate(100);
            
        }else{
            $tasks = Task::orderByDesc('created_at')
                    ->orderBy('order','asc')
                    ->paginate(100);
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
        $data = $request->validate([
            'order' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            "can_task_be_partially_completed" => "nullable",
            'weight' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
        ]);

        // Create a new task
        $task = Task::create($data);

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

         // Check if subtask data exists
         if ($request->has('subtask_order') AND $request->can_task_be_partially_completed) {
            // Get subtask data from the request
            $subtaskOrders = $request->input('subtask_order');
            $subtaskTitles = $request->input('subtask_title');
            $subtaskWeights = $request->input('subtask_weight');
            $subtaskDescriptions = $request->input('subtask_description');
            $subtaskFiles = $request->file('files'); // Handle file uploads for subtasks

            // Loop through each subtask data and create subtasks
            foreach ($subtaskOrders as $key => $order) {
                $subtaskData = [
                    'order' => $order,
                    'title' => $subtaskTitles[$key],
                    'weight' => $subtaskWeights[$key],
                    'description' => $subtaskDescriptions[$key],
                    'project_id' => $task->project_id,
                    'task_id' => $task->id
                ];

                // Create the subtask
                $subtask = Task::create($subtaskData);
                
                // Upload task files
                if (isset($request->subfiles[$key])) {
                    $files = $request->subfiles[$key];
                    foreach ($files as $file) {
                        $fileExtension = $file->getClientOriginalExtension();
                        $fileName = $file->getClientOriginalName();

                        // Store the file and associate it with the task
                        $taskFile = TaskFile::create([
                            'task_id' => $subtask->id,
                            'file' => $file, // Customize the storage path as needed
                            'extension' => $fileExtension,
                            'name' => $fileName,
                        ]);
                    }
                }
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
        $notStartedYet = 0;
        
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
            
            $userTasks = UserTask::OrwhereIn('task_id',$tasks->pluck('id')->toArray())->where('user_id', auth()->id())->get();

            $report = false;
        }else{
            $taskAnalytics = UserTask::where('task_id',$task->id)->get();

            foreach($project->users as $user)
            {
                $notStartedYetCheck = UserTask::where('user_id', $user->id)->where('task_id', $task->id)->where('status','!=','not started yet')->first();

                if(!$notStartedYetCheck)
                {   
                    $notStartedYet++;
                }elseif($notStartedYet AND $notStartedYet->status == 'not started yet')
                {
                    $notStartedYet++;
                }
            }

        }

        $projectTasks = Task::where('project_id',$project->id)->whereNull('task_id')->orderBy('order','asc')->get();
        $ProjectRate = ProjectRate::where('project_id',$project->id)->where('user_id', auth()->id())->first();


        $projectUser = DB::table('project_users')
                        ->where('project_id', $project->id)
                        ->where('user_id', auth()->id())
                        ->first();

        if($projectUser AND !$projectUser->user_answer AND auth()->user()->can('belongs-tasks'))
        {
            return redirect(route('projects.agree',$project->id));
        }
        
         return view('dashboard.tasks.show',[
            'task' => $task,
            'projectTasks' => $projectTasks,
            'ProjectRate' => $ProjectRate,
            'tasks' => $tasks,
            'project' => $project,
            'report' => $report,
            'notStartedYet' => $notStartedYet,
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

        $data = $request->validate([
            'order' => 'required',
            'can_task_be_partially_completed' => 'nullable',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'weight' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
        ]);



        // Create a new task
        $task->update($data);

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

         // Check if subtask data exists
         if ($request->has('subtask_order') AND $request->can_task_be_partially_completed) {
            // Get subtask data from the request
            $subtaskOrders = $request->input('subtask_order');
            $subtaskTitles = $request->input('subtask_title');
            $subtaskWeights = $request->input('subtask_weight');
            $subtaskDescriptions = $request->input('subtask_description');
            $subtaskFiles = $request->file('files'); // Handle file uploads for subtasks

            // Loop through each subtask data and create subtasks
            foreach ($subtaskOrders as $key => $order) {
                $subtaskData = [
                    'order' => $order,
                    'title' => $subtaskTitles[$key],
                    'weight' => $subtaskWeights[$key],
                    'description' => $subtaskDescriptions[$key],
                    'project_id' => $task->project_id,
                    'task_id' => $task->id
                ];

                // Create the subtask
                $subtask = Task::create($subtaskData);
                
                // Upload task files
                if (isset($request->subfiles[$key])) {
                    $files = $request->subfiles[$key];
                    foreach ($files as $file) {
                        $fileExtension = $file->getClientOriginalExtension();
                        $fileName = $file->getClientOriginalName();

                        // Store the file and associate it with the task
                        $taskFile = TaskFile::create([
                            'task_id' => $subtask->id,
                            'file' => $file, // Customize the storage path as needed
                            'extension' => $fileExtension,
                            'name' => $fileName,
                        ]);
                    }
                }
            }
        }


        return response()->json();
    }

   
    public function destroy( $task)
    {
        $task = Task::findOrFail($task);

        foreach ($task->files as $file) {
            Storage::delete($file->file); // Assuming you are using Laravel's Storage facade for file deletion
        }

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

        if($request->status == 'It cannot be Completed')
        {
            $messge ='This task has been marked as “Not Successfully Completed. The task will be excluded from your final calculation';
        }else{
            $messge = 'Saved';
        }


        $userTask->update($data);

        return back()->withSuccess($messge);
    }

   

    public function timeTask(UserTask $userTask, Request $request)
    {
        if ($request->ajax()) {
            $action = $request->input('action');

            if ($action === 'start' AND !$userTask->start_time) {
                $userTask->start_time = now();
                $userTask->status = 'Working';
            } elseif ($action === 'end' AND !$userTask->end_time) {
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
