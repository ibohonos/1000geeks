<?php

namespace App\Controller;

use App\Repository\CodeRepository;
use App\Utils\RandomizerUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

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
	 * @return BinaryFileResponse|JsonResponse
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
	 */
	public function generate(Request $request)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$export = $request->get('export');

		if ($request->get('nb'))
			$nb = $request->get('nb');
		else
			$nb = 1;

		$res = RandomizerUtil::rand($this->codeRepository, $entityManager, $nb);

		if ($export === "xls") :
			$spreadsheet = new Spreadsheet();

			$sheet = $spreadsheet->getActiveSheet();

			foreach ($res as $key => $val) :
				$sheet->setCellValue('A' . ++$key, $val);
			endforeach;

			$writer = new Xls($spreadsheet);

			$fileName = "code" . time() . ".xls";
			$temp_file = tempnam(sys_get_temp_dir(), $fileName);

			$writer->save($temp_file);

			return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
		endif;

		return $this->json($res);
	}

	/**
	 * @Route("/{code}", name="code")
	 * @param string $code
	 * @return JsonResponse
	 */
	public function show(string $code): JsonResponse
	{
		try {
			$codeRep = $this->codeRepository->findOneBy(['code' => $code]);
		} catch (\Exception $e) {
			return $this->json($e->getMessage(), 500);
		}

		if (!$codeRep) :
			return $this->json(["Code not found"], 404);
		endif;

		return $this->json($codeRep);
	}
}
