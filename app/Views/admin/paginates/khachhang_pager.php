<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
$baseUrl = ''; // base_url(route_to('khachhang'));
?>

<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
    <ul class="pagination">

        <!-- nút Previous -->
        <?php if ($pager->hasPrevious()) : ?>
            <li>
                <a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>">
                    <span aria-hidden="true"><?= lang('Pager.first') ?></span>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>">
                    <span aria-hidden="true"><?= lang('Pager.previous') ?></span>
                </a>
            </li>
        <?php endif ?>

        <!-- Nút Pages -->
        <?php foreach ($pager->links() as $link) : ?>
            <li <?= $link['active'] ? 'class="active"' : '' ?>>
                <?php if (!empty($baseUrl)) : ?>
                    <a href="<?= $baseUrl ?>?page=<?= $link['title'] ?>"><?= $link['title'] ?></a>
                <?php else : ?>
                    <a href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
                <?php endif; ?>
            </li>
        <?php endforeach ?>

        <!-- Nút Next -->
        <?php if ($pager->hasNext()) : ?>
            <li>
                <a href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>">
                    <span aria-hidden="true"><?= lang('Pager.next') ?></span>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>">
                    <span aria-hidden="true"><?= lang('Pager.last') ?></span>
                </a>
            </li>
        <?php endif ?>
    </ul>
</nav>
