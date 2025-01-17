<?php
/**
 * @author Łukasz Marszałek <lmarszalek@divante.co>
 * @author Piotr Rugała <piotr@isedo.pl>
 * @copyright Copyright (c) 2019 Divante Ltd. (https://divante.co)
 */

declare(strict_types=1);

namespace DivanteTranslationBundle\Controller;

use DivanteTranslationBundle\Provider\FormalityProviderInterface;
use DivanteTranslationBundle\Provider\ProviderFactory;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/object")
 */
final class ObjectController extends FrontendController
{
    private string $sourceLanguage;
    private string $provider;

    public function __construct(string $sourceLanguage, string $provider)
    {
        $this->sourceLanguage = $sourceLanguage;
        $this->provider = $provider;
    }

    /**
     * @Route("/translate-field", methods={"GET"})
     */
    public function translateFieldAction(Request $request, ProviderFactory $providerFactory): JsonResponse
    {
        try {
            $object = DataObject::getById($request->get('sourceId'));

            if (!$object) {
                throw new NotFoundHttpException('Object not found');
            }

            $lang = $request->get('lang');
            $fieldName = 'get' . ucfirst($request->get('fieldName'));

            $data = $object->$fieldName($lang) ?: $object->$fieldName($this->sourceLanguage);

            if (!$data) {
                return $this->json([
                    'success' => false,
                    'message' => 'Data are empty',
                ]);
            }

            $provider = $providerFactory->get($this->provider);
            if (
                $request->get('formality') &&
                $provider instanceof FormalityProviderInterface
            ) {
                $provider->setFormality($request->get('formality'));
            }

            $data = strip_tags($data);
            $data = $provider->translate($data, $lang);
        } catch (\Throwable $exception) {
            return $this->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }

        return $this->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
