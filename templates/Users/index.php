<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<div class="users index content">
    <?= $this->Html->link(__('New User'), ['action' => 'register'], ['class' => 'button float-right']) ?>
    <h3><?= __('Users') ?></h3>
    <div class="table-responsive">
        <table>
            
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('username') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $currentUserName = $this->request->getAttribute('identity')->get('username');
                ?>
                <tr>
                    <td><?= h($currentUserName) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $currentUserName]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $currentUserName]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $currentUserName], ['confirm' => __('Are you sure you want to delete # {0}?', $currentUserName)]) ?>
                        <?= $this->Html->tag('span', __('Current'), ['style' => 'color: red; font-weight: normal']) ?>
                    </td>
                    </td>
                </tr>
                <?php 
                    foreach ($users as $user): 
                        if ($user->username !== $currentUserName):
                ?>
                    <tr>
                        <td><?= h($user->username) ?></td>
                        <td class="actions">
                            
                                <?= $this->Html->link(__('View'), ['action' => 'view', $user->username]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->username]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->username], ['confirm' => __('Are you sure you want to delete # {0}?', $user->username)]) ?>
                                
                        </td>
                    </tr>
                <?php 
                        endif;
                    endforeach; 
                ?>
            </tbody>
        </table>
    </div>
</div>
