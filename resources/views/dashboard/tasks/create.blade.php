@extends('dashboard.layouts.app')
@section('pageTitle' , isset($task) ? $task->title : 'Create Task')
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
                    <h3 class="fw-bolder m-0">{{ isset($task) ? $task->title : 'Create Task' }}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <!--begin::Form-->
                <form id="kt_ecommerce_add_product_form" 
                    data-kt-redirect="{{  isset($task) ? route('tasks.edit',$task->id) : route('tasks.create') }}" 
                action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}"
                          method="post" enctype="multipart/form-data" 
                        class="form d-flex flex-column flex-lg-row store" >
                    <!--begin::Card body-->
                    @csrf 
                    @if(isset($task)) @method('PUT') @endif

                    <div class="card-body border-top p-9">
                        <!--begin::Input group-->
                        <div class="row ">


                        @if(!request()->project_id)
                            <div class="com-md-12 mt-5">
                                     <label class="required">Project</label>
                                    <select required  name="project_id" required aria-label="Select a Country" data-control="select2" data-placeholder="select project" 
                                    class="form-select form-select-solid form-select-lg fw-bold select2-hidden-accessible" data-select2-id="select2-data-10-05ls" tabindex="-1" aria-hidden="true">
                                        <option value="" data-select2-id="select2-data-12-wa4o">select project</option>
                                        @foreach ($projects as $project)
                                        <option value="{{$project->id}}" 
                                            @if(isset($task)) 
                                                {{$task->project_id  == $project->id ? "selected" : ""}}
                                            @else 
                                                {{old('project_id')  == $project->id ? "selected" : ""}}
                                            @endif 
                                            >{{ $project->title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            @else 
                                <input type="hidden" value="{{ request()->project_id }}" name="project_id">
                            @endif 


                            <div class="col-md-12">
                                <label class="col-form-label required fw-bold fs-6">Order</label>
                                <div class="">
                                    <input type="number" name="order" required class="form-control form-control-lg form-control-solid" placeholder="order" value="{{ isset($task) ? $task->order : old('order') }}">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="col-form-label required fw-bold fs-6">Title</label>
                                <div class="">
                                    <input type="text" name="title" required class="form-control form-control-lg form-control-solid" placeholder="Title" value="{{ isset($task) ? $task->title : old('title') }}">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="col-form-label required fw-bold fs-6">Weight (%)</label>
                                <div class="">
                                    <input type="number" name="weight" required class="form-control form-control-lg form-control-solid" placeholder="weight" value="{{ isset($task) ? $task->weight : old('weight') }}">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                            </div>
    
                            <div class="com-md-12 mt-5">
                                <label class="required">Description</label>
                                <div class="">
                                    <textarea required name="description" required style="height:80px;"  placeholder="Description"class="form-control form-control-lg form-control-solid">{{ isset($task) ? $task->description : old('description') }}</textarea>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="form-group mt-10">
                                <p for="" class="required"><strong> can task be partially completed</strong></p>
                               
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"  {{isset($task) && $task->can_task_be_partially_completed  ? 'checked' : ''}}    type="radio" id="yes" name="can_task_be_partially_completed" value="1">
                                    <label class="form-check-label" for="yes">yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" {{isset($task) && !$task->can_task_be_partially_completed ? 'checked' : ''}} id="no" name="can_task_be_partially_completed" value="0">
                                    <label class="form-check-label" for="no">no</label>
                                </div>
                            </div>

                            <div class="com-md-12 mt-5 {{isset($task) && $task->can_task_be_partially_completed ? '' : 'd-none'}} " id="tasks-count-parent">
                                <label class="required">objective count</label>
                                <div class="">
                                    <input type="number" class="form-control" id="tasks-count">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                                <p id="messgeDiv" class=" alert alert-danger">Please correct your task inputs. You need to have at least 2 sub-tasks.</p>
                            </div>
                        </div>

                        <hr>
                        <div id="sub-tasks">
                            @if(isset($task))
                                 @foreach ($task->tasks as $index => $subtask)
                                    <div class="sub-task row">
                                        <div class="col-md-11 border-right">
                                            <div class="col-md-12">
                                                <label class="col-form-label required fw-bold fs-6">Order</label>
                                                <div class="">
                                                    <input type="number" name="subtask_order[]" required class="form-control form-control-lg form-control-solid" placeholder="Order" value="{{ $subtask->order }}">
                                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="col-form-label required fw-bold fs-6">Title</label>
                                                <div class="">
                                                    <input type="text" name="subtask_title[]" required class="form-control form-control-lg form-control-solid" placeholder="Title" value="{{ $subtask->title }}">
                                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="col-form-label required fw-bold fs-6">Weight (%)</label>
                                                <div class="">
                                                    <input type="number" name="subtask_weight[]" required class="form-control form-control-lg form-control-solid" placeholder="Weight" value="{{ $subtask->weight }}">
                                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-5">
                                                <label class="required">Description</label>
                                                <div class="">
                                                    <textarea required name="subtask_description[]" style="height:80px;" placeholder="Description" class="form-control form-control-lg form-control-solid">{{ $subtask->description }}</textarea>
                                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-5">
                                                <lable>Files</lable>

                                                @foreach($subtask->files as $i => $file)
                                                <div class="d-flex" id="file-{{$file->id}}">
                                                    <a href="{{asset($file->file)}}">{{$file->name}}</a>
                                                    <a href="#" class="delete-file" 
                                                    data-url="{{ route('tasksfile.destroy',$file->id) }}" data-id="{{ $file->id }}">
                                                        <i class="fa fa-trash text-danger"></i>
                                                    </a>
                                                </div>   
                                                @endforeach

                                                <input type="file" name="subfiles[{{$index}}][]" class="form-control form-control-lg form-control-solid" multiple>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="#" data-route="{{ route('tasks.destroy', $subtask->id) }}" class="btn btn-danger btn-sm delete-task"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                    @endforeach
                            @endif 
                        </div>
                            
                        <button type="button" id="add-subtask-btn" class="mt-5 btn btn-sm btn-success">Add Objective</button>
                        <hr>



                        <div class="row mb-6">
                            <label class="col-lg-12 col-form-label fw-bold fs-6">Upload Files
                                    @if(isset($task))
                                            @foreach($task->files as $i => $file)
                                            <div class="d-flex" id="file-{{$file->id}}">
                                                <a href="{{asset($file->file)}}">{{$file->name}}</a>
                                                <a href="#" class="delete-file" 
                                                data-url="{{ route('tasksfile.destroy',$file->id) }}" data-id="{{ $file->id }}">
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

        let index = `{{ isset($index) ? $index : 0 }}`;
        // Handle click event of the "Add Subtask" button
        $('#add-subtask-btn').click(function() {
            // Construct the dynamic content for subtask details
           addSubTask();
        });

        $(document).on('click', '.delete-parent-sub-task', function(e) {
            e.preventDefault();
            // Remove the parent div when the delete button is clicked
            $(this).closest('.sub-task').remove();

            let length = $('.sub-task').length;

           if(length > 1)
           {
                console.log('tes');
                $("#messgeDiv").hide();
            }else{
                $("#messgeDiv").show();
            }
            
        });

        $("#tasks-count").on("keyup", function (){
            let subTasks = $(this).val();

            for(let i =0; i < subTasks; i++)
            {
                addSubTask();
            }
        });


        function addSubTask()
        {
            var subtaskContent = `
                <div class="sub-task row">
                    <div class="col-md-11 border-right">
                        <div class="col-md-12">
                            <label class="col-form-label required fw-bold fs-6">Order</label>
                            <div class="">
                                <input type="number" name="subtask_order[]" required class="form-control form-control-lg form-control-solid" placeholder="Order">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="col-form-label required fw-bold fs-6">Title</label>
                            <div class="">
                                <input type="text" name="subtask_title[]" required class="form-control form-control-lg form-control-solid" placeholder="Title">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="col-form-label required fw-bold fs-6">Weight (%)</label>
                            <div class="">
                                <input type="number" name="subtask_weight[]" required class="form-control form-control-lg form-control-solid" placeholder="Weight">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-5">
                            <label class="required">Description</label>
                            <div class="">
                                <textarea required name="subtask_description[]" style="height:80px;" placeholder="Description" class="form-control form-control-lg form-control-solid"></textarea>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-5">
                            <lable>Files</lable>
                            <input type="file" name="subfiles[${index}][]" class="form-control form-control-lg form-control-solid" multiple>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <a href="#" class="btn btn-danger btn-sm delete-parent-sub-task"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
            `;

            index++;

            
            // Append the dynamic content to the sub-tasks div
            $('#sub-tasks').append(subtaskContent);
            let length = $('.sub-task').length;

           if(length > 1)
           {
                console.log('tes');
                $("#messgeDiv").hide();
            }else{
                $("#messgeDiv").show();
            }
        }


    });

    $(document).ready(function() {
    // Listen for click events on the delete task buttons
    $('.delete-task').click(function(e) {
        e.preventDefault(); // Prevent the default link behavior
        let el = $(this);
        var route = $(this).data('route'); // Get the route from data attribute

        // Show a confirmation dialog
        if (confirm('Are you sure you want to delete this task?')) {
            // If user confirms, send an AJAX request to delete the task
            $.ajax({
                url: route, // Route for deleting the task
                type: 'DELETE', // HTTP method for deletion
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                },
                success: function(response) {
                    // Handle success response
                    el.closest('.sub-task').remove();
                   
                    
                    let length = $('.sub-task').length;

                if(length > 1)
                {
                        console.log('tes');
                        $("#messgeDiv").hide();
                    }else{
                        $("#messgeDiv").show();
                    }

                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error('Error deleting task:', error);
                    // Optionally, you can display an error message to the user
                }
            });
        }
    });
});
</script>


    <script>
        $('#customer_id').select2();
        $('.select2').select2();

        $('input[name="can_task_be_partially_completed"]').change(function() {
            // Check if the user selected "yes"
            if ($(this).val() === "1") {
                // Show the objective count input field
                $('#tasks-count-parent').removeClass('d-none');
            } else {
                // Hide the objective count input field
                $('#tasks-count-parent').addClass('d-none');
            }
        });

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
@push('css')
    <style>
        .delete-parent-sub-task , .delete-task {
            margin-top: 20px; /* Adjust as needed */
            position: absolute;
            top: 50%;
        }
        .sub-task {
            position:relative;
            background: #fdfbfb;
            padding: 20px;
            margin: 5px;
            border-radius: 10px;
        }
    </style>
@endpush 