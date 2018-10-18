<?php

namespace semantic\grid;

use \Exception;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use semantic\LinkPager;



class GridView extends \yii\grid\GridView
{
	public $tableOptions = ['class'=>'ui celled sortable table'];
	public $dataColumnClass = 'semantic\grid\DataColumn';
	
	
	public function renderPager()
	{
		$pagination = $this->dataProvider->getPagination();
		if ($pagination === false || $this->dataProvider->getCount() <= 0) {
			return '';
		}
		/* @var $class LinkPager */
		$pager = $this->pager;
		$class = ArrayHelper::remove($pager, 'class', LinkPager::className());
		$pager['pagination'] = $pagination;
		$pager['view'] = $this->getView();
		return $class::widget($pager);
	}
	
}
