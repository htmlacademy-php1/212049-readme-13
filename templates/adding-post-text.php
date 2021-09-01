<section class="adding-post__text tabs__content tabs__content--active">
  <h2 class="visually-hidden">Форма добавления текста</h2>
  <form class="adding-post__form form" action="add.php" method="post">
    <div class="form__text-inputs-wrapper">
      <div class="form__text-inputs">
        <div class="adding-post__input-wrapper form__input-wrapper
          <?php if(isset($errors['text-title'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <input class="visually-hidden" type="text" name="content-type['text']">
          <label class="adding-post__label form__label" for="text-heading">Заголовок <span class="form__input-required">*</span></label>
          <div class="form__input-section">
            <input class="adding-post__input form__input" id="text-heading" type="text" name="text-title" placeholder="Введите заголовок" value="<?=getPostValue('text-title');?>">
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['text-title']) echo $errors['text-title'];?>
              </p>
            </div>
          </div>
        </div>
        <div class="adding-post__textarea-wrapper form__textarea-wrapper
          <?php if(isset($errors['text-content'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <label class="adding-post__label form__label" for="post-text">Текст поста <span class="form__input-required">*</span></label>
          <div class="form__input-section">
            <textarea class="adding-post__textarea form__textarea form__input" id="post-text" name="text-content" placeholder="Введите текст публикации" value="<?=getPostValue('text-content');?>"></textarea>
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['text-content']) echo $errors['text-content'];?>
              </p>
            </div>
          </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper
          <?php if(isset($errors['text-tag'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <label class="adding-post__label form__label" for="post-tags">Теги</label>
          <div class="form__input-section">
            <input class="adding-post__input form__input" id="post-tags" type="text" name="text-tag" placeholder="Введите теги" value="<?=getPostValue('text-tag');?>">
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['text-tag']) echo $errors['text-tag'];?>
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