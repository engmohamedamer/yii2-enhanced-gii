<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator mootensai\enhancedgii\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->nsModel ?>\base;

use Yii;
use <?= ltrim($generator->baseModelClass, '\\'); ?>;
<?php if ($queryClassName) { ?>
use <?php echo ltrim($queryClassFullName = '\\' . $generator->queryNs . '\\' . $queryClassName,'\\'); ?>;
<?php } ?>
use mootensai\relation\RelationTrait;
<?php if ($generator->createdAt || $generator->updatedAt): ?>
use yii\behaviors\TimestampBehavior;
<?php if (!empty($generator->timestampValue) && $generator->timestampValue != 'time()'):?>
use <?php echo ltrim($generator->getClassFromString($generator->timestampValue), '\\'); ?>;
<?php endif; ?>
<?php endif; ?>
<?php if ($generator->createdBy || $generator->updatedBy): ?>
use yii\behaviors\BlameableBehavior;
<?php endif; ?>
<?php if ($generator->UUIDColumn): ?>
use mootensai\behaviors\UUIDBehavior;
<?php endif; ?>

/**
 * This is the base model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= '\\' . $generator->nsModel . '\\' . $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= $generator->getBaseClassName($generator->baseModelClass) . "\n" ?>
{

    use RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
    return [<?= "\n            '" . implode("',\n            '", array_keys($relations)) . "'\n        " ?>];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . "\n        " ?>];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>
<?php if (!empty($generator->optimisticLock)): ?>

    /**
     * 
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock 
     * 
     */
    public function optimisticLock() {
        return '<?= $generator->optimisticLock ?>';
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
<?php if (!in_array($name, $generator->skippedColumns)): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endif; ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= ucfirst($name) ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($generator->createdAt || $generator->updatedAt
        || $generator->createdBy || $generator->updatedBy
        || $generator->UUIDColumn): 
    echo "\n"; ?>/**
     * @inheritdoc
     * @return array
     */ 
    public function behaviors()
    {
        return [
<?php if ($generator->createdAt || $generator->updatedAt):?>
            [
                'class' => TimestampBehavior::class,
<?php if (!empty($generator->createdAt)):?>
                'createdAtAttribute' => '<?= $generator->createdAt?>',
<?php else :?>
                'createdAtAttribute' => false,
<?php endif; ?>
<?php if (!empty($generator->updatedAt)):?>
                'updatedAtAttribute' => '<?= $generator->updatedAt?>',
<?php else :?>
                'updatedAtAttribute' => false,
<?php endif; ?>
<?php if (!empty($generator->timestampValue) && $generator->timestampValue != 'time()'):?>
                'value' => <?= $generator->simplifyFNQ($generator->timestampValue)?>,
<?php endif; ?>
            ],
<?php endif; ?>
<?php if ($generator->createdBy || $generator->updatedBy):?>
            [
                'class' => BlameableBehavior::class,
<?php if (!empty($generator->createdBy)):?>
                'createdByAttribute' => '<?= $generator->createdBy?>',
<?php else :?>
                'createdByAttribute' => false,
<?php endif; ?>
<?php if (!empty($generator->updatedBy)):?>
                'updatedByAttribute' => '<?= $generator->updatedBy?>',
<?php else :?>
                'updatedByAttribute' => false,
<?php endif; ?>
<?php if (!empty($generator->blameableValue) && $generator->blameableValue != '\\Yii::$app->user->id'):?>
                'value' => <?= $generator->blameableValue?>,
<?php endif; ?>
            ],
<?php endif; ?>
<?php if ($generator->UUIDColumn):?>
            [
                'class' => UUIDBehavior::class,
<?php if (!empty($generator->UUIDColumn)):?>
                'column' => '<?= $generator->UUIDColumn?>',
<?php endif; ?>
            ],
<?php endif; ?>
        ];
    }
<?php endif; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * @inheritdoc
     * @return <?= $queryClassName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassName ?>(get_called_class());
    }
<?php endif; ?>
}
