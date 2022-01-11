<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Util\Json;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    /**
     * @Route("/api/genres", name="api_genres_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager,SerializerInterface $serializer, ValidatorInterface $validator)
    {
         $data= $request->getContent();
        // $genres =new Genre();
        //$serializer->deserialize($data,Genre::class,'json',['object_to_populate'=> $genres]);
        $genres= $serializer->deserialize($data,Genre::class,'json');

        //gestion des erreurs de validation 
        $errors =$validator->validate($genres);
        if( count($errors)){
            $errorsJson=$serializer->serialize($errors,'json');
            return new JsonResponse($errorsJson,Response::HTTP_BAD_REQUEST,[], true);
        }
        $manager->persist($genres); 
        $manager->flush(); 
       
        return new JsonResponse(
            "le genre a bien été crée",
            Response::HTTP_CREATED,
            ["location"=> $this->generateUrl(
                'api_genres_show',
                ["id"=>$genres->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL)],
                true);
    }
    /**
     * @Route("/api/genres/{id}", name="api_genres_update", methods={"PUT"})
     */
    public function edit(Genre $genres ,Request $request,EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
         
        $data=$request->getContent();
        $serializer->deserialize($data,Genre::class,'json',['object_to_populate'=>$genres]);
        
        //gestion des erreurs de validation 
        $errors =$validator->validate($genres);
        if( count($errors)){
            $errorsJson=$serializer->serialize($errors,'json');
            return new JsonResponse($errorsJson,Response::HTTP_BAD_REQUEST,[], true);
        }

        $manager->persist($genres); 
        $manager->flush(); 
        
        return new JsonResponse("le genre a bien été modifié ", 200,[], true);
      
       
       
    }
     /**
     * @Route("/api/genres/{id}", name="api_genres_delete", methods={"DELETE"})
     */
    public function delete(Genre $genres ,EntityManagerInterface $manager)
    {

        $manager->remove($genres); 
        $manager->flush(); 
        
        return new JsonResponse("le genre a bien été supprimé", 200,[], true);

    }

}
