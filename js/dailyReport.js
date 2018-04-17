(function() {
    // TODO: make a fn to prevent more than 2-3 lines being added if lines are left empty
    const formState = {
        keys: [],
        actLists: []
    };
    
    const form = document.forms['dailyReportForm'];
    const submitEvent = new window.Event('submit');
    
    // this counter will be used to count input lines
    let count = 0;
    
    // The Submit handler
    function handleSubmit() {
        const endpoint = 'submitDaily.php';
        const data = new FormData(form);
        // delete all instances of actInput
        // append actList details to appropriate actList
        console.log('submit data:', data, formState.actLists);
        let i = 0;
        for (let list of formState.actLists) {
            let j = 0;
            console.log('list' + i + ':', list);
            data.delete('actInput_' + i);
            for (let id in list) {
                const listItem = list[id];
                // flatten act list data
                data.append('actDesc_' + i + '_' + j, listItem.textVal);
                data.append('actHrs_' + i + '_' + j, listItem.hrsVal);
                j++;
            }
            i++;
        }
        // console log the result

        // const jsonData = {}
        // for (let [key, val] of data) {
        //     jsonData[key] = val;
        // }

        // for (let [key, val] of data) console.log(key + ': ' + val);
        
        window.fetch(endpoint, {
            method: 'POST',
            body: data
        }).then(res => {
            if (res.ok) return res.text()
        }).then(text => document.write(text))
    }
    
    // handlers for elements that occur only once
    form.addEventListener('submit', event => {
        event.preventDefault();
        console.log('submit handler target:', event.target);
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
    document.getElementById('selectEquipLabor_0')
        .addEventListener('change', event => {
            return renderLabelText(event, 0);
        });
    document.getElementById('showNotes_0')
        .addEventListener('click', event => {
            return showNotesField(event, 0);
        });
    document.getElementById('actInput_0')
        .addEventListener('keypress', event => {
            return handleKeypressEnter(event, 0);
        });
    document.getElementById('hours_0')
        .addEventListener('keypress', event => {
            return handleKeypressEnter(event, 0);
        });
    document.getElementById('addAct_0')
        .addEventListener('click', event => {
            return addActToList(event, 0);
        });
    // I may need more handlers to handle all the cases of selecting an option
    document.getElementById('actList_0')
        .addEventListener('input', event => {
            return handleActSelect(event, 0);
        });
    document.getElementById('hours_0')
        .addEventListener('change', event => {
            return updateHours(event, 0);
        });
    
    // focus handlers
    function submitActHrs(ev, num) {
        const curList = document.getElementById('actList_'+ num);
        const curHrs = document.getElementById('hours_' + num);
        if (!curList.value.length) {
            curHrs.value = '';
            return;
        } else {
            const curInput = document.getElementById('actInput_' + num);
            const curID = curList.selectedOptions[0].uniqueID;
    
            updateHours(ev, num);
    
            curHrs.value = '';
            curList.value = '';
            curInput.value = '';
            curInput.focus();
        }
    }
        
    // handlers that get/set json data
    function handleActSelect(ev, num) {
        const hrsEl = document.getElementById('hours_' + num);
        // get actList obj from state
        const selectedOptID = ev.target.selectedOptions[0].uniqueID;
        
        console.log('handleActSelect ev.target', ev.target, 'pertinent hrs el', hrsEl);
        // select hours_ form control corresponding to num
        hrsEl.value = formState.actLists[num][selectedOptID].hrsVal;
        hrsEl.focus();
    }
    
    function updateHours(ev, num) {
        console.log('updateHours ev.target', ev.target);
        const curList = document.getElementById('actList_' + num);
        const curID = curList.selectedOptions[0].uniqueID;
        formState.actLists[num][curID].hrsVal = ev.target.value;
        console.log(formState.actLists[num][curID]);
    }
    
    function addActToList(ev, num) {
        console.log('num of cur inputs', num);
        // IDEA: create a warning if duplicate text entries
        // BEWARE: event.target may be the <i> icon
        // 1. check formState for existence of index @ num
        // 2. if it doesn't exist, push a new empty obj
        // 3. then assign it
        if (!formState.actLists[num]) formState.actLists.push({});
        let actList = formState.actLists[num];
        
        const curInput = document.getElementById('actInput_' + num);
        const newItemText = curInput.value.trim();
        curInput.value = '';
        // if text content in act description input instantiate new actList @ new uniqueID
        if (newItemText) {
            const curKey = newUniqueID(formState.keys);
            const curList = document.getElementById('actList_' + num);
            const curHrs = document.getElementById('hours_' + num);
            
            actList[curKey] = {
                textVal: newItemText,
                hrsVal: null,
                domEl: document.createElement('option')
            };
            actList[curKey].domEl.innerText = newItemText;
            actList[curKey].domEl.uniqueID = curKey;

            // append new option to select element and select it
            curList.appendChild(actList[curKey].domEl);
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
            if (ev.target.id.includes('actInput_')) {
                return addActToList(ev, num);
            }
            else if (ev.target.id.includes('hours_')) {
                return submitActHrs(ev, num);
            } else ev.target.dispatchEvent(submitEvent);
        }
    }

    // scripts to show/hide DOM elements
    function renderLabelText(event, num) {
        // TODO: this fcn needs to change name attr of text field
        const numLabel = document.getElementById('labelNumEquipLabor_' + num);
        const descLabel = document.getElementById('labelDescEquipLabor_' + num);
        const numInput = document.getElementById('equipOrLaborNum_' + num);
        const descInput = document.getElementById('equipOrLaborDesc_' + num);
        if (event.target.value == 'labor') {
            console.log(event.target.value);
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

        const formCtrls = {
            firstRow: [
                {
                    tagName: 'select',
                    label: 'Equip/Labor',
                    type: null,
                    id: 'selectEquipLabor',
                    name: 'equipOrLabor',
                    classList: 'form-control',
                    style: null,
                    handlers: [
                        {
                            event: 'change',
                            fn: renderLabelText
                        }
                    ],
                    children: [
                        {
                            tagName: 'option',
                            innerText: 'Labor',
                            value: 'labor'
                        },
                        {
                            tagName: 'option',
                            innerText: 'Equipment',
                            value: 'equipment'
                        }
                    ]
                },
                {
                    tagName: 'input',
                    label: {
                        innerText: '# of Personnel',
                        id: 'labelNumEquipLabor'
                    },
                    type: 'number',
                    id: 'equipOrLaborDesc',
                    name: 'laborNum',
                    classList: 'form-control',
                    style: 'max-width: 110px',
                    handlers: null
                },
                {
                    tagName: 'input',
                    label: {
                        innerText: 'Description of labor',
                        id: 'labelDescEquipLabor'
                    },
                    type: 'text',
                    id: 'equipOrLaborDesc',
                    name: 'laborDesc',
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
                    handlers: [
                        {
                        event: 'click',
                        fn: showNotesField
                        }
                    ],
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
                    id: 'actInput',
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
                    id: 'addAct',
                    name: null,
                    classList: ['btn', 'btn-success', 'block'],
                    style: null,
                    handlers: [
                        {
                            event: 'click',
                            fn: addActToList
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
                    id: 'actList',
                    name: null,
                    classList: ['form-control', 'full-width'],
                    style: null,
                    handlers: [
                        {
                            event: 'input',
                            fn: handleActSelect
                        }
                    ],
                    innerText: null
                },
                {
                    tagName: 'input',
                    label: 'Hours',
                    type: 'number',
                    id: 'hours',
                    name: 'actHrs',
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
        const subGroup = document.createElement('div');
        subGroup.classList.add('pad', 'border-radius', 'grey-bg');
        newGroup.appendChild(subGroup);
        
        const secondRow = document.createElement('div');
        secondRow.classList.add('flex-row', 'item-margin-bottom');
        subGroup.appendChild(secondRow);
        
        // append divs to firstRow
        /*for (let ctrl of formCtrls.firstRow) {
            // for each one append a div.item-margin-right
            const curParent = firstRow.appendChild(document.createElement('div'));
            curParent.classList.add('item-margin-right');
            // then loop over ctrl keys
            let curCtrl = curParent.appendChild(document.createElement(ctrl.tagName));
            for (let prop in ctrl) {
                // first append label
                if (ctrl[prop]) {
                    if (prop === 'label') {
                        if (typeof ctrl[prop] === 'object') {
                            let label = curCtrl.insertAdjacentElement('beforebegin', document.createElement('label'));
                            label.innerText = ctrl.label.innerText;
                            label.id = ctrl.label.id;
                        }
                        else {
                            curCtrl.insertAdjacentElement('beforebegin', document.createElement('label')).appendChild(document.createTextNode(ctrl.label));
                        }
                    }
                    
                    // then append form control element
                    if (prop === 'innerText') curCtrl.innerText = ctrl[prop];
                    else if (prop === 'classList') {
                        if (typeof ctrl[prop] === 'object') {
                            for (let className of ctrl[prop]) {
                                curCtrl.classList.add(className);
                            }
                        } else curCtrl.classList.add(ctrl[prop]);
                    }
                    else if (prop === 'children') {
                        let curChild;
                        for (let child of ctrl.children) {
                            curChild = curCtrl.appendChild(document.createElement(child.tagName));
                            for (let attr in child) {
                                if (attr === 'tagName') continue;
                                else if (attr === 'classList') {
                                    for (let className of child[attr]) {
                                        curChild.classList.add(className);
                                    }
                                } else if (attr === 'handlers') {
                                    for (let handler of child[attr]) {
                                        curChild.addEventListener(handler.event, ev => {
                                            return handler.fn(ev, num);
                                        });
                                    }
                                } else curChild.setAttribute(attr, child[attr]);
                            }
                        }
                    }
                    if (prop === 'siblings') {
                        let curSib;
                        let curChild;
                        for (let sib of ctrl.siblings) {
                            curSib = curCtrl.insertAdjacentElement('afterend', document.createElement(sib.tagName));
                            for (let attr in sib) {
                                if (attr === 'children') {
                                    for (let child of sib[attr]) {
                                        curChild = curSib.appendChild(document.createElement(child.tagName));
                                        for (let [childAttr, childAttrVal] in child) {
                                            if (childAttr === 'tagName') continue;
                                            else if (childAttr === 'classList') {
                                                for (let className of childAttr) {
                                                    curChild.classList.add(className);
                                                }
                                            } else if (childAttr === 'handler') {
                                                for (let handler of childAttr) {
                                                    curChild.addEventListener(handler.event, ev => {
                                                        return handler.fn(ev, num);
                                                    })
                                                }
                                            } else curChild.setAttribute(childAttr, childAttrVal)
                                        }
                                    }
                                }
                                else {
                                    curSib.setAttribute(attr, sib[attr]);
                                }
                            }
                        }
                    }
                    else curCtrl.setAttribute(prop, ctrl[prop]);
                }
            }
        }*/
        appendNextRow(formCtrls.firstRow, firstRow, num);
        appendNextRow(formCtrls.secondRow, secondRow, num);
        
        // add some additional classes to particular formCtrl parents
        newGroup.children[0].children[2].classList.add('flex-grow');
        newGroup.children[1].children[0].children[0].classList.add('flex-grow');
        
        parentEl.appendChild(newGroup);
    }
    
    function appendNextRow(elements, row, num) {
        let label;
        let curCtrl;

        for (let ctrl of elements) {
            // for each one append a div.item-margin-right
            const curParent = row.appendChild(document.createElement('div'));
            curParent.classList.add('item-margin-right');
            // then loop over ctrl keys
            curCtrl = curParent.appendChild(document.createElement(ctrl.tagName));
            for (let prop in ctrl) {
                if (prop === 'tagName') continue;
                // first append label
                if (ctrl[prop]) {
                    if (prop === 'label') {
                        if (typeof ctrl[prop] === 'object') {
                            label = curCtrl.insertAdjacentElement('beforebegin', document.createElement('label'));
                            label.innerText = ctrl.label.innerText;
                            label.id = ctrl.label.id + '_' + num;
                        }
                        else {
                            curCtrl.insertAdjacentElement('beforebegin', document.createElement('label')).appendChild(document.createTextNode(ctrl.label));
                        }
                    }
                    
                    // then append form control element
                    else if (prop === 'innerText') curCtrl.innerText = ctrl[prop];
                    else if (prop === 'classList') {
                        if (typeof ctrl[prop] === 'object') {
                            for (let className of ctrl[prop]) {
                                curCtrl.classList.add(className);
                            }
                        } else curCtrl.classList.add(ctrl[prop]);
                    }
                    else if (prop === 'handlers') {
                        for (let handler of ctrl[prop]) {
                            curCtrl.addEventListener(handler.event, ev => {
                                handler.fn(ev, num);
                            })
                        }
                    }
                    else if (prop === 'children') {
                        let curChild;
                        for (let child of ctrl.children) {
                            curChild = curCtrl.appendChild(document.createElement(child.tagName));
                            for (let attr in child) {
                                if (attr === 'tagName') continue;
                                else if (attr === 'innerText') curChild.innerText = child[attr];
                                else if (attr === 'classList') {
                                    for (let className of child[attr]) {
                                        curChild.classList.add(className);
                                    }
                                } else if (attr === 'handlers') {
                                    for (let handler of child[attr]) {
                                        curChild.addEventListener(handler.event, ev => {
                                            return handler.fn(ev, num);
                                        });
                                    }
                                } else curChild.setAttribute(attr, child[attr]);
                            }
                        }
                    }
                    else if (prop === 'siblings') {
                        let curSib;
                        let curChild;
                        for (let sib of ctrl.siblings) {
                            curSib = curCtrl.insertAdjacentElement('afterend', document.createElement(sib.tagName));
                            for (let attr in sib) {
                                if (attr === 'children') {
                                    for (let child of sib[attr]) {
                                        curChild = curSib.appendChild(document.createElement(child.tagName));
                                        for (let [childAttr, childAttrVal] in child) {
                                            if (childAttr === 'tagName') continue;
                                            else if (childAttr === 'classList') {
                                                for (let className of childAttr) {
                                                    curChild.classList.add(className);
                                                }
                                            } else if (childAttr === 'handler') {
                                                for (let handler of childAttr) {
                                                    curChild.addEventListener(handler.event, ev => {
                                                        return handler.fn(ev, num);
                                                    })
                                                }
                                            } else curChild.setAttribute(childAttr, childAttrVal)
                                        }
                                    }
                                }
                                else {
                                    curSib.setAttribute(attr, sib[attr]);
                                }
                            }
                        }
                    }
                    else if (prop === 'id' || 'name') {
                        curCtrl.setAttribute(prop, ctrl[prop] + '_' + num);
                    }
                    else curCtrl.setAttribute(prop, ctrl[prop]);
                }
            }
        }
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
