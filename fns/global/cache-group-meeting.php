
<?php

function getCachedData($key)
{
    $cacheFile = 'assets/cache/group_meeting_info.cache';
    if (file_exists($cacheFile)) {
        $cachedData = file_get_contents($cacheFile);
        $data = unserialize($cachedData);
        if (isset($data[$key])) {
            return $data[$key];
        }
        return null;
    }
    return null;
}

function setCachedData($key, $value)
{
    $cacheFile = 'assets/cache/group_meeting_info.cache';
    if (!file_exists($cacheFile)) touch($cacheFile);

    $cachedData = file_get_contents($cacheFile);
    $data = unserialize($cachedData);
    $data[$key] = $value;
    $cachedData = serialize($data);
    file_put_contents($cacheFile, $cachedData);
}
