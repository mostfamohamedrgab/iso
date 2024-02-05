@extends('dashboard.layouts.app')
@section('pageTitle' ,  $project->title)
@section('content')
<!-------------->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">

    <div class="card card-flush">

        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <h2> {{$project->title}} - tasks ({{$tasks->count()}})</h2>
        </div>
   
    <!--begin::Card header-->
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <input type="text" data-kt-ecommerce-category-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="@lang('dashboard.search_title', ['page_title' => __('dashboard.tasks')])" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--end::Add customer-->
                    <span class="w-5px h-2px"></span>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->

        <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3" >
                                        <input class="form-check-input" id="checkedAll"  type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_category_table .form-check-input" value="1" />
                                    </div>
                                </th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Project</th>
                                <th>objective</th>
                                <th>Status</th>
                                <th class="text-end min-w-70px">@lang('dashboard.actions')</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                            @foreach ($tasks as $task)
                                <!--begin::Table row-->
                                <tr data-id="{{$task->id}}">
                                    <!--begin::Checkbox-->
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid ">
                                            <input class="form-check-input checkSingle" type="checkbox" value="1" id="{{$task->id}}"/>
                                        </div>
                                    </td>
                                    <!--end::Checkbox-->
                                    <!--begin::Category=-->
                                    <td>
                                        <div class="d-flex">
                                            <div class="ms-5">
                                                <!--begin::Title-->
                                                <a href="{{ route('tasks.show', $task->id) }}" class="text-gray-800 text-hover-primary fs-5 fw-bolder mb-1" data-kt-ecommerce-category-filter="category_name">{{$task->title }}</a>
                                                <!--end::Title-->
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->project->title }}</td>
                                    <td>{{$task->tasks->count() }}</td>
                                    <td>
                                        @if(auth()->user()->tasks->where('id',$task->id)->first())
                                            {{auth()->user()->tasks->where('id',$task->id)->first()->pivot->status }}
                                        @endif 
                                    </td>
                                
                                    <!--begin::Action=-->
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            actions
                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                        <span class="svg-icon svg-icon-5 m-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon--></a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            @can('tasks.show')
                                            <div class="menu-item px-3">
                                                <a href="{{route('tasks.show', $task->id)}}" class="menu-link px-3">show</a>
                                            </div>
                                            @endcan 
                                            @can('tasks.edit')
                                            <div class="menu-item px-3">
                                                <a href="{{route('tasks.edit', $task->id)}}" class="menu-link px-3">edit</a>
                                            </div>
                                            @endcan 
                                            <!--end::Menu item-->
                                            @can('tasks.destroy')
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-kt-ecommerce-category-filter="delete_row" data-url="{{route('tasks.destroy', $task->id)}}" data-id="{{$task->id}}">delete</a>
                                            </div>
                                            @endcan 
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                    <!--end::Action=-->
                                </tr>
                                <!--end::Table row-->
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                 </div>

                 @if(!auth()->user()->can('belongs-tasks'))
                 <hr style="background:#ddd;padding:10px">
                    <div class="row ">
                            @foreach(taskStatus() as $status)
                                <div class="col-md-4 ">
                                    <div class="card" style="width: 90%;margin:auto">
                                        <div class="card-body">
                                            <a href="#" class="btn btn-primary" style="display:block;margin:10px 0">{{ $userTasks->where('status',$status)->count() }}</a>
                                            <h5 class="card-title">{{ $status }}</h5>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <div class="col-md-4 ">
                                    <div class="card" style="width: 100%">
                                        <div class="card-body">
                                            <a href="#" class="btn btn-primary" style="display:block;margin:10px 0">{{ $userTasks->sum('errors_count') }}</a>
                                            <h5 class="card-title">Errors</h5>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12 mt-3">
                                    <div class="card card-flush">
                                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                            <h1>Task Analytics</h1>
                                        </div>
                                        <div class="card-body pt-0">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                                                <thead>
                                                    <tr>
                                                        <th>Task</th>
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
                                                    @foreach($userTasks as $analytics)
                                                        <tr>
                                                            <td>
                                                                <a href="{{ route('tasks.show',$task->id) }}">
                                                                    {{$analytics->task->title}}
                                                                </a>
                                                            </td>
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
                  

                    @endif 


        </div>
    </div>
    <!--end::Container-->
</div>
@endsection

