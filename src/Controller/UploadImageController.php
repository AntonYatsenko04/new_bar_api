<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class UploadImageController
{
    public function __invoke(Request $request, SerializerInterface $serializer): JsonResponse
    {
        

//        $image = $request->files->get('image');
//        $menuId = $request->request->get('menuId');
//
//        if (!$image || !$menuId) {
//            throw new BadRequestHttpException('Both image and menuId are required.');
//        }
//
//        if (!$image->isValid() || !in_array($image->getMimeType(), ['image/jpeg', 'image/png'])) {
//            throw new BadRequestHttpException('Invalid image file.');
//        }
//
//        $uploadsDir = __DIR__ . '/Users/user/PhpstormProjects/bar_api/images';
//        $filename = uniqid(true, true) . '.' . $image->guessExtension();
//        $image->move($uploadsDir, $filename);

        return new JsonResponse([
            'message' => 'Image uploaded successfully!',
            'filename' => '$filename',
            'menuId' => '$menuId',
            'menuIds' => $request->files,
        ], 200);
    }
}
