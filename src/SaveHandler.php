<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndex;

use MateuszMesek\DocumentDataIndexApi\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexApi\SaveHandlerInterface;
use Traversable;

class SaveHandler implements SaveHandlerInterface
{
    private DimensionResolverInterface $documentNameResolver;
    private SaveHandlerFactory $saveHandlerFactory;

    public function __construct(
        DimensionResolverInterface $documentNameResolver,
        SaveHandlerFactory $saveHandlerFactory
    )
    {
        $this->documentNameResolver = $documentNameResolver;
        $this->saveHandlerFactory = $saveHandlerFactory;
    }

    public function isAvailable(array $dimensions = []): bool
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        return $this->saveHandlerFactory->get($documentName)->isAvailable($dimensions);
    }

    public function saveIndex(array $dimensions, Traversable $documents): void
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        $this->saveHandlerFactory->get($documentName)->saveIndex($dimensions, $documents);
    }

    public function deleteIndex(array $dimensions, Traversable $documents): void
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        $this->saveHandlerFactory->get($documentName)->deleteIndex($dimensions, $documents);
    }
}
