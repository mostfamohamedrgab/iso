@extends('dashboard.layouts.app')
@section('pageTitle' , isset($project) ? $project->title : 'Create Project')
@section('content')
<!-------------->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Basic info-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">{{ isset($project) ? $project->title : 'Create Project' }}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <!--begin::Form-->
                <form id="" 
                    data-kt-redirect="{{    route('projects.edit',$project->id)  }}" 
                action="{{   route('projects.accept', $project->id)   }}"
                          method="post" enctype="multipart/form-data" 
                        class=" d-flex flex-column flex-lg-row " >
                    <!--begin::Card body-->
                    @csrf 

                    <div class="card-body border-top p-9">
                        <!--begin::Input group-->

                        <!-- Start Date and End Date (Col-6) -->
                        <div class="row mb-6">
                            <div class="col-lg-6">
                                <label class="col-form-label  fw-bold fs-6 required">Estimate hourly rate:</label>
                                <input type="number" name="hourly_rate" required  class="form-control form-control-lg form-control-solid" value="{{ isset($projectUser) ? $projectUser->hourly_rate : old('hourly_rate') }}">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        
                            <div class="col-lg-6">
                                <label class="col-form-label  fw-bold fs-6 required">years of experience:</label>
                                <input type="number" name="year_of_experience" required  class="form-control form-control-lg form-control-solid" value="{{ isset($projectUser) ? $projectUser->year_of_experience : old('year_of_experience') }}">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                        <hr>

                        <div class="form-check">
                            <label class="form-check-label {{$project->used_software_before ? 'required' : ''}}" for="used_software_before">
                                Have You Used the Software Before
                            </label>
                            <input class="form-check-input" {{$project->used_software_before ? 'required' : ''}} type="checkbox" value="1" {{isset($projectUser) && $projectUser->used_software_before ? 'checked' : ''}} name="used_software_before" id="used_software_before">
                        </div>
                        <hr>
                        
                        <div class="form-group mt-10">
                            <label for="" class="required"><strong> Gender identity</strong></label>
                             <div class="form-check form-check-inline">
                                <input class="form-check-input"  {{isset($projectUser) && strpos($projectUser->gender, 'male') !== false ? "checked" : ''}}  type="radio" id="male" name="gender" value="male">
                                <label class="form-check-label" for="male">male</label>
                            </div>
                             <div class="form-check form-check-inline">
                                <input class="form-check-input" {{isset($projectUser) && strpos($projectUser->gender, 'female') !== false ? "checked" : ''}} type="radio" id="female" name="gender" value="female">
                                <label class="form-check-label" for="female">female</label>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group mt-10">
                            <label for="" class="{{$project->user_computer_skills ? 'required' : ''}}"><strong> Computer Skills</strong></label>
                             <div class="form-check form-check-inline">
                                <input class="form-check-input"  {{isset($projectUser) && strpos($projectUser->user_computer_skills, 'beginner') !== false ? "checked" : ''}}  type="radio" id="beginner" name="user_computer_skills" value="beginner">
                                <label class="form-check-label" for="beginner">beginner</label>
                            </div>
                             <div class="form-check form-check-inline">
                                <input class="form-check-input" {{isset($projectUser) && strpos($projectUser->user_computer_skills, 'intermediate') !== false ? "checked" : ''}} type="radio" id="intermediate" name="user_computer_skills" value="intermediate">
                                <label class="form-check-label" for="intermediate">intermediate</label>
                            </div>
                             <div class="form-check form-check-inline">
                                <input class="form-check-input" {{isset($projectUser) && strpos($projectUser->user_computer_skills, 'advanced') !== false ? "checked" : ''}}  type="radio" id="advanced" name="user_computer_skills" value="advanced">
                                <label class="form-check-label" for="advanced">advanced</label>
                            </div>
                        </div>
    

                         <!--begin::Actions-->
                    <div class="d-flex justify-content-end mt-10">
                           
                            <!--begin::Button-->
                            <button type="submit" id="" class="btn btn-success">
                                <span class="indicator-label">@lang('dashboard.save_changes')</span>
                                <span class="indicator-progress">@lang('dashboard.please_wait')
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <!--end::Button-->
                        </div>
                    <!--end::Actions-->
                    </div>
                    <!--end::Card body-->
                   
            
            </form>
                <!--end::Form-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Basic info-->
      
      
    </div>
    <!--end::Container-->
</div>
@endsection
