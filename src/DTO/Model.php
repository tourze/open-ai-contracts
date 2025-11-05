<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\DTO;

class Model
{
    /**
     * @param array<string, mixed>|null $permission
     */
    public function __construct(
        private readonly string $id,
        private readonly string $object,
        private readonly int $created,
        private readonly string $ownedBy,
        private readonly ?array $permission = null,
        private readonly ?string $root = null,
        private readonly ?string $parent = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: self::extractStringValue($data, 'id', ''),
            object: self::extractStringValue($data, 'object', 'model'),
            created: self::extractIntValue($data, 'created', time()),
            ownedBy: self::extractStringValue($data, 'owned_by', ''),
            permission: self::extractArrayValue($data, 'permission'),
            root: self::extractNullableStringValue($data, 'root'),
            parent: self::extractNullableStringValue($data, 'parent'),
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function extractStringValue(array $data, string $key, string $default): string
    {
        if (isset($data[$key]) && is_string($data[$key])) {
            return $data[$key];
        }

        return $default;
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function extractIntValue(array $data, string $key, int $default): int
    {
        if (isset($data[$key]) && (is_int($data[$key]) || is_string($data[$key]))) {
            return (int) $data[$key];
        }

        return $default;
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function extractNullableStringValue(array $data, string $key): ?string
    {
        if (isset($data[$key]) && is_string($data[$key])) {
            return $data[$key];
        }

        return null;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>|null
     */
    private static function extractArrayValue(array $data, string $key): ?array
    {
        if (isset($data[$key]) && is_array($data[$key])) {
            /** @var array<string, mixed> $value */
            $value = $data[$key];
            return $value;
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'object' => $this->object,
            'created' => $this->created,
            'owned_by' => $this->ownedBy,
        ];

        if (null !== $this->permission) {
            $data['permission'] = $this->permission;
        }

        if (null !== $this->root) {
            $data['root'] = $this->root;
        }

        if (null !== $this->parent) {
            $data['parent'] = $this->parent;
        }

        return $data;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getObject(): string
    {
        return $this->object;
    }

    public function getCreated(): int
    {
        return $this->created;
    }

    public function getOwnedBy(): string
    {
        return $this->ownedBy;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getPermission(): ?array
    {
        return $this->permission;
    }

    public function getRoot(): ?string
    {
        return $this->root;
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }
}
