<?php

namespace E7\Cache\Simple;

class FileCacheTest extends CacheTestCase
{
    /**
     * @dataProvider    providerDirectory
     * @param           array $params
     * @param           array $expected
     */
    public function testDirectory(array $params, $expected)
    {
        $cache = new FileCache($params);
        $this->assertEquals($expected, $cache->getDirectory());
        $cache->clear();

//        rmdir($cache->getDirectory());
    }

    /**
     * @return  array
     */
    public function providerDirectory()
    {
        $rand = md5(rand(2, 9999));
        $directory = '/tmp/foo/bar/' . $rand;
        $defaultDirectory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'e7-cache';
        $namespace = 'test-namespace-' . $rand;

        $params = [
            [
                [
                    'directory' => $directory,
                ],
                $directory
            ],
            [
                [],
                $defaultDirectory
            ],
            [
                [
                    'directory' => $directory,
                    'namespace' => $namespace,
                ],
                $directory . DIRECTORY_SEPARATOR . $namespace
            ],
            [
                [
                    'namespace' => $namespace,
                ],
                $defaultDirectory . DIRECTORY_SEPARATOR . $namespace
            ],
        ];

        return $params;
    }
}