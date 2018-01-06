@php



    if (!isset($parentChildArr[$currentId])) {
        return;
    }

    foreach($parentChildArr[$currentId] as $childId) {

        foreach($catsColl as $id => $title ){
            if ($id == $childId) {
                echo $title;
            }
        }
        echo ":<input type='checkbox' name='child_id_arr[]' value='" . $childId . "' checked> | ";

    }


@endphp