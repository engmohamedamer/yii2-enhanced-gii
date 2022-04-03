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
            'id' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form'
        ]
    ]); ?>
    <div class="card">
        <div class="card-body">

    <?= "<?= " ?>$form->errorSummary($model); ?>

<?php
$i=0;
foreach ($generator->tableSchema->getColumnNames() as $attribute) {
    if (!in_array($attribute, $generator->skippedColumns)) {

        if ($i % 2 != 0) { echo "<div class='row'>" ;}
            echo " <div class=\"col-md-4\">   <?= " . $generator->generateActiveField($attribute, $generator->generateFK()) . " ?> </div>\n\n";
        if ($i % 2 != 0) { echo  "</dev>" ; }

        $i++;
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
        <div class="card-footer">
        <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Update') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
<?php if ($generator->cancelable): ?>
        <?= "<?= " ?>Html::a(Yii::t('app', 'Cancel'),['index'],['class'=> 'btn btn-danger', 'data-dismiss' => 'modal']) ?>
<?php endif; ?>
    </div>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>