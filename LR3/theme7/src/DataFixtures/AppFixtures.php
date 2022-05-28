<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    /**
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Admin
        $user = (new User())
            ->setEmail('admin@site.com')
            ->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->userPasswordHasher->hashPassword(
            $user,
            'admin_password'
        ));
        $manager->persist($user);

        for ($i = 1; $i < 10; $i++) {
            $user = (new User())
                ->setEmail('some.user' . $i . '@site.com')
                ->setRoles(['ROLE_USER']);

            $user->setPassword($this->userPasswordHasher->hashPassword(
                $user,
                'hard_password' . $i
            ));
            $manager->persist($user);
        }

        for ($i = 1; $i < 10; $i++) {
            $book = (new Book())
                ->setTitle('Самая крутая книга ' . $i)
                ->setAuthor('Какой-то человек ' . $i)
                ->setCoverUrl('files/covers/1.png')
                ->setFileUrl('files/books/1.pdf')
                ->setDateRead((new DateTime())->setTimestamp(1652868287 - rand(10**6, 10**7)));
            $manager->persist($book);
        }
        $manager->flush();
    }
}
