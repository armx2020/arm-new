@extends('admin.layouts.app')
@section('content')
    @livewire('admin.edit-offer', ['offer' => $offer])
@endsection
