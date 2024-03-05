<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\ProjectFile;
use App\Models\Customer;
use App\Models\User;
use App\Models\Task;
use App\Models\UserTask;
use App\Models\ProjectRate;
use Illuminate\Http\Request;
use Storage;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProjectSelectedNotification;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class ProjectsController extends Controller
{
   
    public function agree($id)
    {
        $project = Project::findOrFail($id);

        // Check if the user is assigned to the project
        if (!$project->users->contains(auth()->user())) {
            abort(403); // User is not assigned to the project, so forbid access
        }

        $projectUser = DB::table('project_users')
                        ->where('project_id', $id)
                        ->where('user_id', auth()->id())
                        ->first();

        
        if($projectUser->user_answer)
        {
            return redirect(route('projects.accepshow',$project->id));
        }

        return view('dashboard.projects.agree',[
            'project' => $project
        ]);
    }

    public function accept ($id)
    {
        $project = Project::findOrFail($id);

        $projectUser = DB::table('project_users')
                        ->where('project_id', $id)
                        ->where('user_id', auth()->id())
                        ->first();

        // Check if the user is assigned to the project
        if (!$projectUser) {
            abort(403); // User is not assigned to the project, so forbid access
        }



        return view('dashboard.projects.accept',[
            'project' => $project,
            'projectUser' => $projectUser
        ]);
    }

    public function acceptSave(Request $request ,$id)
    {
        $project = Project::findOrFail($id);
        $projectUser = $project->users->where('id', auth()->id())->first();
        // Check if the user is assigned to the project
        if (!$projectUser) {
            abort(403); // User is not assigned to the project, so forbid access
        }

        $projectUser = $projectUser->pivot;
            
        $validatedData['hourly_rate'] = 'required';
        $validatedData['year_of_experience'] = 'nullable';
        $validatedData['used_software_before'] = $project->used_software_before == '1' ? 'required|in:1' : 'required|in:0';
        $validatedData['gender'] = 'required';
        $validatedData['user_computer_skills'] = $project->user_computer_skills ? 'required|in:'. $project->user_computer_skills : 'nullable';
       
        $data = $request->validate($validatedData,[
            'user_computer_skills.in' => 'The selected computer skills are not accepted in this project - ' . $project->user_computer_skills,
        ]);

        $data['user_answer'] = '1';

        $projectUser->update($data);

        return redirect(route('projects.show', $project->id));
        return back()->withSuccess('Saved Successfully');
    }   

    public function report($projectId)
    {
        $project = Project::with(['users', 'tasks', 'userTasks'])->findOrFail($projectId);
        $projectTasks = Task::where('project_id',$project->id)->where('task_id',null)->get();
        $allprojectTasks = Task::where('project_id',$project->id)->get();
        $projectUsers = $project->users;
        $usersTasks = UserTask::where('project_id',$project->id)->get();
        $ProjectRates = ProjectRate::where('project_id',$project->id)->get();

        foreach($usersTasks as $task)
        {
            $task->taken_time = (int) $task->getTimeDiff();
        }

        $efficiencyData = [];

        foreach ($project->users as $user) {
            $userTasks = $project->userTasks->where('user_id', $user->id);
            $totalTasks = $userTasks->count();
            $assistedTasks = $userTasks->where('assisted', true)->count();
            $unassistedTasks = $userTasks->where('assisted', false)->count();
            $totalTaskTime = $userTasks->sum(function ($task) {
                return Carbon::parse($task->start_time)->diffInSeconds(Carbon::parse($task->end_time));
            });
            $totalErrors = $userTasks->sum('errors_count');
            $totalAssists = $userTasks->sum('assisted_count');
            $helpAccessTime = $userTasks->sum('time_spent_getting_help') + $userTasks->sum('time_spent_searching');
    
            $efficiency = ($totalTaskTime > 0) ? (($totalTaskTime - $helpAccessTime) / $totalTaskTime) : 0;
    
            $efficiencyData[] = [
                'user' => $user->name,
                'assisted_rate' => $totalTasks > 0 ? $assistedTasks / $totalTasks : 0,
                'unassisted_rate' => $totalTasks > 0 ? $unassistedTasks / $totalTasks : 0,
                'total_task_time' => gmdate('H:i:s', $totalTaskTime),
                'errors' => $totalErrors,
                'assists' => $totalAssists,
                'help_access_time' => $helpAccessTime,
                'efficiency' => $efficiency
            ];
        }
    


        $pdf = new Dompdf();

        $pdf->loadHtml(View::make('dashboard.projects.report', [
            'project' => $project,
            'usersTasks' => $usersTasks,
            'ProjectRates' => $ProjectRates,
            'allprojectTasks' => $allprojectTasks,
            'projectUsers' => $projectUsers,
            'efficiencyData' => $efficiencyData,
        ])->render());

        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        // Send PDF data as response with appropriate headers
        return $pdf->stream();
    }

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
            'image' => 'nullable',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal' => 'required|string',
            'admin_hourly_rate' => 'required',
            'average_time_to_complete' => 'required',
            'start_date' => 'nullable|date',
            'used_software_before' => 'required',
            'user_computer_skills' => 'nullable',
            'end_date' => 'nullable|date',
            'customer_id' => 'nullable|exists:customers,id', 
        ]);

       
        if($request->user_computer_skills)
        {
            $data['user_computer_skills'] = implode(',', $request->user_computer_skills);
        }


        $users = $request->input('users', []);
        

        // Create a new project with the validated data
        $project = Project::create($data);
        $projectUrl = route('projects.agree', $project->id);

        foreach ($users as $userId) {
            $user = User::findOrFail($userId);
            Mail::to($user->email)->send(new ProjectSelectedNotification($project, $projectUrl,$user));
            $project->users()->attach($userId);
        }


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
        $projectId = $project->id;
        $notStartedYet = 0;

        if (auth()->user()->can('belongs-tasks')) {
            // Check if the user is assigned to the project
            if (!$project->users->contains(auth()->user())) {
                abort(403); // User is not assigned to the project, so forbid access
            }

            $projectUser = DB::table('project_users')
                ->where('project_id', $project->id)
                ->where('user_id', auth()->id())
                ->first();

        }else{

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

        $tasks = Task::where('project_id',$project->id)->whereNull('task_id')->orderBy('order','asc')->get();
        $userTasks = UserTask::whereIn('task_id',$tasks->pluck('id')->toArray())->get();


     


        if(isset($projectUser) AND !$projectUser->user_answer)
        {
            return redirect(route('projects.agree',$project->id));
        }
      
        return view('dashboard.projects.show', compact('project','tasks','userTasks','notStartedYet'));
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
        $projectUsers = $project->users()->withPivot('hourly_rate')->get();


        return view('dashboard.projects.create', compact('project','customers','users','projectUsers'));
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
            'goal' => 'required|string',
            'admin_hourly_rate' => 'required',
            'average_time_to_complete' => 'required',
            'used_software_before' => 'required',
            'user_computer_skills' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'customer_id' => 'nullable|exists:customers,id', // Ensure customer exists
        ]);

     
        if($request->user_computer_skills)
        {
            $data['user_computer_skills'] = implode(',', $request->user_computer_skills);
        }


        // Update the project with the validated data
        $project->update($data);
        $oldUsers  =    $project->users->pluck('id')->toArray();
        $project->users()->detach();

        // Create a new project with the validated data
        $users = $request->input('users', []);
        $projectUrl = route('projects.agree', $project->id);

        foreach ($users as $userId) {

            if(!in_array($userId,$oldUsers))
            {
                $user = User::findOrFail($userId);
                Mail::to($user->email)->send(new ProjectSelectedNotification($project, $projectUrl,$user));
            }
            $project->users()->attach($userId);
        }



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

    public function rate(Request $request,$project)
    {
        $validatedData = $request->validate([
            'rate' => 'required|numeric|min:0|max:10',
            'reason' => 'nullable',
        ]);

        $validatedData['user_id'] = auth()->id();
        $validatedData['project_id'] = $project;
        $rate = ProjectRate::where('user_id',auth()->id())->where('project_id',$project)->first();

        if($rate)
        {
            $rate->update($validatedData);
        }else{
            ProjectRate::create($validatedData);
        }

        return redirect()->back()->with('success', 'Thank you for completing this assessment.');
    }
}
