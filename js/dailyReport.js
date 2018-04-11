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
    document.getElementById('addNewLine')
        .addEventListener('click', event => {
            return addNewLine(event, count);
        })
    
    // scripts to show/hide DOM elements
    function renderLabelText(event, num) {
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
        const notesField = document.getElementById('notesField_' + num);
        if (notesField.style.display === 'none') notesField.style.display = 'block';
        else notesField.style.display = 'none';
    }
    
    // scripts to add/remove DOM elements
    function addTaskToList(ev, num) {
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
        const prevGroup = document.getElementById('workInputGroup_' + num);
        num++;
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
        let curEl;
        // append divs to firstRow
        for (let i = 0; i < formCtrls.firstRowElements.length; i++) {
            firstRow.appendChild(document.createElement('div')).classList.add('item-margin-right');
        // append form control elements to each div
            curLabel = firstRow.children[i]
                .appendChild(document.createElement('label'));
            curLabel.classList.add('input-label');
            curLabel.innerText = labels.firstRowLabels[i];
            
            curEl = formCtrls.firstRowElements[i];
            if (curEl.startsWith('input')) {
                curCtrl = firstRow
                    .children[i]
                    .appendChild(
                        document.createElement(
                            curEl
                            .slice(0, curEl.indexOf('['))
                        )
                    );
                curCtrl.setAttribute(
                    'type',
                    formCtrls
                        .firstRowElements[i]
                        .slice(
                            curEl
                            .indexOf('[') + 1, curEl.indexOf(']'))
                    );
            } else {
                curCtrl = firstRow
                    .children[i]
                    .appendChild(
                        document.createElement(curEl)
                    )
            }
        }
        
        // add some additional attrs to children of firstRow
        firstRow.children[2].classList.add('flex-grow');
        firstRow.children[3].style.position = 'relative';
        
        console.log(num, prevGroup, newGroup);
    }
    
    function destroyLine(num) {
        console.log(num);
    }
})()
