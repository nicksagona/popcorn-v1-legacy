#!/usr/bin/php
<?php

$xml = null;
$base = null;
$components = array();

if (($xml =@ new SimpleXMLElement('http://popcorn.popphp.org/components/popcorn.xml', LIBXML_NOWARNING, true)) !== false) {
    $base = $xml->attributes()->base;
    foreach ($xml->component as $item) {
        $comp = (string)$item->attributes()->name;
        $components[$comp] = array();
        if ($item->count() > 0) {
            $children = $item->children();
            foreach ($children as $child) {
                $components[$comp][] = (string)$child->attributes()->name;
            }
        }
    }
    print_r($argv);
} else {
    echo 'The component URL cannot be read at this time.' . PHP_EOL;
    exit(0);
}
