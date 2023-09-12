<?php
function htmlEncodedJson($object)
{
    $result = [];
    // doesn't work with nested objects or arrays
    foreach ($object as $key => $value) {
        // not sure if needed or if this is even correct.
        if (is_callable([$object, $key])) {
            continue;
        }
        $result[$key] = htmlentities($value);

    }
    return json_encode($result);
}