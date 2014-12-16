<?php

namespace SS6\ShopBundle\Form\Admin\Product;

use SS6\ShopBundle\Form\Admin\Product\Parameter\ProductParameterValueFormTypeFactory;
use SS6\ShopBundle\Form\Admin\Product\ProductFormTypeFactory;
use SS6\ShopBundle\Model\FileUpload\FileUpload;
use SS6\ShopBundle\Model\Image\ImageFacade;
use SS6\ShopBundle\Model\Product\Product;

class ProductEditFormTypeFactory {

	/**
	 * @var \SS6\ShopBundle\Model\FileUpload\FileUpload
	 */
	private $fileUpload;

	/**
	 * @var \SS6\ShopBundle\Form\Admin\Product\Parameter\ProductParameterValueFormTypeFactory
	 */
	private $productParameterValueFormTypeFactory;

	/**
	 * @var \SS6\ShopBundle\Model\Image\ImageFacade
	 */
	private $imageFacade;

	/**
	 * @var \SS6\ShopBundle\Form\Admin\Product\ProductFormTypeFactory
	 */
	private $productFormTypeFactory;

	public function __construct(
		FileUpload $fileUpload,
		ProductParameterValueFormTypeFactory $productParameterValueFormTypeFactory,
		ImageFacade $imageFacade,
		ProductFormTypeFactory $productFormTypeFactory
	) {
		$this->fileUpload = $fileUpload;
		$this->productParameterValueFormTypeFactory = $productParameterValueFormTypeFactory;
		$this->imageFacade = $imageFacade;
		$this->productFormTypeFactory = $productFormTypeFactory;
	}

	/**
	 * @param \SS6\ShopBundle\Model\Product\Product|null $product
	 * @return \SS6\ShopBundle\Form\Admin\Product\ProductFormType
	 */
	public function create(Product $product = null) {
		if ($product !== null) {
			$images = $this->imageFacade->getImagesByEntity($product, null);
		} else {
			$images = array();
		}

		return new ProductEditFormType(
			$images,
			$this->productParameterValueFormTypeFactory,
			$this->fileUpload,
			$this->productFormTypeFactory
		);
	}

}
