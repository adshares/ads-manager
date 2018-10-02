<?php
/**
 * Copyright (C) 2018 Adshares sp. z o.o.
 *
 * This file is part of ADS Operator
 *
 * ADS Operator is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * ADS Operator is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ADS Operator.  If not, see <https://www.gnu.org/licenses/>
 */

namespace Adshares\AdsOperator\Document;

class LocalTransaction
{
    private $id;

    private $userId;

    private $type;

    private $hash;

    private $msid;

    private $data;

    private $params;

    public function __construct(
        string $id,
        string $userId,
        string $type,
        string $hash,
        string $msid,
        string $data,
        array $params
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->hash = $hash;
        $this->msid = $msid;
        $this->data = $data;
        $this->type = $type;
        $this->params = $params;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getMsid(): int
    {
        return $this->msid;
    }

    public function getData(): string
    {
        return $this->data;
    }
}