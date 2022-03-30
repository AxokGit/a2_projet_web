<?php
/* Smarty version 4.1.0, created on 2022-03-29 09:24:59
  from 'D:\OneDrive\OneDrive - Association Cesi Viacesi mail\01 Pro\Semestre 1\Projet_4 - Développement Web\Livrable 3\a2_projet_web\views\a_propos.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.0',
  'unifunc' => 'content_6242b44ba16ca2_44770285',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '59e57c68fae074bc91f60ee36667886dd0f07d1f' => 
    array (
      0 => 'D:\\OneDrive\\OneDrive - Association Cesi Viacesi mail\\01 Pro\\Semestre 1\\Projet_4 - Développement Web\\Livrable 3\\a2_projet_web\\views\\a_propos.tpl',
      1 => 1648538698,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6242b44ba16ca2_44770285 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>

<html lang="fr">
    <head>
        <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
        <meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
">
        <link rel="stylesheet" href="./assets/vendors/fontawesome/css/all.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="assets/images/logo_petit.png">
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="assets/css/a_propos.css">
    </head>
    <body>
        <div class="container">
            <div class="main">
                <div class="content">
                    <div class="bubble">
                        <img src="assets/images/fond_cesi_large.png" alt="fond cesi">
                        <div class="centered"><?php echo $_smarty_tpl->tpl_vars['page_title']->value;?>
</div>
                    </div>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['bubbles']->value, 'bubble', false, 'key');
$_smarty_tpl->tpl_vars['bubble']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['bubble']->value) {
$_smarty_tpl->tpl_vars['bubble']->do_else = false;
?>
                    <div class="bubble">
                        <div class="title_bubble"><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
</div>
                        <div class="text_content"><?php echo $_smarty_tpl->tpl_vars['bubble']->value;?>
</div>
                    </div>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    <div>
                        <a class="btnvoir" href="accueil.php">
                            <span>Retour à l'accueil</span>
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </body>
</html><?php }
}
