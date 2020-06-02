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