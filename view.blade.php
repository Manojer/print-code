@extends('layouts.app')

@section('page-css')
    <style>
        #invoice-summary-table th,
        #invoice-summary-table td,
        #invoice-item-details-table th,
        #invoice-item-details-table td,
        #vertical-invoice-item-details-table th,
        #vertical-invoice-item-details-table td,
        #invoice-item-make-size th,
        #invoice-item-make-size td,
        #vertical-invoice-item-make-size th,
        #vertical-invoice-item-make-size td,
        #invoice-item-pipe-size th,
        #invoice-item-pipe-size td,
        #invoice-item-label th,
        #invoice-item-label td,
        #curvers-invoice-item-details-table th,
        #curvers-invoice-item-details-table td
        #curvers-invoice-item-make-size th,
        #curvers-invoice-item-make-size td,
        #securityDoor-invoice-item-details-table th,
        #securityDoor-invoice-item-details-table td,
        #securityDoor-invoice-item-make-size th,
        #securityDoor-invoice-item-make-size td,
        #Curtain-invoice-item-details-table th,
        #Curtain-invoice-item-details-table td,
        #Curtain-invoice-item-make-size th,
        #Curtain-invoice-item-make-size td
        {
            font-size: 14px;
        }
        table.dataTable th,
        table.dataTable td {
            white-space: nowrap;
        }
    </style>
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('public/backend/assets/css/invoice.css') }}">


<input type="hidden" value="{{$purchase->id}}" id="order_id">

