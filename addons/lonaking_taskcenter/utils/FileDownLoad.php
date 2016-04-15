<?php

class FileDownLoad
{

    static function download_remote_file($file_url, $save_to)
    {
        $content = file_get_contents($file_url);
        file_put_contents($save_to, $content);
    }

    static function download_remote_file_to_dir($file_url, $save_dir, $filename)
    {
        if (! is_dir($save_dir)) {
            mkdir($save_dir,0777,true);
        }
        FileDownLoad::download_remote_file($file_url, $save_dir ."\\". $filename);
    }
}

?>