<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Response;

use Tourze\OpenAiContracts\DTO\Balance;

interface BalanceResponseInterface extends OpenAiResponseInterface
{
    public function getBalance(): Balance;
}
