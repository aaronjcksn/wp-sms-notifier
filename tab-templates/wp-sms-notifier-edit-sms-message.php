<?php
    if ( ! defined( 'ABSPATH') ) {
        exit; // Exit if accessed directly
    }
?>

<?php
    self::fetch_wp_feed();
?>
<div class="container wp-sms-notifier-sms-section">
    <ul>
        <?php
            foreach($x->channel->item as $entry) {
                if($i == 3) break;
                echo "<li style='list-style: none;'><a href='$entry->link' title='$entry->title'>$title</a>
                            $entry->description
                     </li>";
                $i++;
            }
        ?>
    </ul>
</div>
