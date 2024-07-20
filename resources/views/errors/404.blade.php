@extends('errors.parent')

@section('img', asset('assets/images/all-img/404.svg'))
@section('title', '404 Not Found')
@section('img', '404.svg')
@section('main-title', 'Page not found')
@section('info',
    'The page you are looking for might have been removed had its name changed or is temporarily
    unavailable.')
