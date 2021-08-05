const divTable = document.getElementById('resultados_filter');//tabla de filtro de tickets
const divTablePending = document.getElementById('resultados_filter_pending'); //tabla de filtro de resultados de tickets sin asignar
const divTableTesting = document.getElementById('resultados_filter_testing');
const divTableAll = document.getElementById('resultados_filter_all');
const tablePending = document.getElementById('table_pending');
const tableTesting = document.getElementById('table_testing');
const tableAll = document.getElementById('table_All');

divTableTesting.style.display = 'none';
divTablePending.style.display = 'none';//Ocultamos la tabla de filtro sin asignar
divTable.style.display = 'none'; //Ocultamos la tabla del filtro

const searchQuery = []; //Array
const searchQueryPending = []; // Array to pending tickets
const searchQueryTesting = []; // Array to testing tickets
const searchQueryAll = []; //Array to all tickets

// Filter tickets
const addFilter = () => {
    
    const filter = document.getElementById('search_for');
    const word = document.getElementById('search_word');
    const filterValue = filter.options[filter.selectedIndex].value;
    const wordValue = word.value;
    const element = document.getElementById('new');
    switch(filterValue){
        case 'Titulo':
            console.log('case 1');
            searchQuery.push(addOptionSelected(filterValue, wordValue)); //agregar el obj a buscar
            deleteOption(filter, word); //remove the option select
            const oneFilter = createElement(filterValue, wordValue)
            element.appendChild(oneFilter);
            console.log(searchQuery);
            break;

        case 'Prioridad':
            console.log('case 2');
            searchQuery.push(addOptionSelected(filterValue, wordValue)); //Hacer la prueba
            deleteOption(filter, word); //remove the option select
            const twoFilter = createElement(filterValue, wordValue)
            element.appendChild(twoFilter);
        
            break;

        case 'Empresa':
            console.log('case 3');
            searchQuery.push(addOptionSelected(filterValue, wordValue)); //Hacer la prueba
            deleteOption(filter, word); //remove the option select
            const threeFilter = createElement(filterValue, wordValue)
            element.appendChild(threeFilter);
            break;

        case 'Creado':
            console.log('case 4');
            searchQuery.push(addOptionSelected(filterValue, wordValue)); //Hacer la prueba
            deleteOption(filter, word); //remove the option select
            const fourFilter = createElement(filterValue, wordValue)
            element.appendChild(fourFilter);
            break;

        case 'Asignado':
            console.log('case 5');
            searchQuery.push(addOptionSelected(filterValue, wordValue)); //Hacer la prueba
            deleteOption(filter, word); //remove the option select
            const fiveFilter = createElement(filterValue, wordValue)
            element.appendChild(fiveFilter);
            break;

        case 'Estatus':
            console.log('case 6');
            searchQuery.push(addOptionSelected(filterValue, wordValue));
            deleteOption(filter, word); //remove the option select
            const sixFilter = createElement(filterValue, wordValue)
            element.appendChild(sixFilter);
            break;

        case 'Modulo':
            console.log('case 7');
            searchQuery.push(addOptionSelected(filterValue, wordValue));
            deleteOption(filter, word); //remove the option select
            const sevenFilter = createElement(filterValue, wordValue)
            element.appendChild(sevenFilter);
            break;

        default:
            console.log(`El filtro ${filterValue} no existe.`);
    }
}

const addSpanText = (filter, value) => {
    const spn = document.createElement('span');
    spn.setAttribute("class", "input-group-text");
    spn.setAttribute("style", "font-size: 15px;");
    spn.innerHTML = filter + " = " + value;
    return spn;
}
const createElement = (filter, value) =>{
    const btn = document.createElement('button');
    const icon = document.createElement('i');
    const newDiv = document.createElement('div');
    let span = addSpanText(filter, value);

    btn.setAttribute("class", "btn btn-link");
    btn.setAttribute("type", "button");
    btn.setAttribute("onclick", `btnRemove("${filter}")`); //send the value
    
    icon.setAttribute("class", "fa fa-times");
    icon.setAttribute("aria-hidden", "true");
    newDiv.setAttribute("id", `div${filter}`); //Add id attribute to new div

    btn.appendChild(icon);

    newDiv.appendChild(btn); //add button element
    newDiv.appendChild(span); //add span element
    return newDiv;
}
//remove the filter in array.
const btnRemove = (filter) => {
    console.log(filter);
    const select = document.getElementById('search_for');
    const divRemove = document.getElementById(`div${filter}`);
    
    const opt = document.createElement('option');
    opt.value = filter;
	opt.innerHTML = filter;
    select.appendChild(opt);

    //remove from arrayQuery
    for(let i = 0; i < searchQuery.length; i++){
        let obj = searchQuery[i];
        if(filter == obj.campo){
            searchQuery.splice(i, 1);
        }
    }
    function removeAllChildNodes(parent) {
        while (parent.firstChild) {
            parent.removeChild(parent.firstChild);
        }
        divRemove.remove(); //eliminamos le div del filter
    }
    removeAllChildNodes(divRemove); //remove the child elements
    if(searchQuery == ''){
        $("#tablaPrincipal").css('display','block');
        divTable.style.display = 'none';
        
    }
};


