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


        <div style='float:left;'>
        <input type='text' name='source_user_id' placeholder='source_user_id'>
        <input type='text' name='username' placeholder='username'>
        <input type='text' name='site' placeholder='sitename'>
        <input type='hidden' name='action' value='add'>

        <div class='isActive'>
            <label>Is active: <input type='hidden' name='is_active' value='0'></label>
        </div>

        <div class='isPrimary'>
            <label>Is primary: <input type='hidden' name='is_primary' value='0'></label>
        </div>

        <div class='useAvatar'>
            <label>Use avatar: <input type='hidden' name='use_avatar' value='0'></label>
         </div>
        <div style='text-align:center;margin-top:2px;'>
            <input size=100 type='text' name='avatar' placeholder='avatar url'>
        </div>

        <div style='clear:both;'></div>

        </div>
    <div class='submitBtn' style='float:left;'>
        <button class="btn btn-primary" name="edit">Add</button>
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