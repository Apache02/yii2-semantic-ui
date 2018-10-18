<?php

namespace semantic\grid;

use yii\helpers\Html;
use semantic\Semantic;


class DataLinkColumn extends DataColumn
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
		$label = parent::renderDataCellContent($model, $key, $index);
		
		if ( $this->url ) {
			$options = $this->urlOptions;
			if ( is_callable($options) ) {
				$options = call_user_func($options, $model, $key, $index);
			}
			$label = Html::a($label, $this->resolveUrl($this->url, $this->urlAttr, $model), $options);
		}
		
		return $label;
    }
}
