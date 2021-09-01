<section class="adding-post__video tabs__content tabs__content--active">
  <h2 class="visually-hidden">Форма добавления видео</h2>
  <form class="adding-post__form form" action="add.php" method="post" enctype="multipart/form-data">
    <div class="form__text-inputs-wrapper">
      <div class="form__text-inputs">
        <div class="adding-post__input-wrapper form__input-wrapper
          <?php if(isset($errors['video-title'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <input class="visually-hidden" type="text" name="content-type['video']">
          <label class="adding-post__label form__label" for="video-heading">Заголовок <span class="form__input-required">*</span></label>
          <div class="form__input-section">
            <input class="adding-post__input form__input" id="video-heading" type="text" name="video-title" placeholder="Введите заголовок" value="<?=getPostValue('video-title');?>">
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['video-title']) echo $errors['video-title'];?>
              </p>
            </div>
          </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper
          <?php if(isset($errors['video-url'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <label class="adding-post__label form__label" for="video-url">Ссылка youtube <span class="form__input-required">*</span></label>
          <div class="form__input-section">
            <input class="adding-post__input form__input" id="video-url" type="text" name="video-url" placeholder="Введите ссылку" value="<?=getPostValue('video-url');?>">
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['video-url']) echo $errors['video-url'];?>
              </p>
            </div>
          </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper
          <?php if(isset($errors['video-tag'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <label class="adding-post__label form__label" for="video-tags">Теги</label>
          <div class="form__input-section">
            <input class="adding-post__input form__input" id="video-tags" type="text" name="video-tag" placeholder="Введите ссылку" value="<?=getPostValue('video-tag');?>">
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['video-tag']) echo $errors['video-tag'];?>
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