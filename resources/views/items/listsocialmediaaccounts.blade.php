@extends('layouts.app')
@section('content')

<div class="container" id="tablegrid">

@include('layouts.partials.adminnav')

<h2 class="sectionTitle">Social Media</h2>
    <div style='clear:both;'></div>
    
<form action="/items/0/updatesocialmediaaccounts" method="get">

    {{ csrf_field() }}

    <h2 class="sectionTitle">Add</h2>
    <div style='clear:both;'></div>

    <div style='width:800px;'>
        <div class='submitBtn' style='float:right;'>
            <button class="btn btn-primary" name="edit">Add</button>
        </div>
        <div>
        <input type='text' name='source_user_id' placeholder='source_user_id'>
        <input type='text' name='username' placeholder='username'>
        yelp:<input type='checkbox' name='site' value='yelp.com'> |
        twitter:<input type='checkbox' name='site' value='twitter.com'> |
         instagram:<input type='checkbox' name='site' value='instagram.com'>
        <input type='hidden' name='action' value='add'>
        <input type='hidden' name='search' value='{{$search}}'>

        <div class='isActive'>
            <input type='hidden' name='is_active' value='0'>
        </div>

        <div class='isPrimary'>
            <input type='hidden' name='is_primary' value='0'>
        </div>

        <div class='useAvatar'>
            <input type='hidden' name='use_avatar' value='0'>
         </div>
        <div style='margin:2px 0px 2px 0px;'>
            <input size=100 type='text' name='avatar' placeholder='avatar url'>
        </div>

        <div style='clear:both;'></div>

        </div>
    </div>



</form>

    <div style='clear:both;'></div>
    <hr>

@include('items.partials.search', [
    'route' => route('items.index'),
    'search' => $search
])

    <div style='clear:both;'></div>
<br>
<div class='itemTitle'> &nbsp; </div>
<div class='socialSiteName'> </div>
<div class='accountRemove'>Remove/Add</div>
<div class='isActive'>Is Active</div>
<div class='isPrimary'>Is Primary</div>
<div class='useAvatar'>Use Avatar</div>
<div class='submitBtn'> &nbsp; </div>

<div style='clear:both;'></div>

@foreach( $itemsColl as $item )

    <div class='itemTitle'><a href='/items?search=@php echo urlencode($item->title); @endphp'>{{$item->title}}</a></div>
    @include('items.partials.socialmediaaccounts', [
        'item' => $item,
        'socialMediaAssocAccountsArr' => $socialMediaAssocAccountsArr,
        '$socialMediaAccountsColl' => $socialMediaAccountsColl,
        'searchCatsId' => $searchCatsId,
        'search' => $search,
        'sort' => $sort
    ])


@endforeach

<div style='clear:both;'></div>
{!! $itemsColl->appends(['sort' => $sort, 'search' => $search, 'cats_id' => $searchCatsId])->render() !!}

</div>
@endsection