const aplicar = (userID) =>{
    console.log(userID);
    const data = JSON.stringify(searchQuery);
    const xmlHttpReq = initAjaxRequest();
    const value = "ticket";
	xmlHttpReq.onreadystatechange = responseData;
	xmlHttpReq.open('POST', 'ajax/filter.php', false);
	xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttpReq.send('data=' + data + '&id=' + userID + '&flag=' + value);

	function responseData() {
		if (self.xmlHttpReq.readyState === self.xmlHttpReq.DONE) {
			if (self.xmlHttpReq.status === 200) {
                // const obj = self.xmlHttpReq.responseText;
                console.log(self.xmlHttpReq.responseText);
                $("#tablaPrincipal").css('display','none'); //ocultamos la tabla principal.
                divTable.style.display = 'block'; //mostramos la tabla del filtro

                $("#resultados_filter").html(self.xmlHttpReq.responseText);
                // load(1);

			} else {
				alert('Hubo un problema al obtener el registro.');
			}
		}
	}

}

const deleteOption = (selected, input) => {
	//Eliminamos del DOM la opción seleccionada
    selected.remove(selected.selectedIndex);
    input.value = '';
}
const addOptionSelected = (option, word) => {
    let objOption = {
        campo: option,
        palabra: word
    }
    return objOption;
}

const initAjaxRequest = () => {
	const xmlHttpReq = false;
	const self = this;

	if (window.XMLHttpRequest) {
		self.xmlHttpReq = new XMLHttpRequest();
		return self.xmlHttpReq;
	} else if (window.ActiveXObject) {
		self.xmlHttpReq = new ActiveXObject('Microsoft.XMLHTTP');
		return self.xmlHttpReq;
	}
}


////Filter tickets whitout asign////

const addFilterPending = () => {
    const element = document.getElementById('filter');
    const searchPending = document.getElementById('search_for_pending');
    const wordPending = document.getElementById('search_word_pending');
    const selectValue = searchPending.options[searchPending.selectedIndex].value;
    const wordValue = wordPending.value;

    switch(selectValue){
        case 'Titulo':
            console.log('case 1');
            searchQueryPending.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(searchPending, wordPending); //remove the option select
            const oneFilter = createElementPending(selectValue, wordValue)
            element.appendChild(oneFilter);
            console.log(searchQueryPending);
            break;

        case 'Prioridad':
            console.log('case 2');
            searchQueryPending.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(searchPending, wordPending); //remove the option select
            const twoFilter = createElementPending(selectValue, wordValue)
            element.appendChild(twoFilter);
            console.log(searchQueryPending);
            break;

        case 'Empresa':
            console.log('case 3');
            searchQueryPending.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(searchPending, wordPending); //remove the option select
            const threeFilter = createElementPending(selectValue, wordValue)
            element.appendChild(threeFilter);
            console.log(searchQueryPending);
            break;

        case 'Creado':
            console.log('case 4');
            searchQueryPending.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(searchPending, wordPending); //remove the option select
            const fourFilter = createElementPending(selectValue, wordValue)
            element.appendChild(fourFilter);
            console.log(searchQueryPending);
            break;

        case 'Asignado':
            console.log('case 5');
            searchQueryPending.push(addOptionSelected(selectValue, wordValue)); //Hacer la prueba
            deleteOption(searchPending, wordPending); //remove the option select
            const fiveFilter = createElementPending(selectValue, wordValue)
            element.appendChild(fiveFilter);
            console.log(searchQueryPending);

            break;

        case 'Estatus':
            console.log('case 6');
            searchQueryPending.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(searchPending, wordPending); //remove the option select
            const sixFilter = createElementPending(selectValue, wordValue)
            element.appendChild(sixFilter);
            console.log(searchQueryPending);
            break;

        case 'Modulo':
            console.log('case 7');
            searchQueryPending.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(searchPending, wordPending); //remove the option select
            const sevenFilter = createElementPending(selectValue, wordValue)
            element.appendChild(sevenFilter);
            console.log(searchQueryPending);
            break;

        default:
            console.log(`El filtro ${selectValue} no existe.`);
    }

}

