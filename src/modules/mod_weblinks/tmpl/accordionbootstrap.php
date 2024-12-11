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
    foreach ($list as $l) {
        $cats[] = array('catid' => $l->catid, 'title' => $l->category_title);
    }
    $cats = array_values(array_map('unserialize', array_unique(array_map('serialize', $cats))));
    ?>
    
    <div class="accordion" id="weblinksAccordion">
        <?php foreach ($cats as $k => $cat) : ?>
            <?php 
            $items = array_filter($list, function($item) use ($cat) {
                return $item->catid == $cat['catid'];
            });
            $accordionId = 'accordion-' . $cat['catid'];
            ?>
            
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-<?php echo $accordionId; ?>">
                    <button class="accordion-button <?php echo ($k !== 0) ? 'collapsed' : ''; ?>" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#collapse-<?php echo $accordionId; ?>" 
                            aria-expanded="<?php echo ($k === 0) ? 'true' : 'false'; ?>" 
                            aria-controls="collapse-<?php echo $accordionId; ?>">
                        <?php echo htmlspecialchars($cat['title'], ENT_COMPAT, 'UTF-8'); ?>
                    </button>
                </h2>
                
                <div id="collapse-<?php echo $accordionId; ?>" 
                     class="accordion-collapse collapse <?php echo ($k === 0) ? 'show' : ''; ?>" 
                     aria-labelledby="heading-<?php echo $accordionId; ?>" 
                     data-bs-parent="#weblinksAccordion">
                    <div class="accordion-body">
                        <ul class="weblinks<?php echo $moduleclass_sfx; ?> list-unstyled">
                            <?php foreach ($items as $item) : ?>
                                <li class="mb-3">
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="flex-grow-1">
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
                                                    $attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=' . $width . ',height=' . $height;
                                                    echo "<a href=\"$link\" onclick=\"window.open(this.href, 'targetWindow', '" . $attribs . "'); return false;\">" .
                                                        htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') . '</a>';
                                                    break;
                                                case 3:
                                                    // Open in a modal window
                                                    $modalId                   = 'weblink-item-modal-' . $item->id;
                                                    $modalParams['title']      = htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8');
                                                    $modalParams['url']        = $link;
                                                    $modalParams['height']     = '100%';
                                                    $modalParams['width']      = '100%';
                                                    $modalParams['bodyHeight'] = 70;
                                                    $modalParams['modalWidth'] = 80;
                                                    echo HTMLHelper::_('bootstrap.renderModal', $modalId, $modalParams);
                                                    echo '<button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#' . $modalId . '">' . 
                                                        htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') . '</button>';
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
                                            <div class="flex-grow-1"><?php echo $item->description; ?></div>
                                        <?php endif; ?>
                                        <?php if ($params->get('hits', 0)) : ?>
                                            <div>
                                                <span class="badge bg-info"><?php echo $item->hits . ' ' . Text::_('MOD_WEBLINKS_HITS'); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <ul class="weblinks<?php echo $moduleclass_sfx; ?> list-unstyled">
        <?php foreach ($list as $item) : ?>
            <li class="mb-3">
                <div class="d-flex flex-wrap gap-3">
                    <div class="flex-grow-1">
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
                                $attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=' . $width . ',height=' . $height;
                                echo "<a href=\"$link\" onclick=\"window.open(this.href, 'targetWindow', '" . $attribs . "'); return false;\">" .
                                    htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') . '</a>';
                                break;
                            case 3:
                                // Open in a modal window
                                $modalId                   = 'weblink-item-modal-' . $item->id;
                                $modalParams['title']      = htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8');
                                $modalParams['url']        = $link;
                                $modalParams['height']     = '100%';
                                $modalParams['width']      = '100%';
                                $modalParams['bodyHeight'] = 70;
                                $modalParams['modalWidth'] = 80;
                                echo HTMLHelper::_('bootstrap.renderModal', $modalId, $modalParams);
                                echo '<button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#' . $modalId . '">' .
                                    htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') . '</button>';
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
                        <div class="flex-grow-1"><?php echo $item->description; ?></div>
                    <?php endif; ?>
                    <?php if ($params->get('hits', 0)) : ?>
                        <div>
                            <span class="badge bg-info"><?php echo $item->hits . ' ' . Text::_('MOD_WEBLINKS_HITS'); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>