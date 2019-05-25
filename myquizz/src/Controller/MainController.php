<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Entity\Quizz;
use App\Entity\Question;
use App\Entity\Reponse;


class MainController extends AbstractController
{
    private $session;
    private $security;

    public function __construct(SessionInterface $session, Security $security)
    {
        $this->session = $session;
        $this->security = $security;
    }

    /**
     * @Route("/main/{id}", name="main")
     */
    public function index($id, Request $request)
    {
        $user = $this->security->getUser();
        $quizz = $this->getDoctrine()
        ->getRepository(Quizz::class)
        ->find($id);
        
        if($this->session->has('quizz')){
            if(($quizz->id != $this->session->get('quizz')->id)){
                $this->session->set('count', 0);
                $this->session->set('invitedscore', 0);
            }
        }
        else{
            $this->session->set('count', 0);
        }
        $this->session->set('quizz', $quizz);

        if (!$quizz) {
            throw $this->createNotFoundException(
                'No quizz found for id '.$id
            );
        }

        $questions = $quizz->getQuestions();

        $totalQuestions = count($questions);

        $currentCount = $this->session->get('count') ?? 0;

        if($currentCount >= $totalQuestions){
            if($user != null){
                return $this->getScore($user, $quizz);
            }
            else{
                $finalscore = $this->session->get('invitedscore');
                return $this->render('main/score.html.twig', [
                    'controller_name' => 'MainController',
                    'points' => $finalscore,
                    'quizz' => $quizz,
                ]);
            }
        }
        else{
            $this->session->set('question', $questions[$currentCount]);
    
            $currentQuestion = $this->session->get('question');
    
            $reponses = $currentQuestion->getReponses();
    
            $defaultData = ['message' => 'Type your message here'];
    
            $form = $this->createFormBuilder($defaultData);
                $form->add('reponse', ChoiceType::class, [
                    'choices' => $reponses,
                    'choice_label' => 'reponse',
                    'choice_value' => 'id',
                    'expanded' => true,
                    'multiple' => false,
                    'attr' => ['class' => 'm-2 d-flex flex-column']
                ]);
                $form->add('save', SubmitType::class, ['label' => 'Next', 'attr' => ['class' => 'btn btn-secondary']]);
                $form = $form->getForm();
    
                $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $defaultData = $form->getData();
                
                if($user == null){
                    $invitedscore = $this->session->get('invitedscore') ?? 0;
                    $reponse = $form->getData()['reponse'];
                    $result = $reponse->getReponseExpected();
                    if($result == true){
                        $invitedscore++;
                        $this->session->set('invitedscore', $invitedscore);
                    }
                }
                else {
                    $user->addReponse($defaultData['reponse']);
                    $this->getDoctrine()->getManager()->flush();
                }
                $currentCount++;

                $this->session->set('count', $currentCount);

                return $this->redirectToRoute('main', ['id' => $id]);
            }
    
            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
                'quizz' => $quizz,
                'question' => $currentQuestion,
                'reponses' => $reponses,
                'form' => $form->createView(),
            ]);
        }


    }

    public function getScore($user, $quizz) {

        $reponses = $user->getReponses();
        $points = 0;

        foreach($reponses as $reponse){
            $result = $reponse->getReponseExpected();
            $question = $reponse->getQuestion();
            $checkQuizz = $question->getQuizz();

            if($result == true && $checkQuizz == $quizz){
                $points++;
            }
        }
        
        return $this->render('main/score.html.twig', [
            'controller_name' => 'MainController',
            'points' => $points,
            'quizz' => $quizz,
        ]);
    }
}
