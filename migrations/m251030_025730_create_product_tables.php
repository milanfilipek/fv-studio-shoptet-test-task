<?php

use yii\db\Migration;

class m251030_025730_create_product_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'shoptet_id' => $this->string()->unique(),
            'name' => $this->string(),
            'code' => $this->string(),
            'url' => $this->string(),
            'image_url' => $this->string(),
            'stock_quantity' => $this->integer(),
            'description' => $this->text(),
            'price' => $this->double(2),
        ]);

        $this->createTable('{{%categories}}', [
            'id' => $this->primaryKey(),
            'shoptet_id' => $this->string()->unique(),
            'name' => $this->string(),
        ]);

        $this->createTable('{{%products_categories}}', [
            'product_id' => $this->integer(),
            'category_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%products_categories}}');
        $this->dropTable('{{%categories}}');
        $this->dropTable('{{%products}}');
    }
}
