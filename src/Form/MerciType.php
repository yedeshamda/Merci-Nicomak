<?php
namespace App\Form;

use App\Entity\Merci;
use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class MerciType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $user = $options['user'];  // Récupère l'utilisateur connecté
        $employees = $options['employees'];  // Récupère les employés passés en option

        $builder
            ->add('fromEmployee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'username',
                'disabled' => true,  // Empêche l'édition de "fromEmployee"
            ])
            ->add('toEmployee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'username',
                'choices' => $employees,  // Utilise les employés filtrés
            ])
            ->add('message', TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Merci::class,
            'user' => null,  // Ajouter l'option 'user' pour le formulaire
            'employees' => []  // Ajouter l'option 'employees', qui sera passée depuis le contrôleur
        ]);
    }
}