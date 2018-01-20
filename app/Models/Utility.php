<?php

namespace App\Models;


class Utility
{

    public static function cleanText($text)
    {

        // First, replace UTF-8 characters.
        $text = str_replace(
            array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"),
            array("'", "'", '"', '"', '-', '--', '...'),
            $text
        );

        // Next, replace their Windows-1252 equivalents.
        $text = str_replace(
            array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)),
            array("'", "'", '"', '"', '-', '--', '...'),
            $text
        );

        return $text;

    }

    public static function makeHyperlinks($text)
    {


    }

    /*
     * Remove all line breaks and replace double spaces with single
     */
    public static function tighten($text)
    {
        $text = str_replace("\n", " ", $text);
        $text = str_replace("\r", " ", $text);
        $text = str_replace("  ", " ", $text);
        return $text;
    }

}