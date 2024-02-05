<div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">
    <div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
        <div class=" menu-active-bg">
            <div class="menu-item">
                <a class="menu-link {{ isActiveRoute('home') }}" href="{{route('home')}}">
                    <span class="menu-bullet">
                            <img src="{{ asset('images/home.png') }}" style="width:25px;height:25px">
                    </span>
                    <span class="menu-title">
                        Home
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="menu-item">
        <div class="menu-content pt-8 pb-2">
            <span class="menu-section text-muted text-uppercase fs-8 ls-1">Pages</span>
        </div>
    </div>


@can('roles.index')
    <!--begin::Menu item-->
<div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['roles.index' , 'roles.create' , 'roles.edit'])}}" data-kt-menu-trigger="click">
    <!--begin::Menu link-->
    <a href="#" class="menu-link py-3 {{areActiveRoutes(['roles.index' , 'roles.create' , 'roles.edit'])}}">
        <span class="menu-icon">
            <img src="{{ asset('images/roles.png') }}" style="width:25px;height:25px">
        </span>
        <span class="menu-title">@lang('dashboard.roles')</span>
        <span class="menu-arrow"></span>
    </a>
    <!--end::Menu link-->

    <!--begin::Menu sub-->
    <div class="menu-sub menu-sub-accordion pt-3">
        <!--begin::Menu item-->
        <div class="menu-item">
            <a href="{{ route('roles.index') }}" class="menu-link py-3  {{ isActiveRoute('roles.index') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.roles')])</span>
            </a>
        </div>
        <!--end::Menu item-->
        @can('roles.create')
        <!--begin::Menu item-->
        <div class="menu-item">
            <a href="{{route('roles.create')}}" class="menu-link py-3 {{ isActiveRoute('roles.create') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.role')])</span>
            </a>
        </div>
        <!--end::Menu item-->
        @endcan
    </div>
    <!--end::Menu sub-->
</div>
<!--end::Menu item-->
@endcan 
@can('admins.index')
    <!--begin::Menu item-->
    <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['admins.index' , 'admins.create' , 'admins.edit'])}}" data-kt-menu-trigger="click">
        <!--begin::Menu link-->
        <a href="#" class="menu-link py-3 {{areActiveRoutes(['admins.index' , 'admins.create' , 'admins.edit'])}}">
            <span class="menu-icon">
                    <img src="{{ asset('images/admins.png') }}" style="width:25px;height:25px">
            </span>
            <span class="menu-title">@lang('dashboard.admins')</span>
            <span class="menu-arrow"></span>
        </a>
        <!--end::Menu link-->
        <!--begin::Menu sub-->
        <div class="menu-sub menu-sub-accordion pt-3">
            <!--begin::Menu item-->
            <div class="menu-item">
                <a href="{{ route('admins.index') }}" class="menu-link py-3  {{ isActiveRoute('admins.index') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.admins')])</span>
                </a>
            </div>
            <!--end::Menu item-->
            @can('admins.create')
            <!--begin::Menu item-->
            <div class="menu-item">
                <a href="{{route('admins.create')}}" class="menu-link py-3 {{ isActiveRoute('admins.create') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.admin')])</span>
                </a>
            </div>
            @endcan 
            <!--end::Menu item-->
        </div>
        <!--end::Menu sub-->
    </div>
    <!--end::Menu item-->
@endcan 
@can('customers.index')
    <!--begin::Menu item-->
    <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['customers.index','customers.show', 'customers.create' , 'customers.edit'])}}" data-kt-menu-trigger="click">
        <!--begin::Menu link-->
        <a href="#" class="menu-link py-3 {{areActiveRoutes(['customers.index' , 'customers.create' , 'customers.edit'])}}">
            <span class="menu-icon">
                    <img src="{{ asset('images/customers.png') }}" style="width:25px;height:25px">
            </span>
            <span class="menu-title">@lang('dashboard.customers')</span>
            <span class="menu-arrow"></span>
        </a>
        <!--end::Menu link-->
        <!--begin::Menu sub-->
        <div class="menu-sub menu-sub-accordion pt-3">
            <!--begin::Menu item-->
            <div class="menu-item">
                <a href="{{ route('customers.index') }}" class="menu-link py-3  {{ isActiveRoute('customers.index') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.customers')])</span>
                </a>
            </div>
            <!--end::Menu item-->
            @can('customers.create')
            <!--begin::Menu item-->
            <div class="menu-item">
                <a href="{{route('customers.create')}}" class="menu-link py-3 {{ isActiveRoute('customers.create') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.customers')])</span>
                </a>
            </div>
            @endcan 
            <!--end::Menu item-->
        </div>
        <!--end::Menu sub-->
    </div>
    <!--end::Menu item-->
