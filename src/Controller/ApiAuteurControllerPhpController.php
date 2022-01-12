<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Repository\AuteurRepository;
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

class ApiAuteurController extends AbstractController
{
    /**
     * @Route("/api/auteurs", name="api_auteurs", methods={"GET"})
     */
    public function list(AuteurRepository $repo, SerializerInterface $serializer)
    {
        $auteurs = $repo->findAll();
        $resultat = $serializer->serialize($auteurs, 'json', ['groups' => ['listAuteurFull']] );
        // return $this->render('api_auteur/index.html.twig', [
        //     'controller_name' => 'ApiAuteurController',
        // ]);
        return new JsonResponse($resultat, 200,[], true);
    }
    /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_show", methods={"GET"})
     */
    public function show(Auteur $auteurs ,AuteurRepository $repo, SerializerInterface $serializer)
    {
         
        $resultat = $serializer->serialize(
            $auteurs, 
        'json',
        [
            'groups' => ['listAuteurSimple']
        ] );
        // return $this->render('api_auteur/index.html.twig', [
        //     'controller_name' => 'ApiAuteurController',
        // ]);
        return new JsonResponse($resultat, 200,[], true);
    }
    /**
     * @Route("/api/auteurs", name="api_auteurs_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager,SerializerInterface $serializer, ValidatorInterface $validator)
    {
         $data= $request->getContent();
        // $auteurs =new Auteur();
        //$serializer->deserialize($data,Auteur::class,'json',['object_to_populate'=> $auteurs]);
        $auteurs= $serializer->deserialize($data,Auteur::class,'json');

        //gestion des erreurs de validation 
        $errors =$validator->validate($auteurs);
        if( count($errors)){
            $errorsJson=$serializer->serialize($errors,'json');
            return new JsonResponse($errorsJson,Response::HTTP_BAD_REQUEST,[], true);
        }
        $manager->persist($auteurs); 
        $manager->flush(); 
       
        return new JsonResponse(
            "le auteur a bien été crée",
            Response::HTTP_CREATED,
            ["location"=> $this->generateUrl(
                'api_auteurs_show',
                ["id"=>$auteurs->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL)],
                true);
    }
    /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_update", methods={"PUT"})
     */
    public function edit(Auteur $auteurs ,Request $request,EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
         
        $data=$request->getContent();
        $serializer->deserialize($data,Auteur::class,'json',['object_to_populate'=>$auteurs]);
        
        //gestion des erreurs de validation 
        $errors =$validator->validate($auteurs);
        if( count($errors)){
            $errorsJson=$serializer->serialize($errors,'json');
            return new JsonResponse($errorsJson,Response::HTTP_BAD_REQUEST,[], true);
        }

        $manager->persist($auteurs); 
        $manager->flush(); 
        
        return new JsonResponse("le auteur a bien été modifié ", 200,[], true);
      
       
       
    }
     /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_delete", methods={"DELETE"})
     */
    public function delete(Auteur $auteurs ,EntityManagerInterface $manager)
    {

        $manager->remove($auteurs); 
        $manager->flush(); 
        
        return new JsonResponse("le auteur a bien été supprimé", 200,[], true);

    }

}
