@extends('backend.layouts.errors')

@section('content')
    <div class="kt-grid kt-grid--ver kt-grid--root">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid  kt-error-v1"
             style="background-image: url({{ URL::asset('assets/backend/media/error/bg1.jpg') }});">
            <div class="kt-error-v1__container">
                <h1 class="kt-error-v1__text">Under Maintenance</h1>
                <p class="kt-error-v1__desc">
                    Oops! We are currently improving our site.
                    <br>
                    We'll be back soon. Please stay tuned.
                </p>
            </div>
        </div>
    </div>
@endsection
