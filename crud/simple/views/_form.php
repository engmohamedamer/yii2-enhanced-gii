<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \mootensai\enhancedgii\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\builder\Form;
use kartik\select2\Select2;
use kartik\widgets\ActiveForm;
<?php
// @TODO : use namespace of foreign keys & widgets
?>

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form kartik\widgets\ActiveForm */

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
$form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'action' => $model->isNewRecord ? Url::to(['create']) : Url::to(['update', 'id' => $model->id]),
    'formConfig' => [
        'labelSpan' => 4
    ],
    'options' => [
        'id' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form'
    ]
]);

echo $form->errorSummary($model);
<?php echo 'echo '.  $generator->generateActiveField($pk, $generator->generateFK()); ?>
echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 2,
    'attributes' => [
<?php foreach ($generator->tableSchema->getColumnNames() as $attribute) {
    if ($attribute !== $pk) {
        if (!in_array($attribute, $generator->skippedColumns)) {
            echo $generator->generateActiveFormField($attribute, $generator->generateFK()) . ",\n";
        }
    }
} ?>
    ],
]);

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
?>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Update') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
<?php if ($generator->cancelable): ?>
        <?= "<?= " ?>Html::a(Yii::t('app', 'Cancel'),['index'],['class'=> 'btn btn-danger', 'data-dismiss' => 'modal']) ?>
<?php endif; ?>
    </div>
</div>
<?= "<?php " ?>ActiveForm::end(); ?>
<?= "<?php " ?>$this->render('/layouts/flash'); ?>