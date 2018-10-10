# EasySlug (easy creation of slugs for Laravel 5+)
[![Packagist License](https://poser.pugx.org/daaner/easy-slug/license.png)](http://choosealicense.com/licenses/mit/)
[![Latest Stable Version](https://poser.pugx.org/daaner/easy-slug/v/stable)](https://packagist.org/packages/daaner/easy-slug)
[![Total Downloads](https://poser.pugx.org/daaner/easy-slug/downloads)](https://packagist.org/packages/daaner/easy-slug)

## About
[ru] Простое создание уникального слага для Ларавел 5+

[en] Simple creation of a unique slug for Laravel 5+

## Установка

`composer require daaner/easy-slug`

В нужном месте подключаем класс `use EasySlug\EasySlug;`


## Использование

[ru]
- Добавляем мутатор в нужную модель (или базовую модель)
- Первый параметр `$value` - наше значение
- Второй параметр `slug` - поле, в котором хранится наш слаг
- Третий параметр `custom_field` - поле из которого будет формироваться слаг

ЗЫ: Если Вы хотите формировать слаг из полей `title` или `name` - третий параметр можно не указывать (если находит `title` формирует сперва из него, если нет - ищет `name`). Если третий параметр не указан или не найден - формируется слаг `'slug_'. date("Y-m-d-H-i-s")`, проверяется на уникальность и, если есть совпадения, дописывает первое уникальное число через дефис.


[en]
- Add a mutator to the desired model (or base model)
- The first parameter `$value` is our value
- The second parameter `slug` is the field in which our slug is stored
- The third parameter `custom_field` is the field from which the slug will be formed

PS: If you want to form a slug from the `title` or` name` fields - the third parameter can be null (if it finds `title` forms first of it, if not, it searches for` name`). If the third parameter is not specified or not found, the slug formed `'slug_'. date("Y-m-d-H-i-s")`, is checked for uniqueness and, if there are matches, appends the first unique number with a hyphen.


```
public function setSlugAttribute($value) {

  $this->EasySlugCheck($value, 'slug', 'custom_field');

}
```


## Контакты
https://t.me/neodaan по всем вопросам


## License
EasySlug is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