<div class="row">
    <div class="col-12">
        <div class="btn-group group-buttons">
{{--            <a class="btn btn-primary btn-sm print" href="#" data-print="invoice-view"><i class="ti-printer"></i>--}}
{{--                {{ _lang('Print') }}</a>--}}
{{--            <a class="btn btn-danger btn-sm" href="{{ route('purchase_orders.download_pdf', $purchase->id) }}"><i--}}
{{--                    class="ti-file"></i> {{ _lang('Export PDF') }}</a>--}}
{{--            @if($purchase->payment_status != 1)--}}
{{--            <a class="btn btn-success btn-sm ajax-modal" data-title="{{ _lang('Make Payment') }}"--}}
{{--                href="{{ route('purchase_orders.create_payment', $purchase->id) }}"><i class="ti-credit-card"></i>--}}
{{--                {{ _lang('Record a Payment') }}</a>--}}
{{--            @else--}}
{{--            <button class="btn btn-success btn-sm" disabled><i class="ti-receipt"></i>--}}
{{--                {{ _lang('PAID') }}</button>--}}
{{--            @endif--}}
{{--            <a class="btn btn-warning btn-sm" href="{{ action('PurchaseController@edit', $purchase->id) }}"><i--}}
{{--                    class="ti-pencil-alt"></i> Edit</a>--}}
{{--            <a class="btn btn-warning btn-sm ajax-modal" href="{{ route('Blinds.AddMoreRoller',['orderId'=>$purchase->id]) }}" data-reload="false"--}}
{{--               data-title="Add more blinds"><i--}}
{{--                        class="ti-plus"></i> Add more blinds</a>--}}
        </div>

        @php $date_format = get_company_option('date_format','Y-m-d'); @endphp

        <div class="card clearfix">

            <span class="panel-title d-none">{{ _lang('Purchase Order') }}</span>

            <div class="card-body">

                <div id="invoice-view">
                    <table class="classic-table">
                        <tbody>
                            <tr>
                                <td>
                                    <img src="{{asset('/public/uploads/contacts')}}/{{$purchase->user->client->contact_image}}" class="mh-80">
                                </td>
                                <td>
                                    <img src="{{ get_company_logo() }}" class="mh-80">
                                </td>
                            </tr>

                            <tr class="information">
                                <td class="pt-4">
                                    <h5><b>Customer Details</b></h5>
                                    <b>{{ _lang('Name') }}</b> : {{ $purchase->user->client->company_name}}<br>
                                    <b>{{ _lang('Email') }}</b> : {{ $purchase->user->client->contact_email }}<br>
                                    <b>Delivery Address</b> : {{ $purchase->user->client->delivery_address }}<br>
                                    <b>Director Name</b> : {{ $purchase->user->client->director_name }}<br>
                                    <b>Director Contact</b> : {{ $purchase->user->client->director_contact }}<br>
                                </td>
                                <td class="auto-column pt-4">
                                    <h5><b>{{ _lang('Purchase Order') }}</b></h5>
                                    <b>{{ _lang('Order ID') }} #:</b> {{$purchase->order_no}}{{ $purchase->id }}<br>
                                    <b>{{ _lang('Order Date') }}:</b> {{ $purchase->order_date }}<br>
                                    <b>Order type:</b> {{ config('constants.blinds.'.$purchase->order_product_type) }}<br>

                                    <b>{{ _lang('Order Status') }}:</b>

                                    @if($purchase->order_status == 0)
                                    <span class="badge badge-info">{{ _lang('Draft') }}</span><br>
                                    @elseif($purchase->order_status == 1)
                                    <span class="badge badge-info">{{ _lang('Ordered') }}</span><br>
                                    @elseif($purchase->order_status == 2)
                                    <span class="badge badge-danger">{{ _lang('Pending') }}</span><br>
                                    @elseif($purchase->order_status == 3)
                                    <span class="badge badge-success">{{ _lang('Completed') }}</span><br>
                                    @elseif($purchase->order_status == 4)
                                    <span class="badge badge-danger">{{ _lang('Canceled') }}</span><br>
                                    @endif

                                    <b>{{ _lang('Payment') }}:</b>

                                    @if($purchase->payment_status == 0)
                                    <span class="badge badge-danger">{{ _lang('Due') }}</span>
                                    @else
                                    <span class="badge badge-success">{{ _lang('Paid') }}</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!--End Invoice Information-->
                    <hr>
                    <div style="margin-top: 50px;">
                        <div class="row">
                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")

                           <div class="col-md-12">
                               @if( $purchase->order_status == '0')
                                   <p class="alert alert-warning">Please review and confirm order by changing status from draft.</p>
                               @endif
                           </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Change Order Status</label>
                                    <select class="form-control select2" name="order_status" id="order_status">
                                        <option value="1" {{ $purchase->order_status == '0' ? 'selected' : '' }}>
                                            Draft</option>
                                        <option value="1" {{ $purchase->order_status == '1' ? 'selected' : '' }}>
                                            {{ _lang('Ordered') }}</option>
                                        <option value="2" {{ $purchase->order_status == '2' ? 'selected' : '' }}>
                                            {{ _lang('Pending') }}</option>
                                        <option value="3" {{ $purchase->order_status == '3' ? 'selected' : '' }}>
                                            {{ _lang('Completed') }}</option>
                                        <option value="4" {{ $purchase->order_status == '4' ? 'selected' : '' }}>
                                            {{ _lang('Canceled') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Change Payment Status</label>
                                    <select class="form-control select2" name="payment_status" id="payment_status">
                                        <option value="0" {{ $purchase->payment_status == '0' ? 'selected' : '' }}>
                                            {{ _lang('Due') }}</option>
                                        <option value="1" {{ $purchase->payment_status == '1' ? 'selected' : '' }}>
                                            {{ _lang('Paid') }}</option>
                                    </select>
                                </div>
                            </div>
                                <div class="col-md-4">
                                    <p style="margin-top: 30px;"><a href="/invoices/{{$purchase->invoice_id}}/edit/" target="_blank" class="btn btn-warning btn-block">Xero invoice</a></p>
                                </div>
                            @endif

{{--                            <div class="col-md-4">--}}
{{--                                <div class="form-group" style="margin-top: 30px;">--}}
{{--                                    <a style="font-size: 16px;padding: 6px 30px;" class="btn btn-warning btn-sm" href="{{ route('Blinds.AddMoreRoller',['orderId'=>$purchase->id]) }}"><i--}}
{{--                                                class="ti-plus"></i> Add More Blinds</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                        </div>
                    </div>

                    @php $currency = currency(); @endphp

<a style="display:block;max-width:250px;" class="btn btn-info" href="{{ route('client.view_invoice', encrypt($purchase->invoice->id)) }}" target="_blank">Invoice Details</a>
               


               
                    @if($hasRollerBlinds)


                    
                        <ul class="nav nav-tabs" id="invoiceNav">
                            <li><a class="active" data-toggle="tab" href="#invDetails">Order Details</a></li>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <li><a data-toggle="tab" href="#makeSize">Make Size</a></li>
                                <li><a data-toggle="tab" href="#pipeSize">Pipe Size</a></li>
                                <li><a data-toggle="tab" href="#label">Label</a></li>
                            @endif
                        </ul>

                        <div class="tab-content">
                            <div id="invDetails" class="tab-pane fade in active show">
                                <!--Invoice Product-->
                                <div style="margin-top: 50px;margin-bottom: 50px;">
                                    <table style="width: 100%" class="table table-responsive mt-2" id="invoice-item-details-table">
                                        <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>MINIMUMM</th>
                                            <th>ACTUAL M</th>
                                            <th>ACTUAL SQM</th>
                                            <th>FABRIC UNIT</th>
                                            <th>FABRIC PRICE</th>
                                            <th>PELMET UNIT</th>
                                            <th>PELMET PRICE</th>
                                            <th>TUBE UNIT</th>
                                            <th>TUBE PRICE</th>
                                            <th>BRACKET PRICE</th>
                                            <th>CHAIN OR MOTOR </th>
                                            <th>SYSTEM PRICE</th>
                                            <th>HD GEAR PRICE</th>
                                            <th>BOTTOM BAR UNIT PRICE</th>
                                            <th>BOTTOM BAR PRICE</th>
                                            <th>TOTAL PRICE</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($purchase->purchase_items as $item)
                                            @if($item->blinds_type != config('constants.blindsId.RollerBlinds'))
                                                @continue
                                            @endif
                                            @php
                                                $attributes =  json_decode($item->attributes,true);
                                            @endphp
                                            <tr id="product-{{ $item->product_id }}">
                                                <td></td>
                                                <td class="text-center">{{$attributes['minimum_m']}}</td>
                                                <td class="text-center">{{$attributes['actual_m']}}</td>
                                                <td class="text-center">{{$attributes['actual_sqm']}}</td>
                                                <td class="text-center">{{decimalPlace($attributes['fabric_unit'],$currency)}}</td>
                                                <td class="text-center">{{decimalPlace($attributes['fabric_price'],$currency)}}</td>
                                                <td class="text-center">{{decimalPlace($attributes['plemet_unit_price'],$currency)}}</td>
                                                <td class="text-center">{{decimalPlace($attributes['plemet_price'],$currency)}}</td>
                                                <td class="text-center">{{decimalPlace($attributes['tube_unit_price'],$currency)}}</td>
                                                <td class="text-center">{{decimalPlace($attributes['tube_price'],$currency)}}</td>
                                                <td class="text-center">{{decimalPlace($attributes['bracket_price'],$currency)}}</td>
                                                <td class="text-center">{{decimalPlace($attributes['chain_or_motor_price'],$currency)}}</td>
                                                <td class="text-center">{{decimalPlace($attributes['system_type_price'],$currency)}}</td>
                                                <td class="text-center">{{decimalPlace($attributes['hd_gear_price'],$currency)}}</td>
                                                <td class="text-center">{{decimalPlace($attributes['bottom_bar_unit_price'],$currency)}}</td>
                                                <td class="text-center">{{decimalPlace($attributes['bottom_bar_price'],$currency)}}</td>
                                                <td class="text-center">{{decimalPlace($item->sub_total,$currency)}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!--End Invoice Product-->
                            </div>

                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <div id="makeSize" class="tab-pane fade">
                                    <div class="table-responsive" style="margin-top: 50px;margin-bottom: 50px;">
                                        <table style="width: 100%" class="table mt-2" id="invoice-item-make-size">
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>ROLLER TYPE</th>
                                                <th>SYSTEM TYPE</th>
                                                <th>TUBE</th>
                                                <th>WIDTH</th>
                                                <th>HEIGHT</th>
                                                <th>CONTROL SIDE</th>
                                                <th>ROLLING TYPE</th>
                                                <th>FABRIC COMPANY</th>
                                                <th>FABRIC TYPE</th>
                                                <th>FABRIC NAME</th>
                                                <th>FABRIC COLOR</th>
                                                <th>BOTTOM BAR</th>
                                                <th>BOTTOM COLOUR</th>
                                                <th>SYSTEM COLOUR</th>
                                                <th>CHAIN&MOTOR</th>
                                                <th>CHAIN DROP</th>
                                                <th>HD GEAR</th>
                                                <th>PELMET</th>
                                                <th>LINK BRACKET</th>
                                                <th>M2</th>
                                                <th>LOCATION</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($purchase->purchase_items as $item)
                                                @if($item->blinds_type != config('constants.blindsId.RollerBlinds'))
                                                    @continue
                                                @endif
                                                @php
                                                    $attributes =  json_decode($item->attributes,true);
                                                @endphp
                                                <tr id="product-{{ $item->product_id }}">
                                                    <td></td>
                                                    <td class="text-center">{{$item->item_type}}</td>
                                                    <td class="text-center">{{$attributes['system_type']}}</td>
                                                    <td class="text-center">{{$attributes['tube']}}</td>
                                                    <td class="text-center">{{$attributes['width']}}</td>
                                                    <td class="text-center">{{$attributes['height']}}</td>
                                                    <td class="text-center">{{$attributes['control_side']}}</td>
                                                    <td class="text-center">{{$attributes['rolling_type']}}</td>
                                                    <td class="text-center">{{$attributes['fabric_company']}}</td>
                                                    <td class="text-center">{{$attributes['fabric_type']}}</td>
                                                    <td class="text-center">{{$attributes['fabric_name']}}</td>
                                                    <td class="text-center">{{$attributes['fabric_color']}}</td>
                                                    <td class="text-center">{{$attributes['bottom_bar']}}</td>
                                                    <td class="text-center">{{$attributes['bottom_colour']}}</td>
                                                    <td class="text-center">{{$attributes['system_colour']}}</td>
                                                    <td class="text-center">{{$attributes['chain_motor']}}</td>
                                                    <td class="text-center">{{$attributes['chain_drop']}}</td>
                                                    <td class="text-center">{{$attributes['hd_gear']}}</td>
                                                    <td class="text-center">{{$attributes['pelmet']}}</td>
                                                    <td class="text-center">{{$attributes['link_bracket']}}</td>
                                                    <td class="text-center">{{$attributes['m2']}}</td>
                                                    <td>{{$item->location}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="pipeSize" class="tab-pane fade">
                                    <div class="table-responsive" style="margin-top: 50px;margin-bottom: 50px;">
                                        <table style="width: 100%" class="table mt-2" id="invoice-item-pipe-size">
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>ROLLER TYPE</th>
                                                <th>TUBE</th>
                                                <th>WIDTH</th>
                                                <th>Pelmet Color</th>
                                                <th>Pelmet size</th>
                                                <th>BOTTOM BAR</th>
                                                <th>BOTTOM COLOUR</th>
                                                <th>PELMET</th>
                                                <th>M2</th>
                                                <th>LOCATION</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($purchase->purchase_items as $item)
                                                @if($item->blinds_type != config('constants.blindsId.RollerBlinds'))
                                                    @continue
                                                @endif
                                                @php
                                                    $attributes =  json_decode($item->attributes,true);
                                                @endphp
                                                <tr id="product-{{ $item->product_id }}">
                                                    <td></td>
                                                    <td class="text-center">{{$item->item_type}}</td>
                                                    <td class="text-center">{{$attributes['tube']}}</td>
                                                    <td class="text-center">{{$attributes['width']}}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-center">{{$attributes['bottom_bar']}}</td>
                                                    <td class="text-center">{{$attributes['bottom_colour']}}</td>
                                                    <td class="text-center">{{$attributes['pelmet']}}</td>
                                                    <td class="text-center">{{$attributes['m2']}}</td>
                                                    <td>gf</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="label" class="tab-pane fade">
                                    <div class="table-responsive" style="margin-top: 50px;margin-bottom: 50px;">
                                        <table style="width: 100%" class="table mt-2" id="invoice-item-label">
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>TYPE</th>
                                                <th>WIDTH</th>
                                                <th>DROP</th>
                                                <th>ROLLING TYPE</th>
                                                <th>LOCATION</th>
                                                <th>CUSTOMER NAME</th>
                                                <th>ORDER NUMBER</th>
                                                <th>TOTAL QTY</th>
                                                <th>TUBE</th>
                                                <th>BOTTOM</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($purchase->purchase_items as $item)
                                                @if($item->blinds_type != config('constants.blindsId.RollerBlinds'))
                                                    @continue
                                                @endif
                                                @php
                                                    $attributes =  json_decode($item->attributes,true);
                                                @endphp
                                                <tr id="product-{{ $item->product_id }}">
                                                    <td></td>
                                                    <td class="text-center">{{$item->item_type}}</td>
                                                    <td class="text-center">{{$attributes['width']}}</td>
                                                    <td class="text-center">{{$attributes['height']}}</td>
                                                    <td class="text-center">{{$attributes['rolling_type']}}</td>
                                                    <td></td>
                                                    <td>{{$purchase->user->client->company_name}}</td>
                                                    <td>{{$purchase->order_no}}{{$purchase->id}}</td>
                                                    <td></td>
                                                    <td class="text-center">{{$attributes['tube']}}</td>
                                                    <td class="text-center">{{$attributes['bottom_bar']}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif


                    @if($hasVerticalBlinds)
                        <ul class="nav nav-tabs" id="invoiceNav">
                            <li><a class="active" data-toggle="tab" href="#verticalInvDetails">Order Details</a></li>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <li><a data-toggle="tab" href="#verticalMakeSize">Price Calculator</a></li>
                            @endif
                        </ul>

                        <div class="tab-content">
                            <div id="verticalInvDetails" class="tab-pane fade in active show">
                                <!--Invoice Product-->
                                <div style="margin-top: 50px;margin-bottom: 50px;">
                                    <table style="width: 100%" class="table table-responsive mt-2" id="vertical-invoice-item-details-table">
                                        <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>PRODUCT TYPE</th>
                                            <th>HOOK TYPE</th>
                                            <th>Width</th>
                                            <th>Height</th>
                                            <th>CONTROL/STACK</th>
                                            <th>FIT TYPE</th>
                                            <th>Fabric Type</th>
                                            <th>Fabric Color</th>
                                            <th>BOTTOM & TOP</th>
                                            <th>CORD /WAND HEIGHT</th>
                                            <th>WAND TYPE</th>
                                            <th>TRACK COLOUR</th>
                                            <th>REMOTE</th>
                                            <th>BRACKETS</th>
                                            <th>M2</th>
                                            <th>SLAT QTY</th>
                                            <th>BRACKET QTY</th>
                                            <th>Location</th>
                                            <th>TOTAL PRICE</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($purchase->purchase_items as $item)
                                            @if($item->blinds_type != config('constants.blindsId.VerticalBlinds'))
                                                @continue
                                            @endif
                                            @php
                                                $attributes =  json_decode($item->attributes,true);
                                            @endphp
                                            <tr id="product-{{ $item->product_id }}-vertical">
                                                <td></td>
                                                <td class="text-center">{{$attributes['v_product_type']}}</td>
                                                <td class="text-center">{{$attributes['v_hook_type']}}</td>
                                                <td class="text-center">{{$attributes['v_width']}}</td>
                                                <td class="text-center">{{$attributes['v_height']}}</td>
                                                <td class="text-center">{{$attributes['v_control_stack']}}</td>
                                                <td class="text-center">{{$attributes['v_fit_type']}}</td>
                                                <td class="text-center">{{$attributes['v_fabric_type']}}</td>
                                                <td class="text-center">{{$attributes['v_fabric_color']}}</td>
                                                <td class="text-center">{{$attributes['v_top_and_bottom']}}</td>
                                                <td class="text-center">{{$attributes['cord_wand_v_height']}}</td>
                                                <td class="text-center">{{$attributes['v_wand_type']}}</td>
                                                <td class="text-center">{{$attributes['v_track_colour']}}</td>
                                                <td class="text-center">{{$attributes['v_remote']}}</td>
                                                <td class="text-center">{{$attributes['v_brackets']}}</td>
                                                <td class="text-center">{{$attributes['v_m2']}}</td>
                                                <td class="text-center">{{$attributes['slat_qty']}}</td>
                                                <td class="text-center">{{$attributes['bracket_qty']}}</td>
                                                <td class="text-center">{{$attributes['v_location']}}</td>
                                                <td class="text-center">{{decimalPlace($item->sub_total,$currency)}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!--End Invoice Product-->
                            </div>

                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <div id="verticalMakeSize" class="tab-pane fade">
                                    <div class="table-responsive" style="margin-top: 50px;margin-bottom: 50px;">
                                        <table style="width: 100%" class="table mt-2" id="vertical-invoice-item-make-size">
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>Control PRICE</th>
                                                <th>TOP & BOTTOM UNIT</th>
                                                <th>TOP & BOTTOM PRICE</th>
                                                <th>REMOTE</th>
                                                <th>Fabric Unit</th>
                                                <th>Fabric Price</th>
                                                <th>Bracket Unit</th>
                                                <th>Bracket price</th>
                                                <th>Total</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($purchase->purchase_items as $item)
                                                @if($item->blinds_type != config('constants.blindsId.VerticalBlinds'))
                                                    @continue
                                                @endif
                                                @php
                                                    $attributes =  json_decode($item->attributes,true);
                                                @endphp
                                                <tr id="product-{{ $item->product_id }}">
                                                    <td></td>
                                                    <td class="text-center">{{decimalPlace($attributes['control_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['bottom_unit_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['bottom_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['remote_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['fabric_unit_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['fabric_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['bracket_unit_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['bracket_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($item->sub_total,$currency)}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>

                    @endif


                    @if($hasPanelBlinds)
                    </br>
<button id="print-button" onclick="pannelbracket_printTable();" class="btn btn-primary">🖶 Print Bracket Label</button>
                    <button id="print-button" onclick="pannel_printTable();" class="btn btn-primary">🖶 Print Panel Label</button>

  <script>
  function pannelbracket_printTable() {
 // Create a new window for printing
   // Get the table content
  var tableContent = document.getElementById("pannel-invoice-item-details-table-1").outerHTML;

  // Create a new window for printing
  var printWindow = window.open("", "Print Label", "width=800,height=600"); // Set a small window size

  // Set print document styles
  printWindow.document.write(`
    <html><head>
      <style>
        @page { size: 250mm 20mm; /* Set label size (width x height) */
                margin: 0; /* Remove margins */
                padding: 0; /* Remove padding */ }
        table { width: 100%; /* Ensure table fits label width */
                table-layout: fixed; /* Prevent wrapping across pages */
                font-size: 8px; /* Adjust font size for smaller data */ }
        th, td { padding: 2px; border: 1px solid #ddd; }
      </style>
    </head><body>`);

  // Write the table content with container
  printWindow.document.write(`<div style="width: 250mm;">${tableContent}</div>`); // Apply container width

  // Close document, focus, and print
  printWindow.document.write("</body></html>");
  printWindow.document.close();
  printWindow.focus();
  printWindow.print();
  // Close the new window after printing

}
function pannel_printTable() {
 // Create a new window for printing
   // Get the table content
  var tableContent = document.getElementById("pannel-invoice-item-details-table").outerHTML;

  // Create a new window for printing
  var printWindow = window.open("", "Print Label", "width=800,height=600"); // Set a small window size

  // Set print document styles
  printWindow.document.write(`
    <html><head>
      <style>
        @page { size: 100mm 75mm; /* Set label size (width x height) */
                margin: 0; /* Remove margins */
                padding: 0; /* Remove padding */ }
        table { width: 99%; /* Ensure table fits label width */
                table-layout: fixed; /* Prevent wrapping across pages */
                font-size: 18px; /* Adjust font size for smaller data */ }
        th, td { padding: 2px; border: 1px solid #ddd; }
      </style>
    </head><body>`);

  // Write the table content with container
  printWindow.document.write(`<div style="width: 100mm;">${tableContent}</div>`); // Apply container width

  // Close document, focus, and print
  printWindow.document.write("</body></html>");
  printWindow.document.close();
  printWindow.focus();
  printWindow.print();
  // Close the new window after printing

}



</script>
  <div class="tab-content" hidden>
    <div id="verticalInvDetails" class="tab-pane fade in active show">
    
      <div style="margin-top: 50px;margin-bottom: 50px;">
        <table  class="table table-responsive mt-2" id="pannel-invoice-item-details-table-1">
          <thead>
            <tr>
             
             <th>RAIL TYPE</th>
                                            <th>WIDTH</th>
                                            <th>HEIGHT</th>
                                            <th>PANEL QTY</th>
                                            <th>FABRIC WIDTH</th>
                                            <th>FABRIC HEIGHT</th>
                                            <th>STACK SIDE</th>
                                            <th>FABRIC COMPANY</th>
                                            <th>FABRIC TYPE</th>
                                            <th>FABRIC TYPE</th>
                                            <th>FABRIC COLOR</th>
                                            <th>TRACK COLOUR</th>
                                            <th>BOTTOM BAR</th>
                                            <th>BOTTOM COLOUR</th>
                                            <th>WAND HEIGHT</th>
                                            <th>FIT</th>
                                            <th>BRACKET</th>
            </tr>
          </thead>

          <tbody>
            @foreach($purchase->purchase_items as $item)
                                            @if($item->blinds_type != config('constants.blindsId.PanelBlinds'))
                                                @continue
                                            @endif
                                            @php
                                                $attributes =  json_decode($item->attributes,true);
                                            @endphp
             <tr id="product-{{ $item->product_id }}-panel">
               
             
         <td class="text-center">{{$attributes['rail_type']}}</td>
                   <td class="text-center">{{$attributes['width']}}</td>
                                                <td class="text-center">{{$attributes['height']}}</td>
                                                <td class="text-center">{{$attributes['panel_qty']}}</td>
                                                <td class="text-center">{{$attributes['fabric_width']}}</td>
                                                <td class="text-center">{{$attributes['fabric_height']}}</td>
                                                <td class="text-center">{{$attributes['stack_side']}}</td>
                                                <td class="text-center">{{$attributes['fabric_company']}}</td>
                                                <td class="text-center">{{$attributes['fabric_type']}}</td>
                                                <td class="text-center">{{$attributes['fabric_type2']}}</td>
                                                <td class="text-center">{{$attributes['fabric_color']}}</td>
                                                <td class="text-center">{{$attributes['track_colour']}}</td>
                                                <td class="text-center">{{$attributes['bottom_bar']}}</td>
                                                <td class="text-center">{{$attributes['bottom_colour']}}</td>
                                                <td class="text-center">{{$attributes['wand_height']}}</td>
                                                <td class="text-center">{{$attributes['fit']}}</td>
                                                <td class="text-center">{{$attributes['brackets']}}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>


  </div>
<div class="tab-content" hidden>
<table id="pannel-invoice-item-details-table" border="1" height="100%" >
<tr>
<td colspan="4"><center><h2>{{ $purchase->user->client->company_name}}</h2></center></td>
</tr>
  <tr>
    <td><strong>Order No</strong></td>
    <td style="word-wrap: break-word;">{{$purchase->order_no}}{{ $purchase->id }}</td>
    <td><strong>Location</strong></td>
	 
   <td>{{ json_decode($purchase->purchase_items)[0]->location }}</td>
  </tr>
  <tr>
    <td><strong>Width</strong></td>
    <td><strong>Drop</strong></td>
    <td><strong>Panel Qty</strong></td>
	<td><strong>Fit Type</strong></td>
  </tr>
  <tr>
    <td>{{ json_decode(json_decode($purchase->purchase_items)[0]->attributes)->width }}</td>
    <td>{{ json_decode(json_decode($purchase->purchase_items)[0]->attributes)->height }}</td>
    <td>{{ json_decode(json_decode($purchase->purchase_items)[0]->attributes)->panel_qty }}</td>
    <td>{{ json_decode(json_decode($purchase->purchase_items)[0]->attributes)->bottom_bar }}</td>
  </tr>
  
</table>
</div>
                        <ul class="nav nav-tabs" id="invoiceNav">
                            <li><a class="active" data-toggle="tab" href="#panelInvDetails">Order Details</a></li>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <li><a data-toggle="tab" href="#panelInvPrice">Price Calculator</a></li>
                            @endif
                        </ul>

                        <div class="tab-content">
                            <div id="panelInvDetails" class="tab-pane fade in active show">
                                <!--Invoice Product-->
                                <div style="margin-top: 50px;margin-bottom: 50px;">
                                    <table style="width: 100%" class="table table-responsive mt-2" id="vertical-invoice-item-details-table">
                                        <thead>
                                        <tr>
                                            <th>NO.</th>
                                            <th>LOCATION</th>
                                            <th>RAIL TYPE</th>
                                            <th>WIDTH</th>
                                            <th>HEIGHT</th>
                                            <th>PANEL QTY</th>
                                            <th>FABRIC WIDTH</th>
                                            <th>FABRIC HEIGHT</th>
                                            <th>STACK SIDE</th>
                                            <th>FABRIC COMPANY</th>
                                            <th>FABRIC TYPE</th>
                                            <th>FABRIC TYPE</th>
                                            <th>FABRIC COLOR</th>
                                            <th>TRACK COLOUR</th>
                                            <th>BOTTOM BAR</th>
                                            <th>BOTTOM COLOUR</th>
                                            <th>WAND HEIGHT</th>
                                            <th>FIT</th>
                                            <th>BRACKET</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($purchase->purchase_items as $item)
                                            @if($item->blinds_type != config('constants.blindsId.PanelBlinds'))
                                                @continue
                                            @endif
                                            @php
                                                $attributes =  json_decode($item->attributes,true);
                                            @endphp
                                            <tr id="product-{{ $item->product_id }}-panel">
                                                <td></td>
                                                <td class="text-center">{{$attributes['location']}}</td>
                                                <td class="text-center">{{$attributes['rail_type']}}</td>
                                                <td class="text-center">{{$attributes['width']}}</td>
                                                <td class="text-center">{{$attributes['height']}}</td>
                                                <td class="text-center">{{$attributes['panel_qty']}}</td>
                                                <td class="text-center">{{$attributes['fabric_width']}}</td>
                                                <td class="text-center">{{$attributes['fabric_height']}}</td>
                                                <td class="text-center">{{$attributes['stack_side']}}</td>
                                                <td class="text-center">{{$attributes['fabric_company']}}</td>
                                                <td class="text-center">{{$attributes['fabric_type']}}</td>
                                                <td class="text-center">{{$attributes['fabric_type2']}}</td>
                                                <td class="text-center">{{$attributes['fabric_color']}}</td>
                                                <td class="text-center">{{$attributes['track_colour']}}</td>
                                                <td class="text-center">{{$attributes['bottom_bar']}}</td>
                                                <td class="text-center">{{$attributes['bottom_colour']}}</td>
                                                <td class="text-center">{{$attributes['wand_height']}}</td>
                                                <td class="text-center">{{$attributes['fit']}}</td>
                                                <td class="text-center">{{$attributes['brackets']}}</td>
                                                <td class="text-center">{{decimalPlace($item->sub_total,$currency)}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!--End Invoice Product-->
                            </div>

                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <div id="panelInvPrice" class="tab-pane fade">
                                    <div class="table-responsive" style="margin-top: 50px;margin-bottom: 50px;">
                                        <table style="width: 100%" class="table mt-2" id="vertical-invoice-item-make-size">
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>FABRIC WIDTH</th>
                                                <th>FABRIC HEIGHT</th>
                                                <th>BRACKET QTY</th>
                                                <th>M2</th>
                                                <th>MINIMUM M</th>
                                                <th>RAIL PRICE</th>
                                                <th>FABRIC UNIT</th>
                                                <th>FABRIC PRICE</th>
                                                <th>BOTTOM UNIT</th>
                                                <th>BOTTOM PRICE</th>
                                                <th>WAND PRICE</th>
                                                <th>BRACKET UNITS</th>
                                                <th>BRACKET PRICE</th>
                                                <th>CONTROL  %</th>
                                                <th>CONTROL PRICE</th>
                                                <th>SUB TOTAL</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($purchase->purchase_items as $item)
                                                @if($item->blinds_type != config('constants.blindsId.PanelBlinds'))
                                                    @continue
                                                @endif
                                                @php
                                                    $attributes =  json_decode($item->attributes,true);
                                                @endphp
                                                <tr id="product-{{ $item->product_id }}">
                                                    <td></td>
                                                    <td class="text-center">{{$attributes['fabric_width']}}</td>
                                                    <td class="text-center">{{$attributes['fabric_height']}}</td>
                                                    <td class="text-center">{{$attributes['bracket_qty']}}</td>
                                                    <td class="text-center">{{$attributes['m2']}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['minimum_m'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['rail_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['fabric_unit_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['fabric_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['bottom_unit_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['bottom_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['wand_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['bracket_unit_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['bracket_price'],$currency)}}</td>
                                                    <td class="text-center">{{$attributes['control']}}%</td>
                                                    <td class="text-center">{{decimalPlace($attributes['control_unit_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['total_price'],$currency)}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>

                    @endif

                    @if($hasCombiandVenetian)
                        <ul class="nav nav-tabs" id="invoiceNav">
                            <li><a class="active" data-toggle="tab" href="#combiInvDetails">Order Details</a></li>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <li><a data-toggle="tab" href="#combiInvPrice">Price Calculator</a></li>
                            @endif
                        </ul>

                        <div class="tab-content">
                            <div id="combiInvDetails" class="tab-pane fade in active show">
                                <!--Invoice Product-->
                                <div style="margin-top: 50px;margin-bottom: 50px;">
                                    <table style="width: 100%" class="table table-responsive mt-2" id="vertical-invoice-item-details-table">
                                        <thead>
                                        <tr>
                                            <th>NO.</th>
                                            <th>LOCATION</th>
                                            <th>PRODUCT TYPE</th>
                                            <th>WIDTH</th>
                                            <th>HEIGHT</th>
                                            <th>CONTROL SIDE</th>
                                            <th>CORD HEIGHT</th>
                                            <th>FIT TYPE</th>
                                            <th>FABRIC / COLOUR DETAIL</th>
                                            <th>SLAT SIZE (VENETIAN ONLY)</th>
                                            <th>SUB TOTAL</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($purchase->purchase_items as $item)
                                            @if($item->blinds_type != config('constants.blindsId.CombiandVenetian'))
                                                @continue
                                            @endif
                                            @php
                                                $attributes =  json_decode($item->attributes,true);
                                            @endphp
                                            <tr id="product-{{ $item->product_id }}-panel">
                                                <td></td>
                                                <td class="text-center">{{$attributes['location']}}</td>
                                                <td class="text-center">{{$attributes['product_type']}}</td>
                                                <td class="text-center">{{$attributes['width']}}</td>
                                                <td class="text-center">{{$attributes['height']}}</td>
                                                <td class="text-center">{{$attributes['control_side']}}</td>
                                                <td class="text-center">{{$attributes['cord_height']}}</td>
                                                <td class="text-center">{{$attributes['fit_type']}}</td>
                                                <td class="text-center">{{$attributes['fabric_colour_detail']}}</td>
                                                <td class="text-center">{{$attributes['slat_size']}}</td>
                                                <td class="text-center">{{decimalPlace($item->sub_total,$currency)}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!--End Invoice Product-->
                            </div>

                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <div id="combiInvPrice" class="tab-pane fade">
                                    <div class="table-responsive" style="margin-top: 50px;margin-bottom: 50px;">
                                        <table style="width: 100%" class="table mt-2" id="vertical-invoice-item-make-size">
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>M2</th>
                                                <th>UNIT PRICE</th>
                                                <th>SUB TOTAL</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($purchase->purchase_items as $item)
                                                @if($item->blinds_type != config('constants.blindsId.CombiandVenetian'))
                                                    @continue
                                                @endif
                                                @php
                                                    $attributes =  json_decode($item->attributes,true);
                                                @endphp
                                                <tr id="product-{{ $item->product_id }}">
                                                    <td></td>
                                                    <td class="text-center">{{$attributes['m2']}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['product_unit_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['total_price'],$currency)}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>

                    @endif

                    @if($hasCurvers)
                        <ul class="nav nav-tabs" id="invoiceNav">
                            <li><a class="active" data-toggle="tab" href="#CurversInvDetails">Order Details</a></li>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <li><a data-toggle="tab" href="#CurversInvPrice">Price Calculator</a></li>
                            @endif
                        </ul>

                        <div class="tab-content">
                            <div id="CurversInvDetails" class="tab-pane fade in active show">
                                <!--Invoice Product-->
                                <div style="margin-top: 50px;margin-bottom: 50px;">
                                    <table style="width: 100%" class="table table-responsive mt-2" id="curvers-invoice-item-details-table">
                                        <thead>
                                        <tr>
                                            <th>NO.</th>
                                            <th>LOCATION</th>
                                            <th>SIZE</th>
                                            <th>WIDTH</th>
                                            <th>HEIGHT</th>
                                            <th>STACK SIDE</th>
                                            <th>CURVERS PATTERN</th>
                                            <th>CURVERS COLOUR</th>
                                            <th>TRACK COLOUR</th>
                                            <th>WAND COLOUR</th>
                                            <th>WAND HEIGHT</th>
                                            <th>FIT TYPE</th>
                                            <th>BRACKET COLOUR</th>
                                            <th>MOTOR TRACK</th>
                                            <th>REMOTE CHANNEL</th>
                                            <th>MOTOR BRACKET COLOUR</th>
                                            <th>SUB TOTAL</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($purchase->purchase_items as $item)
                                            @if($item->blinds_type != config('constants.blindsId.Curvers'))
                                                @continue
                                            @endif
                                            @php
                                                $attributes =  json_decode($item->attributes,true);
                                            @endphp
                                            <tr id="product-{{ $item->product_id }}-panel">
                                                <td></td>
                                                <td class="text-center">{{$attributes['location']}}</td>
                                                <td class="text-center">{{$attributes['sizes']}}</td>
                                                <td class="text-center">{{$attributes['width']}}</td>
                                                <td class="text-center">{{$attributes['height']}}</td>
                                                <td class="text-center">{{$attributes['stack_side']}}</td>
                                                <td class="text-center">{{$attributes['curvers_pattern']}}</td>
                                                <td class="text-center">{{$attributes['curvers_colour']}}</td>
                                                <td class="text-center">{{$attributes['track_colour']}}</td>
                                                <td class="text-center">{{$attributes['wand_colour']}}</td>
                                                <td class="text-center">{{$attributes['wand_height']}}</td>
                                                <td class="text-center">{{$attributes['fit_type']}}</td>
                                                <td class="text-center">{{$attributes['bracket_colour']}}</td>
                                                <td class="text-center">{{$attributes['motor_track']}}</td>
                                                <td class="text-center">{{$attributes['remote_channel']}}</td>
                                                <td class="text-center">{{$attributes['motor_bracket_colour']}}</td>
                                                <td class="text-center">{{decimalPlace($item->sub_total,$currency)}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!--End Invoice Product-->
                            </div>

                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <div id="CurversInvPrice" class="tab-pane fade">
                                    <div class="table-responsive" style="margin-top: 50px;margin-bottom: 50px;">
                                        <table style="width: 100%" class="table mt-2" id="curvers-invoice-item-make-size">
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>M2</th>
                                                <th>SLAT QTY</th>
                                                <th>INSIDE BRACKET QTY</th>
                                                <th>OUTSIDE BRACKET QTY</th>
                                                <th>FABRIC UNIT </th>
                                                <th>BRACKET UNIT</th>
                                                <th>BRACKET PRICE</th>
                                                <th>SUB TOTAL</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($purchase->purchase_items as $item)
                                                @if($item->blinds_type != config('constants.blindsId.Curvers'))
                                                    @continue
                                                @endif
                                                @php
                                                    $attributes =  json_decode($item->attributes,true);
                                                @endphp
                                                <tr id="product-{{ $item->product_id }}">
                                                    <td></td>
                                                    <td class="text-center">{{$attributes['m2']}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['salt_qty'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['inside_bracket_qty'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['inside_bracket_qty'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['fabric_unit_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['bracket_unit_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['bracket_price'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['total_price'],$currency)}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>

                    @endif

                    @if($hasSecurityDoor)
                        <ul class="nav nav-tabs" id="invoiceNav">
                            <li><a class="active" data-toggle="tab" href="#SecurityDoorInvDetails">Order Details</a></li>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <li><a data-toggle="tab" href="#SecurityDoorInvPrice">Price Calculator</a></li>
                            @endif
                        </ul>

                        <div class="tab-content">
                            <div id="SecurityDoorInvDetails" class="tab-pane fade in active show">
                                <!--Invoice Product-->
                                <div style="margin-top: 50px;margin-bottom: 50px;">
                                    <table style="width: 100%" class="table table-responsive mt-2" id="securityDoor-invoice-item-details-table">
                                        <thead>
                                        <tr>
                                            <th>NO.</th>
                                            <th>Door Type</th>
                                            <th>Width</th>
                                            <th>Height</th>
                                            <th>Lock Position</th>
                                            <th>Lock height</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($purchase->purchase_items as $item)
                                            @if($item->blinds_type != config('constants.blindsId.SecurityDoor'))
                                                @continue
                                            @endif
                                            @php
                                                $attributes =  json_decode($item->attributes,true);
                                            @endphp
                                            <tr id="product-{{ $item->product_id }}-panel">
                                                <td></td>
                                                <td class="text-center">{{$attributes['door_type']}}</td>
                                                <td class="text-center">{{$attributes['width']}}</td>
                                                <td class="text-center">{{$attributes['height']}}</td>
                                                <td class="text-center">{{$attributes['lock_position']}}</td>
                                                <td class="text-center">{{$attributes['lock_height']}}</td>
                                                <td class="text-center">{{decimalPlace($item->sub_total,$currency)}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!--End Invoice Product-->
                            </div>

                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <div id="SecurityDoorInvPrice" class="tab-pane fade">
                                    <div class="table-responsive" style="margin-top: 50px;margin-bottom: 50px;">
                                        <table style="width: 100%" class="table mt-2" id="securityDoor-invoice-item-make-size">
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>DOOR PRICE</th>
                                                <th>ACCESSORY 1</th>
                                                <th>ACCESSORY 2</th>
                                                <th>ACCESSORY 3</th>
                                                <th>ACCESSORY 4</th>
                                                <th>LOCK PRICE</th>
                                                <th>COLOUR PRICE</th>
                                                <th>SUB TOTAL</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($purchase->purchase_items as $item)
                                                @if($item->blinds_type != config('constants.blindsId.SecurityDoor'))
                                                    @continue
                                                @endif
                                                @php
                                                    $attributes =  json_decode($item->attributes,true);
                                                @endphp
                                                <tr id="product-{{ $item->product_id }}">
                                                    <td></td>
                                                    <td class="text-center">{{decimalPlace($attributes['doorPrice'],$currency)}}</td>
                                                    <td class="text-center">@if(!empty($attributes['DoorAccessory1Price'])){{decimalPlace($attributes['DoorAccessory1Price'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['DoorAccessory2Price'])){{decimalPlace($attributes['DoorAccessory2Price'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['DoorAccessory3Price'])){{decimalPlace($attributes['DoorAccessory3Price'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['DoorAccessory4Price'])){{decimalPlace($attributes['DoorAccessory4Price'],$currency)}}@endif</td>
                                                    <td class="text-center">{{decimalPlace($attributes['lockPrice'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['colorPrice'],$currency)}}</td>
                                                    <td class="text-center">{{decimalPlace($attributes['total_price'],$currency)}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>

                    @endif

                    @if($hasCurtain)
                        <ul class="nav nav-tabs" id="invoiceNav">
                            <li><a class="active" data-toggle="tab" href="#CurtainInvDetails">Order Details</a></li>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <li><a data-toggle="tab" href="#CurtainInvPrice">Price Calculator</a></li>
                            @endif
                        </ul>

                        <div class="tab-content">
                            <div id="CurtainInvDetails" class="tab-pane fade in active show">
                                <!--Invoice Product-->
                                <div style="margin-top: 50px;margin-bottom: 50px;">
                                    <table style="width: 100%" class="table table-responsive mt-2" id="Curtain-invoice-item-details-table">
                                        <thead>
                                        <tr>
                                            <th>NO.</th>
                                            <th>LOCATION</th>
                                            <th>CURTAINS TYPE</th>
                                            <th>WIDTH</th>
                                            <th>HEIGHT</th>
                                            <th>BOTTOM</th>
                                            <th>FABRIC TYPE</th>
                                            <th>FABRIC NAME</th>
                                            <th>FABRIC COLOUR</th>
                                            <th>TRACK TYPE</th>
                                            <th>BRACKETS</th>
                                            <th>STACK SIDE</th>
                                            <th>CONTROL TYPE</th>
                                            <th>MOTOR</th>
                                            <th>RENOTE</th>
                                            <th>TRACK COLOUR</th>
                                            <th>WAND HEIGHT</th>
                                            <th>CURVED TRACK</th>
                                            <th>BRACKETS QTY</th>
                                            <th>TOTAL PRICE</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($purchase->purchase_items as $item)
                                            @if($item->blinds_type != config('constants.blindsId.Curtain'))
                                                @continue
                                            @endif
                                            @php
                                                $attributes =  json_decode($item->attributes,true);
                                            @endphp
                                            <tr id="product-{{ $item->product_id }}-panel">
                                                <td></td>
                                                <td class="text-center">{{$attributes['location']}}</td>
                                                <td class="text-center">{{$attributes['curtain_type']}}</td>
                                                <td class="text-center">{{$attributes['width']}}</td>
                                                <td class="text-center">{{$attributes['height']}}</td>
                                                <td class="text-center">{{$attributes['bottom']}}</td>
                                                <td class="text-center">{{$attributes['fabric_type']}}</td>
                                                <td class="text-center">{{$attributes['fabric_name']}}</td>
                                                <td class="text-center">{{$attributes['fabric_color']}}</td>
                                                <td class="text-center">{{$attributes['track_type']}}</td>
                                                <td class="text-center">{{$attributes['brackets']}}</td>
                                                <td class="text-center">{{$attributes['stack_side']}}</td>
                                                <td class="text-center">{{$attributes['control_type']}}</td>
                                                <td class="text-center">{{$attributes['motor']}}</td>
                                                <td class="text-center">{{$attributes['remote']}}</td>
                                                <td class="text-center">{{$attributes['track_colour']}}</td>
                                                <td class="text-center">{{$attributes['wand_height']}}</td>
                                                <td class="text-center">{{$attributes['curved_track']}}</td>
                                                <td class="text-center">{{$attributes['bracketsQty']}}</td>
                                                <td class="text-center">{{decimalPlace($item->sub_total,$currency)}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!--End Invoice Product-->
                            </div>

                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <div id="CurtainInvPrice" class="tab-pane fade">
                                    <div class="table-responsive" style="margin-top: 50px;margin-bottom: 50px;">
                                        <table style="width: 100%" class="table mt-2" id="Curtain-invoice-item-make-size">
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>M2</th>
                                                <th>BRACKET QTY</th>
                                                <th>CONTROL UNIT</th>
                                                <th>CONTROL PRICE</th>
                                                <th>HEAD TYPE</th>
                                                <th>TRACK PRICE</th>
                                                <th>FABRIC UNIT</th>
                                                <th>FABRIC PRICE</th>
                                                <th>BRACKET UNIT</th>
                                                <th>BRACKET PRICE</th>
                                                <th>CURVED</th>
                                                <th>EXTRA</th>
                                                <th>BOTTOM</th>
                                                <th>MOTOR</th>
                                                <th>REMOTE</th>
                                                <th>SUB TOTAL</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($purchase->purchase_items as $item)
                                                @if($item->blinds_type != config('constants.blindsId.Curtain'))
                                                    @continue
                                                @endif
                                                @php
                                                    $attributes =  json_decode($item->attributes,true);
                                                @endphp
                                                <tr id="product-{{ $item->product_id }}">
                                                    <td></td>
                                                    <td class="text-center">{{$attributes['m2']}}</td>
                                                    <td class="text-center">{{$attributes['bracketsQty']}}</td>
                                                    <td class="text-center">@if(!empty($attributes['controlunitPrice'])){{decimalPlace($attributes['controlunitPrice'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['controlPrice'])){{decimalPlace($attributes['controlPrice'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['headType'])){{decimalPlace($attributes['headType'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['trackPrice'])){{decimalPlace($attributes['trackPrice'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['fabricUnitPrice'])){{decimalPlace($attributes['fabricUnitPrice'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['fabricPrice'])){{decimalPlace($attributes['fabricPrice'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['bracketUnitPrice'])){{decimalPlace($attributes['bracketUnitPrice'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['bracketPrice'])){{decimalPlace($attributes['bracketPrice'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['curvedPrice'])){{decimalPlace($attributes['curvedPrice'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['extra'])){{decimalPlace($attributes['extra'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['bottomFinish'])){{decimalPlace($attributes['bottomFinish'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['mottorPrice'])){{decimalPlace($attributes['mottorPrice'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['remotePrice'])){{decimalPlace($attributes['remotePrice'],$currency)}}@endif</td>
                                                    <td class="text-center">{{decimalPlace($attributes['total_price'],$currency)}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>

                    @endif

                    @if($hasSecurityWindow)
                        <ul class="nav nav-tabs" id="invoiceNav">
                            <li><a class="active" data-toggle="tab" href="#SecurityWindowInvDetails">Order Details</a></li>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <li><a data-toggle="tab" href="#SecurityWindowInvPrice">Price Calculator</a></li>
                            @endif
                        </ul>

                        <div class="tab-content">
                            <div id="SecurityWindowInvDetails" class="tab-pane fade in active show">
                                <!--Invoice Product-->
                                <div style="margin-top: 50px;margin-bottom: 50px;">
                                    <table style="width: 100%" class="table table-responsive mt-2" id="SecurityWindow-invoice-item-details-table">
                                        <thead>
                                        <tr>
                                            <th>NO.</th>
                                            <th>PRODUCT</th>
                                            <th>WIDTH</th>
                                            <th>HEIGHT</th>
                                            <th>MESH(D&F only)</th>
                                            <th>COLOR</th>
                                            <th>ACCESSORY SUPPLY ONLY</th>
                                            <th>QTY/M</th>
                                            <th>TOTAL PRICE</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($purchase->purchase_items as $item)
                                            @if($item->blinds_type != config('constants.blindsId.SecurityWindow'))
                                                @continue
                                            @endif
                                            @php
                                                $attributes =  json_decode($item->attributes,true);
                                            @endphp
                                            <tr id="product-{{ $item->product_id }}-panel">
                                                <td></td>
                                                <td class="text-center">{{config('constants.SECURITY_WINDOW.WINDOW_TYPE.'.$attributes['window_type'])}}</td>
                                                <td class="text-center">{{$attributes['width']}}</td>
                                                <td class="text-center">{{$attributes['height']}}</td>
                                                <td class="text-center">{{$attributes['mesh']}}</td>
                                                <td class="text-center">{{$attributes['color']}}</td>
                                                <td class="text-center">{{$attributes['accessory_supply']}}</td>
                                                <td class="text-center">{{$attributes['qty_m']}}</td>
                                                <td class="text-center">{{decimalPlace($item->sub_total,$currency)}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!--End Invoice Product-->
                            </div>

                            @if(\Illuminate\Support\Facades\Auth::user()->user_type != "client")
                                <div id="SecurityWindowInvPrice" class="tab-pane fade">
                                    <div class="table-responsive" style="margin-top: 50px;margin-bottom: 50px;">
                                        <table style="width: 100%" class="table mt-2" id="SecurityWindow-invoice-item-make-size">
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>Sheet(W)</th>
                                                <th>Sheet(H)</th>
                                                <th>Wedge(W)</th>
                                                <th>Wedge(H)</th>
                                                <th>Infill(W)</th>
                                                <th>Infill(H)</th>
                                                <th>SCREEN PRICE</th>
                                                <th>COLOR PRICE</th>
                                                <th>MESH PRICE</th>
                                                <th>ACCESSORY</th>
                                                <th>SUB TOTAL</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($purchase->purchase_items as $item)
                                                @if($item->blinds_type != config('constants.blindsId.SecurityWindow'))
                                                    @continue
                                                @endif
                                                @php
                                                    $attributes =  json_decode($item->attributes,true);
                                                @endphp
                                                <tr id="product-{{ $item->product_id }}">
                                                    <td></td>
                                                    <td class="text-center">{{$attributes['sheetW']}}</td>
                                                    <td class="text-center">{{$attributes['sheetH']}}</td>
                                                    <td class="text-center">{{$attributes['wedgeW']}}</td>
                                                    <td class="text-center">{{$attributes['wedgeH']}}</td>
                                                    <td class="text-center">{{$attributes['infillW']}}</td>
                                                    <td class="text-center">{{$attributes['infillH']}}</td>
                                                    <td class="text-center">@if(!empty($attributes['screenPrice'])){{decimalPlace($attributes['screenPrice'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['colorPrice'])){{decimalPlace($attributes['colorPrice'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['meshPrice'])){{decimalPlace($attributes['meshPrice'],$currency)}}@endif</td>
                                                    <td class="text-center">@if(!empty($attributes['accessoryPrice'])){{decimalPlace($attributes['accessoryPrice'],$currency)}}@endif</td>
                                                    <td class="text-center">{{decimalPlace($attributes['total_price'],$currency)}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>

                    @endif

                    <!--Summary Table-->
                    <div class="invoice-summary-right">
                        <table class="table table-bordered" id="invoice-summary-table">
                            <tbody>
                                <tr>
                                    <td>{{ _lang('Sub Total') }}</td>
                                    <td class="text-right">
                                        <span>{{ decimalPlace($purchase->product_total, $currency) }}</span>
                                    </td>
                                </tr>
                                @foreach($purchase_taxes as $tax)
                                <tr>
                                    <td>{{ $tax->name }}</td>
                                    <td class="text-right">
                                        <span>{{ decimalPlace($tax->tax_amount, $currency) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td>{{ _lang('Shipping Cost') }}</td>
                                    <td class="text-right">
                                        <span>+ {{ decimalPlace($purchase->shipping_cost, $currency) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ _lang('Discount') }}</td>
                                    <td class="text-right">
                                        <span>- {{ decimalPlace($purchase->order_discount, $currency) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ _lang('Grand Total') }}</b></td>
                                    <td class="text-right">
                                        <b>{{ decimalPlace($purchase->grand_total, $currency) }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ _lang('Total Paid') }}</td>
                                    <td class="text-right">
                                        <span>{{ decimalPlace($purchase->paid, $currency) }}</span>
                                    </td>
                                </tr>
                                @if($purchase->payment_status == 0)
                                <tr>
                                    <td>{{ _lang('Amount Due') }}</td>
                                    <td class="text-right">
                                        <span>{{ decimalPlace(($purchase->grand_total - $purchase->paid), $currency) }}</span>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!--End Summary Table-->

                    <div class="clearfix"></div>

                    <!--Related Transaction-->
                    @if( ! $transactions->isEmpty() )
                    <div class="table-responsive">
                        <table class="table table-bordered" id="invoice-payment-history-table">
                            <thead class="base_color">
                                <tr>
                                    <td colspan="7" class="text-center"><b>{{ _lang('Payment History') }}</b></td>
                                </tr>
                                <tr>
                                    <th>{{ _lang('Date') }}</th>
                                    <th>{{ _lang('Account') }}</th>
                                    <th class="text-right">{{ _lang('Amount') }}</th>
                                    <th class="text-right">{{ _lang('Base Amount') }}</th>
                                    <th>{{ _lang('Payment Method') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr id="transaction-{{ $transaction->id }}">
                                    <td>{{ date($date_format, strtotime($transaction->trans_date)) }}</td>
                                    <td>{{ $transaction->account->account_title.' - '.$transaction->account->account_currency }}
                                    </td>
                                    <td class="text-right">
                                        {{ decimalPlace($transaction->amount, currency($transaction->account->account_currency)) }}
                                    </td>
                                    <td class="text-right">{{ decimalPlace($transaction->base_amount, $currency) }}</td>
                                    <td>{{ $transaction->payment_method->name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <!--END Related Transaction-->



                </div>
            </div>
        </div>
    </div>
    <!--End Classic Invoice Column-->
</div>

<!--End Classic Invoice Row-->
@endsection


@section('js-script')
    <script>

        var t = $('#invoice-item-details-table').DataTable({
            responsive: true,
            searching: false,
            columnDefs: [
                {
                    searchable: false,
                    orderable: false,
                    targets: 0,
                },
            ],
            order: [[1, 'asc']],
        });

        t.on('order.dt search.dt', function () {
            let i = 1;

            t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                this.data(i++);
            });
        }).draw();

        var vt1 = $('#vertical-invoice-item-details-table').DataTable({
            responsive: true,
            searching: false,
            columnDefs: [
                {
                    searchable: false,
                    orderable: false,
                    targets: 0,
                },
            ],
            order: [[1, 'asc']],
        });

        vt1.on('order.dt search.dt', function () {
            let i = 1;

            t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                this.data(i++);
            });
        }).draw();



        var t2 = $('#invoice-item-make-size').DataTable({
            responsive: true,
            searching: false,
            columnDefs: [
                {
                    searchable: false,
                    orderable: false,
                    targets: 0,
                },
            ],
            order: [[1, 'asc']],
        });

        t2.on('order.dt search.dt', function () {
            let i = 1;

            t2.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                this.data(i++);
            });
        }).draw();

        var vt2 = $('#vertical-invoice-item-make-size').DataTable({
            responsive: true,
            searching: false,
            columnDefs: [
                {
                    searchable: false,
                    orderable: false,
                    targets: 0,
                },
            ],
            order: [[1, 'asc']],
        });

        vt2.on('order.dt search.dt', function () {
            let i = 1;

            t2.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                this.data(i++);
            });
        }).draw();



        var t3 = $('#invoice-item-pipe-size').DataTable({
            responsive: true,
            searching: false,
            columnDefs: [
                {
                    searchable: false,
                    orderable: false,
                    targets: 0,
                },
            ],
            order: [[1, 'asc']],
        });

        t3.on('order.dt search.dt', function () {
            let i = 1;

            t3.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                this.data(i++);
            });
        }).draw();

        var t4 = $('#invoice-item-label').DataTable({
            responsive: true,
            searching: false,
            columnDefs: [
                {
                    searchable: false,
                    orderable: false,
                    targets: 0,
                },
            ],
            order: [[1, 'asc']],
        });

        t4.on('order.dt search.dt', function () {
            let i = 1;

            t4.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                this.data(i++);
            });
        }).draw();


        var t5 = $('#curvers-invoice-item-details-table').DataTable({
            responsive: true,
            searching: false,
            columnDefs: [
                {
                    searchable: false,
                    orderable: false,
                    targets: 0,
                },
            ],
            order: [[1, 'asc']],
        });

        t5.on('order.dt search.dt', function () {
            let i = 1;

            t4.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                this.data(i++);
            });
        }).draw();

        var t6 = $('#Curtain-invoice-item-details-table').DataTable({
            responsive: true,
            searching: false,
            columnDefs: [
                {
                    searchable: false,
                    orderable: false,
                    targets: 0,
                },
            ],
            order: [[1, 'asc']],
        });

        t6.on('order.dt search.dt', function () {
            let i = 1;

            t4.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                this.data(i++);
            });
        }).draw();

        $("#order_status").change(function (){

            var value = $(this).val();
            var orderId = $("#order_id").val();

            $.ajax({
                type:'GET',
                url:'/purchase_orders/change_order_status/'+orderId+"/"+value,
                success: function (data)
                {
                    if(data.result == "success")
                    {
                        $.toast({
                            text: data.message,
                            showHideTransition: 'slide',
                            icon: 'success',
                            position: 'top-right'
                        });
                    }
                }
            });
        });

        $("#payment_status").change(function (){

            var value = $(this).val();
            var orderId = $("#order_id").val();

            $.ajax({
                type:'GET',
                url:'/purchase_orders/change_payment_status/'+orderId+"/"+value,
                success: function (data)
                {
                    if(data.result == "success")
                    {
                        $.toast({
                            text: data.message,
                            showHideTransition: 'slide',
                            icon: 'success',
                            position: 'top-right'
                        });
                    }
                }
            });
        });


    </script>
@endsection