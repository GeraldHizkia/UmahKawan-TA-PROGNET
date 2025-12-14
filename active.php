<?php
function active($page)
{

    return basename($_SERVER['PHP_SELF']) === $page ? 'active' : '';
}
