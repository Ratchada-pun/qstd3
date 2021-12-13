<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'โรงพยาบาลสิรินธร';

$css = <<<CSS
  .kiosk-container {
    padding: 20px;
  }
  .card-section-1 {
    transition: all .4s ease-in;
    border-radius: 7rem;
    box-shadow: 0 10px 0 rgb(0 0 0 / 20%);
    border: 1px solid #17a2b8;
  }
  .card-section-2 {
    transition: all .4s ease-in;
    border-radius: 7rem;
    box-shadow: 0 10px 0 rgb(0 0 0 / 20%);
    border: 1px solid #ffffff;
  }
  .card-section-1:hover,
  .card-section-2:hover {
    background: #ffa800 radial-gradient(circle,transparent 1%,#ffa800 0) 50%/15000%;
    transform: scale(1.05);
  }
  .card-section-1:hover h1,
  .card-section-1:hover h2,
  .card-section-1:hover h3,
  .card-section-1:hover h4,
  .card-section-1:hover h5,
  .card-section-1:hover h6 {
    color: #fff;
  }


  .lds-dual-ring {
    display: inline-block;
    width: 80px;
    height: 80px;
  }
  .lds-dual-ring:after {
    content: " ";
    display: block;
    width: 64px;
    height: 64px;
    margin: 8px;
    border-radius: 50%;
    border: 6px solid #17a2b8;
    border-color: #17a2b8 transparent #17a2b8 transparent;
    animation: lds-dual-ring 1.2s linear infinite;
  }
  @keyframes lds-dual-ring {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }

  /* numpad style */
  .buttons {
    display: flex;
    font-family: Helvetica;
    font-weight: 400;
    flex-wrap: wrap;
    justify-content: flex-start;
    margin: 0 auto;
    width: 310px;
  }

  .button-numpad {
    border: 1px solid #006494;
    border-radius: 50px;
    color: #006494;
    cursor: pointer;
    display: inline-block;
    font-size: 26px;
    height: 80px;
    line-height: 80px;
    margin: 10px;
    text-align: center;
    width: 80px;
    -webkit-transition: all 0.3s;
    transition: all 0.3s;
  }

  .button-numpad:hover {
    background-color: #006494;
    color: #FFFFFF;
  }

  .iq-card-body {
    padding: 20px;
    font-size: 45px;
}

CSS;
$this->registerCss($css);

$themeAsset = Yii::$app->assetManager->getPublishedUrl('@xray/assets/dist');
?>

