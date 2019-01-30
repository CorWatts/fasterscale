<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\components\ActiveRecord;
use kartik\grid\GridView;

/**
 * This is the model class for table "custom_behavior".
 *
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class CustomBehavior extends ActiveRecord {
    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public static function tableName() {
        return 'custom_behavior';
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function rules() {
        return [
            [['user_id', 'category_id', 'name'], 'required'],
            [['user_id', 'category_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            ['category_id', 'in', 'range' => array_keys(\common\models\Category::getCategories())],
            [['name'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function behaviors() {
      return [[
          'class' => TimestampBehavior::className(),
          'attributes' => [
            ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
            ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
          ],
          // if you're using datetime instead of UNIX timestamp:
          // 'value' => new Expression('NOW()'),
        ]];
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'category_id' => 'Category',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @codeCoverageIgnore
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(\common\models\User::class, ['id' => 'user_id']);
    }

    public function getGridView() {
      $dataProvider = new \yii\data\ActiveDataProvider([
        'query' => \common\models\CustomBehavior::find()->where(['user_id' => Yii::$app->user->id])->indexBy('id'),
        'pagination' => [
          'pageSize' => 20,
        ],
      ]);
      $gridColumns = [[
        'class'=>'kartik\grid\EditableColumn',
        'attribute'=>'name',
        'editableOptions' => ['formOptions' => ['action' => ['/custom-behavior/update']]],
      ], [
        'class'=>'kartik\grid\EditableColumn',
        'attribute'=>'category_id',
        'editableOptions' => [
          'formOptions' => ['action' => ['/custom-behavior/update']],
          'inputType' => 'dropDownList',
          'data' => \common\models\Category::getCategories(),
          'displayValueConfig' => \common\models\Category::getCategories(),
        ],
      ], [
        'class' => 'kartik\grid\ActionColumn',
        'buttons' => [
          'view' => function ($url, $model) { return ''; },
          'update' => function ($url, $model) { return ''; },
          'delete' => function ($url, $model) {
            return yii\helpers\Html::a('<span class="glyphicon glyphicon-remove"></span>', "/custom-behavior/delete?id={$model->id}", [
              'title' => 'Delete',
              'data-pjax' => true,
              'data-method' => 'post',
              'data-confirm' => 'Are you sure to delete this item?'
            ]);
          }
      ],
      ]];
      $gridView = \kartik\grid\GridView::widget([
        'dataProvider'=>$dataProvider,
        'columns'=>$gridColumns,
        'panel' => false,
        'pjax' => true,
        'layout' => "\n{toolbar}\n{items}\n{summary}\n{pager}",
        'toolbar' => [[
          'content' => '<button type="button" class="btn btn-success btn-sm pull-right add-custom-behavior-btn" aria-label="Add Behavior" title="Add Behavior"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> </button>',
          'options' => ['class' => 'btn-group clearfix', 'style' => 'width: 100%;'],
        ]]
      ]);

      return $gridView;
    }
}
