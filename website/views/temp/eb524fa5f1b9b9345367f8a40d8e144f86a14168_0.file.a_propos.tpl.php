<?php
/* Smarty version 4.1.0, created on 2022-04-01 10:33:16
  from 'C:\Web\a2_projet_web\a2_projet_web\website\views\a_propos.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.0',
  'unifunc' => 'content_6246b8cc1d1733_61743522',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'eb524fa5f1b9b9345367f8a40d8e144f86a14168' => 
    array (
      0 => 'C:\\Web\\a2_projet_web\\a2_projet_web\\website\\views\\a_propos.tpl',
      1 => 1648796329,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6246b8cc1d1733_61743522 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>

<html lang="fr">
    <head>
        <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
        <meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
">
        <link rel="stylesheet" href="http://ctsstatic.fr/assets/vendors/fontawesome/css/all.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="http://ctsstatic.fr/assets/images/logo_petit.png">
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="http://ctsstatic.fr/assets/css/a_propos.css">
    </head>
    <body>
        <div class="container">
            <div class="main">
                <div class="content">
                    <div class="bubble">
                        <img src="http://ctsstatic.fr/assets/images/fond_cesi_large.png" alt="fond cesi">
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
