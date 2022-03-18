<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USERS = [
        self::SUPERADMIN,
        self::ADMIN,
        self::ADMINUSER,
        self::EDITUSER,
        self::CREATEUSER,
        self::ADMINHOUSING,
        self::ADMINFURNITURE,
        self::ADMINSERVICE,
        self::ADMINSUPPORTER,
        self::USER,
        self::SUPPORTER,
        self::GUEST];

    public const SUPERADMIN = 'superAdmin';
    public const ADMIN = 'admin';
    public const ADMINUSER = 'adminUser';
    public const EDITUSER = 'editUser';
    public const CREATEUSER = 'createUser';
    public const ADMINHOUSING = 'adminHousing';
    public const ADMINFURNITURE = 'adminFurniture';
    public const ADMINSERVICE = 'adminService';
    public const ADMINSUPPORTER = 'adminSupporter';
    public const USER = 'user';
    public const SUPPORTER = 'supporter';
    public const GUEST = 'guest';

    /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // create user of each role for testing purpose
        $superAdmin = new User();
        $superAdmin->setName('Super Admin');
        $superAdmin->setEmail('super-admin@dev.de');
        $superAdmin->setPassword($this->userPasswordHasher->hashPassword($superAdmin, 'super-admin'));
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($superAdmin);
        $this->addReference(self::SUPERADMIN, $superAdmin);

        $admin = new User();
        $admin->setName('Admin');
        $admin->setEmail('admin@dev.de');
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, 'admin'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $this->addReference(self::ADMIN, $admin);

        $adminUser = new User();
        $adminUser->setName('Admin User');
        $adminUser->setEmail('admin-user@dev.de');
        $adminUser->setPassword($this->userPasswordHasher->hashPassword($adminUser, 'admin-user'));
        $adminUser->setRoles(['ROLE_ADMIN_USER']);
        $manager->persist($adminUser);
        $this->addReference(self::ADMINUSER, $adminUser);

        $editUser = new User();
        $editUser->setName('Edit User');
        $editUser->setEmail('edit-user@dev.de');
        $editUser->setPassword($this->userPasswordHasher->hashPassword($editUser, 'edit-user'));
        $editUser->setRoles(['ROLE_EDIT_USER']);
        $manager->persist($editUser);
        $this->addReference(self::EDITUSER, $editUser);

        $createUser = new User();
        $createUser->setName('Create User');
        $createUser->setEmail('create-user@dev.de');
        $createUser->setPassword($this->userPasswordHasher->hashPassword($createUser, 'create-user'));
        $createUser->setRoles(['ROLE_CREATE_USER']);
        $manager->persist($createUser);
        $this->addReference(self::CREATEUSER, $createUser);

        $adminHousing = new User();
        $adminHousing->setName('Admin Housing');
        $adminHousing->setEmail('admin-housing@dev.de');
        $adminHousing->setPassword($this->userPasswordHasher->hashPassword($adminHousing, 'admin-housing'));
        $adminHousing->setRoles(['ROLE_ADMIN_HOUSING']);
        $manager->persist($adminHousing);
        $this->addReference(self::ADMINHOUSING, $adminHousing);

        $adminFurniture = new User();
        $adminFurniture->setName('Admin Furniture');
        $adminFurniture->setEmail('admin-furniture@dev.de');
        $adminFurniture->setPassword($this->userPasswordHasher->hashPassword($adminFurniture, 'admin-furniture'));
        $adminFurniture->setRoles(['ROLE_ADMIN_FURNITURE']);
        $manager->persist($adminFurniture);
        $this->addReference(self::ADMINFURNITURE, $adminFurniture);

        $adminService = new User();
        $adminService->setName('Admin Service');
        $adminService->setEmail('admin-service@dev.de');
        $adminService->setPassword($this->userPasswordHasher->hashPassword($adminService, 'admin-service'));
        $adminService->setRoles(['ROLE_ADMIN_SERVICE']);
        $manager->persist($adminService);
        $this->addReference(self::ADMINSERVICE, $adminService);

        $adminSupporter = new User();
        $adminSupporter->setName('Admin Supporter');
        $adminSupporter->setEmail('admin-supporter@dev.de');
        $adminSupporter->setPassword($this->userPasswordHasher->hashPassword($adminSupporter, 'admin-supporter'));
        $adminSupporter->setRoles(['ROLE_ADMIN_SUPPORTER']);
        $manager->persist($adminSupporter);
        $this->addReference(self::ADMINSUPPORTER, $adminSupporter);

        $user = new User();
        $user->setName('User');
        $user->setEmail('user@dev.de');
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'user'));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $this->addReference(self::USER, $user);

        $supporter = new User();
        $supporter->setName('Supporter');
        $supporter->setEmail('supporter@dev.de');
        $supporter->setPassword($this->userPasswordHasher->hashPassword($supporter, 'supporter'));
        $supporter->setRoles(['ROLE_SUPPORTER']);
        $manager->persist($supporter);
        $this->addReference(self::SUPPORTER, $supporter);

        $guest = new User();
        $guest->setName('Guest');
        $guest->setEmail('guest@dev.de');
        $guest->setPassword($this->userPasswordHasher->hashPassword($guest, 'guest'));
        $guest->setRoles(['ROLE_GUEST']);
        $manager->persist($guest);
        $this->addReference(self::GUEST, $guest);

        $manager->flush();
    }
}
