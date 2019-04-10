<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\User;
use App\Form\CompanyType;
use App\Form\UserType;
use App\Service\SlackManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminUserController extends AbstractController
{
    private $em;
    private $slackManager;

    public function __construct(EntityManagerInterface $em, SlackManager $slackManager)
    {
        $this->em = $em;
        $this->slackManager = $slackManager;
    }

    /**
     * @Route("/admin/user/", name="user.list")
     */
    public function index()
    {
        $users = $this->em->getRepository(User::class)->findAll();

        /** @var User $user */
        foreach ($users as $key => $user) {
            if (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
                unset($users[$key]);
            }
        }

        return $this->render('Admin/index.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/admin/user/edit/{id}", name="user.edit")
     */
    public function edit(Request $request, User $user, $_route)
    {
        $form = $this->createForm(UserType::class, $user, array('route' => $_route));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $form->getConfig()->getData()->setPlainPassword(''); // Evite l'erreur plainPassword blank

            $this->em->persist($user);
            $this->em->flush();

            $this->slackManager->sendMessage($this->getUser()->getUsername().' vient de modifier le compte de '.$user->getId().' (id) '.$user->getUsername().' (username).');

            return $this->redirectToRoute('user.list');
        }

        return $this->render('Admin/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="user.delete")
     */
    public function delete($id = null)
    {
        if ($id) {
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);

            $this->em->remove($user);
            $this->em->flush();
        }

        return $this->redirectToRoute('user.list');
    }

    /**
     * @Route("/admin/company", name="company.list")
     */
    public function indexCompany()
    {
        $companies = $this->getDoctrine()->getRepository(Company::class)->findAll();

        return $this->render('Company/Admin/index.html.twig', array(
            'companies' => $companies,
        ));
    }

    /**
     * @Route("/admin/company/add/", name="company.add")
     */
    public function addCompany(Request $request)
    {
        $company = new Company();

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($company);
            $this->em->flush();

            return $this->redirectToRoute('company.list');
        }

        return $this->render('Company/Admin/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/company/edit/{id}", name="company.edit")
     */
    public function editCompany(Request $request, Company $company)
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($company);
            $this->em->flush();

            return $this->redirectToRoute('company.list');
        }

        return $this->render('Company/Admin/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/company/delete/{id}", name="company.delete")
     */
    public function companyDelete($id = null)
    {
        if ($id) {
            $company = $this->getDoctrine()->getRepository(Company::class)->find($id);

            $this->em->remove($company);
            $this->em->flush();
        }

        return $this->redirectToRoute('company.list');
    }
}