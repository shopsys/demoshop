<?php

namespace SS6\ShopBundle\Model\Administrator;

use Doctrine\ORM\EntityManager;
use SS6\ShopBundle\Model\Administrator\Administrator;

class AdministratorRepository {

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->em = $em;
	}

	/**
	 * @return \Doctrine\ORM\EntityRepository
	 */
	private function getAdministratorRepository() {
		return $this->em->getRepository(Administrator::class);
	}

	/**
	 * @param int $administratorId
	 * @return \SS6\ShopBundle\Model\Administrator\Administrator|null
	 */
	public function findById($administratorId) {
		return $this->getAdministratorRepository()->find($administratorId);
	}

	/**
	 * @param int $administratorId
	 * @return \SS6\ShopBundle\Model\Administrator\Administrator
	 * @throws \SS6\ShopBundle\Model\Administrator\Exception\AdministratorNotFoundException
	 */
	public function getById($administratorId) {
		$criteria = array('id' => $administratorId);
		$administrator = $this->getAdministratorRepository()->findOneById($administratorId);
		if ($administrator === null) {
			throw new \SS6\ShopBundle\Model\Administrator\Exception\AdministratorNotFoundException($criteria);
		}

		return $administrator;
	}

	/**
	 * @param type $administratorUserName
	 * @return \SS6\ShopBundle\Model\Administrator\Administrator
	 */
	public function findByUserName($administratorUserName) {
		return $this->getAdministratorRepository()->findOneBy(array('username' => $administratorUserName));
	}

	/**
	 * @return \SS6\ShopBundle\Model\Administrator\Administrator[]
	 */
	public function getAll() {
		return $this->getAdministratorRepository()->findAll();
	}
}
