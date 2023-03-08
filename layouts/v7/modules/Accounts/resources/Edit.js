/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Edit_Js("Accounts_Edit_Js",{
   
},{
	registerEvents: function() {





        console.log('Edit view page for accounts');

		const select_contacto = document.querySelector('[data-fieldname="modo_de_contacto"]');
		const input_contacto = document.getElementById('s2id_autogen12');
		const field_contacto_otro = document.querySelector('[name="modo_de_contacto_otro"]');

		// Valores por defecto
		field_contacto_otro.disabled = true;
		field_contacto_otro.style.backgroundColor =("background-color", "#80808030");

		// const event = new Event('change');
		// select_contacto.dispatchEvent(event);
		document.body.addEventListener('click', (event) => {
			// Check if the target of the click event is the select element
			// if (event.target === select) {
			//   return; // Do nothing if the click is on the select element
			// }
			
			// // Set the selectedIndex property to the current selected index
			// select.selectedIndex = select.selectedIndex;
			console.log("click");
			if (event.target.matches('#select2-drop, #select2-drop-mask')) {
				return; // Do nothing if the click is on one of the two divs
			  }
			  
			// Dispatch a change event
			const changeEvent = new Event('change');
			select_contacto.dispatchEvent(changeEvent);
		  });


		// const c1 = document.getElementById('select2-results-12');
		// const c2 = document.getElementById('select2-drop-mask');
		// c1.addEventListener('click', (event) => { 
		// 	select_contacto.dispatchEvent(changeEvent);
		// });

		// c2.addEventListener('click', (event) => { 
		// 	select_contacto.dispatchEvent(changeEvent);
		// });


		select_contacto.addEventListener('change', (event) => {
		// input_contacto.addEventListener("input", function() {
			// console.log("cambio");
			
			const selectedOption = event.target.value;
			// console.log(`Selected option: ${selectedOption}`);
			
		if (selectedOption === "Otro") {

			field_contacto_otro.disabled = false;
			field_contacto_otro.style.backgroundColor =("background-color", "#ffffff");
		} else {

			field_contacto_otro.disabled = true;
			field_contacto_otro.style.backgroundColor =("background-color", "#80808030");

		}
		});
		

		// const myDiv = document.getElementById('s2id_autogen11');

		// // Create a new MutationObserver instance
		// const observer = new MutationObserver((mutations) => {
		// mutations.forEach((mutation) => {
		// 	if (mutation.attributeName === 'class') {

		// 		select_contacto.dispatchEvent(new Event('change'));

		// 	}
		// });
		// });

		// Configure the observer to watch for changes to the class attribute
		// const config = { attributes: true, attributeFilter: ['class'] };
		// observer.observe(myDiv, config);

		// const fakeselect = document.getElementById('select2-chosen-12');

		// fakeselect.addEventListener('blur', (event) => {
		//   console.log('Div lost focus');
		// });
		




    },
   
	//This will store the editview form
	editViewForm : false,
   
	//Address field mapping within module
	addressFieldsMappingInModule : {
										'bill_street':'ship_street',
										'bill_pobox':'ship_pobox',
										'bill_city'	:'ship_city',
										'bill_state':'ship_state',
										'bill_code'	:'ship_code',
										'bill_country':'ship_country'
								},
   
   // mapping address fields of MemberOf field in the module              
   memberOfAddressFieldsMapping : {
                                        'bill_street':'bill_street',
										'bill_pobox':'bill_pobox',
										'bill_city'	:'bill_city',
										'bill_state':'bill_state',
										'bill_code'	:'bill_code',
										'bill_country':'bill_country',
                                        'ship_street' : 'ship_street',        
                                        'ship_pobox' : 'ship_pobox',
                                        'ship_city':'ship_city',
                                        'ship_state':'ship_state',
                                        'ship_code':'ship_code',
                                        'ship_country':'ship_country'
                                   },                          
	/**
	 * Function to swap array
	 * @param Array that need to be swapped
	 */ 
	swapObject : function(objectToSwap){
		var swappedArray = {};
		var newKey,newValue;
		for(var key in objectToSwap){
			newKey = objectToSwap[key];
			newValue = key;
			swappedArray[newKey] = newValue;
		}
		return swappedArray;
	},
	
	/**
	 * Function to copy address between fields
	 * @param strings which accepts value as either odd or even
	 */
	copyAddress : function(swapMode, container){
		var thisInstance = this;
		var addressMapping = this.addressFieldsMappingInModule;
		if(swapMode == "false"){
			for(var key in addressMapping) {
				var fromElement = container.find('[name="'+key+'"]');
				var toElement = container.find('[name="'+addressMapping[key]+'"]');
				toElement.val(fromElement.val());
			}
		} else if(swapMode){
			var swappedArray = thisInstance.swapObject(addressMapping);
			for(var key in swappedArray) {
				var fromElement = container.find('[name="'+key+'"]');
				var toElement = container.find('[name="'+swappedArray[key]+'"]');
				toElement.val(fromElement.val());
			}
		}
	},
	
	/**
	 * Function to register event for copying address between two fileds
	 */
	registerEventForCopyingAddress : function(container){
		var thisInstance = this;
		var swapMode;
		jQuery('[name="copyAddress"]').on('click',function(e){
			var element = jQuery(e.currentTarget);
			var target = element.data('target');
			if(target == "billing"){
				swapMode = "false";
			}else if(target == "shipping"){
				swapMode = "true";
			}
			thisInstance.copyAddress(swapMode, container);
		})
	},
	
	/**
	 * Function which will copy the address details - without Confirmation
	 */
	copyAddressDetails : function(data, container) {
		var thisInstance = this;
		thisInstance.getRecordDetails(data).then(
			function(data){
				var response = data['result'];
				thisInstance.mapAddressDetails(thisInstance.memberOfAddressFieldsMapping, response['data'], container);
			},
			function(error, err){

			});
	},
	
	/**
	 * Function which will map the address details of the selected record
	 */
	mapAddressDetails : function(addressDetails, result, container) {
		for(var key in addressDetails) {
			// While Quick Creat we don't have address fields, we should  add
            if(container.find('[name="'+key+'"]').length == 0) { 
                   container.append("<input type='hidden' name='"+key+"'>"); 
            } 
			container.find('[name="'+key+'"]').val(result[addressDetails[key]]);
			container.find('[name="'+key+'"]').trigger('change');
			container.find('[name="'+addressDetails[key]+'"]').val(result[addressDetails[key]]);
			container.find('[name="'+addressDetails[key]+'"]').trigger('change');
		}
	},
	
	/**
	 * Function which will register basic events which will be used in quick create as well
	 *
	 */
	registerBasicEvents : function(container) {
		this._super(container);
		this.registerEventForCopyingAddress(container);
	}
});