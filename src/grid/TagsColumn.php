<?php

namespace semantic\grid;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use semantic\Semantic;


class TagsColumn extends DataColumn
{
	use \semantic\traits\ResolveUrlTrait;
	
 	public $tagName			= 'div';
	public $tagOptions		= ['class' => 'ui label'];
	public $separator		= '';
	public $encodeLabels	= true;
	
	public $url				= false;
	public $urlAttr			= false;
	
	
	private function checkOptions ()
	{
	}
	
	public function init ()
	{
		$this->checkOptions();
		parent::init();
	}
	
	protected function renderTag ( $model )
	{
		$options = $this->tagOptions;
		if ( $color = ArrayHelper::getValue($model, 'color', '') ) {
			Html::addCssClass($options, $color);
		}
		$tagName = $this->tagName;
		$label = $model->name;
		if ( $this->encodeLabels ) {
			$label = Html::encode($label);
		}
		if ( $this->url ) {
			$url = $this->resolveUrl($this->url, $this->urlAttr, $model);
			return Html::a($label, $url, $options);
		} else {
			return Html::tag($tagName, $label, $options);
		}
	}
	
	
	/**
	 * {@inheritdoc}
	 */
    protected function renderDataCellContent ( $model, $key, $index )
    {
		$tags = ArrayHelper::getValue($model, $this->attribute);
		if ( !$tags ) {
			return '';
		}
		return implode($this->separator, array_map([$this, 'renderTag'], $tags));
    }
}
