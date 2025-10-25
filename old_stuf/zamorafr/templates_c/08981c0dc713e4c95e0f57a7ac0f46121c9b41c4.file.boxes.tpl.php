<?php /* Smarty version Smarty-3.1.13, created on 2013-03-07 19:01:40
         compiled from "..\templates\boxes.tpl" */ ?>
<?php /*%%SmartyHeaderCode:281825136955e5c1484-83464608%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '08981c0dc713e4c95e0f57a7ac0f46121c9b41c4' => 
    array (
      0 => '..\\templates\\boxes.tpl',
      1 => 1362704495,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '281825136955e5c1484-83464608',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5136955e6599b5_03986116',
  'variables' => 
  array (
    'counter' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5136955e6599b5_03986116')) {function content_5136955e6599b5_03986116($_smarty_tpl) {?><head>
    
        <title>TICO-Tickets Control Center</title>
        <link href="/assets/css/index.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/casesAssignForm.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/graphBox.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/engineersBoxes.css" rel="stylesheet" type="text/css" />
           
</head>


 <div id="boxesMainSection">
    <div id="leyendDescriptionLink"><i class="icon-question-sign" style="color: #D7410B"></i>Leyend</div>
    <div id="leyendDescription">
        <ul>
            <li><i class="icon-circle" style="color:#B7CA34"></i>Foundation Case</li>
            <li><i class="icon-asterisk" style="color:#B7CA34"></i>Premier Case</li>
            <li><i class="icon-circle" style="color:#FF0000"></i>Critical Foundation Case</li>
            <li><i class="icon-asterisk" style="color:#FF0000"></i>Critical Premier Case</li>
            <li><i class="icon-phone-sign" style="color:#B7CA34"></i>Callback Foundation</li>
            <li><i class="icon-phone" style="color:#B7CA34"></i>Callback Premier</li>
            <li><i class="icon-phone-sign" style="color:#FF0000"></i>Critical Callback Foundation</li>
            <li><i class="icon-phone" style="color:#FF0000"></i>Critical Callback Premier</li>
            <li><i class="icon-circle-arrow-up" style="color:#B7CA34"></i>Elevation Foundation</li>
            <li><i class="icon-upload" style="color:#B7CA34"></i>Elevation Premier</li>
            <li><i class="icon-circle-arrow-up" style="color:#FF0000"></i>Critical Elevation Foundation</li>
            <li><i class="icon-upload" style="color:#FF0000"></i>Critical Elevation Premier</li>
            <li class="divider"></li>
            <li><i class="icon-star"></i>Premier Engineer</li>
            <li><i class="icon-home"></i>Status: Working from home</li>
            <li><i class="icon-thumbs-up"></i>Status: On Permission</li>
            <li><i class="icon-plane"></i>Status: Vacations</li>
            <li><i class="icon-book"></i>Status: Training</li>
            <li><i class="icon-medkit"></i>Status: Sick</li>
            <li><i class="icon-minus-sign"></i>Status: OOQ</li>
            <li><i class="icon-desktop"></i>Status: Queue Monitor</li>
            <li><i class="icon-suitcase"></i>Status: Holiday</li>
            <li><i class="icon-gift"></i>Status: Birthday</li>
        </ul>
    </div>                        
    <div id="engineerListBox">
        <div id="total_eng"><?php echo $_smarty_tpl->tpl_vars['counter']->value;?>
</div>
        <div id="engineerBoxTitle">Available</div>
        <div id="BoxColumnsLabel">
            <div id="engineerNameTitle"><b>Engineer</b></div>        
            <div id="in">In</div>
            <div id="lIn">Lunch</div>
            <div id="out">Out</div>
            <div id="especialIcons"></div>
            <div id="casesLabel">Cases</div>
        </div>

            <div id="boxRow">
                <div id="person">M - Andrea Martinez</div>
                <div id="in">10:00</div>
                <div id="lIn">13:00 - 14:00</div>
                <div id="out">19:00</div>
                <div id="cases"></div>
                <div id="especialIcons"></div>
            </div>
        
            <div id="boxRow">
                <div id="person">Mario Bolaños</div>
                <div id="in">10:00</div>
                <div id="lIn">13:00 - 14:00</div>
                <div id="out">19:00</div>
                <div id="cases"></div>
                <div id="especialIcons"></div>
            </div>
    </div>
</div>








<?php }} ?>