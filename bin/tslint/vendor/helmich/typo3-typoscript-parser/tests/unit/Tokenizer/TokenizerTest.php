<?php
namespace Helmich\TypoScriptParser\Tests\Unit\Tokenizer;

use Helmich\TypoScriptParser\Tokenizer\Token;
use Helmich\TypoScriptParser\Tokenizer\Tokenizer;
use Helmich\TypoScriptParser\Tokenizer\TokenizerException;
use Helmich\TypoScriptParser\Tokenizer\TokenizerInterface;
use VirtualFileSystem\FileSystem;

class TokenizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var TokenizerInterface */
    private $tokenizer;

    public function setUp()
    {
        $this->tokenizer = new Tokenizer();
    }

    public function dataValidForTokenizer()
    {
        yield ["foo = bar", [
            new Token(Token::TYPE_OBJECT_IDENTIFIER, "foo", 1, 1),
            new Token(Token::TYPE_WHITESPACE, " ", 1, 4),
            new Token(Token::TYPE_OPERATOR_ASSIGNMENT, "=", 1, 5),
            new Token(Token::TYPE_WHITESPACE, " ", 1, 6),
            new Token(Token::TYPE_RIGHTVALUE, "bar", 1, 7),
        ]];

        // assert that trailing whitespaces are simply ignored
        yield ["foo = bar ", [
            new Token(Token::TYPE_OBJECT_IDENTIFIER, "foo", 1, 1),
            new Token(Token::TYPE_WHITESPACE, " ", 1, 4),
            new Token(Token::TYPE_OPERATOR_ASSIGNMENT, "=", 1, 5),
            new Token(Token::TYPE_WHITESPACE, " ", 1, 6),
            new Token(Token::TYPE_RIGHTVALUE, "bar", 1, 7)
        ]];

        yield ["foo.bar = baz", [
            new Token(Token::TYPE_OBJECT_IDENTIFIER, "foo.bar", 1, 1),
            new Token(Token::TYPE_WHITESPACE, " ", 1, 8),
            new Token(Token::TYPE_OPERATOR_ASSIGNMENT, "=", 1, 9),
            new Token(Token::TYPE_WHITESPACE, " ", 1, 10),
            new Token(Token::TYPE_RIGHTVALUE, "baz", 1, 11)
        ]];

        yield ["foo.bar.baz = baz", [
            new Token(Token::TYPE_OBJECT_IDENTIFIER, "foo.bar.baz", 1, 1),
            new Token(Token::TYPE_WHITESPACE, " ", 1, 12),
            new Token(Token::TYPE_OPERATOR_ASSIGNMENT, "=", 1, 13),
            new Token(Token::TYPE_WHITESPACE, " ", 1, 14),
            new Token(Token::TYPE_RIGHTVALUE, "baz", 1, 15)
        ]];

        yield ["foo =< bar", [
            new Token(Token::TYPE_OBJECT_IDENTIFIER, "foo", 1, 1),
            new Token(Token::TYPE_WHITESPACE, " ", 1, 4),
            new Token(Token::TYPE_OPERATOR_REFERENCE, "=<", 1, 5),
            new Token(Token::TYPE_WHITESPACE, " ", 1, 7),
            new Token(Token::TYPE_OBJECT_IDENTIFIER, "bar", 1, 8)
        ]];

        yield ["foo > # Something", [
            new Token(Token::TYPE_OBJECT_IDENTIFIER, "foo", 1, 1),
            new Token(Token::TYPE_WHITESPACE, " ", 1, 4),
            new Token(Token::TYPE_OPERATOR_DELETE, ">", 1, 5),
            new Token(Token::TYPE_WHITESPACE, " ", 1, 6),
            new Token(Token::TYPE_COMMENT_ONELINE, "# Something", 1, 7)
        ]];
    }

    public function dataInvalidForTokenizer()
    {
        // unterminated multiline assignment
        yield ["a (\nasdf"];

        // unterminated block comment
        yield ["/*\nhello world"];

        // invalid operators
        yield ["foo != bar"];
        yield ["foo *= bar"];
    }

    /**
     * @param $inputText
     * @param $expectedTokenStream
     * @dataProvider dataValidForTokenizer
     */
    public function testInputTextIsCorrectlyTokenized($inputText, $expectedTokenStream)
    {
        $tokenStream = $this->tokenizer->tokenizeString($inputText);
        assertThat($tokenStream, equalTo($expectedTokenStream));
    }

    /**
     * @param $inputText
     * @param $expectedTokenStream
     * @dataProvider dataValidForTokenizer
     */
    public function testInputStreamIsCorrectlyTokenized($inputText, $expectedTokenStream)
    {
        $fs = new FileSystem();
        $fs->createFile('/test.typoscript', $inputText);
        $tokenStream = $this->tokenizer->tokenizeStream($fs->path('/test.typoscript'));
        assertThat($tokenStream, equalTo($expectedTokenStream));
    }

    /**
     * @param $inputText
     * @expectedException \Helmich\TypoScriptParser\Tokenizer\TokenizerException
     * @dataProvider dataInvalidForTokenizer
     */
    public function testInvalidInputTestThrowsTokenizerError($inputText)
    {
        $this->tokenizer->tokenizeString($inputText);
    }
}