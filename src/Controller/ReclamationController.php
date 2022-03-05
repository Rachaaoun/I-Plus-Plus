<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
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
use \Twilio\Rest\Client;
use App\Data\SearchData;
use App\Form\SearchForm;



/**
 * @Route("/reclamation")
 */
class ReclamationController extends AbstractController 
{

    private $twilio;

    


    /**
     * @Route("/", name="reclamation_index", methods={"GET"})
     */
    public function index(ReclamationRepository $reclamationRepository,Request $request): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        $reclamation= $reclamationRepository->findSearch($data);
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamation,
            'form' => $form->createView(),
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


            $sid = "AC1a089fe9379ec0c1d0bce40ea126d0fd"; // Your Account SID from www.twilio.com/console
            $token = "795132b6a9d6ec3a1ed3b610a548dd84"; // Your Auth Token from www.twilio.com/console
            
            $client = new Client($sid, $token);
             $message = $client->messages 
            ->create("+21625058640", // to 
                     array(  
                         "messagingServiceSid" => "MG2ce7c477daa3dd9b0284211cd90c32cf",      
                         "body" => "You reclamation was sended to the admin " 
                     ) 
            ); 
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
}
