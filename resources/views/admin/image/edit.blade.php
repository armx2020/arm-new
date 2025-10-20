@extends('admin.layouts.app')
@section('content')
    @livewire('admin.edit-entity', ['entity' => $entity])
@endsection
