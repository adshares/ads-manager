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


namespace Adshares\AdsOperator\Repository;

use Adshares\AdsOperator\Document\ExchangeRateHistory;
use Adshares\AdsOperator\Exchange\Currency;
use DateTime;

interface ExchangeRateHistoryRepositoryInterface
{
    public function fetchNewest(): ExchangeRateHistory;

    public function addExchangeRate(ExchangeRateHistory $exchangeRateHistory);

    public function fetchForCurrencyBetweenDates(Currency $currency, DateTime $start, DateTime $end): array;
}
