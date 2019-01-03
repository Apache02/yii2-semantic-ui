<?php

namespace semantic;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use semantic\Widget;
use semantic\Semantic;


/**
 * allowed flags:
 * - tabular
 * - secondary
 * - inverted
 */


class Menu extends Widget
{
	const ALLOWED_TYPES = [
//		'right floated',
		'inverted',
		'secondary',
		'tabular',
		'vertical',
	];
	
	public $itemAliases = [
		'divider'			=> '<div class="ui divider"></div>',
		'fitted divider'	=> '<div class="ui fitted divider"></div>',
	];
	
	public $options = ['class'=>'ui menu'];
	public $tagName = 'div';
	
	public $type = [];
	
	public $items = [];
	public $activeCssClass = 'active';
	public $labelTemplate = '{icon} {label} {dropdown}';
	public $labelTemplateSubmenu = '{dropdown} {icon} {label}';
	public $container = false;
	
	public $route = null;
	public $allowDropdown = true;
	
	
	
	private function checkOptions ()
	{
		if ( is_string($this->type) ) {
			$this->type = [$this->type];
		}
		if ( !is_array($this->type) ) {
			throw new Exception('Attribute "type" must be string or array');
		}
		foreach ( $this->type as $type ) {
			if ( !in_array($type, self::ALLOWED_TYPES) ) {
				throw new Exception('Type "'.$type.'" is not allowed for menu');
			}
		}
		if ( $this->route === null && Yii::$app->controller !== null ) {
			$this->route = Yii::$app->controller->getRoute();
		}
	}
	
	/**
	 * Checks whether a menu item is active.
	 * be considered active.
	 * @param array $item the menu item to be checked
	 * @return bool whether the menu item is active
	 */
	protected function isItemActive ( $item )
	{
		if ( isset($item['url']) && is_array($item['url']) && isset($item['url'][0]) ) {
			$route = Yii::getAlias($item['url'][0]);
			if ( $route[0] !== '/' && Yii::$app->controller ) {
				$route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
			}
			if ( ltrim($route, '/') !== $this->route ) {
				return false;
			}

			return true;
		}

		return false;
	}
	/*
	public function isItemActive ( $item )
	{
	}
	*/
	
	public function run ()
	{
		$this->checkOptions();
		
		$options = $this->options;
		$options['id'] = $this->id;
		
		$addClass = $this->type
			? ' '.implode(' ', $this->type)
			: '';
		if ( $addClass ) {
			Html::addCssClass($options, $addClass);
		}
		
		$html = $this->renderItems($this->items);
		if ( $this->container ) {
			$html = '<div class="ui container">'
				. $html
				. '</div>';
		}

        echo Html::tag('div', $html, $options);
	}
	
	public function renderItems ( $items, $depth = 0 )
	{
		return implode('', array_map(function ( $item ) use ( $depth ) {
			return call_user_func([$this, 'renderItem'], $item, $depth);
		}, $items));
	}
	
	public function renderItem ( $item, $depth )
	{
		if ( is_string($item) && isset($this->itemAliases[$item]) ) {
			return $this->itemAliases[$item];
		}
		if ( isset($item['visible']) && !$item['visible'] ) {
			return '';
		}
		if ( isset($item['html']) ) {
			return $item['html'];
		}
		
		$content = '';
		if ( is_string($item) ) {
			$content = $item;
			$item = [
				'tag'	=> 'div',
			];
		} else if ( isset($item['content']) ) {
			$content = $item['content'];
		} else {
			
			if ( !empty($item['items']) ) {
				if ( !$this->allowDropdown ) {
					$item['dropdown'] = false;
				} else if ( empty($item['dropdown']) ) {
					$item['dropdown'] = true;
				}
				$item['tag'] = 'div';
			}
			// Normalize dropdown attribute
			if ( !isset($item['dropdown']) || !$item['dropdown'] ) {
				$item['dropdown'] = false;
			} else if ( !is_string($item['dropdown']) ) {
				$item['dropdown'] = 'dropdown';
			}
			
			$template = ArrayHelper::getValue($item, 'template', ($depth ? $this->labelTemplateSubmenu : $this->labelTemplate));
			
			$content = Semantic::renderTemplate($template, [
				'icon'		=> isset($item['icon'])
					? Semantic::icon($item['icon'])
					: '',
				'label'		=> (isset($item['encodeLabel']) && !$item['encodeLabel'])
					? $item['label']
					: Html::encode($item['label']),
				'dropdown'	=> $item['dropdown']
					? Semantic::icon($item['dropdown'])
					: '',
				'depth'		=> $depth,
			]);
		}
		
		$options = isset($item['options'])
			? $item['options']
			: [];
		Html::addCssClass($options, 'item');
		
		if ( empty($item['active']) ) {
			$item['active'] = $this->isItemActive($item);
		}
		if ( $item['active'] ) {
			Html::addCssClass($options, $this->activeCssClass);
		}
		if ( !empty($item['disabled']) && !in_array('disabled', explode(' ', $options['class'])) ) {
			Html::addCssClass($options, 'disabled');
		}
		
		$tagName = isset($item['tag'])
			? $item['tag']
			: (isset($item['url']) ? 'a' : 'div');
		
		if ( $tagName == 'a' ) {
			$url = isset($item['url'])
				? $item['url'] : '';
			return Html::a($content, $url, $options);
		}
		
		// sub-menu (dropdown)
		if ( !empty($item['items']) ) {
			$optionsSub = ['class' => 'ui menu'];
			if ( $this->allowDropdown ) {
				Html::addCssClass($options, 'ui dropdown');
				$this->view->registerJs("$('#{$this->id} .ui.dropdown').dropdown();");
			} else {
				$optionsSub = ['class' => 'menu'];
			}
			$content .= Html::tag('div', $this->renderItems($item['items'], $depth + 1), $optionsSub);
		}
		return Html::tag($tagName, $content, $options);
	}

}
