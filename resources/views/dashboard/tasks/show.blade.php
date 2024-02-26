@section('pageTitle' , "tasks")

@extends('dashboard.layouts.app')
@section('content')


<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">

    <h2>{{$project->title}} - {{$task->title}} 

        
    </h2>
    <p>{{$project->goal}}</p>


    

    @if($report)
            <div class="row">
                @foreach(taskStatus() as $status)
                <div class="col-md-4 mt-5">
                    <div class="card" style="width: 90%;margin:auto">
                        <div class="card-body">
                            <a href="#" class="btn btn-primary" style="display:block;margin:10px 0">{{ $taskAnalytics->where('status',$status)->count() }}</a>
                            <h5 class="card-title">{{ $status }}</h5>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="col-md-4 mt-5">
                    <div class="card" style="width: 100%">
                        <div class="card-body">
                            <a href="#" class="btn btn-primary" style="display:block;margin:10px 0">{{ $taskAnalytics->sum('errors_count') }}</a>
                            <h5 class="card-title">Errors</h5>
                        </div>
                    </div>
                </div>


                <div class="col-12 mt-20">
                    <div class="card card-flush">
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <h1>Task Analytics</h1>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Status</th>
                                        <th>start time</th>
                                        <th>end time</th>
                                        <th>errors count</th>
                                        <th>Assisted</th>
                                        <th>notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($taskAnalytics as $analytics)
                                        <tr>
                                            <td>{{ $analytics->user->name }}</td>
                                            <td>{{ $analytics->status }}</td>
                                            <td>{{ $analytics->start_time }}</td>
                                            <td>{{ $analytics->end_time }}</td>
                                            <td>{{ $analytics->errors_count }}</td>
                                            <td>{{ $task->assisted ? 'Yes' : 'No'  }}</td>
                                            <td>{{ $analytics->notes }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
    @else 

    <!--begin::Crd-->
    <div class="card card-custom gutter-b">


    @if($project->end_date AND $project->end_date < now())
    <div class="alert alert-success text-center">Project completed At {{$project->end_date}}</div>
    @endif 

        <div class="row">
            <div class="col-md-9">
        @foreach($tasks as $subTaskIndex => $subTask)
                @php 
                    $prevTask  = $subTask;
                    $workingTask = true; 
                    
                    if($subTaskIndex > 1):
                        $prevTaskIndex = $subTaskIndex - 1;
                        $prevTask = $tasks[$prevTaskIndex]; // Assuming $tasks is an array of tasks
                        $prevTaskStatus = $userTasks->where('task_id', $prevTask->id)->first()->status;
                        $workingTask = isset($prevTaskStatus) && isDoneTask($prevTaskStatus);
                    endif;  
                @endphp 
            
                <div class="card-body {{$subTask->task_id ? 'sub-task' : ''}} {{$workingTask ? 'working-task' : 'disabled-task'}} {{ isset($prevTaskStatus) ? $prevTaskStatus : ''}}">
                    <div class="d-flex">
                        <!--begin: Pic-->
                        <div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
                            @if($project->image)
                            <div class="symbol symbol-50 symbol-lg-120">
                                <img alt="Pic" src="{{ asset($project->image) }}" />
                            </div>
                            @endif 
                            
                        </div>
                        <!--end: Pic-->
                        <!--begin: Info-->
                        <div class="flex-grow-1">
                            <!--begin: Title-->
                            <div class="mr-3">
                                    <!--begin::Name-->
                                    <h3  class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">
                                    @if($subTask->task_id) <i class="fa fa-arrow-right " style="margin-right:10px"></i>  @endif 
                                        {{$subTaskIndex != 0 ? "[".$subTaskIndex ."]" : ''}}
                                    {{$subTask->title}}
                                    <i class="flaticon2-correct text-success icon-md ml-2"></i></h3>
                                    <!--end::Name-->
                                    
                                </div>


                            <div class="d-flex align-items-center justify-content-between flex-wrap" id="task-scroll{{$subTask->id}}">
                                <div class="my-lg-0 my-1">
                                    <a id="start-task{{$subTask->id}}" 
                                        data-end-task="end-task-{{$subTask->id}}"
                                     href="#" class="btn btn-sm btn-success start-task font-weight-bolder text-uppercase mr-3" 
                                        data-url="{{ route('tasks.time',$userTasks->where('task_id',$subTask->id)->first()->id) }}" 
                                        onclick="startTask(this); return false;">
                                        {{$userTasks->where('task_id',$subTask->id)->first()->start_time ? $userTasks->where('task_id',$subTask->id)->first()->start_time : 'Start Task'}}
                                    </a>
                                    <a  
                                        id="end-task-{{$subTask->id}}"
                                         href="#" class="btn btn-sm btn-danger end-task font-weight-bolder text-uppercase"
                                         data-url="{{ route('tasks.time',$userTasks->where('task_id',$subTask->id)->first()->id) }}" 
                                         data-started="{{ $userTasks->where('task_id',$subTask->id)->first()->start_time ? '1' : '0' }}"
                                         onclick="endTask(this); return false;">
                                        {{$userTasks->where('task_id',$subTask->id)->first()->end_time ? $userTasks->where('task_id',$subTask->id)->first()->end_time : 'End Task'}}
                                    </a>
                                </div>
                                @if($userTasks->where('task_id',$subTask->id)->first()->start_time AND $userTasks->where('task_id',$subTask->id)->first()->end_time)
                                    <span class="btn btn-sm btn-light-primary">
                                        Taken Time: {{$userTasks->where('task_id',$subTask->id)->first()->getTimeDiff()}}
                                    </span>
                                @endif 
                            </div>
                            <!--end: Title-->
                            <hr>
                            <!--begin: Content-->
                            <div class="d-flex align-items-center flex-wrap justify-content-between">
                                <div class="flex-grow-1 font-weight-bold text-dark-50 py-5 py-lg-2 mr-5">
                                    {{$subTask->description }}
                                </div>
                            </div>
                            <!--end: Content-->
                        </div>
                        <!--end: Info-->
                    </div>
                    
                    <!--begin: Items-->
                    <div class="d-flex align-items-center flex-wrap">
                        <!--begin: Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                            <div class="d-flex flex-column text-dark-75">
                                @foreach($subTask->files as $file)
                                    <a href="{{ asset($file->file) }}">
                                        <li class="font-weight-bolder font-size-sm">{{$file->name}}</li>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <!--end: Item-->
                    </div>
                    <!--begin: Items-->

                    <!--begin::Basic info-->
                    <!--begin::Form-->
                    <form id="kt_ecommerce_add_product_form{{$subTask->id}}" 
                        action="{{  route('tasks.rate',$userTasks->where('task_id',$subTask->id)->first()->id)  }}"
                        method="post" enctype="multipart/form-data" 
                        class=" d-flex flex-column flex-lg-row " >
                        <!--begin::Card body-->
                        @csrf 
                        @method('PUT')

                        <div class="card-body border-top p-0">
                            <!--begin::Input group-->
                            <div class="row ">

                            <div class="col-md-12 mt-5">
                                    <label class="">Status</label>
                                    <div class="">
                                        <select name="status" class="form-select form-select-lg form-select-solid">
                                            @foreach(taskStatus() as $status)
                                            <option value="{{ $status }}" {{  $userTasks->where('task_id',$subTask->id)->first()->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>

                                <!-- Add fields for notes, errors_count, assisted, and status -->
                                <div class="com-md-12 mt-5">
                                    <label class="">Notes</label>
                                    <div class="">
                                        <textarea name="notes" style="height:80px;" placeholder="Notes" class="form-control form-control-lg form-control-solid">{{ $userTasks->where('task_id',$subTask->id)->count() ? $userTasks->where('task_id',$subTask->id)->first()->notes : old('notes') }}</textarea>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-4 mt-5">
                                    <label class="">Errors Count</label>
                                    <div class="">
                                        <input type="number" name="errors_count" placeholder="Errors Count" class="form-control form-control-lg form-control-solid" value="{{ $userTasks->where('task_id',$subTask->id)->count() ? $userTasks->where('task_id',$subTask->id)->first()->errors_count : old('errors_count') }}">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-4 mt-5 ">
                                    <label class="">Assisted</label>
                                    <div class="">
                                        <select name="assisted" class="assisted form-select form-select-lg form-select-solid">
                                            <option value="1" {{  $userTasks->where('task_id',$subTask->id)->first()->assisted == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{  $userTasks->where('task_id',$subTask->id)->first()->assisted == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-md-4 mt-5 assisted-count">
                                    <label class="">Assisted Count</label>
                                    <div class="">
                                        <input type="number" name="assisted_count" placeholder="Assisted count" class="form-control form-control-lg form-control-solid" value="{{ $userTasks->where('task_id',$subTask->id)->count() ? $userTasks->where('task_id',$subTask->id)->first()->assisted_count : old('assisted_count') }}">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mt-5 time_spent_searching">
                                        <label class="">time spent searching</label>
                                        <div class="">
                                            <input type="number" name="time_spent_searching" placeholder="time spent searching" class="form-control form-control-lg form-control-solid" value="{{ $userTasks->where('task_id',$subTask->id)->count() ? $userTasks->where('task_id',$subTask->id)->first()->time_spent_searching : old('time_spent_searching') }}">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-5">
                                        <label class="">time spent getting help</label>
                                        <div class="">
                                            <input type="number" name="time_spent_getting_help" placeholder="time spent getting help" class="form-control form-control-lg form-control-solid" value="{{ $userTasks->where('task_id',$subTask->id)->count() ? $userTasks->where('task_id',$subTask->id)->first()->time_spent_getting_help : old('time_spent_getting_help') }}">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <!--begin::Actions-->
                            <div class="d-flex justify-content-end mt-5">
                                <!--begin::Button-->
                                <button type="submit"  form="kt_ecommerce_add_product_form{{$task->id}}" class="btn submit-button btn-primary">
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
            @endforeach
        </div>
        <!--end::Card-->
 
    <div class="col-md-3">
            <ul class="list-group mt-20" style="padding:20px">
                @foreach($projectTasks as $taskIndex => $loopTask)

                    @php 
                        $prevTask = null;
                        $workingTask = true;

                        if ($taskIndex > 0) {
                            $prevTaskIndex = $taskIndex - 1;
                            $prevTask = $projectTasks[$prevTaskIndex];
                            $prevTaskStatusObj = $userTasks->where('task_id', $prevTask->id)->first();
                            $prevTaskStatus = $prevTaskStatusObj ? $prevTaskStatusObj->status : null;
                            $workingTask = isset($prevTaskStatus) && isDoneTask($prevTaskStatus) && $loopTask->order > $task->order;
                        }
                    @endphp

                    <li class="list-group-item {{$task->id == $loopTask->id ? 'active text-light' : ''}} {{$workingTask ? 'working-sub-task ' : 'disabled-sub-task'}} {{ isset($prevTaskStatus) ? $prevTaskStatus : ''}}">
                        <a class="nav-link {{$task->id == $loopTask->id ? 'text-light' : ''}}" href="{{ route('tasks.show',$loopTask->id) }}">{{ $loopTask->title }}</a>
                    </li>
                @endforeach

                 <!--- check if user finsih form the last task he take an action !--->

                 

                 @if($userTasks->where('task_id', $project->tasks->whereNull('task_id')->last()->id)->first()->status != 'not started yet')
                    @if(!$report)
                        @can('projects.rate')
                        <button type="button" class="mt-2 btn btn-sm btn-primary" data-toggle="modal" data-target="#RateProject">
                            Rate Project <i class="fa fa-star" style="color:yellow"></i>
                        </button>
                        @endcan
                    @endif  
                 @endif 


                             <!-- Modal -->
<div class="modal fade" id="RateProject" tabindex="-1" role="dialog" aria-labelledby="RateProjectLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="RateProjectLabel">Rate the Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{ route('projects.rate',$project->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12 mt-5">
                    <div class="form-group">
                        <label for="ease_of_use">How likely is that your would recommend this software to a friend or colleague?:</label>
                        <div class="form-group mt-2">
                            @for($i =1;$i <= 10;$i++)
                             <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" {{ $ProjectRate?->rate == $i ? 'checked' : ''}}  type="radio" id="value-{{$i}}" name="rate" value="{{$i}}">
                                <label class="form-check-label" for="value-{{$i}}">{{$i}}</label>
                            </div>
                             @endfor
                        </div>

                        <div class="col-md-12 mt-5  {{ $ProjectRate?->rate < 5 ? '' : 'd-none'}} " id="reason">
                            <div class="form-group">
                                <label for="appearance">what changes must be made for your to give a higher rating?:</label>
                                <textarea name="reason" class="form-control" cols="30" rows="10">{{ $ProjectRate?->reason}}</textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <!----- 
                <div class="col-md-6 mt-5">
                    <div class="form-group">
                        <label for="usefulness">Usefulness:</label>
                        <input type="number" class="form-control" id="usefulness" value="{{$ProjectRate?->usefulness}}" name="usefulness" min="0" max="7" step="1">
                    </div>
                </div>
                <div class="col-md-6 mt-5">
                    <div class="form-group">
                        <label for="appearance">Appearance:</label>
                        <input type="number" class="form-control" id="appearance" value="{{$ProjectRate?->appearance}}" name="appearance" min="0" max="7" step="1">
                    </div>
                </div>
                <div class="col-md-6 mt-5">
                    <div class="form-group">
                        <label for="clarity_and_understandability">Clarity and Understandability:</label>
                        <input type="number" class="form-control" id="clarity_and_understandability" value="{{$ProjectRate?->clarity_and_understandability}}" name="clarity_and_understandability" min="0" max="7" step="1">
                    </div>
                </div> --->
            </div>
         
            <button type="submit" class="btn btn-primary mt-10">Save</button>
            
        </form>
      </div>
     
    </div>
  </div>
</div>

      

                 
              
            </ul>
        </div>
    </div>
        <!--end::Container-->
    @endif <!--- end -->
</div>
<!--end::Post-->
					
@endsection

@push('css')
    @if($project->end_date AND $project->end_date < now())
        <style>
            .start-task , .end-task {
                display:none
            }
        </style>
    @endif 
@endpush

@push('js')
@if(!$report)

            

    @if($project->end_date AND $project->end_date < now())
            <script>
                $(".submit-button").hide();
            </script>
    @endif 

    <script>    

    $(document).ready(function() {
        // Add change event listener to checkboxes
        $('input[name="rate"]').change(function() {
            // Get the value of the selected checkbox
            var selectedValue = $(this).val();

            // Check if the selected value is less than 5
            if (selectedValue < 5) {
                // If less than 5, show the #reason div
                $('#reason').removeClass('d-none');
            } else {
                // If 5 or greater, hide the #reason div
                $('#reason').addClass('d-none');
            }
        });
    });


    const csrfToken = $('meta[name="csrf-token"]').attr('content');


        function startTask(element) {
            // Retrieve the userTask value from the clicked anchor tag
            const url = $(element).data('url');
            const endTask = $("#" + $(element).data('end-task'));

            // Send an Ajax request to start the task
            $.ajax({
                type: 'PUT',
                url: url,
                data: {
                    action: 'start',
                    _token: csrfToken, // Include the CSRF token in the request
                },
                success: function(response) {
                    // Update the button text with the start time
                    $(element).text(response.start_time);
                    endTask.attr('data-started','1');
                },
                error: function(error) {
                    console.error('Error starting the task:', error);
                }
            });
        }

        function endTask(element) {
            // Retrieve the userTask value from the clicked anchor tag
            const url = $(element).data('url');
            const started = $(element).data('started'); // Get the data-started attribute value

            if (started != '1') {
                return false;
            }

            // Send an Ajax request to end the task
            $.ajax({
                type: 'PUT',
                url: url,
                data: {
                    action: 'end',
                    _token: csrfToken, // Include the CSRF token in the request
                },
                success: function(response) {
                    // Update the button text with the end time
                    $(element).text(response.end_time);
                },
                error: function(error) {
                    console.error('Error ending the task:', error);
                }
            });
        }   

        $(document).ready(function () {
            // Add event listener to all elements with the class "assisted"
            $('.assisted').on('change', function () {
                toggleAssistedCountVisibility($(this));
            });

            // Function to toggle visibility
            function toggleAssistedCountVisibility(assistedDropdown) {
                const assistedCountInput = assistedDropdown.closest('.row').find('.assisted-count');
                
                // Check the selected value of the "Assisted" dropdown
                if (assistedDropdown.val() === '1') {
                    // Show the nearest "Assisted Count" input if assisted is selected
                    assistedCountInput.show();
                } else {
                    // Hide the nearest "Assisted Count" input if not assisted
                    assistedCountInput.hide();
                }
            }

            $('.assisted').each(function () {
                toggleAssistedCountVisibility($(this));
            });
        });


        $(".disabled-task a").css("display","none");
        $(".disabled-task textarea").attr("disabled",true);
        $(".disabled-task input").attr("disabled",true);
        $(".disabled-task button").attr("disabled",true);
        $(".disabled-task select").attr("disabled",true);

    //   $(".disabled-sub-task a").attr("href","#");
    </script>
@endif 
@endpush 
@push('css')
    <style>
        .disabled-sub-task 
        {
           /* background:#F3EFEF */
        }
        .sub-task
        {
            background: #f3efef;
            width: 90%;
            margin: auto;
            margin-bottom: 5px;
            border-radius: 5px;
        }
        
        .card-custom
        {
            padding-bottom:100px;
            margin-bottom:130px
        }
        /* CSS for the overlay */
        .disabled-task {
            position: relative;
        }

        .disabled-task::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); /* Adjust the opacity (0.5) as needed */
            pointer-events: none; /* Prevent the overlay from capturing mouse events */
            z-index: 1; /* Adjust the z-index to ensure it's above the content */
        }

    </style>
@endpush 