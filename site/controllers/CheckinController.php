<?php

namespace site\controllers;

use Yii;
use common\models\Category;
use common\models\Option;
use common\models\UserOption;
use site\models\CheckinForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\helpers\VarDumper;

class CheckinController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view'],
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $form = new CheckinForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $options = array_merge((array)$form->options1, (array)$form->options2, (array)$form->options3, (array)$form->options4, (array)$form->options5, (array)$form->options6, (array)$form->options7);
            $options = array_filter($options);

            // delete the old data, we only store one data set per day
            if(sizeof($options) > 0)
                UserOption::deleteAll('user_id=:user_id AND date(date)=:date', [':user_id' => Yii::$app->user->id, ':date' => date('Y-m-d H:i:s')]);

            foreach($options as $option_id) {
                $user_option = new UserOption;
                $user_option->option_id = $option_id;
                $user_option->user_id = Yii::$app->user->id;
                $user_option->date = date('Y-m-d H:i:s');
                $user_option->save();
            }
            Yii::$app->session->setFlash('success', 'Your emotions have been logged!');
            return $this->redirect('checkin/view', 200);
        } else {
            print_r($form->getErrors());
            $categories = Category::find()->asArray()->all();
            $options = Option::find()->asArray()->all();
            $optionsList = \yii\helpers\ArrayHelper::map($options, "id", "name", "category_id");
            return $this->render('index', ['categories' => $categories, 'model' => $form, 'optionsList' => $optionsList]);
        }
    }

    public function actionView($date = null)
    {
        if(is_null($date))
            $date = date("Y-m-d");

        $form = new CheckinForm();

        $categories = Category::find()->asArray()->all();

        $options = Option::find()->asArray()->all();
        $optionsList = \yii\helpers\ArrayHelper::map($options, "id", "name", "category_id");

        $user_options = UserOption::find()->where(["user_id" => Yii::$app->user->id, 'date(date)' => $date])->with('option')->asArray()->all();

        $score = 0;

        if($user_options) {
            foreach($user_options as $option) {
                $user_options_by_category[$option['option']['category_id']][] = $option['option_id'];
                $attribute = "options".$option['option']['category_id'];
                $form->{$attribute}[] = $option['option_id'];
            }


            $query = new Query;
            $query->select("c.id as category_id, c.name as name, c.weight as weight, COUNT(o.id) as option_count")
                ->from('category c')
                ->join("INNER JOIN", "option o", "o.category_id = c.id")
                ->groupBy('c.id, c.name, c.weight')
                ->orderBy('c.id')
                ->indexBy('category_id');
            $category_options = $query->all();

            /*
            $query = new Query;
            $query->select("o.category_id as category_id, COUNT(l.id) as option_count")
                ->from('user_option_link l')
                ->join("INNER JOIN", "option o", "o.id = l.option_id")
                ->groupBy('o.category_id')
                ->orderBy('o.category_id')
                ->where('l.date::date=:date', [":date" => date("Y-m-d")])
                ->indexBy('category_id');
            $user_options = $query->all();
             */

            $query = new Query;
            $query->params = [":user_id" => Yii::$app->user->id];
            $query->select("o.id as id, o.name as name, COUNT(o.id) as count")
                ->from('user_option_link l')
                ->join("INNER JOIN", "option o", "l.option_id = o.id")
                ->groupBy('o.id, l.user_id')
                ->having('l.user_id = :user_id')
                ->orderBy('count DESC')
                ->limit(5);
            $user_rows = $query->all();


            foreach($category_options as $options) {
                if(array_key_exists($options['category_id'], $user_options_by_category))
                    $stats[$options['category_id']] = $category_options[$options['category_id']]['weight'] * (count($user_options_by_category[$options['category_id']]) / $options['option_count']);
                else
                    $stats[$options['category_id']] = 0;
            }

            $sum = 0;
            $count = 0;
            foreach($stats as $stat) {
                $sum += $stat;

                if($stat > 0)
                    $count += 1;
            }

            $avg = ($count > 0) ? $sum / $count : 0;

            $score = round($avg * 100);
        }

        return $this->render('view', ['model' => $form, 'categories' => $categories, 'optionsList' => $optionsList, 'date' => $date, 'score' => $score]);
    }

    public function actionReport() {
        $query = new Query;
        $query->params = [":user_id" => Yii::$app->user->id];
        $query->select("o.id as id, o.name as name, COUNT(o.id) as count")
            ->from('user_option_link l')
            ->join("INNER JOIN", "option o", "l.option_id = o.id")
            ->groupBy('o.id, l.user_id')
            ->having('l.user_id = :user_id')
            ->orderBy('count DESC')
            ->limit(5);
        $user_rows = $query->all();

        $query2 = new Query;
        $query2->params = [":user_id" => Yii::$app->user->id];
        $query2->select("c.name as name, COUNT(o.id) as count")
            ->from('user_option_link l')
            ->join("INNER JOIN", "option o", "l.option_id = o.id")
            ->join("INNER JOIN", "category c", "o.category_id = c.id")
            ->groupBy('c.id, c.name, l.user_id')
            ->having('l.user_id = :user_id');
        $answer_pie = $query2->all();
        $pie_colors = [
            [
                "color" => "#277553",
                "highlight" => "#499272"
            ],
            [
                "color" => "#29506D",
                "highlight" => "#496D89"
            ],
            [
                "color" => "AA5939",
                "highlight" => "D4886A"
            ],
            [
                "color" => "#AA7939",
                "highlight" => "#D4A76A"
            ],
            [
                "color" => "#277553",
                "highlight" => "#499272"
            ],
            [
                "color" => "#29506D",
                "highlight" => "#496D89"
            ],
            [
                "color" => "AA5939",
                "highlight" => "D4886A"
            ]
        ];


        $pie_data = [];
        foreach($answer_pie as $key => $category) {
            $json = [
                "value" => (int)$category['count'],
                "color" => $pie_colors[$key]["color"],
                "highlight" => $pie_colors[$key]["highlight"],
                "label" => $category['name']
            ];
            $pie_data[] = $json;
        }


        return $this->render('report', ['top_options' => $user_rows, 'pie_chart' => json_encode($pie_data)]);
    }
}
