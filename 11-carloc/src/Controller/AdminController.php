<?php

namespace App\Controller;

use App\Entity\Order;
use DateTime;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Form\OrderType;
use App\Form\RegistrationFormType;
use App\Form\VehicleType;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'pageTitle' => 'Administration'
        ]);
    }

    // *********************************************** Gestion des utilisateurs ***********************************************
    // Voir les utilisateurs
    #[Route('/admin/users', name: 'admin_users')]
    public function findUsers(UserRepository $repository): Response {
        $users = $repository->findAll(); // Trouver les utilisateurs

        return $this->render('admin/users.html.twig', [
            'users' => $users,
            'pageTitle' => 'Administration - Gestion des membres'
        ]);
    }

    // Ajouter et éditer les utilisateurs
    #[Route('/admin/users/add', name: 'admin_users_add')]
    #[Route('/admin/users/edit/{id}', name: 'admin_users_edit')]
    public function manageUser(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasher, User $user = null): Response {
        if(!$user) $user = new User;

        $form = $this->createForm(RegistrationFormType::class, $user, [
            'edit_mode' => $user->getId() ? true : false
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Si le champ "password" existe, il s'agit de la création d'un nouvel utilisateur
            if ($form->has('password') && $form->get('password')->getData()) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            
                $user->setCreatedAt(new DateTime()); // En plus de mot du passe, il faut insérer la date de création 
            }

            $manager->persist($user);
            $manager->flush();
            
            $this->addFlash("success", "✅ Utilisateur mis à jour avec succès");
            return $this->redirectToRoute("admin_users");
        }

        return $this->render("registration/register.html.twig", [
            "registrationForm" => $form->createView(),
            "editMode" => $user->getId() !== NULL,
            'pageTitle' => "Administration - Créer/Modifier un utilisateur"
        ]);
    }

    // Supprimer les utilisateurs
    #[Route('/admin/users/delete/{id}', name: 'admin_users_delete')]
    public function deleteUser(User $user, EntityManagerInterface $manager): Response {
        $currentUser = $this->getUser();

        if ($currentUser && $currentUser->getId() === $user->getId()) {
            return throw $this->createAccessDeniedException("Vous ne pouvez pas supprimer votre propre compte depuis le panneau d'administration.");
        } else {
            $manager->remove($user);
            $manager->flush();
            $this->addFlash("success", "✅ Utilisateur supprimé avec succès");
            return $this->redirectToRoute("admin_users");
        }
    }

    // *********************************************** Gestion des véhicules ***********************************************

    // Voir les véhicules
    #[Route('/admin/vehicles', name: 'admin_vehicles')]
    public function findVehicles(VehicleRepository $repository): Response {
        $vehicles = $repository->findAll(); // Trouver les véhicules

        return $this->render('admin/vehicles.html.twig', [
            'vehicles' => $vehicles,
            'pageTitle' => 'Administration - Gestion des véhicules'
        ]);
    }

    // Ajouter et éditer les véhicules
    #[Route('/admin/vehicles/add', name: 'admin_vehicles_add')]
    #[Route('/admin/vehicles/edit/{id}', name: 'admin_vehicles_edit')]
    public function manageVehicles(Request $request, EntityManagerInterface $manager, Vehicle $vehicle = null): Response {
        if(!$vehicle) {
            $vehicle = new Vehicle;
            $vehicle->setCreatedAt(new \DateTime());
        }

        $form = $this->createForm(VehicleType::class, $vehicle);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($vehicle);
            $manager->flush();
            
            $this->addFlash("success", "✅ Véhicule mis à jour avec succès");
            return $this->redirectToRoute("admin_vehicles");
        }

        return $this->render("admin/form_vehicle.html.twig", [
            "formVehicle" => $form->createView(),
            "editMode" => $vehicle->getId() !== NULL,
            'pageTitle' => 'Ajouter/Modifier un véhicule'
        ]);
    }

    // Supprimer les véhicules
    #[Route('/admin/vehicles/delete/{id}', name: 'admin_vehicles_delete')]
    public function deleteVehicle(Vehicle $vehicle, EntityManagerInterface $manager): Response {
        $manager->remove($vehicle);
        $manager->flush();

        $this->addFlash("success", "✅ Utilisateur supprimé avec succès");
        return $this->redirectToRoute("admin_users");
    }

    // *********************************************** Gestion des commandes ***********************************************

    // Voir les commandes
    #[Route('/admin/orders', name: 'admin_orders')]
    public function findOrders(OrderRepository $repository): Response {
        $orders = $repository->findAll(); // Trouver les commandes

        return $this->render('admin/orders.html.twig', [
            'orders' => $orders,
            'pageTitle' => 'Administration - Gestion des commandes'
        ]);
    }

    // Ajouter et éditer les commandes
    #[Route('/admin/orders/add', name: 'admin_orders_add')]
    #[Route('/admin/orders/edit/{id}', name: 'admin_orders_edit')]
    public function manageOrders(Request $request, EntityManagerInterface $manager, Order $order = null): Response {
        if(!$order) {
            $order = new Order;
            $order->setCreatedAt(new \DateTime());
        }

        $form = $this->createForm(OrderType::class, $order, [
            'admin_mode' => true
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $totalPrice = $order->calculateTotalDailyPrice() * $order->calculateTotalDays();
            $order->setTotalPrice($totalPrice);

            $manager->persist($order);
            $manager->flush();
            
            $this->addFlash("success", "✅ Commande mise à jour avec succès");
            return $this->redirectToRoute("admin_orders");
        }

        return $this->render("home/order.html.twig", [
            'formOrder' => $form->createView(),
            'adminCreateMode' => true,
            'adminEditMode' => $order->getId() !== NULL,
            'pageTitle' => 'Ajouter/Modifier une commande'
        ]);
    }

    // Supprimer les commandes
    #[Route('/admin/orders/delete/{id}', name: 'admin_orders_delete')]
    public function deleteOrder(Order $order, EntityManagerInterface $manager): Response {
        $manager->remove($order);
        $manager->flush();

        $this->addFlash("success", "✅ Commande supprimée avec succès");
        return $this->redirectToRoute("admin_orders");
    }
}
