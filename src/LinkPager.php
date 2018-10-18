<?php

namespace semantic;

use yii\helpers\Html;
use semantic\Semantic;



class LinkPager extends \yii\widgets\LinkPager
{
	public $options = [
		'class'	=> 'ui pagination menu',
		'tag'	=> 'div',
	];
	
	public $pageCssClass = '';
	public $activePageCssClass = 'active';
	public $disabledPageCssClass = 'disabled';
	
	public $firstPageLabel = true;
	public $lastPageLabel = true;
	
	
	/**
	 * Renders a page button.
	 * You may override this method to customize the generation of page buttons.
	 * @param string $label the text label for the button
	 * @param int $page the page number
	 * @param string $class the CSS class for the page button.
	 * @param bool $disabled whether this page button is disabled
	 * @param bool $active whether this page button is active
	 * @return string the rendering result
	 */
	protected function renderPageButton ( $label, $page, $class, $disabled, $active )
	{
		$options = ['class' => 'item'];
		Html::addCssClass($options, empty($class) ? $this->pageCssClass : $class);
		if ( $active ) {
			Html::addCssClass($options, $this->activePageCssClass);
		}
		if ( $disabled ) {
			Html::addCssClass($options, $this->disabledPageCssClass);
			$disabledItemOptions = $this->disabledListItemSubTagOptions;
			return Html::tag('div', $label, $options);
		}
		$options['data-page'] = $page;
		return Html::a($label, $this->pagination->createUrl($page), $options);
	}

}
