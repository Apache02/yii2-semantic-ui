<?php

namespace semantic\grid;

use yii\helpers\Html;
use semantic\Semantic;
use yii\base\Exception;


class UserLinkColumn extends DataColumn
{
	public $url						= false;
	public $urlOptions				= [];
	public $relationName			= null;
	public $notFoundDisplay			= null;
	
	
	/**
	 * {@inheritdoc}
	 */
	public function init ()
	{
		parent::init();
		
		if ( !$this->notFoundDisplay ) {
			$this->notFoundDisplay = '<i class="null">not found</i>';
		}
		if ( !is_string($this->relationName) ) {
			throw new Exception('Attribute "relationName" must be a string.');
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
    protected function renderDataCellContent ( $model, $key, $index )
    {
		$value = $this->getDataCellValue($model, $key, $index);
		
		if ( !$model || !$value ) {
			return '';
		}
		
		$user = $model->{$this->relationName};
		if ( !$user ) {
			return $this->notFoundDisplay;
		}
		
		$url = $this->url;
		$url['id'] = $user->id;
		$options = $this->urlOptions;
		if ( is_callable($options) ) {
			$options = call_user_func($options, $model, $key, $index);
		}
		$label = Html::encode($user->username);
		return Html::a($label, $url, $options);
    }
}
