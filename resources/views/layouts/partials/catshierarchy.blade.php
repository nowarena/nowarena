@php

echo "<pre>";print_r($parentChildFlattenedArr);echo "</pre>";

foreach($parentChildFlattenedArr as $id => $arr) {

    echo "<ul><li>" . getName($id, $catsColl) . "</li>";
    echo "<ul>";
    displayCats($arr, $catsColl);
    echo "</ul></ul>";

}



function displayCats($arr, $catsColl, $str = '') {

    if (!is_array($arr)) {
        echo "<li>" . getName($arr, $catsColl) . "</li>";
    } else {
        foreach($arr as $id => $tmp) {
            echo "<ul>";
            echo "<li>" . getName($id, $catsColl) . "</li>";
            if (is_array($tmp)) {
                echo "<ul>";
                displayCats($tmp, $catsColl);
                echo "</ul>";
            }
            echo "</ul>";
        }
    }

}


function getName($catId, $catsColl) {
    foreach($catsColl as $id => $title ){
        if ($id == $catId) {
            //return $title;
            return $id . " " . $title;
        }

    }
}

@endphp