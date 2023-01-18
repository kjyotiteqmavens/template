<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>

<div class="users index content">
    <h3><?= __('Users') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('fname') ?></th>
                    <th><?= $this->Paginator->sort('lname') ?></th>
                    <th><?= $this->Paginator->sort('phone') ?></th>
                    <th><?= $this->Paginator->sort('Profile.role') ?></th>
                    <th><?= $this->Paginator->sort('Profile.city') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>

                    <th><?= $this->Paginator->sort('gender') ?></th>
                    <th><?= $this->Paginator->sort('image') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>

                <?php
                $sno = 1;

                foreach ($users as $user) : ?>
                    <tr>
                        <!-- <td><?= $this->Number->format($user->id) ?></td> -->
                        <td><?= $sno++ ?></td>
                        <td><?= h($user->fname) ?></td>
                        <td><?= h($user->lname) ?></td>
                        <td><?= h($user->phone) ?></td>
                        <td><?= h($user->role) ?></td>
                        <td><?= h($user->city) ?></td>
                        <td><?= h($user->email) ?></td>

                        <td><?= h($user->gender) ?></td>
                        <td><?= $this->Html->image($user->image) ?></td>

                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?>
                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]) ?>
                            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>