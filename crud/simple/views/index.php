<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \mootensai\enhancedgii\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$tableSchema = $generator->getTableSchema();
$baseModelClass = StringHelper::basename($generator->modelClass);
echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "kartik\\grid\\GridView;" : "yii\\widgets\\ListView;" ?>


/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= ($generator->pluralize) ? $generator->generateString(Inflector::pluralize(Inflector::camel2words($baseModelClass))) : $generator->generateString(Inflector::camel2words($baseModelClass)) ?>;
$this->params['breadcrumbs'][] = $this->title;
$gridColumn = [
    ['class' => 'yii\grid\SerialColumn'],
<?php
if ($generator->expandable):
    ?>
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('_expand', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => true
    ],
<?php
endif;
?>
<?php
if (($tableSchema = $generator->getTableSchema()) === false) :
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
    echo "'" . $name . "',\n";
        } else {
    echo "// '" . $name . "',\n";
        }
    }
else :
    foreach ($tableSchema->getColumnNames() as $attribute):
        if (!in_array($attribute, $generator->skippedColumns)) :
            ?>
    <?= $generator->generateGridViewFieldIndex($attribute, $generator->generateFK($tableSchema), $tableSchema)?>
<?php
endif;
endforeach; ?>
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{update} {delete}'
    ],
];
<?php
endif;
?>
<?php
if ($generator->indexWidgetType === 'grid'):
?>

/** @noinspection PhpUnhandledExceptionInspection */
echo GridView::widget([
    'dataProvider' => $dataProvider,
    <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n    'columns' => \$gridColumn,\n" : "'columns' => \$gridColumn,\n"; ?>
    'panel' => [
        'type' => 'dark',
        'heading' => $this->title,
        'before' => Html::a(Yii::t('app', <?= $generator->generateString('Create ' . Inflector::camel2words($baseModelClass)) ?>), ['create'], ['class' => 'to-modal btn btn-success']),
    ],
]);
<?php
else:
?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]);
?>
<?php
endif;
?>
