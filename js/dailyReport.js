(function() {
    // TODO: make a fn to prevent more than 2-3 lines being added if lines are left empty
    const formState = {
        keys: [],
        taskLists: []
    };
    
    const form = document.forms['dailyReportForm'];
    const submitEvent = new window.Event('submit');
    
    // this counter will be used to count input lines
    let count = 0;
    
    // The Submit handler
    function handleSubmit() {
        const endpoint = 'submitDaily.php';
        const data = new FormData(form);
        // delete all instances of taskInput
        // append taskList details to appropriate taskList
        for (let [i, id] of formState.taskLists) {
            data.delete('taskInput_' + i);
            for (let [key, obj] of id) {
                let j = 0;
                // flatten task list data
                data.append('taskDescription_' + i + '_' + j, obj.textVal);
                data.append('taskHours_' + i + '_' + j, obj.hrsVal);
                i++;
            }
        }
        // console log the result
        const jsonData = {}
        for (let [key, val] of data) {
            jsonData[key] = val;
        }
        console.log(jsonData);
        // window.fetch(endpoint, {
        //     method: 'POST',
        //     body: data
        // })
    }
    
    // handlers for elements that occur only once
    form.addEventListener('submit', event => {
        event.preventDefault();
        return handleSubmit();
    })
    // form.addEventListener('keypress', event => {
    //     return handleKeypressEnter()
    // })
    document.getElementById('addLineBtn')
        .addEventListener('click', event => {
            count++;
            return addNewLine(event, count);
        });
        
    // add ev listeners on default (first) rendered line
    document.getElementById('selectEquipPersons_0')
        .addEventListener('change', event => {
            return renderLabelText(event, 0);
        });
    document.getElementById('showNotes_0')
        .addEventListener('click', event => {
            return showNotesField(event, 0);
        });
    document.getElementById('taskInput_0')
        .addEventListener('keypress', event => {
            return handleKeypressEnter(event, 0);
        });
    document.getElementById('hours_0')
        .addEventListener('keypress', event => {
            return handleKeypressEnter(event, 0);
        });
    document.getElementById('addTask_0')
        .addEventListener('click', event => {
            return addTaskToList(event, 0);
        });
    // I may need more handlers to handle all the cases of selecting an option
    document.getElementById('taskList_0')
        .addEventListener('input', event => {
            return handleTaskSelect(event, 0);
        });
    document.getElementById('hours_0')
        .addEventListener('change', event => {
            return updateHours(event, 0);
        });
    
    // focus handlers
    function submitTaskHours(ev, num) {
        const curList = document.getElementById('taskList_'+ num);
        const curHrs = document.getElementById('hours_' + num);
        if (!curList.value.length) {
            curHrs.value = '';
            return;
        } else {
            const curInput = document.getElementById('taskInput_' + num);
            const curID = curList.selectedOptions[0].uniqueID;
    
            updateHours(ev, num);
    
            curHrs.value = '';
            curList.value = '';
            curInput.value = '';
            curInput.focus();
        }
    }
        
    // handlers that get/set json data
    function handleTaskSelect(ev, num) {
        const hrsEl = document.getElementById('hours_' + num);
        // get taskList obj from state
        const selectedOptID = ev.target.selectedOptions[0].uniqueID;
        
        console.log(ev.target);
        console.log(document.getElementById('hours_' + num));
        // select hours_ form control corresponding to num
        hrsEl.value = formState.taskLists[num][selectedOptID].hrsVal;
        hrsEl.focus();
    }
    
    function updateHours(ev, num) {
        console.log(ev.target);
        const curList = document.getElementById('taskList_' + num);
        const curID = curList.selectedOptions[0].uniqueID;
        formState.taskLists[num][curID].hrsVal = ev.target.value;
        console.log(formState.taskLists[num][curID]);
    }
    
    function addTaskToList(ev, num) {
        console.log(num);
        // IDEA: create a warning if duplicate text entries
        // BEWARE: event.target may be the <i> icon
        // 1. check formState for existence of index @ num
        // 2. if it doesn't exist, push a new empty obj
        // 3. then assign it
        if (!formState.taskLists[num]) formState.taskLists.push({});
        let taskList = formState.taskLists[num];
        
        const curInput = document.getElementById('taskInput_' + num);
        const newItemText = curInput.value.trim();
        curInput.value = '';
        // if text content in task description input instantiate new taskList @ new uniqueID
        if (newItemText) {
            const curKey = newUniqueID(formState.keys);
            const curList = document.getElementById('taskList_' + num);
            const curHrs = document.getElementById('hours_' + num);
            
            taskList[curKey] = {
                textVal: newItemText,
                hrsVal: null,
                domEl: document.createElement('option')
            };
            taskList[curKey].domEl.innerText = newItemText;
            taskList[curKey].domEl.uniqueID = curKey;

            // append new option to select element and select it
            curList.appendChild(taskList[curKey].domEl);
            curList.value = newItemText;
            
            curHrs.focus();
            console.log(formState);
        } else {
            return;
        }
    }
    
    function handleKeypressEnter(ev, num) {
        // is it possible I'll get some weird event targets here?
        ev.stopPropagation();
        if (ev.key === 'Enter') {
            ev.preventDefault();
            if (ev.target.id.includes('taskInput_')) {
                return addTaskToList(ev, num);
            }
            else if (ev.target.id.includes('hours_')) {
                return submitTaskHours(ev, num);
            } else ev.target.dispatchEvent(submitEvent);
        }
    }

    // scripts to show/hide DOM elements
    function renderLabelText(event, num) {
        // TODO: this fcn needs to change name attr of text field
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
    function addNewLine(event, num) {
        const parentEl = document.getElementById('workInputList');
        // generic components

        // const labels = {
        //     firstRowLabels: ['Equip/Labor', 'Equip No.', 'Description of equipment', 'Notes'],
        //     secondRowLabels: ['Description of task/activity', 'Add task', 'Task/activity', 'Hours']
        // };
        // const formCtrls = {
        //     firstRowElements: ['select', 'input[number]', 'input[text]', 'button'],
        //     secondRowElements: ['input[text]', 'button', 'select', 'input[number]']
        // };
        
        const formCtrls = {
            firstRow: [
                {
                    tagName: 'select',
                    label: 'Equip/Labor',
                    type: null,
                    id: 'selectEquipPersons',
                    name: 'equipOrPersons',
                    classList: 'form-control',
                    style: null,
                    handlers: [
                        {
                            event: 'change',
                            fn: renderLabelText
                        }
                    ]
                },
                {
                    tagName: 'input',
                    label: {
                        innerText: 'Equip No.',
                        id: 'labelNumEquipLabor'
                    },
                    type: 'number',
                    id: null,
                    name: 'numEquipOrPersons',
                    classList: 'form-control',
                    style: 'max-width: 110px',
                    handlers: null
                },
                {
                    tagName: 'input',
                    label: {
                        innerText: 'Description of equipment',
                        id: 'labelDescEquipLabor'
                    },
                    type: 'text',
                    id: null,
                    name: 'equipDesc',
                    classList: ['form-control', 'full-width'],
                    style: null,
                    handlers: null
                },
                {
                    tagName: 'button',
                    label: 'Notes',
                    type: 'button',
                    id: 'showNotes',
                    name: null,
                    classList: 'form-control',
                    style: null,
                    handlers: {
                        event: 'click',
                        fn: showNotesField
                    },
                    innerText: null,
                    children: [
                        {
                            tagName: 'i',
                            classList: ['typcn', 'typcn-document-text']
                        }
                    ],
                    siblings: [
                        {
                            tagName: 'aside',
                            id: 'notesField',
                            style: 'display: none; position: absolute; right: 46px; bottom: -2px; border: 1px solid #3333; padding: .25rem; background-color: white;',
                            children: [
                                {
                                    tagName: 'textarea',
                                    name: 'remarks',
                                    rows: '5',
                                    cols: '30',
                                    maxlength: '125',
                                    classList: 'form-control'
                                }
                            ]
                        }
                    ]
                }
            ],
            secondRow: [
                {
                    tagName: 'input',
                    label: 'Description of task/activity',
                    type: 'text',
                    id: 'taskInput',
                    name: null,
                    classList: ['form-control', 'full-width'],
                    style: null,
                    handlers: [
                        {
                            event: 'keypress',
                            fn: handleKeypressEnter
                        }
                    ],
                    innerText: null
                },
                {
                    tagName: 'button',
                    label: 'Add Task',
                    type: 'button',
                    id: 'addTask',
                    name: null,
                    classList: ['btn', 'btn-success', 'block'],
                    style: null,
                    handlers: [
                        {
                            event: 'click',
                            fn: addTaskToList
                        }
                    ],
                    innerText: 'Add',
                    children: [
                        {
                            tagName: 'i',
                            classList: ['typcn', 'typcn-chevron-right-outline']
                        }
                    ]
                },
                {
                    tagName: 'select',
                    label: 'Task/activity',
                    type: null,
                    id: 'taskList',
                    name: null,
                    classList: ['form-control', 'full-width'],
                    style: null,
                    handlers: [
                        {
                            event: 'input',
                            fn: handleTaskSelect
                        }
                    ],
                    innerText: null
                },
                {
                    tagName: 'input',
                    label: 'Hours',
                    type: 'number',
                    id: 'hours',
                    name: 'hours',
                    classList: ['form-control', 'full-width'],
                    style: null,
                    handlers: [
                        {
                            event: 'change',
                            fn: updateHours
                        }
                    ],
                    innerText: null
                }
            ]
        }
        
        // specific DOM elements
        const newGroup = document.createElement('div');
        newGroup.classList.add('form-subsection', 'item-border-bottom', 'item-margin-bottom');
        newGroup.id = 'workInputGroup_' + num;

        // append first row of inputs
        const firstRow = document.createElement('div');
        firstRow.classList.add('flex-row', 'item-margin-bottom');
        newGroup.appendChild(firstRow);
        
        // append second row of inputs within subGroup
        const subGroup = document.createElement('div')
        subGroup.classList.add('pad', 'border-radius', 'grey-bg');
        newGroup.appendChild(subGroup);
        
        const secondRow = document.createElement('div');
        secondRow.classList.add('flex-row', 'item-margin-bottom');
        subGroup.appendChild(secondRow);
        
        let label;
        let curCtrl;
        let curStr;
        // append divs to firstRow
        for (let ctrl of formCtrls.firstRow) {
            // for each one append a div.item-margin-right
            const parent = firstRow.appendChild(document.createElement('div')).classList.add('item-margin-right');
            // then loop over ctrl keys
            for (let key of ctrl) {
                if (typeof ctrl.label === 'object') {
                    label = parent.appendChild(document.createElement('label'));
                    label.innerText = ctrl.label.innerText;
                    label.id = ctrl.label.id;
                } else parent.appendChild(document.createElement('label')).appendChild(document.createTextNode(ctrl.label));
                curCtrl = parent.appendChild(document.createElement(ctrl.tagName));
                if ('type' in ctrl && ctrl.type) curCtrl.setAttribute('type', ctrl.type);
                if ('id' in ctrl && ctrl.id) curCtrl.setAttribute('id', ctrl.id);
                if ('name' in ctrl && ctrl.name) curCtrl.setAttribute('name', ctrl.name);
                if ('style' in ctrl && ctrl.style) curCtrl.setAttribute('style', ctrl.style);
                if ('innerText' in ctrl && ctrl.innerText) curCtrl.innerText = ctrl.innerText;
                if ('classList' in ctrl && ctrl.classList) {
                    if (typeof ctrl.classList === 'object') {
                        for (let className of ctrl.classList) {
                            curCtrl.classList.add(className);
                        }
                    } else curCtrl.classList.add(ctrl.classList);
                }
                if ('children' in ctrl && ctrl.children) {
                    let curChild;
                    for (let child of ctrl.children) {
                        curChild = curCtrl.appendChild(document.createElement(child.tagName));
                        for (let [attr, val] of child) {
                            if (attr === 'tagName') continue;
                            else if (attr === 'classList') {
                                for (let className of attr) {
                                    curChild.classList.add(className);
                                }
                            } else if (attr === 'handlers') {
                                for (let handler of attr) {
                                    curChild.addEventListener(handler.event, ev => {
                                        return handler.fn(ev, num);
                                    });
                                }
                            } else curChild.setAttribute(attr, val);
                        }
                    }
                }
                if ('siblings' in ctrl && ctrl.siblings) {
                    let curSib;
                    let curChild;
                    for (let sib of ctrl.siblings) {
                        curSib = curCtrl.insertAdjacentElement('afterend', document.createElement(sib.tagName));
                        for (let [attr, val] of sib) {
                            if (attr === 'children') {
                                for (let child of attr) {
                                    curChild = sib.appendChild(document.createElement(child.tagName));
                                    for (let [childAttr, childAttrVal] of child) {
                                        if (childAttr === 'tagName') continue;
                                        else if (childAttr === 'classList') {
                                            for (let className of childAttr) {
                                                child.classList.add(className);
                                            }
                                        } else if (childAttr === 'handler') {
                                            for (let handler of childAttr) {
                                                child.addEventListener(handler.event, ev => {
                                                    return handler.fn(ev, num);
                                                })
                                            }
                                        } else child.setAttribute(childAttr, childAttrVal)
                                    }
                                }
                            }
                            else sib.setAttribute(attr, val);
                        }
                    }
                }
            }
        }
        
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
        curCtrl.name = 'selectEquipPersons_' + num;
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
        labels.firstRowLabels[1].name = 'labelNumEquipLabor_' + num;
        formCtrls.firstRowElements[1].style.maxWidth = '110px';
        
        // manipulate new description input
        labels.firstRowLabels[2].id = 'labelDescEquipLabor_' + num;
        labels.firstRowLabels[2].name = 'labelDescEquipLabor_' + num;
        formCtrls.firstRowElements[2].classList.add('full-width');
        
        // manipulate new notes button
        curCtrl = formCtrls.firstRowElements[3];
        curCtrl.id = 'showNotes_' + num;
        curCtrl.name = 'showNotes_' + num;
        curCtrl
            .appendChild(document.createElement('i'))
            .classList.add('typcn', 'typcn-document-text');
        curCtrl.addEventListener('click', ev => {
            return showNotesField(ev, num);
        });
        
        curCtrl = curCtrl
            .insertAdjacentElement('afterend', document.createElement('aside'));
        curCtrl.id = 'notesField_' + num;
        curCtrl.name = 'notesField_' + num;
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
        formCtrls.secondRowElements[0].name = 'taskInput_' + num;
        formCtrls.secondRowElements[0].classList.add('full-width');
        
        // manipulate new addTask button
        curCtrl = formCtrls.secondRowElements[1];
        curCtrl.innerText = 'Add';
        curCtrl
            .appendChild(document.createElement('i'))
            .classList.add('typcn', 'typcn-chevron-right-outline');
        curCtrl.id = 'addTask_' + num;
        curCtrl.num = 'addTask_' + num;
        curCtrl.classList.remove('form-control');
        curCtrl.classList.add('btn', 'btn-success', 'block');
        curCtrl.addEventListener('click', ev => {
           return addTaskToList(ev, num); 
        });
        
        // maniuplate new task select list
        formCtrls.secondRowElements[2].id = 'taskList_' + num;
        formCtrls.secondRowElements[2].name = 'taskList_' + num;
        formCtrls.secondRowElements[2].classList.add('full-width');
        
        // manipulate new task hours input
        formCtrls.secondRowElements[3].classList.add('full-width');
        formCtrls.secondRowElements[3].id = 'hours_' + num;
        formCtrls.secondRowElements[3].name = 'hours_' + num;
        
        parentEl.appendChild(newGroup);
    }
    
    function destroyLine(num) {
        // when user destroys line, also destory corresponding formState obj
        console.log(num);
    }
    
    // utils
    function newUniqueID(prevKeys) {
        // if no prevKeys, set it to an empty array
        prevKeys = prevKeys || [];
        let uniqueID = (new Date().getTime()).toString(16);
        let i = 0;
        if (!prevKeys.includes(uniqueID)) {
            formState.keys.push(uniqueID);
            return uniqueID;
        } else {
            while (prevKeys.includes(uniqueID)) {
                uniqueID = (new Date().getTime()).toString(16);
                i++;
                if (i > 99) break;
            }
            formState.keys.push(uniqueID);
            return uniqueID;
        }
        
    }
})()
