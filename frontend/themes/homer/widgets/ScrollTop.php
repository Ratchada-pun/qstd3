<?php

namespace homer\widgets;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\Html;

class ScrollTop extends Widget
{
	public function init()
    {
        parent::init();
        $view = $this->getView();
        $view->registerCss("
	        .back-to-top {
			    cursor: pointer;
			    position: fixed;
			    bottom: 20px;
			    right: 20px;
			    display:none;
			    z-index: 999;
			}
		");
        $view->registerJs("
	        $(window).scroll(function () {
		        if ($(this).scrollTop() > 50) {
		            $('#back-to-top').fadeIn();
		        } else {
		            $('#back-to-top').fadeOut();
		        }
		    });
		    // scroll body to 0px on click
		    $('#back-to-top').click(function () {
		        $('#back-to-top').tooltip('hide');
		        $('body,html').animate({
		            scrollTop: 0
		        }, 500);
		        return false;
		    });
		    $('#back-to-top').tooltip('show');
	    ");
    }

     public function run()
    {
        echo Html::a('<span class="glyphicon glyphicon-chevron-up"></span>','#',[
        	'id' 				=> 'back-to-top',
        	'class' 			=> 'btn btn-primary btn-lg back-to-top',
        	'role' 				=> 'button',
        	'title' 			=> 'Click to return on the top page',
        	'data-toggle' 		=> 'tooltip',
        	'data-placement' 	=> 'left'
        ]);
        parent::run();
    }
}