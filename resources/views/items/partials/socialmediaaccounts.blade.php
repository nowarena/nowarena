@php

if (count($socialMediaAccountsColl) == 0) {
    return;
}

$hasAccount = false;
if (count($socialMediaAssocAccountsArr)) {

    foreach($socialMediaAssocAccountsArr as $key => $obj) {
        if ($obj->items_id == $item->id) {
            $hasAccount = true;

            echo "<input type='hidden' name='source_id' value='" . $obj->source_id . "'>";
            echo "<input type='hidden' name='site' value='" . $obj->site . "'>";

            echo "<div class='accountRemove'>";
            echo "<input type='checkbox' name='remove' value='1'>" . $obj->username;
            echo "</div>";

            echo "<div class='isActive'>";
            echo "<input type='checkbox' name='is_active' value='1' ";
            if ($obj->is_active) {
                echo "checked";
            }
            echo ">";
            echo "</div>";

            echo "<div class='isPrimary'>";
            echo "<input type='checkbox' name='is_primary' value='1' ";
            if ($obj->is_primary) {
                echo "checked";
            }
            echo ">";
            echo "</div>";

            echo "<div class='useAvatar'>";
            echo "<input type='checkbox' name='use_avatar' value='1' ";
            if ($obj->use_avatar) {
                echo "checked";
            }
            echo ">";
            echo "<img class='socialAccountAvatar' src='" . $obj->avatar . "'>";
            echo "</div>";

        }
    }
}

if ($hasAccount == false) {
    echo "<select name='add_source_id' class='socialMediaAccountDD'>";
    echo "<option value='0'>Add</option>";
    foreach($socialMediaAccountsColl as $key => $obj) {
        // if already associated, skip
        if ($obj->items_id > 0) {
            continue;
        }
        echo "<option value='" . $obj->source_id . "' ";
        if (stristr($obj->username, $item->title) || stristr($item->title, $obj->username)) {
            echo "selected";
        }
        echo ">" . $obj->username . "</option>";
    }
    echo "</select>";
    echo "<div class='isPrimary'> &nbsp; </div>";
    echo "<div class='isActive'> &nbsp; </div>";
    echo "<div class='useAvatar'> &nbsp; </div>";


}



@endphp

