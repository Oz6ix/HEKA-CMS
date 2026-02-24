@if(count($featured_products) > 0)
    @foreach($featured_products as $key => $featured)
        @if($key % 2 == 0)
            {{-- even iteration --}}
            <!-- begin:: Featured Category on Left Section -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 nopadding">
                        <!-- begin:: Left Section -->
                        <div class="banner-container-1 bg-image lazy"
                             style="background: url({{ URL::asset('uploads/' . $directory_category . '/' . $featured['category']['image']) }}) no-repeat; background-size: cover;">
                            <a href="{{ url('/category/' . $featured['category']['sef_url']) }}">
                                <div class="banner-inner-1">
                                    <h2 class="bigtitle">{{ strtoupper($featured['category']['category']) }}</h2>
                                    <div class="bgtitle-sublink">View Catalogue</div>
                                    <div class="divline"></div>
                                </div>
                            </a>
                        </div>
                        <!-- end:: Left Section -->
                    </div>
                    <div class="col-md-6 nopadding">
                        <!-- begin:: Right Section -->
                        <div class="product-section-2">
                            <div class="row">
                                @if(count($featured['products']) > 0)
                                    @foreach($featured['products'] as $key_index => $product)
                                        <?php
                                        $product_image = (count($product['product_image']) > 0) ? $product['product_image'][0] : null;
                                        $image_folder = ($product_image != null && $product_image['image_folder'] != null) ? $product_image['image_folder'] . '/' : '';
                                        $image_name = ($product_image != null) ? $product_image['image'] : 'no-image.jpg';
                                        ?>
                                        <div class="col-md-6">
                                            <a href="{{ route('detail', ['sef_url' => $product['sef_url']]) }}">
                                                <div class="row">
                                                    <div class="col-md-12 productimagesec">
                                                        <img data-src="{{ URL::asset('uploads/' . $directory . '/' . $image_folder . $image_name) }}"
                                                             alt=""
                                                             class="img-fluid lazy">
                                                    </div>
                                                    <div class="col-md-12 product-section-text text-center">
                                                        {{ $product['title'] }}
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <!-- end:: Right Section -->
                    </div>
                </div>
            </div>
            <!-- end:: Featured Category on Left Section -->
        @else
            {{-- odd iteration --}}
            <!-- begin:: Featured Category on Right Section -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 nopadding">
                        <!-- begin:: Left Section -->
                        <div class="product-section-2">
                            <div class="row">
                                @if(count($featured['products']) > 0)
                                    @foreach($featured['products'] as $key_index => $product)
                                        <?php
                                        $product_image = (count($product['product_image']) > 0) ? $product['product_image'][0] : null;
                                        $image_folder = ($product_image != null && $product_image['image_folder'] != null) ? $product_image['image_folder'] . '/' : '';
                                        $image_name = ($product_image != null) ? $product_image['image'] : 'no-image.jpg';
                                        ?>
                                        <div class="col-md-6">
                                            <a href="{{ route('detail', ['sef_url' => $product['sef_url']]) }}">
                                                <div class="row">
                                                    <div class="col-md-12 productimagesec">
                                                        <img data-src="{{ URL::asset('uploads/' . $directory . '/' . $image_folder . $image_name) }}"
                                                             alt=""
                                                             class="img-fluid lazy">
                                                    </div>
                                                    <div class="col-md-12 product-section-text text-center">
                                                        {{ $product['title'] }}
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <!-- end:: Left Section -->
                    </div>
                    <div class="col-md-6 nopadding">
                        <!-- begin:: Right Section -->
                        <div class="banner-container-1"
                             style="background: url({{ URL::asset('uploads/' . $directory_category . '/' . $featured['category']['image']) }}) no-repeat; background-size: cover;">
                            <a href="{{ url('/category/' . $featured['category']['sef_url']) }}">
                                <div class="banner-inner-1">
                                    <h2 class="bigtitle">{{ strtoupper($featured['category']['category']) }}</h2>
                                    <div class="bgtitle-sublink">View Catalogue</div>
                                    <div class="divline"></div>
                                </div>
                            </a>
                        </div>
                        <!-- end:: Right Section -->
                    </div>
                </div>
            </div>
            <!-- end:: Featured Category on Right Section -->
        @endif
    @endforeach
@endif