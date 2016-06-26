# Symfony Form Value Object Mapper

[![Build Status](https://travis-ci.org/mikeSimonson/symfony-form-entity-mapper.svg?branch=master)](https://travis-ci.org/mikeSimonson/symfony-form-entity-mapper)
[![Code Coverage](https://scrutinizer-ci.com/g/mikeSimonson/symfony-form-entity-mapper/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mikeSimonson/symfony-form-entity-mapper/?branch=master)

## Motivation

Value object usage with the Symfony form component.
[https://webmozart.io/blog/2015/09/09/value-objects-in-symfony-forms/](There is a great post from Webmozart explaining how to use the Symfony form component with value object.)
But sometimes people might get discourage to use value object because they now need to do the mapping between the object and the form manually.
It can look daunting when the number of properties of the object is high.

This library intend to fix 90% of that issue by providing automatic mapping in most of the cases.
See the requirements part.

In the 10% left, it still make more sense to do the mapping manually than do try to make a library that has to do magic incantation to guess the user need. 


## Install

```sh
composer require mikesimonson/symfony-form-value-object-mapper
```

## Usage

In any form that requires it you can use 
```php
$builder->setDataMapper(new FormMapper());
```

## Requirement to use this Mapper:

- Having entities
- Having getters and setters on those entities
- The name of the form elements must match the name of the properties in the entity
- The name of the constructor parameters must match the name of the entities property
- If yout throw an exception in case of validation failure it needs to extends InvalidArgumentException
- Tests for your forms that use it. If you change a property name in your value object the form will break. (Although it's also true with the default form mapper from the symfony form) 


## Limitation:

Right now this mapper doesn't work with the collectionType field.
PR are welcome.
