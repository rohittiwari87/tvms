<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Order $order
 */
use Cake\Core\Configure;
use Cake\I18n\Time;

$gender = Configure::read('gender');
$interviewResult = Configure::read('interviewResult');

$action = $this->request->getParam('action');

$cityJP = Configure::read('cityJP');
$cityJP = array_map('array_shift', $cityJP);

$yesNoQuestion = Configure::read('yesNoQuestion');
$interviewType = Configure::read('interviewType');
$workTime = Configure::read('workTime');

$this->Html->css('bootstrap-datetimepicker.min.css', ['block' => 'styleTop']);
$this->Html->css('switchery.min.css', ['block' => 'styleTop']);
$this->Html->css('order.css', ['block' => 'styleTop']);
$this->Html->script('moment-with-locales.min.js', ['block' => 'scriptBottom']);
$this->Html->script('bootstrap-datetimepicker.min.js', ['block' => 'scriptBottom']);
$this->Html->script('switchery.min.js', ['block' => 'scriptBottom']);
$this->Html->script('sweet-alert.js', ['block' => 'scriptBottom']);
$this->Html->script('order.js', ['block' => 'scriptBottom']);
?>

<?php if ($action === 'add'): ?>
    <?php $this->assign('title', 'Thêm mới đơn hàng'); ?>
    <?php $this->start('content-header'); ?>
        <h1><?= __('THÊM MỚI ĐƠN HÀNG') ?></h1>
        <button class="btn btn-success submit-order-btn" type="button">Lưu lại</button>
        <ol class="breadcrumb">
            <li>
                <?= $this->Html->link(
                    '<i class="fa fa-home"></i> Trang Chủ',
                    '/',
                    ['escape' => false]) ?>
            </li>
            <li>
                <?= $this->Html->link(__('Danh sách đơn Hàng'), [
                    'controller' => 'Orders',
                    'action' => 'index']) ?>
            </li>
            <li class="active">Thêm mới đơn hàng</li>
        </ol>
    <?php $this->end(); ?>
<?php else: ?>
    <?php $this->assign('title', $order->name . ' - Cập nhật thông tin đơn hàng'); ?>
    <?php $this->start('content-header'); ?>
        <h1><?= __('CẬP NHẬT THÔNG TIN ĐƠN HÀNG') ?></h1>
        <button class="btn btn-success submit-order-btn" type="button">Lưu lại</button>
        <ol class="breadcrumb">
            <li>
                <?= $this->Html->link(
                    '<i class="fa fa-home"></i> Trang Chủ',
                    '/',
                    ['escape' => false]) ?>
            </li>
            <li>
                <?= $this->Html->link(__('Danh sách đơn hàng'), [
                    'controller' => 'Orders',
                    'action' => 'index']) ?>
            </li>
            <li class="active"><?= $order->name ?></li>
        </ol>
    <?php $this->end(); ?>
<?php endif; ?>

<?= $this->Form->create($order, [
    'class' => 'form-horizontal form-label-left',
    'id' => 'add-order-form',
    'data-parsley-validate' => '',
    'templates' => [
        'inputContainer' => '{{content}}'
        ]
    ]) ?>
<?= $this->Form->hidden('status') ?>
<?= $this->Form->unlockField('status') ?>
<?= $this->Form->unlockField('students') ?>

