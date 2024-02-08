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
                <form id="kt_ecommerce_add_product_form" 
                    data-kt-redirect="{{  isset($project) ? route('projects.edit',$project->id) : route('projects.create') }}" 
                action="{{ isset($project) ? route('projects.update', $project->id) : route('projects.store') }}"
                          method="post" enctype="multipart/form-data" 
                        class="form d-flex flex-column flex-lg-row store" >
                    <!--begin::Card body-->
                    @csrf 
                    @if(isset($project)) @method('PUT') @endif

                    <div class="card-body border-top p-9">
                        <!--begin::Input group-->

                         <!--begin::Input group-->
                         <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-6">Image</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Image input-->
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image:  asset('assets/media/svg/avatars/blank.svg')">
                                    <!--begin::Preview existing avatar-->
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ isset($project) ? asset($project->image) : asset('assets/media/avatars/300-1.jpg') }})"></div>
                                    <!--end::Preview existing avatar-->
                                    <!--begin::Label-->
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change avatar">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <!--begin::Inputs-->
                                        <input type="file" name="image" accept=".png, .jpg, .jpeg">
                                        <input type="hidden" name="avatar_remove">
                                        <!--end::Inputs-->
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Cancel-->
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <!--end::Cancel-->
                                    <!--begin::Remove-->
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <!--end::Remove-->
                                </div>
                                <!--end::Image input-->
                                <!--begin::Hint-->
                                <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                <!--end::Hint-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <div class="row ">
                                 <div class="col-md-6">
                                    <label class="col-form-label required fw-bold fs-6">Title</label>
                                    <div class="">
                                        <input type="text" name="title" required class="form-control form-control-lg form-control-solid" placeholder="Title" value="{{ isset($project) ? $project->title : old('title') }}">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                                    <!-- Customer (Assuming customer is a related model) -->
                                <div class="col-md-6">
                                    <label class=" col-form-label  fw-bold fs-6">Customer</label>
                                    <div class="">
                                        <!--begin::Col-->
                                            <select name="customer_id" id="customer_id"   class="form-select form-select-solid ">
                                                <option value="" data-select2-id="select2-data-12-wa4o">select customer</option>
                                                @foreach ($customers as $customer)
                                                <option value="{{$customer->id}}" 
                                                    @if(isset($project)) 
                                                        {{$project->customer_id  == $customer->id ? "selected" : ""}}
                                                    @else 
                                                        {{old('customer_id')  == $customer->id ? "selected" : ""}}
                                                    @endif 
                                                    >{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <!--end::Input group-->
                                    </div>
                                </div>
                        </div>

                        <!-- Description -->
                        <div class="row ">
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">Description</label>
                            <div class="col-lg-12">
                                <textarea name="description" required style="height:80px;"  placeholder="Description"class="form-control form-control-lg form-control-solid">{{ isset($project) ? $project->description : old('description') }}</textarea>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>

                        <!-- Objective -->
                        <div class="row mb-2">
                            <label class="col-lg-12 col-form-label  fw-bold fs-6">Objective</label>
                            <div class="col-lg-12">
                                <textarea name="objective" style="height:80px;"  placeholder="objective"class="form-control form-control-lg form-control-solid">{{ isset($project) ? $project->objective : old('objective') }}</textarea>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>

                        <!-- Start Date and End Date (Col-6) -->
                        <div class="row mb-6">
                            <div class="col-lg-6">
                                <label class="col-form-label  fw-bold fs-6">Start Date</label>
                                <input type="date" name="start_date"  class="form-control form-control-lg form-control-solid" value="{{ isset($project) ? $project->start_date : old('start_date') }}">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label  fw-bold fs-6">End Date</label>
                                <input type="date" name="end_date"  class="form-control form-control-lg form-control-solid" value="{{ isset($project) ? $project->end_date : old('end_date') }}">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>


                        <div class="row mb-6">
                        <label class="col-lg-12 col-form-label required fw-bold fs-6">Users</label>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <button type="button" class="btn btn-primary" id="addUserBtn">Add User</button>
                            </div>
                            <div id="userFieldsContainer">
                                <!-- User fields will be added here -->
                                @if(isset($project))
                                    @foreach($projectUsers as $user)
                                        <div class="row mb-3 user-hourly-rate" id="user-hourly-rate-{{$user->id}}">
                                            <div class="col-lg-6">
                                                <select name="users[]" required data-placeholder="Select user" class=" form-select form-select-lg form-select-solid">
                                                    @foreach ($users as $projectUser)
                                                    <option {{$user->id == $projectUser->id ? 'selected' : ''}} value="{{ $projectUser->id }}">{{ $projectUser->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                            <div class="col-lg-4">
                                                <input type="number" name="hourly_rate[]" required  value="{{ $user->pivot->hourly_rate }}" class="form-control form-control-lg form-control-solid" placeholder="Hourly rate">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                            <div class="col-lg-2">
                                                <button type="button" class="btn btn-danger delete-user" data-user-id="{{$user->id}}"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>

                                    @endforeach
                                @endif 
                            </div>
                        </div>
                    </div>



                        <div class="row mb-6">
                            <label class="col-lg-12 col-form-label fw-bold fs-6">Upload Files

                                    @if(isset($project))
                                            @foreach($project->files as $i => $file)
                                            <div class="d-flex" id="file-{{$file->id}}">
                                                <a href="{{asset($file->file)}}">{{$file->name}}</a>
                                                <a href="#" class="delete-file" 
                                                data-url="{{ route('projectsfile.destroy',$file->id) }}" data-id="{{ $file->id }}">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                            </div>   
                                            @endforeach
                                    @endif
                            </label>
                            <div class="col-lg-12">
                                <input type="file" name="files[]" class="form-control form-control-lg form-control-solid" multiple>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>

                         <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
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
@section('scripts')
<script>
       $(document).ready(function() {
        $('#addUserBtn').click(function(e) {
            e.preventDefault();
            addUserField();
        });

        // Function to add user field dynamically
        function addUserField() {
            var userId = Date.now(); // Generate unique user ID
            var userField = `
                <div class="row mb-3 user-hourly-rate" id="user-hourly-rate-${userId}">
                    <div class="col-lg-6">
                        <select name="users[]" required data-placeholder="Select user" class=" form-select form-select-lg form-select-solid">
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>
                    <div class="col-lg-4">
                        <input type="number" name="hourly_rate[]" required class="form-control form-control-lg form-control-solid" placeholder="Hourly rate">
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>
                    <div class="col-lg-2">
                        <button type="button" class="btn btn-danger delete-user" data-user-id="${userId}"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;
            $('#userFieldsContainer').append(userField);

            // Reinitialize Select2 for the new dropdown
            $('#user-hourly-rate-' + userId + ' select').select2();

            // Delete user event
           
        }
    });


    $(document).on('click', '.delete-user', function() {
        var userId = $(this).data('user-id');
        $('#user-hourly-rate-' + userId).remove();
    });

    $("#customer_id").select2();

    $(".delete-file").click(function(e){
        e.preventDefault();

    if(confirm('تأكيد الحذف ?')){

        var url = $(this).data("url");
        var id = $(this).data('id');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        console.log(url);

        $.ajax(
        {
            url: url,
            type: 'DELETE',
            data:{id:id},
            dataType: "JSON",
            success: function (res)
            {
                if(res.status == 200) // removed
                {
                    $('div#file-'+id).remove();
                }else if(res.status == 404) // not found
                {
                    $.toast({
                        text : res.msg,
                        allowToastClose:true,
                        hideAfter: 10000,
                        position:'top-right',
                        bgColor:'#ff0000'
                    })
                }
                else if(res.status == 400) // not found
                {
                    $.toast({
                        text : res.msg,
                        allowToastClose:true,
                        hideAfter: 10000,
                        position:'top-right',
                        bgColor:'#ff0000'
                    })
                }
                else {
                    $.toast({
                        text : "Errors !",
                        allowToastClose:true,
                        hideAfter: 10000,
                        position:'top-right',
                        bgColor:'#ff0000'
                    })
                }
            } // end success
        });
        }else {
        return false;
        }; // end confirm delete
    });

    </script>
@endsection
