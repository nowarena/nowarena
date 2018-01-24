{{--@extends('layouts.adminlayout')--}}
@extends('layouts.app')

@section('content')

<div class="container" id="tablegrid">

    @include('layouts.partials.adminnav')

    @include('partials.errors')

    <!-- ADD CAT FORM-->
    <form action="{{ route('cats.store') }}" method="post">

        {{ csrf_field() }}

        <h2 class="sectionTitle">Add</h2>
        <div class="sectionForm">
        <input type="text" size="30" name="title" placeholder="Title">
        <input type="text" size="60" name="description" placeholder="Description">
        Parent Only:<input type="checkbox" name="parent_only" value="1">
        <input class="btn btn-primary" type="submit" value="Add Category">
        </div>

    </form>

    <div style="clear:both;"> </div>


    <!-- EDIT CAT SECTION-->
    <form action="{{ route('cats.index') }}" method="get">
        <h2 class="sectionTitle">Edit</h2>
        <div class="sectionForm">
            <input type='text' name='search' size='20' placeholder='Search' value="{{ $search }}">
            <input type='submit' value='Search' class='btn btn-primary'>
        </div>
    </form>

    @php


    // SORT EDIT CATS LINKS
    $searchQStr = '';
    if (!empty($search)) {
        $searchQStr = "&search=" . urlencode($search);
    }
    $ascActive = '';
    $descActive = '';
    $newActive = '';
    $oldActive = '';
    if ($sort == 'asc') {
        $ascActive = 'activeLink';
    } elseif ($sort == 'old') {
        $oldActive = 'activeLink';
    } elseif ($sort == 'new') {
        $newActive = 'activeLink';
    } else {
        $descActive = 'activeLink';
    }

    echo '<ul style="float:left;padding-left:20px;margin-top:20px;" class="nav nav-pills">';

    echo '<li class="nav-item" style="margin-top:10px;font-weight:bold;">Sort:</li>';

    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $descActive . '" href="/cats?sort=desc' . $searchQStr . '">Alpha Desc</a>';
    echo '</li>';

    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $ascActive . '" href="/cats?sort=asc' . $searchQStr . '">Alpha Asc</a>';
    echo '</li>';

    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $newActive . '" href="/cats?sort=new' . $searchQStr . '">Newest First</a>';
    echo '</li>';

    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $oldActive . '" href="/cats?sort=old' . $searchQStr . '">Oldest First</a>';
    echo '</li>';

    echo '</ul>';

    @endphp

    <div style="clear:both;"></div>

    <div style='background-color:#ffffff;float:right;border:1px solid black;position:absolute;z-index:2;top:60px;right:50px;'>
            @include('layouts.partials.catshierarchy', [
                'parentChildArr' => $parentChildArr,
                'catsCollArr' => $catsCollArr,
                'parentChildFlattenedArr' => $parentChildFlattenedArr
            ])
    </div>

    <!-- EDIT CATS FORM LIST-->
    <table border="0" cellpadding="4" cellspacing="4">
        @foreach( $catsPaginator as $cat )
            <form action="{{ route('cats.update', $cat) }}" method="post">
            <input type="hidden" name="on_page" value="{{$catsPaginator->currentPage()}}">
            <input type="hidden" name="parent_cats_id" value="{{$cat->id}}">
            {{ csrf_field() }}
            <tr>
                <td>
                    <input type="text" size="30" name="title" value="{{ $cat->title }}">
                    <input type="hidden" size="30" name="title_old" value="{{ $cat->title }}">
                </td>
                <td>
                    <input type="text" size="60" name="description" value="{{ $cat->description }}">
                </td>
                <td>
                Parent Only: <input type='checkbox' name='parent_only' value='1'
                @foreach( $parentChildHierArr as $key => $arr )
                    @if ($arr['child_id'] == $cat->id)
                         checked
                         break
                    @endif
                @endforeach
                >
                </td>
                <td>
                    <button class="btn btn-primary" name="edit">Submit Edit</button>
                </td>
                <td>
                    <a class="btn btn-danger" href="{{ route('cats.delete', $cat )}}" onclick="return confirm('Really delete?');">Delete</a>

                </td>
            </tr>
                <tr>
                <td>
                    @php

                    @endphp
                    @include('layouts.partials.child_cats_dd', [
                        'catsCollArr' => $catsCollArr,
                        'selectedId' => 0,
                        'currentId' => $cat->id,
                        'parentChildArr' => $parentChildArr,
                        'parentChildHierArr' => $parentChildHierArr
                    ])
                </td>
                <td colspan="4">
                    @if (!empty($parentChildArr[$cat->id]))
                        @include('layouts.partials.child_cats_ckboxes', [
                            'parentChildArr' => $parentChildArr,
                            'catsCollArr' => $catsCollArr,
                            'currentId' => $cat->id
                        ])
                    @endif
                </td></tr>
                <tr><td colspan='5'><hr></td></tr>


            </form>
        @endforeach
    </table>

    <!-- PAGINATION-->
    {!! $catsPaginator->appends(['sort' => $sort, 'search' => $search])->render() !!}

@php
echo "<pre>";
print_r($parentChildHierArr);
print_r(DB::getQueryLog());
print_r([$sort, $search]);
echo "</pre>";
@endphp

</div>
@endsection