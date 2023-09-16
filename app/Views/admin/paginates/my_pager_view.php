<ul class="pagination">
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

    <?php foreach ($pager->links() as $link) : ?>
        <?php if ($link['active']) : ?>
            <li class="active">
                <a><?= $link['title'] ?></a>
                <!-- thêm input này để lấy giá trị pager đang hiển thị cho url ajax: -->
                <input type="hidden" id="currentPage" value="<?php echo $link['title']; ?>">
            </li>
        <?php else : ?>
            <li><a href="<?= $link['uri'] ?>"><?= $link['title'] ?></a></li>
        <?php endif; ?>
    <?php endforeach; ?>

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
