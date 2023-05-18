
<?php
$username_field = isset($username_field) ? $username_field : 'email';
$password_field = isset($password_field) ? $password_field : 'password';

$username_label = isset($username_label) ? $username_label : __d('alaxos', 'email');
$password_label = isset($password_label) ? $password_label : __d('alaxos', 'password');
$login_label    = isset($login_label)    ? $login_label    : __d('alaxos', 'login');
?>

<div id="login_form">
    <?php
    echo $this->AlaxosForm->create();
    
    echo '<div class="top_label">';
    echo $username_label;
    echo '</div>';
    echo $this->AlaxosForm->input($username_field, array('label' => false));
    
    echo '<div class="top_label">';
    echo $password_label;
    echo '</div>';
    echo $this->AlaxosForm->input($password_field, array('label' => false));
    
    echo '<div style="height:10px"></div>';
    
    echo $this->AlaxosForm->submit($login_label);
    echo $this->AlaxosForm->end();
    ?>
</div>