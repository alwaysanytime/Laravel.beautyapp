<?php

namespace App\Http\Controllers\Backend;


class jSignature_Tools_Base30
{

    private $chunkSeparator = '';
    private $charmap = array();
    private $charmap_reverse = array();
    private $allchars = array();
    private $bitness = 0;
    private $minus = '';
    private $plus = '';

    function __construct()
    {
        global $bitness, $allchars, $charmap, $charmap_reverse, $minus, $plus, $chunkSeparator;

        $allchars = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWX');
        $bitness = sizeof($allchars) / 2;
        $minus = 'Z';
        $plus = 'Y';
        $chunkSeparator = '_';

        for ($i = $bitness - 1; $i > -1; $i--) {
            $charmap[$allchars[$i]] = $allchars[$i + $bitness];
            $charmap_reverse[$allchars[$i + $bitness]] = $allchars[$i];
        }
    }

    private function uncompress_stroke_leg($datastring)
    {
        global $charmap, $charmap_reverse, $bitness, $minus, $plus;

        $answer = array();
        $chars = str_split($datastring);
        $l = sizeof($chars);
        $ch = '';
        $polarity = 1;
        $partial = array();
        $preprewhole = 0;
        $prewhole = 0;

        for ($i = 0; $i < $l; $i++) {

            $ch = $chars[$i];
            if (array_key_exists($ch, $charmap) || $ch == $minus || $ch == $plus) {

                if (sizeof($partial) != 0) {

                    $prewhole = intval(implode('', $partial), $bitness) * $polarity + $preprewhole;
                    array_push($answer, $prewhole);
                    $preprewhole = $prewhole;
                }

                if ($ch == $minus) {
                    $polarity = -1;
                    $partial = array();
                } else if ($ch == $plus) {
                    $polarity = 1;
                    $partial = array();
                } else {

                    $partial = array($ch);
                }
            } else {

                array_push($partial, $charmap_reverse[$ch]);
            }
        }
        array_push($answer, intval(implode('', $partial), $bitness) * $polarity + $preprewhole);

        return $answer;
    }

    public function Base64ToNative($datastring)
    {
        global $chunkSeparator;

        $data = array();
        $chunks = explode($chunkSeparator, $datastring);
        $l = sizeof($chunks) / 2;
        for ($i = 0; $i < $l; $i++) {
            array_push($data, array(
                'x' => $this->uncompress_stroke_leg($chunks[$i * 2])
            , 'y' => $this->uncompress_stroke_leg($chunks[$i * 2 + 1])
            ));
        }
        return $data;
    }

}
