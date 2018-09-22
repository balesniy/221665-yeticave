<section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
            <?php foreach($categories as $value):?>
                <li class="promo__item promo__item--<?=$value['promo_class'] ?>">
                    <a class="promo__link" href="pages/all-lots.html"><?=$value['title'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <!--заполните этот список из массива с товарами-->
            <?php foreach($lots as $lot):?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?=$lot['picture'] ?>" width="350" height="260" alt="<?=htmlspecialchars($lot['title']) ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=$lot['category'] ?></span>
                        <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?=htmlspecialchars($lot['title']) ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?=price_format($lot['price']) ?></span>
                            </div>
                            <div class="lot__timer timer">
                                <?=get_time() ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>