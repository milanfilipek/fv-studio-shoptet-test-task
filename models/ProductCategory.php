<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "products_categories".
 *
 * @property int|null $product_id
 * @property int|null $category_id
 */
class ProductCategory extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'products_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['product_id', 'category_id'], 'default', 'value' => null],
            [['product_id', 'category_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'product_id' => 'Product ID',
            'category_id' => 'Category ID',
        ];
    }

}