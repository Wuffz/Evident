# DO NOT USE IN PRODUCTION !

..... 
But read on if interested ..

# Evident

Base packages for Evident PHP

The idea is to start with a data framework using closures as expressions , inspired by linq, but should be php, as it doesn't support generics, thats a challenge.

- Expressio, smart library for converting Closure / Arrow functions into transpilable expressions, like but not limited to AnsiSQL
- Bunch, verry minimal collection and enumerator implementation based on IteratorAggregation. should use closures for ease.
- Matter, Data Abstract Layer, support both Readable, Writable and Bidirectional datastreams. could be your sql , mongo or even logger connection
- Lingua - Verry simple SQL like pure php syntax query builder, uses Matter in the backend for creating connections and actually handling and Expressio for transpiling. Responses are always bunch compatible

- Quantum - Full Entity Framework, based on all of the above.


# Roadmap in this order:

v Expressio
    Expression library for converting PHP Closures 
    in runtime to anything other than php 
    e.g. sql, mongo. as for now this is a proof of concept. ( sql will do fine with sqllite )

v Bunch 
    Verry minimal Collection and Enumerator classes. 
    The Enumerator is intentionally immutable, 
    the Collection is intentonaly mutable.

x Matter
    Data provider library based on bunch, 
    implementing closure/expressio and bunch r/o data. 
    muttable OR immutable, depending on datasource, basically an Data Abstraction Layer
        - As for now, focus on Map/Reduce/Skip/Take and Aggregation functionality
        - should translate somthing like select(User::class, Lectures::class)->map(fn1)->where(fn2)->skip(int1)->take(int2);
        // for sql that should transpile to some select user.* as user1 as fn1(user) where fn2(user) limit int2 offset int1
        - introduce relations/eagerloading later on, using reflection we can create optimized queries i think, named entities?
    
x Lingua
    Querying Language Library for multiple datasources  
    Querying library based on Matter data sources
    will pass anything just closures
    The idea is to have a single API for all data
    Will have 2 interfaces, Readable, Writable, implementing CollectionInterface and EnumeratorInterface
        - For perfomance, should always allow Matter API to be used, so stdClasses kan be used.

x Quantum 
    Entity framework based on Lingua. 
    Can be used with middleware datasources, therefore archiving, data-migrating and sharding can be done over multiple types of backends provided they are both writable
    - The hydration functions should be publicly accessibly to dehydrate optemized classes

