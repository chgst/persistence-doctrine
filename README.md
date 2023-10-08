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

Create your model class:

```php
<?php
// src/Document/DefaultEvent.php

namespace Document;

use Chgst\Event\Event;

class DefaultEvent extends Event
{
    protected string $id;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
```

Add mapping

```xml
 <doctrine-mongo-mapping xmlns="http://doctrine-project.org/schemas/odm/doctrine-mongo-mapping"
                         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                         xsi:schemaLocation="http://doctrine-project.org/schemas/odm/doctrine-mongo-mapping
                    https://doctrine-project.org/schemas/odm/doctrine-mongo-mapping.xsd">

    <document name="Document\Event">
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
