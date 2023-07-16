<?php

namespace Evident\Matter\Utilities;

/*
 * as for now, we use cakephp's inflector, wrapped in a non static way (hard to test, less flexible, no mocking). Because we don't know yet what we will use, and what we will actually need or want.
 * we haven't deceided on if we want just english or want to suport multiple languages, or even make it possible to customize this entirely
 */
class Inflector {
    public function __construct(
        private string $word
    ){}
    public function shortify(): self {
        $this->word = (new \ReflectionClass($this->word))->getShortName();
        return $this;
    }
    public function pluralize(): self {
        $this->word = \Cake\Utility\Inflector::pluralize($this->word);
        return $this;
    }
    public function camelize() {
        $this->word = \Cake\Utility\Inflector::camelize($this->word);
        return $this;
    }
    public function underscore() {
        $this->word = \Cake\Utility\Inflector::underscore($this->word);
        return $this;
    }
    public function toString(): String {
        return $this->_toString();
    }
    public function _toString() : String {
        return $this->word;
    }

}