const createElementPending = (filter, value) =>{
    const btn = document.createElement('button');
    const icon = document.createElement('i');
    const newDiv = document.createElement('div');
    let span = addSpanText(filter, value);

    btn.setAttribute("class", "btn btn-link");
    btn.setAttribute("type", "button");
    btn.setAttribute("onclick", `btnRemovePending("${filter}")`); //send the value
    
    icon.setAttribute("class", "fa fa-times");
    icon.setAttribute("aria-hidden", "true");
    newDiv.setAttribute("id", `divPending${filter}`); //Add id attribute to new div

    btn.appendChild(icon);

    newDiv.appendChild(btn); //add button element
    newDiv.appendChild(span); //add span element
    return newDiv;
}

const btnRemovePending = (filter) => {
    console.log(filter);
    const select = document.getElementById('search_for_pending');
    const divRemove = document.getElementById(`divPending${filter}`);
    
    const opt = document.createElement('option');
    opt.value = filter;
	opt.innerHTML = filter;
    select.appendChild(opt);

    //remove from arrayQuery
    for(let i = 0; i < searchQueryPending.length; i++){
        let obj = searchQueryPending[i];
        if(filter == obj.campo){
            searchQueryPending.splice(i, 1);
        }
    }
    function removeAllChildNodes(parent) {
        while (parent.firstChild) {
            parent.removeChild(parent.firstChild);
        }
        divRemove.remove(); //Eliminamos el div del filter
    }
    
    removeAllChildNodes(divRemove); //remove the child elements
    
    if(searchQueryPending == ''){
        tablePending.style.display = 'block';
        divTablePending.style.display = 'none';
    }
};

const aplicarPending = (userID) =>{

    console.log(userID, searchQueryPending);
    const data = JSON.stringify(searchQueryPending);
    const xmlHttpReq = initAjaxRequest();
    const value = "pending";
    xmlHttpReq.onreadystatechange = responseDataPending;
	xmlHttpReq.open('POST', 'ajax/filter.php', false);
	xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttpReq.send('data=' + data + '&id=' + userID + '&flag=' + value);
    function responseDataPending() {
		if (self.xmlHttpReq.readyState === self.xmlHttpReq.DONE) {
			if (self.xmlHttpReq.status === 200) {
                // const obj = self.xmlHttpReq.responseText;
                console.log(self.xmlHttpReq.responseText);
                tablePending.style.display = 'none';
                divTablePending.style.display = 'block';
                $("#resultados_filter_pending").html(self.xmlHttpReq.responseText);
                // load(1);

			} else {
				alert('Hubo un problema al obtener el registro.');
			}
		}
	}
}

////Filter Testing Tickets

const addFilterTesting = () =>{
    const selectTesting = document.getElementById('search_for_testing');
    const wordTesting = document.getElementById('search_word_testing');
    const selectValue = selectTesting.options[selectTesting.selectedIndex].value;
    const wordValue = wordTesting.value;
    const element = document.getElementById('filterTesting');
    switch(selectValue){
        case 'Titulo':
            console.log('case 1');
            searchQueryTesting.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(selectTesting, wordTesting); //remove the option select
            const oneFilter = createElementTesting(selectValue, wordValue)
            element.appendChild(oneFilter);
            console.log(searchQueryTesting);
            break;

        case 'Prioridad':
            console.log('case 2');
            searchQueryTesting.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(selectTesting, wordTesting); //remove the option select
            const twoFilter = createElementTesting(selectValue, wordValue)
            element.appendChild(twoFilter);
            console.log(searchQueryTesting);
        
            break;

        case 'Empresa':
            console.log('case 3');
            searchQueryTesting.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(selectTesting, wordTesting); //remove the option select
            const threeFilter = createElementTesting(selectValue, wordValue)
            element.appendChild(threeFilter);
            console.log(searchQueryTesting);
            break;

        case 'Creado':
            console.log('case 4');
            searchQueryTesting.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(selectTesting, wordTesting); //remove the option select
            const fourFilter = createElementTesting(selectValue, wordValue)
            element.appendChild(fourFilter);
            console.log(searchQueryTesting);
            break;

        case 'Asignado':
            searchQueryTesting.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(selectTesting, wordTesting); //remove the option select
            const fiveFilter = createElementTesting(selectValue, wordValue)
            element.appendChild(fiveFilter);
            console.log(searchQueryTesting);
            break;
        
        case 'Modulo':
            console.log('case 7');
            searchQueryTesting.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(selectTesting, wordTesting); //remove the option select
            const sevenFilter = createElementPending(selectValue, wordValue)
            element.appendChild(sevenFilter);
            console.log(searchQueryTesting);
            break;

        default:
            console.log(`El filtro ${selectValue} no existe.`);
    }
}

