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

namespace Adshares\AdsOperator\Repository\Doctrine;

use Adshares\AdsOperator\Document\User;
use Adshares\AdsOperator\Repository\Exception\UserNotFoundException;
use Adshares\AdsOperator\Repository\UserRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentRepository;

class UserRepository extends DocumentRepository implements UserRepositoryInterface
{
    public function signUp(User $user): void
    {
        $this->save($user);
    }

    public function save(User $user): void
    {
        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush();
    }

    public function findByEmail(string $email): User
    {
        $user = $this->findOneBy(['email' => $email]);

        if (!$user instanceof User) {
            throw new UserNotFoundException(sprintf('User %s (email) not found', $email));
        }

        return $user;
    }

    public function findById(string $id): User
    {
        $user = $this->findOneBy(['id' => $id]);

        if (!$user instanceof User) {
            throw new UserNotFoundException(sprintf('User %s (id) not found', $id));
        }

        return $user;
    }

    public function findByNewEmail(string $email): User
    {
        $user = $this->findOneBy(['newEmail' => $email]);

        if (!$user instanceof User) {
            throw new UserNotFoundException(sprintf('User %s (newEmail) not found', $email));
        }

        return $user;
    }
}
