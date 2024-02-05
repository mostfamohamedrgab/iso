<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\Customer;
use App\Models\User;
use App\Models\Task;
use App\Models\UserTask;
use Illuminate\Http\Request;
use Storage;
use DB;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('belongs-tasks')) {
            $projects = Project::whereIn('id', auth()->user()->projects->pluck('id')->toArray())->orderBy('created_at','desc')->paginate(100); // Retrieve all projects from the database
        }else{
            $projects = Project::orderBy('created_at','desc')->paginate(100); // Retrieve all projects from the database
        }

        return view('dashboard.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::select('id','name')->get();
        $users = User::select('id','name')->get();
        
        return view('dashboard.projects.create',[
            'customers' => $customers,
            'users' => $users
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
        // Validate the incoming request data
        $data = $request->validate([
            'image' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'objective' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'customer_id' => 'required|exists:customers,id', 
        ]);


        // Create a new project with the validated data
        $project = Project::create($data);

        $project->users()->sync($request->input('users', []));


        if($files  = $request->file('files'))
        {
            foreach($files as $file)
            { 
                ProjectFile::create([
                    'project_id' => $project->id,
                    'file' => $file,
                    'extension' => $file->getClientOriginalExtension(),
                    'name' => $file->getClientOriginalName()
                ]);
            }
        }

        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show( $project)
    {
        $project = Project::findOrFail($project);

        if (auth()->user()->can('belongs-tasks')) {
            // Check if the user is assigned to the project
            if (!$project->users->contains(auth()->user())) {
                abort(403); // User is not assigned to the project, so forbid access
            }
        }

        $tasks = Task::where('project_id',$project->id)->whereNull('task_id')->orderBy('order','asc')->get();
        $userTasks = UserTask::whereIn('task_id',$tasks->pluck('id')->toArray())->get();


        return view('dashboard.projects.show', compact('project','tasks','userTasks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit( $project)
    {
        $project = Project::findOrFail($project);
        $customers = Customer::select('id','name')->get();
        $users = User::select('id','name')->get();

        return view('dashboard.projects.create', compact('project','customers','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $project)
    {
        $project = Project::findOrFail($project);
        // Validate the incoming request data
        $data = $request->validate([
            'image' => 'nullable',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'objective' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'customer_id' => 'required|exists:customers,id', // Ensure customer exists
        ]);

        // Update the project with the validated data
        $project->update($data);
        $project->users()->sync($request->input('users', []));



        if($files  = $request->file('files'))
        {
            foreach($files as $file)
            { 
                ProjectFile::create([
                    'project_id' => $project->id,
                    'file' => $file,
                    'extension' => $file->getClientOriginalExtension(),
                    'name' => $file->getClientOriginalName()
                ]);
            }
        }


        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy( $project)
    {
        $project = Project::findOrFail($project);
        $project->delete();
        return response()->json();
    }


    public function deleteFile($id)
    {
        $file = ProjectFile::findOrFail($id);
        Storage::delete( $file->getRawOriginal('file'));
        $file->delete();

        return response()->json([
            'status' => 200,
            'msg' => 'File deleted'
        ]);
    }
}
