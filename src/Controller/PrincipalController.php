<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrincipalController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager
        ->getRepository(Product::class)
        ->findAll();

        return $this->render('principal/index.html.twig', [
            'controller_name' => 'PrincipalController',
            'products' => $products
        ]);
    }

    #[Route('/viajes', name: 'viajes', methods: ['GET'])]
    public function viajes(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager
            ->getRepository(Product::class)
            ->findAll();

        return $this->render('viajes/viajes.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/actividades', name: 'actividades', methods: ['GET'])]
    public function actividades(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager
            ->getRepository(Product::class)
            ->findAll();

        return $this->render('actividades/actividades.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/hoteles', name: 'hoteles', methods: ['GET'])]
    public function hoteles(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager
            ->getRepository(Product::class)
            ->findAll();

        return $this->render('hoteles/hoteles.html.twig', [
            'products' => $products,
        ]);
    }

}
