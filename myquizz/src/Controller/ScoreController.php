<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\Users;
use App\Entity\Reponse;

class ScoreController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/score", name="score")
     */
    public function index()
    {
        $user = $this->security->getUser();
       
        $reponses = $user->getReponses();
        $points = [];
        
        foreach($reponses as $reponse){

            $question = $reponse->getQuestion();
            $result = $reponse->getReponseExpected();
            $quizz = $question->getQuizz();
            $quizzName = $quizz->getName();

            if($result == true){
                if(!isset($points[$quizzName])){
                    $points[$quizzName] = 1 ;
                }
                else{
                    $points[$quizzName] += 1 ;
                }
            }
        }

        return $this->render('score/index.html.twig', [
            'controller_name' => 'ScoreController',
            'points' => $points,
        ]);
    }
}
