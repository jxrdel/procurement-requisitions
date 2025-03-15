@extends('errors::minimal')

@section('title', __('Maintenance Mode'))
@section('code', '503')
@section('message', __("Changes underway! We'll be back online soon."))
