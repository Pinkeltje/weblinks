<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Weblinks
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

?>

<?php if ($params->get('groupby', 0)) : ?>
    <?php 
    $cats = [];
    $cols = $params->get('groupby_columns', 3);
    
    foreach ($list as $l) {
        $cats[] = array('catid' => $l->catid, 'title' => $l->category_title);
    }
    $cats = array_values(array_map('unserialize', array_unique(array_map('serialize', $cats))));
    ?>
    
    <style>
    .uk-accordion > :nth-child(n+2) {
        margin-top: 0 !important;
        padding-top: 0 !important;
        border-top: none !important;
    }
    .uk-accordion-title {
        margin-bottom: 5px !important;
    }
    </style>
    
    <?php if ($cols > 1) : ?>
    <div class="uk-grid uk-grid-small" uk-grid>
    <?php endif; ?>
    
    <?php foreach ($cats as $k => $cat) : ?>
        <?php 
        $items = array_filter($list, function($item) use ($cat) {
            return $item->catid == $cat['catid'];
        });
        $accordionId = 'accordion-' . $cat['catid'];
        ?>
        
        <?php if ($cols > 1) : ?>
            <div class="uk-width-1-<?php echo $cols; ?>@m uk-margin-bottom">
        <?php endif; ?>
        
        <div uk-accordion="multiple: false" id="weblinks-accordion-<?php echo $accordionId; ?>">
            <div>
                <a class="uk-accordion-title" href="#"><?php echo htmlspecialchars($cat['title'], ENT_COMPAT, 'UTF-8'); ?></a>
                <div class="uk-accordion-content uk-margin-remove-top">
                    <ul class="weblinks<?php echo $moduleclass_sfx; ?> uk-list uk-margin-remove">
                        <?php foreach ($items as $item) : ?>
                            <li class="uk-margin-xsmall">
                                <div class="uk-flex uk-flex-wrap uk-flex-between uk-flex-top">
                                    <div class="uk-flex-1">
                                        <?php
                                        $link   = $item->link;
                                        $width  = (int) $item->params->get('width', 600);
                                        $height = (int) $item->params->get('height', 500);
                                        switch ($item->params->get('target')) {
                                            case 1:
                                                // Open in a new window
                                                echo '<a href="' . $link . '" target="_blank" rel="' . $params->get('follow', 'nofollow') . '">' .
                                                    htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') . '</a>';
                                                break;
                                            case 2:
                                                // Open in a popup window
                                                echo '<a href="' . $link . '" onclick="window.open(this.href, \'targetWindow\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=' . $width . ',height=' . $height . '\'); return false;">' .
                                                    htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') . '</a>';
                                                break;
                                            case 3:
                                                // Use UIkit modal
                                                echo '<a href="' . $link . '" uk-toggle>' .
                                                    htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') . '</a>';
                                                break;
                                            default:
                                                // Open in parent window
                                                echo '<a href="' . $link . '" rel="' . $params->get('follow', 'nofollow') . '">' .
                                                    htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') . '</a>';
                                                break;
                                        }
                                        ?>
                                    </div>
                                    <?php if ($params->get('description', 0)) : ?>
                                        <div class="uk-flex-1 uk-margin-xsmall-left"><?php echo $item->description; ?></div>
                                    <?php endif; ?>
                                    <?php if ($params->get('hits', 0)) : ?>
                                        <div class="uk-margin-xsmall-left">
                                            <span class="uk-badge"><?php echo $item->hits . ' ' . Text::_('MOD_WEBLINKS_HITS'); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <?php if ($cols > 1) : ?>
            </div> <!-- End column -->
        <?php endif; ?>
        
    <?php endforeach; ?>
    
    <?php if ($cols > 1) : ?>
    </div> <!-- End grid -->
    <?php endif; ?>
    
<?php else : ?>
    <ul class="weblinks<?php echo $moduleclass_sfx; ?> uk-list uk-margin-remove">
        <?php foreach ($list as $item) : ?>
            <li class="uk-margin-xsmall">
                <div class="uk-flex uk-flex-wrap uk-flex-between uk-flex-top">
                    <div class="uk-flex-1">
                        <?php
                        $link   = $item->link;
                        $width  = (int) $item->params->get('width', 600);
                        $height = (int) $item->params->get('height', 500);
                        switch ($item->params->get('target')) {
                            case 1:
                                // Open in a new window
                                echo '<a href="' . $link . '" target="_blank" rel="' . $params->get('follow', 'nofollow') . '">' .
                                    htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') . '</a>';
                                break;
                            case 2:
                                // Open in a popup window
                                echo '<a href="' . $link . '" onclick="window.open(this.href, \'targetWindow\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=' . $width . ',height=' . $height . '\'); return false;">' .
                                    htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') . '</a>';
                                break;
                            case 3:
                                // Use UIkit modal
                                echo '<a href="' . $link . '" uk-toggle>' .
                                    htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') . '</a>';
                                break;
                            default:
                                // Open in parent window
                                echo '<a href="' . $link . '" rel="' . $params->get('follow', 'nofollow') . '">' .
                                    htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') . '</a>';
                                break;
                        }
                        ?>
                    </div>
                    <?php if ($params->get('description', 0)) : ?>
                        <div class="uk-flex-1 uk-margin-xsmall-left"><?php echo $item->description; ?></div>
                    <?php endif; ?>
                    <?php if ($params->get('hits', 0)) : ?>
                        <div class="uk-margin-xsmall-left">
                            <span class="uk-badge"><?php echo $item->hits . ' ' . Text::_('MOD_WEBLINKS_HITS'); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>