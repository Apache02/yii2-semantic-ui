<?php

namespace semantic;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use semantic\Semantic;



class ActiveField extends \yii\widgets\ActiveField
{
	private static $_cbCounter = 0;
	
	
	public $options			= ['class'=>'field'];
	public $inputOptions	= [];
	
	public $labelOptions	= [];
	
	public $errorOptions	= ['class' => 'ui pointing error label'];
	public $hintOptions		= ['class' => 'ui pointing label'];
	
	public $template		= "{label}\n{beginWrapper}\n{icon}\n{input}\n{endWrapper}\n{error}\n{hint}\n";
	public $checkboxTemplate = '<div class="ui checkbox">{input}{label}{hint}{error}</div>';
	
	public $wrapperOptions	= ['class' => 'ui input'];
	
	public $enableLabel		= false;
	public $icon			= false;
	public $iconAlign		= 'left';
	
	
	public function render ( $content = null )
	{
		if ( !isset($this->parts['{beginWrapper}']) ) {
			$options = $this->wrapperOptions;
			if ( $this->icon ) {
				Html::addCssClass($options, implode(' ', [$this->iconAlign, 'icon']));
			}
			$tag = ArrayHelper::remove($options, 'tag', 'div');
			$this->parts['{beginWrapper}'] = Html::beginTag($tag, $options);
			$this->parts['{endWrapper}'] = Html::endTag($tag);
		}
		if ( !isset($this->parts['{icon}']) ) {
			$this->parts['{icon}'] = $this->icon
				? Semantic::icon($this->icon)
				: '';
		}
		if ( $this->enableLabel === false ) {
			$this->parts['{label}'] = '';
			$this->parts['{beginLabel}'] = '';
			$this->parts['{labelTitle}'] = '';
			$this->parts['{endLabel}'] = '';
		}
		return parent::render($content);
	}
	
	
	public function checkbox ( $options = [], $enclosedByLabel = true )
	{
		$this->template = $this->checkboxTemplate;
		if ( !empty($options['class']) && 'toggle' == $options['class'] ) {
			$this->template = str_replace('"ui checkbox"', '"ui toggle checkbox"', $this->template);
		}
		
		// resolve id
		$id = empty($options['id'])
			? substr(md5($this->attribute . microtime(true) . static::$_cbCounter ++), 0, 6)
			: $options['id'];
		$options['id'] = $id;
		
		// label options
		$labelOptions = $this->labelOptions;
		$labelOptions['for'] = $id;
		if ( !empty($options['label']) ) {
			$labelOptions['label'] = $options['label'];
			unset($options['label']);
		}
		
		// checkbox options
		$cbOptions = [
			'id' => $id,
			'value' => 1,
			'checked' => (bool) ArrayHelper::getValue($this->model, $this->attribute),
		];
		
		$this->parts['{input}'] =
			Html::activeInput('hidden', $this->model, $this->attribute, ['value'=>0])
			. Html::activeInput('checkbox', $this->model, $this->attribute, $cbOptions)
			. Html::activeLabel($this->model, $this->attribute, $labelOptions);
		$this->parts['{label}'] = '';

		if ( $this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT ) {
			$this->addErrorClassIfNeeded($options);
		}

		return $this;
	}
	
	public function dropDownListEx ( $items, $options = [] )
	{
		$options = array_merge($this->inputOptions, $options);
		if ( $this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT ) {
			$this->addErrorClassIfNeeded($options);
		}
		$this->addAriaAttributes($options);
		$this->adjustLabelFor($options);
		Html::addCssClass($options, 'ui dropdown');
		if ( !array_key_exists('id', $options) ) {
			$options['id'] = static::getInputId($this->model, $this->attribute);
		}
		$this->form->view->registerJs('$("#'.$options['id'].'").dropdown();');
		$this->parts['{input}'] = Html::activeDropDownList($this->model, $this->attribute, $items, $options);
		return $this;
	}
	
	public function staticText ( $options = [] )
	{
		$text = ArrayHelper::getValue($options, 'text');
		if ( empty($text) ) {
			$attribute = $this->attribute;
			$text = ArrayHelper::getValue($this->model, $attribute);
		}
		if ( !isset($options['encodeText']) || $options['encodeText'] ) {
			$text = Html::encode($text);
		}
		unset($options['encodeText']);
		
		$options['disabled'] = !empty($options['disabled']);
		$options['readonly'] = true;
		
		$this->parts['{input}'] = Html::input('text', null, $text, $options);
		$this->parts['{error}'] = '';
		return $this;
		
	}

}
