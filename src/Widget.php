<?php

namespace semantic;

use Yii;
use Exception;



class Widget extends \yii\base\Widget
{
    /**
     * @var array
     */
    public $options = [];

    public function init()
    {
        parent::init();
        isset($this->options['id'])
            ? $this->setId($this->options['id'])
            : $this->options['id'] = $this->getId();
    }

	public static function widgetObject ( $config = [] )
	{
		try {
			/* @var $widget Widget */
			$config['class'] = get_called_class();
			$widget = Yii::createObject($config);
			if ($widget->beforeRun()) {
				$result = $widget->run();
				echo $widget->afterRun($result);
			}
			return $widget;
		} catch ( Exception $e ) {
			throw $e;
		}
		return null;
	}
	
}
