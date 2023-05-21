<?php

namespace Evident\Expressio;

use Closure;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use RuntimeException;

// a class
class ExpressionReflectorException extends \Exception
{
};

class ExpressionReflector extends \ReflectionFunction
{
    /* @var Node[] */
    private array $ast;
    private string $body_source;
    // hash name for caching.
    private String $hash;

    public function __construct(Closure $closure)
    {
        parent::__construct($closure);

        // echo $this->extractSource($reflection);
        $filename = $this->getFileName();
        $start = $this->getStartLine();
        $end = $this->getEndLine();

        $this->hash = $filename.':'.$start.':'.$end;

        $lines = file($filename);
        $lines = array_slice($lines, $start - 1, $end - $start + 1);
        $source = implode('', $lines);

        // parse the source code
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        try {
            $ast = $parser->parse('<?php '.$source);
        } catch (Error $error) {
            $preventfail = false;

            // check if this was array notation
            if ($error->getMessage() == 'Syntax error, unexpected \',\' on line 1') {
                try {
                    $ast = $parser->parse('<?php '.rtrim($source, ", \n").';');
                    $preventfail = true;
                } catch (Error $e) {
                    // don't override the error.
                }
            }

            if (!$preventfail) {
                throw new ExpressionReflectorException("Parse error: ".$error->getMessage());
            }
        }

        // find the nodes which explicitly is a closure or arrow function
        $nodeFinder = new NodeFinder();
        $nodes = $nodeFinder->find($ast, function (Node $node) {
            return
                $node instanceof Expr\Closure ||
                $node instanceof Expr\ClosureUse ||
                $node instanceof Expr\ArrowFunction;
        });
        if (count($nodes) > 1) {
            throw new ExpressionReflectorException("Multiple closures on a single line of source code is not supported");
        }

        // store the AST
        //var_dump($nodes[0]));
        $ast = $nodes[0];
        if (!$ast) {
            throw new ExpressionReflectorException("Cannot determine location for closure");
        }
        unset($nodes);

        // store the full source code and body source code
        $prettyPrinter = new PrettyPrinter\Standard();
        $this->body_source =  $prettyPrinter->prettyPrint($ast->getStmts());
        $this->ast = $ast->getStmts();
    }

    public function getAst(): array
    {
        return $this->ast;
    }

    public function getSource(): String
    {
        return $this->body_source;
    }
}
