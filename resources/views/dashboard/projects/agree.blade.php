@extends('dashboard.layouts.app')
@section('pageTitle' , isset($project) ? $project->title : 'Create Project')
@section('content')
<!-------------->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Basic info-->
        <div class="card mb-5 mb-xl-10 p-20">
            
            <div id="no-message" class="alert alert-success d-none">
                        Dear Participant, <br>
                        Thank you for taking the time to  participate in the assessment. <br>
                        will redirect you after 5 seconds <br>
                        Thank you 
                    </div>
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">

                
                <p class="agree-text">
                    Dear Participant, 
                    You are invited to complete this <strong>[{{$project->title}}]</strong> quality in-use assessment for <strong>[{{$project->title}}]</strong>. The objective of this 
                    assessment is [{{$project->goal}}].
                    This assessment is expected to take on average [{{$project->average_time_to_complete}}] 
                    minutes. The information in this study will be used 
                    only for research purposes and in ways that will not 
                    reveal who you are. 
                    To participate, please accept the assessment
                    <div class="text-center">
                        <a href="{{ route('projects.accepshow', $project->id) }}" class="btn btn-success">Accept</a>
                        <button id="choose-no" class="btn btn-danger">No</button>
                    </div>
                </p>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Basic info-->
      
      
    </div>
    <!--end::Container-->
</div>
@endsection
@push('css')
    <style>
        .agree-text
        {
            padding:10px;
            font-size:14px;
            color:#000;
            line-height:2
        }
    </style>
    
@endpush
@push('js')
<script>
    document.getElementById('choose-no').addEventListener('click', function() {
        // Remove the "d-none" class from the alert message
        document.getElementById('no-message').classList.remove('d-none');

        // Wait for 5 seconds before redirecting
        setTimeout(function() {
            // Redirect to the logout route
            window.location.href = '<?php echo route("logout"); ?>';
        }, 5000); // 5000 milliseconds = 5 seconds
    });
</script>
@endpush