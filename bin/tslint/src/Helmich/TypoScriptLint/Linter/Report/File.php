<?php
namespace Helmich\TypoScriptLint\Linter\Report;

/**
 * Checkstyle report containing issues for a single TypoScript file.
 *
 * @author     Martin Helmich <typo3@martin-helmich.de>
 * @license    MIT
 * @package    Helmich\TypoScriptLint
 * @subpackage Linter\Report
 */
class File
{

    /** @var string */
    private $filename;

    /** @var Issue[] */
    private $issues = [];

    /**
     * Constructs a new file report.
     *
     * @param string $filename The filename.
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Gets the filename.
     *
     * @return string The filename.
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Adds a new issue for this file.
     *
     * @param Issue $issue The new issue
     * @return void
     */
    public function addIssue(Issue $issue)
    {
        $this->issues[] = $issue;
    }

    /**
     * Gets all issues for this file. The issues will be sorted by line
     * numbers, not by order of addition to this report.
     *
     * @return Issue[] The issues for this file.
     */
    public function getIssues()
    {
        usort(
            $this->issues,
            function (Issue $a, Issue $b) {
                return $a->getLine() - $b->getLine();
            }
        );
        return $this->issues;
    }

    /**
     * Gets all issues for this file that have a certain severity.
     *
     * @param string $severity The severity. Should be one of the Issue class' SEVERITY_* constants
     * @return Issue[] All issues with the given severity
     */
    public function getIssuesBySeverity($severity)
    {
        return array_values(array_filter($this->getIssues(), function(Issue $i) use ($severity) {
            return $i->getSeverity() === $severity;
        }));
    }

    /**
     * Creates a new empty report for the same file
     *
     * @return File The new report
     */
    public function cloneEmpty()
    {
        return new static($this->filename);
    }

    /**
     * Merges this file report with another file report
     *
     * @param File $other The file report to merge this report with
     * @return File The merged report
     */
    public function merge(File $other)
    {
        $new = new static($this->filename);
        $new->issues = array_merge($this->issues, $other->issues);
        return $new;
    }
}
