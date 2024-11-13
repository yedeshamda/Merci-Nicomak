<?php
namespace App\DataFixtures;

use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $employeesData = [
            ['name' => 'Myriam', 'password' => 'password123'],
            ['name' => 'Geoffroy', 'password' => 'password123'],
            ['name' => 'Laura', 'password' => 'password123'],
            ['name' => 'Céline', 'password' => 'password123'],
            ['name' => 'Alice', 'password' => 'password123'],
            ['name' => 'Laetitia', 'password' => 'password123'],
        ];

        foreach ($employeesData as $data) {
            $employee = new Employee();
            $employee->setUsername($data['name']);

            // Fonction pour nettoyer les caractères spéciaux dans le nom de l'avatar (nom Céline)
            $employee->setAvatar('avatar_' . strtolower(preg_replace('/[éèêë]/', 'e', $data['name'])) . '.PNG');

            //Encodeur pour hacher le mot de passe
            $hashedPassword = $this->passwordEncoder->encodePassword($employee, $data['password']);
            $employee->setPassword($hashedPassword);

            $manager->persist($employee);
        }

        $manager->flush();
    }
}