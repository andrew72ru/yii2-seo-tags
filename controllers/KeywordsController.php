<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 07.03.17
 * Time: 15:03
 */

namespace andrew72ru\seotag\controllers;

use andrew72ru\seotag\models\SeotagKeywords;
use andrew72ru\seotag\models\SeotagKeywordsSearch;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Управление ключевыми словами
 *
 * Class KeywordsController
 * @package common\modules\seotag\controllers
 */
class KeywordsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post']
                ]
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SeotagKeywordsSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->load(\Yii::$app->request->post()) && $model->save())
            return $this->redirect('index');

        if(\Yii::$app->request->isAjax)
            return $this->renderAjax('update', ['model' => $model]);

        return $this->render('update', ['model' => $model]);
    }

    /**
     * @param integer $id
     * @return SeotagKeywords
     * @throws NotFoundHttpException
     */
    private function findModel($id)
    {
        $model = SeotagKeywords::findOne($id);

        if($model === null)
            throw new NotFoundHttpException(\Yii::t('app.seotag', 'Keyword not found'));

        return $model;
    }
}