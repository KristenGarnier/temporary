<?php

namespace Finortho\Fritage\EchangeBundle\Tests\Services;

use Finortho\Fritage\EchangeBundle\Services\FileTree;

class FileTreeTest extends \PHPUnit_Framework_TestCase
{
    public function testShloudRenderString(){
        $filtree = new FileTree();
        $result = $filtree->php_file_tree(__DIR__.'/../', 'http://test.com');
        $this->assertTrue(is_string($result));
        $this->assertEquals(preg_match("/Services/", $result), 1);
        $this->assertEquals(preg_match("/<ul>/", $result), 1);
        $this->assertEquals(preg_match("/<li/", $result), 1);
        $this->assertEquals(preg_match("/href/", $result), 1);
        $this->assertEquals(preg_match("/<a/", $result), 1);
        $this->assertEquals(preg_match('/class="php-file-tree"/', $result), 1);
        $this->assertEquals(preg_match('/FileTreeTest.php/', $result), 1);
    }
}
