<?php

namespace CbCaio\Boletos\Testing;

use Orchestra\Testbench\TestCase;

abstract class AbstractTestCase extends TestCase
{
    /**
     * Array of service providers should be loaded before tests.
     * @var array
     */
    protected $providers = [
        \CbCaio\Boletos\Providers\BoletoServiceProvider::class
    ];

    /**
     * Array of test case which should not load the service providers.
     * @var array
     */
    protected $skipProvidersFor = [];

    /**
     * Get source package path.
     *
     * @param string $path
     *
     * @return string
     */
    public function srcPath($path = null)
    {
        return __DIR__.'/../src'.$this->parseSubPath($path);
    }

    /**
     * Get the resources path.
     *
     * @param string $path
     *
     * @return string
     */
    public function resourcePath($path = null)
    {
        return $this->srcPath('/../resources').$this->parseSubPath($path);
    }

    /**
     * Stubs path.
     *
     * @param string $path
     *
     * @return string
     */
    public function stubsPath($path = null)
    {
        return __DIR__.'/stubs'.$this->parseSubPath($path);
    }

    /**
     * @param string $path
     * @return string
     */
    public function filesPath($path = null)
    {
        return __DIR__.'/stubs/files'.$this->parseSubPath($path);
    }

    /**
     * Get package providers.
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        if (in_array($this->getName(), $this->skipProvidersFor)) {
            return [];
        }

        return $this->providers;
    }

    /**
     * Trim slashes of path and return prefixed by DIRECTORY_SEPARATOR.
     * @param string $path
     * @return string
     */
    protected function parseSubPath($path)
    {
        return $path ? DIRECTORY_SEPARATOR.trim($path, DIRECTORY_SEPARATOR) : '';
    }

}