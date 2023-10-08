# Doctrine data store for Repository interface

[![Version](https://img.shields.io/packagist/v/chgst/persistence-doctrine.svg?style=flat-square)](https://packagist.org/packages/chgst/persistence-doctrine)
[![CircleCI](https://dl.circleci.com/status-badge/img/circleci/UiMSDe5Q43N7rRZKowVuq2/M9aAirJaHrCa9RQijVSTV1/tree/develop.svg?style=shield)](https://dl.circleci.com/status-badge/redirect/circleci/UiMSDe5Q43N7rRZKowVuq2/M9aAirJaHrCa9RQijVSTV1/tree/develop)
[![Coverage Status](https://coveralls.io/repos/github/chgst/persistence-doctrine/badge.svg?branch=develop)](https://coveralls.io/github/chgst/persistence-doctrine?branch=develop)
[![License](https://poser.pugx.org/chgst/persistence-doctrine/license.svg)](https://packagist.org/packages/chgst/persistence-doctrine)

## Installation

```bash
composer require chgst/persistence-doctrine
```

## Configuration

```yaml
#app/config/services.yml

services:

    Chgst\Event\RepositoryInterface:
        public: true
        class: Chgst\Event\ObjectRepository
        arguments: [ '@doctrine_mongodb.odm.document_manager' ] # or '@doctrine.orm.entity_manager'

```

Create your model class:

```php
<?php
# src/{Entity|Document}/MyEvent.php
namespace App\Document;
// namespace App\Entity;

class MyEvent extends Changeset\Event\Event
{
    private $id;
    
    public function getId() { return $this->id; }
    
    public function setId($id) { $this->id = $id; }
}

```

Add mapping

```xml
 <doctrine-mongo-mapping xmlns="http://doctrine-project.org/schemas/odm/doctrine-mongo-mapping"
                         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                         xsi:schemaLocation="http://doctrine-project.org/schemas/odm/doctrine-mongo-mapping
                    https://doctrine-project.org/schemas/odm/doctrine-mongo-mapping.xsd">

    <document name="App\Document\Event">
        <id />
        <field field-name="name" type="string" nullable="false" />
        <field field-name="aggregateType" type="string" nullable="false" />
        <field field-name="aggregateId" type="string" nullable="false" />
        <field field-name="payload" type="hash" nullable="false" />
        <field field-name="createdAt" type="date" nullable="false" />
        <field field-name="createdBy" type="string" nullable="false" />
    </document>
</doctrine-mongo-mapping>
```