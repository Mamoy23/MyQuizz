<?php 

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Reponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

/**
* @var UserRepository
*/
class UserController extends AbstractController {

    private $session;
    private $security;

    public function __construct(SessionInterface $session, Security $security)
    {
        $this->session = $session;
        $this->security = $security;
    }

    /**
     * @Route("/home", name="home")
     */
    public function test()
    {
        //$this->session->set('user', app.user.id);
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Users $user, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        $id = $this->security->getUser()->getId();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $user->setPassword(
            //     $passwordEncoder->encodePassword(
            //         $user,
            //         $form->get('password')->getData()
            //     )
            // );
            $this->getDoctrine()->getManager()->flush();
            
            $contactData = $form->getData();

            $message = (new \Swift_Message('Hello Email'))
            ->setFrom('marine.moynet@gmail.com')
            ->setTo($contactData->getEmail())
            ->setBody(
                    "Merci de cliquer sur le lien suivant afin de valider votre compte http://localhost:8000/validate/". $contactData->getId()
                );
    
            $mailer->send($message);

            $entityManager = $this->getDoctrine()->getManager();
            $valide = $entityManager->getRepository(Users::class)
                ->find($id);
    
            if (!$valide) {
                throw $this->createNotFoundException(
                'There are no user with the following id: ' . $id
                );
            }
            $valide->setValidated(false);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('users/useredit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/validate/{id}", name="validate")
     */

    public function email($id){
        $entityManager = $this->getDoctrine()->getManager();
        $valide = $entityManager->getRepository(Users::class)
            ->find($id);

        if (!$valide) {
            throw $this->createNotFoundException(
            'There are no user with the following id: ' . $id
            );
        }
        $valide->setValidated(true);
        $entityManager->flush();
        return $this->render('/validate.html.twig', compact('valide'));
    }

}
