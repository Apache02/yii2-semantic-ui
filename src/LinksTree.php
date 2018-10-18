<?php

namespace semantic;

use yii\helpers\Html;
use semantic\Semantic;
use yii\base\Exception;



class LinksTree extends Widget
{
	public $itemTag = 'li';
	public $itemOptions = ['class'=>'item'];
	public $listTag = 'ul';
	public $listOptions = ['class'=>'ui links list'];
	public $linkOptions = [];
	public $items = null;
	public $encodeLabels = true;
	
	
	private function checkOptions ()
	{
		if ( !$this->items ) {
			throw new Exception('Attribute "items" is not defined.');
		}
		if ( !is_array($this->items) ) {
			throw new Exception('Attribute "items" must be an array.');
		}
	}
	
	public function run ()
	{
		$this->checkOptions();
		
		$options = $this->listOptions;
		$options['id'] = $this->id;
		echo $this->renderList($this->items, $options);
	}
	
	public function renderList ( $items, $options = null )
	{
		if ( !$options ) {
			$options = $this->listOptions;
		}
		$tagName = $this->listTag;
		
		return Html::beginTag($tagName, $options)
			. $this->renderItems($items)
			. Html::endTag($tagName);
	}
	
	public function renderItems ( $items )
	{
		return implode(array_map([$this, 'renderItem'], $items));
	}
	
	public function renderItem ( $item )
	{
		$options = $this->itemOptions;
		$label = $item['label'];
		if (
			$this->encodeLabels
			&& ( !isset($item['encodeLabel']) || $item['encodeLabel'] )
		) {
			$label = Html::encode($label);
		}
		if ( empty($item['url']) ) {
			$label = '<span>' . $label . '</span>';
		} else {
			$label = Html::a($label, $item['url'], $this->linkOptions);
		}
		if ( !empty($item['items']) ) {
			$label .= $this->renderList($item['items']);
		}
		$tagName = $this->itemTag;
		return Html::tag($tagName, $label, $options);
	}
	
	
}
