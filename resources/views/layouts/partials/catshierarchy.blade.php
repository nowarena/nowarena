@php

echo "<pre>";print_r($parentChildFlattenedArr);echo "</pre>";

foreach($parentChildFlattenedArr as $id => $arr) {

    echo "<ul class='ulAdmin'><li class='liAdmin'>" . getName($id, $catsColl) . "</li>";
    echo "<ul class='ulAdmin'>";
    displayCats($arr, $catsColl);
    echo "</ul></ul>";

}


@endphp