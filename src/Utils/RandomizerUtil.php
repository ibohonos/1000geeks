<?php

namespace App\Utils;

use App\Entity\Code;
use App\Repository\CodeRepository;
use Doctrine\ORM\EntityManager;

class RandomizerUtil
{
	private static $letters = ['A', 'B', 'C', 'D', 'E', 'F'];
	private static $numbers = [2, 3, 4, 6, 7, 8, 9];
	private static $nbLetters = 6;
	private static $nbNumbers = 4;

	/**
	 * @param CodeRepository $codeRepository
	 * @param EntityManager $entityManager
	 * @param int $nb
	 * @return array
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public static function rand(CodeRepository $codeRepository, EntityManager $entityManager, int $nb): array
	{
		$res = [];
		$i = 0;

		while (++$i <= $nb) :
			$code = "";

			shuffle(self::$letters);
			for ($j = 0; $j < self::$nbLetters; $j++)
				$code .= self::$letters[array_rand(self::$letters)];

			shuffle(self::$numbers);
			for ($j = 0; $j < self::$nbNumbers; $j++)
				$code .= self::$numbers[array_rand(self::$numbers)];
			$code = str_shuffle($code);
			$codeDb = $codeRepository->findBy(['code' => $code]);

			if (empty($codeDb)) :
				$codeModel = new Code;
				$codeModel->setCode($code);
				$entityManager->persist($codeModel);
				$entityManager->flush();
				$res[] = $code;
			else:
				$i--;
			endif;
		endwhile;

		return $res;
	}
}