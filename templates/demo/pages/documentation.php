<div class="row">
    <div class="col-lg-2">
        <h3>Базовые классы</h3>
        <ul>
            <li><a href="#/elements">Elements</a>
                <ul>
                    <li><a href="#/elements/get">get</a></li>
                    <li><a href="#/elements/set">set</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="col-lg-10">
        <ul class="classes">
            <li>
                <h1>Все есть Элемент</h1>
                Все данные системы организованы в элементы. Элементы разделены на типы. Категории, пользователи, текстовые блоки, товары - все это примеры типов элементов.
                Типы элементов организованы в древовидную структуру. Каждый тип наследует свойства своего родительского типа и дополняет их собственными.
                Так, например, дочерний от типа "товары" тип "пылесосы" будет содержать все свойства товара и, кроме того, свойсва характерные только для пылесосов: мощность, объем бака и т.п.
                Используя базовый класс Elements можно работать с любыми элементами системы. Все остальные классы созданы для удобства работы с конкретными типами элементов, они предопределяют параметры методов базового класса и дополняют его свойства.
                <h1><a name="/elements">Elements</a></h1>
                <div class="descr">Базовый класс. Все остальные классы являются расширениями данного класса.</div>
                <strong>Методы:</strong>
                <ul class="methods">
                    <li><h2><a name="/elements/get">get(params)</a></h2>
                        <div class="return"><span>Возвращает:</span> массив элементов</div>
                        <div class="desc">Выбор элементов указанного типа на основе параметров</div>
                        <ul class="params">
                            <li>
                                <h3>params</h3>
                                <div class="type"><span>Тип:</span> массив</div>
                                <div class="desc">
                                    Параметры выборки элементов:
                                    <ul>
                                        <li>
                                            <h5>type</h5>
                                            <div class="type"><span>Тип:</span> строка или целое число</div>
                                            <div class="default">Обязательный</div>
                                            <div class="desc">ID или имя типа элементов</div>
                                        </li>
                                        <li>
                                            <h5>filter</h5>
                                            <div class="type"><span>Тип:</span> массив</div>
                                            <div class="default"><span>По-умолчанию:</span> пустой массив</div>
                                            <div class="desc"><a href="#/elements/filter">Массив параметров фильтрации элементов</a></div>
                                        </li>
                                        <li>
                                            <h5>limit</h5>
                                            <div class="type"><span>Тип:</span> число</div>
                                            <div class="default"><span>По-умолчанию:</span> 0</div>
                                            <div class="desc">Лимит кол-ва возвращаемых элементов</div>
                                        </li>
                                        <li>
                                            <h5>page</h5>
                                            <div class="type"><span>Тип:</span> число</div>
                                            <div class="default"><span>По-умолчанию:</span> 0</div>
                                            <div class="desc">Номер страницы. Отсчет страниц ведется с 0</div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                        <div class="examples">
                            <h3>Примеры</h3>
                                <pre>
/*
Выборка первых 30 велосипедов
с 18-ым размером рамы
в наличии на складе в Москве или Перми
любой макри кроме GT
*/
$elements=Elements::get('bikes',array(
    'limit'=>30,
    'filter'=>'frame_size>18 & (store_moskow >1|store_perm>1)&brand!=GT'
));

/* Сортировать посты авторов с рейтингом больше 100 по кол-ву комментариев */
$elements=Elements::get('posts',array(
    'limit'=>30,
    'select'=>'posts_comments.count(*) as comments_cnt',
    'sort'=>'comments_cnt',
    'filter'=>'users.rate>100'
));

                                </pre>
                        </div>
                    </li>
                    <li><h2><a name="/elements/set">getById(element_id)</a></h2>
                        <div class="return"><span>Возвращает:</span> элемент</div>
                        <div class="desc">Возвращает элемент по его идентификатору</div>
                        <ul class="params">
                            <li>
                                <h3>element_id</h3>
                                <div class="type"><span>Тип:</span> целое</div>
                                <div class="desc">ID элемента</div>
                            </li>
                        </ul>
                        <div class="examples">
                            <h3>Примеры</h3>
                                <pre>
