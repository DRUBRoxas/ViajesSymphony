<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/productos')]
class ProductController extends AbstractController
{
    #[Route('/index', name: 'app_product_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager
            ->getRepository(Product::class)
            ->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }


    
    #[Route('/viajes', name: 'app_product_viajes', methods: ['GET'])]
    public function viajes(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager
            ->getRepository(Product::class)
            ->findAll();

        return $this->render('viajes/viajesindex.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/actividades', name: 'app_product_actividades', methods: ['GET'])]
    public function actividades(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager
            ->getRepository(Product::class)
            ->findAll();

        return $this->render('actividades/actividadesindex.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/hoteles', name: 'app_product_hoteles', methods: ['GET'])]
    public function hoteles(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager
            ->getRepository(Product::class)
            ->findAll();

        return $this->render('hoteles/hotelesindex.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheroimagen = $form['FicheroImagen']->getData();
            if ($ficheroimagen) {
                $nombrearchivo = $ficheroimagen->getClientOriginalName();
                $ficheroimagen->move(
                    $this->getParameter('directorio_imagenes'),
                    $nombrearchivo
                );
                
                $product->setImagen($nombrearchivo);
                $entityManager->persist($product);
                $entityManager->flush();
                $this->forward('App\Controller\PrincipalController::index');
            }
            else
            {
                $nombrearchivo = "sinimagen.jpg";
                $product->setImagen($nombrearchivo);
                $entityManager->persist($product);
                $entityManager->flush();
                $this->forward('App\Controller\PrincipalController::index');
            }
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/show/{idproduct}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/edit/{idproduct}', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{idproduct}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getIdproduct(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }

}
