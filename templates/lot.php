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
<section class="lot-item container">
      <h2><?=htmlspecialchars($lot['name']) ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?=$lot['img'] ?>" width="730" height="548" alt="<?=htmlspecialchars($lot['name']) ?>">
          </div>
          <p class="lot-item__category">Категория: <span><?=$lot['category_title'] ?></span></p>
          <p class="lot-item__description"><?=htmlspecialchars($lot['description']) ?></p>
        </div>
        <div class="lot-item__right">
          <div class="lot-item__state">
            <div class="lot-item__timer timer">
              <?=get_time($lot['finish']) ?>
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=price_format($lot['price']) ?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?=price_format($lot['price']+$lot['amount_step']) ?></span>
              </div>
            </div>
            <?php if(!empty($user) && !isset($user['error'])): ?>
            <form class="lot-item__form" action="bet.php?id=<?=$lot['id'] ?>" method="post">
              <p class="lot-item__form-item">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="number" name="cost" placeholder="<?=price_format($lot['price']+$lot['amount_step']) ?>">
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
            <?php endif; ?>
          </div>
          <div class="history">
            <h3>История ставок (<span><?=count($bets)?></span>)</h3>
            <table class="history__list">
            <?php foreach($bets as $value):?>
            <tr class="history__item">
                <td class="history__name"><?=htmlspecialchars($value['name'])?></td>
                <td class="history__price"><?=price_format($value['amount'])?></td>
                <td class="history__time"><?=$value['reg_date']?></td>
              </tr>
            <?php endforeach; ?>
              
            </table>
          </div>
        </div>
      </div>
    </section>