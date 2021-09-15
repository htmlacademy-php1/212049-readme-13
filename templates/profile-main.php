<main class="page__main page__main--profile">
  <h1 class="visually-hidden">Профиль</h1>
  <div class="profile profile--default">
    <div class="profile__user-wrapper">
      <div class="profile__user user container">
        <div class="profile__user-info user__info">
          <div class="profile__avatar user__avatar">
            <img class="profile__picture user__picture" src="img/<?=$profileUser['avatar']?>" alt="Аватар пользователя">
          </div>
          <div class="profile__name-wrapper user__name-wrapper">
            <span class="profile__name user__name"><?=$profileUser['login']?></span>
            <time class="profile__user-time user__time" datetime="2014-03-20"><?=str_replace('назад', '', getModDate($profileUser['registed_at'])['rel'], );?> на сайте</time>
          </div>
        </div>
        <div class="profile__rating user__rating">
          <p class="profile__rating-item user__rating-item user__rating-item--publications">
            <span class="user__rating-amount"><?=$profileUser['posts']?></span>
            <span class="profile__rating-text user__rating-text">публикаций</span>
          </p>
          <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
            <span class="user__rating-amount"><?=$profileUser['subscriptions']?></span>
            <span class="profile__rating-text user__rating-text">подписчиков</span>
          </p>
        </div>
        <div class="profile__user-buttons user__buttons">
          <a class="profile__user-button user__button user__button--subscription button button--main "href="subscription.php?isSubscribed=<?=$isSubscribed?>&profileUserId=<?=$profileUser['id']?>">
            <?php if(!$isSubscribed): ?>
              Подписаться
            <?php else: ?>
              Отписаться
            <?php endif; ?>
          </a>
          <a class="profile__user-button user__button user__button--writing button button--green" href="#">Сообщение</a>
        </div>
      </div>
    </div>
    <div class="profile__tabs-wrapper tabs">
      <div class="container">
        <div class="profile__tabs filters">
          <b class="profile__tabs-caption filters__caption">Показать:</b>
          <ul class="profile__tabs-list filters__list tabs__list">
            <?php foreach ($filters as $key => $filter): ?>
              <li class="profile__tabs-item filters__item">
                <a class="profile__tabs-link filters__button tabs__item
                  <?php if($key === $activeFilter): ?>
                    filters__button--active tabs__item--active
                  <?php endif; ?>
                  button" href="profile.php?filter=<?=$key?>"><?=$filter?>
               </a>
            </li>
            <?php endforeach; ?>
            </li>
          </ul>
        </div>
        <div class="profile__tab-content">
            <?=$blocksContainer?>
        </div>
      </div>
    </div>
  </div>
</main>co