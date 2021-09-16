<main class="page__main page__main--publication">
  <div class="container">
    <h1 class="page__title page__title--publication"><?=htmlspecialchars($post['title'])?></h1>
    <section class="post-details">
      <h2 class="visually-hidden">Публикация</h2>
      <div class="post-details__wrapper post-<?=htmlspecialchars($post['class_name'])?>">
        <div class="post-details__main-block post post--details">
          <?=$blockContent?>
          <div class="post__indicators">
            <div class="post__buttons">
              <a class="post__indicator post__indicator--likes button" href="like.php?postId=<?=$post['id'];?>" title="Лайк">
                <svg class="post__indicator-icon" width="20" height="17">
                  <use xlink:href="#icon-heart"></use>
                </svg>
                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                  <use xlink:href="#icon-heart-active"></use>
                </svg>
                <span><?=$post['likes_count']?></span>
                <span class="visually-hidden">количество лайков</span>
              </a>
              <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-comment"></use>
                </svg>
                <span><?=$post['comments_count']?></span>
                <span class="visually-hidden">количество комментариев</span>
              </a>
              <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-repost"></use>
                </svg>
                <span>5</span>
                <span class="visually-hidden">количество репостов</span>
              </a>
            </div>
            <span class="post__view"><?=$post['num_of_views']?> просмотров</span>
          </div>
          <ul class="post__tags">
            <?php foreach($tags as $tag): ?>
              <li><a href="search.php?search=<?=trim($tag, '#')?>"><?=htmlspecialchars($tag)?></a></li>
            <?php endforeach; ?>
          </ul>
          <div class="comments">
            <form class="comments__form form" action="post.php?post_id=<?=$post['id']?>" method="post">
              <div class="comments__my-avatar">
                <img class="comments__picture" src="img/<?=$user['avatar']?>" alt="Аватар пользователя">
              </div>
              <div class="form__input-section
              <?php if(!empty($errors)): ?>
                form__input-section--error
              <?php endif; ?>
              ">
                <textarea class="comments__textarea form__textarea form__input" name="text" placeholder="Ваш комментарий"></textarea>
                <label class="visually-hidden">Ваш комментарий</label>
                <?php if(!empty($errors)): ?>
                  <button class="form__error-button button" type="button">!</button>
                  <div class="form__error-text">
                    <h3 class="form__error-title">Ошибка валидации</h3>
                    <p class="form__error-desc"><?=$errors['text']?></p>
                  </div>
                <?php endif; ?>
              </div>
              <button class="comments__submit button button--green" type="submit">Отправить</button>
            </form>
            <div class="comments__list-wrapper">
              <ul class="comments__list">
                <?php foreach($comments as $comment): ?>
                <li class="comments__item user">
                  <div class="comments__avatar">
                    <a class="user__avatar-link" href="#">
                      <img class="comments__picture" src="img/<?=$comment['avatar']?>" alt="Аватар пользователя">
                    </a>
                  </div>
                  <div class="comments__info">
                      <div class="comments__name-wrapper">
                        <a class="comments__user-name" href="#">
                          <span><?=$comment['login']?></span>
                        </a>
                        <time class="comments__time" datetime="2019-03-18"><?=getModDate($comment['created_at'])['rel']?></time>
                      </div>
                      <p class="comments__text">
                        <?=$comment['comment']?>
                      </p>
                  </div>
                </li>
                <?php endforeach; ?>
              </ul>
              <a class="comments__more-link" href="#">
                <span>Показать все комментарии</span>
                <sup class="comments__amount">45</sup>
              </a>
            </div>
          </div>
        </div>
        <div class="post-details__user user">
          <div class="post-details__user-info user__info">
            <div class="post-details__avatar user__avatar">
              <a class="post-details__avatar-link user__avatar-link" href="#">
                <img class="post-details__picture user__picture" src="img/<?=$post['avatar']?>" alt="Аватар пользователя">
              </a>
            </div>
            <div class="post-details__name-wrapper user__name-wrapper">
              <a class="post-details__name user__name" href="#">
                <span><?=htmlspecialchars($post['author'])?></span>
              </a>
              <time class="post-details__time user__time" datetime="2014-03-20"><?=str_replace('назад', '', getModDate($post['created_at'])['rel']);?> на сайте</time>
            </div>
          </div>
          <div class="post-details__rating user__rating">
            <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
              <span class="post-details__rating-amount user__rating-amount"><?=$post['subscription_count']?></span>
              <span class="post-details__rating-text user__rating-text">подписчиков</span>
            </p>
            <p class="post-details__rating-item user__rating-item user__rating-item--publications">
              <span class="post-details__rating-amount user__rating-amount"><?=$post['post_count']?></span>
              <span class="post-details__rating-text user__rating-text">публикаций</span>
            </p>
          </div>
          <div class="post-details__user-buttons user__buttons">
            <a class="user__button user__button--subscription button button--main" type="button" href="subscription.php?isSubscribed=<?=$isSubscribed?>&subUserId=<?=$post['user_id']?>">
              <?php if(!$isSubscribed): ?>
                Подписаться
              <?php else: ?>
                Отписаться
              <?php endif; ?>
            </a>
            <a class="user__button user__button--writing button button--green" href="#">Сообщение</a>
          </div>
        </div>
      </div>
    </section>
  </div>
</main>