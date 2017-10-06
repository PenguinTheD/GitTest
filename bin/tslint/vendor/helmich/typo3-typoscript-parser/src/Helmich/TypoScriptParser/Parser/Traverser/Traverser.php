<?php
namespace Helmich\TypoScriptParser\Parser\Traverser;

use Helmich\TypoScriptParser\Parser\AST\ConditionalStatement;
use Helmich\TypoScriptParser\Parser\AST\NestedAssignment;
use Helmich\TypoScriptParser\Parser\AST\Statement;

/**
 * Class Traverser
 *
 * @package    Helmich\TypoScriptParser
 * @subpackage Parser\Traverser
 */
class Traverser
{
    /** @var Statement[] */
    private $statements;

    /** @var AggregatingVisitor */
    private $visitors;

    /**
     * @param Statement[] $statements
     */
    public function __construct(array $statements)
    {
        $this->statements = $statements;
        $this->visitors   = new AggregatingVisitor();
    }

    /**
     * @param Visitor $visitor
     */
    public function addVisitor(Visitor $visitor)
    {
        $this->visitors->addVisitor($visitor);
    }

    /**
     * @return void
     */
    public function walk()
    {
        $this->visitors->enterTree($this->statements);
        $this->walkRecursive($this->statements);
        $this->visitors->exitTree($this->statements);
    }

    /**
     * @param Statement[] $statements
     * @return Statement[]
     */
    private function walkRecursive(array $statements)
    {
        foreach ($statements as $statement) {
            $this->visitors->enterNode($statement);

            if ($statement instanceof NestedAssignment) {
                $statement->statements = $this->walkRecursive($statement->statements);
            } elseif ($statement instanceof ConditionalStatement) {
                $statement->ifStatements   = $this->walkRecursive($statement->ifStatements);
                $statement->elseStatements = $this->walkRecursive($statement->elseStatements);
            }

            $this->visitors->exitNode($statement);
        }
        return $statements;
    }
}
