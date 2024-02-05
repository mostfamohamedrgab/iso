@extends('dashboard.layouts.app')
@section('style')
<link href="{{asset('dashboard/css/main.css')}}" rel="stylesheet" type="text/css" />

@endsection
@section('content')


    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <!--begin::Title-->
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">تفاصيل الطلب</h1>
                    <!--end::Title-->
                    <!--begin::Separator-->
                    <span class="h-20px border-gray-300 border-start mx-4"></span>
                    <!--end::Separator-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{route('home')}}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{route('orders.index')}}" class="text-muted text-hover-primary">الطلبات</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-dark"> تعديل الطلب</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
      
            </div>
            <!--end::Container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Form-->
                <form id="kt_ecommerce_add_product_form" method="POST" action="{{route('orders.update' , $order->id)}}" enctype="multipart/form-data" 
                    class="form d-flex flex-column flex-lg-row">
                    @csrf
                    @method('PUT')
                    <!--begin::Aside column-->
                    <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                    
                        <!--begin::Status-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Status</h2>
                                </div>
                                <!--end::Card title-->
                                <!--begin::Card toolbar-->
                                <select class="form-select mb-2" data-control="select2" data-hide-search="true"
                                    data-placeholder="Select an option" name="status" id="kt_ecommerce_add_category_status_select">
                                    <option></option>
                                    <option value="pending" {{$order->status == "pending" ? 'selected' : ''}} >@lang('apis.pending_order')</option>
                                    <option value="preparing" {{$order->status == "preparing" ? 'selected' : ''}} >@lang('apis.preparing_order')</option>
                                    <option value="delegate_accept" {{$order->status == "delegate_accept" ? 'selected' : ''}} >@lang('apis.delegate_accept')</option>
                                    <option value="cancelled" {{$order->status == "cancelled" ? 'selected' : ''}} >@lang('apis.cancelled_order')</option>
                                    <option value="done"      {{$order->status == "done" ? 'selected' : ''}} >@lang('apis.done_order')</option>
                                </select>
                                <!--begin::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Status-->

                        <!--begin::users-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>المستخدم</h2>
                                </div>
                                <!--end::Card title-->
                                <!--begin::Card toolbar-->
                                <select class="form-select mb-2" data-control="select2" data-hide-search="true"
                                    data-placeholder="Select an option" name="user_id" id="kt_ecommerce_add_user_id_select">
                                    <option></option>
                                    @foreach ($users as $user)
                                    <option value="{{$user->id}}" {{$order->user_id == $user->id ? 'selected' : ''}}> {{ $user->name }}</option>
                                    @endforeach

                                </select>
                                <!--begin::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::users-->

                    </div>
                    <!--end::Aside column-->
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <!--begin::Tab content-->
                        <div class="tab-content">
                            <!--begin::Tab pane-->
                            <div class="tab-pane fade show active" id="kt_ecommerce_add_product_en" role="tab-panel">
                                <div class="d-flex flex-column gap-7 gap-lg-10">
                                    <!--begin::Inventory-->
                                    <div class="card card-flush py-4">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>تفاصيل الطلب</h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="required form-label">نوع الغسلة </label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <select class="form-select mb-2" data-control="select2" data-hide-search="true"
                                                data-placeholder="Select an option" name="category_id" id="kt_ecommerce_add_category_status_select">
                                                <option></option>
                                                @foreach ($categories as $category)
                                                <option value="{{$category->id}}" {{$order->category_id == $category->id ? 'selected' : ''}}> {{ $category->name }}</option>
                                                @endforeach
                                                </select>
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="required form-label">نوع السيارة </label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <select class="form-select mb-2" data-control="select2" data-hide-search="true"
                                                data-placeholder="Select an option" name="car_id" id="kt_ecommerce_add_car_status_select">
                                                <option></option>
                                                @foreach ($cars as $car)
                                                <option value="{{$car->id}}" {{$order->car_id == $car->id ? 'selected' : ''}}> {{ $car->name }}</option>
                                                @endforeach
                                                </select>
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="required form-label">العنوان</label>
                                                <div class="col-md-12 col-12 mt-2 mb-2">
                                                    <label for="pac-input"></label>
                                                    <input id="pac-input" class="form-control mb-2 controls" type="text" placeholder="ابحث بإسم الحي او الشارع"/>
                                                    <div id="map" class="h-100 w-100"></div>
                                                </div>
                                                <div class="col-12 mt-2 mb-2">
                                                    <div class="form_input">
                                                        <span class="fontBold mt-2 mb-2 d-block">العنوان</span>
                                                        <label id="current" class="position-relative w-100 form_label">
                                                            <input id="address" name="address" class="form-control mb-2 location" readonly required  value="{{ $order->address }}">
                                                            <span class="input_icon"><i class="fa-solid fa-location-dot"></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <!--end::Label-->
                                                <input type="hidden" id="lat" name="lat" value="{{ $order->lat }}">
                                                <input type="hidden" id="lng" name="lng" value="{{ $order->lng }}">


                                            </div>
                                            <!--end::Input group-->


                                            {{-- @if($order->coupon) --}}
                                            <!--begin::Input group-->
                                            <div class="mb-10 fv-row">
                                                <!--begin::Label-->
                                                <label class="required form-label">الكوبون</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="text" value="{{ $order->coupon ? $order->coupon->code : "not use coupon" }}"
                                                    class="form-control mb-2" readonly/>
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->

                                            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-1 row-cols-xl-3 g-9" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']">
                                                <!--begin::Col-->
                                                <div class="col">
                                                    <!--begin::Option-->
                                                    <label class="btn btn-outline btn-outline-dashed btn-outline-default active d-flex text-start p-6" data-kt-button="true">
                                                        <!--begin::Radio-->
                                                        <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                            <input class="form-check-input" type="radio" disabled name="discount_option" value="1"  {{ !$order->coupon ? "checked='checked'" : ""}}>
                                                        </span>
                                                        <!--end::Radio-->
                                                        <!--begin::Info-->
                                                        <span class="ms-5">
                                                            <span class="fs-4 fw-bolder text-gray-800 d-block">No Discount</span>
                                                        </span>
                                                        <!--end::Info-->
                                                    </label>
                                                    <!--end::Option-->
                                                </div>
                                                <!--end::Col-->
                                                <!--begin::Col-->
                                                <div class="col">
                                                    <!--begin::Option-->
                                                    <label class="btn btn-outline btn-outline-dashed btn-outline-default d-flex text-start p-6" data-kt-button="true">
                                                        <!--begin::Radio-->
                                                        <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                            <input class="form-check-input" type="radio" disabled name="discount_option" {{$order->coupon && $order->coupon->percentage ? "checked='checked'" : ""}} value="2">
                                                        </span>
                                                        <!--end::Radio-->
                                                        <!--begin::Info-->
                                                        <span class="ms-5">
                                                            <span class="fs-4 fw-bolder text-gray-800 d-block">Percentage {{$order->coupon&&$order->coupon->percentage ? '(' .  $order->coupon->discount .')' : '' }} %</span>
                                                        </span>
                                                        <!--end::Info-->
                                                    </label>
                                                    <!--end::Option-->
                                                </div>
                                                <!--end::Col-->
                                                <!--begin::Col-->
                                                <div class="col">
                                                    <!--begin::Option-->
                                                    <label class="btn btn-outline btn-outline-dashed btn-outline-default d-flex text-start p-6" data-kt-button="true">
                                                        <!--begin::Radio-->
                                                        <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                                            <input class="form-check-input" type="radio" disabled name="discount_option" {{$order->coupon && $order->coupon->discount ? "checked='checked'" : ""}} value="3">
                                                        </span>
                                                        <!--end::Radio-->
                                                        <!--begin::Info-->
                                                        <span class="ms-5">
                                                            <span class="fs-4 fw-bolder text-gray-800 d-block">Fixed Price  {{$order->coupon && !$order->coupon->percentage ? ($order->coupon->discount) : '' }}</span>
                                                        </span>
                                                        <!--end::Info-->
                                                    </label>
                                                    <!--end::Option-->
                                                </div>
                                                <!--end::Col-->
                                            </div>

                                            {{-- //end coupon --}}
                                           
                                        </div>
                                        <!--end::Card header-->
                                    </div>
                                    <!--end::Inventory-->
                                </div>
                            </div>
                            <!--end::Tab pane-->
                        </div>
                        <!--end::Tab content-->
                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <a href="{{ route('orders.index') }}" id="kt_ecommerce_add_product_cancel"
                                class="btn btn-light me-5">Cancel</a>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                <span class="indicator-label">Save Changes</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                            <!--end::Button-->
                        </div>
                    </div>
                    <!--end::Main column-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->
@section('scripts')

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBn3NtsJ5lgHSIxUJ4AuqAMm2RXldDDjN8&callback=initAutocomplete&libraries=places&v=weekly" async></script>

<script>
    function initAutocomplete() {

        var map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: Number("{{$order->lat}}"), lng: Number("{{$order->lng}}") },
            zoom: 13
        });
        var input = document.getElementById('pac-input');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);
        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29),
            draggable: true
        });
        google.maps.event.addListener(map, 'click', function(event) {
            document.getElementById("lat").value = event.latLng.lat();
            document.getElementById("lng").value = event.latLng.lng();

            marker.setPosition(event.latLng);

        });
        marker.addListener('position_changed', printMarkerLocation);

        function printMarkerLocation() {
            console.log( marker.position.lat());
            document.getElementById('lat').value = marker.position.lat();
            document.getElementById('lng').value = marker.position.lng();
        
        }

        autocomplete.addListener('place_changed', function() {
            infowindow.close();
            marker.setVisible(false);
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }
            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            marker.setIcon(({
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(35, 35)
            }));
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
            var address = '';
            if (place.address_components) {
                console.log(place.address_components[0]);
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }
            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);
            //Location details
            console.log( place.address_components.length);
            for (var i = 0; i < place.address_components.length; i++) {
console.log(place.formatted_address);
             document.getElementById('address').value = place.formatted_address;

            }

        });
    }
</script>



@endsection

@endsection
