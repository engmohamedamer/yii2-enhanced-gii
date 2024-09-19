<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $generator \mootensai\enhancedgii\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$tableSchema = $generator->getTableSchema();
$baseModelClass = StringHelper::basename($generator->modelClass);
echo "<?php\n";
?>

use yii\helpers\Html;
use kartik\export\ExportMenu;
use <?= $generator->indexWidgetType === 'grid' ? "kartik\\grid\\GridView;" : "yii\\widgets\\ListView;" ?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */
$hasFilters = ! empty($_GET);
$this->title = <?= ($generator->pluralize) ? $generator->generateString(Inflector::pluralize(Inflector::camel2words($baseModelClass))) : $generator->generateString(Inflector::camel2words($baseModelClass)) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="d-flex align-items-center flex-wrap section_header justify-content-between gap-3">
    <div class="section_header_right">
        <h4 class="mb-0">
            <?= "<?= " ?> $this->title ?>
        </h4>
    </div>
    <div class="mb-0 d-inline-flex gap-2">
        <a class="btn filter_toggler <?= "<?= " ?> $hasFilters ? '' : 'collapsed' ?>" data-toggle="collapse" href="#collapseFilters" role="button" aria-expanded="false" aria-controls="collapseExample">
            <span class="isax icon isax-filter-remove"></span>
        </a>
        <?= "<?= " ?> Html::a(
            Html::tag('i', '', ['class' => 'isax isax-add']) . ' ' . Yii::t('common', 'Create  <?= Inflector::camel2words($baseModelClass) ?>' ) ,
            ['create'],
            ['class' => 'btn btn-secondary']
        ) ?>
    </div>
</div>




<div class="py-3">

   <!-- Filters Toolbar -->
    <div id="collapseFilters" class="collapse <?= "<?= " ?> $hasFilters ? 'show' : '' ?>">
        <div class="section_toolbar">
            <?= "<?= " ?> $this->render('_search', ['model' => $searchModel ,'hasFilters'=>$hasFilters]); ?>
        </div>
    </div>
    <!-- End Filters Toolbar -->

    
<div>
    
<?php 
if ($generator->indexWidgetType === 'grid'): 
?>
    <?= "<?php \n" ?>
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
                echo "            '" . $name . "',\n";
            } else {
                echo "            // '" . $name . "',\n";
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
            "width"=>"20%",
            "template"=>'{update} {view} '//{delete}
        ],
    ]; 
<?php 
    endif; 
?>
    ?>
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'options' => ['class' => 'gridview table-responsive'],
        'tableOptions' => ['class' => 'table text-nowrap mb-0'],
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-<?= Inflector::camel2id(StringHelper::basename($generator->modelClass))?>']],
        'panel' => [
            'type' => GridView::TYPE_LIGHT,
            'heading' => false ,
        ],
        // set a label for default menu
        'export' => [
            'label' => 'Page',
            'fontAwesome' => true,
            'options' => ['class' => false],
        ],
        // your toolbar can include the additional full export menu
        'toolbar' => [
            '{export}',
            ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumn,
                'target' => ExportMenu::TARGET_BLANK,
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => 'Full',
                    'class' => 'btn btn-default',
                    'itemsBefore' => [
                        '<li class="dropdown-header">Export All Data</li>',
                    ],
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_PDF => false,
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_EXCEL => false,
                ]
            ]) ,
        ],
        'exportConfig' => [
            GridView::CSV => ['label' => 'Save as CSV'],
            GridView::EXCEL => [ ],

        ],
    ]); ?>
<?php 
else: 
?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php 
endif; 
?>

</div>
</div>
