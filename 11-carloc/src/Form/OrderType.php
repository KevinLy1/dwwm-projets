<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class OrderType extends AbstractType
{
    private VehicleRepository $vehicleRepository;

    public function __construct(VehicleRepository $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $vehicles = $this->vehicleRepository->findAll();
        
        $builder
            ->add('idVehicle', EntityType::class, [
                'class' => Vehicle::class,
                'choices' => $vehicles,
                'choice_label' => 'title',
                'choice_attr' => function (Vehicle $vehicle) {
                    // Définir l'attribut data-price pour chaque option en utilisant le prix du véhicule
                    return ['data-price' => $vehicle->getDailyPrice()];
                },
                'multiple' => true,
                'expanded' => true
            ])
            ->add('dateTimeDeparture', DateTimeType::class, [
                'data' => new \DateTime(), // Date du jour
                'constraints' => [
                    new Assert\Callback(['callback' => [$this, 'validateDateTimeDeparture']])
                ]
            ])
            ->add('dateTimeEnd', DateTimeType::class, [
                'data' => (new \DateTime())->modify('+1 day'), // Date du jour + 1 jour par défaut
                'constraints' => [
                    new Assert\Callback(['callback' => [$this, 'validateDateTimeEnd']])
                ]
            ])
        ;

        if($options['admin_mode']) {
            $builder->add('idUser', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username'
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            "admin_mode" => false,
            'constraints' => [
                new Assert\Callback(['callback' => [$this, 'validateVehicle']]),
                new Assert\Callback(['callback' => [$this, 'validateDates']])
            ]
        ]);
    }

    public function validateVehicle(Order $currentOrder, ExecutionContextInterface $context)
    {
        // Récupérer la date de début saisie dans le formulaire
        $formStartDate = $currentOrder->getDateTimeDeparture();

        // Récupérer toutes les voitures associées à la commande
        $vehicles = $currentOrder->getIdVehicle();
    
        // Vérifier s'il existe des voitures associées à la commande
        if ($vehicles->count() > 0) {
            // Trouver la date de fin la plus récente parmi toutes les voitures associées à la commande
            $latestDateTimeEnd = null;
    
            // Parcourir les véhicules
            foreach ($vehicles as $vehicle) {
    
                // Retrouver les commandes d'un véhicule
                $orders = $vehicle->getOrders();
    
                // Pour toutes les commandes associées à ce véhicule
                foreach ($orders as $associatedOrder) {
                    // Vérifier si la commande est différente de la commande actuelle
                    if ($associatedOrder !== $currentOrder) {
                        $dateTimeEnd = $associatedOrder->getDateTimeEnd(); // Récupérer la date de fin
                        if ($latestDateTimeEnd === null || $dateTimeEnd > $latestDateTimeEnd) {
                            $latestDateTimeEnd = $dateTimeEnd; // remplacer par la nouvelle date si plus récente
                        }
                    }
                }
            }
    
            // Vérifier que la date de fin la plus récente est inférieure à la nouvelle date de début
            if ($latestDateTimeEnd >= $formStartDate) {
                $formattedDate = $latestDateTimeEnd->format('d/m/Y à H:i:s');
                $context->buildViolation("Un ou plusieurs véhicules sont déjà loués aux dates demandées. La première disponibilité est à partir du $formattedDate.")
                    ->atPath('idVehicle')
                    ->addViolation();
            }
        }
    }

    public function validateDates(Order $order, ExecutionContextInterface $context)
    {
        $startDate = $order->getDateTimeDeparture();
        $endDate = $order->getDateTimeEnd();

        if ($endDate < $startDate) {
            $context->buildViolation('La date de fin doit être supérieure ou égale à la date de début.')
                ->atPath('dateTimeEnd')
                ->addViolation();
        }
    }

    public function validateDateTimeDeparture($value, ExecutionContextInterface $context)
    {
        // Vérifie si la date sélectionnée est dans le passé
        $now = new \DateTime();
        if ($value < $now) {
            $context->buildViolation('La date ne peut pas être dans le passé.')
                ->atPath('dateTimeDeparture')
                ->addViolation();
        }
    }

    public function validateDateTimeEnd($value, ExecutionContextInterface $context)
    {
        // Vérifie si la date sélectionnée est dans le passé
        $now = new \DateTime();
        if ($value < $now) {
            $context->buildViolation('La date ne peut pas être dans le passé.')
                ->atPath('dateTimeEnd')
                ->addViolation();
        }
    }
}
