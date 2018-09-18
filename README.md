# Doctrine data store for Repository interface

[![Version](https://img.shields.io/packagist/v/chgst/persistence-doctrine.svg?style=flat-square)](https://packagist.org/packages/chgst/persistence-doctrine)
[![Build Status](https://travis-ci.org/chgst/persistence-doctrine.svg?branch=develop)](https://travis-ci.org/chgst/persistence-doctrine)
[![Coverage Status](https://coveralls.io/repos/github/chgst/persistence-doctrine/badge.svg?branch=develop)](https://coveralls.io/github/chgst/persistence-doctrine?branch=develop)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/chgst/persistence-doctrine/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/chgst/persistence-doctrine/?branch=develop)
[![License](https://poser.pugx.org/chgst/persistence-doctrine/license.svg)](https://packagist.org/packages/chgst/persistence-doctrine)

## Installation

```bash
composer require chgst/persistence-doctrine
```

## Configuration

```yaml
#app/config/services.yml

services:

    Changeset\Event\RepositoryInterface:
        public: true
        class: Changeset\Event\ObjectRepository
        arguments: [ '@doctrine_mongodb.odm.document_manager' ] # or '@doctrine.orm.entity_manager'

```

Create your model class:

```php
<?php
# src/AppBundle/{Entity|Document}/MyEvent.php
namespace AppBundle\Document;
// namespace AppBundle\Entity;

class MyEvent extends Changeset\Event\Event
{
    private $id;
    
    public function getId() { return $this->id; }
    
    public function setId($id) { $this->id = $id; }
}

```

Add mapping

```yaml
# src/AppBundle/Resources/doctrine/MyEvent.{orm|mongodb}.yml

AppBundle\Document\Event: # or AppBundle\Entity\Event
  fields:
    id:
      id: true
    name:
      type: string
    aggregateType:
      type: string
      name: aggregate_type
    aggregateId:
      type: string
      name: aggregate_id
    payload:
      type: hash # or string (or something to store json)
    createdAt:
      type: date
      name: created_at
    createdBy:
      type: string
      name: created_by
```