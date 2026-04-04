@extends('layouts.app')



@section('title') {{ __('general.add_physical_product') }} -@endsection



@section('content')

<section class="section section-sm">

    <div class="container-fluid">

      <div class="row justify-content-center text-center mb-sm">

        <div class="col-lg-12 py-5">

          <h2 class="mb-0 font-montserrat font_weight_700 fs-24 pb-3">

            {{ __('general.add_physical_product') }}

          </h2>

          <p class="lead mt-0 font_weight_400 fs-14">

            {{ __('general.physical_products_desc') }}

        </p>

        </div>

      </div>

      <div class="row justify-content-center mb-3">



        <div class="col-lg-7">

            <form action="{{ url()->current() }}" method="post" enctype="multipart/form-data" id="shopProductForm">

              @csrf



              <div class="form-group preview-shop">

                <label for="preview">{{ __('general.preview') }} <small>(JPG, PNG)</small></label>

                <input type="file" name="preview" id="preview" accept="image/*">

              </div>



              <div class="form-group">

                <input type="text" class="form-control" name="name" placeholder="{{ __('admin.name') }}">

              </div>



                <div class="form-group">

                  <input type="text" class="form-control isNumber" name="price" autocomplete="off" placeholder="{{ __('general.price') }}">

                </div>



                @if ($settings->allow_external_links_shop)

                <div class="form-group">

                  <input type="text" class="form-control" name="external_link" placeholder="https://yourwebsite.com/purchase-url">

                  <div class="text-muted btn-block small mt-1">

                    <i class="bi-info-circle mr-1"></i> {{ __('general.external_link_buy') }}

                  </div>

                  

                </div>

                @endif



                <div class="form-group">

                  <input type="text" class="form-control isNumber" name="shipping_fee" autocomplete="off" placeholder="{{ __('general.shipping_fee') }}">

                </div>



                <div class="form-group">

                  <select name="country_free_shipping" class="form-control custom-select dark_all_dropdown">

                    <option value="" selected>{{ __('general.country_free_shipping') }}</option>

                    @foreach (Countries::orderBy('country_name')->get() as $country)

                      <option value="{{$country->id}}">{{ $country->country_name }}</option>

                      @endforeach

                  </select>

                </div>



                <div class="form-group">

                  <input type="text" class="form-control" name="tags" placeholder="{{ __('general.tags') }} ({{ __('general.separate_with_comma') }})">

                </div>



                <div class="form-group">

                  <select name="quantity" class="form-control custom-select dark_all_dropdown">

                    <option disabled value="" selected>{{ __('general.quantity') }}</option>

                    @for ($i=1; $i <= 100; ++$i)

                      <option value="{{$i}}">{{$i}}</option>

                    @endfor

                  </select>

                </div>



                <div class="form-group">

                  <input type="text" class="form-control" name="box_contents" placeholder="{{ __('general.box_contents') }}">

                </div>



                <div class="form-group">

                  <select name="category" class="form-control custom-select dark_all_dropdown">

                    <option disabled value="" selected>{{ __('general.category') }}</option>

                    @foreach (App\Models\ShopCategories::orderBy('name')->get() as $category)

                      <option value="{{ $category->id }}">

                        {{ Lang::has('shop-categories.' . $category->slug) ? __('shop-categories.' . $category->slug) : $category->name }}

                      </option>

                    @endforeach

                  </select>

                </div>



              <div class="form-group">

                <textarea class="form-control textareaAutoSize" name="description" placeholder="{{ __('general.description') }}" rows="3"></textarea>

              </div>



              <!-- Alert -->

            <div class="alert alert-danger my-3 display-none" id="errorShopProduct">

               <ul class="list-unstyled m-0" id="showErrorsShopProduct"><li></li></ul>

             </div><!-- Alert -->



              <button class="btn btn-1 btn-primary btn-block" id="shopProductBtn" type="submit"><i></i> {{ __('users.create') }}</button>

            </form>

        </div><!-- end col-md-12 -->

      </div>

    </div>

  </section>

@endsection



@section('javascript')

  <script src="{{ asset('js/fileuploader/fileuploader-shop-preview.js') }}"></script>

  <script src="{{ asset('js/shop.js') }}"></script>

@endsection

