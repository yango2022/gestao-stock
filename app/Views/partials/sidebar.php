<?php
    use App\Config\Menu;

    $user = auth()->user();
    $menuItems = Menu::items($user);
?>

<div class="sidebar">
    <h4 class="text-center mb-4">Painel Admin</h4>


    <?php foreach ($menuItems as $item): ?>

    <?php if (!empty($item['submenu'])): ?>

        <div class="submenu">
            <div class="submenu-title">
                <i class="<?= $item['icon'] ?>"></i> <?= $item['label'] ?>
            </div>

            <div class="submenu-items">
                <?php foreach ($item['submenu'] as $sub): ?>
                    <a href="/<?= $sub['url'] ?>"><?= $sub['label'] ?></a>
                <?php endforeach ?>
            </div>
        </div>

    <?php else: ?>

        <a href="/<?= $item['url'] ?>">
            <i class="<?= $item['icon'] ?>"></i> <?= $item['label'] ?>
        </a>

    <?php endif ?>

<?php endforeach ?>
</div>


