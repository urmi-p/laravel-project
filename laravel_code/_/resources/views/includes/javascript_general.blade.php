<script src="{{ asset('js/core.min.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/jqueryTimeago_'.Lang::locale().'.js') }}"></script>
<script src="{{ asset('js/lazysizes.min.js') }}" async=""></script>
<script src="{{ asset('js/plyr/plyr.min.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('js/plyr/plyr.polyfilled.min.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('js/app-functions.js') }}?v={{$settings->version}}"></script>

@if (request()->routeIs('reels.section.*') || request()->routeIs('profile') && request('media') == 'reels')
<script src="{{ asset('js/reels/reels.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('js/reels/comments-reels.js') }}?v={{$settings->version}}"></script>
@endif

@if (! request()->is('live/*'))
<script src="{{ asset('js/install-app.js') }}?v={{$settings->version}}"></script>
@endif

@auth
  <script src="{{ asset('js/fileuploader/jquery.fileuploader.min.js') }}"></script>
  <script src="{{ asset('js/fileuploader/fileuploader-post.js') }}?v={{$settings->version}}"></script>
  <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
  <script src="{{ asset('js/vault.js') }}?v={{$settings->version}}"></script>
  @if (request()->path() == '/' 
  		&& auth()->user()->verified_id == 'yes' 
		|| request()->routeIs('profile') 
		&& request()->path() == auth()->user()->username  
		&& auth()->user()->verified_id == 'yes'
		)
  <script src="{{ asset('js/jquery-ui/mentions.js') }}"></script>
@endif

@if ($settings->story_status)
<script src="{{ asset('js/story/zuck.min.js') }}?v={{$settings->version}}"></script>
@endif

@if ($settings->video_call_status)
<script src="{{ asset('js/calls.js') }}?v={{$settings->version}}"></script>
@endif

<script src="https://js.stripe.com/v3/"></script>
<script src='https://checkout.razorpay.com/v1/checkout.js'></script>
<script src='https://js.paystack.co/v1/inline.js'></script>
@if (request()->is('my/wallet'))
<script src="{{ asset('js/add-funds.js') }}?v={{$settings->version}}"></script>
@else
<script src="{{ asset('js/payment.js') }}?v={{$settings->version}}&{{ filemtime(public_path('js/payment.js')) }}"></script>
<script src="{{ asset('js/payments-ppv.js') }}?v={{$settings->version}}"></script>
@endif
<script src="{{ asset('js/send-gift.js') }}?v={{$settings->version}}"></script>
@endauth

@if ($settings->custom_js)
  <script type="text/javascript">
  {!! $settings->custom_js !!}
  </script>
@endif

<script type="text/javascript">
const lightbox = GLightbox({
    touchNavigation: true,
    loop: false,
    closeEffect: 'fade'
});

@auth
$('.btnMultipleUpload').on('click', function() {
  $('.fileuploader').toggleClass('d-block');
});

$(document).on('click', '.topup-wallet', function(e) {
  var $modal = $('#modalTopupWallet');
  if ($modal.length && typeof $modal.modal === 'function') {
    e.preventDefault();
    $modal.modal('show');
  }
});

