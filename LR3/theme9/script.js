// Обработка события клика кнопки
$('#submitBtn').click(function(e){
    // Предотвращаем стандартное поведение
    e.preventDefault();

    var address = document.form.address.value;

    $.ajax({
        // Отправляем запрос на получение адреса метро
        url: 'getMetroAndPositionByAddress.php',
        type: 'GET',
        dataType: 'json',
        data: {
            address: address,
        },
        success: (function(data){
            // Показываем адрес пользователю
            let $result = $('#result');
            $result.empty();

            appendField($result,'Скорректированный адрес',  data.result.address);
            appendField($result,'Координаты',  data.result.position);
            appendField($result,'Ближайшее метро',  data.result.metro);
            
            $result.css('display', 'block');
        })
    })
});

/** Добавить поле для вывода данных о метро пользователю
 * @param $message - jquery-элемент для вставки поля в конец
 * @param name - имя поля
 * @param value - значение поля
 */
function appendField($result, name, value) {
    $result.append('<p>' + name + ': ' + value +'</p>');
}