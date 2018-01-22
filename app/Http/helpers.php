<?php

function isOneDimension($arr) {

    if (!is_array($arr) || count($arr) == 0) {
        return false;
    }

    foreach($arr as $i => $val) {
        if (is_array($val)) {
            return false;
        }
    }

    return true;

}

function displayItemsCatsCkBoxes($arr, $str = '', $catsColl, $itemsCatsColl, $itemsId) {

    foreach($arr as $id => $tmp) {
        if (is_array($tmp)) {
            echo displayItemsCatsCkBox($id, $catsColl, $itemsCatsColl, $itemsId);
            displayItemsCatsCkBoxes($tmp, $str, $catsColl, $itemsCatsColl, $itemsId);
            echo "<br>";
        } else {
            echo displayItemsCatsCkBox($id, $catsColl, $itemsCatsColl, $itemsId);
        }
    }


}

function displayItemsCatsCkBox($id, $catsColl, $itemsCatsColl, $itemsId) {
    $html = " <input type='checkbox' name='catsIdArr[]' value = '$id'";
    foreach($itemsCatsColl as $k => $obj) {
        if ($obj->cats_id == $id && $itemsId == $obj->items_id) {
            $html.=" checked";
        }
    }
    $html.="> ";
    return getName($id, $catsColl, $html);

}


function displayCats($arr, $catsColl, $str = '') {

    if (!is_array($arr)) {
        $name = getName($arr, $catsColl);
        if ($name != 'name not found') {
            echo "<li class='liAdmin'>" . $name . "</li>";
        }
    } else {
        foreach($arr as $id => $tmp) {
            $name = getName($id, $catsColl);
            if ($name == 'name not found') {
                // There may be no subcategories, which is fine.
                continue;
            }
            echo "<ul class='ulAdmin'>";
            echo "<li class='liAdmin'>" . $name . "</li>";
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
    return "name not found";
}

function printR($arr) {

    return "<pre>" . print_r($arr, 1) . "</pre>";

}