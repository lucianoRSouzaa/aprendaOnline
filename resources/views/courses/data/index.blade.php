@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
@endpush

@section('header')
    <div class="container">
        <div class="d-flex justify-content-between">
            <a href="{{ auth()->user()->isCreator() ? route('courses.creator') : route('courses.viewer') }}" class="logo">
                @if (Cookie::get('theme_preference', 'light') === 'dark')
                    <img class="logo2" src="{{ asset('images/logoMenu.png') }}" alt="">
                @else
                    <img class="logo2" src="{{ asset('images/logoMenu2.png') }}" alt="">
                @endif
            </a>       
            <div class="search-container d-flex justify-content-end align-items-center">
                <a href="{{ route('modules.index', $course->slug) }}">{{ trans('modulesAndLessons') }}</a>
                <a class="active" href="{{ route('course.data.index', $course->slug) }}">{{ trans('viewCourseData') }}</a>
                <a href="{{ route('course.config', $course->slug) }}">{{ trans('settings') }}</a>
            </div>    
        </div>
    </div>
@endsection

@section('main')
    <div class="container">
        <div class="col-table">
            <div class="row justify-content-center">
                <h2 class="text-center mt-3 mb-3">{{ trans('WhatWouldULikeToSee') }}</h2>
                <div class="col-md-6 col-sm-11 mb-4">
                    <div class="card">
                        <h5 class="card-title card-header"><strong>{{ trans('courseOverview') }}</strong></h5>
                        <div class="card-body">
                            <p class="card-text">{{ trans('courseOverviewDescription') }}</p>
                            <a href="{{ route('course.data.overview', $course->slug) }}" class="btn btn-primary stretched-link">{{ trans('view') }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-11 mb-4">
                    <div class="card">
                        <h5 class="card-title card-header"><strong>{{ trans('coursePerformanceOverTime') }}</strong></h5>
                        <div class="card-body">
                            <p class="card-text">{{ trans('coursePerformanceDescription') }}</p>
                            <a href="{{ route('course.data.performance', $course->slug) }}" class="btn btn-primary stretched-link">{{ trans('view') }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-11">
                    <div class="card">
                        <h5 class="card-title card-header"><strong>{{ trans('ratingsAndFeedback') }}</strong></h5>
                        <div class="card-body">
                            <p class="card-text">{{ trans('ratingsAndFeedbackDescription') }}</p>
                            <a href="{{ route('course.data.reviews', $course->slug) }}" class="btn btn-primary stretched-link">{{ trans('view') }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-11">
                    <div class="card">
                        <h5 class="card-title card-header"><strong>{{ trans('studentProgress') }}</strong></h5>
                        <div class="card-body">
                            <p class="card-text">{{ trans('studentProgressDescription') }}</p>
                            <a href="{{ route('course.data.progress', $course->slug) }}" class="btn btn-primary stretched-link">{{ trans('view') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
