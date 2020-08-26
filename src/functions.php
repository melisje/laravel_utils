<?php

/**
 * Include all php files from the given subfolder in the /routes
 * @param $folder
 */
function includeRoutes($folder)
{
    foreach (glob(base_path() . "/routes/$folder/*.php") as $filename)
    {
        require_once $filename;
    }

}