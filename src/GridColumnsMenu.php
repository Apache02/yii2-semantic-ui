<?php

namespace semantic;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use semantic\Widget;
use semantic\Semantic;



class GridColumnsMenu extends Widget
{
	public $itemAliases = [
		'divider'			=> '<div class="ui divider"></div>',
	];
	
	/**
	 * @var array
	 */
	public $options = ['class'=>'ui grid'];
	public $tagName = 'div';
	
	public $type = [];
	
	public $items = [];
	public $itemActiveClass = 'active';
	public $labelTemplate = '{icon} {label}';
	public $inverted = false;
	
	
	
	private function checkOptions ()
	{
		if ( in_array('inverted', $this->type) ) {
			ArrayHelper::removeValue($this->type, 'inverted');
			$this->inverted = true;
		}
	}
	
	public function isItemActive ()
	{
	}
	
	public function run ()
	{
		$this->checkOptions();
		
		$options = $this->options;
		$options['id'] = $this->id;
		
		$addClass = $this->type
			? implode(' ', $this->type)
			: '';
		if ( $this->inverted ) {
			$addClass .= ' inverted';
		}
		if ( $addClass ) {
			Html::addCssClass($options, $addClass);
		}

        echo Html::tag('div', $this->renderColumns($this->items), $options);
	}
	
	public function renderColumns ( $items )
	{
		return implode('', array_map([$this, 'renderColumn'], $items));
	}
	
	public function renderColumn ( $column )
	{
		$options = [];
		if ( isset($column['wide']) ) {
			Html::addCssClass($options, Semantic::wide($column['wide']) . ' wide');
		}
		Html::addCssClass($options, 'column');
		// header
		$content = $this->renderColumnHeader($column);
		$content .= isset($column['content'])
			? $column['content']
			: $this->renderColumnList($column['items']);
		return Html::tag('div', $content, $options);
	}
	
	public function renderColumnHeader ( $column )
	{
		$label = isset($column['header'])
			? Html::encode($column['header'])
			: '&nbsp;';
		$options = ['class'=>'ui header'];
		if ( $this->inverted ) {
			Html::addCssClass($options, 'inverted');
		}
		return Html::tag('h4', $label, $options);
	}
	
	public function renderColumnList ( $items )
	{
		$options = ['class'=>'ui link list'];
		if ( $this->inverted ) {
			Html::addCssClass($options, 'inverted');
		}
		return Html::tag('div', $this->renderColumnItems($items), $options);
	}
	
	public function renderColumnItems ( $items )
	{
		return implode('', array_map([$this, 'renderItem'], $items));
	}
	
	public function renderItem ( $item )
	{
		if ( is_string($item) && isset($this->itemAliases[$item]) ) {
			return $this->itemAliases[$item];
		}
		if ( isset($item['html']) ) {
			return $item['html'];
		}
		
		$content = '';
		if ( is_string($item) ) {
			$content = $item;
			$item = [
				'tag'	=> 'div',
			];
		} else if ( isset($item['content']) ) {
			$content = $item['content'];
		} else {
			// normal item
			$template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);
			
			$templateOptions = [
				'icon'		=> isset($item['icon'])
					? Semantic::icon($item['icon'])
					: '',
				'label'		=> (isset($item['encodeLabel']) && !$item['encodeLabel'])
					? $item['label']
					: Html::encode($item['label']),
			];
			
			$content = preg_replace_callback('/\{(\w+)\}/', function ( $match ) use ( $templateOptions ) {
				$attr = $match[1];
				return isset($templateOptions[$attr])
					? $templateOptions[$attr]
					: $match[0];
			}, $template);
		}
		
		$options = isset($item['options'])
			? $item['options']
			: [];
		
		Html::addCssClass($options, 'item');
		
		$tagName = isset($item['tag'])
			? $item['tag']
			: 'a';
		
		if ( $tagName == 'a' ) {
			$url = isset($item['url'])
				? $item['url'] : '';
			return Html::a($content, $url, $options);
		}
		
		return Html::tag($tagName, $content, $options);
	}

}
