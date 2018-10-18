<?php

namespace semantic\grid;

use yii\helpers\Html;
use semantic\Semantic;

class BooleanColumn extends DataColumn
{
	public $labels		= [
		0		=> '<i class="icon red x"></i>',
		1		=> '<i class="icon green check"></i>',
	];
	
	
	
	/**
	 * {@inheritdoc}
	 */
    protected function renderDataCellContent ( $model, $key, $index )
    {
		$value = $this->getDataCellValue($model, $key, $index) ? 1 : 0;
		return isset($this->labels[$value])
			? $this->labels[$value]
			: '';
    }
}
