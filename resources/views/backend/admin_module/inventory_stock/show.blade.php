@extends('backend.layouts.admin')

@section('breadcrumb')
    
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ url($url_prefix . '/inventory_stocks') }}" class="kt-subheader__breadcrumbs-link">
        Item Stock </a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">View</span>
@endsection

@section('content')
    <!-- Messages section -->
    @include('backend.layouts.includes.notification_alerts')
    <div class="alert alert-light alert-elevate" role="alert">
        <div class="alert-icon"><i class="flaticon-information kt-font-brand"></i></div>
        <div class="alert-text">
            Display all the details of an inventory item stock.
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                 id="kt_page_portlet">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">Inventory Item Stock Info:</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ url($url_prefix . '/inventory_stocks') }}" class="btn btn-clean kt-margin-r-10">
                            <i class="la la-arrow-left"></i>
                            <span class="kt-hidden-mobile">Back to List</span>
                        </a>
                        <a href="{{ url($url_prefix . '/inventory_stock/edit/'.$item['id']) }}"
                           class="btn btn-default btn-icon-sm">
                            <i class="la la-edit"></i>
                            <span class="kt-hidden-mobile">Edit</span>
                        </a>&nbsp;&nbsp;
                        <a class="btn btn-icon-sm btn-hover-danger btn-font-danger btn-delete" href="javascript:;"
                           onclick="delete_record('{{ url($url_prefix . '/inventory_stock/delete/'.$item['id']) }}');">
                            <i class="la la-trash"></i>
                            <span class="kt-hidden-mobile">Delete</span>
                        </a>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    {!! Form::open(['class' => 'kt-form']) !!}
                    <div class="row">
                        
                        <div class="col-md-12">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body">
                                    <h3 class="kt-section__title kt-section__title-lg"></h3>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Item Category
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label "><strong>{{ $item['inventorymaster']['inventory_category']['inventory_name'] }}</strong></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Item Name
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['inventorymaster']['item_name'] }}</strong></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Supplier
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['supplier']['supplier_name'] }}</strong></label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Quantity
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['quantity'] }}</strong></label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Purchase Price
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['purchase_price'] }}</strong></label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Date
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ date('M d, Y', strtotime($item['date'])) }}</strong></label>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Description
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{!! $item['description'] !!}</strong></label>
                                        </div>
                                    </div>
                                    
                                    @if(isset($item['document']['0']) && !empty($item['document']))
                                    @php
                                    $file_type1='fa-file-download';
                                    $file_color_one='#D04423';
                                    @endphp
                                    @endif

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Document
                                        </label>
                                        <div class="col-9">
                                          @if(isset($item['document']) && !empty($item['document']))
                                             <span><a href="{{ URL::asset('uploads/inventory_stock_document/' .$item['document']) }}" download><i class="fa {{ $file_type1 }}" aria-hidden="true" style="font-size: 36px;color:{{$file_color_one}}"></i></a></span>
                                            @endif
                                        </div>
                                    </div>







                                   
                                </div>
                            </div>
                        </div>
                    
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
@endsection
