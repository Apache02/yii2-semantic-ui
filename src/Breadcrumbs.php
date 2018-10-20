<?php

namespace semantic;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use semantic\Semantic;



class Breadcrumbs extends \yii\widgets\Breadcrumbs
{
	const SIZE_TYPES	= ['mini','tiny','small','large','big','huge','massive'];
	
 	public $tag = 'div';
	public $options = ['class' => 'ui breadcrumb'];
	public $size = null;
	public $itemTemplate = "<div class=\"section\">{link}</div>\n";
	public $activeItemTemplate = "<div class=\"active section\">{link}</div>";
	public $divider = '<i class="right chevron icon divider"></i>';
	
	
	/**
	 * Renders the widget.
	 */
	public function run ()
	{
		if ( empty($this->links) ) {
			return;
		}
		$links = [];
		if ( $this->homeLink === null ) {
			$links[] = $this->renderItem([
				'label' => Yii::t('semantic', 'Home'),
				'url' => Yii::$app->homeUrl,
			], $this->itemTemplate);
		} elseif ( $this->homeLink !== false ) {
			$links[] = $this->renderItem($this->homeLink, $this->itemTemplate);
		}
		foreach ( $this->links as $link ) {
			if ( !is_array($link) ) {
				$link = ['label' => $link];
			}
			$links[] = $this->renderItem($link, isset($link['url']) ? $this->itemTemplate : $this->activeItemTemplate);
		}
		
		$options = $this->options;
		if ( !empty($this->size) && in_array($this->size, static::SIZE_TYPES) ) {
			Html::addCssClass($options, $this->size);
		}
		$options['id'] = $this->id;
		echo Html::tag($this->tag, implode($this->divider, $links), $options);
	}
}
