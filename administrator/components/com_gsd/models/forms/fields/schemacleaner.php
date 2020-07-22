<?php

/**
 * @package         Google Structured Data
 * @version         4.6.0 Free
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2019 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('subform');

class JFormFieldSchemaCleaner extends JFormFieldSubform
{
    /**
     * Method to get a list of options for a list input.
     *
     * @return      array           An array of JHtml options.
     */
    protected function getInput()
    {
        JFactory::getDocument()->addStyleDeclaration('
            .schemacleaner {
                max-width: 600px;
                box-sizing: border-box;
            }
            .schemacleaner * {
                box-sizing: inherit;
            }
            .schemacleaner .controls {
                padding:0;
            }
            .schemacleaner .adminlist thead tr > th:first-child {
                width: 70px;
            }
            .schemacleaner input {
                padding: 14px 10px;
            }
            .schemacleaner td, .schemacleaner th {
                vertical-align:middle;
            }
            .schemacleaner .nrtoggle {
                top: 3px;
                left: 6px;
            }
        ');

        $html = '<div class="schemacleaner">' . parent::getInput() . '</div>';

        return $html;
    }
}