const createElementTesting = (filter, value) =>{
    const btn = document.createElement('button');
    const icon = document.createElement('i');
    const newDiv = document.createElement('div');
    let span = addSpanText(filter, value);

    btn.setAttribute("class", "btn btn-link");
    btn.setAttribute("type", "button");
    btn.setAttribute("onclick", `btnRemoveTesting("${filter}")`); //send the value
    
    icon.setAttribute("class", "fa fa-times");
    icon.setAttribute("aria-hidden", "true");
    newDiv.setAttribute("id", `divTesting${filter}`); //Add id attribute to new div

    btn.appendChild(icon);

    newDiv.appendChild(btn); //add button element
    newDiv.appendChild(span); //add span element
    return newDiv;
}

const btnRemoveTesting = (filter) => {
    console.log(filter);
    const select = document.getElementById('search_for_testing');
    const divRemove = document.getElementById(`divTesting${filter}`);
    
    const opt = document.createElement('option');
    opt.value = filter;
	opt.innerHTML = filter;
    select.appendChild(opt);

    //remove from arrayQuery
    for(let i = 0; i < searchQueryTesting.length; i++){
        let obj = searchQueryTesting[i];
        if(filter == obj.campo){
            searchQueryTesting.splice(i, 1);
        }
    }
    function removeAllChildNodes(parent) {
        while (parent.firstChild) {
            parent.removeChild(parent.firstChild);
        }
        divRemove.remove(); //Eliminamos el div del filter

    }
    
    removeAllChildNodes(divRemove); //remove the child elements
    
    if(searchQueryTesting == ''){
        tableTesting.style.display = 'block';
        divTableTesting.style.display = 'none';
    }
}

const aplicarTesting = (userID) =>{
    console.log(userID, searchQueryTesting);
    const data = JSON.stringify(searchQueryTesting);
    const xmlHttpReq = initAjaxRequest();
    const value ="testing";
    xmlHttpReq.onreadystatechange = responseDataTesting;
	xmlHttpReq.open('POST', 'ajax/filter.php', false);
	xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttpReq.send('data=' + data + '&id=' + userID + '&flag=' + value);
    function responseDataTesting() {
		if (self.xmlHttpReq.readyState === self.xmlHttpReq.DONE) {
			if (self.xmlHttpReq.status === 200) {
                // const obj = self.xmlHttpReq.responseText;
                console.log(self.xmlHttpReq.responseText);
                tableTesting.style.display = 'none';
                divTableTesting.style.display = 'block';
                $("#resultados_filter_testing").html(self.xmlHttpReq.responseText);
                // load(1);

			} else {
				alert('Hubo un problema al obtener el registro.');
			}
		}
	}
}

