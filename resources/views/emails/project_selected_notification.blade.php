<!-- resources/views/emails/project_selected_notification.blade.php -->

@component('mail::message')
# New Project Selected

You have been added to a new project. Here are the details:

**Project Title:** {{ $project->title }}

**Project Description:** {{ $project->description }}

@component('mail::button', ['url' => $projectUrl])
View Project
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
