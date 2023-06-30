<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Entity\Commande;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManager;
use App\Repository\SliderRepository;
use App\Repository\ChambreRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(SliderRepository $repo): Response
    {
        $slider = $repo->findAll();
        // $slider = $repo->findBy(['page'=>'spa']);


        return $this->render('app/index.html.twig', [
            'slider' => $slider
        ]);
    }

    #[Route('/chambre', name: 'chambre')]
    public function chambre(ChambreRepository $repo): Response
    {
        $chambres = $repo->findAll();

        return $this->render('app/chambre/chambre.html.twig', [
            'chambres' => $chambres
        ]);
    }

    #[Route('/detail-chambre/{id}', name: 'detail-chambre')]
    public function reservationChambre(Chambre $chambre,ChambreRepository $repo, Request $request, EntityManagerInterface $manager)
    {   
        $commande = new Commande;
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid())
        {
            
            $arrivee = $commande->getDateArrivee();
            if ($arrivee->diff($commande->getDateArrivee())->invert == 1)
            {
                $this->addFlash('danger', 'Une période de temps ne peut pas être négative.');
                return $this->redirectToRoute('détail-chambre', [
                    'id' => $chambre->getId()
                ]);
            }
            $jours = $arrivee->diff($commande->getDateDepart())->days;
            $prixTotal = $chambre->getPrixJournalier() * $jours;
            
            $commande->setPrixTotal($prixTotal);
            $commande->setDateEnregistrement(new \DateTime());
            $commande->setChambre($chambre);
            
            $manager->persist($commande);
            $manager->flush();
            $this->addFlash('success', 'Votre chambre a bien été reservé');
            return $this->redirectToRoute('reservation', [
                'id' => $commande->getId()
            ]);

        }

        return $this->render('app/chambre/detailChambre.html.twig',[
            'item' => $chambre,
            'formCommande' => $form->createView()
        ]);
    }

    #[Route('reservation/{id}', name: 'reservation')]
    public function reservation(Commande $commande, Request $request, CommandeRepository $repo)
    {
        

        return $this->render('app/chambre/reservation.html.twig', [
            'commande' => $commande
        ]);
    }

    #[Route('restauration', name: 'restauration')]
    public function restauration()
    {
        return $this->render('app/restauration.html.twig');
    }

    
}
