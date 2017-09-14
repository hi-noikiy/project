<?php

class KindEditor extends CInputWidget{
	private $language = 'zh_CN';
    public  $themeType;
	
	public function getAssetsPath(){
		$baseDir = dirname(__FILE__);
		return Yii::app()->getAssetManager()->publish($baseDir.DIRECTORY_SEPARATOR.'assets');
	}
    
	public function makeOptions(){
		list($name, $id) = $this->resolveNameID();
        
		if($this->themeType=='simple'){
			$items="items : ['fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
				'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
				'insertunorderedlist', '|', 'emoticons' , 'link'],";
		}else 
			$items='';
		$assets = $this->getAssetsPath();
		$cssPath = $assets . '/plugins/code/prettify.css';
		$uploadJson = $assets . '/php/upload_json.php';
		$fileManagerJson = $assets . '/php/file_manager_json.php'; 
		$pluginsPath=$assets.'/plugins/';
		$script = <<<EOP

$(function() {
	var editor = KindEditor.create('textarea[id="{$id}"]', {
		cssPath : '{$cssPath}',
		uploadJson : '{$uploadJson}',
		fileManagerJson : '{$fileManagerJson}',
		allowFileManager : true,
		pluginsPath : '{$pluginsPath}',		
		afterCreate : function() {
			var self = this;
			KindEditor.ctrl(document, 13, function() {
				self.sync();
				KindEditor('form')[0].submit();
			});
			KindEditor.ctrl(self.edit.doc, 13, function() {
				self.sync();
				KindEditor('form')[0].submit();
			});
		},
		$items		
	});
	prettyPrint();
	});
EOP;
		return $script;
	}

    public function run(){
        parent::run();
        $assets = $this->getAssetsPath();
        $cs = Yii::app()->getClientScript();
        $path="/protected/extensions/kindeditor/assets";
        $cs->registerScriptFile($assets.'/plugins/code/prettify.js',CClientScript::POS_BEGIN);
        $cs->registerCssFile($assets.'/themes/default/default.css');
        $cs->registerCssFile($assets.'/plugins/code/prettify.css');
        $cs->registerScriptFile($assets.'/kindeditor.js',CClientScript::POS_BEGIN);
        $cs->registerScriptFile($assets.'/lang/zh_CN.js',CClientScript::POS_BEGIN);
        $cs->registerScriptFile($assets.'/plugins/code/prettify.js',CClientScript::POS_BEGIN);
        $cs->registerScript('content',$this->makeOptions(),CClientScript::POS_BEGIN);
    }
}
?>