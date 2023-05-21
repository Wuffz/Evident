# Expressio

Evident package for  the closure/arrow expressions into true expression objects,
This allows for runtime transpilation/execution with any backend

## Todo:
- make transpilations cachable so we can bypass the php-parser library for optimisations in a strandardized way



```
<?php 
// Some mockup idea for SomeExpressionCaching ( lambda -> sourcecode -> transpilation -> php factory for rapid compilation )
class SqlExpresion___FILE___LINE {
    const string $hash = __HASH__;
    private function generateHash(\ReflectionFunction $func) {
        
    }
    public function getFactory(... $params) {
        return function( $context ) {
            return [ 
                sql => ' where a.name => ?a.name ',
                bind => [ '?a.name' => $context['a']['name']
            ]
        }
        // throw new cacheInvalidException
    }
    public function isValid(ReflectionFunction $func) {
        if ( $this->generateHash($fileOnDisk)
    }
}
?>
```