///Filter All Tickets
const addFilterAll = () =>{
    const selectAll = document.getElementById('search_for_all');
    const wordAll = document.getElementById('search_word_all');
    const selectValue = selectAll.options[selectAll.selectedIndex].value;
    const wordValue = wordAll.value;
    const element = document.getElementById('filterAll');
    switch(selectValue){
        case 'Titulo':
            console.log('case 1');
            searchQueryAll.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(selectAll, wordAll); //remove the option select
            const oneFilter = createElementAll(selectValue, wordValue)
            element.appendChild(oneFilter);
            console.log(searchQueryAll);
            break;

        case 'Prioridad':
            console.log('case 2');
            searchQueryAll.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(selectAll, wordAll); //remove the option select
            const twoFilter = createElementAll(selectValue, wordValue)
            element.appendChild(twoFilter);
            console.log(searchQueryAll);
        
            break;

        case 'Empresa':
            console.log('case 3');
            searchQueryAll.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(selectAll, wordAll); //remove the option select
            const threeFilter = createElementAll(selectValue, wordValue)
            element.appendChild(threeFilter);
            console.log(searchQueryAll);
            break;

        case 'Creado':
            console.log('case 4');
            searchQueryAll.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(selectAll, wordAll); //remove the option select
            const fourFilter = createElementAll(selectValue, wordValue)
            element.appendChild(fourFilter);
            console.log(searchQueryAll);
            break;

        case 'Asignado':
            console.log('case 5');
            searchQueryAll.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(selectAll, wordAll); //remove the option select
            const fiveFilter = createElementAll(selectValue, wordValue)
            element.appendChild(fiveFilter);
            console.log(searchQueryAll);
            break;
        
        case 'Estatus':
            console.log('case 6');
            searchQueryAll.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(selectAll, wordAll); //remove the option select
            const sixFilter = createElementAll(selectValue, wordValue)
            element.appendChild(sixFilter);
            console.log(searchQueryAll);
            break;

        case 'Modulo':
            console.log('case 7');
            searchQueryAll.push(addOptionSelected(selectValue, wordValue)); //agregar el obj a buscar
            deleteOption(selectAll, wordAll); //remove the option select
            const sevenFilter = createElementAll(selectValue, wordValue)
            element.appendChild(sevenFilter);
            console.log(searchQueryAll);
            break;

        default:
            console.log(`El filtro ${selectValue} no existe.`);
    }
}

const createElementAll = (filter, value) => {
    const btn = document.createElement('button');
    const icon = document.createElement('i');
    const newDiv = document.createElement('div');
    let span = addSpanText(filter, value);

    btn.setAttribute("class", "btn btn-link");
    btn.setAttribute("type", "button");
    btn.setAttribute("onclick", `btnRemoveAll("${filter}")`); //send the value
    
    icon.setAttribute("class", "fa fa-times");
    icon.setAttribute("aria-hidden", "true");
    newDiv.setAttribute("id", `divAll${filter}`); //Add id attribute to new div

    btn.appendChild(icon);

    newDiv.appendChild(btn); //add button element
    newDiv.appendChild(span); //add span element
    return newDiv;
}

const btnRemoveAll = (filter) => {
    console.log(filter);
    const select = document.getElementById('search_for_all');
    const divRemove = document.getElementById(`divAll${filter}`);
    
    const opt = document.createElement('option');
    opt.value = filter;
	opt.innerHTML = filter;
    select.appendChild(opt);

    //remove from arrayQuery
    for(let i = 0; i < searchQueryAll.length; i++){
        let obj = searchQueryAll[i];
        if(filter == obj.campo){
            searchQueryAll.splice(i, 1);
        }
    }
    function removeAllChildNodes(parent) {
        while (parent.firstChild) {
            parent.removeChild(parent.firstChild);
        }
        divRemove.remove(); //Eliminamos el div del filter

    }
    
    removeAllChildNodes(divRemove); //remove the child elements
    
    if(searchQueryTesting == ''){
        tableAll.style.display = 'block';
        divTableAll.style.display = 'none';
    }
}

const aplicarAll = (userID) => {
    console.log(userID, searchQueryAll);

    const data = JSON.stringify(searchQueryAll);
    const xmlHttpReq = initAjaxRequest();
    const value ="all";

    xmlHttpReq.onreadystatechange = responseDataAll;
	xmlHttpReq.open('POST', 'ajax/filter.php', false);
	xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttpReq.send('data=' + data + '&id=' + userID + '&flag=' + value);

    function responseDataAll() {
		if (self.xmlHttpReq.readyState === self.xmlHttpReq.DONE) {
			if (self.xmlHttpReq.status === 200) {
                // const obj = self.xmlHttpReq.responseText;
                console.log(self.xmlHttpReq.responseText);
                tableAll.style.display = 'none';
                divTableAll.style.display = 'block';
                $("#resultados_filter_all").html(self.xmlHttpReq.responseText);
                // load(1);

			} else {
				alert('Hubo un problema al obtener el registro.');
			}
		}
	}
}