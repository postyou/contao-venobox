/**
 * List wizard
 *
 * @param {object} el The DOM element
 * @param {string} command The command name
 * @param {string} id The ID of the target element
 */
function myListWizard(el, command, id, name) {
    var list = $(id),
        parent = $(el).getParent('li'),
        items = list.getChildren(),
        tabindex = parseInt(list.get('data-tabindex')),
        input, previous, next, rows, i, j;
    Backend.getScrollOffset();
    switch (command) {
        case 'copy':
            var clone = parent.clone(true).inject(parent, 'after');
            var i=getIndex(clone);
            var copybaleChildren = clone.getElementsByClassName('copybale');
            var textFieldsNumber = 0;
            for (j = 0; j < copybaleChildren.length; j++) {
                if (copybaleChildren[j].nodeName == 'LABEL') {
                    copybaleChildren[j].htmlFor  = name + '[' + i + ']' + '[' + textFieldsNumber + ']';
                }
                if (copybaleChildren[j].nodeName == 'INPUT' || copybaleChildren[j].nodeName == 'SELECT') {
//                                copybaleChildren[j].set('data-tabindex', i + 1);
                    copybaleChildren[j].name = name + '[' + i + ']' + '[' + textFieldsNumber + ']';
                    textFieldsNumber++;
                }
            }
            var noCopybaleChildren = clone.getElementsByClassName('deleteValOnCopy');
            for (j = 0; j < noCopybaleChildren.length; j++) {
                noCopybaleChildren[j].innerHTML = "";
            }
//                    }
            break;
        case 'up':
            if (previous = parent.getPrevious('li')) {
                parent.inject(previous, 'before');
            } else {
                parent.inject(list, 'bottom');
            }
            break;
        case 'down':
            if (next = parent.getNext('li')) {
                parent.inject(next, 'after');
            } else {
                parent.inject(list.getFirst('li'), 'before');
            }
            break;
        case 'delete':
            if (items.length > 1) {
                parent.destroy();
            } else {
                lastOne = list.getChildren()[0];
                child_Length = lastOne.childNodes.length;
                for (i = 0; i < child_Length; i++) {
                    if (lastOne.childNodes[i].nodeName == 'INPUT')
                        lastOne.childNodes[i].set('value', '');
                }
            }
            break;
    }
    new Sortables(list, {
        contstrain: true,
        opacity: 0.6,
        handle: '.drag-handle'
    });
}
function getIndex(li) {
    var lis = li.parentNode.getElementsByTagName('li');
    for (var i = 0, len = lis.length; i < len; i++) {
        if (li === lis[i]) {
            return i;
        }
    }

}
