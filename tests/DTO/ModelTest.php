<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Tests\DTO;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\OpenAiContracts\DTO\Model;

/**
 * @internal
 */
#[CoversClass(Model::class)]
class ModelTest extends TestCase
{
    public function testConstructWithMinimalParametersShouldCreateInstance(): void
    {
        $model = new Model(
            id: 'gpt-3.5-turbo',
            object: 'model',
            created: 1677610602,
            ownedBy: 'openai'
        );

        $this->assertSame('gpt-3.5-turbo', $model->getId());
        $this->assertSame('model', $model->getObject());
        $this->assertSame(1677610602, $model->getCreated());
        $this->assertSame('openai', $model->getOwnedBy());
        $this->assertNull($model->getPermission());
        $this->assertNull($model->getRoot());
        $this->assertNull($model->getParent());
    }

    public function testConstructWithAllParametersShouldCreateInstance(): void
    {
        $permission = [
            'id' => 'modelperm-123',
            'object' => 'model_permission',
            'created' => 1677610602,
            'allow_create_engine' => false,
            'allow_sampling' => true,
        ];

        $model = new Model(
            id: 'text-davinci-003',
            object: 'model',
            created: 1669599635,
            ownedBy: 'openai-internal',
            permission: $permission,
            root: 'text-davinci-003',
            parent: 'text-davinci-002'
        );

        $this->assertSame('text-davinci-003', $model->getId());
        $this->assertSame('model', $model->getObject());
        $this->assertSame(1669599635, $model->getCreated());
        $this->assertSame('openai-internal', $model->getOwnedBy());
        $this->assertSame($permission, $model->getPermission());
        $this->assertSame('text-davinci-003', $model->getRoot());
        $this->assertSame('text-davinci-002', $model->getParent());
    }

    public function testFromArrayWithMinimalDataShouldCreateInstance(): void
    {
        $data = [
            'id' => 'gpt-4',
            'object' => 'model',
            'created' => 1687882411,
            'owned_by' => 'openai',
        ];

        $model = Model::fromArray($data);

        $this->assertSame('gpt-4', $model->getId());
        $this->assertSame('model', $model->getObject());
        $this->assertSame(1687882411, $model->getCreated());
        $this->assertSame('openai', $model->getOwnedBy());
        $this->assertNull($model->getPermission());
        $this->assertNull($model->getRoot());
        $this->assertNull($model->getParent());
    }

    public function testFromArrayWithCompleteDataShouldCreateInstance(): void
    {
        $permission = [
            'id' => 'modelperm-456',
            'object' => 'model_permission',
            'created' => 1687882411,
            'allow_create_engine' => true,
            'allow_sampling' => true,
            'allow_logprobs' => true,
        ];

        $data = [
            'id' => 'custom-model-v1',
            'object' => 'model',
            'created' => 1700000000,
            'owned_by' => 'custom-org',
            'permission' => $permission,
            'root' => 'base-model',
            'parent' => 'parent-model',
        ];

        $model = Model::fromArray($data);

        $this->assertSame('custom-model-v1', $model->getId());
        $this->assertSame('model', $model->getObject());
        $this->assertSame(1700000000, $model->getCreated());
        $this->assertSame('custom-org', $model->getOwnedBy());
        $this->assertSame($permission, $model->getPermission());
        $this->assertSame('base-model', $model->getRoot());
        $this->assertSame('parent-model', $model->getParent());
    }

    public function testFromArrayWithMissingValuesShouldUseDefaults(): void
    {
        $data = [];
        $currentTime = time();

        $model = Model::fromArray($data);

        $this->assertSame('', $model->getId());
        $this->assertSame('model', $model->getObject());
        $this->assertGreaterThanOrEqual($currentTime, $model->getCreated());
        $this->assertSame('', $model->getOwnedBy());
        $this->assertNull($model->getPermission());
        $this->assertNull($model->getRoot());
        $this->assertNull($model->getParent());
    }

    public function testToArrayWithMinimalDataShouldReturnCorrectArray(): void
    {
        $model = new Model(
            id: 'whisper-1',
            object: 'model',
            created: 1677532384,
            ownedBy: 'openai-internal'
        );

        $expected = [
            'id' => 'whisper-1',
            'object' => 'model',
            'created' => 1677532384,
            'owned_by' => 'openai-internal',
        ];

        $this->assertSame($expected, $model->toArray());
    }