<div class="row">    
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= __('Thông tin cơ bản') ?></h3>
                <div class="box-tools pull-right">
                    <a href="javascript:;" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name"><?= __('Tên đơn hàng') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <?= $this->Form->control('name', [
                            'label' => false, 
                            'class' => 'form-control col-md-7 col-xs-12', 
                            'required' => true,
                            'placeholder' => 'Nhập tên đơn hàng'
                            ]) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="job_id"><?= __('Nghề nghiệp') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <?= $this->Form->control('job_id', [
                            'options' => $jobs, 
                            'empty' => true, 
                            'required' => true, 
                            'label' => false, 
                            'class' => 'form-control col-md-7 col-xs-12 select2-theme',
                            'data-parsley-errors-container' => '#error-job',
                            'data-parsley-class-handler' => '#select2-job-id',
                            ]) ?>
                        <span id="error-job"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="salary"><?= __('Mức lương') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <div class="col-md-5 col-sm-5 col-xs-12 group-picker">
                            <?= $this->Form->control('salary_from', [
                                'label' => false,
                                'min' => '0',
                                'less-than' => '#salary-to',
                                'class' => 'form-control col-md-7 col-xs-12 limit-min', 
                                'required' => true,
                                'placeholder' => '¥/tháng'
                                ]) ?>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 seperate-from-to"> ～ </div>
                        <div class="col-md-5 col-sm-5 col-xs-12 group-picker">
                            <?= $this->Form->control('salary_to', [
                                'label' => false,
                                'min' => '0',
                                'greater-than' => '#salary-from',
                                'class' => 'form-control col-md-7 col-xs-12 limit-max',
                                'required' => true,
                                'placeholder' => '¥/tháng'
                                ]) ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="interview_date"><?= __('Ngày phỏng vấn') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <div class="input-group date input-picker gt-now" id="interview-date">
                            <?= $this->Form->control('interview_date', [
                                'type' => 'text',
                                'label' => false, 
                                'class' => 'form-control',
                                'placeholder' => 'yyyy-mm-dd',
                                'required' => true,
                                'data-parsley-errors-container' => '#error-interview-date'
                                ])?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                        <span id="error-interview-date"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="work_at"><?= __('Địa điểm làm việc') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <?= $this->Form->control('work_at', [
                            'options' => $cityJP, 
                            'required' => true, 
                            'empty' => true, 
                            'label' => false, 
                            'data-parsley-errors-container' => '#error-address',
                            'data-parsley-class-handler' => '#select2-work-at',
                            'class' => 'form-control col-md-7 col-xs-12 select2-theme'
                            ]) ?>
                        <span id="error-address"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="work_time"><?= __('Thời gian làm việc') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <?= $this->Form->control('work_time', [
                            'options' => $workTime, 
                            'required' => true, 
                            'empty' => true, 
                            'label' => false, 
                            'data-parsley-errors-container' => '#error-work-time',
                            'data-parsley-class-handler' => '#select2-work-time',
                            'class' => 'form-control col-md-7 col-xs-12 select2-theme'
                            ]) ?>
                        <span id="error-work-time"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="company_id"><?= __('Công ty tiếp nhận') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <?= $this->Form->control('company_id', [
                            'options' => $companies,
                            'empty' => true, 
                            'required' => true, 
                            'label' => false, 
                            'class' => 'form-control col-md-7 col-xs-12 select2-theme',
                            'data-parsley-errors-container' => '#error-company',
                            'data-parsley-class-handler' => '#select2-company-id',
                            ]) ?>
                        <span id="error-company"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="skill_test"><?= __('Thi tay nghề') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <?= $this->Form->control('skill_test', [
                            'options' => $yesNoQuestion,
                            'required' => true,
                            'empty' => true,
                            'label' => false,
                            'data-parsley-errors-container' => '#error-skill-test',
                            'data-parsley-class-handler' => '#select2-skill-test',
                            'class' => 'form-control col-md-7 col-xs-12 select2-theme',
                            ]) ?>
                        <span id="error-skill-test"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="interview_type"><?= __('Hình thức phỏng vấn') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <?= $this->Form->control('interview_type', [
                            'options' => $interviewType,
                            'required' => true,
                            'empty' => true,
                            'label' => false,
                            'data-parsley-errors-container' => '#error-interview-type',
                            'data-parsley-class-handler' => '#select2-interview-type',
                            'class' => 'form-control col-md-7 col-xs-12 select2-theme',
                            ]) ?>
                        <span id="error-interview-type"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="departure_date"><?= __('Ngày xuất cảnh') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <div class="input-group date input-picker gt-now" id="departure-date">
                            <?= $this->Form->control('departure_date', [
                                'type' => 'text',
                                'label' => false, 
                                'class' => 'form-control',
                                'placeholder' => 'yyyy-mm-dd',
                                'required' => true,
                                'data-parsley-errors-container' => '#error-departure-date'
                                ])?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                        <span id="error-departure-date"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= __('Yêu cầu tuyển chọn') ?></h3>
                <div class="box-tools pull-right">
                    <a href="javascript:;" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12 optional" for="experience"><?= __('Kinh nghiệm') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <?= $this->Form->control('experience', [
                            'label' => false, 
                            'type' => 'textarea',
                            'rows' => 3,
                            'class' => 'form-control col-md-7 col-xs-12', 
                            'placeholder' => 'Nhập kinh nghiệm cần thiết'
                            ]) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="male_num"><?= __('Số lượng nam') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <?= $this->Form->control('male_num', [
                            'label' => false, 
                            'class' => 'form-control col-md-7 col-xs-12', 
                            'required' => true,
                            'placeholder' => 'Nhập số lượng nam cần tuyển'
                            ]) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="female_num"><?= __('Số lượng nữ') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <?= $this->Form->control('female_num', [
                            'label' => false, 
                            'class' => 'form-control col-md-7 col-xs-12', 
                            'required' => true,
                            'placeholder' => 'Nhập số lượng nữ cần tuyển'
                            ]) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="age_interval"><?= __('Độ tuổi') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <div class="col-md-5 col-sm-5 col-xs-12 group-picker">
                            <?= $this->Form->control('age_from', [
                                'label' => false,
                                'min' => '0',
                                'max' => '100',
                                'less-than' => '#age-to',
                                'class' => 'form-control col-md-7 col-xs-12 limit-min', 
                                'required' => true,
                                'placeholder' => 'Tuổi từ'
                                ]) ?>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 seperate-from-to"> ～ </div>
                        <div class="col-md-5 col-sm-5 col-xs-12 group-picker">
                            <?= $this->Form->control('age_to', [
                                'label' => false,
                                'min' => '0',
                                'max' => '100',
                                'greater-than' => '#age-from',
                                'class' => 'form-control col-md-7 col-xs-12 limit-max', 
                                'required' => true,
                                'placeholder' => 'Tuổi đến'
                                ]) ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12 optional" for="height"><?= __('Chiều cao') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <?= $this->Form->control('height', [
                            'label' => false, 
                            'class' => 'form-control col-md-7 col-xs-12',
                            'placeholder' => 'Nhập chiều cao tối thiểu cần thiết'
                            ]) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12 optional" for="weight"><?= __('Cân nặng') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <?= $this->Form->control('weight', [
                            'label' => false, 
                            'class' => 'form-control col-md-7 col-xs-12', 
                            'placeholder' => 'Nhập cân năng tối thiểu cần thiết'
                            ]) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12 optional" for="requirement"><?= __('Yêu cầu khác') ?></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <?= $this->Form->control('requirement', [
                            'label' => false, 
                            'type' => 'textarea',
                            'rows' => 3,
                            'class' => 'form-control col-md-7 col-xs-12', 
                            'placeholder' => 'Nhập yêu cầu khác (nếu có)'
                            ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= __('Danh sách ứng viên') ?></h3>
                <div class="box-tools pull-right">
                    <a href="javascript:;" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="overlay hidden" id="list-candidate-overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <button type="button" class="btn btn-primary btn-candidate" id="add-candidate" onclick="showAddCandidateModal();">
                    <?= __('Thêm ứng viên') ?>
                </button>
                <table class="table table-bordered custom-table candidate-table">
                    <thead>
                        <tr>
                            <th scope="col" class="col-md-1"><?= __('STT') ?></th>
                            <th scope="col" class="col-md-3"><?= __('Họ tên') ?></th>
                            <th scope="col" class="col-md-1"><?= __('Tuổi') ?></th>
                            <th scope="col" class="col-md-1"><?= __('Giới tính') ?></th>
                            <th scope="col" class="col-md-3"><?= __('Số ĐT') ?></th>
                            <th scope="col" class="col-md-1"><?= __('Kết quả') ?></th>
                            <th scope="col" class="actions"></th>
                        </tr>
                    </thead>
                    <tbody id="candidate-container">
                    <?php if (!empty($order->students)): ?>
                        <?php $counter = 0; $now = Time::now(); ?>
                        <?php foreach ($order->students as $key => $value): ?>
                            <div class="hidden candidate-id" id="candidate-<?=$counter?>-id">
                                <?= $this->Form->hidden('students.'  . $key . '.id', ['value' => $value->id]) ?>
                            </div>
                            <div class="hidden interview-id" id="interview-<?=$counter?>-id">
                                <?= $this->Form->hidden('students.' . $key . '._joinData.id') ?>
                            </div>
                            <tr class="row-rec" id="row-candidate-<?=$counter?>">
                                <td class="cell col-md-1 stt-col">
                                    <?= $counter+1 ?>
                                </td>
                                <td class="cell hidden"></td>
                                <td class="cell col-md-3">
                                    <a href="javascript:;" onclick="viewCandidate('<?=$value->id?>');"><?= h($value->fullname) ?></a>
                                </td>
                                <td class="cell col-md-1">
                                    <?= h(($now->diff($value->birthday))->y) ?>
                                </td>
                                <td class="cell col-md-1">
                                    <?= $gender[$value->gender]?>
                                </td>
                                <td class="cell col-md-3">
                                    <?= $this->Phone->makeEdit($value->phone) ?>
                                </td>
                                <td class="cell col-md-1">
                                    <span class="result-text"><?= $interviewResult[$value->_joinData->result] ?></span>
                                    <div class="hidden">
                                        <?= $this->Form->control('students.' . $key . '._joinData.result', [
                                            'options' => $interviewResult,
                                            'label' => false,
                                            'class' => 'form-control interviewResult result'
                                            ]) ?>
                                    </div>
                                </td>
                                <td class="cell hidden">
                                    <?= $this->Form->control('students.' . $key . '._joinData.description', [
                                        'label' => false, 
                                        'type' => 'textarea',
                                        'class' => 'form-control col-md-7 col-xs-12 interviewDesc description', 
                                        ]) ?>
                                </td>
                                <td class="actions cell">
                                    <?= $this->Html->link(
                                        '<i class="fa fa-2x fa-pencil"></i>', 
                                        'javascript:;',
                                        [
                                            'escape' => false,
                                            'onClick' => "setPassed(this)"
                                        ]) 
                                    ?>
                                    <?= $this->Html->link(
                                        '<i class="fa fa-2x fa-remove" style="font-size: 2.3em;"></i>',
                                        'javascript:;',
                                        [
                                            'escape' => false, 
                                            'onClick' => "deleteCandidate(this, true)"
                                        ]
                                    )?>
                                    <?php $isHidden = $value->_joinData->result != 1 ? " hidden" : "";  ?>
                                    <?= $this->Html->link(
                                        '<i class="fa fa-2x fa-folder"></i>',
                                        'javascript:;',
                                        [
                                            'class' => 'edit-doc' . $isHidden,
                                            'escape' => false,
                                            'onClick' => "editDoc($value->id)"
                                        ])
                                    ?>
                                </td>
                            </tr>
                            <?php $counter++; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

