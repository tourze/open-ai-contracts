<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Response;

use Tourze\OpenAiContracts\DTO\Model;

interface ModelListResponseInterface extends OpenAiResponseInterface
{
    /**
     * @return Model[]
     */
    public function getData(): array;
}
