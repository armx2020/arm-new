@extends('admin.layouts.app')
@section('content')
    @livewire('admin.edit-category', ['category' => $category])
@endsection
