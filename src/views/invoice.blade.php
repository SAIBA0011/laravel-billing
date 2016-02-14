<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<style type="text/css">
		.invoice {
		  margin-bottom: 20px;
		}

		.invoice .invoice-logo {
		  margin-bottom: 20px;
		}

		.invoice table {
		  margin: 30px 0;
		}

		.invoice .invoice-logo p {
		  font-size: 20px;
		  line-height: 28px;
		  padding: 25px 0;
		  text-align: right;
		}

		.invoice .invoice-logo p small {
		  display: block;
		  font-size: 14px;
		}

		.invoice h4 {
		  font-family: 'Open Sans', sans-serif;
		  font-weight: 300 !important;
		}

		.invoice-details {
		  padding-top: 30px;
		}

		.invoice .invoice-block {
		  text-align: right;
		}

		.invoice .invoice-block .amounts {
		  font-size: 14px;
		  margin-top: 20px;
		}
	</style>
</head>
<body>
	<div class="col-md-8 col-md-offset-2">
		<div class="invoice">
	<div class="row invoice-logo">
		<div class="col-sm-6">
			<img alt="" src="http://www.cliptheme.com/demo/clip-two/AngularJs-Admin/STANDARD/assets/images/your-logo-here.png">
		</div>
		<div class="col-sm-6">
			<p class="text-dark">
				#{{ $invoice->id }} / {{ date('M jS, Y', strtotime($invoice->date)) }} 
				@if (isset($product))
					<small class="text-light"><strong>Product:</strong> {{ $product }}</small>
				@endif
			</p>
		</div>
	</div>
	<hr>
	@if(isset($user))
	<div class="row">
		<div class="col-sm-4">
			<h4>Client:</h4>
			<div class="well">
				<address>
					<strong class="text-dark">
					@if($user->profile->title)
						{{ $user->profile->title }}
					@endif
					{{ $user->first_name }} {{ $user->last_name }}</strong>
					@if($user->profile->phone)
					<br>
					<abbr title="Phone">P:</abbr> {{ $user->profile->phone }}
					@endif
					@if($user->profile->cell)
					<br>
					<abbr title="Phone">C:</abbr> {{ $user->profile->cell }}
					@endif
				</address>
				<address>
					<strong class="text-dark">E-mail:</strong>
					<a href="mailto:{{ $user->email }}">
						{{ $user->email }}
					</a>
				</address>
			</div>
		</div>
		
		<div class="col-sm-4 pull-right">
			<h4>Payment Details:</h4>
			<ul class="list-unstyled invoice-details padding-bottom-30 padding-top-10 text-dark">
				<li>
					<strong>V.A.T Reg #:</strong> 233243444
				</li>
				<li>
					<strong>Account Name:</strong> Company Ltd
				</li>
				<li>
					<strong>Branch code:</strong> 1233F4343ABCDEW
				</li>
				<li>
					<strong>DATE:</strong> 01/01/2014
				</li>
				<li>
					<strong>DUE:</strong> 11/02/2014
				</li>
			</ul>
		</div>
	</div>
	@endif
	<div class="row">
		<div class="col-sm-12">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Description</th>
						<th>Date</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($invoice->items() as $item)
					<tr>
						@if ($item->subscription_id)
							<td>
								@if ($item->description)
									{{ $item->description }}
								@else
									Subscription
									
									@if ($item->subscription())
										to {{ ucwords(str_replace(array('_', '-'), ' ', $item->subscription()->plan)) }}
									@endif
									
									@if ($item->quantity > 1)
										(x{{ $item->quantity }})
									@endif
								@endif
							</td>
							<td>
								@if ($item->period_start && $item->period_end)
									{{ date('M jS, Y', strtotime($item->period_start)) }}
									-
									{{ date('M jS, Y', strtotime($item->period_end)) }}
								@endif
							</td>
						@else
							<td>{{ $item->description }}</td>
							<td>&nbsp;</td>
						@endif
						
						@if ($item->amount >= 0)
							<td>R{{ number_format($item->amount / 100, 2) }}</td>
						@else
							<td>-R{{ number_format(abs($item->amount) / 100, 2) }}</td>
						@endif
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 invoice-block">
			<ul class="list-unstyled amounts text-small">
				@if ($invoice->subtotal)
				<li>
					<strong>Sub-Total:</strong> R{{ number_format($invoice->subtotal / 100, 2) }}
				</li>				
				@endif
				
				@if ($invoice->discounts)
					@foreach ($invoice->discounts as $discount)
						<li>
							<strong>Discount:</strong> 
								{{ array_get($discount, 'coupon') }}
								
								@if (array_get($discount, 'amount_off'))
									(R{{ array_get($discount, 'amount_off') / 100 }} Off)
								@else
									({{ array_get($discount, 'percent_off') }}% Off)
								@endif

								<strong>
									@if (array_get($discount, 'amount_off'))
										-R{{ number_format(abs(array_get($discount, 'amount_off') / 100), 2) }}
									@else
										-R{{ number_format($invoice->subtotal * (array_get($discount, 'percent_off') / 100) / 100, 2) }}
									@endif
								</strong>
							</td>
						</tr>
					@endforeach
				@endif				
				@if (isset($vat))
					<li>
						<strong>VAT:</strong> {{ $vat }}%
					</li>
				@endif

				@if ($invoice->starting_balance)
					<li>
						<strong>Starting Customer Balance:</strong> 
						@if ($invoice->starting_balance >= 0)
							<strong>R{{ number_format($invoice->starting_balance / 100, 2) }}</strong>
						@else
							<strong>-R{{ number_format(abs($invoice->starting_balance) / 100, 2) }}</strong>
						@endif
					</li>					
				@endif
				
				<li class="text-extra-large text-dark margin-top-15">
					<strong>Amound Paid:</strong> R{{ number_format($invoice->amount / 100, 2) }}
				</li>
			</ul>
			<br>
			<a onclick="javascript:window.print();" class="btn btn-lg btn-primary hidden-print">
				Print <i class="fa fa-print"></i>
			</a>			
		</div>
			</div>
		</div>
	</div>
</body>
</html>
