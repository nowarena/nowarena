{{--@extends('layouts.adminlayout')--}}
@extends('layouts.app')
@section('content')

<div class="container" id="tablegrid">

    @include('layouts.partials.adminnav')

    @include('partials.errors')

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

    <!--h2 class="sectionTitle">Edit</h2-->

    @include('items.partials.search', [
        'search' => $search,
        'catsArr' => $catsArr,
        'searchCatsId' => $searchCatsId
    ])

    <div style="clear:both;"></div>
    <div id='categoryTable'>
        @foreach( $itemsColl as $item )
            <form class='catRow' id="form_{{ $item->id }}" action="{{ route('items.update', $item) }}" method="post">
            <input type="hidden" name="on_page" value="{{$itemsColl->currentPage()}}">
            <input type="hidden" name="items_id" value="{{ $item->id }}">
            <input type="hidden" name="cats_id" value="{{ $searchCatsId }}">
            {{ csrf_field() }}
            <div class='tr'>
                <div class='td'>
                    @php echo getSocialMediaHyperLink($socialMediaAssocAccountsArr, $item->id); @endphp
                    <input type="text" size="30" name="title" value="{{ $item->title }}">
                    <input type="hidden" size="30" name="title_old" value="{{ $item->title }}">
                </div>
                <div class='td'>
                    <input type="text" size="60" name="description" value="{{ $item->description }}">
                </div>
                <div class='td'>
                    <button class="btn btn-primary" name="edit">Submit Edit</button>
                </div>
                <div class='td'>
                    <a class="btn btn-danger" href="{{ route('items.delete', $item, ['page' => $itemsColl->currentPage()] )}}" onclick="return confirm('Really delete?');">Delete</a>

                </div>
                <div class='td'><a href='/items/listsocialmediaaccounts?search={{$item->title}}'>Social item id {{ $item->id }}</a></div>
                <div class='td'> | <a target=_blank href='http://www.yelp.com/search?find_desc={{$item->title}}&find_loc=90291'>Yelp</a></div>
            </div>
            <div style='clear:both;'></div>
            <div class='tr'>
                <div class='td'  id='addCatsCheckboxes_{{ $item->id }}'>

                    @php
                    //echo printR($parentChildFlattenedArr);
                    //echo printR($itemsCatsColl);
                    if (1) {
                        displayItemsCatsCkBoxes($parentChildFlattenedArr, '', $catsCollArr, $itemsCatsColl, $item->id);
                    } else if (isOneDimension($parentChildFlattenedArr)) {
                        displayItemsCatsCkBoxes($parentChildFlattenedArr, '', $catsCollArr, $itemsCatsColl, $item->id);
                    } else {
                        $count = 0;
                        foreach($parentChildFlattenedArr as $id => $arr ) {
                            echo ($count !== 0) ? "<br>" : '';
                            echo getName($id, $catsCollArr);
                            displayItemsCatsCkBoxes($arr, '', $catsCollArr, $itemsCatsColl, $item->id);
                            $count++;
                        }
                    }
                    @endphp

                    {{--@include('layouts.partials.catscheckboxes', [--}}
                        {{--'catsArr' => $catsArr,--}}
                        {{--'itemsId' => $item->id,--}}
                        {{--'selectedCatsId' => 0,--}}
                        {{--'parentChildFlattenedArr' => $parentChildFlattenedArr--}}
                    {{--])--}}
                </div>
            </div>
            <div style='clear:both;'></div>
            </form>
            <div style='clear:both;'></div>

        @endforeach
    </div>
    <div style='clear:both;'></div>
    {!! $itemsColl->appends(['sort' => $sort, 'search' => $search, 'cats_id' => $searchCatsId])->render() !!}

{{--<pre>--}}
        {{--{!! $itemsCatsColl !!}--}}
{{--</pre>--}}
@php
echo "<pre>";

