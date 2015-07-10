<?php
namespace Nabble;

/**
 * Class SemaltUpdater
 * @package Nabble
 */
class SemaltUpdater
{
    public static $blocklist = './../domains/blocked';
    public static $ttl = 604800; // = 60 * 60 * 24 * 7 = 7 days
    private static $updateUrl = 'https://raw.githubusercontent.com/nabble/semalt-blocker/master/domains/blocked';

    //////////////////////////////////////////
    // PUBLIC API                           //
    //////////////////////////////////////////

    /**
     * Try to update the blocked domains list
     */
    public static function update()
    {
        if (!self::isWritable()) return;

        if (!self::isOutdated()) return;

        $domains = self::getNewDomainList();

        if (!empty($domains))
            @file_put_contents(self::$blocklist, $domains);
    }

    //////////////////////////////////////////
    // PRIVATE FUNCTIONS                    //
    //////////////////////////////////////////

    private static function isWritable()
    {
        return is_writable(self::$blocklist);
    }

    private static function isOutdated()
    {
        return filemtime(self::$blocklist) < (time() - self::$ttl);
    }

    private static function getNewDomainList()
    {
        $domains = @file_get_contents(self::$updateUrl);
        return $domains;
    }
}