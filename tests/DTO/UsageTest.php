<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Tests\DTO;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\OpenAiContracts\DTO\Usage;

/**
 * @internal
 */
#[CoversClass(Usage::class)]
class UsageTest extends TestCase
{
    public function testConstructWithMinimalParametersShouldCreateInstance(): void
    {
        $usage = new Usage(
            promptTokens: 50,
            completionTokens: 100,
            totalTokens: 150
        );

        $this->assertSame(50, $usage->getPromptTokens());
        $this->assertSame(100, $usage->getCompletionTokens());
        $this->assertSame(150, $usage->getTotalTokens());
        $this->assertNull($usage->getPromptTokensDetails());
        $this->assertNull($usage->getCompletionTokensDetails());
    }

    public function testConstructWithAllParametersShouldCreateInstance(): void
    {
        $promptDetails = [
            'cached_tokens' => 20,
            'audio_tokens' => 5,
        ];
        $completionDetails = [
            'reasoning_tokens' => 30,
            'audio_tokens' => 10,
        ];

        $usage = new Usage(
            promptTokens: 200,
            completionTokens: 300,
            totalTokens: 500,
            promptTokensDetails: $promptDetails,
            completionTokensDetails: $completionDetails
        );

        $this->assertSame(200, $usage->getPromptTokens());
        $this->assertSame(300, $usage->getCompletionTokens());
        $this->assertSame(500, $usage->getTotalTokens());
        $this->assertSame($promptDetails, $usage->getPromptTokensDetails());
        $this->assertSame($completionDetails, $usage->getCompletionTokensDetails());
    }

    public function testConstructWithZeroTokensShouldCreateInstance(): void
    {
        $usage = new Usage(
            promptTokens: 0,
            completionTokens: 0,
            totalTokens: 0
        );

        $this->assertSame(0, $usage->getPromptTokens());
        $this->assertSame(0, $usage->getCompletionTokens());
        $this->assertSame(0, $usage->getTotalTokens());
    }

    public function testFromArrayWithMinimalDataShouldCreateInstance(): void
    {
        $data = [
            'prompt_tokens' => 75,
            'completion_tokens' => 125,
            'total_tokens' => 200,
        ];

        $usage = Usage::fromArray($data);

        $this->assertSame(75, $usage->getPromptTokens());
        $this->assertSame(125, $usage->getCompletionTokens());
        $this->assertSame(200, $usage->getTotalTokens());
        $this->assertNull($usage->getPromptTokensDetails());
        $this->assertNull($usage->getCompletionTokensDetails());
    }

    public function testFromArrayWithCompleteDataShouldCreateInstance(): void
    {
        $data = [
            'prompt_tokens' => 400,
            'completion_tokens' => 600,
            'total_tokens' => 1000,
            'prompt_tokens_details' => [
                'cached_tokens' => 50,
                'audio_tokens' => 25,
                'text_tokens' => 325,
            ],
            'completion_tokens_details' => [
                'reasoning_tokens' => 200,
                'audio_tokens' => 50,
                'text_tokens' => 350,
            ],
        ];

        $usage = Usage::fromArray($data);

        $this->assertSame(400, $usage->getPromptTokens());
        $this->assertSame(600, $usage->getCompletionTokens());
        $this->assertSame(1000, $usage->getTotalTokens());
        $this->assertSame([
            'cached_tokens' => 50,
            'audio_tokens' => 25,
            'text_tokens' => 325,
        ], $usage->getPromptTokensDetails());
        $this->assertSame([
            'reasoning_tokens' => 200,
            'audio_tokens' => 50,
            'text_tokens' => 350,
        ], $usage->getCompletionTokensDetails());
    }

    public function testFromArrayWithMissingValuesShouldUseDefaults(): void
    {
        $data = [];

        $usage = Usage::fromArray($data);

        $this->assertSame(0, $usage->getPromptTokens());
        $this->assertSame(0, $usage->getCompletionTokens());
        $this->assertSame(0, $usage->getTotalTokens());
        $this->assertNull($usage->getPromptTokensDetails());
        $this->assertNull($usage->getCompletionTokensDetails());
    }

    public function testFromArrayWithPartialDataShouldUseDefaults(): void
    {
        $data = [
            'prompt_tokens' => 100,
            'prompt_tokens_details' => ['cached_tokens' => 10],
        ];

        $usage = Usage::fromArray($data);

        $this->assertSame(100, $usage->getPromptTokens());
        $this->assertSame(0, $usage->getCompletionTokens());
        $this->assertSame(0, $usage->getTotalTokens());
        $this->assertSame(['cached_tokens' => 10], $usage->getPromptTokensDetails());
        $this->assertNull($usage->getCompletionTokensDetails());
    }

    public function testToArrayWithMinimalDataShouldReturnCorrectArray(): void
    {
        $usage = new Usage(
            promptTokens: 150,
            completionTokens: 250,
            totalTokens: 400
        );

        $expected = [
            'prompt_tokens' => 150,
            'completion_tokens' => 250,
            'total_tokens' => 400,
        ];

        $this->assertSame($expected, $usage->toArray());
    }