// Top up modal logic (loaded after core scripts)
@if (auth()->check() && $settings->disable_wallet == 'off')
$(function() {
  var $modal = $('#modalTopupWallet');
  if (!$modal.length) {
    return;
  }

  var decimalTopup = @if (in_array(config('settings.currency_code'), config('currencies.zero-decimal'))) 0 @else 2 @endif;

  function toFixedTopup(number, decimals) {
    var x = Math.pow(10, Number(decimals) + 1);
    return (Number(number) + (1 / x)).toFixed(decimals);
  }

  function resetTotals() {
    $modal.find('#topupHandlingFee, #topupTotal, #topupTotal2').html('0');
    var taxes = $modal.find('span.topupTaxable').length;
    for (var i = 1; i <= taxes; i++) {
      $modal.find('.topupPercentageTax' + i).html('0');
    }
  }

  function updateTotals() {
    var valueOriginal = $modal.find('#topupOnlyNumber').val();
    var value = parseFloat(valueOriginal);
    var paymentGateway = $modal.find('input[name=payment_gateway]:checked').val();

    var taxes = $modal.find('span.topupTaxable').length;
    var totalTax = 0;

    if (valueOriginal.length == 0
      || valueOriginal == ''
      || value < {{ $settings->min_deposits_amount }}
      || value > {{ $settings->max_deposits_amount }}
    ) {
      resetTotals();
      return;
    }

    for (var i = 1; i <= taxes; i++) {
      var percentage = $modal.find('.topupPercentageAppliedTax' + i).attr('data');
      var valueFinal = (value * percentage / 100);
      $modal.find('.topupPercentageTax' + i).html(toFixedTopup(valueFinal, decimalTopup));
      totalTax += valueFinal;
    }
    var totalTaxes = (Math.round(totalTax * 100) / 100).toFixed(2);

    if (paymentGateway
      && value <= {{ $settings->max_deposits_amount }}
      && value >= {{ $settings->min_deposits_amount }}
      && valueOriginal != ''
    ) {
      var fee = 0;
      var cents = 0;
      switch (paymentGateway) {
        @foreach (PaymentGateways::where('enabled', '1')->get() as $payment)
        case '{{ $payment->name }}':
          fee = {{ $payment->fee }};
          cents = {{ $payment->fee_cents }};
          break;
        @endforeach
      }

      var amount = (value * fee / 100) + cents;
      var amountFinal = toFixedTopup(amount, decimalTopup);
      var total = (parseFloat(value) + parseFloat(amountFinal) + parseFloat(totalTaxes));

      $modal.find('#topupHandlingFee').html(amountFinal);
      $modal.find('#topupTotal, #topupTotal2').html(total.toFixed(decimalTopup));
    }
  }

  function toggleBankBox() {
    var paymentGateway = $modal.find('input[name=payment_gateway]:checked').val();
    if (paymentGateway == 'Bank Transfer' || paymentGateway == 'Bank') {
      $modal.find('#topupBankTransferBox').fadeIn();
    } else {
      $modal.find('#topupBankTransferBox').fadeOut();
    }
  }

  $modal.on('click', 'input[name=payment_gateway]', function() {
    toggleBankBox();
    updateTotals();
  });

  $modal.on('keyup', '#topupOnlyNumber', function() {
    updateTotals();
  });

  $modal.on('click', '.topup-amount-increase', function () {
    var $input = $modal.find('#topupOnlyNumber');
    $input.val(parseInt($input.val() || 0) + 1);
    updateTotals();
  });

  $modal.on('click', '.topup-amount-decrease', function () {
    var $input = $modal.find('#topupOnlyNumber');
    var val = parseInt($input.val() || 0);
    if (val > 1) {
      $input.val(val - 1);
      updateTotals();
    }
  });

  $modal.on('change', '#topupFileBankTransfer', function() {
    $modal.find('#topupPreviewImage').html('');
    if (window.File && window.FileReader && window.FileList && window.Blob) {
      if ($(this).val()) {
        var rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image)$/i;
        if ($(this)[0].files.length === 0) { return; }
        var oFile = $(this)[0].files[0];
        var fsize = $(this)[0].files[0].size;

        if (!rFilter.test(oFile.type)) {
          $('#topupFileBankTransfer').val('');
          swal({
            title: error_oops,
            text: formats_available_images,
            type: "error",
            confirmButtonText: ok
          });
          return false;
        }

        var allowed_file_size = file_size_allowed_verify_account;
        if (fsize > allowed_file_size) {
          $('.popout').addClass('popout-error').html(max_size_id_lang).fadeIn(500).delay(4000).fadeOut();
          $(this).val('');
          return false;
        }

        $modal.find('#topupPreviewImage').html('<i class="fas fa-image text-info"></i> <strong>' + oFile.name + '</strong>');
      }
    } else {
      alert('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.');
      return false;
    }
  });

  $modal.on('click', '#topupAddFundsBtn', function(e) {
    var isValid = this.form.checkValidity();
    if (!isValid) {
      return;
    }

    e.preventDefault();
    var $button = $(this);
    var $form = $modal.find('#topupFormAddFunds');
    var payment = $modal.find('input[name=payment_gateway]:checked').val();
    $button.attr({'disabled' : 'true'});
    $button.html('<i class="spinner-border spinner-border-sm mr-2"></i> {{__("general.processing")}}');

    (function() {
      $form.ajaxForm({
        dataType: 'json',
        success: function(result) {
          if (result.success && result.instantPayment) {
            window.location.reload();
          }

          if (result.success == true && result.insertBody) {
            $('#bodyContainer').html('');
            $(result.insertBody).appendTo("#bodyContainer");
            if (payment != 'PayPal' && payment != 'Stripe') {
              $button.removeAttr('disabled');
              $button.html('{{ __('general.add_funds') }}');
            }
            $modal.find('#topupErrorAddFunds').hide();
          } else if (result.success == true && result.status == 'pending') {
            swal({
              title: thanks,
              text: result.status_info,
              type: "success",
              confirmButtonText: ok
            });

            $form.trigger("reset");
            $button.removeAttr('disabled');
            $button.html('{{ __('general.add_funds') }}');
            $modal.find('#topupPreviewImage').html('');
            resetTotals();
            $modal.find('#topupBankTransferBox').hide();
          } else if (result.success == true && result.url) {
            window.location.href = result.url;
          } else {
            if (result.errors) {
              var error = '';
              var $key = '';
              for ($key in result.errors) {
                error += '<li><i class="far fa-times-circle"></i> ' + result.errors[$key] + '</li>';
              }
              $modal.find('#topupShowErrorsFunds').html(error);
              $modal.find('#topupErrorAddFunds').show();
              $button.removeAttr('disabled');
              $button.html('{{ __('general.add_funds') }}');
            }
          }
        },
        error: function(responseText, statusText, xhr, $form) {
          $button.removeAttr('disabled');
          $button.html('{{ __('general.add_funds') }}');
          swal({
            type: 'error',
            title: error_oops,
            text: error_occurred+' ('+xhr+')',
          });
        }
      }).submit();
    })();
  });

  $modal.on('shown.bs.modal', function() {
    toggleBankBox();
    updateTotals();
  });

  $modal.on('hidden.bs.modal', function() {
    $modal.find('#topupErrorAddFunds').hide();
    $modal.find('#topupShowErrorsFunds').html('');
    $modal.find('#topupPreviewImage').html('');
    $modal.find('#topupFormAddFunds').trigger("reset");
    resetTotals();
  });
});
@endif

	@if (request()->routeIs('post.edit') && $preloadedFile)
	$(document).ready(function() {
		$('.fileuploader').addClass('d-block');
	});
	@endif

