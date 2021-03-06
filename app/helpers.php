<?php

use Illuminate\Support\HtmlString;

function svg(string $filename): HtmlString
{
    return new HtmlString(\file_get_contents(resource_path("svg/{$filename}.svg")));
}
