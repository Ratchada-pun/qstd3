<?php

use kartik\switchinput\SwitchInput;
use yii\helpers\Html;
use yii\helpers\Json;
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
    font-size: 30px;
}
footer.bg-white.iq-footer {
    position: fixed;
    bottom: 0;
    right: 120px;
    left: 120px;
    /* display:none; */
}

footer {
    display:none;
}

CSS;
$this->registerCss($css);

$themeAsset = Yii::$app->assetManager->getPublishedUrl('@xray/assets/dist');
$this->registerCssFile("https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::class],
]);
$this->registerJsFile(
    'https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>
<?php /*
<div class="row">
    <div class="col-md-6 col-lg-8">
    </div>
    <div class="col-md-6 col-lg-4" style="padding-top: 45px;text-align: center;">
        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <?php
            echo '<label class="control-label" style="font-size:16pt;">เลือก ภาษา/Select language</label>';
            echo SwitchInput::widget([
                'name' => 'locale',
                'value' => substr(Yii::$app->language, 0, 2) == 'th',
                'pluginOptions' => [
                    'size' => 'large',
                    'onColor' => 'success',
                    'offColor' => 'danger',
                    'onText' => 'ภาษาไทย',
                    'offText' => 'English',
                ],
                'pluginEvents' => [
                    "switchChange.bootstrapSwitch" => "function() {
                    if($(this).is(':checked')) {
                        app.\$i18n.locale = 'th';
                    } else {
                        app.\$i18n.locale = 'en';
                    }
                }",
                ]
            ]);
            ?>
        </div>
    </div>
</div>
*/ ?>
<div class="row">
    <div class="col-md-6 col-lg-6">
    </div>
    <div class="col-md-6 col-lg-6" style="padding-top: 45px;text-align: right;">
        <button id="btn-th" titel="ภาษาไทย" type="button" class="btn btn-outline-light"> <?= Html::img('/img/th.svg', ['width' => '65px']) ?></button>
        <button id="btn-en" titel="English" type="button" class="btn btn-outline-light"> <?= Html::img('/img/us.svg', ['width' => '65px']) ?></button>
    </div>
</div>
<div id="app" class="kiosk-container">

    <?php /*
    <!-- begin:: Section Home -->
    <section v-if="!action" class="section-home">

        <!-- begin: Title -->
        <div class="row" style="padding-top: 20%;">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="sawatdee text-center" style="padding-top: 46px;">
                    <h1 class="text-center">
                        {{ $t("แผนกเวชระเบียน ยินดีให้บริการค่ะ") }}
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
                        {{ $t("Select language") }}
                    </h1>
                </section>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-xl-12 col-sm-12 animated animate__zoomIn faster">
                <a href="#" class="button-effect" @click.prevent="onSelectLanguage('th')">
                    <div class="iq-card card-section-1">
                        <div class="iq-card-body">
                            <div class="d-flex">
                                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                                    <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option">
                                        <h4>{{ $t("ภาษาไทย") }}</h4>
                                    </span>
                                </div>
                                <div>
                                    <?= Html::img('/img/th.svg', ['width' => '65px']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-12 col-sm-12 animated animate__zoomIn faster">
                <a href="#" class="button-effect" @click.prevent="onSelectLanguage('en')">
                    <div class="iq-card card-section-1">
                        <div class="iq-card-body">
                            <div class="d-flex">
                                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                                    <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option">
                                        <h4>{{ $t("English") }} </h4>
                                    </span>
                                </div>
                                <div>
                                    <?= Html::img('/img/us.svg', ['width' => '65px']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <marquee direction="left" scrollamount="5" style="font-size: 40px; color: #a579ed;">
                    <?php echo $news_ticker['news_ticker_detail'] ?>
                </marquee>
            </div>
        </div>
    </section>
    */ ?>


    <!-- begin:: สแกนบัตรประชาชน -->
    <section v-if="action === 'scan-idcard' && !patient" class="section-scan-idcard">
        <div v-cloak class="row">
            <div class="col-md-12 col-lg-12 col-sm-12" style="padding-top: 154px;">
                <div class="text-center" style="padding-top: 46px;">
                    <h1>{{ $t("ทำรายการด้วยบัตรประชาชน") }}</h1>
                    <br>
                    <?= Html::img($themeAsset . '/images/kiosk/Thai-smart-card.png', ['style' => '']) ?>
                    <h4>
                        {{ $t(loadingMsg) }}
                    </h4>

                    <div v-if="loading" class="lds-dual-ring"></div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-3 col-lg-3 col-sm-12">
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12" style="padding-top: 120px">
                <a href="#" class="button-effect" @click.prevent="onCancelAction()">
                    <div class="iq-card card-section-2" style="background: #dc3545;">
                        <div class="iq-card-body" style="padding: 10px;">
                            <div class="d-flex">
                                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                                    <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option text-center">
                                        <h4 class="text-white"><i class="fas fa-arrow-left"></i> {{ $t("Cancel") }}</h4>
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
    <section v-if="!patient && !right && action === 'hn-or-idcard'" class="section-scan-idcard">
        <div v-cloak class="row">
            <div class="col-md-12 col-lg-12 col-sm-12" style="padding-top: 140px;">
                <div class="text-center" style="padding-top: 46px;">
                    <h1>{{ $t("Enter your ID Card Number") }}</h1>
                </div>
                <br>
                <form>
                    <div class="form-group row mb-0">
                        <div class="col-md-8 col-md-offset-2 offset-sm-2">
                            <input v-model="search" ref="search" id="input-hn-or-idcard" type="number" max="13" class="form-control form-control-lg text-center bg-white" style="font-size: 2rem;color: #17a2b8;" autofocus>
                        </div>
                    </div>
                    <br>
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
            <div class="col-md-4 col-lg-4 col-sm-12" style="padding-top: 115px">
                <a href="#" class="button-effect" @click.prevent="onCancelAction()">
                    <div class="iq-card card-section-2" style="background: #dc3545;">
                        <div class="iq-card-body" style="padding: 10px;">
                            <div class="d-flex">
                                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                                    <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option text-center">
                                        <h4 class="text-white"><i class="fas fa-arrow-left"></i> {{ $t("Cancel") }}</h4>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-12" style="padding-top: 115px"></div>
            <div class="col-md-4 col-lg-4 col-sm-12" style="padding-top: 115px">
                <!-- <a href="#" class="button-effect" @click.prevent="onConfirmSearch()">
                    <div class="iq-card card-section-2" style="background: #28a745;">
                        <div class="iq-card-body" style="padding: 10px;">
                            <div class="d-flex">
                                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                                    <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option text-center">
                                        <h4 class="text-white">{{ $t("OK") }} <i class="far fa-check-circle"></i></h4>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a> -->
            </div>
        </div>
    </section>
    <!-- end:: ป้อนเลข HN หรือ เลขบัตรประชาชน -->

    <!-- Begin:: ข้อมูลผู้ป่วย -->
    <section v-if="patient || right">
        <div v-cloak class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <h3>{{ $t("ข้อมูลทั่วไป") }}</h3>
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height iq-user-profile-block" style="height: 90%;">
                    <div class="iq-card-body">
                        <div class="user-details-block">
                            <div class="user-profile text-center" style="background-color: #eeeeee;border-radius: 1.75rem;width: 135px;height: 135px;margin: auto;margin-top: -85px !important">
                                <img :src="avatar" alt="profile-img" class="avatar-130 img-fluid rounded" style="padding: 15px;">
                            </div>
                            <div class="text-center mt-3">
                                <h4><b>{{ patientName }}</b></h4>
                                <p>
                                <h4>{{ $t("Age") }} {{ age }} {{ $t("Year") }}</h4>
                                </p>
                            </div>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="text-center" style="width: 50%;">
                                            <h4 class="text-primary">{{ $t("เลขบัตรประจำตัวประชาชน") }}</h4>
                                            <h5>{{ cidFormat }}<span></span></h5>
                                        </td>
                                        <td class="text-center" style="width: 50%;">
                                            <h4 class="text-primary">{{ $t("สิทธิการรักษา") }}</h4>
                                            <h5>{{ rightName }}<span></span></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" style="width: 50%;">
                                            <h4 class="text-primary">{{ $t("หน่วยบริการประจำ") }}</h4>
                                            <h5>
                                                {{ getRight('hmain_op_name', '-') }}
                                                <span></span>
                                            </h5>
                                        </td>
                                        <td class="text-center" style="width: 50%;">
                                            <h4 class="text-primary">{{ $t("หน่วยบริการปฐมภูมิ") }}</h4>
                                            <h5>{{ getRight('hsub_name', '-') }}<span></span></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" style="width: 50%;">
                                            <h4 class="text-primary" style="margin-left:8%">{{ $t("หน่วยบริการรับส่งต่อ") }}</h4>
                                            <h5 style="margin-left:15%">{{ getRight('hmain_name', '-') }}<span></span></h5>
                                        </td>
                                        <td class="text-center">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php /*
                            <ul class="doctoe-sedual d-flex align-items-center justify-content-between p-0 mt-4 mb-0">
                                <!--  <li class="text-center">
                  <h4 class="text-primary">HN</h4>
                  <h5>{{ hn }}<span></span></h5>
                </li> -->
                                <li class="text-center">
                                    <h4 class="text-primary">{{ $t("เลขบัตรประจำตัวประชาชน") }}</h4>
                                    <h5>{{ cidFormat }}<span></span></h5>
                                </li>
                                <li class="text-center">
                                    <h4 class="text-primary">{{ $t("สิทธิการรักษา") }}</h4>
                                    <h5>{{ rightName }}<span></span></h5>
                                </li>
                            </ul>
                            <ul class="doctoe-sedual d-flex align-items-center justify-content-between p-0 mt-4 mb-0">
                                <li class="text-center">
                                    <h4 class="text-primary">{{ $t("หน่วยบริการประจำ") }}</h4>
                                    <h5>
                                        {{ getRight('hmain_op_name', '-') }}
                                        <span></span>
                                    </h5>
                                </li>
                                <li class="text-center">
                                    <h4 class="text-primary">{{ $t("หน่วยบริการปฐมภูมิ") }}</h4>
                                    <h5>{{ getRight('hsub_name', '-') }}<span></span></h5>
                                </li>
                            </ul>
                            <ul class="doctoe-sedual d-flex align-items-center justify-content-between p-0 mt-8 mb-0">
                                <li class="text-left">
                                    <h4 class="text-primary" style="margin-left:8%">{{ $t("หน่วยบริการรับส่งต่อ") }}</h4>
                                    <h5 style="margin-left:15%">{{ getRight('hmain_name', '-') }}<span></span></h5>
                                </li>
                            </ul>
                            */ ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end:: ข้อมูลผู้ป่วย -->

    <section v-if="(patient || right) || action !== 'hn-or-idcard'">
        <div v-cloak class="row pt-5">
            <div class="col-md-12 col-lg-12 col-sm-12" style="padding-top: 15px;">
                <div class="service-title">
                    <h1>{{ $t("เลือกบริการ") }}</h1>
                </div>

                <div class="row">
                    <div v-for="(item, index) in services" :key="index" class="col-md-12 col-lg-12 col-sm-12">
                        <a href="#" class="button-effect" @click.prevent="onSelectService(item.serviceid)">
                            <div class="iq-card card-section-1" :style="{ background: service_id === item.serviceid ? '#ffffff' : '#17a2b8' }">
                                <div class="iq-card-body" style="padding: 10px;">
                                    <div class="d-flex">
                                        <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                                            <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option">
                                                <h4 :class="{ 'text-white': service_id !== item.serviceid, 'text-success': service_id === item.serviceid }">
                                                    {{ index + 2 }}. {{ $t(item.btn_kiosk_name) }} <i v-show="service_id === item.serviceid" class="far fa-check-circle"></i>
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
                <a v-if="(patient || right) && !service_id" href="#" class="button-effect" @click.prevent="onCancelAction()">
                    <div class="iq-card card-section-2" style="background: #dc3545;">
                        <div class="iq-card-body" style="padding: 10px;">
                            <div class="d-flex">
                                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                                    <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option text-center">
                                        <h4 class="text-white"><i class="fas fa-arrow-left"></i> {{ $t("Cancel") }}</h4>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-12"></div>
            <div class="col-md-4 col-lg-4 col-sm-12">
                <!-- <a  href="#" class="button-effect" @click.prevent="onCreateQueue()" :style="disabledStyle">
                    <div class="iq-card card-section-2" :style="{ background: '#28a745', opacity: opacity }">
                        <div class="iq-card-body" style="padding: 10px;">
                            <div class="d-flex">
                                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto">
                                    <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option text-center">
                                        <h4 class="text-white">{{ $t("OK") }} <i class="far fa-check-circle"></i></h4>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a> -->
            </div>
        </div>

    </section>

    <div v-if="!patient && !right && action !== 'hn-or-idcard'" v-cloak class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="service-title">
                <h1>หรือทำรายการโดย</h1>
            </div>
            <div class="text-center">
                <?= Html::img($themeAsset . '/images/kiosk/Thai-smart-card.png', ['style' => 'width:100px']) ?>
                <h4>
                    {{ $t(loadingMsg) }}
                </h4>

                <div v-if="loading" class="lds-dual-ring"></div>
            </div>
        </div>
    </div>

    <section v-if="!patient && !right && action !== 'hn-or-idcard'" class="section-home">
        <div class="row">

            <div class="col-xl-12 col-sm-12 animated animate__zoomIn faster">
                <a href="#" class="button-effect" @click.prevent="onSelectAction('hn-or-idcard')">
                    <div class="iq-card card-section-1">
                        <div class="iq-card-body">
                            <div class="d-flex">
                                <div class="d-flex flex-column flex-grow-1 gutter-b m-auto ">
                                    <span class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 card-title-option ">
                                        <h4>{{ $t("Enter your ID Card Number") }} </h4>
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
        </div>
    </section>
    <!-- end:: Section Home -->

    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <marquee direction="left" scrollamount="5" style="font-size: 40px; color: #a579ed;">
                <?php echo $news_ticker['news_ticker_detail'] ?>
            </marquee>
        </div>
    </div>

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
$this->registerJsFile(
    'https://unpkg.com/vue-i18n@8',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

$baseURL = YII_ENV_DEV ? Url::base(true) : "http://192.168.100.253";
$socketBaseURL = YII_ENV_DEV ? 'http://localhost:3000' : "http://192.168.100.253";
$socketPath = YII_ENV_DEV ? '/socket.io' : "/node/socket.io";
$nodeBaseURL = YII_ENV_DEV ? 'http://localhost:3000' : "http://192.168.100.253/node";
$patientPicture = $themeAsset . "/images/kiosk/patient.png";
$nodeBaseURLLocal = 'http://192.168.100.253';
$locale = substr(Yii::$app->language, 0, 2);
$messages = Json::encode($messages);
$js = <<<JS
  window.baseURL = "$baseURL";
  window.patientPicture = "$patientPicture";
  window.nodeBaseURL = "$nodeBaseURL";
  window.socketBaseURL = "$socketBaseURL";
  window.socketPath = "$socketPath";
  window.nodeBaseURLLocal = "$nodeBaseURLLocal";
  window.locale = "$locale";
  var messages = $messages;
JS;
$this->registerJs($js, \yii\web\View::POS_HEAD);
$this->registerJs($this->render(YII_ENV_DEV ? 'kiosk.js' : 'kiosk.min.js'), \yii\web\View::POS_READY);
?>