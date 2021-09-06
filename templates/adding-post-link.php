<section class="adding-post__link tabs__content tabs__content--active">
  <h2 class="visually-hidden">Форма добавления ссылки</h2>
  <form class="adding-post__form form" action="" method="post">
    <div class="form__text-inputs-wrapper">
      <div class="form__text-inputs">
        <div class="adding-post__input-wrapper form__input-wrapper
          <?php if(isset($errors['link-title'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <label class="adding-post__label form__label" for="link-heading">Заголовок <span class="form__input-required">*</span></label>
          <div class="form__input-section">
            <input class="adding-post__input form__input" id="link-heading" type="text" name="link-title" placeholder="Введите заголовок" value="<?=getPostValue('link-title');?>">
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['link-title']) echo $errors['link-title'];?>
              </p>
            </div>
          </div>
        </div>
        <div class="adding-post__textarea-wrapper form__input-wrapper
          <?php if(isset($errors['link-url'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <label class="adding-post__label form__label" for="post-link">Ссылка <span class="form__input-required">*</span></label>
          <div class="form__input-section">
            <input class="adding-post__input form__input" id="post-link" type="text" name="link-url" value="<?=getPostValue('link-url');?>">
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['link-url']) echo $errors['link-url'];?>
              </p>
            </div>
          </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper
          <?php if(isset($errors['link-tag'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <label class="adding-post__label form__label" for="link-tags">Теги</label>
          <div class="form__input-section">
            <input class="adding-post__input form__input" id="link-tags" type="text" name="link-tag" placeholder="Введите ссылку" value="<?=getPostValue('link-tag');?>">
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['link-tag']) echo $errors['link-tag'];?>
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="form__invalid-block">
        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
        <ul class="form__invalid-list">
          <?php foreach($modErrors as $title => $error): ?>
            <li class="form__invalid-item"><?=$title . '. ' .$error?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <div class="adding-post__buttons">
      <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
      <a class="adding-post__close" href="#">Закрыть</a>
    </div>
  </form>
</section>