@endauth
</script>

@if (auth()->check() && session('show_age_verification_after_register') && $settings->age_verification_status && $settings->show_modal_age_verification)
<script>
	$('#alertAgeVerification').modal({
		backdrop: 'static',
		keyboard: false,
		show: true
	});
</script>
@endif

@if (
  auth()->check()
  && session('show_language_after_register')
  && $languages->count() > 1
  && !($settings->age_verification_status && $settings->show_modal_age_verification)
)
<script>
	var $languagePreferenceModal = $('#languagePreferenceModal');

	$languagePreferenceModal.modal({
		backdrop: 'static',
		keyboard: false,
		show: true
	});

	$languagePreferenceModal.on('hidden.bs.modal', function() {
		var clearUrl = $languagePreferenceModal.data('clear-url');

		if (clearUrl) {
			$.post(clearUrl, {
				_token: $('meta[name="csrf-token"]').attr('content')
			});
		}
	});
</script>
@endif

@if (auth()->guest()
    && ! request()->is('password/reset')
    && ! request()->is('password/reset/*')
    && ! request()->is('contact')
    )
<script type="text/javascript">
	//<---------------- Login Register ----------->>>>
	onSubmitformLoginRegister = function() {
		  sendFormLoginRegister();
		}

	if (! captcha) {
	    $(document).on('click','#btnLoginRegister',function(s) {
 		 s.preventDefault();
		 sendFormLoginRegister();
 	 });//<<<-------- * END FUNCTION CLICK * ---->>>>
	}

	function sendFormLoginRegister() {
		var element = $(this);
		$('#btnLoginRegister').attr({'disabled' : 'true'});
		$('#btnLoginRegister').find('i').addClass('spinner-border spinner-border-sm align-middle mr-1');

		(function(){
			 $("#formLoginRegister").ajaxForm({
			 dataType : 'json',
			 success:  function(result) {

         if (result.actionRequired) {
           if (result.csrf_token) {
             $('meta[name="csrf-token"]').attr('content', result.csrf_token);
             $('#formVerify2fa').find('input[name="_token"]').val(result.csrf_token);
           }

           $('#modal2fa').modal({
    				    backdrop: 'static',
    				    keyboard: false,
    						show: true
    				});

            $('#loginFormModal').modal('hide');
           return false;
         }

				 // Success
				 if (result.success) {

           if (result.isModal && result.isLoginRegister) {
             window.location.reload();
           }

					 if (result.url_return && ! result.isModal) {
					 	window.location.href = result.url_return;
					 }

					 if (result.check_account) {
					 	$('#checkAccount').html(result.check_account).fadeIn(500);

						$('#btnLoginRegister').removeAttr('disabled');
						$('#btnLoginRegister').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
						$('#errorLogin').fadeOut(100);
						$("#formLoginRegister").reset();
					 }

				 }  else {

					 if (result.errors) {
						 var error = '';
						 var $key = '';

					for ($key in result.errors) {
							 error += '<li><i class="far fa-times-circle"></i> ' + result.errors[$key] + '</li>';
						 }

						 $('#showErrorsLogin').html(error);
						 $('#errorLogin').fadeIn(500);
						 $('#btnLoginRegister').removeAttr('disabled');
						 $('#btnLoginRegister').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');

						 if (captcha) {
							grecaptcha.reset();
						 }
					 }
				 }
				},

				statusCode: {
						419: function() {
							window.location.reload();
						}
					},
				error: function(responseText, statusText, xhr, $form) {
						// error
						$('#btnLoginRegister').removeAttr('disabled');
						$('#btnLoginRegister').find('i').removeClass('spinner-border spinner-border-sm align-middle mr-1');
						swal({
								type: 'error',
								title: error_oops,
								text: error_occurred+' ('+xhr+')',
							});
							
						if (captcha) {
							grecaptcha.reset();
						 }
				}
			}).submit();
		})(); //<--- FUNCTION %
	}
</script>
@endif
