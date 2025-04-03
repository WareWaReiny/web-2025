<div class="post">
    <img src="<?php echo htmlspecialchars($post['avatar']); ?>" alt="profile's picture" class="avatar">
    <p class="username">
        <a href="profile.php?id=<?php echo $post['user_id']; ?>">
            <?php echo htmlspecialchars($post['name']); ?>
        </a>
    </p>
    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="post-image" class="post-image"> 
    <div class="post-info">
        <button class="like-button">
            <img src="src/images/heart.png" alt="like-icon" class="like-icon"> <?php echo $post['likes']; ?>
        </button>
        <p class="post-text"><?php echo htmlspecialchars($post['content']); ?></p>
        <p class="time"><?php echo date('d.m.Y H:i', strtotime($post['posted_at'])); ?></p>
    </div>
</div>

