@php

foreach($catsArr as $catsId => $title) {
    echo $title . ":<input type='checkbox' name='selectedCatsArr[$itemsId][]' value='$catsId'";
    if (!empty($itemsCatsArr[$itemsId][$catsId])) {
        echo " checked";
    }
    echo "> | ";
}

@endphp