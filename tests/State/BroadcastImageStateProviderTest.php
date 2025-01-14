<?php

namespace App\Tests\State;

use ApiPlatform\Metadata\Operation;
use App\Repository\BroadcastImageToDbEntityRepository;
use App\Repository\BroadcastImageToFileEntityRepository;
use App\State\BroadcastImageStateProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;


class BroadcastImageStateProviderTest extends TestCase
{
    private $fileRepositoryMock;
    private $dbRepositoryMock;
    private $stateProvider;

    public function testProvideReturnsMergedImages()
    {
        $this->fileRepositoryMock->method('getAllImagesAsBase64')
            ->willReturn(['fileImage1', 'fileImage2']);
        $this->dbRepositoryMock->method('getAllImagesAsBase64')
            ->willReturn(['dbImage1', 'dbImage2']);

        $operationMock = $this->createMock(Operation::class);

        $result = $this->stateProvider->provide($operationMock);

        $this->assertIsArray($result);
        $this->assertCount(4, $result);
        $this->assertEquals(['fileImage1', 'fileImage2', 'dbImage1', 'dbImage2'], $result);
    }

    public function testFileProvideHandlesException()
    {
        $this->fileRepositoryMock->method('getAllImagesAsBase64')
            ->willThrowException(new \Exception('Repository error'));

        $operationMock = $this->createMock(Operation::class);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Repository error');

        $this->stateProvider->provide($operationMock);
    }

    public function testDbProvideHandlesException()
    {
        $this->dbRepositoryMock->method('getAllImagesAsBase64')
            ->willThrowException(new \Exception('Repository error'));

        $operationMock = $this->createMock(Operation::class);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Repository error');

        $this->stateProvider->provide($operationMock);
    }

    protected function setUp(): void
    {
        $this->fileRepositoryMock = $this->createMock(BroadcastImageToFileEntityRepository::class);
        $this->dbRepositoryMock = $this->createMock(BroadcastImageToDbEntityRepository::class);

        $this->stateProvider = new BroadcastImageStateProvider(
            $this->fileRepositoryMock,
            $this->dbRepositoryMock,
        );
    }
}

