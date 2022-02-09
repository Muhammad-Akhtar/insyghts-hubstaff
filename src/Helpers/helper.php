<?php

namespace Insyghts\Hubstaff\Helpers;


class Helper {

    public static function get_public_path($folder)
    {
        return app()->basePath('public') . DIRECTORY_SEPARATOR . $folder;
    }
}