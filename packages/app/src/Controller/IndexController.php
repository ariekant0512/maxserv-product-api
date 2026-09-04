<?php

declare(strict_types=1);

namespace MaxServ\App\Controller;

use MaxServ\App\Repository\ProductRepository;
use MaxServ\Core\Render\TemplateRenderer;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class IndexController
{
    public function __construct(
        private TemplateRenderer $templateRenderer,
        private ProductRepository $productRepository,
    ) {
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function index(): void
    {
        $products = $this->productRepository->findAll();

        echo $this->templateRenderer->render('index.html.twig', [
            'products' => $products,
        ]);
    }
}
