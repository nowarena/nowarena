@php

if (count($socialMediaAccountsColl) == 0) {
    return;
}

$hasAccount = false;
if (count($socialMediaAssocAccountsArr)) {

    foreach($socialMediaAssocAccountsArr as $key => $obj) {
        if ($obj->items_id == $item->id) {
            $hasAccount = true;

            @endphp

            <form action="{{ route('items.updatesocialmediaaccounts', $item) }}" method="get" class='socialMediaRow'>
            <input type="hidden" name="cats_id" value='{{$searchCatsId}}'>
            <input type="hidden" name="search" value='{{$search}}'>
            <input type="hidden" name="sort" value='{{$sort}}'>
            <input type="hidden" name="on_page" value="{{$itemsColl->currentPage()}}">
            <input type="hidden" name="items_id" value="{{ $item->id }}">
            {{ csrf_field() }}

            @php
            echo "<input type='hidden' name='source_user_id' value='" . $obj->source_user_id . "'>";
            echo "<input type='hidden' name='site' value='" . $obj->site . "'>";

            echo "<div class='socialSiteName'>" . $obj->site . "</div>";

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

            if (!empty($obj->avatar)) {
                echo "<img class='socialAccountAvatar' src='" . $obj->avatar . "'>";
            }

            echo "</div>";

            echo "<div class='submitBtn'>";
            echo '<button class="btn btn-primary" name="edit">Submit Edit</button>';
            echo '</div>';
            echo "<div style='clear:both;'></div>";

            echo "<div style='margin:2px auto;width:500px;border:0px solid black;'>";
            echo "<input type='text' style='width:496px;' name='avatar' value='" . $obj->avatar . "'>";
            echo "</div>";

            echo "<div style='clear:both;'></div>";

            echo '</form>';
            echo "<div style='clear:both;'></div>";


        }
    }
}

if ($search && $socialMediaAccountsColl->count()) {

    $hasMatch = false;
    echo '<form id="formassoc_' . $item->id . '" action="';
    @endphp {{ route("items.updatesocialmediaaccounts", $item) }}@php
    echo '" method="get" class="socialMediaRow">';
    echo "<input type='hidden' name='items_id' value='" . $item->id . "'>";
    echo "<input type='hidden' name='search' value='" . $search . "'>";
    echo "<select name='add_source_user_id' class='socialMediaAccountDD'>";
    echo "<option value='0'>Add</option>";
    foreach($socialMediaAccountsColl as $key => $obj) {
        // if already associated, skip
        if ($obj->items_id > 0) {
            continue;
        }
        echo "<option value='" . $obj->source_user_id . "' ";
        if (stristr($obj->username, $item->title) || stristr($item->title, $obj->username)) {
            echo "selected";
        }
        echo ">" . $obj->username . " : " . $obj->site . "</option>";
        $hasMatch = true;
    }
    echo "</select>";
    echo "<div class='isPrimary'> &nbsp; </div>";
    echo "<div class='isActive'> &nbsp; </div>";
    echo "<div class='useAvatar'> &nbsp; </div>";
    echo "<div class='submitBtn'>";
    echo '<button class="btn btn-primary" name="edit">Submit Edit</button>';
    echo '</div>';
    echo "<div style='clear:both;'></div>";
    echo '</form>';

    if ($hasMatch == false) {
        echo "<style>#formassoc_" . $item->id . "{display:none;}</style>";
    }


}



@endphp

