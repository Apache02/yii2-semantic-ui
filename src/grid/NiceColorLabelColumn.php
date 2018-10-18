<?php

namespace semantic\grid;

use yii\helpers\Html;
use yii\base\Exception;
use semantic\Semantic;


class NiceColorLabelColumn extends DataColumn
{
	public $tagName			= 'div';
	public $colors			= [
		'red',		'orange',	'yellow',
		'olive',	'green',	'teal',
		'blue',		'violet',	'purple',
		'pink',		'brown',	'grey',
		'black',	'basic white',
	];
	public $colorMap		= [];
	public $excludeColorMap	= false;
	
	
	public function init ()
	{
		parent::init();
		
		if ( $this->colorMap && $this->excludeColorMap ) {
			$exclude = array_values($this->colorMap);
			$this->colors = array_values(array_filter($this->colors, function ( $col ) use ( $exclude ) {
				return !in_array($col, $exclude);
			}));
		}
	}
	
	
	/**
	 * {@inheritdoc}
	 */
    protected function renderDataCellContent ( $model, $key, $index )
    {
		$value = $this->getDataCellValue($model, $key, $index);
		
		// resolve color
		$color = '';
		$color = isset($this->colorMap[$value])
			? $this->colorMap[$value]
			: (count($this->colors)>0 ? $this->colors[abs(crc32($value)) % count($this->colors)] : '');
		
		return Html::tag($this->tagName, $value, ['class'=>'ui '.$color.' label']);
    }
}
