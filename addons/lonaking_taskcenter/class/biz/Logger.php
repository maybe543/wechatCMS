<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/4
 * Time: 上午12:57
 */
class Logger
{
    public static function log($content){
        load()->func('logging');
        logging_run($content,'normal','lonaking_taskcenter',true);
    }

}