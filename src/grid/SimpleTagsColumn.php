<?php

namespace semantic\grid;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use semantic\Semantic;


class SimpleTagsColumn extends DataColumn
{
	public $tagName			= 'div';
	public $tagOptions		= ['class' => 'ui label'];
	public $tags			= null;
	public $separator		= '';
	public $encodeLabels	= true;
	public $template		= '{icon}{label}';
	
	
	private function checkOptions ()
	{
		if ( !$this->tags || !is_array($this->tags) ) {
			throw new Exception('Attribute "tags" is not defined.');
		}
		foreach ( $this->tags as $descr ) {
			if ( empty($descr) ) {
				throw new Exception('Attribute "tags" format error.');
			}
		}
	}
	
	public function init ()
	{
		$this->checkOptions();
		parent::init();
	}
	
	protected function evaluateCondition ( $if, $model )
	{
		if ( is_string($if) ) {
			$inverted = false;
			if ( $if[0] == '!' ) {
				$inverted = true;
				$if = substr($if, 1);
			}
			if ( !strlen($if) ) {
				return false;
			}
			$result = (bool) ArrayHelper::getValue($model, $if);
			return $inverted
				? !$result
				: $result;
		} else if ( is_callable($if) ) {
			return call_user_func($if, $model);
		}
		return false;
	}
	
	protected function renderTag ( $tag, $model )
	{
		$options = $this->tagOptions;
		$tagName = isset($tag['tagName']) ? $tag['tagName'] : $this->tagName;
		if ( !empty($tag['title']) ) {
			$options['title'] = $tag['title'];
		}
		if ( isset($tag['color']) ) {
			Html::addCssClass($options, $tag['color']);
		}
		$label = $tag['label'];
		if ( is_callable($label) ) {
			$label = call_user_func($label, $model);
		}
		if ( $this->encodeLabels ) {
			$label = Html::encode($label);
		}
		$label = strtr($this->template, [
			'{label}' => $label,
			'{icon}' => empty($tag['icon']) ? '' : Semantic::icon($tag['icon']),
		]);
		return Html::tag($tagName, $label, $options);
	}
	
	
	/**
	 * {@inheritdoc}
	 */
    protected function renderDataCellContent ( $model, $key, $index )
    {
		$tags = array_filter(array_map(function ( $descr ) use ( $model ) {
			if ( $this->evaluateCondition($descr['if'], $model) ) {
				return $this->renderTag($descr, $model);
			}
			return null;
		}, $this->tags));
		return implode($this->separator, $tags);
    }
}
