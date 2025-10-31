<?php

declare(strict_types=1);

namespace app\commands;

use app\components\ShoptetApiHelper;
use app\models\Category;
use app\models\Product;
use app\models\ProductCategory;
use yii\console\Controller;

class SyncShoptetProductsController extends Controller
{
    private const LOCK_FILE = '@runtime/sync_shoptet_products_to_db.lock';

    public function actionRun()
    {
        $lockFilePath = \Yii::getAlias(self::LOCK_FILE);

        if (file_exists($lockFilePath)) {
            $lockAge = time() - filemtime($lockFilePath);
            if ($lockAge < 5400) {
                echo "[" . date('Y-m-d H:i:s') . "] Sync already running or locked.\n";
                return;
            } else {
                echo "[" . date('Y-m-d H:i:s') . "] Lock file is old, removing stale lock.\n";
                unlink($lockFilePath);
            }
        }

        file_put_contents($lockFilePath, getmypid());

        try {
            $api = new ShoptetApiHelper();
            $page = 1;

            while (true) {
                try {
                    $products = $api->getProducts($page);
                } catch (\Exception $e) {
                    break;
                }

                foreach ($products as $p) {
                    $product = Product::findOne(['shoptet_id' => $p['guid']]) ?? new Product();
                    usleep(750000); // rate limiter - 750 * 10^-6s = 0,75s
                    $product_detail = $api->getProductDetail($p['guid']);

                    $product->shoptet_id = $product_detail['guid'];
                    $product->name = $product_detail['name'];
                    $product->code = $product_detail['variants'][0]['code'];
                    $product->url = $product_detail['url'];
                    $product->image_url = $p['mainImage']['cdnName'] ?? null;
                    $product->stock_quantity = $product_detail['variants'][0]['stock'] . ' ' . $product_detail['variants'][0]['unit'];
                    $product->price = $product_detail['variants'][0]['price'];
                    $product->save(false);

                    foreach ($product_detail['categories'] as $c) {
                        $cat = Category::findOne(['shoptet_id' => $c['guid']]) ?? new Category();
                        $cat->shoptet_id = $c['guid'];
                        $cat->name = $c['name'];
                        $cat->save(false);

                        if (!ProductCategory::findOne(['product_id' => $product->id, 'category_id' => $cat->id])) {
                            (new ProductCategory([
                                'product_id' => $product->id,
                                'category_id' => $cat->id
                            ]))->save(false);
                        }
                    }
                }

                echo "[" . date('Y-m-d H:i:s') . "] Synced page $page\n";
                $page++;
                sleep(2); // rate limiter - 2s
            }

            echo "[" . date('Y-m-d H:i:s') . "] Sync complete.\n";
        } finally {
            if (file_exists($lockFilePath)) {
                unlink($lockFilePath);
            }
        }

        echo "Sync complete.\n";
    }

    public function actionCron()
    {
        echo "[" . date('Y-m-d H:i:s') . "] Starting cron sync...\n";
        $this->actionRun();
        echo "[" . date('Y-m-d H:i:s') . "] Cron sync finished.\n";
    }
}

