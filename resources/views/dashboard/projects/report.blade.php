<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 3px;
            text-align: left;
            font-size:14px
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Project Report: {{ $project->title }}</h1>
    <h3>Calculations for Tasks Report </h3>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Total Tasks Completed</th>
                <th>Errors (Assisted)</th>
                <th>Errors (Unassisted)</th>
                <th>Total Task Time</th>
                <th>Cost Effectiveness</th>
                <th>Time Efficiency</th>
                <th>Proportion of Tasks Achieved</th>
                <th>Productive Time Ratio</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($projectUsers as $projectUser)
            <tr>
                    <td>{{ $projectUser->name }}</td>
                    <td>{{ $usersTasks->where('status','complated')->where('user_id',$projectUser->id)->count() }}</td>
                    <td>{{ $usersTasks->where('user_id',$projectUser->id)->sum('assisted_count') }}</td>
                    <td>{{ $usersTasks->where('user_id',$projectUser->id)->sum('errors_count') }}</td>
                    <td>{{ $usersTasks->where('user_id',$projectUser->id)->sum('taken_time') }}</td>
                    <td>##</td>
                    @php 
                        $taken_time = $usersTasks->where('user_id',$projectUser->id)->sum('taken_time');
                        $efficiency = 0;
                        if($taken_time)
                        {
                            $taken_time  = $taken_time / $usersTasks->where('status','complated')->where('user_id',$projectUser->id)->count();
                        }
                    @endphp 
                    <td>{{ number_format($taken_time,2) }}</td>
                    @php 
                        $complatedTasksAndObjective =  $usersTasks->where('status','complated')->where('user_id',$projectUser->id)->count();
                        $allprojectTasksUser = $usersTasks->where('user_id',$projectUser->id)->count();

                        if($complatedTasksAndObjective AND $allprojectTasksUser)
                        {
                            $complatedTasksAndObjective = $complatedTasksAndObjective / $allprojectTasksUser;
                        }
                    @endphp 
                    <td>{{ number_format($complatedTasksAndObjective,2) }}</td>

                    @php 

                        $totalTaskTime = $usersTasks->where('user_id',$projectUser->id)->sum('taken_time');
                        $totalHelpTime = $usersTasks->where('user_id',$projectUser->id)->sum('time_spent_getting_help');
                        $totalSearchTime = $usersTasks->where('user_id',$projectUser->id)->sum('time_spent_searching');
                        
                        $productiveTimeRatio = 0;

                        if ($totalTaskTime > 0) {
                            $productiveTimeRatio = ($totalTaskTime - $totalHelpTime - $totalSearchTime) / $totalTaskTime;
                        }

                        @endphp 

                    <td>{{ number_format($productiveTimeRatio * 100, 2) . '%' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <hr>
    <h3>Effectiveness Measures Report Table (Objectives)  </h3>

    <table>
    <thead>
        <tr>
            <th>User </th>
            <th>Total  of Task Completed</th>
            <th>Total  of Objectives Completed</th>
            <th>Total  of Errors (Both assisted and Unassisted)</th>
            <th>Total  of Objectives with Errors</th>
            <th>Task Error Intensity</th>
        </tr>
    </thead>
    <tbody>
         @foreach ($projectUsers as $projectUser)
        <tr>
            <td>{{ $projectUser->name }}</td>
            <td>{{ $usersTasks->where('status','complated')->whereNull('task_id')->where('user_id',$projectUser->id)->count() }}</td>
            <td>{{ $usersTasks->where('status','complated')->whereNotNull('task_id')->where('user_id',$projectUser->id)->count() }}</td>
            <td>{{ $usersTasks->where('user_id',$projectUser->id)->sum('assisted_count') +  $usersTasks->where('user_id',$projectUser->id)->sum('errors_count') }}</td>
            <td>{{ $usersTasks->whereNotNull('task_id')->where('user_id',$projectUser->id)->sum('errors_count') }}</td>
            <td>{{ $usersTasks->whereNotNull('errors_count')->count() / $projectUser->count() }}</td>
        </tr>
        @endforeach
        <tr>
            <td>Total Users</td>
            <td colspan="5">{{$projectUser->count()}}</td>
        </tr>
    </tbody>
</table>

    <hr>
    <h3>Summary Efficiency Results by User  </h3>

    <table border="1">
        <thead>
            <tr>
                <th>User #</th>
                <th>Assisted Task Completion Rate</th>
                <th>Unassisted Task Completion Rate</th>
                <th>Total Task Time (h:m:s)</th>
                <th>Errors</th>
                <th>Assists</th>
                <th>Help Access Time</th>
                <th>Efficiency</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($efficiencyData as $data)
                <tr>
                <td>{{ $data['user'] }}</td>
                    <td>{{ number_format($data['assisted_rate'] * 100, 2) }}%</td>
                    <td>{{ number_format($data['unassisted_rate'] * 100, 2) }}%</td>
                    <td>{{ $data['total_task_time'] }}</td>
                    <td>{{ $data['errors'] }}</td>
                    <td>{{ $data['assists'] }}</td>
                    <td>{{ $data['help_access_time'] }}</td>
                    <td>{{ number_format($data['efficiency'] * 100, 2) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <hr>
    <h3>Satisfaction Report per User   </h3>


    <table style="border: 1px solid black; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="border: 1px solid black;">USER #</th>
            <th style="border: 1px solid black;">Ease of Use</th>
            <th style="border: 1px solid black;">Usefulness</th>
            <th style="border: 1px solid black;">Appearance</th>
            <th style="border: 1px solid black;">Clarity and Understandability</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ProjectRates as $ratings)
            <tr>
                <td style="border: 1px solid black;">{{ $ratings->user->name }}</td>
                <td style="border: 1px solid black;">{{ $ratings->ease_of_use }}</td>
                <td style="border: 1px solid black;">{{ $ratings->usefulness }}</td>
                <td style="border: 1px solid black;">{{ $ratings->appearance }}</td>
                <td style="border: 1px solid black;">{{ $ratings->clarity_and_understandability }}</td>
            </tr>
        @endforeach
        <tr>
            <td style="border: 1px solid black;">Mean</td>
            <td style="border: 1px solid black;">x</td>
            <td style="border: 1px solid black;">x</td>
            <td style="border: 1px solid black;">x</td>
            <td style="border: 1px solid black;">x</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Standard Dev</td>
            <td style="border: 1px solid black;">x</td>
            <td style="border: 1px solid black;">x</td>
            <td style="border: 1px solid black;">x</td>
            <td style="border: 1px solid black;">x</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Standard Error</td>
            <td style="border: 1px solid black;">x</td>
            <td style="border: 1px solid black;">x</td>
            <td style="border: 1px solid black;">x</td>
            <td style="border: 1px solid black;">x</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Min</td>
            <td style="border: 1px solid black;">X</td>
            <td style="border: 1px solid black;">X</td>
            <td style="border: 1px solid black;">X</td>
            <td style="border: 1px solid black;">X</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Max</td>
            <td style="border: 1px solid black;">x</td>
            <td style="border: 1px solid black;">x</td>
            <td style="border: 1px solid black;">x</td>
            <td style="border: 1px solid black;">x</td>
        </tr>
    </tbody>
</table>


    
</body>
</html>
