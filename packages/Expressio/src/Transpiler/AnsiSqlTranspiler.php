<?php

namespace Evident\Expressio\Transpiler;

use Evident\Expressio\Expression;
use Evident\Expressio\Transpiler\Transpilation;
use Evident\Expressio\Transpiler\TranspilationInterface;
use Evident\Expressio\Transpiler\TranspilerInterface;
use PhpParser\Node;

class AnsiSqlTranspiler implements TranspilerInterface
{
    private array $aliasses = [];
    private array $bindings = [];
    
    private $antiColide = true;

    private ?Expression $expr;

    public function disableAntiColide() {
        $this->antiColide = false;
    }
    /**
     * Setting aliasses array
     *
     * @param array $aliasses
     * 
     * @return void
     * 
     */
    public function setAliasses(array $aliasses): void
    {
        $this->aliasses = $aliasses;
    }
    /**
     * Transpile the Expression into SQl, aliasses are used ( see setAliasses() )
     *
     * @param Expression $expr
     *
     * @return AnsiSqlTranspilation
     *
     */
    public function transpile(Expression $expr, $remap = true): AnsiSqlTranspilation
    { 
        $this->expr = $expr;
        $transpilation = new AnsiSqlTranspilation();
        $transpilation->statement = $this->transpileNode($this->expr->getReflection()->getAst());
        $transpilation->bindings = $this->bindings;
        $transpilation = $this->antiColideBindings($transpilation);
        
        return $transpilation;
    }

    public function antiColideBindings($transpilation): AnsiSqlTranspilation {
        if ( !$this->antiColide ) return $transpilation;

        foreach ( $transpilation->bindings as $old => $value ) {
            $new = ':'.base_convert(microtime(), 10, 36);
            $transpilation->bindings[$new] = $value;
            unset($transpilation->bindings[$old]);
            $transpilation->statement = str_replace("$old", "$new", $transpilation->statement);
        }
        return $transpilation;
    }

    /**
     * Internal function, transforms the classname of a node into a single camelcase transpile function
     *
     * example: PhpParser\Node\\Expr\\Variable wil go to transpileExprVariable function, this allows for a uniform way to do this.
     *
     * @param mixed $node
     *
     * @return string
     *
     */
    private function getNodeNameHandler($node): string
    {
        $name = get_class($node);
        $name = str_replace("\\", "", rtrim(ucfirst(str_replace("PhpParser\Node\\", "", $name)), "_"));
        return "transpile" . $name;
    }

    /**
     * Transpiles a \PhpParse\Node interface using the getNodeNameHandler function
     * this allows for calling transpileNode on every single node
     * basically a proxy function
     *
     * @param Node|array $node
     *
     * @return string
     *
     */
    public function transpileNode(Node|array $node): string
    {
        if (is_array($node)) {
            $s = '';
            foreach ($node as $n) {
                $s .= $this->transpileNode($n);
            }
            return $s;
        } else {
            // there may be a faster of way doing this, maybe even a big switch statement
            $fn = $this->getNodeNameHandler($node);
            if (method_exists($this, $fn)) {
                return $this->{$fn}($node);
            } else {
                throw new \RuntimeException("$fn is not supported in " . self::class . ' ' . var_export($node, true));
            }
        }
    }

    /**
     * Transpile a variable into a value or binding.
     *
     * @param Node $n
     *
     * @return String
     *
     */
    private function transpileExprVariable(Node $n): string
    {
        // was it an alias, translate it.
        $params = $this->expr->getReflection()->getParameters();
        foreach ($params as $param) {
            foreach ($this->aliasses as $alias_type => $alias) {
                // echo $alias_type .' => '. $alias . PHP_EOL;
                if ((string) $param->getName() === (string) $n->name && $param->hasType() && (string) $param->getType() === (string) $alias_type) {
                    return $alias;
                }
            }
        }
        // is it a use variable? then get the value of the object.. ( maybe need to be recursive ? we'll see)
        foreach ($this->expr->getReflection()->getClosureUsedVariables() as $unsafe_parameter => $value) {
            if ($n->name == $unsafe_parameter) {
                $this->bindings[':' . $unsafe_parameter] = $value;
                return ':' . $unsafe_parameter;
            }
        }
        return $n->name;
    }

    private function transpileIdentifier(Node $node): string
    {
        if ( array_key_exists($node->name,$this->aliasses) ) {
            return $this->aliasses[$node->name];
        }

        return $node->name;
    }
    private function transpileExprPropertyFetch(Node $n): string
    {
        // check if it is a passed object?
        $result = $this->transpileNode($n->var) . '.' . $this->transpileNode($n->name);
        if ( array_key_exists($result,$this->aliasses) ) {
            $result = $this->aliasses[$result];
        }
        // is this a passed object? then use its value as a binding
        // need bindings?
        if ($n->var->name == 'this') {
            $scope = $this->expr->getReflection()->getClosureThis();
            if ($scope) {
                $ref = new \ReflectionProperty($scope, $n->name);
                if ($ref) {
                    $this->bindings[':' . $result] = $ref->getValue($scope);
                    return ':' . $result;
                }
            }
        }
        return $result;
    }

    // basic operations translations
    private function transpileStmtReturn(Node $n): string
    {
        return $this->transpileNode($n->expr);
    }

    private function transpileExprBinaryOpEqual(Node $n): string
    {
        return $this->transpileNode($n->left) . ' = ' . $this->transpileNode($n->right);
    }
    private function transpileExprBinaryOpGreater(Node $n): string
    {
        return $this->transpileNode($n->left) . ' > ' . $this->transpileNode($n->right);
    }
    private function transpileExprBinaryOpSmaller(Node $n): string
    {
        return $this->transpileNode($n->left) . ' < ' . $this->transpileNode($n->right);
    }
    private function transpileExprBinaryOpMul(Node $n): string
    {
        return $this->transpileNode($n->left) . ' * ' . $this->transpileNode($n->right);
    }
    private function transpileExprBinaryOpMod(Node $n): string
    {
        return 'MOD( ' . $this->transpileNode($n->left) . ' , ' . $this->transpileNode($n->right) . ' )';
    }
    private function transpileExprBinaryOpPow(Node $n): string
    {
        return 'POWER( ' . $this->transpileNode($n->left) . ' , ' . $this->transpileNode($n->right) . ' )';
    }
    private function transpileExprBinaryOpBooleanAnd(Node $n): string
    {
        return '( ' . $this->transpileNode($n->left) . ' AND ' . $this->transpileNode($n->right) . ' )';
    }
    private function transpileExprBinaryOpBooleanOr(Node $n): string
    {
        return '( ' . $this->transpileNode($n->left) . ' OR ' . $this->transpileNode($n->right) . ' )';
    }

    private function transpileScalarString(Node $n): string
    {
        return '"' . $n->value . '"';
    }

    private function transpileExprConstFetch(Node $n): string
    {
        $test = strtolower($n->name->parts[0]);
        if ($test == 'true') {
            return 1;
        }
        if ($test == 'false') {
            return 0;
        }
        throw new \RuntimeException("transpileExprConstFetch cannot determine value in " . self::class);
    }
}
