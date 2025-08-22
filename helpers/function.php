<?php

function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    exit;
}

/* Check if variable is set and not empty for array and object. And not empty checker for the regular variable
    * @regular variable isset or not cant be checked because of some restriction of php
    * @this function will return true if variable is set and not empty
*/
function isNotEmpty($postVariable, $name = null)
{
    if (is_array($postVariable) && $name !== null) {
        return isset($postVariable[$name]) && ! empty($postVariable[$name]);
    }

    if (is_object($postVariable) && $name !== null) {
        return isset($postVariable->$name) && ! empty($postVariable->$name);
    }

    return ! empty($postVariable);
}
