<?php
namespace Evident\Matter\Behaviour;
/*
 *  Fow now we use CamelCaseFullFullTableNamesConvention as the default because of the chonook db.
 *  We do not like the naming conventions in chinook, therefore we have a seperate default we can implement later .
 */ 
class DefaultNaming extends CamelCaseFullFullTableNamesConvention implements NamingInterface {
    
}