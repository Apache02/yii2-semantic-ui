<?php

namespace semantic\grid;

use yii\helpers\Html;

class DataColumn extends \yii\grid\DataColumn
{
	public function renderHeaderCell()
	{
		$options = $this->headerOptions;
		
		if (
			$this->enableSorting
			&& ($attribute = $this->attribute) !== null
			&& ($sort = $this->grid->dataProvider->getSort()) !== false
			&& $sort->hasAttribute($attribute)
		) {
			$order = $sort->attributeOrders;
			if ( isset($order[$attribute]) ) {
				if ( $order[$attribute] == SORT_ASC ) {
					Html::addCssClass($options, 'sorted ascending');
				} else if ( $order[$attribute] == SORT_DESC ) {
					Html::addCssClass($options, 'sorted descending');
				}
			}
		}
		
		return Html::tag('th', $this->renderHeaderCellContent(), $options);
	}

}
