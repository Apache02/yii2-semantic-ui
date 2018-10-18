<?php

namespace semantic;


class AssetBundle extends \yii\web\AssetBundle
{
	public $sourcePath = __DIR__ . '/assets';
	public $depends = [
		'yii\web\JqueryAsset',
	];

    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/{version}/semantic.min.css',
		'yii-semantic.css'
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/{version}/semantic.min.js',
    ];
	
	public $jsModes = [
		'telInput' => '/jquery.telInput.js',
		'action_ajax_modal_form' => '/semantic.action_ajax_modal_form.js',
	];
	
	public function init ()
	{
		$version = Semantic::getVersion();
		foreach ( ['js', 'css'] as $attibute ) {
			foreach ( $this->$attibute as &$resource ) {
				$resource = str_replace('{version}', $version, $resource);
			}
		}
		parent::init();
	}
	
	
	public function registerMod ( $view, $mod )
	{
		if ( isset($this->jsModes[$mod]) ) {
			$view->registerJsFile(
				$this->baseUrl . $this->jsModes[$mod],
				['depends' => [static::class]]
			);
		}
	}
}
