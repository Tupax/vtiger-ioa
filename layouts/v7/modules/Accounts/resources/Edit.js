/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Edit_Js("Accounts_Edit_Js", {

}, {
	// registerEvents: function () {

	// },

	//This will store the editview form
	editViewForm: false,

	//Address field mapping within module
	addressFieldsMappingInModule: {
		'bill_street': 'ship_street',
		'bill_pobox': 'ship_pobox',
		'bill_city': 'ship_city',
		'bill_state': 'ship_state',
		'bill_code': 'ship_code',
		'bill_country': 'ship_country'
	},

	// mapping address fields of MemberOf field in the module              
	memberOfAddressFieldsMapping: {
		'bill_street': 'bill_street',
		'bill_pobox': 'bill_pobox',
		'bill_city': 'bill_city',
		'bill_state': 'bill_state',
		'bill_code': 'bill_code',
		'bill_country': 'bill_country',
		'ship_street': 'ship_street',
		'ship_pobox': 'ship_pobox',
		'ship_city': 'ship_city',
		'ship_state': 'ship_state',
		'ship_code': 'ship_code',
		'ship_country': 'ship_country'
	},
	/**
	 * Function to swap array
	 * @param Array that need to be swapped
	 */
	swapObject: function (objectToSwap) {
		var swappedArray = {};
		var newKey, newValue;
		for (var key in objectToSwap) {
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
	copyAddress: function (swapMode, container) {
		var thisInstance = this;
		var addressMapping = this.addressFieldsMappingInModule;
		if (swapMode == "false") {
			for (var key in addressMapping) {
				var fromElement = container.find('[name="' + key + '"]');
				var toElement = container.find('[name="' + addressMapping[key] + '"]');
				toElement.val(fromElement.val());
			}
		} else if (swapMode) {
			var swappedArray = thisInstance.swapObject(addressMapping);
			for (var key in swappedArray) {
				var fromElement = container.find('[name="' + key + '"]');
				var toElement = container.find('[name="' + swappedArray[key] + '"]');
				toElement.val(fromElement.val());
			}
		}
	},

	/**
	 * Function to register event for copying address between two fileds
	 */
	registerEventForCopyingAddress: function (container) {
		var thisInstance = this;
		var swapMode;
		jQuery('[name="copyAddress"]').on('click', function (e) {
			var element = jQuery(e.currentTarget);
			var target = element.data('target');
			if (target == "billing") {
				swapMode = "false";
			} else if (target == "shipping") {
				swapMode = "true";
			}
			thisInstance.copyAddress(swapMode, container);
		})
	},

	/**
	 * Function which will copy the address details - without Confirmation
	 */
	copyAddressDetails: function (data, container) {
		var thisInstance = this;
		thisInstance.getRecordDetails(data).then(
			function (data) {
				var response = data['result'];
				thisInstance.mapAddressDetails(thisInstance.memberOfAddressFieldsMapping, response['data'], container);
			},
			function (error, err) {

			});
	},

	/**
	 * Function which will map the address details of the selected record
	 */
	mapAddressDetails: function (addressDetails, result, container) {
		for (var key in addressDetails) {
			// While Quick Creat we don't have address fields, we should  add
			if (container.find('[name="' + key + '"]').length == 0) {
				container.append("<input type='hidden' name='" + key + "'>");
			}
			container.find('[name="' + key + '"]').val(result[addressDetails[key]]);
			container.find('[name="' + key + '"]').trigger('change');
			container.find('[name="' + addressDetails[key] + '"]').val(result[addressDetails[key]]);
			container.find('[name="' + addressDetails[key] + '"]').trigger('change');
		}
	},

	/**
	 * Function which will register basic events which will be used in quick create as well
	 *
	 */
	registerBasicEvents: function (container) {
		this._super(container);
		this.registerEventForCopyingAddress(container);



		console.log(container);
		console.log('Edit view page for accounts');

		const select_contacto = document.querySelector('[data-fieldname="modo_de_contacto"]');
		const field_contacto_otro = document.querySelector('[name="modo_de_contacto_otro"]');

		// Valores por defecto
		field_contacto_otro.disabled = true;
		field_contacto_otro.style.backgroundColor = ("background-color", "#80808030");

		// Preguntar porque esto no funciona? Porque el overlay no registra el evento click?
		// document.body.addEventListener('click', (event) => {
		// 	console.log("click");
		// 	const changeEvent = new Event('change');
		// 	select_contacto.dispatchEvent(changeEvent);
		//   });

		const changeEvent = new Event('change');

		// select the span element
		const select2Chosen12 = document.querySelector('#select2-chosen-12');
		// save the initial content of the span element
		let initialContent = select2Chosen12.innerHTML;

		// Create a new mutation observer
		const observer = new MutationObserver(mutations => {
			mutations.forEach(mutation => {
				if (mutation.type === 'childList' && mutation.target === select2Chosen12 && select2Chosen12.innerHTML !== initialContent) {
					// console.log('Cambio a:', select2Chosen12.innerHTML);
					select_contacto.dispatchEvent(changeEvent);
					initialContent = select2Chosen12.innerHTML;
				}
			});
		});
		// Define the observer options
		const observerConfig = {
			characterData: true,
			subtree: true,
			childList: true
		};
		// Start observing the span element for changes
		observer.observe(select2Chosen12, observerConfig);

		select_contacto.addEventListener('change', (event) => {
			const selectedOption = event.target.value;
			console.log(`selectedOption: ${selectedOption}`);

			if (selectedOption === "Otro") {
				field_contacto_otro.disabled = false;
				field_contacto_otro.style.backgroundColor = ("background-color", "#ffffff");
			} else {
				field_contacto_otro.disabled = true;
				field_contacto_otro.style.backgroundColor = ("background-color", "#80808030");
				console.log(field_contacto_otro);
				console.log(field_contacto_otro.value);
				
			}
		});












	}
});