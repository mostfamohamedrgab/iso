@extends('dashboard.layouts.app')

@section('content')


<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1"></h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <!--begin::Secondary button-->
                @can('projects.create')
                <a href="{{ route('projects.create') }}" class="btn btn-sm btn-light">New project</a>
                @endcan 
                
                @can('tasks.create')
                <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-primary">New task</a>
                @endcan 
                <!--end::Primary button-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">

            <div class="row">
                @can('customers.index')
                    <div class="col-md-4">
                        <a href="{{ route('customers.index') }}">
                            <div class="card">
                                <div class="card-body">
                                    <h3>total customers</h3>
                                    <h5>{{ $totalCustomersCount }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endcan

                    @can('projects.index')
                    <div class="col-md-4">
                        <a href="{{ route('projects.index') }}">
                            <div class="card">
                                <div class="card-body">
                                    <h3>total projects</h3>
                                    <h5>{{ $totalProjectsCount }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endcan

                    @can('tasks.index')
                    <div class="col-md-4">
                        <a href="{{ route('tasks.index') }}">
                            <div class="card">
                                <div class="card-body">
                                    <h3>total tasks</h3>
                                    <h5>{{ $totalTasksCount }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endcan
            </div>

            <div class="row mt-10">
                @can('projects.index')
                <div class="col-6 mb-xl-10">
                    <!--begin::List widget for Projects-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800"> @lang('dashboard.projects')</span>
                            </h3>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-4 px-0">
                            <!--begin::Tab Content-->
                            <div class="tab-content px-9 hover-scroll-overlay-y pe-7 me-3 mb-2" style="height: 454px">
                                <!--begin::Tab pane-->
                                <div class="tab-pane fade show active" id="kt_list_widget_16_tab_1">
                                    @forelse($latestProjects as $project)
                                        <!--begin::Project Item-->
                                        <div class="m-0">
                                            <!--begin::Timeline-->
                                            <div class="timeline ms-n1">
                                                <!--begin::Timeline item-->
                                                <div class="timeline-item align-items-center mb-4">
                                                    <div class="timeline-line w-20px mt-9 mb-n14"></div>
                                                    <div class="timeline-icon pt-1">
                                                        <i class="fa fa-cogs"></i>
                                                    </div>
                                                    <!--begin::Timeline content-->
                                                    <div class="timeline-content m-0">
                                                        <span class="fs-8 fw-boldest text-success text-uppercase">
                                                            <a href="{{ route('projects.show', $project->id) }}">
                                                                {{ $project->title }}
                                                            </a>
                                                        </span>
                                                        <span class="fw-bold text-gray-400 d-block">start_date: {{ $project->start_date }}</span>
                                                        <span class="fw-bold text-gray-400 d-block">end_date: {{ $project->end_date }}</span>
                                                    </div>
                                                    <!--end::Timeline content-->
                                                </div>
                                                <!--end::Timeline item-->
                                            </div>
                                            <!--end::Timeline-->
                                        </div>
                                        <!--end::Project Item-->
                                        <div class="separator separator-dashed mt-5 mb-4"></div>
                                    @empty
                                        <div class="alert alert-info text-center"> no projects</div>
                                    @endforelse
                                </div>
                                <!--end::Tab pane-->
                            </div>
                            <!--end::Tab Content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::List widget for Projects-->
                </div>
                <!--end::Col-->
                @endcan

                @can('tasks.index')
                <div class="col-6 mb-xl-10">
                    <!--begin::List widget for Tasks-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800">tasks</span>
                            </h3>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-4 px-0">
                            <div class="tab-content px-9 hover-scroll-overlay-y pe-7 me-3 mb-2" style="height: 454px">
                                <div class="tab-pane fade show active">
                                    @forelse($latestTasks as $task)
                                        <div class="m-0">
                                            <div class="timeline ms-n1">
                                                <div class="timeline-item align-items-center mb-4">
                                                    <div class="timeline-line w-20px mt-9 mb-n14"></div>
                                                    <div class="timeline-icon pt-1">
                                                        <i class="fa fa-tasks"></i>
                                                    </div>
                                                    <div class="timeline-content m-0">
                                                        <span class="fs-8 fw-boldest text-success text-uppercase">
                                                            <a href="{{ route('tasks.show', $task->id) }}">
                                                                {{ $task->title }}
                                                            </a>
                                                        </span>
                                                        <span class="fw-bold text-gray-400 d-block">project: {{ $task->project->title }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="separator separator-dashed mt-5 mb-4"></div>
                                    @empty
                                        <div class="alert alert-info text-center">no tasks</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::List widget for Tasks-->
                </div>
                @endcan
            </div>



        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
    </div>
<!--end::Content-->



@section('scripts')
   <script src="{{asset('dashboard/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <script src="{{asset('dashboard/assets/plugins/custom/vis-timeline/vis-timeline.bundle.js')}}"></script>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <!--end::Page Vendors Javascript-->
    <!--begin::Page Custom Javascript(used by this page)-->
    <script src="{{asset('dashboard/assets/js/widgets.bundle.js')}}"></script>
    <script src="{{asset('dashboard/assets/js/custom/widgets.js')}}"></script>
    <script src="{{asset('dashboard/assets/js/custom/apps/chat/chat.js')}}"></script>
    <script src="{{asset('dashboard/assets/js/custom/utilities/modals/upgrade-plan.js')}}"></script>
    <script src="{{asset('dashboard/assets/js/custom/utilities/modals/users-search.js')}}"></script>
@endsection
@endsection
