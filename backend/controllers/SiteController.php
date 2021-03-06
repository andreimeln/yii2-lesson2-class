<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Aneks;
use common\models\TwitPost;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create-tweet'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['gettimenow'],
                        'allow' => true,
                        'roles' => ['?'],
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
     * @inheritdoc
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
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

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
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

    public function actionCreateTweet()
    {
        $tweet = new TwitPost();

        $post = Yii::$app->request->post("Twitpost");
        if (count($post)) {
            $tweet->text = $post['text'];
            $tweet->save();
        }
        return $this->render("create-tweet", [
            'tweet' => $tweet
        ]);
    }

    public function actionGettimenow()
    {
        $timenow = new TwitPost();

        $post = Yii::$app->request->post("TwitPost");
        if (count($post))
        {
            $timenow->text = $post['text'];
            $timenow->save();
        }

        return $this->render("gettimenow", [
            'timenow' => $timenow
        ]);
    }
}
