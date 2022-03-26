<?php

namespace App\Controller;

use App\Entity\Typereclamations;
use App\Form\TypereclamationsType;
use App\Repository\TypereclamationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/typereclamations")
 */
class TypereclamationsController extends AbstractController
{
    const ATTRIBUTES_TO_SERIALIZE =['id','niveau'];

    /**
     * @Route("/", name="app_typereclamations_index", methods={"GET"})
     */
    public function index(TypereclamationsRepository $typereclamationsRepository): Response
    {
        return $this->render('typereclamations/index.html.twig', [
            'typereclamations' => $typereclamationsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_typereclamations_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TypereclamationsRepository $typereclamationsRepository): Response
    {
        $typereclamation = new Typereclamations();
        $form = $this->createForm(TypereclamationsType::class, $typereclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typereclamationsRepository->add($typereclamation);
            return $this->redirectToRoute('app_typereclamations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('typereclamations/new.html.twig', [
            'typereclamation' => $typereclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_typereclamations_show", methods={"GET"})
     */
    public function show(Typereclamations $typereclamation): Response
    {
        return $this->render('typereclamations/show.html.twig', [
            'typereclamation' => $typereclamation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_typereclamations_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Typereclamations $typereclamation, TypereclamationsRepository $typereclamationsRepository): Response
    {
        $form = $this->createForm(TypereclamationsType::class, $typereclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typereclamationsRepository->add($typereclamation);
            return $this->redirectToRoute('app_typereclamations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('typereclamations/edit.html.twig', [
            'typereclamation' => $typereclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_typereclamations_delete", methods={"POST"})
     */
    public function delete(Request $request, Typereclamations $typereclamation, TypereclamationsRepository $typereclamationsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typereclamation->getId(), $request->request->get('_token'))) {
            $typereclamationsRepository->remove($typereclamation);
        }

        return $this->redirectToRoute('app_typereclamations_index', [], Response::HTTP_SEE_OTHER);
    }


     /**
     * @Route("/AddType/json", name="Addtype")
     */
    public function AddPostsJSON(Request $request,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $Type = new Typereclamations();
        $Type->setNiveau($request->get('niveau'));
       
        $em->persist($Type);
        $em->flush();

        $jsonContent= $Normalizer->normalize($Type,'json',['groups'=>"type:read"]);
        return new Response(json_encode($jsonContent));;
    }

    /**
     * @Route("/type/list")
     * @param TypeReclamationsRepository $repo
     */
    public function getList(TypeReclamationsRepository $repo,SerializerInterface $serializer):Response{
     
        $typereclamations=$repo->findAll();
        $json=$serializer->serialize($typereclamations,'json', ['groups' => ['typereclamation']]);


        return $this->json(['typereclamation'=>$typereclamations],Response::HTTP_OK,[],[
            'attributes'=>self::ATTRIBUTES_TO_SERIALIZE
        ]);



    }
}
