 <?php

  use yii\helpers\Html;

  $css = <<<CSS
  /* body, h1, h2, h3, h4, h5, h6 {
    font-family: 'Sriracha', sans-serif !important;
  } */

  .time>span.time__hours:not(:last-child):after,
  .time>span.time__min:not(:last-child):after,
  .time>span.time__sec:not(:last-child):after {
    content: ':';
    width: 10px;
    text-align: center;
    display: inline-block;
    position: relative;
    top: -1px;
    right: -1px;
  }

  @media (max-width: 1299px) {
    .iq-top-navbar, body.sidebar-main .iq-top-navbar {
      width: auto !important;
      left: 7% !important;
      right: 7% !important;
    }
  }

  @media (min-width: 1300px) {
    body.sidebar-main-menu .iq-top-navbar {
      width: auto !important;
      left: 5% !important;
      right: 5% !important;
    }
  }
  .iq-top-navbar {
      min-height: 100px;
      background: #17a2b8;
  }
CSS;
  $this->registerCss($css);

  $formatter = Yii::$app->formatter;
  ?>
 <!-- TOP Nav Bar -->
 <div id="iq-top-navbar" class="iq-top-navbar header-top-sticky" style="width: auto;left: 6%;right: 6%;">
   <div class="iq-navbar-custom">
     <div class="iq-sidebar-logo">
       <div class="top-logo">
         <a href="index.html" class="logo">
           <img src="<?= $directoryAsset ?>/images/logo.png" class="img-fluid" alt="">
           <span> Andaman Pattanalp</span>
         </a>
       </div>
     </div>
     <nav class="navbar navbar-expand-lg navbar-light p-0" style="min-height: 100px;">
       <div class="iq-search-bar">
         <?= Html::img('https://qstd3.andamandev.com/uploads/sirintorn.png', ['width' => '80px', 'height' => '80px']) ?>
       </div>
       <br>
       <div class="kiosk-title">
         <h1 class="display-6 mb-0 text-white">โรงพยาบาลสิรินธร</h1>
         <h3 class="display-6 mb-0 text-white">Sirindhorn Hospital</h3>
       </div>
       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
         <i class="ri-menu-3-line"></i>
       </button>
       <!-- <div class="iq-menu-bt align-self-center">
         <div class="wrapper-menu">
           <div class="main-circle"><i class="ri-more-fill"></i></div>
           <div class="hover-circle"><i class="ri-more-2-fill"></i></div>
         </div>
       </div> -->
       <div class="collapse navbar-collapse" id="navbarSupportedContent">

       </div>
       <ul class="navbar-list">
         <li>
           <a href="javascript:void(0);" class="search-toggle iq-waves-effect d-flex align-items-center">
             <div class="caption clock">
               <h4 class="mb-0 line-height text-white time">
                 <span id="kiosk-date">
                   <?= $formatter->asDate('now', 'php:l d F ') . ($formatter->asDate('now', 'php:Y') + 543) ?>
                 </span>
               </h4>
               <h5 class="mb-0 line-height text-white time text-right">
                 เวลา <span class="time__hours"><?= $formatter->asDate('now', 'php:H') ?></span><span class="time__min"><?= $formatter->asDate('now', 'php:i') ?></span><span class="time__sec"><?= $formatter->asDate('now', 'php:s') ?></span> น.
               </h5>
             </div>
           </a>
         </li>
       </ul>
     </nav>

   </div>
 </div>
 <!-- TOP Nav Bar END -->


 <?php
$js = <<<JS
  if ($(".clock")[0]) {
    var e = new Date()
    e.setDate(e.getDate()),
    setInterval(function () {
      var e = new Date().getSeconds()
      $(".time__sec").html((e < 10 ? "0" : "") + e)
    }, 1e3),
    setInterval(function () {
      var e = new Date().getMinutes()
      $(".time__min").html((e < 10 ? "0" : "") + e)
    }, 1e3),
    setInterval(function () {
      var e = new Date().getHours()
      $(".time__hours").html((e < 10 ? "0" : "") + e)
    }, 1e3)
  }
JS;
$this->registerJs($js);
?>