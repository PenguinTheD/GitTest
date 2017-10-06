<?php
namespace Helmich\TypoScriptLint\Linter\Sniff;

use Helmich\TypoScriptLint\Linter\LinterConfiguration;
use Helmich\TypoScriptLint\Linter\Report\File;
use Helmich\TypoScriptLint\Linter\Report\Issue;
use Helmich\TypoScriptLint\Linter\Sniff\Inspection\TokenInspections;
use Helmich\TypoScriptParser\Tokenizer\LineGrouper;
use Helmich\TypoScriptParser\Tokenizer\Token;
use Helmich\TypoScriptParser\Tokenizer\TokenInterface;

class IndentationSniff implements TokenStreamSniffInterface
{
    use TokenInspections;

    private $useSpaces = true;

    private $indentPerLevel = 4;

    /**
     * Defines whether code inside conditions should be indented by one level.
     *
     * @var bool
     */
    private $indentConditions = false;

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        if (array_key_exists('useSpaces', $parameters)) {
            $this->useSpaces = $parameters['useSpaces'];
        }
        if (array_key_exists('indentPerLevel', $parameters)) {
            $this->indentPerLevel = $parameters['indentPerLevel'];
        }
        if (array_key_exists('indentConditions', $parameters)) {
            $this->indentConditions = $parameters['indentConditions'];
        }
    }

    /**
     * @param TokenInterface[]    $tokens
     * @param File                $file
     * @param LinterConfiguration $configuration
     * @return void
     */
    public function sniff(array $tokens, File $file, LinterConfiguration $configuration)
    {
        $indentCharacter  = $this->useSpaces ? ' ' : "\t";
        $tokensByLine     = new LineGrouper($tokens);
        $indentationLevel = 0;

        /** @var TokenInterface[] $tokensInLine */
        foreach ($tokensByLine->getLines() as $line => $tokensInLine) {
            $indentationLevel = $this->reduceIndentationLevel($indentationLevel, $tokensInLine);

            $expectedIndentationCharacterCount = $this->indentPerLevel * $indentationLevel;
            $expectedIndentation               = str_repeat(
                $indentCharacter,
                $expectedIndentationCharacterCount
            );

            foreach ($tokensInLine as $key => $token) {
                if ($token->getType() === TokenInterface::TYPE_RIGHTVALUE_MULTILINE) {
                    unset($tokensInLine[$key]);
                    $tokensInLine = array_values($tokensInLine);
                }
            }

            // Skip empty lines.
            if ($this->isEmptyLine($tokensInLine)) {
                continue;
            }

            if ($indentationLevel === 0 && self::isWhitespace($tokensInLine[0]) && strlen($tokensInLine[0]->getValue())) {
                $file->addIssue($this->createIssue($line, $indentationLevel, $tokensInLine[0]->getValue()));
            } elseif ($indentationLevel > 0) {
                if (!self::isWhitespace($tokensInLine[0])) {
                    $file->addIssue($this->createIssue($line, $indentationLevel, ''));
                } elseif ($tokensInLine[0]->getValue() !== $expectedIndentation) {
                    $file->addIssue($this->createIssue($line, $indentationLevel, $tokensInLine[0]->getValue()));
                }
            }

            $indentationLevel = $this->raiseIndentationLevel($indentationLevel, $tokensInLine);
        }
    }

    /**
     * Checks if a stream of tokens is an empty line.
     *
     * @param TokenInterface[] $tokensInLine
     * @return bool
     */
    private function isEmptyLine(array $tokensInLine)
    {
        if (count($tokensInLine) > 1) {
            return false;
        }

        $firstToken = $tokensInLine[0];
        return $firstToken->getType() === TokenInterface::TYPE_WHITESPACE && $firstToken->getValue() === "\n";
    }

    /**
     * Check whether indentation should be reduced by one level, for current line.
     *
     * Checks tokens in current line, and whether they will reduce the indentation by one.
     *
     * @param int $indentationLevel The current indentation level
     * @param TokenInterface[] $tokensInLine
     * @return int The new indentation level
     */
    private function reduceIndentationLevel($indentationLevel, array $tokensInLine)
    {
        $raisingIndentation = [
            TokenInterface::TYPE_BRACE_CLOSE,
        ];

        if ($this->indentConditions) {
            $raisingIndentation[] = TokenInterface::TYPE_CONDITION_END;
        }

        foreach ($tokensInLine as $token) {
            if (in_array($token->getType(), $raisingIndentation)) {
                return $indentationLevel - 1;
            }
        }

        return $indentationLevel;
    }

    /**
     * Check whether indentation should be raised by one level, for current line.
     *
     * Checks tokens in current line, and whether they will raise the indentation by one.
     *
     * @param int $indentationLevel The current indentation level
     * @param TokenInterface[] $tokensInLine
     * @return int The new indentation level
     */
    private function raiseIndentationLevel($indentationLevel, array $tokensInLine)
    {
        $raisingIndentation = [
            TokenInterface::TYPE_BRACE_OPEN,
        ];

        if ($this->indentConditions) {
            $raisingIndentation[] = TokenInterface::TYPE_CONDITION;
        }

        foreach ($tokensInLine as $token) {
            if (in_array($token->getType(), $raisingIndentation)) {
                return $indentationLevel + 1;
            }
        }

        return $indentationLevel;
    }

    private function createIssue($line, $expectedLevel, $actual)
    {
        $indentCharacterCount       = ($expectedLevel * $this->indentPerLevel);
        $indentCharacterDescription = ($this->useSpaces ? 'space' : 'tab') . (($indentCharacterCount == 1) ? '' : 's');

        $expectedMessage = "Expected indent of {$indentCharacterCount} {$indentCharacterDescription}.";

        return new Issue($line, strlen($actual), $expectedMessage, Issue::SEVERITY_WARNING, __CLASS__);
    }
}
