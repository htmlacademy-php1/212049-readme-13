<section class="adding-post__quote tabs__content tabs__content--active">
  <h2 class="visually-hidden">Форма добавления цитаты</h2>
  <form class="adding-post__form form" action="add.php" method="post">
    <div class="form__text-inputs-wrapper">
      <div class="form__text-inputs">
        <div class="adding-post__input-wrapper form__input-wrapper
          <?php if(isset($errors['quote-title'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <input class="visually-hidden" type="text" name="content-type['quote']">
          <label class="adding-post__label form__label" for="quote-heading">Заголовок <span class="form__input-required">*</span></label>
          <div class="form__input-section">
            <input class="adding-post__input form__input" id="quote-heading" type="text" name="quote-title" placeholder="Введите заголовок" value="<?=getPostValue('quote-title');?>">
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['quote-title']) echo $errors['quote-title'];?>
              </p>
            </div>
          </div>
        </div>
        <div class="adding-post__input-wrapper form__textarea-wrapper
          <?php if(isset($errors['quote-content'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <label class="adding-post__label form__label" for="cite-text">Текст цитаты <span class="form__input-required">*</span></label>
          <div class="form__input-section">
            <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input" id="cite-text" name="quote-content" placeholder="Текст цитаты" value="<?=getPostValue('quote-content');?>"></textarea>
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['quote-content']) echo $errors['quote-content'];?>
              </p>
            </div>
          </div>
        </div>
        <div class="adding-post__textarea-wrapper form__input-wrapper
          <?php if(isset($errors['quote-author'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <label class="adding-post__label form__label" for="quote-author">Автор <span class="form__input-required">*</span></label>
          <div class="form__input-section">
            <input class="adding-post__input form__input" id="quote-author" type="text" name="quote-author" value="<?=getPostValue('quote-author');?>">
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['quote-author']) echo $errors['quote-author'];?>
              </p>
            </div>
          </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper
          <?php if(isset($errors['quote-tag'])): ?>
            form__input-section--error
          <?php endif; ?>
          ">
          <label class="adding-post__label form__label" for="cite-tags">Теги</label>
          <div class="form__input-section">
            <input class="adding-post__input form__input" id="cite-tags" type="text" name="quote-tag" placeholder="Введите теги" value="<?=getPostValue('quote-tag');?>">
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Заголовок сообщения</h3>
              <p class="form__error-desc">
                <?php if($errors['quote-tag']) echo $errors['quote-tag'];?>
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