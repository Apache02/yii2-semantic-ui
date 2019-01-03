<?php

namespace semantic;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use semantic\Semantic;


class DropDownInput extends InputWidget
{
	public $items		= [];
	
	public $cssClass	= 'ui selection fluid dropdown';
	public $options		= [];
	public $placeholder	= true;
	
	public $itemAliases	= [
		'divider'			=> '<div class="ui divider"></div>',
		'fitted divider'	=> '<div class="ui fitted divider"></div>',
	];
	
	public $labelTemplate = '{icon}{label}';
	
	public $allowSearch	= false;
	public $encodeLabels = true;
	
	// since semantic v2.4.0
	public $clearable	= false;
	
	public $multiple = false;
	public $maxSelections = null;
	
	
	
	
	private function checkOptions ()
	{
	}
	
	public function run ()
	{
		$this->checkOptions();
		
		$options = $this->options;
		$options['id'] = $this->id;
		Html::addCssClass($options, $this->cssClass);
		if ( $this->allowSearch ) {
			Html::addCssClass($options, 'search');
		}
		if ( $this->multiple ) {
			Html::addCssClass($options, 'multiple');
		}
		if ( $this->maxSelections > 1 ) {
			Html::addCssClass($options, 'special');
		}
		
		$placeholder = $this->placeholder;
		$placeholder = ArrayHelper::remove($options, 'placeholder', $placeholder);
		if ( $placeholder === true ) {
			$placeholder = $this->model->getAttributeLabel($this->attribute);
		}
		
		echo Html::beginTag('div', $options),
			Html::activeHiddenInput($this->model, $this->attribute, ['name'=>$this->name]),
			Html::tag('div', $placeholder, ['class'=>'default text']),
			Semantic::icon('dropdown'),
			$this->renderMenu($this->items),
			'</div>';
		
		// register js
		$jsOptions = [];
		if ( $this->clearable ) {
			$jsOptions['clearable'] = true;
		}
		if ( $this->maxSelections > 1 ) {
			$jsOptions['maxSelections'] = $this->maxSelections;
		}
		$this->view->registerJs("$('#{$this->id}').dropdown(".json_encode($jsOptions).");");
	}
	
	public function renderMenu ( $items )
	{
		return '<div class="menu">'
			. $this->renderItems($items)
			. '</div>';
	}
	
	public function renderItems ( $items )
	{
		$content = [];
		foreach ( $items as $val => $item ) {
			if ( !is_array($item) ) {
				$item = [
					'options' => [],
					'label' => $item,
				];
			}
			if ( !isset($item['options']) ) {
				$item['options'] = [];
			}
			$item['options']['data-value'] = $val;
			$content[] = $this->renderItem($item);
		}
		return implode("\n", $content);
	}
	
	public function renderItem ( $item )
	{
		if ( is_string($item) && isset($this->itemAliases[$item]) ) {
			return $this->itemAliases[$item];
		}
		if ( isset($item['visible']) && !$item['visible'] ) {
			return '';
		}
		if ( isset($item['html']) ) {
			return $item['html'];
		}
		
		// resolve item content
		$content = '';
		if ( isset($item['content']) ) {
			$content = $item['content'];
		} else {
			$template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);
			
			$label = $item['label'];
			$encodeLabel = isset($item['encodeLabel']) ? $item['encodeLabel'] : $this->encodeLabels;
			if ( $encodeLabel ) {
				$label = Html::encode($item['label']);
			}
			$content = Semantic::renderTemplate($template, [
				'icon'		=> isset($item['icon'])
					? Semantic::icon($item['icon'])
					: ( isset($item['flag']) ? Semantic::flag($item['flag']) : null ),
				'label'		=> $label,
			]);
		}
		
		$options = isset($item['options'])
			? $item['options']
			: [];
		Html::addCssClass($options, 'item');
		
		return Html::tag('div', $content, $options);
	}
	
}
