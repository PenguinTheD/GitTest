<?php
namespace Helmich\TypoScriptParser\Tests\Unit\Parser\AST;

use Helmich\TypoScriptParser\Parser\AST\RootObjectPath;

class RootObjectPathTest extends \PHPUnit_Framework_TestCase
{
    /** @var RootObjectPath */
    private $path;

    public function setUp()
    {
        $this->path = new RootObjectPath();
    }

    public function testAbsoluteNameIsEmpty()
    {
        assertThat($this->path->absoluteName, equalTo(''));
    }

    public function testRelativeNameIsEmpty()
    {
        assertThat($this->path->relativeName, equalTo(''));
    }

    public function testDepthIsZero()
    {
        assertThat($this->path->depth(), equalTo(0));
    }

    public function testParentIsAlsoRoot()
    {
        assertThat($this->path->parent()->depth(), equalTo(0));
        assertThat($this->path->parent()->absoluteName, equalTo(''));
    }

    public function testCanAppendPath()
    {
        $new = $this->path->append('foo');
        assertThat($new->absoluteName, equalTo('foo'));
        assertThat($new->relativeName, equalTo('foo'));
        assertThat($new->depth(), equalTo(1));
    }
}