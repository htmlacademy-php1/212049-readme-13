<article class="profile__post post post-text">
  <header class="post__header">
    <div class="post__author">
      <a class="post__author-link" href="#" title="Автор">
        <div class="post__avatar-wrapper post__avatar-wrapper--repost">
          <img class="post__author-avatar" src="img/<?=$profileUser['avatar']?>" alt="Аватар пользователя">
        </div>
        <div class="post__info">
          <b class="post__author-name"><?=htmlspecialchars($profileUser['login'])?></b>
          <time class="post__time" datetime="2019-03-30T14:31"><?=getModDate($post['created_at'])['rel']?></time>
        </div>
      </a>
    </div>
  </header>
  <div class="post__main">
    <h2><a href="#"><?=htmlspecialchars($post['title'])?></a></h2>
    <p>
     <?=htmlspecialchars($post['content'])?>
    </p>
    <a class="post-text__more-link" href="#">Читать далее</a>
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
    <div class="comments__list-wrapper">
      <ul class="comments__list">
        <li class="comments__item user">
          <div class="comments__avatar">
            <a class="user__avatar-link" href="#">
              <img class="comments__picture" src="img/userpic-larisa.jpg" alt="Аватар пользователя">
            </a>
          </div>
          <div class="comments__info">
            <div class="comments__name-wrapper">
              <a class="comments__user-name" href="#">
                <span>Лариса Роговая</span>
              </a>
              <time class="comments__time" datetime="2019-03-20">1 ч назад</time>
            </div>
            <p class="comments__text">
              Красота!!!1!
            </p>
          </div>
        </li>
        <li class="comments__item user">
          <div class="comments__avatar">
            <a class="user__avatar-link" href="#">
              <img class="comments__picture" src="img/userpic-larisa.jpg" alt="Аватар пользователя">
            </a>
          </div>
          <div class="comments__info">
            <div class="comments__name-wrapper">
              <a class="comments__user-name" href="#">
                <span>Лариса Роговая</span>
              </a>
              <time class="comments__time" datetime="2019-03-18">2 дня назад</time>
            </div>
            <p class="comments__text">
              Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской границы. Байкал считается самым глубоким озером в мире. Он окружен сетью пешеходных маршрутов, называемых Большой байкальской тропой. Деревня Листвянка, расположенная на западном берегу озера, – популярная отправная точка для летних экскурсий. Зимой здесь можно кататься на коньках и собачьих упряжках.
            </p>
          </div>
        </li>
      </ul>
      <a class="comments__more-link" href="#">
        <span>Показать все комментарии</span>
        <sup class="comments__amount">45</sup>
      </a>
    </div>
  </div>
  <form class="comments__form form" action="#" method="post">
    <div class="comments__my-avatar">
      <img class="comments__picture" src="img/<?=$user['avatar']?>" alt="Аватар пользователя">
    </div>
    <textarea class="comments__textarea form__textarea" placeholder="Ваш комментарий"></textarea>
    <label class="visually-hidden">Ваш комментарий</label>
    <button class="comments__submit button button--green" type="submit">Отправить</button>
  </form>
</article>