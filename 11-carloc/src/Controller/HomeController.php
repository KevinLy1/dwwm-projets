<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'pageTitle' => 'Accueil'
        ]);
    }

    #[Route('/my_orders', name: 'my_orders')]
    public function myOrders(OrderRepository $repo): Response
    {
        $user = $this->getUser();
        $myOrders = $repo->findBy([
            'idUser' => $user->getId()
        ]);

        return $this->render('home/my_orders.html.twig', [
            'pageTitle' => "Mes commandes",
            'myOrders' => $myOrders
        ]);
    }

    #[Route('/inventory', name: 'inventory')]
    public function inventory(VehicleRepository $repo): Response
    {
        $vehicles = $repo->findAll();

        return $this->render('home/inventory.html.twig', [
            'pageTitle' => 'Catalogue',
            'vehicles' => $vehicles
        ]);
    }

    #[Route('/inventory/show/{id}', name: 'show_vehicle')]
    public function showVehicle(VehicleRepository $repo, $id): Response
    {
        $vehicle = $repo->find($id);

        return $this->render('home/show_vehicle.html.twig', [
            'pageTitle' => 'Détail du véhicule',
            'vehicle' => $vehicle
        ]);
    }

    #[Route('/order', name: 'order')]
    public function newOrder(Request $request, EntityManagerInterface $manager, Order $order = null): Response {
        $user = $this->getUser();

        $order = new Order;
        $order->setIdUser($user);
        $order->setCreatedAt(new \DateTime());
        
        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $totalPrice = $order->calculateTotalDailyPrice() * $order->calculateTotalDays();
            $order->setTotalPrice($totalPrice);

            $manager->persist($order);
            $manager->flush();
            
            $this->addFlash("success", "✅ Commande créée avec succès");
            return $this->redirectToRoute("my_orders");
        }

        return $this->render("home/order.html.twig", [
            'formOrder' => $form->createView(),
            'pageTitle' => 'Louer'
        ]);
    }

    #[Route('/order/edit/{id}', name: 'order_edit')]
    public function editOrder(Request $request, EntityManagerInterface $manager, Order $order = null): Response {
        if(!$order) {
            throw $this->createNotFoundException("Vous ne pouvez pas éditer une commande non existante.");
        } else if ($order->getIdUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Vous n'êtes pas autorisé à éditer cette commande.");
        }

        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $totalPrice = $order->calculateTotalDailyPrice() * $order->calculateTotalDays();
            $order->setTotalPrice($totalPrice);

            $manager->persist($order);
            $manager->flush();
            
            $this->addFlash("success", "✅ Commande éditée avec succès");
            return $this->redirectToRoute("show_order", [
                'id' => $order->getId()
            ]);
        }

        return $this->render("home/order.html.twig", [
            'formOrder' => $form->createView(),
            'pageTitle' => 'Éditer une commande'
        ]);
    }

    #[Route('/order/show/{id}', name: 'show_order')]
    public function showOrder(OrderRepository $repo, $id): Response
    {
        if($id !== null && is_numeric($id)) {
            $order = $repo->find($id);
        } else {
            throw $this->createNotFoundException("Le format de commande est invalide.");
        }

        if (!$order) {
            throw $this->createNotFoundException("La commande n'existe pas.");
        } elseif($order->getIdUser() !== $this->getUser() && !in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            throw $this->createAccessDeniedException("Vous n'êtes pas autorisé à voir cette commande.");
        }

        return $this->render('home/show_order.html.twig', [
            'pageTitle' => 'Détail de la location',
            'order' => $order
        ]);
    }
}
