<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string|null $shoptet_id
 * @property string|null $name
 */
class Category extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['shoptet_id', 'name'], 'default', 'value' => null],
            [['shoptet_id', 'name'], 'string', 'max' => 255],
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
        ];
    }

}