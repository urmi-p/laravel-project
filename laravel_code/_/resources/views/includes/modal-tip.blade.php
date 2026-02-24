<div class="modal fade" id="tipForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content shadow-lg tip_modal_main_div">
			<div class="modal-body p-0">
				<div class="card bg-white shadow border-0 tip_modal_card">
					<div class="card-header border-0 position-relative text-center tip_modal_card_header">
					</div>
					<div class="card-body px-lg-5 position-relative" style="padding-top: 0px;">

                        <style>
                            .avatar-wrapper {
                                margin-top: -100px;
                                margin-bottom: 20px;
                                position: relative;
                                display: inline-block;
                            }
                            .avatar-modal-img {
                                width: 110px;
                                height: 110px;
                                /* border: 6px solid white; */
                                border-radius: 50%;
                                object-fit: cover;
                                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                            }
                            .payment-radio:checked + .btn-pay-action {
                                background-color: #ff4b60 !important;
                                color: white !important;
                                border-color: #ff4b60 !important;
                                box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
                            }
                            .payment-option-row {
                                transition: all 0.3s ease;
                                padding: 15px 0;
                            }
                            .btn-pay-action {
                                transition: all 0.2s ease-in-out;
                                cursor: pointer;
                                font-weight: 600;
                            }
                            .btn-pay-action:hover {
                                background-color: #ff4b60;
                                color: white;
                            }
                            .payment-selector {
                                min-width: 60px;
                            }
                        </style>

						<div class="text-center">
                            <div class="avatar-wrapper">
							    <img src="{{Helper::getFile(config('path.avatar').auth()->user()->avatar)}}" class="avatar-modal avatar-modal-img">
                            </div>
							<h5 class="font-weight-bold mb-1">
								{{__('general.send_tip')}} <span class="userNameTip"></span>
							</h5>
							<small class="text-muted d-block mb-4">{{ __('general.in_currency', ['currency_code' => $settings->currency_code]) }}</small>
						</div>

						<form method="post" action="{{url('send/tip')}}" id="formSendTip">
							@csrf
							<input type="hidden" name="id" class="userIdInput" value="{{auth()->user()->id}}"  />

							@if (request()->is('messages/*'))
								<input type="hidden" name="isMessage" value="1" />
							@endif

							@if (request()->route()->named(['live', 'live.private']))
								<input type="hidden" name="isLive" value="1" />

								@if ($live)
									<input type="hidden" name="liveID" value="{{ $live->id }}"  />
								@endif

							@endif

							<input type="hidden" id="cardholder-name" value="{{ auth()->user()->name }}"  />
							<input type="hidden" id="cardholder-email" value="{{ auth()->user()->email }}"  />
							
							<div class="form-group mb-4">
								<input type="number" min="{{$settings->min_tip_amount}}" max="{{$settings->max_tip_amount}}" required data-min-tip="{{$settings->min_tip_amount}}" data-max-tip="{{$settings->max_tip_amount}}" autocomplete="off" id="onlyNumber" class="form-control form-control-lg text-center tipAmount tip_modal_input" name="amount" placeholder="{{__('general.tip_amount')}}">
								<small class="text-muted d-block text-center mt-2">
									{{ __('general.minimum') }} {{ Helper::priceWithoutFormat($settings->min_tip_amount) }} -
									<span class="cursor-pointer" onclick="document.getElementById('onlyNumber').stepUp()">
										<i class="bi-arrow-up-square"></i>
									</span>
									<span class="cursor-pointer" onclick="document.getElementById('onlyNumber').stepDown()">
										<i class="bi-arrow-down-square"></i>
									</span>
								</small>
							</div>

							@csrf

							<div class="payment-options-list text-left">
								@if (!request()->route()->named('live'))

									@foreach ($paymentGatewaysSubscription as $payment)
										@php
											if ($payment->type == 'card' ) {
												$paymentIcon = '<i class="far fa-credit-card fa-lg"></i>';
												$paymentLabel = __('general.debit_credit_card');
												$paymentSubLabel = __('general.powered_by').' '.$payment->name;
											} else if ($payment->id == 1) {
												$paymentIcon = '<i class="fab fa-paypal fa-lg"></i>';
												$paymentLabel = 'PayPal';
												$paymentSubLabel = __('general.redirected_to_paypal_website');
											} else {
												$paymentIcon = '<img src="'.url('img/payments', $payment->logo).'" width="30"/>';
												$paymentLabel = $payment->name;
												$paymentSubLabel = '';
											}
											$allPayments = $paymentGatewaysSubscription;
										@endphp

										<div class="payment-option-item border-bottom payment-option-row">
											<div class="d-flex align-items-center justify-content-between">
												<div class="d-flex align-items-center">
													<div class="icon-wrapper mr-3 tip_modal_icon_wrap">
														{!! $paymentIcon !!}
													</div>
													<div>
														<h6 class="mb-0 font-weight-bold">{{ $paymentLabel }}</h6>
														@if(!empty($paymentSubLabel))
														<small class="text-muted">{{ $paymentSubLabel }}</small>
														@endif
													</div>
												</div>
												
												<div class="payment-selector">
													<input name="payment_gateway_tip" required value="{{$payment->name}}" id="tip_radio{{$payment->name}}" @if ($allPayments->count() == 1 && Helper::userWallet('balance') == 0) checked @endif class="d-none payment-radio" type="radio">
													<label for="tip_radio{{$payment->name}}" class="btn btn-sm btn-outline-danger btn-pay-action mb-0 px-3" style="border-radius: 6px;">
														Pay
													</label>
												</div>
											</div>

											@if ($payment->name == 'Stripe')
												<div id="stripeContainerTip" class="mt-3 @if ($allPayments->count() != 1) display-none @endif">
													<div id="card-element" class="margin-bottom-10">
														<!-- A Stripe Element will be inserted here. -->
													</div>
													<div id="card-errors" class="alert alert-danger display-none" role="alert"></div>
												</div>
											@endif
										</div>
									@endforeach
								@endif

								@if ($settings->disable_wallet == 'on' && Helper::userWallet('balance') != 0 || $settings->disable_wallet == 'off')
									<div class="payment-option-item border-bottom payment-option-row">
										<div class="d-flex align-items-center justify-content-between">
											<div class="d-flex align-items-center">
												<div class="icon-wrapper mr-3 tip_modal_icon_wrap">
													<i class="fas fa-wallet fa-lg"></i>
												</div>
												<div>
													<h6 class="mb-0 font-weight-bold">{{ __('general.wallet') }}</h6>
													<small class="text-muted">
														{{ __('general.available_balance') }}: <span class="font-weight-bold balanceWallet">{{Helper::userWallet()}}</span>
														@if (Helper::userWallet('balance') == 0)
															<a href="{{ url('my/wallet') }}" class="ml-1">{{ __('general.recharge') }}</a>
														@endif
													</small>
												</div>
											</div>
											
											<div class="payment-selector">
												<input name="payment_gateway_tip" required @if (Helper::userWallet('balance') == 0) disabled @endif value="wallet" id="tip_radio0" class="d-none payment-radio" type="radio">
												<label for="tip_radio0" class="btn btn-sm btn-outline-danger btn-pay-action mb-0 px-3 @if (Helper::userWallet('balance') == 0) disabled @endif" style="border-radius: 6px;">
													Pay
												</label>
											</div>
										</div>
									</div>
								@endif
							</div>

							@if ($taxRatesCount != 0 && auth()->user()->isTaxable()->count())
								@include('includes.modal-taxes')
							@endif

							<div class="alert alert-danger display-none" id="errorTip">
								<ul class="list-unstyled m-0" id="showErrorsTip"></ul>
							</div>

							<div class="d-flex justify-content-between mt-4 float-right">
								<button type="button" class="btn btn-outline-danger mr-2 tip_modal_cancel" data-dismiss="modal">{{__('admin.cancel')}}</button>
								<button type="submit" id="tipBtn" class="btn btn-danger w-50 ml-2 tipBtn tip_modal_send"><i></i> {{__('auth.send')}}</button>
							</div>

							<!-- @include('includes.site-billing-info') -->
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- End Modal Tip -->