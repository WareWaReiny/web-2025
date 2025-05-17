<div class="post">
    <img src="<?php echo $user['avatar']; ?>" alt="profile's picture" class="avatar">
    <p class="username">
        <a href="profile.php?user_id=<?php echo $user['id']; ?>">
            <?php echo $user['name']; ?>
        </a>
    </p>
    <img src="<?php echo $post['image']; ?>" alt="post-image" class="post-image">
    <div class="post-info">
        <button class="like-button">
            <img src="src/images/heart.png" alt="like-icon" class="like-icon">
            <?php echo $post['likes']; ?>
        </button>
        <p class="post-text"><?php echo $post['text']; ?></p>
        <p class="time"><?php echo date('d.m.Y H:i', $post['timestamp']); ?></p>
    </div>
</div>