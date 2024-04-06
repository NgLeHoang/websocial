<span>
    <?php
        if (checkLikeStatus($post['id'])) {
            $like_btn_display = 'none';
            $unlike_btn_display = '';
        } else {
            $like_btn_display = '';
            $unlike_btn_display = 'none';
        }
    ?>
    <i class="fa-solid fa-heart unlike-btn" style="display:<?=$unlike_btn_display?>"
        data-post-id="<?=$post['id']?>"></i>
    <i class="fa-regular fa-heart like-btn" style="display:<?=$like_btn_display?>" data-post-id="<?=$post['id']?>"></i>
</span>
&nbsp;&nbsp;
<i class="fa-regular fa-comment"></i>
</h4>
<div>
    <span class="p-1 mx-3" data-bs-toggle="modal" id="likecount<?=$post['id']?>"
        data-bs-target="#likes<?=$post['id']?>"><?php is_array($count_likes) ? $count = count($count_likes) : $count = 0; echo $count; ?>
        like</span>
    <span class="p-1 mx-3" data-bs-toggle="modal" id="commentcount<?=$post['id']?>"
        data-bs-target="#postview<?=$post['id']?>"><?php is_array($count_comments) ? $count = count($count_comments) : $count = 0; echo $count; ?>
        comment</span>
</div>