$lilu=Elements::getById(5);
                                </pre>
                        </div>
                    </li>
                    <li><h2><a name="/elements/set">set(params)</a></h2>
                        <div class="return"><span>Возвращает:</span> истина или id добавленного элемента</div>
                        <div class="desc">Редактирование/добавление элемента</div>
                        <ul class="params">
                            <li>
                                <h3>params</h3>
                                <div class="type"><span>Тип:</span> массив</div>
                                <div class="desc">
                                    Параметры редактирования/добавления элемента:
                                    <ul>
                                        <li>
                                            <h5>type</h5>
                                            <div class="type"><span>Тип:</span> число</div>
                                            <div class="default">Обязательный</div>
                                            <div class="desc">Идентификатор типа элемента</div>
                                        </li>
                                        <li>
                                            <h5>id</h5>
                                            <div class="type"><span>Тип:</span> число</div>
                                            <div class="default"><span>По-умолчанию:</span> 0</div>
                                            <div class="desc">Идентификатор редактируемого элемента. Если не задан, добавляется новый элемент.</div>
                                        </li>
                                        <li>
                                            <h5>fields</h5>
                                            <div class="type"><span>Тип:</span> массив</div>
                                            <div class="default"><span>По-умолчанию:</span> пустой массив</div>
                                            <div class="desc">Массив любых <a href="#/elements/fullType">параметров полного типа</a> элемента вида 'параметр'=>'значение'</div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li><h2><a name="/elements/set">delete(element_id)</a></h2>
                        <div class="return"><span>Возвращает:</span> истина</div>
                        <div class="desc">Удаляет элемент</div>
                        <ul class="params">
                            <li>
                                <h3>element_id</h3>
                                <div class="type"><span>Тип:</span> целое</div>
                                <div class="desc">ID элемента</div>
                            </li>
                        </ul>
                    </li>
                    <li><h2><a name="/elements/set">getType(type)</a></h2>
                        <div class="return"><span>Возвращает:</span> массив</div>
                        <div class="desc">Возвращает тип эелементов по его имени или идентификатору</div>
                        <ul class="params">
                            <li>
                                <h3>element_id</h3>
                                <div class="type"><span>Тип:</span> целое или строка</div>
                                <div class="desc">Имя или ID типа элементов</div>
                            </li>
                        </ul>
                    </li>
                    <li><h2><a name="/elements/set">getFullType(type)</a></h2>
                        <div class="return"><span>Возвращает:</span> массив</div>
                        <div class="desc">Возвращает массив типов в порядке от корневого до заданного</div>
                        <ul class="params">
                            <li>
                                <h3>type</h3>
                                <div class="type"><span>Тип:</span> целое или строка</div>
                                <div class="desc">Имя или ID типа</div>
                            </li>
                        </ul>
                    </li>
                    <li><h2><a name="/elements/set">getTypeFields(type)</a></h2>
                        <div class="return"><span>Возвращает:</span> массив</div>
                        <div class="desc">Возвращает массив полей типа где у каждого поля указано:
                            <ul>
                            <li>Field - имя поля</li>
                                <li>Type - тип поля в базе данных</li>
                                <li>Collation - сравнение</li>
                                <li>Null - может ли принимать значение NULL</li>
                                <li>Key - имя ключа (если задан)</li>
                                <li>Default - значение по-умолчанию</li>
                                <li>Extra - дополнительные данные</li>
                                <li>Privileges - привилегии</li>
                                <li>Comment - комментарий</li>
                                <li>FK - имя таблицы на первичный ключ которой ссылается поле (если ему задан вторичный ключ)</li>
                            </ul>
                        </div>
                        <ul class="params">
                            <li>
                                <h3>type</h3>
                                <div class="type"><span>Тип:</span> целое или строка</div>
                                <div class="desc">Имя или ID типа</div>
                            </li>
                        </ul>
                    </li>
                    <li><h2><a name="/elements/set">getElementType(element_id)</a></h2>
                        <div class="return"><span>Возвращает:</span> массив</div>
                        <div class="desc">Возвращает тип элемента по его идентификатору</div>
                        <ul class="params">
                            <li>
                                <h3>element_id</h3>
                                <div class="type"><span>Тип:</span> целое</div>
                                <div class="desc">ID элемента</div>
                            </li>
                        </ul>
                    </li>
                    <li><h2><a name="/elements/set">translate(text,[lang])</a></h2>
                        <div class="return"><span>Возвращает:</span> строка</div>
                        <div class="desc">Возвращает заранее заданный перевод текста на любом из доступных в интерфейсе языков на текущий язык интерфейса</div>
                        <ul class="params">
                            <li>
                                <h3>text</h3>
                                <div class="type"><span>Тип:</span> строка</div>
                                <div class="desc">Текст на любом языке из доступных в интерфейсе</div>
                            </li>
                            <li>
                                <h3>lang</h3>
                                <div class="type"><span>Тип:</span> строка</div>
                                <div class="desc">Сокращение названия языка на который необходимо перевести текст</div>
                            </li>
                        </ul>
                    </li>

                </ul>
                <strong>Свойства:</strong>
            </li>
    </div>

</div>
</div>