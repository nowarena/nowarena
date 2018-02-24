@php

echo '<form method="get">';
echo '<div class="sectionForm">';
echo "<input style='float:left;' type='text' name='search' size='20' placeholder='Search' value='" . $search . "'>";
echo " <input type='submit' value='Search' class='btn btn-primary'>";
@endphp
@include('layouts.partials.catsdd', ['catsArr' => $catsArr, 'searchCatsId' => $searchCatsId])
@php
echo "</div>";
echo "</form>";

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

echo '<li class="nav-item" style="margin-left:10px;margin-top:10px;font-weight:bold;">Sort:</li>';

echo '<li class="nav-item">';
echo '<a class="nav-link ' . $descActive . '" href="?sort=desc&cats_id=' . $searchCatsId . '&' . $searchQStr . '">Desc</a>';
echo '</li>';

echo '<li class="nav-item">';
echo '<a class="nav-link ' . $ascActive . '" href="?sort=asc&cats_id=' . $searchCatsId . '&' . $searchQStr . '">Asc</a>';
echo '</li>';

echo '<li class="nav-item">';
echo '<a class="nav-link ' . $newActive . '" href="?sort=new&cats_id=' . $searchCatsId . '&' . $searchQStr . '">Newest</a>';
echo '</li>';

echo '<li class="nav-item">';
echo '<a class="nav-link ' . $oldActive . '" href="?sort=old&cats_id=' . $searchCatsId . '&' . $searchQStr . '">First</a>';
echo '</li>';



echo '</ul>';

@endphp


