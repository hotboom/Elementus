</div>
<div class="partners">
    <img src="/templates/psc/static/images/partners.png">
</div>
<div class="footer">

    <div class="fblock copyright">
        <img src="/templates/psc/static/images/logo_footer.png">
        <br>
        © «Первый Строй Центр»
    </div>
    <? $cities=E::get('city');
    foreach($cities as $city):?>
    <div class="fblock cotacts">
            <h3><?=$city['name']?></h3>
            <?=$city['contacts']?>
    </div>
    <? endforeach; ?>
    <div class="fblock info">
        <h3>Информация</h3>
        <ul>
            <li>О компании</li>
            <li>Вакансии</li>
            <li>Пользовательское соглашение</li>
            <li>Карта сайта</li>
        </ul>
    </div>
    <br class="clear" />
</div>
</div>
</body>
</html>