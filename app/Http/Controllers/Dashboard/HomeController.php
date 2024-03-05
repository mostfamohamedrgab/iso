<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Task;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalCustomersCount = Customer::count();
        $totalProjectsCount = Project::count();
        $totalTasksCount = Task::count();
        $latestProjects = Project::orderBy('created_at','desc')->limit(10)->get();
        $latestTasks = Task::orderBy('created_at','desc')->limit(10)->get();

        if (auth()->user()->can('belongs-tasks')) {

            $projectIds = DB::table('projects')
                                    ->join('project_users', 'projects.id', '=', 'project_users.project_id')
                                    ->where('project_users.user_id', auth()->id())
                                    ->select('projects.id')
                                    ->get()->pluck('id');
                                    
            $latestProjects = Project::whereIn('id', $projectIds)->orderBy('created_at','desc')->limit(10)->get();

            $tasks = Task::whereIn('project_id', $projectIds)->orderBy('created_at', 'desc')->count();
            $totalProjectsCount = count($projectIds);
        }

        
        return view('home', compact('totalCustomersCount', 'totalProjectsCount', 'totalTasksCount','latestProjects','latestTasks'));
    }
}
