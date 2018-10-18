<?php

namespace semantic;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;
use semantic\AssetBundle;

/**
 * Renders phone number input
 *
 */
//

class TelInput extends InputWidget
{
	
	public $countryLabel = null;
	public $defaultCountry = null;
	public $list = null;
	
	
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
	
	private function register ()
	{
		$view = $this->getView();
		$asset = AssetBundle::register($view);
		$id = $this->id;
		if ( isset($this->options['id']) ) {
			$id = $this->options['id'];
		}
		$asset->registerMod($view, 'telInput'); 
		
		$jsOptions = static::copyProps($this, ['countryLabel', 'defaultCountry','list']);
		$jsOptions = $jsOptions ? json_encode($jsOptions) : '';
		$view->registerJs('$("#'.$id.'").telInput('.$jsOptions.');');
	}
	
    public function run ()
    {
		$this->register();
		echo $this->renderInputHtml('tel');
    }
}
