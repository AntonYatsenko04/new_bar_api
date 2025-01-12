<?php

namespace App\ApiResource\Menu;


use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;
use App\Controller\UploadImageController;
use App\State\MenuImageStateProcessor;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

//#[Vich\Uploadable]
#[ApiResource(
    types: ['https://schema.org/MediaObject'],
    operations: [
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']],
            controller: UploadImageController::class,
            openapi: new Operation(
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary'
                                    ]
                                ]
                            ]
                        ]
                    ]),
                ),
            ),
            deserialize: false,
            write: true,
            processor: MenuImageStateProcessor::class,
        )
    ],
    normalizationContext: ['groups' => ['media_object:read']],
)]
class MenuImageResource
{
    #[ApiProperty(writable: false)]
    public ?string $filePath = null;


    #[ApiProperty(writable: false, types: ['https://schema.org/contentUrl'])]
    #[Groups(['media_object:read'])]
    public ?string $contentUrl = null;

//    #[Vich\UploadableField(mapping: 'media_object', fileNameProperty: 'filePath')]
    #[Assert\NotNull]
    private ?File $file = null;

    public function getMenu(): ?int
    {
        return $this->menu;
    }

    public function setMenu(?int $menu): void
    {
        $this->menu = $menu;
    }
}
