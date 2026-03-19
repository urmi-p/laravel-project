<!DOCTYPE html>

<html>

  <head>

    <meta charset="UTF-8">

    <title>{{ __('error.error_500') }}</title>

    <link href="{{ asset('css/core.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

  </head>

  <body>

  		<div class="wrap-center">

  			<div class="container">

  				<div class="row">

  					<div class="col-md-12 error-page text-center parallax-fade-top" style="top: 0px; opacity: 1;">

  						<h1>500</h1>

  						<p class="mt-3 mb-5">{{ __('error.server_error') }}</p>

  					</div>

  				</div>

  			</div>

  		</div>

  </body>

</html>
