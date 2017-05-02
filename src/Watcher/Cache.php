<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Watcher;

/**
 * Class Cache
 *
 * This is a watcher based on the CacheProvider class.
 * By default, it will use the application cache provider. You can do something different by overriding
 * initCache in project/includes/controls/QWatcher.class.php.
 * Note that if you want to be able to detect changes by other users, you should use a
 * shared caching mechanism, like APC or Memcache. QCacheProviderLocalMemory will
 * not work for this.
 *
 * @was QWatcherCache
 */
class Cache extends AbstractBase
{

    /** @var  QAbstractCacheProvider */
    public static $objCache = null;

    /**
     *
     */
    public function __construct()
    {
        if (!static::$objCache) {
            static::initCache();
        }
    }

    /**
     * Records the current state of the watched tables.
     */
    public function makeCurrent()
    {
        if (!static::$objCache) {
            static::initCache();
        }
        $curTime = microtime();
        foreach ($this->strWatchedKeys as $key => $val) {
            $time2 = static::$objCache->get($key);

            if ($time2 === false) {
                // if dropped from cache, or not yet cached
                static::$objCache->set($key, $curTime);
                $time2 = $curTime;
            }
            $this->strWatchedKeys[$key] = $time2;
        }
    }

    /**
     *
     * @return bool
     */
    public function isCurrent()
    {
        if (!static::$objCache) {
            static::initCache();
        }
        foreach ($this->strWatchedKeys as $key => $time) {
            $time2 = static::$objCache->get($key);
            if (false === $time2 || $time2 != $time) {
                return false;
            }
        }

        return true;
    }

    /**
     * Model Save() method should call this to indicate that a table has changed.
     *
     * @param string $strTableName
     * @throws \QCubed\Exception\Caller
     */
    public static function markTableModified($strDbName, $strTableName)
    {
        parent::markTableModified($strDbName, $strTableName);
        if (!static::$objCache) {
            static::initCache();
        }
        $key = static::getKey($strDbName, $strTableName);
        $time = microtime();

        self::$objCache->set($key, $time);
    }

    /**
     * Initializes the cache. By default, uses the application cache. Configure that in your config
     * settings.
     */
    protected static function initCache()
    {
        static::$objCache = QApplication::$objCacheProvider;
    }
}