<div id="app" class="kiosk-container">
  <!-- begin:: Section Home -->
  <section v-if="!action" class="section-home">
    <!-- begin: Title -->
    <div class="row">
      <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="sawatdee text-center" style="padding-top: 46px;">
          <h1 class="text-center">
            แผนกเวชระเบียน ยินดีให้บริการค่ะ
          </h1>
          <div class="sawatdee-img">
            <?= Html::img($themeAsset . '/images/kiosk/sawatdee.gif', ['style' => 'margin-left: 10rem;']) ?>
          </div>
        </div>
      </div>
    </div>
    <!-- end: Title -->
    <div class="row">
      <div class="col-md-12 col-lg-12 col-sm-12">
        <section>
          <h1 class="text-center">
            ทำรายการด้วย
          </h1>
        </section>
      </div>
    </div>
    
    <br>

    <div class="row">

      <div class="col-xl-12 col-sm-12 animated animate__zoomIn faster">
        <a href="#" class="button-effect" @click.prevent="onSelectAction('scan-idcard')">
          <div class="iq-card card-section-1">
            <div class="iq-card-body">
              <div class="d-flex">
                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto" >
                  <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option" >
                    <h4 >สแกนบัตรประชาชน</h4>
                  </span>
                </div>
                <div>
                  <?= Html::img($themeAsset . '/images/kiosk/thai-id-card.png', ['width' => '100px']) ?>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-xl-12 col-sm-12 animated animate__zoomIn faster">
        <a href="#" class="button-effect" @click.prevent="onSelectAction('hn-or-idcard')">
          <div class="iq-card card-section-1">
            <div class="iq-card-body">
              <div class="d-flex">
                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                  <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option">
                    <h4>ป้อนหมายเลขบัตรประชาชน </h4>
                  </span>
                </div>
                <div>
                  <?= Html::img($themeAsset . '/images/kiosk/keypad.png', ['width' => '65px']) ?>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-xl-12 col-sm-12 animated animate__zoomIn faster">
        <a href="#" class="button-effect" @click.prevent="onSelectAction('no-idcard')">
          <div class="iq-card card-section-1">
            <div class="iq-card-body">
              <div class="d-flex">
                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                  <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option">
                    <h4>รับคิว(กรณีไม่มีบัตรประชาชน)</h4>
                  </span>
                </div>
                <div>
                  <?= Html::img($themeAsset . '/images/kiosk/thai-id-card.png', ['width' => '100px']) ?>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>
  <!-- end:: Section Home -->

  <!-- begin:: สแกนบัตรประชาชน -->
  <section v-if="action === 'scan-idcard' && !patient" class="section-scan-idcard">
    <div v-cloak class="row">
      <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="text-center"  style="padding-top: 46px;">
          <h1>ทำรายการด้วยบัตรประชาชน</h1>
          <br>
          <?= Html::img($themeAsset . '/images/kiosk/Thai-smart-card.png', ['style' => '']) ?>
          <h4>
            {{ loadingMsg }}
          </h4>

          <div v-if="loading" class="lds-dual-ring"></div>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-3 col-lg-3 col-sm-12">
      </div>
      <div class="col-md-6 col-lg-6 col-sm-12">
        <a href="#" class="button-effect" @click.prevent="onCancelAction()">
          <div class="iq-card card-section-2" style="background: #dc3545;">
            <div class="iq-card-body" style="padding: 10px;">
              <div class="d-flex">
                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                  <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option text-center">
                    <h4 class="text-white"><i class="fas fa-arrow-left"></i> ยกเลิก / Cancel</h4>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-2 col-lg-2 col-sm-12">
      </div>
    </div>
  </section>
  <!-- end:: สแกนบัตรประชาชน -->

  <!-- Begin:: ป้อนเลข HN หรือ เลขบัตรประชาชน -->
  <section v-if="action === 'hn-or-idcard' && !patient && !right" class="section-scan-idcard">
    <div v-cloak class="row">
      <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="text-center m-auto">
          <h2>ป้อนหมายเลขบัตรประจำตัวประชาชน</h2>
        </div>
        <form>
          <div class="form-group row mb-0">
            <div class="col-md-8 col-md-offset-2 offset-sm-2">
              <input v-model="search" ref="search" id="input-hn-or-idcard" type="number" max="13" class="form-control form-control-lg text-center bg-white" style="font-size: 2rem;color: #17a2b8;" autofocus>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-8 col-md-offset-2 offset-sm-2">
              <div class="buttons">
                <div class="button-numpad" @click="onClickNumber(1)">1</div>
                <div class="button-numpad" @click="onClickNumber(2)">2</div>
                <div class="button-numpad" @click="onClickNumber(3)">3</div>
                <div class="button-numpad" @click="onClickNumber(4)">4</div>
                <div class="button-numpad" @click="onClickNumber(5)">5</div>
                <div class="button-numpad" @click="onClickNumber(6)">6</div>
                <div class="button-numpad" @click="onClickNumber(7)">7</div>
                <div class="button-numpad" @click="onClickNumber(8)">8</div>
                <div class="button-numpad" @click="onClickNumber(9)">9</div>
                <div class="button-numpad" @click="onDeleteNumber()"><i class="fas fa-arrow-left"></i></div>
                <div class="button-numpad" @click="onClickNumber(0)">0</div>
                <div class="button-numpad" @click="onClearSearch"><i class="fas fa-times"></i></div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-4 col-lg-4 col-sm-12">
        <a href="#" class="button-effect" @click.prevent="onCancelAction()">
          <div class="iq-card card-section-2" style="background: #dc3545;">
            <div class="iq-card-body" style="padding: 10px;">
              <div class="d-flex">
                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                  <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option text-center">
                    <h4 class="text-white"><i class="fas fa-arrow-left"></i> ยกเลิก / Cancel</h4>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4 col-lg-4 col-sm-12"></div>
      <div class="col-md-4 col-lg-4 col-sm-12">
        <a href="#" class="button-effect" @click.prevent="onConfirmSearch()">
          <div class="iq-card card-section-2" style="background: #28a745;">
            <div class="iq-card-body" style="padding: 10px;">
              <div class="d-flex">
                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                  <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option text-center">
                    <h4 class="text-white">ตกลง / OK <i class="far fa-check-circle"></i></h4>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>
  <!-- end:: ป้อนเลข HN หรือ เลขบัตรประชาชน -->

  <!-- Begin:: ข้อมูลผู้ป่วย -->
  <section v-if="patient || right">
    <div v-cloak class="row">
      <div class="col-md-12 col-lg-12 col-sm-12">
        <h3>ข้อมูลทั่วไป</h3>
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height iq-user-profile-block" style="height: 75%;">
          <div class="iq-card-body">
            <div class="user-details-block">
              <div class="user-profile text-center" style="background-color: #eeeeee;border-radius: 1.75rem;width: 135px;height: 135px;margin: auto;margin-top: -85px !important">
                <img :src="avatar" alt="profile-img" class="avatar-130 img-fluid rounded" style="padding: 15px;">
              </div>
              <div class="text-center mt-3">
                <h4><b>{{ patientName }}</b></h4>
                <p>
                <h4>อายุ {{ age }} ปี</h4>
                </p>
              </div>
              <ul class="doctoe-sedual d-flex align-items-center justify-content-between p-0 mt-4 mb-0">
               <!--  <li class="text-center">
                  <h4 class="text-primary">HN</h4>
                  <h5>{{ hn }}<span></span></h5>
                </li> -->
                <li class="text-center">
                  <h4 class="text-primary">เลขบัตรประจำตัวประชาชน</h4>
                  <h5>{{ cidFormat }}<span></span></h5>
                </li>
                <li class="text-center">
                  <h4 class="text-primary">สิทธิการรักษา</h4>
                  <h5>{{ rightName }}<span></span></h5>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end:: ข้อมูลผู้ป่วย -->

  <section v-if="patient || right || action === 'no-idcard'">
    <div v-cloak class="row pt-5">
      <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="service-title">
          <h3>เลือกบริการ</h3>
        </div>

        <div class="row">
          <div v-for="(item, index) in services" :key="index" class="col-md-6 col-lg-6 col-sm-12">
            <a href="#" class="button-effect" @click.prevent="onSelectService(item.serviceid)">
              <div class="iq-card card-section-1" :style="{ background: service_id === item.serviceid ? '#ffffff' : '#17a2b8' }">
                <div class="iq-card-body" style="padding: 10px;">
                  <div class="d-flex">
                    <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                      <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option text-center">
                        <h4 :class="{ 'text-white': service_id !== item.serviceid, 'text-success': service_id === item.serviceid }">
                          {{ item.btn_kiosk_name }} <i v-show="service_id === item.serviceid" class="far fa-check-circle"></i>
                        </h4>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>

      </div>
    </div>

    <br>
    <div class="row">
      <div class="col-md-4 col-lg-4 col-sm-12">
        <a href="#" class="button-effect" @click.prevent="onCancelAction()">
          <div class="iq-card card-section-2" style="background: #dc3545;">
            <div class="iq-card-body" style="padding: 10px;">
              <div class="d-flex">
                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                  <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option text-center">
                    <h4 class="text-white"><i class="fas fa-arrow-left"></i> ยกเลิก / Cancel</h4>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4 col-lg-4 col-sm-12"></div>
      <div class="col-md-4 col-lg-4 col-sm-12">
        <a href="#" class="button-effect" @click.prevent="onCreateQueue()" 
          :style="disabledStyle">
          <div class="iq-card card-section-2" :style="{ background: '#28a745', opacity: opacity }">
            <div class="iq-card-body" style="padding: 10px;">
              <div class="d-flex">
                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                  <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option text-center">
                    <h4 class="text-white">รับบัตรคิว / OK <i class="far fa-check-circle"></i></h4>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

  </section>

