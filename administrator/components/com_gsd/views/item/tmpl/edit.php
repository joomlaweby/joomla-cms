<?php

/**
 * @package         Google Structured Data
 * @version         4.6.0 Free
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.formvalidation');

if (!defined('J4'))
{
    JHtml::_('formbehavior.chosen', 'select:not(.select2)');
}

$input = JFactory::getApplication()->input;

// In case of modal
$isModal = $input->get('layout') == 'modal' ? true : false;
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';


// This style block is required in the Free version where the assignmentselection.css file is missing.
JFactory::getDocument()->addStyleDeclaration('
    .assign {
        background-color: #F0F0F0;
        border: solid 1px #DEDEDE;
        color: inherit !important;
        padding: 10px 12px;
        margin-bottom: -1px;
    }
    .assign .control-group {
        margin: 0;
    }
');


?>

<script type="text/javascript">
    Joomla.submitbutton = function(task) {
        var form = document.getElementById('adminForm');

        if (task == 'item.cancel' || document.formvalidator.isValid(form)) {
            Joomla.submitform(task, form);

            <?php if ($isModal) { ?>
            if (task !== 'item.apply') {
                window.parent.jQuery('#gsdModal').modal('hide');
            }
            <?php } ?>
        }
    }
</script>

<div class="nr-app <?php echo $isModal ? 'nr-isModal' : '' ?> <?php echo defined('J4') ? 'j4' : '' ?>">
    <div class="nr-row">
        <?php 
            if (!$isModal)
            {
                echo $this->sidebar; 
            }
        ?>
        <div class="nr-main-container">
            <?php if (!$isModal) { ?>
                <div class="nr-main-header">
                    <h2><?php echo $this->title; ?></h2>
                    <p><?php echo JText::_('GSD_ITEM_VIEW_DESC'); ?></p>
                </div>
            <?php } ?>
            <div class="nr-main-content">
                <form action="<?php echo JRoute::_('index.php?option=com_gsd&view=item&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
                    <div class="form-horizontal">
                        <div class="<?php echo defined('J4') ? 'row' : 'row-fluid' ?>">
                            <div class="span9 col-md-9">
                                <?php 
                                    echo $this->form->renderFieldSet('top'); 
                                ?>

                                <?php if (!$this->item->contenttype) { ?>
                                    <div class="well nr-well">
                                        <span class="icon-info"></span>
                                        <?php echo JText::_('GSD_OPTIONS_NEED_SAVE') ?>
                                    </div>
                                <?php } else { ?>

                                    <!-- Content Type -->
                                    <div class="well nr-well">
                                        <h4><?php echo JText::_('GSD_' . strtoupper($this->item->contenttype)) ?></h4>
                                        <div class="well-desc"><?php echo JText::_('GSD_MAP_DESC') ?></div>
                                        <?php echo $this->form->renderFieldSet('contenttype');  ?>
                                    </div>
                                    
                                    <!-- Assignments -->
                                    <?php 
                                        $fieldsets   = array_keys($this->form->getFieldSets());
                                        $assignments = array_diff($fieldsets, array('top', 'main', 'contenttype', 'content_type_help'));
                                        $integration = JText::_('PLG_GSD_' . $this->item->plugin . '_ALIAS');
                                    ?>
                                    
                                    <div class="well nr-well <?php echo $isModal ? 'hide' : '' ?>">
                                        <h4><?php echo JText::_('GSD_ITEM_RULES') ?></h4>
                                        <div class="well-desc">
                                            <?php 
                                                echo JText::sprintf(
                                                    'GSD_ITEM_PUBLISHING_ASSIGNMENTS_DESC', 
                                                    JText::_('GSD_' . $this->item->contenttype), 
                                                    $integration
                                                ) 
                                            ?>
                                        </div>

                                        <?php if ($assignments) { ?>
                                            <?php foreach ($assignments as $key => $assignment) { ?>
                                                <div class="assign"><?php echo $this->form->renderFieldSet($assignment); ?></div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <?php echo JText::sprintf('GSD_NO_FILTERS_NOTICE', $integration, $integration); ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="span3 col-md-3 form-vertical form-no-margin">
                                <?php echo $this->form->renderFieldSet('main'); ?>
                            </div>
                        </div>
                    </div>

                    <?php echo JHtml::_('form.token'); ?>
                    <input type="hidden" name="task" value="" />

                    <?php if ($isModal) { ?>
                        <input type="hidden" name="layout" value="modal" />
                        <input type="hidden" name="tmpl" value="component" />
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</div>
