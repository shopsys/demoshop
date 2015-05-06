<?php

namespace SS6\ShopBundle\Model\AdvancedSearchOrder\Filter;

use Doctrine\ORM\QueryBuilder;
use SS6\ShopBundle\Form\FormType;
use SS6\ShopBundle\Model\AdvancedSearchOrder\AdvancedSearchOrderFilterInterface;

class OrderPriceFilterWithVatFilter implements AdvancedSearchOrderFilterInterface {

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'orderTotalPriceWithVat';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAllowedOperators() {
		return [
			self::OPERATOR_GT,
			self::OPERATOR_LT,
			self::OPERATOR_GTE,
			self::OPERATOR_LTE,
			self::OPERATOR_EQ,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getValueFormType() {
		return FormType::NUMBER;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getValueFormOptions() {
		return [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function extendQueryBuilder(QueryBuilder $queryBuilder, $rulesData) {
		foreach ($rulesData as $index => $ruleData) {
			$dqlOperator = $this->getContainsDqlOperator($ruleData->operator);
			if ($dqlOperator === null || $ruleData->value == '' || $ruleData->value === null) {
				continue;
			}
			$searchValue = $ruleData->value;
			$parameterName = 'totalPriceWithVat_' . $index;
			$queryBuilder->andWhere('o.totalPriceWithVat ' . $dqlOperator . ' :' . $parameterName);
			$queryBuilder->setParameter($parameterName, $searchValue);
		}
	}

	/**
	 * @param string $operator
	 * @return string
	 */
	private function getContainsDqlOperator($operator) {
		switch ($operator) {
			case self::OPERATOR_GT:
				return '>';
			case self::OPERATOR_LT:
				return '<';
			case self::OPERATOR_GTE:
				return '>=';
			case self::OPERATOR_LTE:
				return '<=';
			case self::OPERATOR_EQ:
				return '=';
		}
		return null;
	}

}