    public function testToArrayWithCompleteDataShouldReturnCorrectArray(): void
    {
        $permission = [
            'id' => 'modelperm-789',
            'object' => 'model_permission',
            'created' => 1677532384,
            'allow_create_engine' => false,
            'allow_sampling' => false,
            'allow_logprobs' => false,
        ];

        $model = new Model(
            id: 'dall-e-3',
            object: 'model',
            created: 1698785189,
            ownedBy: 'system',
            permission: $permission,
            root: 'dall-e-3',
            parent: 'dall-e-2'
        );

        $expected = [
            'id' => 'dall-e-3',
            'object' => 'model',
            'created' => 1698785189,
            'owned_by' => 'system',
            'permission' => $permission,
            'root' => 'dall-e-3',
            'parent' => 'dall-e-2',
        ];

        $this->assertSame($expected, $model->toArray());
    }

    public function testSerializationRoundTripShouldPreserveData(): void
    {
        $originalData = [
            'id' => 'test-model-complete',
            'object' => 'model',
            'created' => 1234567890,
            'owned_by' => 'test-organization',
            'permission' => [
                'id' => 'modelperm-test',
                'object' => 'model_permission',
                'created' => 1234567890,
                'allow_create_engine' => true,
                'allow_sampling' => true,
                'allow_logprobs' => true,
                'allow_search_indices' => false,
                'allow_view' => true,
                'allow_fine_tuning' => false,
                'organization' => 'test-org',
                'group' => null,
                'is_blocking' => false,
            ],
            'root' => 'base-test-model',
            'parent' => 'parent-test-model',
        ];

        $model = Model::fromArray($originalData);
        $serializedData = $model->toArray();
        $reconstructedModel = Model::fromArray($serializedData);

        $this->assertSame($model->getId(), $reconstructedModel->getId());
        $this->assertSame($model->getObject(), $reconstructedModel->getObject());
        $this->assertSame($model->getCreated(), $reconstructedModel->getCreated());
        $this->assertSame($model->getOwnedBy(), $reconstructedModel->getOwnedBy());
        $this->assertSame($model->getPermission(), $reconstructedModel->getPermission());
        $this->assertSame($model->getRoot(), $reconstructedModel->getRoot());
        $this->assertSame($model->getParent(), $reconstructedModel->getParent());
    }

    public function testPartialDataShouldOnlyIncludeNonNullValues(): void
    {
        $model = new Model(
            id: 'partial-model',
            object: 'model',
            created: 1600000000,
            ownedBy: 'test-owner',
            root: 'base-model'
        );

        $result = $model->toArray();

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('object', $result);
        $this->assertArrayHasKey('created', $result);
        $this->assertArrayHasKey('owned_by', $result);
        $this->assertArrayHasKey('root', $result);
        $this->assertArrayNotHasKey('permission', $result);
        $this->assertArrayNotHasKey('parent', $result);

        $this->assertSame('partial-model', $result['id']);
        $this->assertSame('model', $result['object']);
        $this->assertSame(1600000000, $result['created']);
        $this->assertSame('test-owner', $result['owned_by']);
        $this->assertSame('base-model', $result['root']);
    }

    public function testComplexPermissionStructureShouldBePreserved(): void
    {
        $complexPermission = [
            'id' => 'modelperm-complex',
            'object' => 'model_permission',
            'created' => 1677610602,
            'allow_create_engine' => true,
            'allow_sampling' => true,
            'allow_logprobs' => false,
            'allow_search_indices' => true,
            'allow_view' => true,
            'allow_fine_tuning' => false,
            'organization' => 'acme-corp',
            'group' => 'research-team',
            'is_blocking' => false,
            'metadata' => [
                'tier' => 'enterprise',
                'features' => ['analytics', 'priority_support'],
                'limits' => [
                    'requests_per_minute' => 1000,
                    'tokens_per_day' => 1000000,
                ],
            ],
        ];

        $model = new Model(
            id: 'enterprise-gpt-4',
            object: 'model',
            created: 1677610602,
            ownedBy: 'openai',
            permission: $complexPermission
        );

        $this->assertSame($complexPermission, $model->getPermission());
        $this->assertSame($complexPermission, $model->toArray()['permission']);
    }

    public function testGetterMethodsShouldReturnCorrectValues(): void
    {
        $permission = ['test' => 'permission'];

        $model = new Model(
            id: 'test-model-id',
            object: 'test-object',
            created: 999999999,
            ownedBy: 'test-owner',
            permission: $permission,
            root: 'test-root',
            parent: 'test-parent'
        );

        $this->assertSame('test-model-id', $model->getId());
        $this->assertSame('test-object', $model->getObject());
        $this->assertSame(999999999, $model->getCreated());
        $this->assertSame('test-owner', $model->getOwnedBy());
        $this->assertSame($permission, $model->getPermission());
        $this->assertSame('test-root', $model->getRoot());
        $this->assertSame('test-parent', $model->getParent());
    }
}
