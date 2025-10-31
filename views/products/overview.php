<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Shoptet - Produkty';
?>

<div class="container mt-4">
    <h2>Produkty</h2>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Obrázek</th>
            <th>Název</th>
            <th>Kód</th>
            <th>URL</th>
            <th>Počet ks</th>
            <th>Akce</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><img src="<?= Html::encode('https://cdn.myshoptet.com/usr/451754.myshoptet.com/user/shop/big/' . $p->image_url) ?>" width="60"></td>
                <td><?= Html::encode($p->name) ?></td>
                <td><?= Html::encode($p->code) ?></td>
                <td><a href="<?= Html::encode($p->url) ?>" target="_blank">Odkaz</a></td>
                <td><?= Html::encode($p->stock_quantity) ?></td>
                <td>
                    <?= Html::a('Detail', ['products/view', 'id' => $p->id], ['class' => 'btn btn-sm btn-info']) ?>
                    <?= Html::a('Upravit popis', ['products/update-description', 'id' => $p->id], [
                        'class' => 'btn btn-sm btn-warning',
                        'data-confirm' => 'Přidat prefix TEST k popisu?'
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
