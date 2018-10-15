<nav class="nav">
    <ul class="nav__list container">
        <!--заполните этот список из массива категорий-->
        <?php foreach($categories as $value):?>
            <li class="nav__item">
            <a href="/?category=<?=$value['category_id'] ?>"><?=$value['title'] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
    <form class="form form--add-lot container <?=count($errors) ? 'form--invalid' : '' ?>" action="add.php" method="post" enctype="multipart/form-data">
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <div class="form__item <?=isset($errors['lot-name']) ? 'form__item--invalid' : '' ?>">
          <label for="lot-name">Наименование</label>
          <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?=isset($lot['lot-name']) ? $lot['lot-name'] : '' ?>">
          <span class="form__error">Введите наименование лота</span>
        </div>
        <div class="form__item <?=isset($errors['category']) ? 'form__item--invalid' : '' ?>">
          <label for="category">Категория</label>
          <select id="category" name="category" required>
            <option>Выберите категорию</option>
            <?php foreach($categories as $value):?>
                <option value="<?=$value['id'] ?>" <?=isset($lot['category'])&&$lot['category'] == $value['id']? 'selected' : '' ?>><?=$value['title'] ?></option>
            <?php endforeach; ?>
          </select>
          <span class="form__error">Выберите категорию</span>
        </div>
      </div>
      <div class="form__item form__item--wide <?=isset($errors['message']) ? 'form__item--invalid' : '' ?>">
        <label for="message">Описание</label>
        <textarea id="message" name="message" placeholder="Напишите описание лота"><?=
            isset($lot['message']) ? $lot['message'] : '' 
        ?></textarea>
        <span class="form__error">Напишите описание лота</span>
      </div>
      <div class="form__item form__item--file <?=isset($errors['file']) ? 'form__item--invalid' : '' ?>"> <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
          <button class="preview__remove" type="button">x</button>
          <div class="preview__img">
            <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
          </div>
        </div>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" id="photo2" name="gif_img">
          <label for="photo2">
            <span>+ Добавить</span>
          </label>
        </div>
        <span class="form__error"><?=isset($errors['file']) ? $errors['file'] : '' ?></span>
      </div>
      <div class="form__container-three">
        <div class="form__item form__item--small <?=isset($errors['lot-rate']) ? 'form__item--invalid' : '' ?>">
          <label for="lot-rate">Начальная цена</label>
          <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?=isset($lot['lot-rate']) ? $lot['lot-rate'] : '' ?>">
          <span class="form__error"><?=isset($errors['lot-rate']) ? $errors['lot-rate'] : '' ?></span>
        </div>
        <div class="form__item form__item--small <?=isset($errors['lot-step']) ? 'form__item--invalid' : '' ?>">
          <label for="lot-step">Шаг ставки</label>
          <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?=isset($lot['lot-step']) ? $lot['lot-step'] : '' ?>">
          <span class="form__error"><?=isset($errors['lot-step']) ? $errors['lot-step'] : '' ?></span>
        </div>
        <div class="form__item <?=isset($errors['lot-date']) ? 'form__item--invalid' : '' ?>">
          <label for="lot-date">Дата окончания торгов</label>
          <input class="form__input-date" id="lot-date" type="date" name="lot-date" value="<?=isset($lot['lot-date']) ? $lot['lot-date'] : '' ?>">
          <span class="form__error">Введите дату завершения торгов</span>
        </div>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Добавить лот</button>
    </form>