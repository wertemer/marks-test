<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Users;
use App\Entity\Roles;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(Request $request): Response
    {
		$msg='';
		//Создаем форму из конструктива класса LoginForm
		$form = $this->createForm('App\Form\LoginForm');
		//Обрабатываем данные, которые поступили в запросе и относятся к форме
		$form->handleRequest($request);
		//Получаем данные введенные в форму.
		$data=$form->getData();
		if(isset($data['login'])){
			$user = $this->getDoctrine()
				->getRepository(Users::class)
				->findOneBy(['login' => $data['login']]);
			if(!$user){
				$this->addFlash(
					'notice',
					'Не верный логин!'
				);
			}else{
				$pas=$user->getPaswd();
				if($data['password']!=$pas){
					$this->addFlash(
						'notice',
						'Не верный пароль!'
					);
				} else {
					// move to view models
					$session = new Session();
					//$session->start();
					$session->set('id', $user->getFRol());
					$session->set('login', $user->getLogin());
					return $this->redirectToRoute("mark_model");
				}
			}
		}
		return $this->render('login/index.html.twig', [
			'form'	=> $form->createView(),
			'title'	=> 'Вход'
        ]);
    }
	#[Route('/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
		$session=$request->getSession();
		$session->clear();
		return $this->redirectToRoute('view');
	}
	#[Route('/login/adduser', name: 'adduser')]
	public function adduser(Request $request): Response
	{
		$em = $this->getDoctrine()->getManager();
		$form = $this->createForm('App\Form\RegisterFormType');
		$form->handleRequest($request);
		$data=$form->getData();
		if(isset($data)){
			$login=$this->getDoctrine()
				->getRepository(Users::class)
				->findOneBy(['login'=>$data['login']]);
			if($login){
				$this->addFlash(
					'notice',
					'Логин уже существует!'
				);
				return $this->redirectToRoute('adduser');
			}
			if($data['password']!=$data['pass_text']){
				$this->addFlash(
					'notice',
					'Пароли не совпадают!'
				);
				return $this->redirectToRoute('adduser');
			}
			if(strlen($data['password'])<6){
				$this->addFlash(
					'notice',
					'Длина пароля должна состовлять не менее 6 символов!'
				);
				return $this->redirectToRoute('adduser');
			}
			$role=$this->getDoctrine()
				->getRepository(Roles::class)
				->findOneBy(['id'=>$data['user_type']]);
			$items = $this->getDoctrine()
				->getRepository(Users::class)
				->findAll();
			$users_count=count($items)+1;
			$user=new Users();
			$user->setId($users_count);
			$user->setLogin($data['login']);
			$user->setName($data['username']);
			$user->setPaswd($data['password']);
			$user->setFRol($role);
			$em->persist($user);
			$em->flush();
			$this->addFlash(
				'notice',
				'Пользователь создан.'
			);
		}
		return $this->render('login/adduser.html.twig', [
			'form'	=> $form->createView(),
			'title'	=> 'Регистрация',
		]);
	}
}
