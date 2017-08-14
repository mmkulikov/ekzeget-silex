**Front controller**

Корень сайта (`DOCUMENT_ROOT`) отличается от корня проекта и расположен в папке web, дочерней папки корня проекта.
Сайт запускался как просто под apache, так и под связкой nginx+apache, в которой nginx обсулуживал статику, а apache - php.
Возможна любая другая популярная конфигурация. В данный же момент на тестовом хосте в корне сайта расположен файл .htaccess,
перенаправляющий все запросы скрипту  `/web/index.php`. Этот скрипт является единственной точкой входа. Больше в папке web скриптов нет, 
но есть статика. В общем, её название, стандартное для silex и symfony(у других фреймворков встречаются другие варианты, наприме
говорит за себя: в ней лежит то, что доступно напрямую из внешнего мира.

**Инициализация и настройки**

Сперва включается скрипт инициализации /init.php. В нём происходит следующее:
1. подключается автозагрузчик composer
composer не только помогает управлять зависимостями, но и пробует найти и включить используемые в коде классы, 
которые ещё не были определены в уже проанализированном интерпретатором коде.
В composer.json указано два пути автозагрузки: `propel-orm/generated-classes/`, где располагаются модели (вероятно в будущем не только сгенерированный propel и связанные с БД), и `src`, где лежат остальные классы.
2. загружаются настройки propel-orm
Это сгенерированный файл конфигурациии и инициализации, созданный на основе написанного вручную. Править можно только файл, написанный самостоятельно. После надо снова генерировать конфиг.
 Подробнее в документации.
3. инициализируется наследник Silex\Application
Можно в принципе и сам Silex\Application инстанциировать. Но в том и прелесть ООП, что мы можем расширять классы. На данный момент мой класс приложения отличается тем, что влючает примесь UrlGeneratorTrait, поставляемую с Silex.
4. подключаются настройки проекта
Настройки хранятся в папке `config`. Если существует файл debug.php, подключается он (с насройками для тестовой среды). Иначе подключается prod.php.
В этих файлах могут быть разного рода настройки. Но преимущественно это присвоения вида `$app['prop'] = 'value'` и ini_set. Отдельно отмечу 
настройку `$app['debug']`. Это пока единственная поставляемая с Silex настройка, которую я изменяю. Если она равна true, сообщения об исключениях отображаются с подробностями.
5. возвращается подготовленный ранее объект приложения
В предыдущих 2 шагах мы выбрали подходящую реализацию класса приложения и, посколько его объект является dic, засунули настройки приложения в него. Теперь возвращаем этот объект чисто ради наглядности.

Дальше регистрируются сервисы, регистрируется обработчик ошибок, подключаются посредники, запускается доделанный поверх Silex механизм маршрутизации и собственно запускается приложение.

**Сервисы**

Сервисы представляют собой разные программные компоненты, библиотеки, выополняющие одну задачу, и которые нельзя отнести к слою  и вообще к нашему конкретному приложения, 
 но могут понадобиться в любом слое приложения. То есть, соответственно названию, они обслуживают наше приложение.
Silex идёт с довольно большим набором сервисов. И есть ещё много готовых, не постовляемых из коробки. Также можно легко создавать свои.
Подробнее это описано в документации. 
В `/web/index.php` я и регистирирую все сервисы, которые в будущем могут понадобиться приложению.
Очень здорово то, что любой из используемых сервисов при необходимости можно будет унаследовать и зарегистрировать наследник, а приложение продлжит работать, как ни в чём не бывало.
Самописные сервисы хранятся в `/src/services`

 Сессии
 
 Локаль
 
 Переводы
 
 Загружаются из /src/strings.php
 
 Самописный сервис, дающий более короткий доступ к сервису переводов
 $app['t']->('string', ['substitute' => 'value'])
 
 Самописный сервис гибкого управления ссылками
 Массив ALL содержит все ссылки. Ключи должны быть говорящими названиями путей. 
 Первые части ключей должны соответствовать капитализированному названию контроллера, к которому относится ссылка.
 Значения массива соответствуют части пути после названия контроллера и содержат переменные согласно документации Silex.
 В дальнейшем для генерации ссылок и регистрации пути, нужно как раз использовать значения массива: URLS:ALL['URL_KEY'].
Пример генерации ссылки: $app->url(URLS:ALL['URL_KEY'], ['param_name' => 'value'])
Таким образом мы можем мгновенно изменить формат ссылок сразу везде: и в маршрутизации, и в перенаправлениях, и в html-ссылках на сайте, и, вероятно, в ACL. 
 
 Первый пробный самописный и не silex-way сервис View. Можно немножко переписать.
 Формы и Twig(для форм)
 
Все сервисы доступны через $app.


**Ошибки**

Silex по умолчанию отлавливает исключения и ошибки. Если $app['debug'] === true, выводит подробную информацию, иначе просто сообщение о том, что что-то пошло не так.
Для регистрации собственного обработчика ошибок предоставляется метод $app->error.
Я зарегистрировал в нём ErrorController::error. Тот же обработчик я передал в set_exception_handler, поскольку фатальные ошибки, преобразованные не в наследника Exception, но в наследника Error, не отлавливались.
Итак, если ошибка фатальная, я продолждаю её обрабатывать в ErrorController::fatalError.
К фатальным ошибкам в том числе относятся те, когда вызвается метод на значении null. В нашем случае это обычно происходит, когда proвpel orm ничего не находит и возвращает null. 
И вместо того, чтобы каждый раз проверять, не вернул ли propel null, я отлавливаю эту ошибку и преобразую её в 404. Некоторые против подобного подхода, но целое сообщество Питона точно за: "It's Better to Beg for Forgiveness than to Ask for Permission"
На самом деле, мы избавились от целого класса одинаковых проверок в каждом контроллере.
Если ошибка не фатальная, то дальше обрабатывается в зависиомсти от её кода. Пока обрабатываем только 404 код.


**Middlewares(посредники)**

Всё согласно документации.
На данный момент хранятся в одном файле `/src/middlewares.php`.
Пока есть один посредник, который на основе cookie и get определяет текущую версию перевода и записывает в `$app['bible_version']`.
В дальнейшем это значение используется во многих местах.


**Маршрутизация и контроллеры**

В документации есть раздел о группировке контрооллеров.
Я воспользовался возможностью монтировать фабрику контроллеров. 
Базовый контроллер содержит логику поиска других контроллеров.
В /web/index.php запускается весь механизм с помощью `Controller::routeRequest($app, $_SERVER['REQUEST_URI'])`
Из запроса выделяется первая часть пути /part1/part2/. Она  и является именем нашего контроллера. Все контроллеры лежат в папке `/src/controllers/`.
Если класс (и, согласно PSR-автозагрузке, файл) "ИмяКонтроллераController" существует, для него создаётся экземпляр фабрики контроллеров. 
Этот экзмепляр передааётся в метод контроллера defineActions. В этом методе к этой фабрике можно прикреплять сколько угодно адресов, всё согласно документации.
**Важно!** После отработки defineActions фабрика монтируется в $app по адресу названия контроллера. То есть если пользователь зашёл на /bible/foo/bar/, 
создаётся экземпляр контроллера BibleController, в его метод defineAction передаётся фабрика контроллера, в этом методе, вероятно, у мы назначили путь `$factory->get('/foo/bar/', function() {...})` или пользователь увидит 404, 
далее фабрика автоматически монтируется `$app->mount('/bible', $factory)`.
См. сервис гибкого управления ссылками
У данного подхода есть минусы и плюсы.

\+
+ Процесс маршрутизации быстр, поскольку добавляется на каждый запрос всего несколько путей - столько, сколько определено в defineActions текущего контроллера.
+ Легко писать контроллеры, всё удобно разделено по файлам. Достаточно создать класс контроллера - и он будет подгружаться, если будет запрошен.
+ Более коротко записаны ссылки

\-
+ defineAction - не очень красивое решение. Хорошо, если бы для кжадого "действия", был бы метод верхнего уровня, а не вложенный в другой метод. Но пока сойдёт. 



 **Перенаправления**
 
 Если Controller не может распознать имя контроллера - а на старом сайте как раз всё по именам файлов, не отделённых слэшем - он запускает `NotFoundController`.
 
 
 **Представления**
 
 У котнроллеров есть самописный метод `render(string $template, array $params) : string`.
 Он подгружает шаблон `$template` в папке `/src/views/$templateNameSpace`. 
 Свойство контроллера `$templateNameSpace` устанавливается автоматически и равно имени контроллера. Но можно и прописать кастомное значение, которое не затрётся.
 В шаблоне все параметры будут доступны, как переменные. То есть если `$params = ['foo' => 'bar']`, в шаблоне будет переменная `$foo` со значением "bar".
 Также в шаблоне доступен $this, указывающий на View. У View тот же самый метод render, соответственно можно внутри шаблона подключить ещё шаблон.
 Есть и `$app`. И вспомогательная переменная `$assets`, указывающая на путь к ресурсным файлам.
 Когда шаблон и подключённые в нём шаблоны отработали, подключается макет. Имя макета по умолчанию установлено в классе Controller: `protected $layout = 'main';`
 В любом наследнике его можно переопределить. Подгружается макет из `/src/views/layouts/$layout.php`.
 Макету доступны всё те же $this, $app, и $assets. Но ещё ему доступна переменная `$contents`.
 Значение этой переменной равно выводу отработавших шаблонов. Таким образом преедполагается выводить $contents меежду шапкой и подвалом.
 У объектов класса View есть также методы setProperty($name, $value) и getProperty($name), которые позволяют установить такие свойства, как title в шаблоне, чтобы потом вывести его в макете (макет ведь загружается последним).
 Результат отработки макета уже и возвращается render.
  
 **Assets**
 
 Можно положить все стили и скрипты в класс-наследник AssetBundle, вызвать в нужном шаблоне его метод register, и в месте шаблона или макета, где надо вывести все стили и скрипты, вызвать $this->assets().
 Регистрировать можно сразу несколько наборов ресурсов. 
 
 См. пример `/src/views/фыыуеы.LegacyMainAsset` `/src/views/layouts/main.php`.
 Также в классе можно обозначить массив $depends и передать в него имена классов, от которого зависят ресурсы данного. Тогда сначала подключатся ресурсы тех классов.
 
 
 **Виджеты**
 
 В архитектуре данного сайта очень важны.
 По сути виджет - визуальный блок со своей отдельной логикой и вёрсткой. 
 Расположены в /src/widgets и являются наследниками абстрактного класса Widget.
 Должны реализовать метод run, возвращающего результирующую строку. Есть метод render. Тоже передаёт в шаблон параметры, превращая их в переменные. Тоже передаёт $app, $this(ссылается на виджет!), $assets.
 Шаблоны виджетов хранятся в папке /src/widgets/views.
 Чтобы воспользоваться виджетом, достаточно вывести результат вызова его статического метода widget. Например: `echo News::widget(['foo' => 'bar'])`.
 В данном случае виджету будет доступно **свойство** $foo со значением "bar". В методе run его вполне можно использовать. Можно на его основе выполнить вычисления. Можно просто передать в шаблон виджета. А можно и не передавать - всё равно в шаблоне есть $this, 
 ссылающийся на виджет, через которой можно достать $foo - $this->foo. Но предпочтительнее передавать переменные, т.к. их можно описать аннотациями PhpDoc и среда разработки их распознает. 
 
 **Формы**
 
 Пока не очень ясно. Попробовал компонент Symfony. К сожалению, не удалось его отделить от Twig.
 Пример можно увдиеть в виджете VersionChooser.
  Для понимания нет необходимости изучать Twig. Нужно лишь изучить сервис форм. 
 
 
 **Модели**
 
 В первую очередь, это обёртки, сгенерированные propel-orm для доступа к БД в красивом ООП стиле. Но в них можно дописывать свой код.
 Желательно, чтобы он был связан с БД. Например, какие-то частые или длинные постороения запросов можно оформить в один хорошо названный метод.
 Для сложных вычислений, мало связанных с БД, желательно создавать в той же папке отдельные классы. 
 
**Внимание!** Пароль от базы не лежит в системе контроля версий. На каждом новом хосте нужно создавать вручную файл /propel-orm/db_credentials.conf
 
 **Тесты**
 
 Расположены в папке /src/tests
 Работают на основе Codeception+AspectMock.
 
 **Переезд**
 
 /moving.sql + /moving.php
 
 moving.php пока только создаёт аудиокаталог немного в новом формате и заменяет в базе некоторые "магические обозначения" или шорткоды, описанные отцом Сергием на странице правил, на html.
 
 Часть данных для переноса в базу может быть в мигразциях. Это большая часть "захардкоденных" значений.
 
 
 **Кэширование, логирование и прочии сквозные задачи**
 
 Всё это нужно будет к запуску боевой версии. Выделю в отдельные классы, вместо того чтобы разносить по всей сисеме, с помощью фреймворка GoAOP.