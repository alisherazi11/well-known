@forelse($products as $product)

	<div class="col-md-3 col-xs-4 product_list no-print">
		
	
		<div class="product_box" data-variation_id="{{$product->id}}" title="{{$product->name}} @if($product->type == 'variable')- {{$product->variation}} @endif {{ '(' . $product->sub_sku . ')'}} @if(!empty($show_prices)) @lang('lang_v1.default') - @format_currency($product->selling_price) @foreach($product->group_prices as $group_price) @if(array_key_exists($group_price->price_group_id, $allowed_group_prices)) {{$allowed_group_prices[$group_price->price_group_id]}} - @format_currency($group_price->price_inc_tax) @endif @endforeach @endif">
			<div class="stock" data-title=" 	
			@if($product->enable_stock == '1')
				Stock: {{number_format($product->qty_available)}}			
			 @endif
		
			
			@if($product->disable_stock == '0')
				Stock: ~
			@endif

				   ">
		<div class="image-container" 
			style="background-image: url(
					@if(count($product->media) > 0)
						{{$product->media->first()->display_url}}
					@elseif(!empty($product->product_image))
						{{asset('/uploads/img/' . rawurlencode($product->product_image))}}
					@else
						{{asset('/img/default.png')}}
					@endif
				);
			background-repeat: no-repeat; background-position: center;
			background-size: contain;">
			
		</div>
		

		<div class="text_div">
			<small class="text text-muted">{{$product->name}} 
			@if($product->type == 'variable')
				- {{$product->variation}}
			@endif
			</small>
			<!--
			<small class="text-muted">
				({{$product->sub_sku}})
				<br>
			</small> -->
			
		</div>
		</div>
		
	</div>
</div>

@empty
	<input type="hidden" id="no_products_found">
	<div class="col-md-12">
		<h4 class="text-center">
			@lang('lang_v1.no_products_to_display')
		</h4>
	</div>
@endforelse
<style>
.stock {
  position: relative;
  padding: 25px 10px 0px 0px;
  
}
.stock:before {
  content: attr(data-title);
  background: #0021a6;
  position: absolute;
  padding: 0 3px;
  color: #fff;
  left: 0;
  top: 0;
}
.product_boxw {
 
  
}
	</style>
