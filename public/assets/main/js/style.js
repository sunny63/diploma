var test = document.querySelectorAll(".part-description");

l = test.length;
console.log(l);
var size = 450;

for (let i = 0; i < l; i++) {
    test[i] = document.getElementsByClassName("test")[i];
    stringLength = test[i].textContent.length;

    if (stringLength > size) {
        oldText = test[i].textContent;
        newText = '';
        for (let j = 0; j < size; j++) {
            newText = newText + oldText[j];
        }
        test[i].textContent = newText + '...';
    }
}

function toggle(el) {
    el.style.display = (el.style.display == 'none') ? '' : 'none'
}

$('#exampleModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Кнопка, что спровоцировало модальное окно
    var recipient = button.data('whatever') // Извлечение информации из данных-* атрибутов
    // Если необходимо, вы могли бы начать здесь AJAX-запрос (и выполните обновление в обратного вызова).
    // Обновление модальное окно Контента. Мы будем использовать jQuery здесь, но вместо него можно использовать привязки данных библиотеки или других методов.
    var modal = $(this)
    modal.find('.modal-title').text('New message to ' + recipient)
    modal.find('.modal-body input').val(recipient)
})






// $().button('string')
rule = document.getElementById('showRules');
$(rule).button('complete');
//
// $('#showRules').on('click', function () {
//     $(this).button('complete');
// });
// // $('#reset').on('click', function () {
// //     $('#download').button('reset');
// // });
