@extends('frontend.layouts.layout_master')
@section('content')
    <!-- begin:: Slider Section -->  
    @include('frontend.home.includes.homepage_slider_section')
    <!-- end:: Slider Section -->   
    @include('frontend.layouts.includes.alert_popup')
    <!-- begin:: Homepage Elements Section -->

    @include('frontend.home.includes.homepage_explore_courses')

    @include('frontend.home.includes.homepage_how_it_work')
    @include('frontend.home.includes.homepage_how_question')

    <!-- end:: Homepage Elements Section -->
    <div class="clearfix"></div>   
@endsection