@endcan 
@can('projects.index')
    <!--begin::Menu item-->
    <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['projects.index','projects.show', 'projects.create' , 'projects.edit'])}}" data-kt-menu-trigger="click">
        <!--begin::Menu link-->
        <a href="#" class="menu-link py-3 {{areActiveRoutes(['projects.index' , 'projects.create' , 'projects.edit'])}}">
            <span class="menu-icon">
                    <img src="{{ asset('images/projects.png') }}" style="width:25px;height:25px">
            </span>
            <span class="menu-title">@lang('dashboard.projects')</span>
            <span class="menu-arrow"></span>
        </a>
        <!--end::Menu link-->
        <!--begin::Menu sub-->
        <div class="menu-sub menu-sub-accordion pt-3">
            <!--begin::Menu item-->
            <div class="menu-item">
                <a href="{{ route('projects.index') }}" class="menu-link py-3  {{ isActiveRoute('projects.index') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.projects')])</span>
                </a>
            </div>
            <!--end::Menu item-->
            @can('projects.create')
            <!--begin::Menu item-->
            <div class="menu-item">
                <a href="{{route('projects.create')}}" class="menu-link py-3 {{ isActiveRoute('projects.create') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.projects')])</span>
                </a>
            </div>
            @endcan 
            <!--end::Menu item-->
        </div>
        <!--end::Menu sub-->
    </div>
    <!--end::Menu item-->
@endcan 
@can('tasks.index')
    <!--begin::Menu item-->
    <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['tasks.index' ,'tasks.show', 'tasks.create' , 'tasks.edit'])}}" data-kt-menu-trigger="click">
        <!--begin::Menu link-->
        <a href="#" class="menu-link py-3 {{areActiveRoutes(['tasks.index' , 'tasks.create' , 'tasks.edit'])}}">
            <span class="menu-icon">
                    <img src="{{ asset('images/tasks.png') }}" style="width:25px;height:25px">
            </span>
            <span class="menu-title">@lang('dashboard.tasks')</span>
            <span class="menu-arrow"></span>
        </a>
        <!--end::Menu link-->
        <!--begin::Menu sub-->
        <div class="menu-sub menu-sub-accordion pt-3">
            <!--begin::Menu item-->
            <div class="menu-item">
                <a href="{{ route('tasks.index') }}" class="menu-link py-3  {{ isActiveRoute('tasks.index') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.tasks')])</span>
                </a>
            </div>
            <!--end::Menu item-->
            @can('tasks.create')
            <!--begin::Menu item-->
            <div class="menu-item">
                <a href="{{route('tasks.create')}}" class="menu-link py-3 {{ isActiveRoute('tasks.create') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.tasks')])</span>
                </a>
            </div>
            @endcan 
            <!--end::Menu item-->
        </div>
        <!--end::Menu sub-->
    </div>
    <!--end::Menu item-->
@endcan 
@can('objective.index')
    <!--begin::Menu item-->
    <div class="menu-item menu-sub-indention menu-accordion  {{areActiveRoutes(['objective.index' ,'objective.show', 'objective.create' , 'objective.edit'])}}" data-kt-menu-trigger="click">
        <!--begin::Menu link-->
        <a href="#" class="menu-link py-3 {{areActiveRoutes(['objective.index' , 'objective.create' , 'objective.edit'])}}">
            <span class="menu-icon">
                    <img src="{{ asset('images/objective.png') }}" style="width:25px;height:25px">
            </span>
            <span class="menu-title">@lang('dashboard.objective')</span>
            <span class="menu-arrow"></span>
        </a>
        <!--end::Menu link-->
        <!--begin::Menu sub-->
        <div class="menu-sub menu-sub-accordion pt-3">
            <!--begin::Menu item-->
            <div class="menu-item">
                <a href="{{ route('objective.index') }}" class="menu-link py-3  {{ isActiveRoute('objective.index') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">@lang('dashboard.all_title', ['page_title' => __('dashboard.objective')])</span>
                </a>
            </div>
            <!--end::Menu item-->
            @can('objective.create')
            <!--begin::Menu item-->
            <div class="menu-item">
                <a href="{{route('objective.create')}}" class="menu-link py-3 {{ isActiveRoute('objective.create') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">@lang('dashboard.create_title', ['page_title' => __('dashboard.objective')])</span>
                </a>
            </div>
            @endcan 
            <!--end::Menu item-->
        </div>
        <!--end::Menu sub-->
    </div>
    <!--end::Menu item-->
@endcan 

    
  
</div>