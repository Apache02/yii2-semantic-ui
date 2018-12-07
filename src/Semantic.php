<?php

namespace semantic;

use yii\base\Exception;
use yii\helpers\Html;


// Semantic helper class

class Semantic
{
	
	const WIDE_NAMES	= [
		1		=> 'one',
		2		=> 'two',
		3		=> 'three',
		4		=> 'four',
		5		=> 'five',
		6		=> 'six',
		7		=> 'seven',
		8		=> 'eight',
		9		=> 'nine',
		10		=> 'ten',
		11		=> 'eleven',
		12		=> 'twelve',
		13		=> 'thirteen',
		14		=> 'fourteen',
		15		=> 'fifteen',
		16		=> 'sixteen',
	];
	
	
	public static function getVersion ()
	{
		return '2.4.1';
	}
	
	public static function wide ( $n )
	{
		if ( !isset(self::WIDE_NAMES[$n]) ) {
			throw new Exception('Unexpected wide number');
		}
		return self::WIDE_NAMES[$n];
	}
	
	public static function icon ( $name )
	{
		if ( !is_string($name) ) {
			throw new Exception('Icon name should be a string');
		}
		return '<i class="icon '.$name.'"></i>';
	}
	
	public static function flag ( $id )
	{
		if ( !is_string($id) ) {
			throw new Exception('Flag id should be a string');
		}
		return '<i class="flag '.$id.'"></i>';
	}
	
	public static function mergeHtmlOptions ( &$target, ...$merges )
	{
		foreach ( $merges as $mergeWith ) {
			if ( is_array($mergeWith) ) {
				foreach ( $mergeWith as $attr => $value ) {
					if ( $attr == 'class' ) {
						if ( !$value ) {
							// nothing to merge with
							// skip
							continue;
						}
						
						Html::addCssClass($target, $value);
					} else if ( $attr == 'data' && is_array($value) ) {
						foreach ( $value as $dattr=>$dvalue ) {
							$target['data-'.$dattr] = $dvalue;
						}
					} else {
						$target[$attr] = $value;
					}
				}
			}
		}
		return $target;
	}
	
	// render template helper
	public static function renderTemplate ( $template, $params, $undefinedValue = '' )
	{
		return preg_replace_callback('/\{(\w+)\}/', function ( $match ) use ( $params, $undefinedValue ) {
			$attr = $match[1];
			return isset($params[$attr])
				? $params[$attr]
				: (is_string($undefinedValue) ? $undefinedValue : $match[0]);
		}, $template);
	}
	
	
	// @return array prepared items for DropDownInput widget
	public static function dataCountryList ( $list )
	{
		foreach ( $list as $i => &$item ) {
			$item = [
				'flag'	=> $i,
				'label' => $item,
			];
		}
		return $list;
	}


	
}
