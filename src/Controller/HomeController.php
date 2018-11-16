<?php

namespace App\Controller;

use App\Repository\CodeRepository;
use App\Utils\RandomizerUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{
	private $codeRepository;

	public function __construct(CodeRepository $codeRepository)
	{
		$this->codeRepository = $codeRepository;
	}

	/**
	 * @Route("/", name="home")
	 */
	public function index()
	{
		return $this->render('home/index.html.twig', [
			'controller_name' => 'HomeController',
		]);
	}

	/**
	 * @Route("/generate", name="generate", methods={"POST"})
	 * @param Request $request
	 * @return null|JsonResponse
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function generate(Request $request): ?JsonResponse
	{
		$entityManager = $this->getDoctrine()->getManager();
		$export = $request->get('export');

		if ($request->get('nb'))
			$nb = $request->get('nb');
		else
			$nb = 1;

		$res = RandomizerUtil::rand($this->codeRepository, $entityManager, $nb);

		if ($export === "xls") :
			return $this->json("Hello XLS");
		endif;

		return $this->json($res);
	}
}
