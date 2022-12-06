@extends('layouts.master')
@section('title')Bad request Invalid Url  | SAG RTA @stop
@section('content')

 <section class="main-page-sec" style="padding-top:150px; padding-bottom:150px;">
  <div class="container">
  <div class="row">
    <div class="col-md-12 title-heading">
      <h2>@if(Session::has('msg')) {!! session('msg') !!} @endif</h2>
     </div>
  </div>
  </div>
  </section>
@stop

      

