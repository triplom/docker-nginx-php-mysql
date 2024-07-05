<?php

/**
 * I belong to a file
 */

namespace App\Acme;

/**
 * I belong to a class
 */
class Foo
{
    /**
     * Gets the name of the application.
     * 
     * @return string The name of the application with "MySQL" highlighted in red.
     */
    public function getName()
    {
        return '<span style="color: dark grey;">Nginx</span> <span style="color: blue;">PHP</span> <span style="color: red;"><del>MySQL</del></span>';
    }
}
