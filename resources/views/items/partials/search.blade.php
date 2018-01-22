@php

echo '<form method="get">';
echo '<div class="sectionForm">';
echo "<input type='text' name='search' size='20' placeholder='Search' value='" . $search . "'>";
echo " <input type='submit' value='Search' class='btn btn-primary'>";
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
echo '<a class="nav-link ' . $descActive . '" href="?sort=desc' . $searchQStr . '">Alpha Desc</a>';
echo '</li>';

echo '<li class="nav-item">';
echo '<a class="nav-link ' . $ascActive . '" href="?sort=asc' . $searchQStr . '">Alpha Asc</a>';
echo '</li>';

echo '<li class="nav-item">';
echo '<a class="nav-link ' . $newActive . '" href="?sort=new' . $searchQStr . '">Newest First</a>';
echo '</li>';

echo '<li class="nav-item">';
echo '<a class="nav-link ' . $oldActive . '" href="?sort=old' . $searchQStr . '">Oldest First</a>';
echo '</li>';

echo '</ul>';

@endphp