@extends('layouts.app')
 
@section('content')
    <h2>Edit Category</h2>
 
    {!! Form::model($linkObj, ['method' => 'PATCH', 'route' => ['links.update', $linkObj->id]]) !!}
        @include('links/partials/_form', [
            'name' => $linkObj->name,
            'link' => $linkObj->link,
            'id' => $linkObj->id
            ])
    {!! Form::close() !!}

{{--@include('admin/partials/_footer')--}}

@endsection