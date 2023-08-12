# DO NOT USE IN PRODUCTION !

..... 
But read on if interested ..

# Evident

Base packages for Evident PHP

The idea is to start with a data framework using closures as expressions , inspired by linq, but should be php, as it doesn't support generics, thats a challenge.

- Expressio, smart library for converting Closure / Arrow functions into transpilable expressions, like but not limited to AnsiSQL
- Bunch, verry minimal collection and enumerator implementation based on IteratorAggregation. should use closures for ease.
- Lingua - Verry simple SQL like pure php syntax query builder, uses Expressio for transpiling. Responses are pure arrays.
- Matter, Data Abstract Layer, support both Readable, Writable and Bidirectional datastreams. could be your sql , mongo or even logger connection, uses Lingua for SQL for now.
- Quantum - Full Entity Framework, based on all of the above.


# Roadmap in this order:

v Bunch 
    Verry minimal Collection and Enumerator classes. 
    The Enumerator is intentionally immutable, 
    the Collection is intentonaly mutable.

v Expressio
    Expression library for converting PHP Closures in runtime to anything other than php.
    e.g. sql, mongo. as for now this is a proof of concept.

v Lingua
    Querying Language Library for Sql like languages 
    Uses Expressio to compile closures into SQL statements
    May be renamed to LinguaSQL if it its obvious that there's another language to translate in to ( Mongo? OpenAPI? GraphQL? S3? Redis? Memcached?, xlsx?, csv?, socket streams ?)

x Matter
    Data Abstraction Layer,
    Abstracts away different backends ( using Lingua package optinally others )
    Does entity mapping and provides a convienient ORM using Bunch Collections

x Quantum 
    Extends on Matter. Code First Entity Framework
    Provides:
        importing current schema's
        creating migrations with different migration tools ( or home made, we have not decided yet. )
        differenciating between current schema, and current code


