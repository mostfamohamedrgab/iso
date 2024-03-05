<!-- resources/views/emails/project_selected_notification.blade.php -->

@component('mail::message')


<h3>Dear Participant,</h3>
You are invited to complete this [{{ $project->title }}] qualityin-use assessment for [{{ $project->title }}]. The objective of this 
assessment is [{{ $project->title }}]. </br>
This assessment is expected to take on average [{{$project->average_time_to_complete}}]  
minutes. <br> 
The information in this study will be used 
only for research purposes and in ways that will not 
reveal who you are.  <br>
To complete the assessment, please 

@component('mail::button', ['url' => $projectUrl])
click here.
@endcomponent

You sign-in information is listed below: <br>
User name: {{$user->email}} <br>
If you have any question, please contact your  <br>
assessment administrator,  <br>



Thanks,<br>
{{ config('app.name') }}
@endcomponent
