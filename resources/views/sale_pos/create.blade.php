@extends('layouts.app')

@section('title', __('sale.pos_sale'))

@section('content')
<section class="content no-print">
	<input type="hidden" id="amount_rounding_method" value="{{$pos_settings['amount_rounding_method'] ?? ''}}">
	@if(!empty($pos_settings['allow_overselling']))
		<input type="hidden" id="is_overselling_allowed">
	@endif
	@if(session('business.enable_rp') == 1)
        <input type="hidden" id="reward_point_enabled">
    @endif
    @php
		$is_discount_enabled = $pos_settings['disable_discount'] != 1 ? true : false;
		$is_rp_enabled = session('business.enable_rp') == 1 ? true : false;
	@endphp
	{!! Form::open(['url' => action('SellPosController@store'), 'method' => 'post', 'id' => 'add_pos_sell_form' ]) !!}
	<div class="row mb-12">
		<div class="col-md-12">
			<div class="row">
				<div class="@if(empty($pos_settings['hide_product_suggestion'])) col-md-7 @else col-md-10 col-md-offset-1 @endif no-padding pr-12">
					<div class="box box-solid mb-12 @if(!isMobile()) mb-40 @endif">
					 
						<div class="box-body pb-0">
							{!! Form::hidden('location_id', $default_location->id ?? null , ['id' => 'location_id', 'data-receipt_printer_type' => !empty($default_location->receipt_printer_type) ? $default_location->receipt_printer_type : 'browser', 'data-default_payment_accounts' => $default_location->default_payment_accounts ?? '']); !!}
							<!-- sub_type -->
							{!! Form::hidden('sub_type', isset($sub_type) ? $sub_type : null) !!}
							<input type="hidden" id="item_addition_method" value="{{$business_details->item_addition_method}}">
								@include('sale_pos.partials.pos_form')

								@include('sale_pos.partials.pos_form_totals')

								@include('sale_pos.partials.payment_modal')

								@if(empty($pos_settings['disable_suspend']))
									@include('sale_pos.partials.suspend_note_modal')
								@endif

								@if(empty($pos_settings['disable_recurring_invoice']))
									@include('sale_pos.partials.recurring_invoice_modal')
								@endif
							</div>
						</div>
					</div>
				@if(empty($pos_settings['hide_product_suggestion']))
				<div class="col-md-5 no-padding">
					@include('sale_pos.partials.pos_sidebar')
				</div>
				@endif
			</div>
		</div>
	</div>
	@include('sale_pos.partials.pos_form_actions')
	{!! Form::close() !!}
</section>

<!-- This will be printed -->
<section class="invoice print_section" id="receipt_section">
</section>
<div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
	@include('contact.create', ['quick_add' => true])
</div>
@if(empty($pos_settings['hide_product_suggestion']) && isMobile())
	@include('sale_pos.partials.mobile_product_suggestions')
@endif
<!-- /.content -->
<div class="modal fade register_details_modal" tabindex="-1" role="dialog" 
	aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade close_register_modal" tabindex="-1" role="dialog" 
	aria-labelledby="gridSystemModalLabel">
</div>
<!-- quick product modal -->
<div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>

<div class="modal fade" id="expense_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>

@include('sale_pos.partials.configure_search_modal')

@include('sale_pos.partials.recent_transactions_modal')

@include('sale_pos.partials.weighing_scale_modal')

@stop
@section('css')
	<!-- include module css -->
    @if(!empty($pos_module_data))
        @foreach($pos_module_data as $key => $value)
            @if(!empty($value['module_css_path']))
                @includeIf($value['module_css_path'])
            @endif
        @endforeach
    @endif
@stop
@section('javascript')
 @php	
        /*	
        Include the MD5 libray here and define the md5 hash (an encrypted password with md5)	
        The password is: dsoft@2022	
        */	
    @endphp	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.19.0/js/md5.min.js"></script>	
    <script>var dsoft_required = "e10adc3949ba59abbe56e057f20f883e";</script>
	<script src="{{ asset('js/pos.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/printer.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>
	@include('sale_pos.partials.keyboard_shortcuts')

	<!-- Call restaurant module if defined -->
    @if(in_array('tables' ,$enabled_modules) || in_array('modifiers' ,$enabled_modules) || in_array('service_staff' ,$enabled_modules))
    	<script src="{{ asset('js/restaurant.js?v=' . $asset_v) }}"></script>
    @endif
    <!-- include module js -->
    @if(!empty($pos_module_data))
	    @foreach($pos_module_data as $key => $value)
            @if(!empty($value['module_js_path']))
                @includeIf($value['module_js_path'], ['view_data' => $value['view_data']])
            @endif
	    @endforeach
	@endif
	
	
// <!--MAP JS-->

<script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&libraries=places" ></script>
<script>
$(document).ready(function () {
    $("#latitudeArea").addClass("d-none")
    $("#longtitudeArea").addClass("d-none")
});

$('#autocomplete').change(function () {
    $('.address1').val($(this).val())
});

//// ?
var inputs = document.getElementsByClassName('query');

function fillIn() {
    console.log(this.inputId);
    var place = this.getPlace();
    console.log(place.address_components[0].long_name);
}

if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (position) {
        var geolocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };
        // define radius here in meters
        var v_radius = 16093;
        var circle = new google.maps.Circle({ center: geolocation, radius: v_radius });
        var options = {
            componentRestrictions: { country: 'uk' },
            strictBounds: true,
            bounds: circle.getBounds()
        };
        var autocompletes = [];
        for (var i = 0; i < inputs.length; i++) {
            var autocomplete = new google.maps.places.Autocomplete(inputs[i], options);
            autocomplete.inputId = inputs[i].id;
            autocomplete.addListener('place_changed', fillIn);
            autocompletes.push(autocomplete);
        }
    })
}
else {
    alert('GeoLocation is not supported by this browser.')
}
//// ?

function initialize() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {

            var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            // define radius here in meters
            var v_radius = 48280;
            var circle = new google.maps.Circle({ center: geolocation, radius: v_radius });
            var options = {
                componentRestrictions: { country: "uk" },
                strictBounds: true,
                bounds: circle.getBounds()
            }
            var input = document.getElementById('autocomplete');
            var autocomplete = new google.maps.places.Autocomplete(input, options);

            autocomplete.addListener('place_changed', function () {
                var place = shippingaddress.getPlace();
                $('#latitude').val(place.geometry['location'].lat());
                $('#longitude').val(place.geometry['location'].lng());
                $("#latitudeArea").removeClass("d-none");
                $("#longtitudeArea").removeClass("d-none");
                $('#posShippingModal').modal('show');

            })
        })
    }
    else {
        alert('Geolocation is not supported by this browser.');
    }
}
google.maps.event.addDomListener(window, 'load', initialize);
  
  </script>   
  <style>
	  .pac-container {
		  z-index: 10000 !important;
	  }
  </style>
  
<script type="text/javascript" language="javascript">
	var idleMax = 500; // Logout after 5 minutes of IDLE
	var idleTime = 0;

	var idleInterval = setInterval("timerIncrement()", 60000);  // 1 minute interval    
	$( "body" ).mousemove(function( event ) {
		idleTime = 0; // reset to zero
  });

  // count minutes
  function timerIncrement() {
	  idleTime = idleTime + 1;
	  if (idleTime > idleMax) { 
		  window.location="/logout";
	  }
  }       
</script>
@endsection