<div class="header">
    <div class="logo">
        <i class="fa fa-magic" aria-hidden="true"></i>
    </div>
    <div class="title"><p><?= $detail['Thread'] ?></p></div>
</div>
<div class="content">
    <div id="listComment"></div>
</div>
<div class="footer">
    <div class="input-container">
        <input class="input value-comment" type="text" data-id="<?= $detail['IDThread'] ?>">
        <button type="submit"><i class="fa fa-paper-plane btn-send" aria-hidden="true"></i></button>
    </div>
    <div class="btn-scroll-bottom" style="display: none;">
        <i class="fa fa-angle-double-down" aria-hidden="true"></i>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function(){
        $('.input').focus();
        showChat()
        $('.btn-send').click(function(e) {
            e.preventDefault();
            var value = $('.value-comment').val();
            var idThread =  $('.value-comment').data('id');
            $.ajax({
                url: '<?= base_url('ForumDiskusi/AddComment') ?>',
                type: 'POST',
                data: {value: value, idThread: idThread},
                success: function(data) {
                    var res = JSON.parse(data)
                    if(res.status == true){
                        $('.value-comment').val('')
                        showChat()
                        scrollToBottom()
                    }
                }
            })
        })

        $('.btn-scroll-bottom').click(function() {
            scrollToBottom()
        })

        $('.content').scroll(function(){ 
            $('.btn-scroll-bottom').show();
            var st = $(this).scrollTop();
            var sh = $(this).prop("scrollHeight");
            var ch = $(this).height();
            if(st + ch >= sh - 100){
                $('.btn-scroll-bottom').hide();
            } else {
                $('.btn-scroll-bottom').show();
            }
        })
    })  

    function showChat() {
        $.ajax({
            url: '<?= base_url('ForumDiskusi/showChat') ?>',
            type: 'POST',
            data:{
                idThread: "<?= $detail['IDThread'] ?>",
            },
            success: function(data) {
                $('#listComment').html(data)
            }
        })
    }

    function scrollToBottom() {
        $(".content").animate({
            scrollTop: $(".content")[0].scrollHeight
        });
    }

</script>