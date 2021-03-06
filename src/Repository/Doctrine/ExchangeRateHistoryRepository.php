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

declare(strict_types=1);


namespace Adshares\AdsOperator\Repository\Doctrine;

use Adshares\AdsOperator\Document\ExchangeRateHistory;
use Adshares\AdsOperator\Exchange\Currency;
use Adshares\AdsOperator\Repository\Exception\ExchangeRateNotFoundException;
use Adshares\AdsOperator\Repository\ExchangeRateHistoryRepositoryInterface;
use DateTime;
use Doctrine\ODM\MongoDB\DocumentRepository;

class ExchangeRateHistoryRepository extends DocumentRepository implements ExchangeRateHistoryRepositoryInterface
{

    public function fetchNewest(): ExchangeRateHistory
    {
        $exchangeRates = $this->findBy([], ['date' => -1], 1, 0);

        if (isset($exchangeRates[0])) {
            return $exchangeRates[0];
        }

        throw new ExchangeRateNotFoundException('Not found');
    }

    public function addExchangeRate(ExchangeRateHistory $exchangeRateHistory): void
    {
        $this->getDocumentManager()->persist($exchangeRateHistory);
        $this->getDocumentManager()->flush();
    }

    public function fetchForCurrencyBetweenDates(Currency $currency, DateTime $start, DateTime $end): array
    {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder
            ->field('currency')->equals($currency->toString())
            ->field('date')->gte($start)
            ->field('date')->lte($end)
        ;

        $result = $queryBuilder
            ->getQuery()
            ->execute()
            ->toArray();

        return $result;
    }
}
