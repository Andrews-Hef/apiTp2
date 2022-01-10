<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="api_genres", methods={"GET"})
     */
    public function list(GenreRepository $repo, SerializerInterface $serializer)
    {
        $genres = $repo->findAll();
        $resultat = $serializer->serialize($genres, 'json', ['groups' => ['listGenreFull']] );
        // return $this->render('api_genre/index.html.twig', [
        //     'controller_name' => 'ApiGenreController',
        // ]);
        return new JsonResponse($resultat, 200,[], true);
    }
    /**
     * @Route("/api/genres/{id}", name="api_genres_show", methods={"GET"})
     */
    public function show(Genre $genres ,GenreRepository $repo, SerializerInterface $serializer)
    {
         
        $resultat = $serializer->serialize(
            $genres, 
        'json',
        [
            'groups' => ['listGenreSimple']
        ] );
        // return $this->render('api_genre/index.html.twig', [
        //     'controller_name' => 'ApiGenreController',
        // ]);
        return new JsonResponse($resultat, 200,[], true);
    }
}
