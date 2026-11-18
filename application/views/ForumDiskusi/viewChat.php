<div id="scrol">
    <?php foreach($comment as $cm) : ?>
        <div class="comment">
        <div class="profile">
            <div class="img">
                <img src="<?= base_url() ?>assets/dist/img/avatar5.png" alt="">
            </div>
        </div>
        <div class="text-container">
            <div class="name-time">
                <p class="name"><?= $cm['UserName'] ?></p>
                <p class="time"><?= date('H:i', strtotime($cm['DateCreated'])) ?></p>
            </div>
            <div class="txtmessage">
                <p><?= $cm['ValueComment'] ?></p>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<script>
    $(document).ready(function() {
        
    });
    
</script>