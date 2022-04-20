@inject('request', 'Illuminate\Http\Request')

<div class="container-fluid">

	
		                	@endif
		                >
		                	{{$val['full_name']}}
		                </option>
		            @endforeach
		        </select>
	    	</div>
		</div>
		<div class="col-md-6">
			<div class="pull-right text-white">
	        	@if(!($request->segment(1) == 'business' && $request->segment(2) == 'register'))

	        	
		            	<!-- pricing url -->
			            @if(Route::has('pricing') && config('app.env') != 'demo' && $request->segment(1) != 'pricing')
		                	<a href="{{ action('\Modules\Superadmin\Http\Controllers\PricingController@index') }}">@lang('superadmin::lang.pricing')</a>
		            	@endif
		            @endif
		        @endif

		        @if(!($request->segment(1) == 'business' && $request->segment(2) == 'register') && $request->segment(1) != 'login')
		        	{{ __('business.already_registered')}} <a href="{{ action('Auth\LoginController@login') }}@if(!empty(request()->lang)){{'?lang=' . request()->lang}} @endif">{{ __('business.sign_in') }}</a>
		        @endif
	        </div>
		</div>
	</div>
</div>