<?php
use homer\widgets\Menu;
use yii\helpers\Html;
use yii\icons\Icon;
use yii\helpers\Url;
$identity = Yii::$app->user->identity;
?>
<!-- Navigation -->
<aside id="menu">
    <div id="navigation">
        <div class="profile-picture">
            <a href="<?= Url::to(['/user/settings/profile']); ?>">
                <?php if(!Yii::$app->user->isGuest):?>
                <img src="<?= $identity->profile->getAvatar(); ?>" class="img-circle m-b img-responsive center-block" alt="logo" style="width: 76px;">
                <?php endif; ?>
            </a>
            <?php if(!Yii::$app->user->isGuest):?>
            <div class="stats-label text-color">
                <span class="font-extra-bold font-uppercase"><?= $identity->profile->name; ?></span>

                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                        <small class="text-muted">Founder of App <b class="caret"></b></small>
                    </a>
                    <ul class="dropdown-menu animated flipInX m-t-xs">
                        <li><?= Html::a(Icon::show('newspaper-o').'ข้อมูลส่วนตัว',['/user/settings/profile'],['title' => 'ข้อมูลส่วนตัว','data-pjax' => '0']); ?></li>
                        <?php if(\Yii::$app->user->can('Admin')) : ?>
                            <li><?= Html::a(Icon::show('users').'จัดการผู้ใช้งาน',['/user/admin/index'],['title' => 'จัดการผู้ใช้งาน','data-pjax' => '0']); ?></li>
                        <?php endif; ?>
                        <li class="divider"></li>
                        <li><?= Html::a(Icon::show('sign-out').'ออกจากระบบ',['/user/security/logout'],['title' => 'ออกจากระบบ','data-method' => 'post']); ?></li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php
        echo Menu::widget([
            'options' => ['class' => 'nav','id' => 'side-menu'],
            'encodeLabels' => false,
            'key'=> Yii::$app->id,
            'activateParents' => true,
        ]);
        ?>

        <?php
        /*
        echo Menu::widget([
            'items' => [
                [
                    'label' => 'หน้าหลัก',
                    'icon' => 'fa fa-home',
                    'url' => ['/site/index'],
                    'visible' => !Yii::$app->user->isGuest
                    //'badge-label' => '20',
                    //'badgeOptions' => ['class' => 'label label-warning pull-right']
                ],
                [
                    'label' => 'Gii',
                    'icon' => 'fa fa-newspaper-o',
                    'url' => ['/gii'],
                    'visible' => \Yii::$app->user->can('Admin')
                ],
                [
                    'label' => 'ประชาสัมพันธ์',
                    'icon' => 'pe-7s-note2',
                    'url' => '#',
                    'items' => [
                        [
                            'label' => 'ออกบัตรคิว',
                            'icon' => 'pe-7s-id',
                            'url' => ['/kiosk/default/index'],
                        ],
                    ],
                    'visible' => \Yii::$app->user->can('Admin') || \Yii::$app->user->can('Admin')
                ],
                [
                    'label' => 'คลีนิกอายุรกรรม',
                    'icon' => 'fa fa-user-md',
                    'url' => '#',
                    'items' => [
                        [
                            'label' => 'ลงทะเบียนคิว',
                            'icon' => 'fa fa-circle-thin',
                            'url' => ['/kiosk/register/index'],
                        ],
                        [
                            'label' => 'เรียกคิวคัดกรอง',
                            'icon' => 'fa fa-circle-thin',
                            'url' => ['/kiosk/calling/screening-room'],
                        ],
                        [
                            'label' => 'เรียกคิวห้องตรวจโรค',
                            'icon' => 'fa fa-circle-thin',
                            'url' => ['/kiosk/calling/examination-room'],
                        ],
                    ],
                    'visible' => \Yii::$app->user->can('Admin') || \Yii::$app->user->can('อายุรกรรม')
                ],
                [
                    'label' => 'ห้องเจาะเลือด',
                    'icon' => 'pe-7s-note2',
                    'url' => '#',
                    'items' => [
                        [
                            'label' => 'ลงทะเบียนคิว',
                            'icon' => 'fa fa-circle-thin',
                            'url' => ['/kiosk/register/index'],
                        ],
                        [
                            'label' => 'เรียกคิว',
                            'icon' => 'fa fa-circle-thin',
                            'url' => ['/kiosk/calling/blooddrill-room'],
                        ],
                    ],
                    'visible' => \Yii::$app->user->can('Admin') || \Yii::$app->user->can('เจาะเลือด')
                ],
                [
                    'label' => 'โปรแกรมเสียง',
                    'icon' => 'pe-7s-volume',
                    'url' => ['/kiosk/calling/play-sound'],
                ],
                [
                    'label' => 'Display',
                    'icon' => 'pe-7s-monitor',
                    'url' => ['/kiosk/display/display-list'],
                ],
                [
                    'label' => 'ตั้งค่าทั่วไป', 
                    'url' => '#',
                    'icon' => 'pe-7s-tools',
                    'items' => [
                        ['label' => 'ผู้ใช้งาน', 'icon' => 'fa fa-users', 'url' => ['/user/admin/index']],
                        ['label' => 'สิทธิ์การใช้งาน', 'icon' => 'pe-7s-unlock', 'url' => ['/admin-manager']],
                        ['label' => 'Key-Value-Storage', 'icon' => 'fa fa-circle-thin', 'url' => ['/key-storage/index']],
                    ],
                    'visible' => \Yii::$app->user->can('Admin')
                ],
                [
                    'label' => 'ตั้งค่าระบบคิว',
                    'icon' => 'pe-7s-tools',
                    'url' => ['/settings/default/index'],
                    'visible' => \Yii::$app->user->can('Admin')
                ],
                [
                    'label' => 'ข้อมูลส่วนตัว',
                    'icon' => 'fa fa-user',
                    'url' => ['/user/settings/profile'],
                    'visible' => !Yii::$app->user->isGuest
                ],
                [
                    'label' => 'ออกจากระบบ',
                    'icon' => 'fa fa-sign-out',
                    'url' => ['/user/security/logout'],
                    'template' => '<a href="{url}" data-method="post"><span class="nav-label">{icon} {label}</span>{badge}</a>',
                    'visible' => !Yii::$app->user->isGuest
                ],
                ['label' => 'Login', 'url' => ['/user/login'], 'visible' => Yii::$app->user->isGuest],
            ],
            'options' => ['class' => 'nav','id' => 'side-menu']
        ]);*/
        ?>

        <!-- <ul class="nav" id="side-menu">
            <li>
                <a href="index.html"> <span class="nav-label">Dashboard</span> <span class="label label-success pull-right">v.1</span> </a>
            </li>
            <li>
                <a href="analytics.html"> <span class="nav-label">Analytics</span><span class="label label-warning pull-right">NEW</span> </a>
            </li>
            <li>
                <a href="#"><span class="nav-label">Interface</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="panels.html">Panels design</a></li>
                    <li><a href="typography.html">Typography</a></li>
                    <li><a href="buttons.html">Colors &amp; Buttons</a></li>
                    <li><a href="components.html">Components</a></li>
                    <li><a href="alerts.html">Alerts</a></li>
                    <li><a href="modals.html">Modals</a></li>
                    <li><a href="loading_buttons.html">Loading buttons</a></li>
                    <li><a href="draggable.html">Draggable panels</a></li>
                    <li><a href="code_editor.html">Code editor</a></li>
                    <li><a href="email_template.html">Email template</a></li>
                    <li><a href="nestable_list.html">List</a></li>
                    <li><a href="tour.html">Tour</a></li>
                    <li><a href="icons.html">Icons library</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><span class="nav-label">App views</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="contacts.html">Contacts</a></li>
                    <li><a href="projects.html">Projects</a></li>
                    <li><a href="project.html">Project detail</a></li>
                    <li><a href="app_plans.html">App plans</a></li>
                    <li><a href="social_board.html">Social board</a></li>
                    <li><a href="faq.html">FAQ</a></li>
                    <li><a href="timeline.html">Timeline</a></li>
                    <li><a href="notes.html">Notes</a></li>
                    <li><a href="profile.html">Profile</a></li>
                    <li><a href="mailbox.html">Mailbox</a></li>
                    <li><a href="mailbox_compose.html">Email compose</a></li>
                    <li><a href="mailbox_view.html">Email view</a></li>
                    <li><a href="blog.html">Blog</a></li>
                    <li><a href="blog_details.html">Blog article</a></li>
                    <li><a href="forum.html">Forum</a></li>
                    <li><a href="forum_details.html">Forum details</a></li>
                    <li><a href="gallery.html">Gallery</a></li>
                    <li><a href="calendar.html">Calendar</a></li>
                    <li><a href="invoice.html">Invoice</a></li>
                    <li><a href="file_manager.html">File manager</a></li>
                    <li><a href="chat_view.html">Chat view</a></li>
                    <li><a href="search.html">Search view</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><span class="nav-label">Charts</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="chartjs.html">ChartJs</a></li>
                    <li><a href="flot.html">Flot charts</a></li>
                    <li><a href="inline.html">Inline graphs</a></li>
                    <li><a href="chartist.html">Chartist</a></li>
                    <li><a href="c3.html">C3 Charts</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><span class="nav-label">Box transitions</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="overview.html"><span class="label label-success pull-right">Start</span> Overview </a>  </li>
                    <li><a href="transition_two.html">Columns from up</a></li>
                    <li><a href="transition_one.html">Columns custom</a></li>
                    <li><a href="transition_three.html">Panels zoom</a></li>
                    <li><a href="transition_four.html">Rows from down</a></li>
                    <li><a href="transition_five.html">Rows from right</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><span class="nav-label">Common views</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="login.html">Login</a></li>
                    <li><a href="register.html">Register</a></li>
                    <li><a href="error_one.html">Error 404</a></li>
                    <li><a href="error_two.html">Error 505</a></li>
                    <li><a href="lock.html">Lock screen</a></li>
                    <li><a href="password_recovery.html">Passwor recovery</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><span class="nav-label">Tables</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="tables_design.html">Tables design</a></li>
                    <li><a href="datatables.html">Data tables</a></li>
                    <li><a href="footable.html">Foo Table</a></li>

                </ul>
            </li>
            <li>
                <a href="widgets.html"> <span class="nav-label">Widgets</span> <span class="label label-success pull-right">Special</span></a>
            </li>
            <li>
                <a href="#"><span class="nav-label">Forms</span><span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="forms_elements.html">Forms elements</a></li>
                    <li><a href="forms_extended.html">Forms extended</a></li>
                    <li><a href="text_editor.html">Text editor</a></li>
                    <li><a href="wizard.html">Wizard</a></li>
                    <li><a href="validation.html">Validation</a></li>
                </ul>
            </li>
            <li>
                <a href="options.html"> <span class="nav-label">Layout options</span></a>
            </li>
            <li>
                <a href="grid_system.html"> <span class="nav-label">Grid system</span></a>
            </li>
            <li>
                <a href="landing_page.html"> <span class="nav-label">Landing page</span></a>
            </li>
            <li>
                <a href="package.html"> <span class="nav-label">Package</span></a>
            </li>

        </ul> -->
    </div>
</aside>