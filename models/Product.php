<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string|null $shoptet_id
 * @property string|null $name
 * @property string|null $code
 * @property string|null $url
 * @property string|null $image_url
 * @property int|null $stock_quantity
 * @property string|null $description
 * @property float|null $price
 */
class Product extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['shoptet_id', 'name', 'code', 'url', 'image_url', 'stock_quantity', 'description', 'price'], 'default', 'value' => null],
            [['stock_quantity'], 'integer'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['shoptet_id', 'name', 'code', 'url', 'image_url'], 'string', 'max' => 255],
            [['shoptet_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'shoptet_id' => 'Shoptet ID',
            'name' => 'Name',
            'code' => 'Code',
            'url' => 'Url',
            'image_url' => 'Image Url',
            'stock_quantity' => 'Stock Quantity',
            'description' => 'Description',
            'price' => 'Price',
        ];
    }

    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('products_categories', ['product_id' => 'id']);
    }
}