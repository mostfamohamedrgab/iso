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
                            <div class="col-md-12">
                                <label class="col-form-label required fw-bold fs-6">Title</label>
                                <div class="">
                                    <input type="text" name="title" required class="form-control form-control-lg form-control-solid" placeholder="Title" value="{{ isset($task) ? $task->title : old('title') }}">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="col-form-label required fw-bold fs-6">Order</label>
                                <div class="">
                                    <input type="number" name="order" required class="form-control form-control-lg form-control-solid" placeholder="order" value="{{ isset($task) ? $task->order : old('order') }}">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                            </div>
    
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

                            <div class="com-md-12 mt-5">
                                <label class="required">Description</label>
                                <div class="">
                                    <textarea required name="description" required style="height:80px;"  placeholder="Description"class="form-control form-control-lg form-control-solid">{{ isset($task) ? $task->description : old('description') }}</textarea>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                            </div>
                        </div>


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
        $('#customer_id').select2();
        $('.select2').select2();

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
