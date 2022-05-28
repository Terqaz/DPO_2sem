$('#submitBtn').click(function(e){
    e.preventDefault();
    var address = document.form.address.value;

    $.ajax({
        url: 'getMetroAndPositionByAddress.php',
        type: 'GET',
        dataType: 'json',
        data: {
            address: address,
        },
        success: (function(data){
            let $result = $('#result');
            $result.empty();
            appendField($result,'Скорректированный адрес',  data.result.address);
            appendField($result,'Координаты',  data.result.position);
            appendField($result,'Ближайшее метро',  data.result.metro);
            $result.css('display', 'block');
        })
    })
});

function appendField($result, name, value) {
    $result.append('<p>' + name + ': ' + value +'</p>');
}