@section('pageTitle' , "tasks")

@extends('dashboard.layouts.app')
@section('content')


<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">

    <h2>{{$task->title}} </h2>

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
    <!--begin::Card-->
    <div class="card card-custom gutter-b">
					<div class="card-body">
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
											{{$project->title}} - {{$task->title}}
										<i class="flaticon2-correct text-success icon-md ml-2"></i></h3>
										<!--end::Name-->
										<!--begin::Contacts-->
										<div class="d-flex flex-wrap my-2">
										
										</div>
										<!--end::Contacts-->
									</div>


								<div class="d-flex align-items-center justify-content-between flex-wrap">
									<div class="my-lg-0 my-1">
										<a id="start-task" href="#" class="btn btn-sm btn-success font-weight-bolder text-uppercase mr-3" onclick="startTask()">
											{{$userTask->start_time ? $userTask->start_time : 'Strat Task'}}
										</a>
										<a id="end-task" href="#" class="btn btn-sm btn-danger font-weight-bolder text-uppercase" onclick="endTask()">
											{{$userTask->end_time ? $userTask->end_time : 'End Task'}}
										</a>

										
									</div>
									@if($userTask->start_time AND $userTask->end_time)
										<span class="btn btn-sm btn-light-primary">
											Taken Time: {{$userTask->getTimeDiff()}}
										</span>
									@endif 
								</div>
								<!--end: Title-->
								<hr>
								<!--begin: Content-->
								<div class="d-flex align-items-center flex-wrap justify-content-between">
									<div class="flex-grow-1 font-weight-bold text-dark-50 py-5 py-lg-2 mr-5">
										{{$task->description }}
									</div>
									<div class="d-flex flex-wrap align-items-center py-2">
										
									</div>
								</div>

								<!--end: Content-->
							</div>
							<!--end: Info-->
						</div>
						<div class="separator separator-solid my-7"></div>
						<!--begin: Items-->
						<div class="d-flex align-items-center flex-wrap">
							<!--begin: Item-->
							<div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
								<div class="d-flex flex-column text-dark-75">
									@foreach($task->files as $file)
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
        <form id="kt_ecommerce_add_product_form" 
            data-kt-redirect="{{  route('tasks.show',$task->id)  }}" 
            action="{{  route('tasks.rate',$userTask->id)  }}"
            method="post" enctype="multipart/form-data" 
            class="form d-flex flex-column flex-lg-row store" >
            <!--begin::Card body-->
			@csrf 
            @method('PUT')

            <div class="card-body border-top p-9">
                <!--begin::Input group-->
                <div class="row ">

                    <!-- Add fields for notes, errors_count, assisted, and status -->
                    <div class="com-md-12 mt-5">
                        <label class="">Notes</label>
                        <div class="">
                            <textarea name="notes" style="height:80px;" placeholder="Notes" class="form-control form-control-lg form-control-solid">{{ isset($userTask) ? $userTask->notes : old('notes') }}</textarea>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-5">
                        <label class="">Errors Count</label>
                        <div class="">
                            <input type="number" name="errors_count" placeholder="Errors Count" class="form-control form-control-lg form-control-solid" value="{{ isset($userTask) ? $userTask->errors_count : old('errors_count') }}">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-5">
                        <label class="">Assisted</label>
                        <div class="">
                            <select name="assisted" class="form-select form-select-lg form-select-solid">
                                <option value="1" {{ isset($userTask) && $userTask->assisted == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ isset($userTask) && $userTask->assisted == 0 ? 'selected' : '' }}>No</option>
                            </select>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-5">
                        <label class="">Status</label>
                        <div class="">
                            <select name="status" class="form-select form-select-lg form-select-solid">
                                @foreach(taskStatus() as $status)
                                <option value="{{ $status }}" {{ isset($userTask) && $userTask->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
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
				</div>
				<!--end::Card-->

    </div>
    <!--end::Container-->
    @endif <!--- end -->
</div>
<!--end::Post-->
					
@endsection
@push('js')

@if(!$report)
    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        function startTask() {
            // Check if the userTask has already started
            if (!('{{ $userTask->start_time }}')) {
                // Send an Ajax request to start the task
                $.ajax({
                    type: 'PUT',
                    url: '{{ route('tasks.time', $userTask->id) }}',
                    data: { action: 'start' },
                    success: function(response) {
                        // Update the button text with the start time
                        $('#start-task').text(response.start_time);
                    },
                    error: function(error) {
                        console.error('Error starting the task:', error);
                    }
                });
            }
        }

        function endTask() {
            // Check if the userTask has already started and not ended
            if ('{{ $userTask->start_time }}' && !('{{ $userTask->end_time }}')) {
                // Send an Ajax request to end the task
                $.ajax({
                    type: 'PUT',
                    url: '{{ route('tasks.time', $userTask->id) }}',
                    data: { action: 'end' },
                    success: function(response) {
                        // Update the button text with the end time
                        $('#end-task').text(response.end_time);
                    },
                    error: function(error) {
                        console.error('Error ending the task:', error);
                    }
                });
            }
        }
    </script>
@endif 
@endpush 