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
    <script>
        $('a.buy').click(function(e){
            $.ajax({
                url: '/index.php?section=cartajax&mode=short&add='+$(this).attr('data-offer'),
                context: this,
                success: function(data, status) {
                    $(this).html('Добавлено');
                    $(this).css('background', 'green');
                    $('.cart .inner').html(data);
                }
            });
            e.preventDefault();
        });

        $('form[data-async]').unbind().submit(function(event) {
            var form = $(this);
            var target = $(form.attr('data-target'));
            console.log(form.serialize());
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),

                success: function(data, status) {
                    target.html(data);
                }
            });
            event.preventDefault();
        });

        $.fn.preload = function() {
            this.each(function(){
                $('<img/>')[0].src = this;
            });
        }
        $(['/templates/psc/static/images/ajax-loader.gif','/templates/psc/static/images/ajax-loader_small.gif']).preload();
    </script>
</div>
</div>
</body>
</html>