<?php

namespace semantic\grid;

use yii\helpers\Html;
use yii\base\Exception;
use semantic\Semantic;

class EnumColumn extends DataColumn
{
	public $labels			= null;
	public $labelUndefined	= 'undefined';
	
	
	/**
	 * {@inheritdoc}
	 */
	public function init ()
	{
		parent::init();
		if ( !$this->labels || !is_array($this->labels) ) {
			throw new Exception('Attribute "labels" must be an array.');
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
    protected function renderDataCellContent ( $model, $key, $index )
    {
		$value = (int) $this->getDataCellValue($model, $key, $index);
		return isset($this->labels[$value])
			? $this->labels[$value]
			: $this->labelUndefined;
    }
}
