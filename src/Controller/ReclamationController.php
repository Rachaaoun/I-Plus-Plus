<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Typereclamations;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Repository\TypereclamationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use \Twilio\Rest\Client;



/**
 * @Route("/reclamation")
 */
class ReclamationController extends AbstractController 
{

    
    const ATTRIBUTES_TO_SERIALIZE =['id','userId','sujetRec','niveau'];

    private $twilio;

    


    /**
     * @Route("/", name="reclamation_index", methods={"GET"})
     */
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="reclamation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();


            $sid = "ACc2758e503eb8d85575ad23a0fa4bcb15"; // Your Account SID from www.twilio.com/console
            $token = "722177e63bd8ec3b0af93eafb21126da"; // Your Auth Token from www.twilio.com/console
            
            $client = new Client($sid, $token);
             $message = $client->messages 
            ->create("+21625058640", // to 
                     array(  
                         "messagingServiceSid" => "MG4fe1ea3899ac9a3000632f5fc6e53417",      
                         "body" => "You reclamation was sended to the admin " 
                     ) 
            ); 

            $client->http->curlopts[CURLOPT_SSL_VERIFYPEER]=false;
+			$client->http->curlopts[CURLOPT_SSL_VERIFYHOST]=2;
            print($message->sid);
            // $sid = getenv("TWILIO_ACCOUNT_SID");
            // $token = getenv("TWILIO_AUTH_TOKEN");
            // $twilio = new Client($sid, $token);

            // $message = $twilio->messages
            //                 ->create("whatsapp:+21658307625", // to
            //                         ["from" => "+21658307625", "body" => "Hi there"]
            //                 );

           // print($message->sid);
        //     $sid = getenv("TWILIO_ACCOUNT_SID");
        // $token = getenv("TWILIO_AUTH_TOKEN");
        // $client = new Client('AC43cd140f0a451f236314dc2b20e42d9e', 'ac19acdcb6ec7f981a1c9ccc9b38ff6a');
        // $twilio_number = "+21658307625";
        //   //  $output->writeln('SMSes to send: #' );
        //    // $sender = $this->getContainer()->getParameter('twilio_number');
        //    $from = $request->request->get('From');
        //    $now = new \DateTime();
        //    $body = $now->format('Y-m-d H:i:s');
   
        //    $client->messages->create(
        //     // Where to send a text message (your cell phone?)
        //     '+21658478298',
        //     array(
        //         'from' => $twilio_number,
        //         'body' => 'I sent this message in under 10 minutes!'
        //     )
        // );

              
            // $output->writeln('SMS #' . $message->sid . ' sent to: ' .'user numberr');
       
            return $this->redirectToRoute('reclamation_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reclamation_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reclamation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/reclamation/list")
     * @param ReclamationRepository $repo
     */
    public function getList(ReclamationRepository $repo,SerializerInterface $serializer):Response{
     
        $reclamations=$repo->findAll();
        $json=$serializer->serialize($reclamations,'json', ['groups' => ['reclamation']]);


        return $this->json(['reclamation'=>$reclamations],Response::HTTP_OK,[],[
            'attributes'=>self::ATTRIBUTES_TO_SERIALIZE
        ]);



    }
    /**
     * @Route("/modifier/reclamation/{id}" , name="rec_edit" ,  methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function reclamationModif(TypereclamationsRepository $repotype,Request $request,SerializerInterface $serializer,$id,ReclamationRepository $repo)
    {
        $reclamation=$repo->findOneById($id);
        $sujet=$request->query->get('sujet');
        $niveau=$request->query->get('niveau');
        
       
        $em=$this->getDoctrine()->getManager();
      
       $reclamation->setSujetRec($sujet);
       $reclamation->setNiveau($niveau);
       $reclamation->setUserId(1);
       $type=$repotype->findOneById(1);
       $reclamation->setTypereclamations($type);
        
        $em->persist($reclamation);
        $em->flush();
        $json = $serializer->serialize(
            $reclamation,
            'json',
            ['groups' => 'reclamation']
        );
       // $serializer=new Serializer([new ObjectNormalizer()]);
       // $formatted=$serializer->normalize($reclamation);
        return new JsonResponse($json);
    }


    /**
     * @Route("/reclamation/delete", name="supprimer_rec")
     */
    public function supprimerReclamation(Request $request, ReclamationRepository $repo): Response
    {

        $id =$request->get("id");
        $em=$this->getDoctrine()->getManager();

     $d=   $repo->find($id);

        if($d != null){
            $em->remove($d);
            $em->flush();
            $serializer=new Serializer([new ObjectNormalizer()]);
            $formatted=$serializer->normalize("reclamation a eté supprimeé");
            return new JsonResponse($formatted);
        }

       return  new JsonResponse("Id Invalide");
    }

     /**
     * @Route("/AddReclamation/json", name="AddReclamation")
     */
    public function AddReclamationJSON(Request $request,NormalizerInterface $Normalizer,TypereclamationsRepository $repo)
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = new Reclamation();
        $idType=$request->get('idType');
        $Type = $repo->findOneById($idType);
        $reclamation->setTypereclamations($Type);
        $reclamation->setSujetRec($request->get('sujet'));
        $reclamation->setNiveau($request->get('niveau'));
        $reclamation->setUserId(1);
       
        $em->persist($reclamation);
        $em->flush();

        $jsonContent= $Normalizer->normalize($reclamation,'json',['groups'=>"reclamation:read"]);
        return new Response(json_encode($jsonContent));;
    }

    
     /**
     * @Route("/AllReclamation/type/json")
     * @param TypeReclamationRepository $repo
     */
    public function getReclamationListByType(TypereclamationsRepository $repo,ReclamationRepository $reclamationRepository,Request $request,SerializerInterface $serializer):Response{
     
        $id=$request->query->get('id');
        $type=$repo->findOneById($id);
        $typeId=$type->getId();
        $reclamations=$type->getReclamations();
        $json=$serializer->serialize($reclamations,'json', ['groups' => ['reclamation']]);


        return $this->json(['reclamation'=>$reclamations],Response::HTTP_OK,[],[
            'attributes'=>self::ATTRIBUTES_TO_SERIALIZE
        ]);

}

}
