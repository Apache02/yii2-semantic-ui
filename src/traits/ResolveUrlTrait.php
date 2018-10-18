<?php

namespace semantic\traits;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;


trait ResolveUrlTrait
{
	protected function resolveUrl ( $url, $attrs, $item )
	{
		if ( is_string($attrs) ) {
			$url[$attrs] = ArrayHelper::getValue($item, $this->attribute);
		} else if ( is_array($attrs) ) {
			foreach ( $attrs as $urlKey => $itemKey ) {
				$url[$urlKey] = ArrayHelper::getValue($item, $itemKey);
			}
		}
		return $url;
	}

}
