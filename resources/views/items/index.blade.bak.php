@extends('layouts.adminlayout')

@section('content')

<div class="container" id="tablegrid">

    @include('layouts.partials.adminnav')

    @include('layouts.partials.errors')

    <form action="{{ route('items.store') }}" method="post">

        {{ csrf_field() }}

        <h2 class="sectionTitle">Add</h2>
        <div class="sectionForm">
        <input type="text" size="30" name="title" placeholder="Title">
        <input type="text" size="60" name="description" placeholder="Description">
        <input class="btn btn-primary" type="submit" value="Add Item">
        </div>

    </form>

    <div style="clear:both;"> </div>

    <form action="{{ route('items.index') }}" method="get">
        <h2 class="sectionTitle">Edit</h2>
        <div class="sectionForm">
            <input type='text' name='search' size='20' placeholder='Search' value="{{ $search }}">
            <input type='submit' value='Search' class='btn btn-primary'>
        </div>
    </form>

    @php

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

    echo '<ul style="padding-left:20px;margin-top:20px;" class="nav nav-pills">';

    echo '<li class="nav-item" style="margin-top:10px;font-weight:bold;">Sort:</li>';

    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $descActive . '" href="/items?sort=desc' . $searchQStr . '">Alpha Desc</a>';
    echo '</li>';

    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $ascActive . '" href="/items?sort=asc' . $searchQStr . '">Alpha Asc</a>';
    echo '</li>';

    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $newActive . '" href="/items?sort=new' . $searchQStr . '">Newest First</a>';
    echo '</li>';

    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $oldActive . '" href="/items?sort=old' . $searchQStr . '">Oldest First</a>';
    echo '</li>';

    echo '</ul>';

    @endphp

    <div style="clear:both;"></div>
    <table id='categoryTable' border="0" cellpadding="4" cellspacing="4">
        @foreach( $items as $item )
            <form id="form_{{ $item->id }}" action="{{ route('items.update', $item) }}" method="post">
            <input type="hidden" name="on_page" value="{{$items->currentPage()}}">
            {{ csrf_field() }}
            <tr>
                <td>
                    <input type="text" size="30" name="title" value="{{ $item->title }}">
                    <input type="hidden" size="30" name="title_old" value="{{ $item->title }}">
                </td>
                <td>
                    <input type="text" size="60" name="description" value="{{ $item->description }}">
                </td>
                <td>
                    <button class="btn btn-primary" name="edit">Submit Edit</button>
                </td>
                <td>
                    <a class="btn btn-danger" href="{{ route('items.delete', $item )}}" onclick="return confirm('Really delete?');">Delete</a>

                </td>
            </tr>
            <tr id="associateCategory">
                <td class="tdTitle">Associate Category</td>

                @php

                $hasAvailableCategories = false;// if all categories are used and dd is empty, don't display
                foreach($catsArr as $cat) {
                    // see if cat_id has already been selected in another drop down for the item, if so, don't display
                    // that category for this drop down. If all categories are used in other drop downs,
                    // don't offer dd and display msg
                    $alreadySelected = false;
                    foreach($itemsCatsArr as $itemsCats) {
                        if ($itemsCats->cats_id == $cat->id) {
                            $alreadySelected = true;
                        }
                    }
                    if ($alreadySelected) {
                        continue;
                    }
                    $hasAvailableCategories = true;
                }

                @endphp
                {{--item_cat_id is the primary key in items_cats join table--}}
                {{-- the dd requires a unique key itemsCatsId of the selected cat, catsArr to loop over,
                    and itemsId of the current item --}}
                <td colspan="2">
                @if ($hasAvailableCategories)
                    @include('layouts.partials.catsdd', [
                        'itemsCatsId' => 0,
                        'catsArr' => $catsArr,
                        'itemsId' => $item->id,
                        'selectedCatsId' => 0,
                        'itemsCatsArr' => $itemsCatsArr,
                        'ddMsg' => 'Associate Category'
                    ])
                @else
                    <div  id="catsDDId_0">
                        All Categories Used
                    </div>
                @endif
                </td>
            </tr>
            @foreach($itemsCatsArr as $i => $itemsCats)
                <tr id="updateCategory_{{ $itemsCats->id }}">
                    <td class="tdTitle">Update Category</td>
                    <td colspan="2">
                        @include('layouts.partials.catsdd', [
                            'itemsCatsId' => $itemsCats->id,
                            'catsArr' => $catsArr,
                            'itemsId' => $item->id,
                            'selectedCatsId' => $itemsCats->cats_id,
                            'itemsCatsArr' => $itemsCatsArr,
                            'ddMsg' => 'Delete This Category'
                        ])
                    </td>
                </tr>
            @endforeach
            </form>
        @endforeach
    </table>

    {!! $items->appends(['sort' => $sort, 'search' => $search])->render() !!}

<pre>
        {!! $itemsCatsArr !!}
</pre>
@php
echo "<pre>";

print_r(DB::getQueryLog());
echo "sort:\n";
print_r($sort);
echo "search:\n";
print_r($search);
echo "itemsCatsArr:\n";
print_r($itemsCatsArr->toArray());
echo "catsArr:\n";
print_r($catsArr->toArray());
echo "</pre>";
@endphp

<script>
    // catsArr
    var catsArr = {!! $catsArr !!};
    // selectedCatsArr
    var itemsCatsArr = {!! $itemsCatsArr !!};

</script>

</div>
@endsection