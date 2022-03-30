<?php
/* Smarty version 4.1.0, created on 2022-03-29 07:54:57
  from 'D:\OneDrive\OneDrive - Association Cesi Viacesi mail\01 Pro\Semestre 1\Projet_4 - Développement Web\Livrable 3\a2_projet_web\views\accueil.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.0',
  'unifunc' => 'content_62429f31d2b5e2_62125654',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3a5471dd0ee6719aaae35cb01cb81fbea07b0e68' => 
    array (
      0 => 'D:\\OneDrive\\OneDrive - Association Cesi Viacesi mail\\01 Pro\\Semestre 1\\Projet_4 - Développement Web\\Livrable 3\\a2_projet_web\\views\\accueil.tpl',
      1 => 1648533296,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62429f31d2b5e2_62125654 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>

<html lang="fr">
    <head>
        <?php echo '<?php'; ?>
 require "controller/Head.php" <?php echo '?>'; ?>

        <title>Accueil - CTS</title>
        <meta name="description" content="Page d'accueil de CTS">
        <link rel="stylesheet" type="text/css" href="assets/css/accueil.css">
    </head>
    <body>
        <div class="container">
            <div class="main">
                <!--<?php echo '<?php'; ?>
 require "controller/Top_bar.php" <?php echo '?>'; ?>
-->
                <div class="content">
                    <div class="bubble">
                        <img src="assets/images/fond_cesi_large.png" alt="fond cesi">
                        <div class="centered">CTS vous conduit vers la réussite</div>
                    </div>
                    <div class="bubble">
                        <div class="title_bubble">Notre histoire</div>
                        <div class="text_content">
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['personne']->value, 'p');
$_smarty_tpl->tpl_vars['p']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['p']->value) {
$_smarty_tpl->tpl_vars['p']->do_else = false;
?>
                            <?php echo $_smarty_tpl->tpl_vars['p']->value;?>

                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        Cesi Ton Stage a débuté dans le salon du co-fondateur Olivier SANDEL en 2022 et a été officiellement lancé le 1 avril 2022.
                        <br>
                        <br>
                        Sous la direction de Justine ADLER, CTS mène une activité diversifiée et assure le futur de milliers d'étudiants.
                        <br>
                        En février 2022, CESI a finalisé son acquisition de CTS, permettant ainsi la fusion entre la meilleure école professionnelle au monde et le meilleur site de stage.
                        </div>
                    </div>
                    <div class="bubble">
                        <div class="title_bubble">Notre but</div>
                        <div class="text_content">
                            La mission de Cesi Ton Stage est simple : mettre en relation les étudiants CESI de la France entière avec des entreprises qui leurs sont adaptées
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--<?php echo '<?php'; ?>
 require "controller/Script.php" <?php echo '?>'; ?>
-->
        <?php echo '<script'; ?>
 src="assets/js/accueil.js"><?php echo '</script'; ?>
>
    </body>
</html><?php }
}
