<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 01.03.17
 * Time: 11:14
 */

namespace andrew72ru\seotag\controllers;


use common\models\Blog;
use common\modules\pages\models\StaticPage;
use common\modules\seotag\models\Seotag;
use common\modules\seotag\models\SeotagSearch;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Основной контроллер для управления мета-тэгами
 *
 * Class MainController
 * @package common\modules\seotag\controllers
 */
class MainController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post']
                ]
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SeotagSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @param null|string $url
     * @return array|string|Response
     */
    public function actionCreate($url = null)
    {
        $model = new Seotag(['url' => $url]);

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(['view', 'id' => $model->id]);

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return array|string|Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(['view', 'id' => $model->id]);

        return $this->render('update', [
            'model' => $model
        ]);

    }

    /**
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

    /**
     * Функция для поиска страниц и записей блога
     *
     * @param null $q
     * @return array
     */
    public function actionPagesList($q = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = [];
        $pages = StaticPage::find()
            ->limit(50)->asArray();
        if(!is_null($q))
        {
            $pages->andwhere(['like', 'slug', $q]);
            $pages->orWhere(['like', 'title', $q]);

        }

        foreach ($pages->all() as $page)
        {
            $out[] = [
                'url' => $page['slug'],
                'name' => $page['title'],
                'value' => $page['title'] . ' (' . $page['slug'] . ')',
                'route' => Yii::$app->urlManagerFrontend->createAbsoluteUrl([$page['slug']]),
            ];
        }

        $blog = Blog::find()
            ->limit(50)->asArray();

        if(!is_null($q))
        {
            $blog->andFilterWhere(['like', 'slug', $q]);
            $blog->orFilterWhere(['like', 'title', $q]);
        }

        foreach ($blog->all() as $blogItem)
        {
            $out[] = [
                'url' => '/blog/' . $blogItem['slug'],
                'name' => $blogItem['title'],
                'value' => $blogItem['title'] . ' (/blog/' . $blogItem['slug'] . ')',
                'route' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/blog/' . $blogItem['slug']]),
            ];
        }

        return $out;
    }

    /**
     * @param null $q
     * @param null $id
     * @return array
     */
    public function actionKeywordsList($q = null, $id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q))
        {
            $query = (new Query())
                ->from('{{%seotag_keywords}}')
                ->select(['word'])
                ->where(['like', 'word', $q])
                ->distinct()
                ->limit(20);
            $out['results'] = array_values($query->all());
        } elseif ($id > 0)
            $out['results'] = ['id' => $id, 'word' => $id];

        return $out;
    }

    /**
     * @return array|null
     */
    public function actionLoadExistData()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        if(array_key_exists('route', $post))
            return $this->getMetadataFromUrl($post['route']);

        if(Yii::$app->request->post('simple_url', null) !== null)
        {
            $route = Yii::$app->urlManagerFrontend->createAbsoluteUrl(Yii::$app->request->post('simple_url'));
            if(Seotag::checkUrlExists($route))
                return $this->getMetadataFromUrl($route);
        }

        return [
            'description' => null,
            'inputKeywords' => [],
            'images' => [],
        ];
    }

    /**
     * @param $url
     * @return array
     */
    private function getMetadataFromUrl($url)
    {
        $currentTags = get_meta_tags($url);

        $result = [
            'description' => array_key_exists('description', $currentTags) ? $currentTags['description'] : null,
            'inputKeywords' => array_key_exists('keywords', $currentTags) ? explode(',', $currentTags['keywords']) : [],
            'images' => $this->fetchImages($url),
        ];
        return $result;
    }

    /**
     * @param $url
     *
     * @return array
     */
    public function actionFetchImages($url)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->fetchImages($url);
    }

    /**
     * Забирает массив картинок из страницы
     *
     * @param $url
     * @return array
     */
    private function fetchImages($url)
    {
        $result = [];
        $srcs = Seotag::getImages($url);
        foreach ($srcs as $src)
        {
            $result[] = $this->renderAjax('_image_selector', ['src' => $src]);
        }

        return $result;
    }

    /**
     * @param $id
     * @return Response
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return Seotag
     * @throws NotFoundHttpException
     */
    private function findModel($id)
    {
        if(($model = Seotag::findOne($id)) === null)
            throw new NotFoundHttpException(Yii::t('app.seotag', 'Seo tag not found'));
        return $model;
    }
}