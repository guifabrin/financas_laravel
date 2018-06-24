@extends('layouts.app')
@section('stylesheet')
  {{-- Styles --}}
  @if(config('laravelusers.enableBootstrapCssCdn'))
    <link rel="stylesheet" type="text/css" href="{{ config('laravelusers.bootstrapCssCdn') }}">
  @endif
  @if(config('laravelusers.enableAppCss'))
    <link rel="stylesheet" type="text/css" href="{{ asset(config('laravelusers.appCssPublicFile')) }}">
  @endif

  @yield('template_linked_css')
@endsection
@section('script')
  {{-- Scripts --}}
  @if(config('laravelusers.enablejQueryCdn'))
    <script src="{{ asset(config('laravelusers.jQueryCdn')) }}"></script>
  @endif
  @if(config('laravelusers.enableBootstrapPopperJsCdn'))
    <script src="{{ asset(config('laravelusers.bootstrapPopperJsCdn')) }}"></script>
  @endif
  @if(config('laravelusers.enableBootstrapJsCdn'))
    <script src="{{ asset(config('laravelusers.bootstrapJsCdn')) }}"></script>
  @endif
  @if(config('laravelusers.enableAppJs'))
    <script src="{{ asset(config('laravelusers.appJsPublicFile')) }}"></script>
  @endif
  @include('laravelusers::scripts.toggleText')

  @yield('template_scripts')
@endsection