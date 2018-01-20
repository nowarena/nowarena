<?php

function displayItemsCatsCkBoxes($arr, $str = '', $catsColl, $itemsCatsColl, $itemsId) {

    $count = 0;
    foreach($arr as $id => $tmp) {
        if (is_array($tmp)) {
            echo getName($id, $catsColl) . " ";
            displayItemsCatsCkBoxes($tmp, $str, $catsColl, $itemsCatsColl, $itemsId);
        } else {
            $html = " <input type='checkbox' name='catsIdArr[]' value = '$id'";
            foreach($itemsCatsColl as $k => $obj) {
                if ($obj->cats_id == $id && $itemsId == $obj->items_id) {
                    $html.=" checked";
                }
            }
            $html.="> ";
            echo getName($id, $catsColl, $html);
        }
        $count++;

    }
    if (count($tmp) != $count) {
        echo "<br>";
    }

}



function displayCats($arr, $catsColl, $str = '') {

    if (!is_array($arr)) {
        echo "<li class='liAdmin'>" . getName($arr, $catsColl) . "</li>";
    } else {
        foreach($arr as $id => $tmp) {
            echo "<ul class='ulAdmin'>";
            echo "<li class='liAdmin'>" . getName($id, $catsColl) . "</li>";
            if (is_array($tmp)) {
                echo "<ul class='ulAdmin'>";
                displayCats($tmp, $catsColl);
                echo "</ul>";
            }
            echo "</ul>";
        }
    }

}


function getName($catId, $catsColl, $html = '') {
    foreach($catsColl as $id => $title ){
        if ($id == $catId) {
            $out = "<div class='catTitle'>";
            $out.= !empty($html) ? $html . " " : '';
            $out.= "$title</div>";
            return $out;
            return $id . " " . $title;
        }

    }
    return "$id name not found";
}

function printR($arr) {

    return "<pre>" . print_r($arr, 1) . "</pre>";

}