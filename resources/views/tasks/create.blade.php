@extends('layouts.adminlayout')

@section('content')
    <div class="container">

        @include('layouts.partials.adminnav')

        <form action="{{ route('tasks.store') }}" method="post">

            {{ csrf_field() }}

            <input type="test" size="50" name="title"></input>

            <input class="btn btn-primary" type="submit">

        </form>
    </div>
@endsection