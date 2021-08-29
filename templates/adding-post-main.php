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
                  <?php if($type['class_name'] === $filterButtonActive): ?>
                    filters__button--active tabs__item--active
                  <?php endif; ?>
                  tabs__item button" href="#">
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
          <section class="adding-post__photo tabs__content tabs__content--active">
            <h2 class="visually-hidden">Форма добавления фото</h2>
            <form class="adding-post__form form" action="add.php" method="post" enctype="multipart/form-data">
              <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                  <div class="adding-post__input-wrapper form__input-wrapper 
                    <?php if($errors['photo-title']): ?>
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
                    <?php if($errors['photo-url']): ?>
                      form__input-section--error
                    <? endif; ?>
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
                    <?php if($errors['photo-tag']): ?>
                      form__input-section--error
                    <? endif; ?>
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
                    <? endforeach; ?>
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

          <section class="adding-post__video tabs__content">
            <h2 class="visually-hidden">Форма добавления видео</h2>
            <form class="adding-post__form form" action="#" method="post" enctype="multipart/form-data">
              <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                  <div class="adding-post__input-wrapper form__input-wrapper
                    <?php if($errors['video-title']): ?>
                      form__input-section--error
                    <? endif; ?>
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
                    <?php if($errors['video-url']): ?>
                      form__input-section--error
                    <? endif; ?>
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
                    <?php if($errors['video-tag']): ?>
                      form__input-section--error
                    <? endif; ?>
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
                    <? endforeach; ?>
                  </ul>
                </div>
              </div>

              <div class="adding-post__buttons">
                <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                <a class="adding-post__close" href="#">Закрыть</a>
              </div>
            </form>
          </section>

          <section class="adding-post__text tabs__content">
            <h2 class="visually-hidden">Форма добавления текста</h2>
            <form class="adding-post__form form" action="add.php" method="post">
              <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                  <div class="adding-post__input-wrapper form__input-wrapper
                    <?php if($errors['text-title']): ?>
                      form__input-section--error
                    <? endif; ?>
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
                    <?php if($errors['text-content']): ?>
                      form__input-section--error
                    <? endif; ?>
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
                    <?php if($errors['text-tag']): ?>
                      form__input-section--error
                    <? endif; ?>
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
                    <? endforeach; ?>
                  </ul>
                </div>
              </div>
              <div class="adding-post__buttons">
                <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                <a class="adding-post__close" href="#">Закрыть</a>
              </div>
            </form>
          </section>

          <section class="adding-post__quote tabs__content">
            <h2 class="visually-hidden">Форма добавления цитаты</h2>
            <form class="adding-post__form form" action="#" method="post">
              <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                  <div class="adding-post__input-wrapper form__input-wrapper
                    <?php if($errors['quote-title']): ?>
                      form__input-section--error
                    <? endif; ?>
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
                    <?php if($errors['quote-content']): ?>
                      form__input-section--error
                    <? endif; ?>
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
                    <?php if($errors['quote-author']): ?>
                      form__input-section--error
                    <? endif; ?>
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
                    <?php if($errors['quote-tag']): ?>
                      form__input-section--error
                    <? endif; ?>
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
                    <? endforeach; ?>
                  </ul>
                </div>
              </div>
              <div class="adding-post__buttons">
                <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                <a class="adding-post__close" href="#">Закрыть</a>
              </div>
            </form>
          </section>

          <section class="adding-post__link tabs__content">
            <h2 class="visually-hidden">Форма добавления ссылки</h2>
            <form class="adding-post__form form" action="#" method="post">
              <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                  <div class="adding-post__input-wrapper form__input-wrapper
                    <?php if($errors['link-title']): ?>
                      form__input-section--error
                    <? endif; ?>
                    ">
                    <input class="visually-hidden" type="text" name="content-type['link']">
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
                    <?php if($errors['link-url']): ?>
                      form__input-section--error
                    <? endif; ?>
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
                    <?php if($errors['link-tag']): ?>
                      form__input-section--error
                    <? endif; ?>
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
                    <? endforeach; ?>
                  </ul>
                </div>
              </div>
              <div class="adding-post__buttons">
                <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                <a class="adding-post__close" href="#">Закрыть</a>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </div>
</main>