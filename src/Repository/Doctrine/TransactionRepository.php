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

use Adshares\Ads\Entity\Transaction\AbstractTransaction;
use Adshares\AdsOperator\Repository\TransactionRepositoryInterface;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Query\Builder;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    /**
     * @return array
     */
    public function availableSortingFields(): array
    {
        return [
            'id',
            'nodeId',
            'blockId',
            'messageId',
            'type',
            'size',
            'amount',
            'senderAddress',
            'targetAddress',
            'time',
        ];
    }

    /**
     * @param string $sort
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @param array $conditions
     * @return array
     */
    public function fetchList(
        string $sort,
        string $order,
        int $limit,
        int $offset,
        array $conditions = []
    ): array {
        return $this->getTransactions($conditions, true, $sort, $order, $limit, $offset);
    }

    /**
     * @param string $transactionId
     * @return AbstractTransaction|null
     * @throws \Doctrine\ODM\MongoDB\LockException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     */
    public function getTransaction(string $transactionId):? AbstractTransaction
    {
        /** @var AbstractTransaction $transaction */
        $transaction = $this->find($transactionId);

        return $transaction;
    }

    /**
     * @param array $conditions
     * @param bool $hideConnections
     * @param string $sort
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getTransactions(
        array $conditions,
        bool $hideConnections,
        string $sort,
        string $order,
        int $limit,
        int $offset
    ): array {
        $results = [];

        $cursor = $this->createBuilderForList($sort, $order, $limit, $offset, $conditions);
        if ($hideConnections) {
            $cursor->field('type')->notEqual('connection');
        }

        try {
            $data = $cursor
                ->getQuery()
                ->execute()
                ->toArray();

            foreach ($data as $transaction) {
                $results[] = $transaction;
            }
        } catch (MongoDBException $ex) {
            return [];
        }

        return $results;
    }

    /**
     * @param string $accountId
     * @param string $sort
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getTransactionsByAccountId(
        string $accountId,
        string $sort,
        string $order,
        int $limit,
        int $offset
    ): array {
        $results = [];

        try {
            $queryBuilder = $this->createQueryBuilder();

            $cursor = $queryBuilder
                ->field('type')->notEqual('connection')
                ->addOr($queryBuilder->expr()->field('senderAddress')->equals($accountId))
                ->addOr($queryBuilder->expr()->field('targetAddress')->equals($accountId))
                ->addOr($queryBuilder->expr()->field('wires.targetAddress')->equals($accountId))
                ->sort($sort, $order)
                ->limit($limit)
                ->skip($offset)
                ->getQuery()
                ->execute();

            $data = $cursor->toArray();

            foreach ($data as $transaction) {
                $results[] = $transaction;
            }

            return $results;
        } catch (MongoDBException $ex) {
            return [];
        }
    }

    /**
     * @param string $messageId
     * @param bool $hideConnections
     * @param string $sort
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getTransactionsByMessageId(
        string $messageId,
        bool $hideConnections,
        string $sort,
        string $order,
        int $limit,
        int $offset
    ): array {
        return $this->getTransactions(['messageId' => $messageId], $hideConnections, $sort, $order, $limit, $offset);
    }

    /**
     * @param string $nodeId
     * @param bool $hideConnections
     * @param string $sort
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getTransactionsByNodeId(
        string $nodeId,
        bool $hideConnections,
        string $sort,
        string $order,
        int $limit,
        int $offset
    ): array {
        return $this->getTransactions(['nodeId' => $nodeId], $hideConnections, $sort, $order, $limit, $offset);
    }

    /**
     * @param string $blockId
     * @param bool $hideConnections
     * @param string $sort
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getTransactionsByBlockId(
        string $blockId,
        bool $hideConnections,
        string $sort,
        string $order,
        int $limit,
        int $offset
    ): array {
        return $this->getTransactions(['blockId' => $blockId], $hideConnections, $sort, $order, $limit, $offset);
    }

    /**
     * @param string $sort
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @param array|null $conditions
     * @return Builder
     */
    public function createBuilderForList(
        string $sort,
        string $order,
        int $limit,
        int $offset,
        ?array $conditions = []
    ): Builder {
        $cursor = $this
            ->createQueryBuilder()
            ->sort($sort, $order)
            ->limit($limit)
            ->skip($offset);

        if ($conditions) {
            foreach ($conditions as $columnName => $value) {
                $cursor->field($columnName)->equals($value);
            }
        }

        return $cursor;
    }
}
