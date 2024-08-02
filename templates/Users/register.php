<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="users form content">
            <?= $this->Form->create() ?>
            <fieldset>
                <h4 class="heading"><?= __('Register') ?></h4>
                <?= $this->Form->control('username', ['required' => true]) ?>
                <?= $this->Form->control('password', ['required' => true, 'value' => ""]) ?>
                <?= $this->Form->control('confirm password', ['required' => true, 'type' => "password", 'value' => ""]) ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
