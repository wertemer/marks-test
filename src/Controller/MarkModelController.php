<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Models;
use App\Entity\Marks;
use App\Entity\Roles;

class MarkModelController extends AbstractController
{
    #[Route('/mark/model', name: 'mark_model')]
    public function index(Request $request): Response
    {
		$models=$this->getDoctrine()
			->getRepository(Models::class)
			->findAll();
		$session=$request->getSession();

		if($session->get('id')){
			$roles=$this->getDoctrine()
				->getRepository(Roles::class)
				->find($session->get('id'));
		} else {
			return $this->redirectToRoute('view');
		}
		return $this->render('mark_model/index.html.twig', [
            'title' => 'Панель управления',
			'role'	=> isset($roles) ? $roles->getName() : null,
			'login'	=> $session->get('login') ? $session->get('login') : null,
			'models'	=> $models,
        ]);
    }

	#[Route('/mark/model/addmodel', name: 'add_model')]
    public function addmodel(Request $request): Response
    {
		$em = $this->getDoctrine()->getManager();
		$session=$request->getSession();
		if($session->get('id')){
			$roles=$this->getDoctrine()
				->getRepository(Roles::class)
				->find($session->get('id'));
			if($roles->getName()!='Администратор'){
				return $this->redirectToRoute('view');
			}
		} else {
			return $this->redirectToRoute('view');
		}
		//add model
		$addmodel = $this->createForm('App\Form\ModelFormType');
		//Обрабатываем данные, которые поступили в запросе и относятся к форме
		$addmodel->handleRequest($request);
		$newmodel=$addmodel->getData();
		if(isset($newmodel['mark_name'])&&isset($newmodel['model_name'])){
			$exists=$this->getDoctrine()
				->getRepository(Models::class)
				->findOneBy([
					'fMark'=>$newmodel['mark_name'],
					'name'=>$newmodel['model_name'],
					'isLeftDrive'	=> $newmodel['typerul'],
				]);
			if(!$exists){
				$mark=$this->getDoctrine()
			 		->getRepository(Marks::class)
					->findOneBy(['id'=>$newmodel['mark_name']]);
				$items = $this->getDoctrine()
					->getRepository(Models::class)
					->findAll();
				$model_count=count($items)+1;
				$newmodels=new Models();
				$newmodels->setId($model_count);
				$newmodels->setName($newmodel['model_name']);
				$newmodels->setFMark($mark);
				$newmodels->setIsLeftDrive($newmodel['typerul']);
				$em->persist($newmodels);
				$em->flush();
				return $this->redirectToRoute('mark_model');
			}
		}
		return $this->render('mark_model/model.html.twig',[
			'title'	=> 'Добавить модель',
			'newmodel'	=> $addmodel->createView(),
			'role'	=> isset($roles) ? $roles->getName() : null,
			'login'	=> $session->get('login') ? $session->get('login') : null,
			'isLeft'	=> null,
			'mark'	=> null,
			'model'	=> null,
			'editable'	=> 0
		]);
	}

	#[Route('/mark/model/addmark', name: 'add_mark')]
    public function addmark(Request $request): Response
    {
		$em = $this->getDoctrine()->getManager();
		$session=$request->getSession();
		if($session->get('id')){
			$roles=$this->getDoctrine()
				->getRepository(Roles::class)
				->find($session->get('id'));
			if($roles->getName()!='Администратор'){
				return $this->redirectToRoute('view');
			}
		} else {
			return $this->redirectToRoute('view');
		}
		//add mark
		$addmark = $this->createForm('App\Form\MarkFormType');
		//Обрабатываем данные, которые поступили в запросе и относятся к форме
		$addmark->handleRequest($request);
		$newmark=$addmark->getData();
		if(isset($newmark['mark_name'])){
			$mark=$this->getDoctrine()
		 		->getRepository(Marks::class)
				->findOneBy(['name'=>$newmark['mark_name']]);
			if(!$mark){
				$items = $this->getDoctrine()
					->getRepository(Marks::class)
					->findAll();
	   			$marks_count=count($items)+1;
				$newmarks=new Marks();
					$newmarks->setId($marks_count);
					$newmarks->setName($newmark['mark_name']);
					$em->persist($newmarks);
					$em->flush();
				return $this->redirectToRoute('mark_model');
			} else {
				$this->addFlash(
					'notice',
					'Марка с таким именем была создана ранее!'
				);
			}
		}
		return $this->render('mark_model/mark.html.twig', [
            'title' => 'Добавление новой марки',
			'role'	=> isset($roles) ? $roles->getName() : null,
			'login'	=> $session->get('login') ? $session->get('login') : null,
			'addmark'	=> $addmark->createView(),
        ]);
	}

	#[Route('/mark/model/editmark', name: 'edit_mark')]
    public function editmodel(Request $request): Response
    {
		$em = $this->getDoctrine()->getManager();
		$session=$request->getSession();
		if($session->get('id')){
			$roles=$this->getDoctrine()
				->getRepository(Roles::class)
				->find($session->get('id'));
			if($roles->getName()=='Пользователь'){
				return $this->redirectToRoute('view');
			}
		} else {
			return $this->redirectToRoute('view');
		}
		$id=$request->query->get('model_id');
		$post=$request->request->get('model_form');//.model_name;
		//add model
		$model = $this->createForm('App\Form\ModelFormType');
		//Обрабатываем данные, которые поступили в запросе и относятся к форме
		$model->handleRequest($request);
		$editmodel=$model->getData();
		$edmodel=$this->getDoctrine()
			->getRepository(Models::class)
			->findOneBy(['id'	=> $id]);
		if(!$edmodel){
			$this->addFlash(
				'notice',
				'Модель не найдена!'
			);
			return $this->redirectToRoute('mark_model');
		} else {
			if(isset($editmodel['mark_name'])){
				$mark=$this->getDoctrine()
					->getRepository(Marks::class)
					->findOneBy(['id'=>$editmodel['mark_name']->getId()]);
				$edmodel->setFMark($mark);
				$edmodel->setName($editmodel['model_name']);
				$edmodel->setIsLeftDrive($editmodel['typerul']);
				$em->persist($edmodel);
				$em->flush();
				return $this->redirectToRoute('mark_model');
			}
		}
		return $this->render('mark_model/model.html.twig',[
			'title'	=> 'Изменить модель'.$edmodel->getName(),
			'newmodel'	=> $model->createView(),
			'role'	=> isset($roles) ? $roles->getName() : null,
			'login'	=> $session->get('login') ? $session->get('login') : null,
			'isLeft'	=> $edmodel->getIsLeftDrive(),
			'mark'	=> $edmodel->getFMark()->getId(),
			'model'	=> $edmodel->getName(),
			'editable'	=> 1
		]);
	}
}
