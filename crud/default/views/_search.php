<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator mootensai\enhancedgii\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="w-100 form-academy-sport-search">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'action' => ['index'],
        'options' => [
                    'class' => 'd-flex  align-items-center justify-content-between flex-wrap gap-3', //needs-validation
                    'novalidate' => 'novalidate'
        ],
        'method' => 'get',
    ]); ?>

    <div class="flex-fill">
        <div class="d-flex gap-2 flex-wrap">
<?php
$count = 0;
foreach ($generator->getColumnNames() as $attribute) {
    if (!in_array($attribute, $generator->skippedColumns)) {
        if (++$count < 6) {
            echo "    <?= " . $generator->generateActiveSearchField($attribute, $generator->generateFK()) . " ?>\n\n";
        } else {
            echo "    <?php /* echo " . $generator->generateActiveSearchField($attribute, $generator->generateFK()) . " */ ?>\n\n";
        }
    }
}
?>
        </div>
    </div>
    <div class="form-group">
        <?= "<?= " ?> Html::submitButton(
            '<span class="isax mr-2 isax-search-normal-1"></span><span>' . Yii::t('common', 'Search') . '</span>',
            ['class' => 'btn btn-info rounded-pill']
        ) ?>

        <?= "<?= " ?>  Html::resetButton(
            '<span class="isax isax-close-circle mr-2"></span><span>' . yii::t('common', 'Reset') . '</span>',
            [
                'class' => 'btn btn-link text-body rounded-pill',
                'onclick' => 'window.location.href = window.location.pathname;'
            ]
        ) ?>

    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
