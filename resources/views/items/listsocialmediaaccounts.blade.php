@extends('layouts.app')
@section('content')

<div class="container" id="tablegrid">

@include('layouts.partials.adminnav')

<h2 class="sectionTitle">Social Media</h2>


@include('items.partials.search', [
    'route' => route('items.index'),
    'search' => $search
])


<br>
<div class='itemTitle'> &nbsp; </div>

<div class='accountRemove'>Remove/Add</div>
<div class='isActive'>Is Active</div>
<div class='isPrimary'>Is Primary</div>
<div class='useAvatar'>Use Avatar</div>
<div class='submitBtn'> &nbsp; </div>

<div style='clear:both;'></div>

@foreach( $itemsColl as $item )
    <form id="form_{{ $item->id }}" action="{{ route('items.updatesocialmediaaccounts', $item) }}" method="get" class='socialMediaRow'>
    <input type="hidden" name="on_page" value="{{$itemsColl->currentPage()}}">
    <input type="hidden" name="items_id" value="{{ $item->id }}">
    {{ csrf_field() }}
    <div class='itemTitle' style='float:left;'>{{$item->title}}</div>
    @include('items.partials.socialmediaaccounts', [
        'item' => $item,
        'socialMediaAssocAccountsArr' => $socialMediaAssocAccountsArr,
        '$socialMediaAccountsColl' => $socialMediaAccountsColl
    ])
    <div class='submitBtn'>
    <button class="btn btn-primary" name="edit">Submit Edit</button>
    </div>
    </form>
    <div style='clear:both;'></div>

@endforeach

<div style='clear:both;'></div>
{!! $itemsColl->appends(['sort' => $sort, 'search' => $search])->render() !!}

</div>
@endsection