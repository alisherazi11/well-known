@php
	$subtype = '';
@endphp
@if(!empty($transaction_sub_type))
	@php
		$subtype = '?sub_type='.$transaction_sub_type;
	@endphp
@endif

@if(!empty($transactions))
	<table class="table table-slim ">
		@foreach ($transactions as $transaction)
			<tr class="cursor-pointer" 
	    		title="Customer: {{optional($transaction->contact)->name}} 
		    		@if(!empty($transaction->contact->mobile) && $transaction->contact->is_default == 0)
		    			<br/>Mobile: {{$transaction->contact->mobile}}
		    			<hr>
		    		@endif
	    		" >
				<td>
					{{ $loop->iteration}}.
				</td>
				<td>
					{{ $transaction->invoice_no }} ({{optional($transaction->contact)->name}})
					<a href="{{action('SellPosController@edit', [$transaction->id]).$subtype}}">
									<button type="button"  class="btn bg-purple " aria-hidden="true" title="Edit" id="pos-cancel">Edit</button>
									
					@if(!empty($transaction->table))
						- {{$transaction->table->name}}
					@endif
			<td class="display_currency"> 
					{{ $transaction->final_total }}
					
				</td>
				<td>
					@if(auth()->user()->can('sell.update') || auth()->user()->can('direct_sell.update'))
					<a href="{{action('SellPosController@edit', [$transaction->id]).$subtype}}">
									
	    				
	    			</a>
	    			@endif 
	    				 
	    				 <a href="{{action('SellPosController@printInvoice', [$transaction->id])}}" class="print-invoice-link">
	    				<i class="btn btn-success fa fa-print text-muted" aria-hidden="true" title="{{__('lang_v1.click_to_print')}}"></i>
	    				
	    			</a>
				</td>
			</tr>
		@endforeach
	</table>
@else
	<p>@lang('sale.no_recent_transactions')</p>
@endif
<style>
.table.no-border, .table.no-border-td, .table.no-border-th{
    border: 0;
    line-height: 2;
    border-bottom: 1px solid #000;
}

.fas.fa-pen.text-muted {

  background: #red;
  position: absolute;
  color:black;
  font-size: 17px;
}
.fa.fa-trash.text-danger {
    display:none;
}

.table.table-slim.no-border{
    border: 0;
    line-height: 2;
    border-bottom: 1px solid #000;
}
.bg-purple{
    background-color:#028bff!important;
}
.fa.fa-print.text-muted{

    border-radius: 50%;
}
.display_currency{
        font-weight: bold;
        padding-top: 8px!important;
}

 
	</style>