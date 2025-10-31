<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\ShoptetApiHelper;
use app\models\Product;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class ProductsController extends Controller
{
    public function actionOverview(): string
    {
        $products = Product::find()->all();

        return $this->render('overview', ['products' => $products]);
    }

    public function actionView(int $id): string
    {
        $product = Product::findOne($id);

        return $this->render('view', ['product' => $product, 'categories' => $product->getCategories()->all()]);
    }

    public function actionUpdateDescription(int $id): Response
    {
        $product = Product::findOne($id);
        $api = new ShoptetApiHelper();
        $product_detail = $api->getProductDetail($product->shoptet_id);

        $newDesc = 'TEST ' . ($product_detail['description'] ?? '');

        if ($api->updateProductDescription($product->shoptet_id, $newDesc)) {
            Yii::$app->session->setFlash('success', 'Popisek upraven.');
        } else {
            Yii::$app->session->setFlash('error', 'Chyba při aktualizaci.');
        }

        return $this->redirect(['overview']);
    }
}