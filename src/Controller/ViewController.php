<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Models;
use App\Entity\Roles;

class ViewController extends AbstractController
{
    #[Route('/', name: 'view')]
    public function index(Request $request): Response
    {
		$session=$request->getSession();
		if($session->get('id')){
			$roles=$this->getDoctrine()
				->getRepository(Roles::class)
				->find($session->get('id'));
		}
		$models=$this->getDoctrine()
			->getRepository(Models::class)
			->findAll();
        return $this->render('view/index.html.twig', [
            'controller_name' => 'Каталог автомобилей',
			'role'	=> isset($roles) ? $roles->getName() : '',
			'login'	=> $session->get('login') ? $session->get('login') : null,
			'models'	=> $models,
        ]);
    }
}
