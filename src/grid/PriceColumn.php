<?php

namespace semantic\grid;

use yii\helpers\Html;
use semantic\Semantic;

class PriceColumn extends DataColumn
{
	public $decimals	= 2;
	
	public $icon		= null;
	public $template	= '{icon}{label}';
	
	
	
	/**
	 * {@inheritdoc}
	 */
    protected function renderDataCellContent ( $model, $key, $index )
    {
		$value = $this->getDataCellValue($model, $key, $index);
		$isNull = !$value;
		$value = $this->grid->formatter->asDecimal($value, $this->decimals);
		if ( $isNull ) {
			return $value;
		}
		
		return strtr($this->template, [
			'{icon}'	=> $this->icon ? Semantic::icon($this->icon) : '',
			'{label}'	=> $value,
		]);
    }
}
