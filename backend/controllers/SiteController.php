<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\AppleNew;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'down', 'eat'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     *
     * @return string
     */
    public function actionDown()
    {
        if (!empty(Yii::$app->request->get()['id'])) {
            $oApple = AppleNew::find()
                ->where(['id' => Yii::$app->request->get()['id']])
                ->one();

            $oApple->fallToGround();
        }
        return $this->redirect(['index']);
    }

    /**
     *
     * @return string
     */
    public function actionEat()
    {
        if (!empty(Yii::$app->request->post()['cnt'])) {
            $oApple = AppleNew::find()
                ->where(['id' => Yii::$app->request->get()['id']])
                ->one();

            $oApple->eat(Yii::$app->request->post()['cnt']);
        }
        return $this->redirect(['index']);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!empty(Yii::$app->request->post()['cnt'])) {
            (new AppleNew())->addNewApples(Yii::$app->request->post()['cnt']);
        }

        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
