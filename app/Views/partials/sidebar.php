<?php
    use App\Config\Menu;

    $user = auth()->user();
    $menuItems = Menu::items($user);
?>

<div class="sidebar">
    <h4 class="text-center mb-4">Painel Admin</h4>

    <?php foreach ($menuItems as $item): ?>
        <a href="/<?= $item['url'] ?>" class="<?= isActive($item['url']) ?>">
            <i class="<?= $item['icon'] ?>"></i> <?= $item['label'] ?>
        </a>
    <?php endforeach; ?>
</div>