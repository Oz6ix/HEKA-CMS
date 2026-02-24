@extends('backend.layouts.errors')

@section('content')
    <div class="kt-grid kt-grid--ver kt-grid--root">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid  kt-error-v1"
             style="background-image: url({{ URL::asset('assets/backend/media/error/bg1.jpg') }});">
            <div class="kt-error-v1__container">
                <h1 class="kt-error-v1__number">405</h1>
                <p class="kt-error-v1__desc">
                    Oops! Method not allowed.
                    <br>
                    You might have used an incorrect request method.
                    <br><br>
                    <a href="{{ url(\Config::get('app.app_route_prefix') . '/dashboard') }}" class="kt-home-button">
                        Return home </a>
                </p>
            </div>
        </div>
    </div>
@endsection
