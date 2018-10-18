<?php

namespace semantic\grid;

use yii\helpers\Html;
use semantic\Semantic;


class DataLinkCountColumn extends DataColumn
{
	use \semantic\traits\ResolveUrlTrait;
	
	public $url						= false;
	public $urlAttr					= false;
	public $urlOptions				= [];
	
	
	/**
	 * {@inheritdoc}
	 */
    protected function renderDataCellContent( $model, $key, $index )
    {
		$value = parent::renderDataCellContent($model, $key, $index);
		if ( $value ) {
			$options = $this->urlOptions;
			if ( is_callable($options) ) {
				$options = call_user_func($options, $model, $key, $index);
			}
			return Html::a($value, $this->resolveUrl($this->url, $this->urlAttr, $model), $options);
		}
		return '';
    }
}