</div>


<?php

/* $this->registerJsFile(
'https://unpkg.com/nprogress@0.2.0/nprogress.js',
['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerJsFile(
'https://unpkg.com/axios/dist/axios.min.js',
['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerJsFile(
'https://cdn.jsdelivr.net/npm/sweetalert2@11',
['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerJsFile(
'https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js',
['depends' => [\yii\web\JqueryAsset::class]]
); */

$baseURL = YII_ENV_DEV ? Url::base(true) : "https://qstd3.andamandev.com";
$socketBaseURL = YII_ENV_DEV ? 'http://localhost:3000' : "https://qstd3.andamandev.com";
$socketPath = YII_ENV_DEV ? '/socket.io' : "/node/socket.io";
$nodeBaseURL = YII_ENV_DEV ? 'http://localhost:3000' : "https://qstd3.andamandev.com/node";
$patientPicture = $themeAsset . "/images/kiosk/patient.png";
$nodeBaseURLLocal = 'http://localhost:3000';
$js = <<<JS
  window.baseURL = "$baseURL";
  window.patientPicture = "$patientPicture";
  window.nodeBaseURL = "$nodeBaseURL";
  window.socketBaseURL = "$socketBaseURL";
  window.socketPath = "$socketPath";
  window.nodeBaseURLLocal = "$nodeBaseURLLocal";
JS;
$this->registerJs($js, \yii\web\View::POS_HEAD);
$this->registerJs($this->render(YII_ENV_DEV ? 'kiosk.js' : 'kiosk.min.js'), \yii\web\View::POS_READY);
?>