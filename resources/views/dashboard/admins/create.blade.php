@extends('dashboard.layouts.app')
@section('pageTitle' , "admins")
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
                    <h3 class="fw-bolder m-0">{{ isset($user) ? $user->name : 'Create New Account' }}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <!--begin::Form-->
                <form id="kt_ecommerce_add_product_form" 
                    data-kt-redirect="{{  isset($user) ? route('admins.edit',$user->id) : route('admins.create') }}" 
                action="{{ isset($user) ?  route('admins.update',$user->id) : route('admins.store')}}"
                          method="post" enctype="multipart/form-data" 
                        class="form d-flex flex-column flex-lg-row store" >
                    <!--begin::Card body-->
                    @csrf 
                    @if(isset($user)) @method('PUT') @endif

                    <div class="card-body border-top p-9">
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
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ isset($user) ? asset($user->image) : asset('assets/media/avatars/300-1.jpg') }})"></div>
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
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">Full Name</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text" name="name"  required class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Full Name" value="{{ isset($user) ? $user->name : old('name') }}">
                                    <div class="fv-plugins-message-container invalid-feedback"></div></div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        

                         <!--begin::Input group-->
                         <div class="row mb-6 ">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-6">
                                <span class="required">Email Address</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input style="" required type="email" name="email" class=" form-control form-control-lg form-control-solid" placeholder="Email Address" value="{{ isset($user) ? $user->email : old('email') }}">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                         <!--begin::Input group-->
                         <div class="row mb-6 ">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-6">
                                <span class="">Phone Number</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input style=""  type="tel" name="phone"  class=" form-control form-control-lg form-control-solid" placeholder="Phone Number" value="{{ isset($user) ? $user->phone : old('phone') }}">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                         <!--begin::Input group-->
                         <div class="row mb-6 ">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-6">
                                <span class="@if(!isset($user)) required @endif">Password</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input style="" @if(!isset($user)) required @endif type="password" name="password" class=" form-control form-control-lg form-control-solid" placeholder="Password">
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">
                                    <span class="required">@lang('dashboard.role')</span>
                                </label>
                                
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    <select name="role_id" required aria-label="Select a Country" data-control="select2" data-placeholder="@lang('dashboard.select_role')" 
                                    class="form-select form-select-solid form-select-lg fw-bold select2-hidden-accessible" data-select2-id="select2-data-10-05ls" tabindex="-1" aria-hidden="true">
                                        <option value="" data-select2-id="select2-data-12-wa4o">@lang('dashboard.select_role')</option>
                                        @foreach ($roles as $role)
                                        <option value="{{$role->id}}" 
                                            @if(isset($user)) 
                                                {{$user->roleName()  == $role->nickname_ar ? "selected" : ""}}
                                            @else 
                                                {{old('role_id')  == $role->id ? "selected" : ""}}
                                            @endif 
                                            >{{lang() == "ar" ? $role->nickname_ar : $role->nickname_en }}</option>
                                        @endforeach
                                    </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div></div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                       
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">
                                    <span class="required">is professional</span>
                                </label>
                                
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                    <select name="is_professional" required aria-label="Select a Country" data-control="select2" data-placeholder="is professional" 
                                    class="form-select form-select-solid form-select-lg fw-bold select2-hidden-accessible" data-select2-id="select2-data-10-05ls" tabindex="-1" aria-hidden="true">
                                        <option value="" data-select2-id="select2-data-12-wa4o">is professional</option>
                                        
                                        <option value="professional" 
                                            @if(isset($user)) 
                                                {{$user->is_professional  == 'professional' ? "selected" : ""}}
                                            @else 
                                                {{old('is_professional')  == 'professional' ? "selected" : ""}}
                                            @endif 
                                            >professional</option>
                                        <option value="Unprofessional" 
                                            @if(isset($user)) 
                                                {{$user->is_professional  == 'Unprofessional' ? "selected" : ""}}
                                            @else 
                                                {{old('is_professional')  == 'Unprofessional' ? "selected" : ""}}
                                            @endif 
                                            >Unprofessional</option>
                                    
                                    </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div></div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                       
                        <!--begin::Input group-->
                        <div class="row mb-0 mt-5">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-bold fs-6">Account Activation</label>
                            <!--begin::Label-->
                            <!--begin::Label-->
                            <div class="col-lg-8 d-flex align-items-center">
                                <div class="form-check form-check-solid form-switch fv-row">
                                    <input class="form-check-input w-45px h-30px" type="checkbox" 
                                        @if(isset($user))
                                            {{$user->is_active == '1' ? 'selected' : ''}}
                                        @else 
                                             {{old('is_active') == '1' ? 'selected' : ''}}
                                        @endif
                                    id="is_active" name="is_active" checked="checked">
                                    <label class="form-check-label" for="is_active"></label>
                                </div>
                            </div>
                            <!--begin::Label-->
                        </div>
                        <!--end::Input group-->
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
        $('#account_type').on('change', function (){
            account_type_location();
        });

        function account_type_location()
        {
            if($('#account_type').val() != 'security_director')
            {
                $('#locations').removeAttr('multiple');
            }else{
                $('#locations').attr('multiple','multiple');
                console.log(2);
            }
        }account_type_location();
    </script>
@endsection
