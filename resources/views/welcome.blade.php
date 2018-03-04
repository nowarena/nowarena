<!doctype html>
<html>
<head>
    <title>AbbotKinneyBL.com - Abbot Kinney Blvd. news and media</title>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0' />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" />
    <link rel='stylesheet' href='./reactswipe/index.css' />

</head>
<body>

<h1 class="titleCont"><div class="title">Abbot Kinney Bl</div><div class='clearBoth'></div></h1>
<div class="subHeader">The latest on Abbot Kinney Bl in Venice Beach!</div>

<!-- DINING -->
<div id="dining" class="rowCont"></div>
<div style='clear:both;'></div>
<hr class='divider' />
<!-- FASHION -->
<div id="fashion" class="rowCont"></div>
<div style='clear:both;'></div>
<hr class='divider' />
<!-- SHOPPING -->
<div id="shopping" class="rowCont"></div>
<div style='clear:both;'></div>
<hr class='divider' />
<!-- COFFEE CASUAL EATS -->
<div id="casualeatscoffee" class="rowCont"></div>
<div style='clear:both;'></div>
<hr class='divider' />
<!-- TECH -->
<!--<div id="tech" class="rowCont"></div>-->
<!--<div style='clear:both;'></div>-->
<!--<hr class='divider' />-->
<!-- NEWS AND PEOPLE -->
<div id="newspeople" class="rowCont"></div>
<div style='clear:both;'></div>
<hr class='divider' />
<!-- SPA AND FITNESS -->
<div id="healthbeauty" class="rowCont"></div>
<div style='clear:both;'></div>
<!--<hr class='divider' />-->
<!-- OTHER -->
<!--<div id="other" class="rowCont"></div>-->
<!--<div style='clear:both;'></div>-->
<!--SERVICES-->
<!--<div id="services"></div>-->
<!--<div style='clear:both;'></div>-->
@php
$r=rand(1,111111111);
$r=date("H");
@endphp
<script src="./reactswipe/bundle.min.js?r={{$r}}"></script>

<!--

 todo

follow all biz on twitter


what to do about overflow
When viewing item's feed only (not all categories), keep item header locked and not slide with rest of body

Add contact info social media links: twitter, insta, fb, yelp, website

Add opentable

cronjob generating json

setup on amazon

make appear to google


-->

</body>
</html>
