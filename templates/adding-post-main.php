<main class="page__main page__main--adding-post">
  <div class="page__main-section">
    <div class="container">
      <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
    </div>
    <div class="adding-post container">
      <div class="adding-post__tabs-wrapper tabs">
        <div class="adding-post__tabs filters">
          <ul class="adding-post__tabs-list filters__list tabs__list">
            <?php foreach ($postTypes as $type): ?>
              <li class="adding-post__tabs-item filters__item">
                <a class="adding-post__tabs-link filters__button filters__button--<?=$type['class_name']?>
                  <?php if($type['class_name'] === $postType): ?>
                    filters__button--active tabs__item--active
                  <?php endif; ?>
                  tabs__item button" href="add.php?postType=<?=$type['class_name']?>">
                  <svg class="filters__icon" width="22" height="18">
                    <use xlink:href="#icon-filter-<?=$type['class_name']?>"></use>
                  </svg>
                  <span><?=$type['type']?></span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="adding-post__tab-content">
        <?=$blockContent?>
        </div>
      </div>
    </div>
  </div>
</main>