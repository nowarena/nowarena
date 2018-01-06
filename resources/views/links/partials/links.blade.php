<?php

if ($linksArr->count()) {
 
    echo "<div class='linksCont'>";
    echo "<div class='linksTitle'>Links</div>";
    foreach($linksArr as $name => $link) {
        
        echo "<div class='linkCont'>";
        echo "<a href='$link' target='_blank'>$name</a>";
        echo "</div>";
    }
    echo "<div style='clear:both;'></div>";
    echo "</div>";
    
}