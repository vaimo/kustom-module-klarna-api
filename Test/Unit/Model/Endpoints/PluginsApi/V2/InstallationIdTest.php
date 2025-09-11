<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\KlarnaApi\Test\Unit\Model\Endpoints\PluginsApi\V2;

use Klarna\Base\Test\Unit\Mock\MockFactory;
use Klarna\Base\Test\Unit\Mock\TestObjectFactory;
use Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\InstallationId;
use Klarna\KlarnaApi\Model\Exception as KlarnaApiException;
use Klarna\PluginsApi\Model\Database\Installation;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Klarna\KlarnaApi\Model\Endpoints\PluginsApi\V2\InstallationId
 */
class InstallationIdTest extends TestCase
{
    /**
     * @var InstallationId
     */
    private InstallationId $installationId;
    /**
     * @var array
     */
    private array $dependencyMocks;
    /**
     * @var Installation
     */
    private Installation $installation;

    public function testGetGettingTheIdFromTheRepositoryImpliesReturningInstallationId(): void
    {
        $this->installation->method('getInstallationId')
            ->willReturn('id');

        $this->dependencyMocks['installationRepository']->expects($this->once())
            ->method('getEntriesByScopeAndStoreAndMarket')
            ->willReturn($this->installation);

        static::assertEquals('id', $this->installationId->get('a', 1, 'eu'));
    }

    public function testGetRepositoryValueIsEmptyButGeneratedFromGuidGeneratorImpliesReturningInstallationId(): void
    {
        $this->installation->method('getInstallationId')
            ->willReturn('');

        $this->dependencyMocks['installationRepository']->expects($this->once())
            ->method('getEntriesByScopeAndStoreAndMarket')
            ->willReturn($this->installation);

        $this->dependencyMocks['guidGenerator']->expects($this->once())
            ->method('generateGUID')
            ->willReturn('id');

        static::assertEquals('id', $this->installationId->get('a', 1, 'eu'));
    }

    public function testGetEmptyFromAllSourcesImpliesThrowingException(): void
    {
        $this->installation->method('getInstallationId')
            ->willReturn('');

        $this->dependencyMocks['installationRepository']->expects($this->once())
            ->method('getEntriesByScopeAndStoreAndMarket')
            ->willReturn($this->installation);

        $this->dependencyMocks['guidGenerator']->expects($this->once())
            ->method('generateGUID')
            ->willReturn('');

        $this->expectException(KlarnaApiException::class);
        $this->installationId->get('a', 1, 'eu');
    }

    protected function setUp(): void
    {
        $mockFactory = new MockFactory($this);
        $objectFactory = new TestObjectFactory($mockFactory);

        $this->installationId = $objectFactory->create(InstallationId::class);
        $this->dependencyMocks = $objectFactory->getDependencyMocks();

        $this->installation = $mockFactory->create(Installation::class);
    }
}