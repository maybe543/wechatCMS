<?php
 class SpecParser{
    public static function parse($weid, $text){
        $lines = explode("\r", $text);
        $specs = array();
        foreach ($lines as $line){
            $tline = trim($line);
            if (!empty($tline)){
                $parts = explode('|', $tline, 3);
                if (is_array($parts)){
                    $item = array('title' => trim($parts[0]), 'ref' => trim($parts[1]), 'selected' => trim($parts[2]));
                    $specs[] = $item;
                }
            }
        }
        $result = self :: parse_data($weid, $specs);
        return $result;
    }
    public static function parse_data($weid, $specs){
        foreach ($specs as & $s){
            if (is_numeric($s['ref'])){
                $s['ref'] = murl('entry/module/detail', array('m' => 'quickshop', 'weid' => $weid, 'id' => $s['ref']));
            }else{
            }
        }
        return $specs;
    }
}
