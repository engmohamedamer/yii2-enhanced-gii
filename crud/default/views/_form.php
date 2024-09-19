<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \mootensai\enhancedgii\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
<?php
// @TODO : use namespace of foreign keys & widgets
?>

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */

<?php
$pk = empty($generator->tableSchema->primaryKey) ? $generator->tableSchema->getColumnNames()[0] : $generator->tableSchema->primaryKey[0];
$modelClass = StringHelper::basename($generator->modelClass);
foreach ($relations as $name => $rel) {
    $relID = Inflector::camel2id($rel[1]);
    if ($rel[2] && isset($rel[3]) && !in_array($name, $generator->skippedRelations)) {
        echo "\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, \n"
                . "    'viewParams' => [\n"
                . "        'class' => '$rel[1]', \n"
                . "        'relID' => '$relID', \n"
                . "        'value' => \yii\helpers\Json::encode(\$model->$name),\n"
                . "        'isNewRecord' => (\$model->isNewRecord) ? 1 : 0\n"
                . "    ]\n"
                . "]);\n";
    }
}
?>
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'action' => $model->isNewRecord ? Url::to(['create']) : Url::to(['update', 'id' => $model->id]),
        'options' => [
            'id' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form',
            'data-start_spinner' => 'true',
            'novalidate' => 'novalidate' // Disable default HTML5 validation
        ]
    ]); ?>


    <div class="d-flex align-items-center flex-wrap section_header justify-content-between gap-3">
        <div class="section_header_right">
            <span class="section_header_icon">
                <span class="isax isax-location-add"></span>
            </span>
            <h4 class="mb-0">
                <?= "<?= " ?> $this->title ?>
            </h4>
        </div>
        <div class="mb-0 d-inline-flex align-items-center gap-2">
            <?= "<?= " ?>  Html::submitButton($model->isNewRecord ? '<span class="isax isax-add"></span> ' . Yii::t('backend', 'Create') : '<span class="isax isax-edit"></span> ' .  Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
        </div>
    </div>



    <div class="card">
        <div class="card-body">
        <div class="row">
    <?= "<?= " ?>$form->errorSummary($model); ?>

<?php
foreach ($generator->tableSchema->getColumnNames() as $attribute) {
    if (!in_array($attribute, $generator->skippedColumns)) {

            echo " <div class=\"col-md-4\">   <?= " . $generator->generateActiveField($attribute, $generator->generateFK()) . " ?> </div>\n\n";

    }
} ?>



        </div>
<?php
foreach ($relations as $name => $rel) {
    $relID = Inflector::camel2id($rel[1]);
    if ($rel[2] && isset($rel[3]) && !in_array($name, $generator->skippedRelations)) {
        echo "    <div class=\"form-group\" id=\"add-$relID\">\n"
            . "        <?= \$this->render('_form".$rel[1]."', ['row'=>\yii\helpers\ArrayHelper::toArray(\$model->$name)]); ?>\n"
            . "    </div>\n\n";
    }
}
?>

        </div>

        <div class="card-footer">
        <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Update') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
<?php if ($generator->cancelable): ?>
        <?= "<?= " ?>Html::a(Yii::t('app', 'Cancel'),['index'],['class'=> 'btn btn-danger', 'data-dismiss' => 'modal']) ?>
<?php endif; ?>
         </div>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>