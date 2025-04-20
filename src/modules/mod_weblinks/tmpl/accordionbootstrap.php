<?php if ($params->get('groupby', 0)) : ?>
    <?php 
    $cats = [];
    $cols = $params->get('groupby_columns', 3);
    
    foreach ($list as $l) {
        $cats[] = array('catid' => $l->catid, 'title' => $l->category_title);
    }
    $cats = array_values(array_map('unserialize', array_unique(array_map('serialize', $cats))));
    ?>
    
    <?php if ($cols > 1) : ?>
    <div class="row">
    <?php endif; ?>
    
    <?php foreach ($cats as $k => $cat) : ?>
        <?php 
        $items = array_filter($list, function($item) use ($cat) {
            return $item->catid == $cat['catid'];
        });
        $accordionId = 'accordion-' . $cat['catid'];
        ?>
        
        <?php if ($cols > 1) : ?>
            <div class="col-md-<?php echo (12 / $cols); ?>">
        <?php endif; ?>
        
        <div class="accordion mb-3" id="weblinksAccordion-<?php echo $accordionId; ?>">
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-<?php echo $accordionId; ?>">
                    <button class="accordion-button collapsed" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#collapse-<?php echo $accordionId; ?>" 
                            aria-expanded="false" 
                            aria-controls="collapse-<?php echo $accordionId; ?>">
                        <?php echo htmlspecialchars($cat['title'], ENT_COMPAT, 'UTF-8'); ?>
                    </button>
                </h2>
                
                <div id="collapse-<?php echo $accordionId; ?>" 
                     class="accordion-collapse collapse" 
                     aria-labelledby="heading-<?php echo $accordionId; ?>" 
                     data-bs-parent="#weblinksAccordion-<?php echo $accordionId; ?>">
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
                                                    $modalId = 'weblink-modal-' . $item->id;
                                                    ?>
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#<?php echo $modalId; ?>">
                                                        <?php echo htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8'); ?>
                                                    </a>
                                                    
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="<?php echo $modalId; ?>" tabindex="-1" aria-labelledby="<?php echo $modalId; ?>Label" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="<?php echo $modalId; ?>Label"><?php echo htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8'); ?></h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <iframe src="<?php echo $link; ?>" width="100%" height="500px" frameborder="0"></iframe>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
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
        </div>
        
        <?php if ($cols > 1) : ?>
            </div> <!-- End column -->
            <?php if (($k + 1) % $cols == 0 || ($k + 1) == count($cats)) : ?>
                </div> <!-- End row -->
                <?php if (($k + 1) < count($cats)) : ?>
                    <div class="row">
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        
    <?php endforeach; ?>
    
    <?php if ($cols > 1 && count($cats) % $cols != 0) : ?>
        </div> <!-- Close any open row -->
    <?php endif; ?>
    
<?php else : ?>
    <ul class="weblinks<?php echo $moduleclass_sfx; ?> list-unstyled">
        <?php foreach ($list as $item) : ?>
            <!-- Non-grouped list items here, unchanged -->
        <?php endforeach; ?>
    </ul>
<?php endif; ?>