    public function testToArrayWithCompleteDataShouldReturnCorrectArray(): void
    {
        $promptDetails = [
            'cached_tokens' => 100,
            'audio_tokens' => 20,
        ];
        $completionDetails = [
            'reasoning_tokens' => 150,
            'audio_tokens' => 30,
        ];

        $usage = new Usage(
            promptTokens: 350,
            completionTokens: 450,
            totalTokens: 800,
            promptTokensDetails: $promptDetails,
            completionTokensDetails: $completionDetails
        );

        $expected = [
            'prompt_tokens' => 350,
            'completion_tokens' => 450,
            'total_tokens' => 800,
            'prompt_tokens_details' => [
                'cached_tokens' => 100,
                'audio_tokens' => 20,
            ],
            'completion_tokens_details' => [
                'reasoning_tokens' => 150,
                'audio_tokens' => 30,
            ],
        ];

        $this->assertSame($expected, $usage->toArray());
    }

    public function testSerializationRoundTripShouldPreserveData(): void
    {
        $originalData = [
            'prompt_tokens' => 1500,
            'completion_tokens' => 2500,
            'total_tokens' => 4000,
            'prompt_tokens_details' => [
                'cached_tokens' => 500,
                'audio_tokens' => 100,
                'text_tokens' => 900,
                'image_tokens' => 0,
            ],
            'completion_tokens_details' => [
                'reasoning_tokens' => 800,
                'audio_tokens' => 200,
                'text_tokens' => 1500,
                'accepted_prediction_tokens' => 0,
                'rejected_prediction_tokens' => 0,
            ],
        ];

        $usage = Usage::fromArray($originalData);
        $serializedData = $usage->toArray();
        $reconstructedUsage = Usage::fromArray($serializedData);

        $this->assertSame($usage->getPromptTokens(), $reconstructedUsage->getPromptTokens());
        $this->assertSame($usage->getCompletionTokens(), $reconstructedUsage->getCompletionTokens());
        $this->assertSame($usage->getTotalTokens(), $reconstructedUsage->getTotalTokens());
        $this->assertSame($usage->getPromptTokensDetails(), $reconstructedUsage->getPromptTokensDetails());
        $this->assertSame($usage->getCompletionTokensDetails(), $reconstructedUsage->getCompletionTokensDetails());
    }

    public function testPartialDetailsShouldBeIncludedInArray(): void
    {
        $usage = new Usage(
            promptTokens: 100,
            completionTokens: 200,
            totalTokens: 300,
            promptTokensDetails: ['cached_tokens' => 50]
        );

        $result = $usage->toArray();

        $this->assertArrayHasKey('prompt_tokens_details', $result);
        $this->assertArrayNotHasKey('completion_tokens_details', $result);
        $this->assertSame(['cached_tokens' => 50], $result['prompt_tokens_details']);
    }

    public function testComplexTokenDetailsStructureShouldBePreserved(): void
    {
        $complexPromptDetails = [
            'cached_tokens' => 1000,
            'audio_tokens' => 500,
            'text_tokens' => 3000,
            'image_tokens' => 200,
            'video_tokens' => 0,
            'metadata' => [
                'cache_hit_rate' => 0.75,
                'compression_ratio' => 0.85,
            ],
        ];

        $complexCompletionDetails = [
            'reasoning_tokens' => 2000,
            'audio_tokens' => 800,
            'text_tokens' => 5000,
            'accepted_prediction_tokens' => 100,
            'rejected_prediction_tokens' => 50,
            'analysis' => [
                'quality_score' => 0.92,
                'coherence_score' => 0.88,
            ],
        ];

        $usage = new Usage(
            promptTokens: 4700,
            completionTokens: 7950,
            totalTokens: 12650,
            promptTokensDetails: $complexPromptDetails,
            completionTokensDetails: $complexCompletionDetails
        );

        $this->assertSame($complexPromptDetails, $usage->getPromptTokensDetails());
        $this->assertSame($complexCompletionDetails, $usage->getCompletionTokensDetails());
        $this->assertSame($complexPromptDetails, $usage->toArray()['prompt_tokens_details']);
        $this->assertSame($complexCompletionDetails, $usage->toArray()['completion_tokens_details']);
    }

    public function testLargeTokenCountsShouldBeHandledCorrectly(): void
    {
        $usage = new Usage(
            promptTokens: 1000000,
            completionTokens: 2000000,
            totalTokens: 3000000
        );

        $this->assertSame(1000000, $usage->getPromptTokens());
        $this->assertSame(2000000, $usage->getCompletionTokens());
        $this->assertSame(3000000, $usage->getTotalTokens());

        $array = $usage->toArray();
        $this->assertSame(1000000, $array['prompt_tokens']);
        $this->assertSame(2000000, $array['completion_tokens']);
        $this->assertSame(3000000, $array['total_tokens']);
    }

    public function testGetterMethodsShouldReturnCorrectValues(): void
    {
        $promptDetails = ['test_prompt' => 'details'];
        $completionDetails = ['test_completion' => 'details'];

        $usage = new Usage(
            promptTokens: 999,
            completionTokens: 1999,
            totalTokens: 2998,
            promptTokensDetails: $promptDetails,
            completionTokensDetails: $completionDetails
        );

        $this->assertSame(999, $usage->getPromptTokens());
        $this->assertSame(1999, $usage->getCompletionTokens());
        $this->assertSame(2998, $usage->getTotalTokens());
        $this->assertSame($promptDetails, $usage->getPromptTokensDetails());
        $this->assertSame($completionDetails, $usage->getCompletionTokensDetails());
    }
}
