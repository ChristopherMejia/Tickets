const searchQuery = []; //Array

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

        case 'Asignado a':
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
    }
    
    removeAllChildNodes(divRemove); //remove the child elements
    if(searchQuery == ''){
        $("#tablaPrincipal").css('display','block');
    }
};


const aplicar = (userID) =>{
    console.log(userID);
    const data = JSON.stringify(searchQuery);
    $("#tablaPrincipal").css('display','none');
    
    const xmlHttpReq = initAjaxRequest();
	xmlHttpReq.onreadystatechange = responseData;
	xmlHttpReq.open('POST', 'ajax/filter.php', false);
	xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttpReq.send('data=' + data + '&id=' + userID + '&action=' + true );

	function responseData() {
		if (self.xmlHttpReq.readyState === self.xmlHttpReq.DONE) {
			if (self.xmlHttpReq.status === 200) {
                console.log(self.xmlHttpReq.responseText);
                // const obj = JSON.parse(self.xmlHttpReq.responseText);
                // console.log(obj);
                var dataSet = [
                    [ "Tiger Nixon", "System Architect", "Edinburgh", "5421", "2011/04/25", "$320,800", "hsd", "asdf", "sadf", "asdf" ]
                ];
                $(document).ready(function(){
                    $('#example').DataTable({
                        data: dataSet,
                        columns: [
                            { title: "#" },
                            { title: "Titulo" },
                            { title: "Prioridad" },
                            { title: "Empresa." },
                            { title: "Creado Por" },
                            { title: "Asignado A" },
                            { title: "Estatus" },
                            { title: "Modulo" },
                            { title: "Creado" },
                            { title: "Ultima Actualización"}
                        ]
                    });
                });
                
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
