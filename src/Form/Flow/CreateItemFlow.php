<?php
namespace App\Form\Flow;

use App\Form\ItemFormType;
use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;

class CreateItemFlow extends FormFlow {

	protected function loadStepsConfig() {
		return [
			[
				'label' => 'wheels',
				'form_type' => ItemFormType::class,
			],
			[
				'label' => 'engine',
				'form_type' => ItemFormType::class,
				// 'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
				// 	return $estimatedCurrentStepNumber > 1 && !$flow->getFormData()->canHaveEngine();
				// },
			],
			[
				'label' => 'confirmation',
			],
		];
	}

}