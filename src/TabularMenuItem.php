<?php

namespace semantic;

use yii\helpers\Html;
use semantic\Semantic;



class TabularMenuItem extends Menu
{
	public $type = ['tabular'];
	
	public $urlAttr = [];
	
	
	public function isItemActive ( $item )
	{
		return isset($item['url']) && $this->view->context->action->id == $item['url'][0];
	}
	
	public function run ()
	{
		foreach ( $this->items as $key => $val ) {
			if ( is_string($val) ) {
				$this->items[$key] = [
					'url'		=> array_merge([$key], $this->urlAttr),
					'label'		=> $val,
				];
			}
			if ( !empty($val['disabled']) ) {
				unset($this->items[$key]['url']);
			}
		}
		parent::run();
	}
	

}
