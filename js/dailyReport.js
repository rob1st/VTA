(function() {
    // TODO: make a fn to prevent more than 2-3 lines being added if lines are left empty
    
    // this counter will be used to count input lines
    let count = 1;
    
    // add ev listeners on default (first) rendered line
    document.getElementById('selectEquipPersons_1')
        .addEventListener('change', event => {
            return renderLabelText(event, 1);
        });
    document.getElementById('showNotes_1')
        .addEventListener('click', event => {
            return showNotesField(event, 1);
        })
    document.getElementById('addTask_1')
        .addEventListener('click', event => {
            return addTaskToList(event, 1);
        })
        
    // connect handler to add new line
    document.getElementById('addLineBtn')
        .addEventListener('click', event => {
            count++
            return addNewLine(event, count);
        })
    
    // scripts to show/hide DOM elements
    function renderLabelText(event, num) {
        console.log(num);
        const numLabel = document.getElementById('labelNumEquipLabor_' + num);
        const descLabel = document.getElementById('labelDescEquipLabor_' + num);
        if (event.target.value == 'labor') {
            numLabel.innerText = '# of Personnel';
            descLabel.innerText = 'Description of Labor';
        } else {
            numLabel.innerText = 'Equipment No.';
            descLabel.innerText = 'Description of Equipment';
        }
    }
    
    function showNotesField(event, num) {
        console.log(num);
        const notesField = document.getElementById('notesField_' + num);
        if (notesField.style.display === 'none') notesField.style.display = 'block';
        else notesField.style.display = 'none';
    }
    
    // scripts to add/remove DOM elements
    function addTaskToList(ev, num) {
        console.log(num);
        // IDEA: pre-load the script with the complete DOM node object as arg when I addEventListener
        // BETTER IDEA: make these various handlers props of the DOM object in question
        // BEWARE: event.target may be the <i> icon
        const curInput = document.getElementById('taskInput_' + num);
        const newItemText = curInput.value.trim();
        curInput.value = '';
        if (newItemText) {
            const curList = document.getElementById('taskList_' + num);
            const newItem = document.createElement('option')
            newItem.appendChild(document.createTextNode(newItemText));
            curList.appendChild(newItem);
        } else {
            return;
        }
    }
    
    function addNewLine(event, num) {
        const parentEl = document.getElementById('workInputList')
        // generic components
        const labels = {
            firstRowLabels: ['Equip/Labor', 'Equip No.', 'Description of equipment', 'Notes'],
            secondRowLabels: ['Description of task/activity', 'Add task', 'Task/activity', 'Hours']
        };
        const formCtrls = {
            firstRowElements: ['select', 'input[number]', 'input[text]', 'button'],
            secondRowElements: ['input[text]', 'button', 'select', 'input[number]']
        };
        
        // specific DOM elements
        const newGroup = document.createElement('div');
        newGroup.classList.add('form-subsection', 'item-border-bottom', 'item-margin-bottom');
        newGroup.id = 'workInputGroup_' + num;

        const firstRow = document.createElement('div');
        firstRow.classList.add('flex-row', 'item-margin-bottom');
        newGroup.appendChild(firstRow);
        
        const subGroup = document.createElement('div')
        subGroup.classList.add('pad', 'border-radius', 'grey-bg');
        newGroup.appendChild(subGroup);
        
        const secondRow = document.createElement('div');
        secondRow.classList.add('flex-row', 'item-margin-bottom');
        subGroup.appendChild(secondRow);
        
        let curLabel;
        let curCtrl;
        let curStr;
        // append divs to firstRow
        for (let i = 0; i < formCtrls.firstRowElements.length; i++) {
            firstRow.appendChild(document.createElement('div')).classList.add('item-margin-right');
        // append form control elements to each div
            curLabel = firstRow.children[i]
                .appendChild(document.createElement('label'));
            curLabel.classList.add('input-label');
            curLabel.innerText = labels.firstRowLabels[i];
            
            labels.firstRowLabels[i] = curLabel;
            
            curStr = formCtrls.firstRowElements[i];
            if (curStr.startsWith('input')) {
                curCtrl = firstRow
                    .children[i]
                    .appendChild(
                        document
                            .createElement(curStr.slice(0, curStr.indexOf('['))
                        )
                    );
                curCtrl.setAttribute(
                    'type',
                    curStr
                        .slice(
                            curStr
                            .indexOf('[') + 1, curStr.indexOf(']'))
                    );
            } else {
                curCtrl = firstRow
                    .children[i]
                    .appendChild(
                        document.createElement(curStr)
                    )
                if (curStr === 'button') curCtrl.setAttribute('type', 'button');
            }
            curCtrl.classList.add('form-control')
            formCtrls.firstRowElements[i] = curCtrl;
        }
        // add some additional attrs to children of firstRow
        firstRow.children[2].classList.add('flex-grow');
        firstRow.children[3].style.position = 'relative';

        // manipulate new <select> element
        curCtrl = formCtrls.firstRowElements[0];
        curCtrl.id = 'selectEquipPersons_' + num;
        curCtrl.addEventListener('change', ev => {
            return renderLabelText(ev, num);
        })
        
        curCtrl.appendChild(document.createElement('option'));
        curCtrl.children[0].setAttribute('value', 'equipment');
        curCtrl.children[0].innerText = 'Equipment';
        
        curCtrl.appendChild(document.createElement('option'));
        curCtrl.children[1].setAttribute('value', 'labor');
        curCtrl.children[1].innerText = 'Labor';
        
        // manipulate new input[number] element
        labels.firstRowLabels[1].id = 'labelNumEquipLabor_' + num;
        formCtrls.firstRowElements[1].style.maxWidth = '110px';
        
        // manipulate new description input
        labels.firstRowLabels[2].id = 'labelDescEquipLabor_' + num;
        formCtrls.firstRowElements[2].classList.add('full-width');
        
        // manipulate new notes button
        curCtrl = formCtrls.firstRowElements[3];
        curCtrl.id = 'showNotes_' + num;
        curCtrl
            .appendChild(document.createElement('i'))
            .classList.add('typcn', 'typcn-document-text');
        curCtrl.addEventListener('click', ev => {
            return showNotesField(ev, num);
        });
        
        curCtrl = curCtrl
            .insertAdjacentElement('afterend', document.createElement('aside'));
        curCtrl.id = 'notesField_' + num;
        curCtrl
            .setAttribute(
                'style',
                'display: none; position: absolute; right: 46px; bottom: -2px; border: 1px solid #3333; padding: .25rem; background-color: white;'
            );
            
        curCtrl = curCtrl.appendChild(document.createElement('textarea'));
        curCtrl.classList.add('form-control');
        curCtrl.setAttribute('rows', '5');
        curCtrl.setAttribute('cols', '30');
        curCtrl.setAttribute('maxlength', '125');
        
        // build secondRow
        for (let i = 0; i < formCtrls.secondRowElements.length; i++) {
            secondRow.appendChild(document.createElement('div')).classList.add('item-margin-right');
        // append form control elements to each div
            curLabel = secondRow.children[i].appendChild(document.createElement('label'));
            curLabel.classList.add('input-label')
            curLabel.innerText = labels.secondRowLabels[i];
            
            labels.secondRowLabels[i] = curLabel;
            
            curStr = formCtrls.secondRowElements[i];
            if (curStr.startsWith('input')) {
                curCtrl = secondRow
                    .children[i]
                    .appendChild(
                        document
                            .createElement(curStr.slice(0, curStr.indexOf('[')))
                    )
                curCtrl.setAttribute(
                    'type',
                    curStr
                        .slice(
                            curStr
                            .indexOf('[') + 1, curStr.indexOf(']'))
                    );
            } else {
                curCtrl = secondRow
                    .children[i]
                    .appendChild(document.createElement(curStr));
                if (curStr === 'button') curCtrl.setAttribute('type', 'button');
            }
            curCtrl.classList.add('form-control')
            formCtrls.secondRowElements[i] = curCtrl;
        }
        // add additional attributes to secondRow children
        secondRow.children[0].classList.add('flex-grow');
        secondRow.children[2].style.minWidth = '150px';
        secondRow.children[3].style.maxWidth = '100px';
        
        // manipulate new task description input
        formCtrls.secondRowElements[0].id = 'taskInput_' + num;
        formCtrls.secondRowElements[0].classList.add('full-width');
        
        // manipulate new addTask button
        curCtrl = formCtrls.secondRowElements[1];
        curCtrl.innerText = 'Add';
        curCtrl
            .appendChild(document.createElement('i'))
            .classList.add('typcn', 'typcn-chevron-right-outline');
        curCtrl.id = 'addTask_' + num;
        curCtrl.classList.remove('form-control');
        curCtrl.classList.add('btn', 'btn-success', 'block');
        curCtrl.addEventListener('click', ev => {
           return addTaskToList(ev, num); 
        });
        
        // maniuplate new task select list
        formCtrls.secondRowElements[2].id = 'taskList_' + num;
        formCtrls.secondRowElements[2].classList.add('full-width');
        
        // manipulate new task hours input
        formCtrls.secondRowElements[3].classList.add('full-width');
        
        parentEl.appendChild(newGroup);
    }
    
    function destroyLine(num) {
        console.log(num);
    }
})()
