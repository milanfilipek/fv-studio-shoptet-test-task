<?php

use yii\helpers\Html;

$this->title = 'Shoptet - Detail produktu ' . $product->name;
?>

<div class="container mt-4">
    <h3> <?= Html::encode($product->name) ?> </h3>
    <p><strong>Kód:</strong> <?= Html::encode($product->code) ?></p>
    <p><strong>Cena:</strong> <?= Html::encode($product->price ?? 'N/A') ?> Kč</p>
    <p><strong>Kategorie:</strong></p>
    <ul>
        <?php foreach (($categories ?? []) as $cat): ?>
            <li><?= Html::encode($cat->shoptet_id . ' - ' . $cat->name) ?></li>
        <?php endforeach; ?>
    </ul>
    <p><a href="<?= Html::encode($product->url) ?>" target="_blank" class="btn btn-secondary">Zobrazit na e-shopu</a></p>

</div>
