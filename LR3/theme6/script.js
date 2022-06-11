// Ограницения полей формы
const CONSTRAINTS = {
    fio: {
        regex: /^([А-ЯЁ][а-яё]{1,32} ){2}([А-ЯЁ][а-яё]{1,32})?$/u,
        maxLength: 200
    },
    email: {
        regex: /^[\dA-Za-z][.-_\dA-Za-z]+[\dA-Za-z]?@([-\dA-Za-z]+\.){1,2}[-A-Za-z]{2,7}$/,
        maxLength: 128
    },
    phone: {
        regex: /^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/
    },
    comment: {
        maxLength: 2048
    }
}

// Обработка события отправки формы
$(document.form).on('submit', function (e) {
    // Предотвращаем стандартное поведение
    e.preventDefault();

    // Валидируем поля
    let isAllValid = true;
    $('.form .form__input').each(function (i, input) {
        if (isValid(input)) {
            input.style.border = '1px solid darkcyan';
        } else {
            input.style.border = '2px solid red';
            isAllValid = false;
        }
    });

    if (isAllValid) {
        // Отправляем запрос на сохранение обращения
        $.ajax({
            url: "process-form.php",
            type: 'POST',
            dataType: 'json',
            data: createBody(document.form),
            success: function (data) {
                if (data.success) {
                    // Показываем пользователю сохраненные данные обращения
                    let form = document.form;
                    let $message = $('#message');

                    $(form).css('display', 'none');

                    let [lastName, firstName, middleName] = form.fio.value.split(' ')
                    appendField($message, 'Фамилия', lastName);
                    appendField($message, 'Имя', firstName);
                    appendField($message, 'Отчество', middleName);
                    
                    appendField($message, 'Ваш email', form.email.value);
                    appendField($message, 'Ваш телефон', form.phone.value);
                    appendField($message, 'С Вами свяжутся после', data.contactTime);

                    $message.css('display', 'block');

                } else {
                    // Выводим ошибку на форму
                    let $formError = $('#formError');
                    $formError.text(data.error);
                    $formError.css('display', 'block');
                }
            }
        });
    }
});

/** Проверка валидности поля
 * @param input - DOM-элемент поля ввода формы
 * @return bool - валидное ли поле
 */
function isValid(input) {
    let constraints = CONSTRAINTS[input.name];
    if (!constraints) {
        return false;
    } else if (constraints.maxLength && input.value.length > constraints.maxLength) {
        return false;
    } else if (constraints.regex && !constraints.regex.test(input.value)) {
        return false;
    }
    return true;
}

/** Создаем тело запроса отправки формы с данными из формы
 * @param input - DOM-элемент поля ввода формы
 * @return bool - валидное ли поле
 */
function createBody() {
    let data = {};
    $('.form .form__input').each(function (i, input) {
        data[input.name] = input.value;
    });
    return data;
}

/** Добавить поле для вывода данных отправленной формы пользователя
 * @param $message - jquery-элемент для вставки поля в конец
 * @param name - имя поля
 * @param value - значение поля
 */
function appendField($message, name, value) {
    $message.append('<p>' + name + ': ' + value +'</p>');
}
