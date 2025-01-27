@extends('layout')

@section('title')
    <title>Users | PRA</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-7">
                <h1 class="h3 mb-0 text-gray-800" style="margin: auto"><strong><i class="fa-solid fa-circle-question"></i>
                        &nbsp;
                        User Manual</strong></h1>
            </div>

            <div class="row mt-5">
                <iframe src="{{ asset('manual.pdf') }}" width="100%" height="1000px"></iframe>

            </div>

        </div>
    </div>
@endsection
