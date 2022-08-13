<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Hint_Reveal
 * @subpackage Hint_Reveal/public/partials
 */
?>

<div class="hintReveal">
    <div class="hintRevealTable">
        <?php
        global $wpdb;

        if(isset($atts['id'])){
            $id = intval($atts['id']);
            $result = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}hint_reveal WHERE ID = $id");

            $data = unserialize($result->data);
            $columns = [];

            if(is_array($data)){
                foreach($data as $d){
                    $columns[] = $d['columns'];
                }
            }

            if(sizeof($columns) > 0){
                foreach($columns as $index => $rows){
                    
                    ?>
                    <div class="hintRow <?php echo (($index === 0) ? 'revealed': '') ?>">
                        <?php 
                        if(is_array($rows)){
                            foreach($rows as $key => $row){
                                ?>
                                <p class="hintColumn"><strong class="responsive_head"><?php echo $columns[0][$key]['field'] ?></strong>
                                    <span class="empty">????</span>
                                    <span class="fvalue"><?php echo $row['field'] ?></span>
                                </p>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <?php
                   
                }
            }
        }
        ?>

    </div>
    <button data-reveald="1" class="reveal_butn">Reveal</button>
</div>