<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Response;

abstract class AbstractOpenAiResponse implements OpenAiResponseInterface
{
    protected ?string $id = null;

    protected ?string $object = null;

    protected ?int $created = null;

    public function __construct(
        ?string $id = null,
        ?string $object = null,
        ?int $created = null,
    ) {
        $this->id = $id;
        $this->object = $object;
        $this->created = $created;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function getCreated(): ?int
    {
        return $this->created;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if (null !== $this->id) {
            $data['id'] = $this->id;
        }

        if (null !== $this->object) {
            $data['object'] = $this->object;
        }

        if (null !== $this->created) {
            $data['created'] = $this->created;
        }

        return $data;
    }
}
