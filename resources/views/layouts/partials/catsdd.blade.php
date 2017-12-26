<div  id="catsDDId_{{$itemsCatsId}}">
<select class="catsDD" data-itemscatsid="{{ $itemsCatsId }}" data-itemsid="{{ $itemsId }}">
    <option value="0">{{ $ddMsg }}</option>
    @php

    $hasCategories = false;// if all categories are used and dd is empty, don't display
    foreach($catsArr as $cat) {
        // see if cat_id has already been selected in another drop down for the item, if so, don't display it here
        $alreadySelected = false;
        foreach($itemsCatsArr as $itemsCats) {
            if ($itemsCats->cats_id == $cat->id && $cat->id != $selectedCatsId) {
                $alreadySelected = true;
            }
        }
        if ($alreadySelected) {
            continue;
        }
        $hasCategories = true;
        echo '<option value="' . $cat->id . '" ';
        if ($cat->id == $selectedCatsId) {
            echo " selected ";
        }
        echo '>' . $cat->title . ' </option>';
    }

    @endphp
</select>
</div>

{{--@php--}}
{{--print_r($catsArr->toArray());--}}
{{--var_dump($itemsCatsId);--}}
{{--var_dump($itemsId);--}}
{{--@endphp--}}