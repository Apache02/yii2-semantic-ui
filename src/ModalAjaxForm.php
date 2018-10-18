<?php

namespace semantic;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use semantic\AssetBundle;
use semantic\Widget;


class ModalAjaxForm extends Widget
{
	public $actionSelector = null;
	public $loaderHtml = null;
	
	public $modalActions = null;
	public $options = ['class'=>'ui small basic modal'];
	
	
	
	public static function copyProps ( $from, $attributes )
	{
		$props = [];
		foreach ( $attributes as $attribute ) {
			if ( ($val = ArrayHelper::getValue($from, $attribute, null)) !== null ) {
				$props[$attribute] = $val;
			}
		}
		return $props;
	}
	
	private function checkOptions ()
	{
		if ( empty($this->actionSelector) || !is_string($this->actionSelector) ) {
			throw new Exception('Attribute "actionSelector" must be a string.');
		}
		if ( !$this->modalActions ) {
			$this->modalActions = [
				'ok'	=> [
					'action'	=> 'ok',
					'class'		=> 'green inverted',
					'label'		=> Yii::t('semantic', 'Ok'),
					'icon'		=> 'check',
				],
				'close' => [
					'action'	=> 'close',
					'class'		=> 'red basic inverted',
					'label'		=> Yii::t('semantic', 'Cancel'),
					'icon'		=> 'remove',
				],
			];
		}
	}
	
	private function register ()
	{
		$view = $this->getView();
		$asset = AssetBundle::register($view);
		$id = $this->id;
		
		$asset->registerMod($view, 'action_ajax_modal_form');
		
		$jsOptions = static::copyProps($this, ['loaderHtml']);
		$jsOptions = json_encode($jsOptions);
		$view->registerJs("$('{$this->actionSelector}').action_ajax_modal_form('#{$id}',{$jsOptions});");
	}
	
	public function renderActionButton ( $item )
	{
		$label = Html::encode($item['label']);
		$options = ['class'=>'ui ' . $item['class'] . ' button'];
		switch ( $item['action'] ) {
			case 'ok':
			case 'success':
			case 'positive':
				$options['class'] .= ' ok';
				break;
			case 'close':
			case 'cancel':
			case 'negative':
				$options['class'] .= ' cancel';
				break;
		}
		return Html::tag('button', $label, $options);
	}
	
    public function run ()
    {
		$this->checkOptions();
		$this->register();
		
		$html = '<div class="content"></div>'
			. '<div class="actions">'
				. implode(array_map([$this, 'renderActionButton'], $this->modalActions))
			. '</div>';
		
		$options = $this->options;
		if ( empty($options['id']) ) {
			$options['id'] = $this->id;
		}
		echo Html::tag('div', $html, $options);
    }
	
}
