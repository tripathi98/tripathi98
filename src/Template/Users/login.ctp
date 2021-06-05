<!-- File: src/Template/Users/login.ctp -->

<div class="users form">
<?= $this->Flash->render() ?>
<?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your email and password') ?></legend>
        <?= $this->Form->control('email_or_phone') ?>
        <?= $this->Form->control('password') ?>
    </fieldset>
<?= $this->Html->link(__('Register'), ['action' => 'register']) ?>
<?= $this->Form->button(__('Login')); ?>
<?= $this->Form->end() ?>
</div>