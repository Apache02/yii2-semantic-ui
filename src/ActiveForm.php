<?php

namespace semantic;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use semantic\Widget;
use semantic\Semantic;


class ActiveForm extends \yii\widgets\ActiveForm
{
	/**
	 * @var array
	 */
	public $options		= ['class' => 'ui form'];
	public $size		= false;
	
	public $fieldClass	= 'semantic\ActiveField';
	public $errorCssClass = 'error';
	
	
	const SIZE_TYPES	= ['mini','small','large','big'];
	const MESSAGE_TYPES	= [
		'error', 'negative', 'info', 'warning', 'success', 'positive',
		'floating', 'compact',
		'mini', 'tiny', 'small', 'large', 'big', 'huge', 'massive',
		'icon',
	];
	const BUTTON_TYPES	= [
		'mini','small','large','big',
		'fluid',
		'teal',
		'submit',
		'primary', 'secondary',
	];
	
	
	public function init ()
	{
//		Html::addCssClass($this->options, 'ui form');
		if ( $this->size && in_array($this->size, self::SIZE_TYPES) ) {
			Html::addCssClass($this->options, $this->size);
		}
		parent::init();
	}
	
	public function message ( $html, $type, $options = [] )
	{
		$options['class'] = 'ui message';
		if ( is_string($type) ) {
			$type = preg_split('/[\s.,;]+/', $type);
		}
		$type = array_intersect($type, self::MESSAGE_TYPES);
		if ( $type ) {
			if ( in_array('error', $type) ) {
				$type[] = 'visible';
			}
			Html::addCssClass($options, implode(' ', $type));
		}
		if ( ArrayHelper::remove($options, 'closeable', false) ) {
			$html = Semantic::icon('close') . $html;
			$this->getView()
				->registerJs('$(".ui.message").on("click",".close",function(){$(this).closest(".ui.message").transition("fade");});');
		}
		return Html::tag('div', $html, $options);
	}
	
	public function errors ( $model )
	{
		if ( $model->hasErrors() ) {
			$errors = array_map(function ( $arr ) {
				return Html::encode($arr[0]);
			}, array_values($model->getErrors()));
			return $this->message('<ul><li>'.implode('</li><li>', $errors).'</li></ul>', 'error');
		}
	}
	
	public function button ( $label, $types = [], $options = [] )
	{
		Html::addCssClass($options, 'ui');
		if ( is_string($types) ) {
			$types = explode(' ', $types);
		}
		foreach ( $types as $type ) {
			if ( isset(self::BUTTON_TYPES[$type]) ) {
				$type = self::BUTTON_TYPES[$type];
			}
			if ( in_array($type, self::BUTTON_TYPES) ) {
				Html::addCssClass($options, $type);
			}
		}
		Html::addCssClass($options, 'button');
		if ( in_array('submit', $types) ) {
			$options['type'] = 'submit';
		}
		if ( !isset($options['encodeLabel']) || $options['encodeLabel'] ) {
			$label = Html::encode($label);
		}
		$icon = ArrayHelper::remove($options, 'icon');
		if ( $icon ) {
			$label = Semantic::icon($icon) . $label;
		}
		return Html::tag('button', $label, $options);
	}

}
