<?php

namespace semantic;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use semantic\Semantic;



class DetailView extends \yii\widgets\DetailView
{
	public $options = [
		'class'	=> 'ui collapsing definition table',
	];
	
	public $template = '<tr><td{captionOptions}>{icon}{label}</td><td{contentOptions}>{value}</td></tr>';
	
	
	/**
	 * {@inheritdoc}
	 */
	protected function renderAttribute($attribute, $index)
	{
		if ( is_string($this->template) ) {
			$captionOptions = Html::renderTagAttributes(ArrayHelper::getValue($attribute, 'captionOptions', []));
			$contentOptions = Html::renderTagAttributes(ArrayHelper::getValue($attribute, 'contentOptions', []));
			$icon = ArrayHelper::getValue($attribute, 'icon', null);
			return strtr($this->template, [
				'{label}' => $attribute['label'],
				'{value}' => $this->formatter->format($attribute['value'], $attribute['format']),
				'{captionOptions}' => $captionOptions,
				'{contentOptions}' => $contentOptions,
				'{icon}' => $icon ? Semantic::icon($icon).' ' : '',
			]);
		}

		return call_user_func($this->template, $attribute, $index, $this);
	}

}
