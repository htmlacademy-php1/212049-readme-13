<article class="profile__post post post-photo">
  <header class="post__header">
    <h2><a href="#"><?=htmlspecialchars($post['title'])?></a></h2>
  </header>
  <div class="post__main">
    <div class="post-photo__image-wrapper">
      <img src="img/<?=$post['image']?>" alt="Фото от пользователя" width="760" height="396">
    </div>
  </div>
  <footer class="post__footer">
    <div class="post__indicators">
      <div class="post__buttons">
        <a class="post__indicator post__indicator--likes button" href="like.php?postId=<?=$post['id']?>&userId=<?=$user['id']?>" title="Лайк">
          <svg class="post__indicator-icon" width="20" height="17">
            <use xlink:href="#icon-heart"></use>
          </svg>
          <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
            <use xlink:href="#icon-heart-active"></use>
          </svg>
          <span>250</span>
          <span class="visually-hidden">количество лайков</span>
        </a>
        <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
          <svg class="post__indicator-icon" width="19" height="17">
            <use xlink:href="#icon-repost"></use>
          </svg>
          <span>5</span>
          <span class="visually-hidden">количество репостов</span>
        </a>
      </div>
      <time class="post__time" datetime="2019-01-30T23:41"><?=getModDate($post['created_at'])['rel']?></time>
    </div>
    <ul class="post__tags">
      <?php if(isset($post['hashtag']) && is_array($post['hashtag'])): ?>
        <?php foreach($post['hashtag'] as $tag): ?>
          <li><a href="search.php?search=<?=trim($tag, '#')?>"><?=htmlspecialchars($tag)?></a></li>
        <?php endforeach; ?>
      <?php else: ?>
        <li><a href="search.php?search=<?=trim($post['hashtag'], '#')?>"><?=htmlspecialchars($post['hashtag'])?></a></li>
      <?php endif; ?>
    </ul>
  </footer>
  <div class="comments">
    <a class="comments__button button" href="#">Показать комментарии</a>
  </div>
</article>