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
        @foreach( $itemsColl as $item )
            <form id="form_{{ $item->id }}" action="{{ route('items.update', $item) }}" method="post">
            <input type="hidden" name="on_page" value="{{$itemsColl->currentPage()}}">
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
                <td colspan='2'><div id='addCatsDDId_{{ $item->id }}'></div></td>
            </tr>

            </form>
        @endforeach
    </table>

    {!! $itemsColl->appends(['sort' => $sort, 'search' => $search])->render() !!}

<pre>
        {!! $itemsCatsColl !!}
</pre>
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
echo "</pre>";
@endphp

<script>

var itemsArr = @php echo json_encode($itemsArr); @endphp;
// catsArr
var catsArr = @php echo json_encode($catsArr); @endphp;
// selected arr  keys: [items_id][cats_id] = items_cats.id
var itemsCatsArr = @php echo json_encode($itemsCatsArr); @endphp;

$(document).ready(function() {

    // run first time page loads. builds category dds
    console.log("catsArr", catsArr);
    console.log("itemsArr", itemsArr);
    console.log("itemsCatsArr", itemsCatsArr);

    var count = Object.keys(itemsCatsArr).length;
    // selectedDDArr is an array of all the itemscats.ids selected for a given cats.id and items.id
    var selectedDDArr = new Array();
    if (false && count > 0) {
        for(var itemsId in itemsCatsLookupArr) {
            itemsCatsId = null;
            catsId = null;
            for(var catsId in itemsCatsLookupArr[itemsId]) {
                console.log("catsId", catsId);
                console.log("itemsCatsLookupArr[catsId]", itemsCatsLookupArr[catsId]);
                var itemsCatsIdArr = itemsCatsLookupArr[itemsId][catsId];
                console.log("itemsCatsIdArr", itemsCatsIdArr);
                var itemsCatsObjArr = new Array();
                selectedDDArr[catsId] = new Array();
                for (var i in itemsCatsIdArr) {
                    var itemsCatsId = itemsCatsIdArr[i];
                    console.log("itemsCatsId", itemsCatsId);
                    //var itemsCatsObj = {items_id:itemsId, cats_id:catsId, items_cats_id:itemsCatsId};
                    //if (typeof itemsCatsObjArr[
                    itemsCatsObjArr.push(itemsCatsObj);
                }
                console.log("A itemsCatsObjArr", itemsCatsObjArr);

                selectedDDArr[catsId].push(itemsCatsObjArr);
                //mngCategoryDD(itemsCatsObjArr);
            }
            console.log("selectedDDArr", selectedDDArr);
            ddArr = buildDDArrs(selectedDDArr);
        }
        console.log("ddArr", ddArr);
    }

    var availableArr = buildAvailableArr();
    console.log("availableArr", availableArr);

    // build an array for each drop down. items.id - cats.id - itemscats.id
    function buildAvailableArr() {

        var availableArr = [];
        // loop over each item
        for(var itemsId in itemsArr) {
            console.log(" --------------- itemsId", itemsId);
            // If an item doesn't have any categories available to it, make all categories available
            if (typeof itemsCatsArr[itemsId] === 'undefined' || itemsCatsArr[itemsId].length === 0) {
                availableArr[itemsId] = [];
                availableArr[itemsId] = catsArr;
                console.log("no cats for itemsId", itemsId);
                console.log("added all cats to dd", availableArr);
                continue;
            } else {
                console.log("itemsCatsArr[itemsId] has values:", itemsCatsArr[itemsId]);
            }

            // loop over each category for each item. If an item already belongs to a category, skip category
            var tmpCatsArr = JSON.parse(JSON.stringify(catsArr));
            for(var catsId in catsArr) {
                // find the items.id in itemsCatsArr and build an array of categories not already selected
                 if (typeof itemsCatsArr[itemsId][catsId] !== 'undefined') {
                    // the item already has this category associated with it, remove category from available array
                    delete tmpCatsArr[catsId];
                 }
            }

            availableArr[itemsId] = [];
            availableArr[itemsId] = (tmpCatsArr);

        }

        return availableArr;
    }

    function mngCategoryDD(itemsCatsObj, insertId, deleteId) {

        // Manage containing ROW
        if (deleteId) {
            // remove the row
            $("#updateCategory_" + deleteId).remove();
        } else if (insertId) {
            var rowId = insertId;
        } else {
            var rowId = itemsCatsObj.items_id;
            console.log("rowId:", rowId);
        }

        // if the category was new and not an update, add the new row to hold the dd
        if ($("#catsDDId_" + rowId).length == 0) {
            console.log("no existing row found for " + rowId + ", building new one");
            var trRow = "<tr id='updateCategory_" + rowId + "'>";
            trRow+= "<td class='tdTitle'>Update Category</td>";
            trRow+= "<td colspan='2'>";
            trRow+= "<div id='catsDDId_" + rowId + "'></div>";
            trRow+= "</td>";
            trRow+= "</tr>";
            $("#categoryTable").append(trRow);
        }



        buildCategoryDD(itemsCatsObj, 'update');

        // Manage drop downs
        //
        // rebuild 'Associate Category'
        // if all categories used, replace display
        // console.log("itemsCatsArr.length", itemsCatsArr.length);
        // console.log("catsArr.length", catsArr.length);
        // console.log("catsArr", catsArr);
        // console.log("itemsCatsArr", itemsCatsArr);
        // var numCatsItemHas = 0;
        // for(var i in itemsCatsArr) {
        //     if (itemsCatsArr[i].items_id == itemsCatsObj.items_id) {
        //         numCatsItemHas++;
        //     }
        // }
        // if (numCatsItemHas == catsArr.length) {
        //     $("#addCatsDDId_" + itemsCatsObj.items_id).html("All Categories Used");
        // } else {
        //     var obj = {};
        //     obj.id = 0;
        //     obj.items_id = itemsCatsObj.items_id;
        //     obj.cats_id = 0;
        //     console.log("add obj", obj);
        //     buildCategoryDD(obj, 'add');
        // }
        //
        // // rebuild all 'Update Category' dd
        // for(var i in itemsCatsArr) {
        //     buildCategoryDD(itemsCatsArr[i], 'update');
        // }

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

    // Delete, Insert or Update json on page
    function updateItemsCatsJson(response, insertId, deleteId) {

        // it was an insert into items_cats table, new category associated for item, not an update, so insert into json
        if (insertId) {
            console.log("insertId", insertId);
            var obj = [];
            obj.id = insertId;
            obj.cats_id = response.cats_id;
            obj.items_id = response.items_id;
            itemsCatsArr.push(obj);
            return;
        }

        for(var i in itemsCatsArr) {
            if (deleteId && itemsCatsArr[i].id == deleteId) {
                console.log("deleted id", deleteId);
                itemsCatsArr.splice(i, 1);
                break;
            } else if (itemsCatsArr[i].id == response.items_cats_id) {
                itemsCatsArr[i].cats_id = response.cats_id;
                itemsCatsArr[i].items_id = response.items_id;
                break;
            }
        }
        console.log("updateItemsCatsJson update final", JSON.stringify(itemsCatsArr));
    }

    function buildCategoryArrForDD(itemsCatsObj) {

    }

    function buildCategoryDD(itemsCatsObj, action) {

        console.log("itemsCatsObj B:", itemsCatsObj);
        console.log("action", action);

        var optionStr = '';
        for(var i in catsArr) {
            var selectedInOtherDD = false;
            for(var j in itemsCatsArr) {
                if (catsArr[i].id == itemsCatsArr[j].cats_id && itemsCatsObj.id != itemsCatsArr[j].id) {
                    // if already selected, but not for this dd
                    selectedInOtherDD = true;
                    break;
                }
            }
            if (selectedInOtherDD == false) {
                optionStr+= "<option value='" + catsArr[i].id + "' ";
                if (catsArr[i].id == itemsCatsObj.cats_id) {
                    optionStr+= "selected ";
                }
                optionStr+= ">" + catsArr[i].title + "</option>";
            }
        }
        if (optionStr != '') {
            var selectMsg = (itemsCatsObj.id == 0) ? "Associate Category" : "Delete This Category";
            ddStr = "<select class='catsDD' data-itemscatsid='" + itemsCatsObj.id + "' data-itemsid='" + itemsCatsObj.items_id + "'>";
            ddStr+= '<option value="0"';
            if (itemsCatsObj.id == 0) {
                ddStr+= ' selected';
            }
            ddStr+= '>' + selectMsg + '</option>';
            ddStr+= optionStr;
            ddStr+= "</select>";

            // add the drop down
            if (action == 'add') {
                $("#addCatsDDId_" + itemsCatsObj.id).html(ddStr);
            } else {
                $("#catsDDId_" + itemsCatsObj.id).html(ddStr);
            }
        }
    }


});


</script>

</div>
@endsection