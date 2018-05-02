<?php
namespace Ycn\Qiniu;

use Ycn\Qiniu\Assets\UploadAsset;
use yii\widgets\InputWidget;
use yii\helpers\Html;

/**
 * Created by PhpStorm.
 *
 * @author huangxianan <xianan_huang@163.com>
 * Date: 2018/4/26
 */

class UploadWidget extends InputWidget
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function run()
    {
        UploadAsset::register($this->view);
        return $this->renderInputHtml('file');
    }

    /**
     * @param string $type
     * @return string
     */
    public function renderInputHtml($type)
    {
        $inputId = Html::getInputId($this->model,$this->attribute);
        $js = <<<JS
        $("#{$inputId}").change(function(){
            var reader = new FileReader();
            reader.readAsDataURL(this.files[0]);
          // getObjectURL是自定义的函数，见下面  
          // this.files[0]代表的是选择的文件资源的第一个，因为上面写了 multiple="multiple" 就表示上传文件可能不止一个  
          // ，但是这里只读取第一个   
          var objUrl = getObjectURL(this.files[0]) ;
          if (objUrl) {  
            // 在这里修改图片的地址属性  
            $("#{$inputId}-show").attr("src", objUrl) ;
          }  
        }) ;  

        function getObjectURL(file) {  
          var url = null ;   
          // 下面函数执行的效果是一样的，只是需要针对不同的浏览器执行不同的 js 函数而已  
          if (window.createObjectURL!=undefined) { // basic  
            url = window.createObjectURL(file) ;  
          } else if (window.URL!=undefined) { // mozilla(firefox)  
            url = window.URL.createObjectURL(file) ;  
          } else if (window.webkitURL!=undefined) { // webkit or chrome  
            url = window.webkitURL.createObjectURL(file) ;  
          }  
          return url ;  
        }  
JS;
        $this->view->registerJs($js);

        if ($this->hasModel()) {
            $html = Html::activeInput($type, $this->model, $this->attribute, $this->options);
            $html .= Html::img("/",['id' => $inputId.'-show', 'width'=>200]);
            return $html;
        }
        return Html::input($type, $this->name, $this->value, $this->options);

    }
}