<div id="add-candidate-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content box">
            <div class="overlay hidden" id="add-candidate-modal-overlay">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">THÊM ỨNG VIÊN PHỎNG VẤN</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12 col-xs-12">
                    <?= $this->Form->create(null, [
                        'class' => 'form-horizontal form-label-left', 
                        'id' => 'add-candidate-form', 
                        'data-parsley-validate' => '',
                        'templates' => [
                            'inputContainer' => '{{content}}'
                            ]
                        ]) ?>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <?= $this->Form->control('candidate.name', [
                                    'label' => false, 
                                    'options' => [],
                                    'class' => 'form-control col-md-7 col-xs-12', 
                                    ]) ?>
                                <span class="input-group-btn">
                                    <button type="button" onclick="addCandidate()" class="btn btn-primary btn-flat">
                                        <i class="fa fa-fw fa-plus"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                            <table class="table table-bordered custom-table">
                                <thead>
                                    <tr>
                                        <th scope="col" class="col-md-1"><?= __('STT') ?></th>
                                        <th scope="col" class="col-md-3"><?= __('Họ tên') ?></th>
                                        <th scope="col" class="col-md-2"><?= __('Tuổi') ?></th>
                                        <th scope="col" class="col-md-2"><?= __('Giới tính') ?></th>
                                        <th scope="col" class="col-md-3"><?= __('Số ĐT') ?></th>
                                        <th scope="col" class="actions"></th>
                                    </tr>
                                </thead>
                                <tbody id="recommend-container">
                                </tbody>
                            </table>
                        </div>                                    
                    </div>
                    <?= $this->Form->end() ?>
                </div>
                <div class="col-md-12 col-xs-12">
                    <p class="footer-note"><strong>Lưu ý:</strong> Sau khi hoàn tất, vui lòng nhấn nút "Lưu lại" trên đầu trang để lưu thông tin.</p>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="add-candidate-btn" onclick="selectCandidate()">Hoàn tất</button>
                <button type="button" class="btn btn-default" id="close-modal-btn" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<div id="set-pass-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">KẾT QUẢ PHỎNG VẤN</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12 col-xs-12">
                    <?= $this->Form->create(null, [
                        'class' => 'form-horizontal form-label-left', 
                        'id' => 'set-pass-form', 
                        'data-parsley-validate' => '',
                        'templates' => [
                            'inputContainer' => '{{content}}'
                            ]
                        ]) ?>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="result"><?= __('Kết quả') ?></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <?= $this->Form->control('result', [
                                'options' => $interviewResult, 
                                'required' => true, 
                                'empty' => true,
                                'label' => false, 
                                'data-parsley-errors-container' => '#error-result',
                                'data-parsley-class-handler' => '#select2-result',
                                'class' => 'form-control col-md-7 col-xs-12 select2-theme'
                                ]) ?>
                            <span id="error-result"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12 optional" for="description"><?= __('Ghi chú') ?></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <?= $this->Form->control('description', [
                                'label' => false, 
                                'type' => 'textarea',
                                'class' => 'form-control col-md-7 col-xs-12', 
                                'placeholder' => 'Nhập ghi chú của buổi phỏng vấn'
                                ]) ?>
                        </div>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
                <div class="col-md-12 col-xs-12">
                    <p class="footer-note"><strong>Lưu ý:</strong> Sau khi hoàn tất, vui lòng nhấn nút "Lưu lại" trên đầu trang để lưu thông tin.</p>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="set-interview-result-btn">Hoàn tất</button>
                <button type="button" class="btn btn-default" id="close-result-modal-btn" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script id="selected-candidate-template" type="text/x-handlebars-template">
    {{#each this}}
    <tr class="row-rec" id="row-candidate-{{row}}">
        <td class="cell col-md-1 stt-col">
            {{inc row}}
        </td>
        <td class="cell hidden">
            <?= $this->Form->control('students.{{row}}.id', [
                'type' => 'text',
                'label' => false,
                'class' => 'form-control id',
                'value' => '{{id}}'
                ])?>
        </td>
        <td class="cell col-md-3">
            <a href="javascript:;" onclick="viewCandidate({{id}});">{{fullname}}</a>
        </td>
        <td class="cell col-md-1">
            {{age}}
        </td>
        <td class="cell col-md-1">
            {{trans gender}}
        </td>
        <td class="cell col-md-3">
            {{phoneFormat phone}}
        </td>
        <td class="cell col-md-1">
            <span class="result-text">-</span>
            <div class="hidden">
                <?= $this->Form->control('students.{{row}}._joinData.result', [
                    'options' => $interviewResult,
                    'label' => false,
                    'class' => 'form-control interviewResult result'
                    ]) ?>
            </div>
        </td>
        <td class="cell hidden">
            <?= $this->Form->control('students.{{row}}._joinData.description', [
                'label' => false, 
                'type' => 'textarea',
                'class' => 'form-control col-md-7 col-xs-12 interviewDesc description', 
                ]) ?>
        </td>
        <td class="actions cell">
            <?= $this->Html->link(
                '<i class="fa fa-2x fa-pencil"></i>', 
                'javascript:;',
                [
                    'escape' => false,
                    'onClick' => "setPassed(this)"
                ]) 
            ?>
            <?= $this->Html->link(
                '<i class="fa fa-2x fa-remove" style="font-size: 2.3em;"></i>',
                'javascript:;',
                [
                    'escape' => false, 
                    'onClick' => "deleteCandidate(this)"
                ]
            )?>
            <?= $this->Html->link(
                '<i class="fa fa-2x fa-folder"></i>',
                'javascript:;',
                [
                    'class' => 'edit-doc hidden',
                    'escape' => false,
                    'onClick' => "editDoc({{id}})"
                ])
            ?>
        </td>
    </tr>
    {{/each}}
</script>

<!-- Template for recommend candidate in modal -->
<script id="recommend-candidate-template" type="text/x-handlebars-template">
    {{#each this}}
    <tr class="row-rec" id="row-candidate-{{@index}}">
        <td class="cell col-md-1 stt-col">
            {{inc @index}}
        </td>
        <td class="hidden">
            <?= $this->Form->control('candidateId', [
                'type' => 'text',
                'label' => false,
                'class' => 'form-control',
                'value' => '{{id}}'
                ])?>
        </td>
        <td class="cell col-md-3">
            <a href="javascript:;" onclick="viewCandidate({{id}});">{{fullname}}</a>
            <div class="hidden">
                <?= $this->Form->control('fullname', [
                    'type' => 'text',
                    'label' => false,
                    'class' => 'form-control',
                    'value' => '{{fullname}}'
                    ])?>
            </div>
        </td>
        <td class="cell col-md-2">
            {{age}}
            <div class="hidden">
                <?= $this->Form->control('age', [
                    'type' => 'text',
                    'label' => false,
                    'class' => 'form-control',
                    'value' => '{{age}}'
                    ])?>
            </div>
        </td>
        <td class="cell col-md-2">
            {{trans gender}}
            <div class="hidden">
                <?= $this->Form->control('gender', [
                    'type' => 'text',
                    'label' => false,
                    'class' => 'form-control',
                    'value' => '{{gender}}'
                    ])?>
            </div>
        </td>
        <td class="cell col-md-3">
            {{phoneFormat phone}}
            <div class="hidden">
                <?= $this->Form->control('phone', [
                    'type' => 'text',
                    'label' => false,
                    'class' => 'form-control',
                    'value' => '{{phone}}'
                    ])?>
            </div>
        </td>
        <td>
            <input name="candidate-{{@index}}" id="cdd-{{id}}" type="checkbox" class="js-switch">
        </td>
    </tr>
    {{/each}}
</script>

<script id="add-recommend-candidate-template" type="text/x-handlebars-template">
    <tr class="row-rec" id="row-candidate-{{counter}}">
        <td class="cell col-md-1 stt-col">
            {{row}}
        </td>
        <td class="hidden">
            <?= $this->Form->control('candidateId', [
                'type' => 'text',
                'label' => false,
                'class' => 'form-control',
                'value' => '{{id}}'
                ])?>
        </td>
        <td class="cell col-md-3">
            <a href="javascript:;" onclick="viewCandidate({{id}});">{{fullname}}</a>
            <div class="hidden">
                <?= $this->Form->control('fullname', [
                    'type' => 'text',
                    'label' => false,
                    'class' => 'form-control',
                    'value' => '{{fullname}}'
                    ])?>
            </div>
        </td>
        <td class="cell col-md-1">
            {{age}}
            <div class="hidden">
                <?= $this->Form->control('age', [
                    'type' => 'text',
                    'label' => false,
                    'class' => 'form-control',
                    'value' => '{{age}}'
                    ])?>
            </div>
        </td>
        <td class="cell col-md-2">
            {{trans gender}}
            <div class="hidden">
                <?= $this->Form->control('gender', [
                    'type' => 'text',
                    'label' => false,
                    'class' => 'form-control',
                    'value' => '{{gender}}'
                    ])?>
            </div>
        </td>
        <td class="cell col-md-3">
            {{phoneFormat phone}}
            <div class="hidden">
                <?= $this->Form->control('phone', [
                    'type' => 'text',
                    'label' => false,
                    'class' => 'form-control',
                    'value' => '{{phone}}'
                    ])?>
            </div>
        </td>
        <td>
            <input name="candidate-{{row}}" type="checkbox" id="cdd-{{id}}" class="js-switch">
        </td>
    </tr>
</script>