<?php

namespace App;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Filesystem\Filesystem;

class Stedentrip
{
    /**
     * The filesystem implementation.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The cache implementation.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Create a new documentation instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @return void
     */
    public function __construct(Filesystem $files, Cache $cache)
    {
        $this->files = $files;
        $this->cache = $cache;
    }

    /**
     * Get the documentation index page.
     *
     * @param  string  $continent
     * @return string|null
     */
    public function getIndex()
    {
        return $this->cache->remember('stedentrips.index', 5, function () use ($continent) {
            $path = base_path('resources/stedentrips/stedentrips.md');

            return null;
        });
    }

    /**
     * Get the given documentation page.
     *
     * @param  string  $version
     * @param  string  $page
     * @return string|null
     */
    public function get($continent, $page)
    {
        return $this->cache->remember('stedentrips.'.$continent.'.'.$page, 5, function () use ($continent, $page) {
            $path = base_path('resources/stedentrips/'.$continent.'/'.$page.'.md');

            return null;
        });
    }

    /**
     * Check if the given section exists.
     *
     * @param  string  $version
     * @param  string  $page
     * @return boolean
     */
    public function sectionExists($version, $page)
    {
        return $this->files->exists(
            base_path('resources/docs/'.$version.'/'.$page.'.md')
        );
    }

    /**
     * Determine which versions a page exists in.
     *
     * @param  string  $page
     * @return \Illuminate\Support\Collection
     */
    public function versionsContainingPage($page)
    {
        return collect(static::getDocVersions())
            ->filter(function ($version) use ($page) {
                return $this->sectionExists($version, $page);
            });
    }

    /**
     * Get the publicly available versions of the documentation
     *
     * @return array
     */
    public static function getDocVersions()
    {
        return [
            'master' => 'Master',
            '7.x' => '7.x',
            '6.x' => '6.x',
            // '6.0' => '6.0',
            '5.8' => '5.8',
            '5.7' => '5.7',
            '5.6' => '5.6',
            '5.5' => '5.5',
            '5.4' => '5.4',
            '5.3' => '5.3',
            '5.2' => '5.2',
            '5.1' => '5.1',
            '5.0' => '5.0',
            '4.2' => '4.2',
        ];
    }
}