//print_r(DB::getQueryLog());
echo "sort:\n";
print_r($sort);
echo "search:\n";
print_r($search);
//echo "itemsCatsColl:\n";
//print_r($itemsCatsColl);
echo "catsArr:\n";
print_r($catsArr);
echo "itemsArr:\n";
//$itemsArr = $itemsColl->toArray()['data'];
print_r($itemsArr);
//echo "itemsColl:\n";
//print_r($itemsColl->toArray());

echo "itemsCatsColl\n";
print_r($itemsCatsColl);

echo "parentChildHierArr\n";
print_r($parentChildHierArr);

echo "itemsCatsArr\n";
print_r($itemsCatsArr);

echo "parentChildFlattenedArr\n";
print_r($parentChildFlattenedArr);

echo "</pre>";
@endphp

<script>

var itemsArr = @php echo json_encode($itemsArr); @endphp;
// catsArr
var catsArr = @php echo json_encode($catsArr); @endphp;
// selected arr  keys: [items_id][cats_id] = items_cats.id
var itemsCatsArr = @php echo json_encode($itemsCatsArr); @endphp;

// TURNED OFF
if (0) {
$(document).ready(function() {

    // run first time page loads. builds category dds
    console.log("catsArr", catsArr);
    console.log("itemsArr", itemsArr);
    console.log("itemsCatsArr", itemsCatsArr);

    // add category checkboxes
    mngCategoryCheckboxes();

    function mngCategoryCheckboxes() {

        for(var itemsId in itemsArr) {
            var ckboxes = '';
            for (var catsId in catsArr) {
                ckboxes+= catsArr[catsId] + ": <input type='checkbox' name='catsArr[" + itemsId + "][]' value='" + catsId + "' ";
                if (typeof itemsCatsArr[itemsId] !== 'undefined' && typeof itemsCatsArr[itemsId][catsId] !== 'undefined') {
                    ckboxes+= "checked";
                }
                ckboxes+="> | ";
            }
            $("#addCatsCheckboxes_" + itemsId).html(ckboxes);
        }

    }




    //
    // UPDATE CATEGORY ON CHANGE
    //
    $(document).on('change', '.catsDD', function(){

        //$('.catsDD').change(function () {
        console.log("change function", JSON.stringify(itemsCatsArr));
        var selectedCatId = $(this).val();
        var itemsCatsId = $(this).data("itemscatsid");
        var itemsId = $(this).data("itemsid");
        if (itemsCatsId == 0 && itemsId == 0) {
            // they've selected 'Select Category' option which has no values
            console.log("Select Category selected. Returning doing nothing");
            return;
        }
        var data = {cats_id: selectedCatId, items_id: itemsId, items_cats_id:itemsCatsId};
        //("#form_" + itemId).('input:hidden[name="_token"]').val();
        var csrfToken = $('input:hidden[name="_token"]').val();
        //console.log("csrfToken", csrfToken);
        console.log("posting data:", data);
        updateItemsCats(data, csrfToken);

    });

    function updateItemsCats(data, csrfToken) {

        var request = $.ajax({
            method: "POST",
            url: '/items/updateitemcat',
            data: data,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
        });
        request.done(function (response) {
            // update json array embedded in page with change
            console.log('response', response);
            var insertId = false;
            var deleteId = false;
            if (response.items_cats_id > 0 && data.items_cats_id == 0) {
                // it was an insert if data.items_cats_id is 0 (items_cats_id hasn't been created yet)
                // the response.items_cats_id is the primary key from the server of items_cats table
                insertId = response.items_cats_id;
            } else if (response.cats_id == 0 && data.cats_id == 0) {
                // if submitted cats_id is 0 and response cats_id is 0, the items_cats_id was deleted
                var deleteId = response.items_cats_id;
            }

            if (deleteId || insertId) {
                updateItemsCatsJson(response, insertId, deleteId);
                mngCategoryDD(response, insertId, deleteId);
            }
            //addCatsChild();
        });
        request.fail(function (jqXHR, textStatus) {
            //alert("Request failed: " + textStatus);
        });
    }



});
}

</script>

</div>
@endsection