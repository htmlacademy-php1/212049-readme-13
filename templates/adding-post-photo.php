<section class="adding-post__photo tabs__content tabs__content--active">
  <h2 class="visually-hidden">Форма добавления фото</h2>
  <form class="adding-post__form form" action="add.php" method="post" enctype="multipart/form-data">
    <div class="form__text-inputs-wrapper">
      <div class="form__text-inputs">
        <div class="adding-post__input-wrapper form__input-wrapper 
          <?php if(isset($errors['photo-title'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <input class="visually-hidden" type="text" name="content-type['photo']">
          <label class="adding-post__label form__label" for="photo-heading">Заголовок <span class="form__input-required">*</span></label>
          <div class="form__input-section">
            <input class="adding-post__input form__input" id="photo-heading" type="text" name="photo-title" placeholder="Введите заголовок" value="<?=getPostValue('photo-title');?>">
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['photo-title']) echo $errors['photo-title'];?>
              </p>
            </div>
          </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper
          <?php if(isset($errors['photo-url'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>
          <div class="form__input-section">
            <input class="adding-post__input form__input" id="photo-url" type="text" name="photo-url" placeholder="Введите ссылку" value="<?=getPostValue('photo-url');?>">
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['photo-url']) echo $errors['photo-url'];?>
              </p>
            </div>
          </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper
          <?php if(isset($errors['photo-tag'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <label class="adding-post__label form__label" for="photo-tags">Теги</label>
          <div class="form__input-section">
            <input class="adding-post__input form__input" id="photo-tags" type="text" name="photo-tag" placeholder="Введите теги" value="<?=getPostValue('photo-tag');?>">
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['photo-tag']) echo $errors['photo-tag'];?>
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
    <div class="adding-post__input-file-container form__input-container form__input-container--file">
      <div class="adding-post__input-file-wrapper form__input-file-wrapper">
        <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
          <input class="adding-post__input-file form__input-file" id="userpic-file-photo" type="file" name="userpic-file-photo" title=" ">
          <div class="form__file-zone-text">
            <span>Перетащите фото сюда</span>
          </div>
        </div>
      </div>
      <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">

      </div>
    </div>
    <div class="adding-post__buttons">
      <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
      <a class="adding-post__close" href="#">Закрыть</a>
    </div>
  </form>
</section>